<?php
/**
 * Contrôleur principal de l'application
 * 
 * PHP versions 4 and 5
 *
 * KoéZionCMS : PHP OPENSOURCE CMS (http://www.koezion-cms.com)
 * Copyright KoéZionCMS
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	KoéZionCMS
 * @link        http://www.koezion-cms.com
 */
class AppController extends Controller {   

//////////////////////////////////////////////////////////////////////////////////////////
//										VARIABLES										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Variable contenant le nombre d'éléments à afficher par page
 *
 * @var 	integer
 * @access 	public
 * @author 	KoéZionCMS
 * @version 0.1 - 21/05/2012 by FI
 */
	var $backofficeElementPerPage = 50;	
	
//////////////////////////////////////////////////////////////////////////////////////////	
//										KOEZION											//
//////////////////////////////////////////////////////////////////////////////////////////

/**
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 25/04/2012 by FI - Rajout de la gestion de la page d'accueil
 * @version 0.3 - 30/04/2012 by FI - Gestion multisites
 * @version 0.4 - 14/06/2012 by FI - Rajout d'un contrôle nécessaire si aucun site n'est retrouné on affiche le formulaire de connexion
 * @see Controller::beforeFilter()
 * @todo améliorer la récupération des configs...
 * @todo améliorer la récupération du menu général pour le moment une mise en cache qui me semble améliorable...
 */	
	function beforeFilter() {
		
		parent::beforeFilter();
				
    	$prefix = isset($this->request->prefix) ? $this->request->prefix : ''; //Récupération du préfixe
    	    	
    	//Si on est dans le backoffice    	
		if($prefix == 'backoffice') {
			
			$adminRole = Session::getRole(); //Récupération du rôle de l'utilisateur connecté			
			if(!Session::isLogged() && !$adminRole) { $this->redirect('users/login'); } //Si pas loggé ou que l'on ne récupère pas de rôle			
			$this->_check_acls($adminRole); //Contrôle des droits utilisateurs
			
			define('IS_USER_LOGGED', 'ok');
			
			//Récupération de l'identifiant du site courant
			$currentWebsite = Session::read('Backoffice.Websites.current');
			define('CURRENT_WEBSITE_ID', $currentWebsite);
			
			$this->layout = 'backoffice'; //Définition du layout pour le backoffice			
			$this->pager['elementsPerPage'] = $this->backofficeElementPerPage; //Nombre d'élément par page

			$leftMenus = $this->_get_backoffice_menu();			
			$this->set('leftMenus', $leftMenus);
			
			//Récupération des formulaires de contacts non validés
			$this->loadModel('Contact');
			$nbFormsContacts = $this->Contact->findCount(array('online' => 0));
			$this->set('nbFormsContacts', $nbFormsContacts);
			
			//Récupération des commentaires articles
			$this->loadModel('PostsComment');
			$nbPostsComments = $this->PostsComment->findCount(array('online' => 0));
			$this->set('nbPostsComments', $nbPostsComments);
			
			//Récupération des plugins
			/*$this->loadModel('Plugin');
			$activatePlugins = $this->Plugin->find(array('conditions' => array('online' => 1)));
			pr($activatePlugins);
			$this->set('activatePlugins', $activatePlugins);*/
			
		//Si on est dans le frontoffice			
		} else {
			
			//////////////////////////////////////////////////
			//   RECUPERATION DES DONNEES DU SITE COURANT   //
			$datas['websiteParams'] = $this->_get_website_datas();
			
			//Dans tous les cas sauf si on est sur le formulaire de connexion
			if($this->params['controllerName'] != 'Users' && ($this->request->action != 'login' || $this->request->action != 'logout')) {
				
				//Si aucun site trouvé on affiche la connexion
				$datas['websiteParams'] = $this->_get_website_datas();				
				if(empty($datas['websiteParams'])) { $datas['websiteParams']['secure_activ'] = 1; } //Si aucun site n'est retourné on affiche le formulaire de connexion
				//////////////////////////////////////////////////
				
				//////////////////////////////////////////////
				//   GESTION DES EVENTUELLES REDIRECTIONS   //				
				$this->_is_secure_activ($datas['websiteParams']['secure_activ'], $datas['websiteParams']['log_users_activ']); //Site sécurisé
				//////////////////////////////////////////////
				
				//////////////////////////////////////////////////////////
				//   MISE EN CACHE DE LA RECUPERATION DU MENU GENERAL   //
				$datas['menuGeneral'] = $this->_get_website_menu($datas['websiteParams']['id']);				
				//////////////////////////////////////////////////////////				
			}
			
			//ON VA DEFINIR LA CONSTANTE D'ACCES AUX VUES DU TEMPLATE//
			define('LAYOUT_VIEWS', WEBROOT.DS.'templates'.DS.$datas['websiteParams']['tpl_layout'].DS.'views');
			$this->set($datas);
		}		
		
		//////////////////////////////////
		//   GESTION DE LA PAGINATION   //
		if(isset($this->request->currentPage)) {
			
			$this->pager['currentPage'] = $this->request->currentPage; //Page courante
			$this->pager['limit'] = $this->pager['elementsPerPage'] * ($this->pager['currentPage'] - 1); //Limit
		}
		//////////////////////////////////
    }
    
    function beforeRender() {
    	
    	parent::beforeRender();
    	
    	$prefix = isset($this->request->prefix) ? $this->request->prefix : ''; //Récupération du préfixe
    	
       	//Si on est dans le backoffice
    	if($prefix == 'backoffice' || ($this->params['controllerName'] == 'Users' && $this->params['action'] == 'login')) {
    		
    		$this->_delete_cache();
    		
    		//Gestion de la variable de session
    		$datas['flashMessage'] = Session::read('Flash');
    		Session::delete('Flash');
    		    		
    		$this->set($datas);
    	} 
    }
    
//////////////////////////////////////////////////////////////////////////////////////////	
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet l'affichage de la liste des éléments
 * 
 * @param 	boolean $return 	Indique si il faut ou non retourner les données récupérées
 * @param 	array 	$fields 	Indique les champs à récupérer dans la requête
 * @param 	varchar $order 		Tri des résultats
 * @param 	array	$conditions Conditions de recherche par défaut
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 09/03/2012 by FI - Mise en place de la variable $fields
 * @version 0.3 - 29/05/2012 by FI - Mise en place de la variable $order
 * @version 0.4 - 11/12/2013 by FI - Mise en place de la variable $conditions
 */    
    function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) {
    	    	
    	$controllerVarName =  $this->params['controllerVarName']; //On récupère la valeur de la variable du contrôleur
    	$modelName =  $this->params['modelName']; //On récupère la valeur du modèle
    	$primaryKey = $this->$modelName->primaryKey;

    	$tableShema = $this->$modelName->shema();
    	if(in_array('order_by', $tableShema)) { $orderBy = 'order_by ASC, name ASC'; } else { $orderBy = $primaryKey.' ASC'; }
    	
    	$findConditions = array('conditions' => $conditions, 'fields' => $fields, 'order' => $orderBy);    	
    	
    	if(isset($this->request->data['Search'])) {
    	
    		$searchConditions = array();
    		foreach($this->request->data['Search'] as $k => $v) {
    			
    			if(trim($v) != "") { //Système de poulie lié au fait que empty(0) retourne faux
    				
    				if($k == 'id') { $searchConditions[] = $k."='".$v."'"; }
    				else { $searchConditions[] = $k." LIKE '%".$v."%'"; }
    			}
    		}
    	
    		if(count($searchConditions) > 0) { $this->searchConditions = $searchConditions; }
    	}
    	
    	$datas['displayAll'] = false;
    	if(!isset($this->request->data['displayall'])) { $findConditions['limit'] = $this->pager['limit'].', '.$this->pager['elementsPerPage']; } else { $datas['displayAll'] = true; }	
    	if(isset($order)) { $findConditions['order'] = $order; }
    	
    	if(isset($this->searchConditions)) { 
    		
    		if(!empty($findConditions['conditions'])) { $findConditions['conditions'] = am($findConditions['conditions'], $this->searchConditions); }
    		else { $findConditions['conditions'] = $this->searchConditions; } 
    	}
    	
    	$datas[$controllerVarName] = $this->$modelName->find($findConditions);    	
    	   	
    	//////////////////////////////////
		//   GESTION DE LA PAGINATION   //
    	if(isset($findConditions['conditions'])) { $this->pager['totalElements'] = $this->$modelName->findCount($findConditions['conditions']); }
    	else { $this->pager['totalElements'] = $this->$modelName->findCount(); }
    	//$this->pager['totalElements'] = $this->$modelName->findCount();
    	if(!$datas['displayAll']) { $this->pager['totalPages'] = ceil($this->pager['totalElements'] / $this->pager['elementsPerPage']); }
    	else { $this->pager['totalPages'] = 1; }
    	//////////////////////////////////
    	
    	if($return) { return $datas; }
    	else { $this->set($datas); }
    }  
    
    /*function backoffice_index($return = false, $fields = null, $order = null) {
    	    	
    	$controllerVarName =  $this->params['controllerVarName']; //On récupère la valeur de la variable du contrôleur
    	$modelName =  $this->params['modelName']; //On récupère la valeur du modèle
    	$primaryKey = $this->$modelName->primaryKey;

    	$tableShema = $this->$modelName->shema();
    	if(in_array('order_by', $tableShema)) { $orderBy = 'order_by ASC, name ASC'; } else { $orderBy = $primaryKey.' ASC'; }
    	
    	$findConditions = array('fields' => $fields, 'order' => $orderBy);    	
    	
    	if(isset($this->request->data['Search'])) {
    	
    		$searchConditions = array();
    		foreach($this->request->data['Search'] as $k => $v) {
    			
    			if(trim($v) != "") { //Système de poulie lié au fait que empty(0) retourne faux
    				
    				if($k == 'id') { $searchConditions[] = $k."='".$v."'"; }
    				else { $searchConditions[] = $k." LIKE '%".$v."%'"; }
    			}
    		}
    	
    		if(count($searchConditions) > 0) { $this->searchConditions = $searchConditions; }
    	}
    	
    	$datas['displayAll'] = false;
    	if(!isset($this->request->data['displayall'])) { $findConditions['limit'] = $this->pager['limit'].', '.$this->pager['elementsPerPage']; } else { $datas['displayAll'] = true; }	
    	if(isset($order)) { $findConditions['order'] = $order; }
    	
    	if(isset($this->searchConditions)) { $findConditions['conditions'] = $this->searchConditions; }
    	$datas[$controllerVarName] = $this->$modelName->find($findConditions);    	
    	   	
    	//////////////////////////////////
		//   GESTION DE LA PAGINATION   //
    	if(isset($findConditions['conditions'])) { $this->pager['totalElements'] = $this->$modelName->findCount($findConditions['conditions']); }
    	else { $this->pager['totalElements'] = $this->$modelName->findCount(); }
    	//$this->pager['totalElements'] = $this->$modelName->findCount();
    	if(!$datas['displayAll']) { $this->pager['totalPages'] = ceil($this->pager['totalElements'] / $this->pager['elementsPerPage']); }
    	else { $this->pager['totalPages'] = 1; }
    	//////////////////////////////////
    	
    	if($return) { return $datas; }
    	else { $this->set($datas); }
    }*/    
    
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @param 	boolean $redirect 		Indique si il faut ou non rediriger après traitement
 * @param 	boolean $forceInsert 	Indique si il faut ou non forcer l'enregistrement si l'id est indiqué
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */
    function backoffice_add($redirect = true, $forceInsert = false) {
    
    	$modelName = $this->params['modelName']; //On récupère la valeur du modèle
    	
    	if($this->request->data) { //Si des données sont postées
    		
    		if($this->$modelName->validates($this->request->data)) { //Si elles sont valides
        			
    			$this->$modelName->save($this->request->data, $forceInsert); //On les sauvegarde 			    			
    			Session::setFlash('Le contenu a bien été ajouté'); //Message de confirmation
    			    			
    			if($redirect) {
    				
					$this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); //Redirection sur la page de listing
    			} else {
    				
    				return true;
    			}
    		} else {
    
    			Session::setFlash('Merci de corriger vos informations', 'error'); //On génère le message d'erreur
    		}
    	}
    }    
    
/**
 * Cette fonction permet l'édition d'un élément
 * 
 * @param 	integer $id Identifiant de l'élément à modifier
 * @param 	boolean $redirect 	Indique si il faut ou non rediriger après traitement 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */    
    function backoffice_edit($id, $redirect = true) {
    	    	
    	$modelName =  $this->params['modelName']; //On récupère la valeur du modèle
    	$primaryKey = $this->$modelName->primaryKey;
    	
    	$this->set($primaryKey, $id); //On stocke l'identifiant dans une variable

    	//Si des données sont postées
    	if($this->request->data) {
    
    		//Si elles sont valides
    		if($this->$modelName->validates($this->request->data)) {
    
    			$this->$modelName->save($this->request->data); //On les sauvegarde    			
    			Session::setFlash('Le contenu a bien été modifié'); //Message de confirmation
    			
    			if($redirect) {
    				
    				$this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); //On retourne sur la page de listing
    			} else {
    				
    				return true;
    			}
    		} else {
    
    			Session::setFlash('Merci de corriger vos informations', 'error'); //On stocke le message d'erreur
    		}
    		
    	//Si aucune donnée n'est postée cela veut dire que c'est le premier passage on va donc récupérer les informations de l'élément
    	} else {
    
    		$findConditions = array('conditions' => array($primaryKey => $id));
    		$this->request->data = $this->$modelName->findFirst($findConditions);
    	}
    }
    
/**
 * Cette fonction permet la suppression d'un élément
 * 
 * @param 	integer $id 		Identifiant de l'élément à supprimer 
 * @param 	boolean $redirect 	Indique si il faut ou non rediriger après traitement 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 16/04/2012 by FI - Mise en place d'un test supplémentaire pour savoir si l'élément est suppressible ou non
 */   
    function backoffice_delete($id, $redirect = true) {    	
    	
    	$this->auto_render = false;
    	$modelName =  $this->params['modelName']; //On récupère la valeur du modèle
    	$primaryKey = $this->$modelName->primaryKey;
    	
    	$findConditions = array('conditions' => array($primaryKey => $id));
    	$element = $this->$modelName->findFirst($findConditions);
    	
    	if(!isset($element['is_deletable']) || (isset($element['is_deletable']) && $element['is_deletable'])) {
    	    		
	    	$this->$modelName->delete($id); //Suppression de l'élément	    	
	    	
	    	$this->_check_cache_configs();
	    	$this->_delete_cache();
	    	
	    	Session::setFlash('Le contenu a bien été supprimé'); //Message de confirmation
	    	if($redirect) { $this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); } //Redirection 
	    	else { return true; }
    	} else {
    		
    		Session::setFlash('Impossible de supprimer cet élément', 'error'); //Message de confirmation
    		if($redirect) { $this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); } //Redirection
    		else { return false; }
    	}
    }    
    
/**
 * Cette fonction permet la suppression massive d'éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 26/01/2012 by FI
 */    
    function backoffice_massive_delete() {
    	
    	$this->auto_render = false;
    	
    	//Gestion de la suppression massive depuis la page d'index
    	//Si on a des données postées de type delete
    	if(isset($this->request->data['delete'])) {
    		
    		//On va les parcourir
    		foreach($this->request->data['delete'] as $k => $v) {
    	
    			//Si on souhaite les supprimer
    			if($v) { $this->backoffice_delete($k, false); }
    		}
    	
    		Session::setFlash('Le contenu a bien été supprimé'); //Message de confirmation
    	} 	
    	
    	$this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); //Redirection
    }
    
/**
 * Cette fonction permet la mise à jour du statut d'un élement directement depuis le listing
 *
 * @param 	integer $id Identifiant de l'élément dont le statut doit être modifié
 * @param 	boolean $redirect 	Indique si il faut ou non rediriger après traitement
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 */
    function backoffice_statut($id, $redirect = true) {
    
    	$this->auto_render = false; //Pas de vue    	
    	$modelName =  $this->params['modelName']; //On récupère la valeur du modèle    	
    	//pr($modelName);
    	$primaryKey = $this->$modelName->primaryKey;
    	$element = $this->$modelName->findFirst(array('conditions' => array($primaryKey => $id))); //Récupération de l'élément
    	$online = $element['online']; //Récupération de la valeur actuelle du champ online
    	$newOnline = abs($online-1); //On génère la nouvelle valeur du champ online
    	$sql = 'UPDATE '.$this->$modelName->table.' SET online = '.$newOnline.' WHERE '.$primaryKey.' = '.$id; //On construit la requête à effectuer
    	$this->$modelName->query($sql); //On lance la requête
    	Session::setFlash('Le statut a bien été modifié'); //Message de confirmation
    	
    	$this->_check_cache_configs();
    	$this->_delete_cache();    	
    	
    	if($redirect) { $this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); } //On retourne sur la page de listing
    	else { 
    		
    		//Dans le cas ou on ne redirige pas on va envoyer la nouvelle valeur du champ online
    		//Cette donnée sera utilisée par le contrôleur Categories
    		$this->set('newOnline', $newOnline);
    		return true; 
    	}
    }     

/**
 * Cette fonction permet la reconstruction de l'index de recherche
 * Fonction utile pour l'administrateur du site
 * Elle n'est, volontairement, pas accessible dans le menu
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/03/2012 by FI
 */
    function backoffice_rebuild_search_index() {    	
    	
    	set_time_limit(0);
    	ini_set("memory_limit" , "256M");
    	
    	$this->auto_render = false;    	
    	$modelName =  $this->params['modelName']; //On récupère la valeur du modèle    	    	
    	$datas = $this->$modelName->find(); //On va récupérer l'ensemble des données du modèle
    	
    	foreach($datas as $k => $v) { $this->$modelName->make_search_index($v, $v['id']); } //Reconstruction de l'index
    	
    	$this->$modelName->optimize_search_index(); //Optimisation de l'index
    	
    	$this->_check_cache_configs();
    	$this->_delete_cache();
    	
    	Session::setFlash('Index du moteur de recherche reconstruit'); //Message de confirmation
    	$this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); //Redirection
    }	
	
//////////////////////////////////////////////////////////////////////////////////////
//										AJAX										//
//////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction est utilisée par l'éditeur de texte pour afficher la liste des liens disponibles
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/02/2012 by FI
 * @version 0.2 - 07/03/2012 by FI - Rajout de la récupération des types de posts
 * @version 0.3 - 14/03/2012 by FI - Rajout de la récupération des rédacteurs et des dates de parution
 * @version 0.4 - 16/05/2012 by FI - Modification de la récupération des catégories suite à la mise en place de la gestion des sites
 * @version 0.5 - 25/02/2013 by FI - Fonction déplacée de CategoriesController vers AppController
 * @version 0.6 - 27/02/2013 by FI - Mise en place d'une procédure automatisée pour la récupération des liens des plugins à intégrer dans l'éditeur (Thanks to Pierstoval) 
 */	
	public function backoffice_ajax_ckeditor_get_internal_links() {
				
		$this->layout = 'ajax'; //Définition du layout à utiliser
		
		///Récupération de toutes les catégories et envoi des données à la vue
		$this->loadModel('Category'); //Chargement du model
		$categories = $this->Category->getTree(array('conditions' => 'type != 3'));
		$this->set('categories', $categories);
		//$this->unloadModel('Category'); //Déchargement du model
		
		//Récupération de tous les articles et envoi des données à la vue
		$this->loadModel('Post'); //Chargement du model
		$posts = $this->Post->find();
		$this->set('posts', $posts);
		//$this->unloadModel('Post'); //Déchargement du model
		
		/////////////////////////////////////////////////////////////////////////////////////////
		//   REGLES ADDITIONNELLES POUR LA RECUPERATION DES LIENS A GENERER POUR LES PLUGINS   //
		$moreLinks = CONFIGS.DS.'plugins'.DS.'ckeditor'.DS.'get_links';
		if(is_dir($moreLinks)) {
		
			foreach(FileAndDir::directoryContent($moreLinks) as $moreLink) { require_once($moreLinks.DS.$moreLink); }
		}
		/////////////////////////////////////////////////////////////////////////////////////////		
		
		/*//Gestion des liens pour les plugins
		if(isset($this->plugins['Flipbooks'])) {
			
			//Récupération de tous les flipbooks et envoi des données à la vue
			$this->loadModel('Flipbook'); //Chargement du model
			$flipbooks = $this->Flipbook->find();
			$this->set('flipbooks', $flipbooks);			
		}*/
		
		$this->render('/elements/ajax/backoffice_ajax_ckeditor_get_internal_links');

		/*
		//Récupération de tous les types d'articles et envoi des données à la vue
		$this->loadModel('PostsType'); //Chargement du model
		$postsTypes = $this->PostsType->find();
		$this->set('postsTypes', $postsTypes);
		$this->unloadModel('PostsType'); //Déchargement du model
		
		//Récupération de tous les utilisateurs (Rédacteurs)
		$this->loadModel('User'); //Chargement du model
		$writers = $this->User->findList();
		$this->set('writers', $writers);
		$this->unloadModel('User'); //Déchargement du model
		
		//Récupération des dates de publication
		$publicationDates = $this->Category->query("SELECT DISTINCT(STR_TO_DATE(CONCAT(YEAR(modified), '-', MONTH(modified)), '%Y-%m')) AS publication_date FROM posts", true);
		$this->set('publicationDates', $publicationDates);
		*/
	}
	
/**
 * Cette fonction est utilisée par l'éditeur de texte pour récupérer le chemin de base des css de l'application
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/01/2013 by FI
 */
	public function backoffice_ajax_get_css_editor() {
	
		$this->layout = 'ajax'; //Définition du layout à utiliser
		
		$currentWebsiteId = Session::read("Backoffice.Websites.current");
		$websiteLayout = Session::read("Backoffice.Websites.details.".$currentWebsiteId.".tpl_layout");
		$websiteLayoutCode = Session::read("Backoffice.Websites.details.".$currentWebsiteId.".tpl_code");
		
		$this->set('baseUrl', BASE_URL);
		$this->set('websiteLayout', $websiteLayout);
		$this->set('websiteLayoutCode', $websiteLayoutCode);
		$this->render('/elements/ajax/backoffice_ajax_get_css_editor');
	}
	
/**
 * Cette fonction est utilisée par l'éditeur de texte pour récupérer le chemin de base de l'application
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/01/2013 by FI
 */
	public function backoffice_ajax_get_baseurl() {
	
		$this->layout = 'ajax'; //Définition du layout à utiliser		
		
		$currentWebsiteId = Session::read("Backoffice.Websites.current");
		$websiteLayout = Session::read("Backoffice.Websites.details.".$currentWebsiteId.".tpl_layout");
		$websiteLayoutCode = Session::read("Backoffice.Websites.details.".$currentWebsiteId.".tpl_code");
		
		$this->set('baseUrl', BASE_URL);
		$this->set('websiteLayout', $websiteLayout);
		$this->render('/elements/ajax/backoffice_ajax_get_baseurl');
	}		
    
/**
 * Cette fonction permet la mise à jour du champ order_by dans la base de données
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 31/05/2012 by FI
 * @version 0.2 - 01/08/2012 by FI - Modification de la requête on passe par un query au lieu d'un save
 */
    function backoffice_ajax_order_by() {
    	
    	$this->auto_render = false; //On ne fait pas de rendu de la vue
    	$modelName =  $this->params['modelName']; //Récupération du nom du modèle    	
    	$primaryKey = $this->$modelName->primaryKey;
    	$modelTable =  $this->$modelName->table; //Récupération du nom de la table
    	$datas = $this->request->data; //Récupération des données
    	
    	$sql = ""; //Requête sql qui sera exécutée
    	foreach($datas['ligne'] as $position => $id) { $sql .= "UPDATE ".$modelTable." SET order_by = ".$position." WHERE ".$primaryKey." = ".$id."; "."\n"; } //Construction de la requête
    	$this->$modelName->query($sql); //Exécution de la requête
    	
    	$this->_check_cache_configs();
    	$this->_delete_cache();
    }			
		
/**
 * Cette fonction est utilisée lors de l'ajout d'un nouveau bouton dans la colonne de droite
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/12/2012 by FI
 * @version 0.2 - 23/12/2013 by FI - Fonction déplacée des contrôleurs Categories et Posts car identique
 */
	public function backoffice_ajax_add_right_button($rightButtonId) {
	
		$this->layout = 'ajax'; //Définition du layout à utiliser		
				
		//Récupération des informations du bouton
		$this->loadModel('RightButton'); //Chargement du modèle
		$rightButton = $this->RightButton->findFirst(array('fields' => array('name'), 'conditions' => array('id' => $rightButtonId))); //On récupère les données
		$this->unloadModel('RightButton'); //Déchargement du modèle
		
		$this->set('rightButtonId', $rightButtonId);
		$this->set('rightButtonName', $rightButton['name']);
		
		$this->render('/elements/ajax/backoffice_ajax_add_right_button');
	}

//////////////////////////////////////////////////////////////////////////////////////////
//									FONCTIONS PRIVEES									//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction permet la récupération des données du site courant
 *
 * @return 	array Données du site Internet
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 * @version 0.2 - 14/06/2012 by FI - Modification de la récupération du site pour la boucle locale - On récupère le premier site de la liste et plus celui avec l'id 1 pour éviter les éventuelles erreurs
 * @version 0.3 - 04/09/2012 by FI - Mise en place d'un passage de paramètre en GET pour pouvoir changer de site en local
 */
	protected function _get_website_datas() {
		
		$httpHost = $_SERVER["HTTP_HOST"]; //Récupération de l'url
  	
    	$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Websites'.DS;
		$cacheFile 		= $httpHost;	
		
		$website = Cache::exists_cache_file($cacheFolder, $cacheFile);	
    	
    	if(!$website) { 
	
			$this->loadModel('Website'); //Chargement du modèle
		
			//HACK SPECIAL LOCAL POUR CHANGER DE SITE pour permettre la passage de l'identifiant du site en paramètre
			if(isset($_GET['hack_current_website_id'])) { Session::write('Frontoffice.hack_current_website_id', $_GET['hack_current_website_id']); }
			$hackCurrentWebsiteId = Session::read('Frontoffice.hack_current_website_id');
			
			if($httpHost == 'localhost' || $httpHost == '127.0.0.1') {
						
				if($hackCurrentWebsiteId) { $websiteId = $hackCurrentWebsiteId; }	
				else {
		
					$websites = $this->Website->findList(array('order' => 'id ASC'));
					$websiteId = current(array_keys($websites));
				}
		
				$websiteConditions = array('conditions' => array('id' => $websiteId, 'online' => 1));
		
			} else { 
				
				if($hackCurrentWebsiteId) { $websiteConditions = array('conditions' => array('id' => $hackCurrentWebsiteId, 'online' => 1)); } 
				else { $websiteConditions = array('conditions' => "url LIKE '%".$_SERVER['HTTP_HOST']."%' AND online = 1"); }
			}
		
			$website = $this->Website->findFirst($websiteConditions);
		
    		Cache::create_cache_file($cacheFolder, $cacheFile, $website);
    	}
    	
    	if(!defined('CURRENT_WEBSITE_ID')) { define('CURRENT_WEBSITE_ID', $website['id']); }	
		$this->layout = $website['tpl_layout'];	
		return $website;
	}
    
/**
 * Cette fonction permet de vérifier si le site courant est sécurisé ou pas
 *
 * @param 	integer $isSecure 			Si vrai alors le site est sécurisé
 * @param 	integer $isLogUsersActiv 	Si vrai alors la mise en place du log des utilisateurs sera activée
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 03/05/2012 by FI
 * @version 0.2 - 05/06/2012 by FI - Changement de la gestion de la sécurité du frontoffice, on test maintenant que l'utilisateur puisse avoir accès au site courant (via son groupe) 
 * @version 0.3 - 13/06/2012 by FI - Reprise des contrôles effectués pour savoir si le site est sécurisé 
 * @version 0.4 - 23/07/2012 by FI - Rajout du log des utilisateurs 
 * @todo Essayer d'alléger les if/else en cascade 
 */   
    
    protected function _is_secure_activ($isSecure, $isLogUsersActiv) {
    	
    	//Si le site est sécurisé on va procéder à quelques contrôles
    	//On évite le contrôleur Errors volontairement pour permettre l'affichage des erreurs
   		if($isSecure && $this->params['controllerName'] != "Errors") { 
   			
   			$session = Session::read('Frontoffice');
   			
   			$redirectConnect = false;
   			
   			//Si la session n'existe pas
   			if(!isset($session['AuthWebsites'])) { $redirectConnect = true; }
   			 
   			//Si la session existe
   			else { 
   				
   				//Et qu'elle n'est pas vide
   				if(!empty($session['AuthWebsites'])) { 
   					
   					//Si l'identifiant du site courant ne figure pas dans la session
   					if(!in_array(CURRENT_WEBSITE_ID, $session['AuthWebsites'])) { $redirectConnect = true; }   					
   					
   				//Si la session est vide
   				} else { $redirectConnect = true; }   				
   			} 
   		
   			if($redirectConnect) { $this->redirect('users/login'); }
   		
	   		//Mise en place du log utilisateurs
	   		if($isLogUsersActiv) {
	   			
	   			$type = 1;
	   			if($this->params['controllerName'] == "Errors") { $type = 2; }
	   			
	   			$this->loadModel('UsersLog');
	   			$logDatas = array(
	   				'url' => $_SERVER['REQUEST_URI'],
	   				'date' => date('Y-m-d H:i:s'),
	   				'type' => $type,
	   				'user_id' => $session['User']['id'],
	   				'website_id' => CURRENT_WEBSITE_ID
	   			);
	   			$this->UsersLog->save($logDatas);   			
	   			$this->unloadModel('UsersLog');   			
	   		}
   		}
    }  
    
/**
 * Cette fonction permet de récupérer le menu
 *
 * @param 	integer $websiteId Identifiant du site Internet
 * @return 	array 	Liste des catégories
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 03/05/2012 by FI
 */       
    protected function _get_website_menu($websiteId) {
    	
    	$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS;
    	$cacheFile 		= "website_menu_".$websiteId;
    	
    	$menuGeneral = Cache::exists_cache_file($cacheFolder, $cacheFile);
    	
    	if(!$menuGeneral) {
    	
    		//Récupération du menu général
    		$this->loadModel('Category');
    		$req = array('conditions' => array('online' => 1, 'type' => 1));
    		$menuGeneral = $this->Category->getTreeRecursive($req);
    		
    		Cache::create_cache_file($cacheFolder, $cacheFile, $menuGeneral);
    	}
    	
    	return $menuGeneral;
    }       
    
/**
 * Cette fonction permet le contrôle et l'envoi des formulaires de contact
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/08/2012 by FI
 * @version 0.2 - 27/06/2013 by FI - Correction sur la gestion de l'élément suite au changement dans la gestion des templates plus nettoyage des données
 */    
    protected function _send_mail_contact() {
		    	
    	if(isset($this->request->data['type_formulaire']) && $this->request->data['type_formulaire'] == 'contact') { //Si le formulaire de contact est posté  		
    		
			$this->loadModel('Contact');
			if($this->Contact->validates($this->request->data)) { //Si elles sont valides
		
				//Récupération du contenu à envoyer dans le mail
				$vars = $this->get('vars');
				$messageContent = $vars['websiteParams']['txt_mail_contact'];				
				$tplLayout = $vars['websiteParams']['tpl_layout'];
				
				if(defined('LAYOUT_VIEWS')) { $emailElement = LAYOUT_VIEWS.DS.'elements'.DS.'email'.DS.'contact'; }
				else { $emailElement = ELEMENTS.DS.'email'.DS.'default'; }
				
				$this->request->data = Sanitize::clean($this->request->data, array('remove_html' => true)); //Petit nettoyage des données avant envoi et insertion
				
				///////////////////////
				//   ENVOI DE MAIL   //
				$mailDatas = array(
					'subject' => '::Contact::',
					'to' => $this->request->data['email'],
					'element' => $emailElement,
					'vars' => array(
						'formUrl' => $this->request->fullUrl,
						'messageContent' => $messageContent
					)
				);
				$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email
				///////////////////////
		
				////////////////////////////////////////////
				//   SAUVEGARDE DANS LA BASE DE DONNEES   //				
				$this->Contact->save($this->request->data); 
				$message = '<p class="confirmation">Votre demande a bien été prise en compte</p>';
				$messageOk = '<p>Votre demande a bien été prise en compte</p>';
				
				$this->set('message', $message);
				$this->set('messageOk', $messageOk);
				////////////////////////////////////////////
				
				//Si le plugin mailing est installé on va alors procéder à l'ajout 
				if(isset($this->plugins['Mailings'])) {
				
					$this->loadModel('MailingsEmail');
					$this->MailingsEmail->save(array(
						'name' => $this->request->data['name'],
						'email' => $this->request->data['email'],
						'etiquette' => $this->request->data['cpostal']	
					));				
					$this->unloadModel('MailingsEmail');
				}
				
				$this->request->data = false;
				
			} else {
		
				//Gestion des erreurs
				$message = '<p class="error">Merci de corriger vos informations';
				foreach($this->Contact->errors as $k => $v) { $message .= '<br />'.$v; }
				$message .= '</p>';
				$messageKo = '<p>Merci de corriger vos informations';
				foreach($this->Contact->errors as $k => $v) { $messageKo .= '<br />'.$v; }
				$messageKo .= '</p>';
				
				$this->set('message', $message);
				$this->set('messageKo', $messageKo);
			}
			
			//$this->unloadModel('Contact');
		}
    }      
    
/**
 * Cette fonction permet le contrôle et l'envoi des formulaires de commentaires
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/08/2012 by FI
 * @version 0.2 - 27/06/2013 by FI - Correction sur la gestion de l'élément suite au changement dans la gestion des templates plus nettoyage des données
 */      
    protected function _send_mail_comments() {
    	    	
    	//////////////////////////////////////////
    	//   GESTION DU DEPOT DE COMMENTAIRES   //
    	if(isset($this->request->data['type_formulaire']) && $this->request->data['type_formulaire'] == 'comment') {
    		
    		//pr('dans _send_mail_comments de app');
    		$this->loadModel('PostsComment'); //Chargement du modèle
    		if($this->PostsComment->validates($this->request->data)) { //Si elles sont valides
    	
    			//Récupération du contenu à envoyer dans le mail
    			$vars = $this->get('vars');
    			$messageContent = $vars['websiteParams']['txt_mail_comments'];				
				$tplLayout = $vars['websiteParams']['tpl_layout'];
				
				if(defined('LAYOUT_VIEWS')) { $emailElement = LAYOUT_VIEWS.DS.'elements'.DS.'email'.DS.'commentaire'; } 
				else { $emailElement = ELEMENTS.DS.'email'.DS.'default'; }
				
				$this->request->data = Sanitize::clean($this->request->data, array('remove_html' => true)); //Petit nettoyage des données avant envoi et insertion
    			
    			///////////////////////
    			//   ENVOI DE MAIL   //
    			$mailDatas = array(
	    			'subject' => '::Commentaire::',
	    			'to' => $this->request->data['email'],
	    			'element' => $emailElement,
					'vars' => array(
						'formUrl' => $this->request->fullUrl,						
						'messageContent' => $messageContent
					)
    			);
    			$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email
    			///////////////////////
		
				////////////////////////////////////////////
				//   SAUVEGARDE DANS LA BASE DE DONNEES   //
    			$this->request->data['post_id'] = $vars['post']['id'];
    			$this->PostsComment->save($this->request->data);
    			$message = '<p class="confirmation">Votre commentaire a bien été prise en compte, il sera diffusé après validation par notre modérateur</p>';
    			$messageOk = '<p>Votre commentaire a bien été prise en compte, il sera diffusé après validation par notre modérateur</p>';
    			
    			$this->set('message', $message);
    			$this->set('messageOk', $messageOk);
				////////////////////////////////////////////
				
				//Si le plugin mailing est installé on va alors procéder à l'ajout 
				if(isset($this->plugins['Mailings'])) {
				
					$this->loadModel('MailingsEmail');
					$this->MailingsEmail->save(array(
						'name' => $this->request->data['name'],
						'email' => $this->request->data['email'],
						'etiquette' => $this->request->data['cpostal']	
					));				
					$this->unloadModel('MailingsEmail');
				}				
				
				$this->request->data = false;
				
    		} else {
    	
    			$message = '<p class="error">Merci de corriger vos informations';
    			foreach($this->PostsComment->errors as $k => $v) { $message .= '<br />'.$v; }
    			$message .= '</p>';
    			$messageKo = '<p>Merci de corriger vos informations';
    			foreach($this->PostsComment->errors as $k => $v) { $messageKo .= '<br />'.$v; }
    			$messageKo .= '</p>';
    			
    			$this->set('message', $message);
    			$this->set('messageKo', $messageKo);
    		}
    		
    		//$this->unloadModel('PostsComment'); //Déchargement du modèle
    	}
    	//////////////////////////////////////////
    }
    
    protected function _delete_cache() {
    	
    	if(isset($this->cachingFiles)) {
    		
    		foreach($this->cachingFiles as $file) { 
    			
    			if(FileAndDir::dexists($file)) { Cache::delete_cache_directory($file); }
    			else if(FileAndDir::fexists($file)) { FileAndDir::remove($file); }
    			
    		}
    	}    	
    }
    
/**
 * Cette fonction permet le contrôle des droits utilisateurs
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/03/2013 by FI
 */
    protected function _check_acls($adminRole) {      	
    	
    	//Dans le cas d'un utilisateur backoffice (2) 
    	//il faut 'vérouiller' certaines pages dans le cas ou on tape directement l'url dans la barre d'adresse
		if($adminRole > 1) {
									
			//GESTION DU PLUGIN ACLS//
			if(isset($this->plugins['Acls'])) {
			
				$controller = $this->request->controller;
				$action = $this->request->action;
				if(isset($this->request->prefix) && !empty($this->request->prefix)) { $action = $this->request->prefix.'_'.$action; }				
				$this->load_component('Acls', PLUGINS.DS.'acls'.DS.'controllers'.DS.'components');
				$isAuth = $this->components['Acls']->is_auth($controller, $action);
				
			} else {
				
		    	//Contrôleurs à vérouilles
		    	$notAuthControllers = array(
					'Websites',
		    		'Users',
		    		'UsersGroups',
		    		'Plugins',
		    		'Configs',
		    		'Modules',
		    		'ModulesTypes',
		    	);
		    	$controllerName = $this->params['controllerName']; //Contrôleur courant
		    	$isAuth = !in_array($controllerName, $notAuthControllers);//Si le nom du contrôleur est dans la liste, alors c'est qu'il ne doit pas être autorisé
		    	
			}
		    	
			if(!$isAuth) { $this->redirect('backoffice/home/not_auth'); }
		}    	
    } 
    
/**
 * Cette fonction permet la récupération du menu backoffice
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 16/03/2013 by FI
 */
    protected function _get_backoffice_menu() {  
						
		//Récupération des modules
		$this->loadModel('ModulesType'); //Chargement du modèle des types de modules
		$modulesTypes = $this->ModulesType->findList(array('conditions' => array('online' => 1), 'order' => 'order_by ASC')); //On récupère les types de modules
		
		$leftMenus = array();
		foreach($modulesTypes as $k => $v) { $leftMenus[$k] = array('libelle' => $v, 'menus' => array()); }						
		
		$this->loadModel('Module');
		$leftMenuTMP = $this->Module->find(array('conditions' => array('online' => 1), 'order' => 'order_by ASC'));
		foreach($leftMenuTMP as $k => $v) { 
			
			$session = Session::read('Backoffice'); //Récupération des données de la session courante
			
			//GESTION DU PLUGIN ACLS//
			if(isset($this->plugins['Acls']) && $session['UsersGroup']['id'] > 1) {
			
				$moduleControllerName = $v['controller_name'];
				$acls = $session['Acl'];
				if(isset($acls[$moduleControllerName]) && isset($leftMenus[$v['modules_type_id']]) && in_array('backoffice_index', $acls[$moduleControllerName])) {
					
					$name = explode('|', $v['name']);
					if(count($name) == 1) { $leftMenus[$v['modules_type_id']]['menus'][] = $v; }
					else {
						
						$v['name'] = $name[1];
						$leftMenus[$v['modules_type_id']]['menus'][$name[0]][] = $v;
					} 
					//$leftMenus[$v['modules_type_id']]['menus'][] = $v;
				}
			} else {
			
				if(isset($leftMenus[$v['modules_type_id']])) { 
					
					$name = explode('|', $v['name']);
					if(count($name) == 1) { $leftMenus[$v['modules_type_id']]['menus'][] = $v; }
					else {
						
						$v['name'] = $name[1];
						$leftMenus[$v['modules_type_id']]['menus'][$name[0]][] = $v;
					} 
				}
			}
			
			/*//GESTION DU PLUGIN ACLS//
			if(isset($this->plugins['Acls']) && $session['UsersGroup']['id'] > 1) {
			
				$moduleControllerName = $v['controller_name'];
				$acls = $session['Acl'];
				if(isset($acls[$moduleControllerName]) && isset($leftMenus[$v['modules_type_id']]) && in_array('backoffice_index', $acls[$moduleControllerName])) {
					
					 $leftMenus[$v['modules_type_id']]['menus'][] = $v;
				}
			} else {
			
				if(isset($leftMenus[$v['modules_type_id']])) { $leftMenus[$v['modules_type_id']]['menus'][] = $v; }
			}*/ 
		}
		return $leftMenus;    	   	
    } 

/**
 * Cette fonction permet de transformer une date FR en date SQL et inversement
 * 
 * @param 	varchar $mode 	Mode de transformation FR --> SQL ou SQL --> FR
 * @param 	varchar $field 	Champ à tester
 * @param 	array 	$datas 	Si renseigné le test se fera dans cette variable au lieu de $this->request->data 
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 25/10/2012 by FI
 * @version 0.2 - 03/11/2013 by FI - Déplacée du contrôleur posts vers le contrôleur app
 * @version 0.3 - 10/11/2013 by FI - Modification de la fonction pour qu'elle prenne en compte les tableaux avec des index multiples
 * @version 0.4 - 09/12/2013 by FI - Modification du champ et du tableau à tester
 */	
	/*protected function _transform_date($mode, $requestField) {
		
		if($mode == 'fr2Sql') {
			
			//Transformation de la date FR en date SQL
			if(isset($this->request->data[$requestField]) && !empty($this->request->data[$requestField])) {
			
				$dateArray = $this->components['Text']->date_human_to_array($this->request->data[$requestField]);
				$this->request->data[$requestField] = $dateArray['a'].'-'.$dateArray['m'].'-'.$dateArray['j'];
			}
		} else if($mode == 'sql2Fr') {
			
			//Transformation de la date SQL en date FR
			if(isset($this->request->data[$requestField]) && !empty($this->request->data[$requestField])) {
			
				$dateArray = $this->components['Text']->date_human_to_array($this->request->data[$requestField], '-', 'i');
				$this->request->data[$requestField] = $dateArray[2].'.'.$dateArray[1].'.'.$dateArray[0];
			}
		}		
	}*/
	
	protected function _transform_date($mode, $field, $datas = null) {
		
		if(!isset($datas)) { $datasToCheck = $this->request->data; }
		else { $datasToCheck = $datas; }
		
		if($datasToCheck) {
			
			if($mode == 'fr2Sql') {
				
				//Transformation de la date FR en date SQL
				if(!empty($field)) {
				
					$date = Set::classicExtract($datasToCheck, $field);
					if(!empty($date) && $date != 'dd.mm.yy') {
						
						$dateArray = $this->components['Text']->date_human_to_array($date);
						$datasToCheck = Set::insert($datasToCheck, $field, $dateArray['a'].'-'.$dateArray['m'].'-'.$dateArray['j']);
						
					} else {
						
						$datasToCheck = Set::insert($datasToCheck, $field, '');
					}
				}
			} else if($mode == 'sql2Fr') {
				
				//Transformation de la date SQL en date FR
				if(!empty($field)) {
				
					$date = Set::classicExtract($datasToCheck, $field);
					if($date != '') {
						
						$dateArray = $this->components['Text']->date_human_to_array($date, '-', 'i');
						$datasToCheck = Set::insert($datasToCheck, $field, $dateArray[2].'.'.$dateArray[1].'.'.$dateArray[0]);
						
					} else {
						
						$datasToCheck = Set::insert($datasToCheck, $field, 'dd.mm.yy');
						
					}
				}
			}
		}

		if(!isset($datas)) { $this->request->data = $datasToCheck; }
		else { return $datasToCheck; } 
	}
}