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
 * @version 0.5 - 02/04/2015 by FI - Mise en place automatisation de la traduction dans les fonctions ADD et EDIT
 * @version 0.6 - 22/04/2015 by FI - Correction pour tester l'existence de la constante CURRENT_WEBSITE_ID
 * @see Controller::beforeFilter()
 * @todo améliorer la récupération des configs...
 * @todo améliorer la récupération du menu général pour le moment une mise en cache qui me semble améliorable...
 */	
	public function beforeFilter() {
		
		parent::beforeFilter();
				
    	$prefix = isset($this->request->prefix) ? $this->request->prefix : ''; //Récupération du préfixe
    	    	
    	//Si on est dans le backoffice    	
		if($prefix == 'backoffice') {
			
			define('INTERFACE_USED', 'backoffice');
			
			$adminRole = Session::getRole(); //Récupération du rôle de l'utilisateur connecté			
			if(!Session::isLogged() && !$adminRole) { $this->redirect('users/login'); } //Si pas loggé ou que l'on ne récupère pas de rôle			
			$this->_check_acls($adminRole); //Contrôle des droits utilisateurs
			
			define('IS_USER_LOGGED', 'ok');
			
			//Récupération de l'identifiant du site courant
			if(!defined('CURRENT_WEBSITE_ID')) {
				
				$currentWebsite = Session::read('Backoffice.Websites.current');
				define('CURRENT_WEBSITE_ID', $currentWebsite);
			}
			
			$this->layout = 'backoffice'; //Définition du layout pour le backoffice
			
			//ON VA DEFINIR LA CONSTANTE D'ACCES AUX VUES DU TEMPLATE//
			define('BACKOFFICE_VIEWS', WEBROOT.DS.'templates'.DS.BACKOFFICE_TEMPLATE.DS.'views');			
			
			$this->pager['elementsPerPage'] = $this->backofficeElementPerPage; //Nombre d'élément par page

			$leftMenus = $this->_get_backoffice_menu();			
			$this->set('leftMenus', $leftMenus);
			
			//Récupération des formulaires de contacts non validés
			$this->load_model('Contact');
			$nbFormsContacts = $this->Contact->findCount(array('online' => 0));
			$this->set('nbFormsContacts', $nbFormsContacts);
			
			//Récupération des commentaires articles
			$this->load_model('PostsComment');
			$nbPostsComments = $this->PostsComment->findCount(array('online' => 0));
			$this->set('nbPostsComments', $nbPostsComments);
						
			/*
			//SUPPRIME LE 02/04/2015 car cela pose des problème lors de la récupération des données pour les listes déroulantes
			//Toutes les traductions étaient récupérées or nous n'avons besoin que de la données de la langue courante du BO
			/////////////////////////////////////////
			//    PARAMETRAGES DE LA TRADUCTION    //
			$modelName = $this->params['modelName'];
			if(
				in_array($this->params['action'], array('add', 'edit')) && 
				isset($this->$modelName->fieldsToTranslate) && 
				!empty($this->$modelName->fieldsToTranslate)
			) {
				
				//Dans le cas de la fonction add et edit on check si on a dans le modèle des champs à traduire
				//Le cas échéant on paramètre les données du modèle pour récupérer les données traduites
				$this->$modelName->getTranslation 		= false; //A ce niveau la pas besoin de récupérer la traduction de l'élément
				$this->$modelName->getTranslatedDatas 	= true; //Récupération de l'ensemble des données traduites pour affiche le formulaire
			}
			*/
			
			//Récupération des plugins
			/*$this->load_model('Plugin');
			$activatePlugins = $this->Plugin->find(array('conditions' => array('online' => 1)));
			pr($activatePlugins);
			$this->set('activatePlugins', $activatePlugins);*/
			
		//Si on est dans le frontoffice			
		} else {
			
			define('INTERFACE_USED', 'frontoffice');
			
			//////////////////////////////////////////////////
			//   RECUPERATION DES DONNEES DU SITE COURANT   //
			//$datas['websiteParams'] = $this->_get_website_datas();
			$ws = $this->components['Website']->get_website_datas();
			$datas['websiteParams'] = $ws['website'];			
			$this->layout = $ws['layout'];
			
			//Dans tous les cas sauf si on est sur le formulaire de connexion
			if($this->params['controllerName'] != 'Users' && ($this->request->action != 'login' || $this->request->action != 'logout')) {
				
				//Si aucun site trouvé on affiche la connexion
				//$datas['websiteParams'] = $this->_get_website_datas();
				//$ws = $this->components['Website']->get_website_datas();
				//$datas['websiteParams'] = $ws['website'];			
				//$this->layout = $ws['layout'];
					
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
			define('FRONTOFFICE_VIEWS', WEBROOT.DS.'templates'.DS.$datas['websiteParams']['tpl_layout'].DS.'views');
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
    
    public function beforeRender() {
    	
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
 * @version 0.5 - 18/03/2015 by FI - Paramétrages de la traduction
 * @version 0.6 - 24/03/2015 by FI - Test de l'existence de id ou _id dans les champs du moteur de recherche pour générer les conditions de recherche
 */    
    public function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) {
    	    	
    	$controllerVarName 	=  $this->params['controllerVarName']; //On récupère la valeur de la variable du contrôleur
    	$modelName 			=  $this->params['modelName']; //On récupère la valeur du modèle
    	$primaryKey 		= $this->$modelName->primaryKey;
    	$tableShema 		= $this->$modelName->shema();
    	
    	/////////////////////////////////////////
    	//    PARAMETRAGES DE LA TRADUCTION    //
    	//$this->$modelName->getTranslation 	= true; //Cette donnée est déjà à vrai dans le model principal elle permet de récupérer la traduction de la langue par défaut du BO pour une meilleure compréhension
    	//$this->$modelName->getTranslatedDatas = false; //On ne récupère pas l'ensemble des données traduites dans le listing pour alléger les requêtes
    	
    	if(in_array('order_by', $tableShema)) { $orderBy = 'order_by ASC'; } else { $orderBy = $primaryKey.' ASC'; }
    	
    	$findConditions = array('conditions' => $conditions, 'fields' => $fields, 'order' => $orderBy);    	
    	
    	if(isset($this->request->data['Search'])) {
    	
    		$searchConditions = array();
    		foreach($this->request->data['Search'] as $k => $v) {
    			
    			if(trim($v) != "") { //Système de poulie lié au fait que empty(0) retourne faux
    				
    				if($k == 'id' || substr($k, strlen($k) -3) == '_id') { $searchConditions[] = $k."='".$v."'"; }
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
    	
    	if(!$datas['displayAll']) { $this->pager['totalPages'] = ceil($this->pager['totalElements'] / $this->pager['elementsPerPage']); }
    	else { $this->pager['totalPages'] = 1; }
    	//////////////////////////////////
    	
    	if($return) { return $datas; }
    	else { $this->set($datas); }
    }
    
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @param 	boolean $redirect 		Indique si il faut ou non rediriger après traitement
 * @param 	boolean $forceInsert 	Indique si il faut ou non forcer l'enregistrement si l'id est indiqué
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 02/04/2015 by FI - Gestion de la traduction
 */
    public function backoffice_add($redirect = true, $forceInsert = false) {
    
    	$modelName = $this->params['modelName']; //On récupère la valeur du modèle
    	
    	/////////////////////////////////////////
    	//    PARAMETRAGES DE LA TRADUCTION    //
    	$this->$modelName->getTranslation 		= false; //A ce niveau la pas besoin de récupérer la traduction de l'élément
    	$this->$modelName->getTranslatedDatas 	= true; //Récupération de l'ensemble des données traduites pour affiche le formulaire
    	    	
    	if($this->request->data) { //Si des données sont postées
    		
    		if($this->$modelName->validates($this->request->data)) { //Si elles sont valides
        			
    			$this->$modelName->save($this->request->data, $forceInsert); //On les sauvegarde 			    			
    			Session::setFlash(_('Le contenu a bien été ajouté')); //Message de confirmation
    			    			    			
    			$this->_check_cache_configs();
    			$this->_delete_cache();
    			
    			if($redirect) { $this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); } //Redirection sur la page de listing  
    			else { return true; }
    			
    		} else { Session::setFlash(_('Merci de corriger vos informations'), 'error'); } //On génère le message d'erreur
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
 * @version 0.2 - 02/04/2015 by FI - Gestion de la traduction
 */    
    public function backoffice_edit($id, $redirect = true) {
    	    	
    	$modelName 	=  $this->params['modelName']; //On récupère la valeur du modèle
    	$primaryKey = $this->$modelName->primaryKey;
    	
    	/////////////////////////////////////////
    	//    PARAMETRAGES DE LA TRADUCTION    //
    	$this->$modelName->getTranslation 		= false; //A ce niveau la pas besoin de récupérer la traduction de l'élément
    	$this->$modelName->getTranslatedDatas 	= true; //Récupération de l'ensemble des données traduites pour affiche le formulaire
    	
    	$this->set($primaryKey, $id); //On stocke l'identifiant dans une variable

    	//Si des données sont postées
    	if($this->request->data) {
    
    		//Si elles sont valides
    		if($this->$modelName->validates($this->request->data)) {
    
    			$this->$modelName->save($this->request->data); //On les sauvegarde    			
    			Session::setFlash(_('Le contenu a bien été modifié')); //Message de confirmation
    			    			
    			$this->_check_cache_configs();
    			$this->_delete_cache();
    			
    			if($redirect) { $this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); } //On retourne sur la page de listing 
    			else { return true; }
    			
    		} else { Session::setFlash(_('Merci de corriger vos informations'), 'error'); } //On stocke le message d'erreur
    		     		
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
    public function backoffice_delete($id, $redirect = true) {    	
    	
    	$this->auto_render 	= false;
    	$modelName 			=  $this->params['modelName']; //On récupère la valeur du modèle
    	$primaryKey 		= $this->$modelName->primaryKey;
    	
    	$findConditions = array('conditions' => array($primaryKey => $id));
    	$element 		= $this->$modelName->findFirst($findConditions);
    	
    	if(!isset($element['is_deletable']) || (isset($element['is_deletable']) && $element['is_deletable'])) {
    	    		
	    	$this->$modelName->delete($id); //Suppression de l'élément	    	
	    	
	    	$this->_check_cache_configs();
	    	$this->_delete_cache();
	    	
	    	Session::setFlash(_('Le contenu a bien été supprimé')); //Message de confirmation
	    	if($redirect) { $this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); } //Redirection 
	    	else { return true; }
	    	
    	} else {
    		
    		Session::setFlash(_('Impossible de supprimer cet élément'), 'error'); //Message de confirmation
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
    public function backoffice_massive_delete() {
    	
    	$this->auto_render = false;
    	
    	//Gestion de la suppression massive depuis la page d'index
    	//Si on a des données postées de type delete
    	if(isset($this->request->data['delete'])) {
    		
    		//On va les parcourir
    		foreach($this->request->data['delete'] as $k => $v) {
    	
    			//Si on souhaite les supprimer
    			if($v) { $this->backoffice_delete($k, false); }
    		}
    	
    		Session::setFlash(_('Le contenu a bien été supprimé')); //Message de confirmation
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
    public function backoffice_statut($id, $redirect = true) {
    
    	$this->auto_render 	= false; //Pas de vue    	
    	$modelName 			=  $this->params['modelName']; //On récupère la valeur du modèle
    	$primaryKey 		= $this->$modelName->primaryKey;
    	$element 			= $this->$modelName->findFirst(array('conditions' => array($primaryKey => $id))); //Récupération de l'élément
    	$online 			= $element['online']; //Récupération de la valeur actuelle du champ online
    	$newOnline 			= abs($online-1); //On génère la nouvelle valeur du champ online
    	$sql 				= 'UPDATE '.$this->$modelName->table.' SET online = '.$newOnline.' WHERE '.$primaryKey.' = '.$id; //On construit la requête à effectuer
    	
    	$this->$modelName->query($sql); //On lance la requête
    	Session::setFlash(_('Le statut a bien été modifié')); //Message de confirmation
    	
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
    public function backoffice_rebuild_search_index() {    	
    	
    	set_time_limit(0);
    	ini_set("memory_limit" , "256M");
    	
    	$this->auto_render 	= false;    	
    	$modelName 			=  $this->params['modelName']; //On récupère la valeur du modèle    	    	
    	$datas 				= $this->$modelName->find(); //On va récupérer l'ensemble des données du modèle
    	
    	foreach($datas as $k => $v) { $this->$modelName->make_search_index($v, $v['id']); } //Reconstruction de l'index
    	
    	$this->$modelName->optimize_search_index(); //Optimisation de l'index
    	
    	$this->_check_cache_configs();
    	$this->_delete_cache();
    	
    	Session::setFlash(_('Index du moteur de recherche reconstruit')); //Message de confirmation
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
		$this->load_model('Category'); //Chargement du model
		$categories = $this->Category->getTree(array('conditions' => 'type != 3'));
		$this->set('categories', $categories);
		
		//Récupération de tous les articles et envoi des données à la vue
		$this->load_model('Post'); //Chargement du model
		$posts = $this->Post->find();
		$this->set('posts', $posts);
		
		/////////////////////////////////////////////////////////////////////////////////////////
		//   REGLES ADDITIONNELLES POUR LA RECUPERATION DES LIENS A GENERER POUR LES PLUGINS   //
		$moreLinks = CONFIGS.DS.'plugins'.DS.'ckeditor'.DS.'get_links';
		if(is_dir($moreLinks)) {
		
			foreach(FileAndDir::directoryContent($moreLinks) as $moreLink) { require_once($moreLinks.DS.$moreLink); }
		}
		/////////////////////////////////////////////////////////////////////////////////////////
		
		/*//Récupération de tous les types d'articles et envoi des données à la vue
		$this->load_model('PostsType'); //Chargement du model
		$postsTypes = $this->PostsType->find();
		$this->set('postsTypes', $postsTypes);
		$this->unload_model('PostsType'); //Déchargement du model
		
		//Récupération de tous les utilisateurs (Rédacteurs)
		$this->load_model('User'); //Chargement du model
		$writers = $this->User->findList();
		$this->set('writers', $writers);
		$this->unload_model('User'); //Déchargement du model
		
		//Récupération des dates de publication
		$publicationDates = $this->Category->query("SELECT DISTINCT(STR_TO_DATE(CONCAT(YEAR(modified), '-', MONTH(modified)), '%Y-%m')) AS publication_date FROM posts", true);
		$this->set('publicationDates', $publicationDates);*/
		
		$this->render('/elements/ajax/backoffice_ajax_ckeditor_get_internal_links');
	}
	
/**
 * Cette fonction est utilisée par l'éditeur de texte pour récupérer le chemin de base des css de l'application
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/01/2013 by FI
 */
	public function backoffice_ajax_get_css_editor() {
	
		$this->layout 		= 'ajax'; //Définition du layout à utiliser		
		$currentWebsiteId 	= Session::read("Backoffice.Websites.current");
		$websiteLayout 		= Session::read("Backoffice.Websites.details.".$currentWebsiteId.".tpl_layout");
		$websiteLayoutCode 	= Session::read("Backoffice.Websites.details.".$currentWebsiteId.".tpl_code");
		
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
	
		$this->layout 		= 'ajax'; //Définition du layout à utiliser				
		$currentWebsiteId 	= Session::read("Backoffice.Websites.current");
		$websiteLayout 		= Session::read("Backoffice.Websites.details.".$currentWebsiteId.".tpl_layout");
		$websiteLayoutCode 	= Session::read("Backoffice.Websites.details.".$currentWebsiteId.".tpl_code");
		
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
    public function backoffice_ajax_order_by() {
    	
    	$this->auto_render 	= false; //On ne fait pas de rendu de la vue
    	$modelName 			=  $this->params['modelName']; //Récupération du nom du modèle    	
    	$primaryKey 		= $this->$modelName->primaryKey;
    	$modelTable 		=  $this->$modelName->table; //Récupération du nom de la table
    	$datas 				= $this->request->data; //Récupération des données
    	
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
		$this->load_model('RightButton'); //Chargement du modèle
		$rightButton = $this->RightButton->findFirst(array('fields' => array('name'), 'conditions' => array('id' => $rightButtonId))); //On récupère les données
				
		$this->set('rightButtonId', $rightButtonId);
		$this->set('rightButtonName', $rightButton['name']);
		
		$this->render('/elements/ajax/backoffice_ajax_add_right_button');
	}
	
//////////////////////////////////////////////////////////////////////////////////////
//										PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////	

/**
 * Cette fonction permet la récupération des données de la catégorie courante
 *
 * @param 	integer $id Identifiant de la catégorie
 * @return	array	Tableau contenant les données de la catégorie 
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/10/2012 by FI
 * @version 0.2 - 02/10/2012 by FI - Récupération de tous les champs
 * @version 0.3 - 30/10/2014 by FI - Déplacement de cette fonction de Categories
 * @version 0.4 - 24/04/2015 by FI - Gestion de la traduction
 */	
	protected function _get_datas_category($id) {
				
		$cacheFolder = TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS;
 
		//On contrôle si le modèle est traduit
		$this->load_model('Category');
		if($this->Category->fieldsToTranslate) { $cacheFile = "category_".$id.'_'.DEFAULT_LANGUAGE; } 
		else { $cacheFile = "category_".$id; }
		
		$category = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$category) {
		
			$conditions = array('conditions' => array('online' => 1, 'id' => $id));
			$category = $this->Category->findFirst($conditions);		
			Cache::create_cache_file($cacheFolder, $cacheFile, $category);
		}

		$datas['category'] = $category;
		return $datas;
	}

/**
 * Cette fonction permet la récupération des articles liés à la catégorie courante
 *
 * @param 	array 	$datas 		Tableau des données à passer à la vue
 * @param 	boolean $setLimit 	Indique si il faut mettre en place une limite lors de la recherche
 * @return	array	Tableau de données à passer à la vue 
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/10/2012 by FI
 * @version 0.3 - 30/10/2014 by FI - Déplacement de cette fonction de Categories
 */		
	protected function _get_posts_category($datas, $setLimit = true) {
				
		//On va compter le nombre d'articles de cette catégorie
		$this->load_model('Post');
		$postsConditions = array('online' => 1, 'category_id' => $datas['category']['id']);
		$nbPosts = $this->Post->findCount($postsConditions);
		
		if($nbPosts > 0) {
			
			//On va envoyer les informations nécessaires à la génération du flux RSS
			$datas['rss_for_layout'] = array(
				'title' => $datas['category']['page_title'],	
				'link' => Router::url('posts/rss/'.$datas['category']['id'].'/'.$datas['category']['slug'], 'xml', true),
				'pageLink' => Router::url('categories/view/id:'.$datas['category']['id'].'/slug:'.$datas['category']['slug'], 'html', true)
			);
		
			//////////////////////////////////////////////////////
			//   RECUPERATION DES CONFIGURATIONS DES ARTICLES   //
			require_once(LIBS.DS.'config_magik.php'); 										//Import de la librairie de gestion des fichiers de configuration des posts
			$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'posts.ini', false, false); 		//Création d'une instance
			$postsConfigs = $cfg->keys_values();											//Récupération des configurations
			//////////////////////////////////////////////////////
		
			$datas['displayPosts'] = true;
		
			//Récupération des types d'articles
			$this->load_model('PostsType');
			$datas['postsTypes'] = $this->PostsType->get_for_front($datas['category']['id']);
		
			//Construction des paramètres de la requête
			$postsQuery = array('conditions' => $postsConditions);			
			if($setLimit) { $postsQuery['limit'] = $this->pager['limit'].', '.$this->pager['elementsPerPage']; }
		
			if($postsConfigs['order'] == 'modified') 		{ $postsQuery['order'] = 'modified DESC'; }
			else if($postsConfigs['order'] == 'created') 	{ $postsQuery['order'] = 'created DESC'; }
			else if($postsConfigs['order'] == 'order_by') 	{ $postsQuery['order'] = 'order_by ASC'; }
		
			$postsQuery['moreConditions'] = ''; //Par défaut pas de conditions de recherche complémentaire
		
			$datas['titlePostsList'] = $datas['category']['title_posts_list'];
		
			//////////////////////////////////////////////////////////////////////////
			///  GESTION DES EVENTUELS PARAMETRES PASSES EN GET PAR L'UTILISATEUR   //
			$filterPosts = $this->_filter_posts($datas['postsTypes'], $postsConfigs['search']);
			if(isset($filterPosts['moreConditions'])) {
		
				$postsQuery['moreConditions'] = $filterPosts['moreConditions'];
				unset($filterPosts['moreConditions']);
			}
		
			$datas = am($datas, $filterPosts);
			//////////////////////////////////////////////////////////////////////////
		
			$datas['posts'] = $this->Post->find($postsQuery); //Récupération des articles
		
			//On va compter le nombre d'élement de la catégorie
			//On compte deux fois le nombre de post une fois en totalité une fois en rajoutant si il est renseigné le type d'article
			//Car si on ne faisait pas cela on avait toujours la zone d'affichage des catégories qui s'affichaient lorsqu'on affichait les frères
			//même si il n'y avait pas de post
			$nbPostsCategory = $this->Post->findCount($postsConditions);
		
			$this->pager['totalElements'] 	= $this->Post->findCount($postsConditions, $postsQuery['moreConditions']); //On va compter le nombre d'élement
			$this->pager['totalPages'] 		= ceil($this->pager['totalElements'] / $this->pager['elementsPerPage']); //On va compter le nombre de page
		}

		return $datas;
	}
    
/**
 * Cette fonction permet de récupérer les articles à afficher sur le frontoffice (Dans les contrôleurs Categories et Posts)
 *
 * @param 	array 	$postsTypes Liste des types de posts
 * @param 	varchar $searchType Type de recherche
 * @return 	array 	Configuration de la recherche
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 03/05/2012 by FI
 * @version 0.3 - 30/10/2014 by FI - Déplacement de cette fonction de Categories
 */       
    protected function _filter_posts($postsTypes, $searchType) {
    	
    	$return = array();
    	
    	//Si l'internaute à cliqué sur un type d'article (ou plusieurs)
    	if(isset($this->request->data['typepost']) && !empty($this->request->data['typepost'])) {
    	
    		/////////////////////////////////////////////
    		//   MISE EN PLACE DE LA REQUETE STRICTE   //
    		if($searchType == 'stricte') {
    	
    			$this->load_model('PostsPostsType');
    			$typePost = explode(',', $this->request->data['typepost']); //Récupération des types de post passés en GET
    	
    			$tableAliasBase = 'Kz'.Inflector::camelize('posts_posts_types'); //Définition de la base des alias
    			$sql =  'SELECT DISTINCT '.$tableAliasBase.'.post_id '; //Construction de la requête
    			$sql .= 'FROM posts_posts_types AS '.$tableAliasBase.' '; //Construction de la requête
    	
    			//Parcours de tous les types de posts passés en GET pour mettre en place les INNER JOIN
    			foreach($typePost as $k => $v) {
    	
    				$tableAlias = $tableAliasBase.$k; //Définition de l'alias de la table
    				$sql .= 'INNER JOIN posts_posts_types AS '.$tableAlias.' ON '.$tableAliasBase.'.post_id = '.$tableAlias.'.post_id '; //Construction de la requête
    			}
    	
    			$sql .= 'WHERE 1 '; //Construction de la requête
    	
    			//Parcours de tous les types de posts passés en GET pour mettre en place les conditions de récupération
    			foreach($typePost as $k => $v) {
    	
    				$tableAlias = $tableAliasBase.$k; //Définition de l'alias de la table
    				$sql .= ' AND '.$tableAlias.'.posts_type_id = '.$v; //Construction de la requête
    			}
    	
    	
    			$result = $this->PostsPostsType->query($sql, true);
    			$postsIdIn = array();
    			foreach($result as $k => $v) { $postsIdIn[] = $v['post_id']; }
    	
    			if(count($postsIdIn)) { $return['moreConditions'] = 'KzPost.id IN ('.implode(',', $postsIdIn).')'; }
    			else { $return['moreConditions'] = 'KzPost.id IN (0)'; }
    	
    			///////////////////////////////////////////
    			//   MISE EN PLACE DE LA REQUETE LARGE   //
    		} else if($searchType == 'large') {
    	
    			//Construction de la requête de recherche
    			$return['moreConditions'] = 'KzPost.id IN (SELECT post_id FROM posts_posts_types WHERE posts_type_id';
    			if(is_numeric($this->request->data['typepost'])) { $return['moreConditions'] .= ' = '.$this->request->data['typepost']; } //Si un seul type
    			else { $return['moreConditions'] .= ' IN ('.$this->request->data['typepost'].')'; }	//Si plusieurs types
    			$return['moreConditions'] .= ')';
    		}
    	
    		$typepost = $this->request->data['typepost']; //Récupération des types passés en GET
    		$libellePage = ''; //Par défaut le libellé de la page est vide
    	
    		//Parcours des types de posts
    		foreach($postsTypes as $columnTitle => $postsTypesValues) {
    	
    			$typePost = explode(',', $typepost); //On transforme les types de posts en tableau
    			foreach($postsTypesValues as $k => $v) { //On parcours les types de post
    	
    				//On stocke le libellé du type de post si celui-ci est passé en paramètre
    				if(in_array($k, $typePost)) { $libellePage[] = $v; }
    			}
    		}
    	
    		$return['libellePage'] = 'Articles de la catégorie : '.implode(', ', $libellePage); //Construction du titre de la page
    	
    		//Si l'internaute à cliqué sur un rédacteur
    	} else if(isset($this->request->data['writer']) && is_numeric($this->request->data['writer'])) {
    	
    		$return['moreConditions'] = 'modified_by = '.$this->request->data['writer'];
    	
    		//On va récupérer le libellé de l'utilisateur pour le stocker dans le libellé de la page
    		$this->load_model('User');
    		$user = $this->User->findFirst(array('conditions' => array('id' => $this->request->data['writer'])));
    		$return['libellePage'] = "Articles rédigés par ".$user['name'];
    		$this->unload_model('User');
    	
    		//Si l'internaute à cliqué sur une date
    	} else if(isset($this->request->data['date']) && !empty($this->request->data['date'])) {
    	
    		$date = explode('-', $this->request->data['date']); //Récupération des données sur la date
    		if(isset($date[0]) && is_numeric($date[0]) && isset($date[1]) && is_numeric($date[1])) {
    	
    			$return['moreConditions'] = 'YEAR(modified) = '.$date[0].' AND MONTH(modified) = '.$date[1];
    			$displayDate = $this->components['Text']->date_sql_to_human($this->request->data['date'].'-00');
    			$return['libellePage'] = "Articles rédigés en ".$displayDate['txt'];
    		}
    	}
    	
    	return $return;
    }
	
/**
 * Cette fonction permet de récupérer les sliders
 *
 * @return	array Liste des sliders
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 27/06/2014 by FI
 * @version 0.2 - 24/04/2015 by FI - Gestion de la traduction
 */	
	public function _get_sliders() {
		
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Sliders'.DS;
		
		//On contrôle si le modèle est traduit
		$this->load_model('Slider');
		if($this->Slider->fieldsToTranslate) { $cacheFile = "website_".CURRENT_WEBSITE_ID.'_'.DEFAULT_LANGUAGE; } 
		else { $cacheFile = "website_".CURRENT_WEBSITE_ID; }
		
		$sliders = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$sliders) {
		
			//$this->load_model('Slider');
			$sliders = $this->Slider->find(array(
				'conditions' => array('online' => 1), 
				'order' => 'order_by ASC, name ASC'
			));
		
			Cache::create_cache_file($cacheFolder, $cacheFile, $sliders);
		}
		
		return $sliders;
	}
	
/**
 * Cette fonction permet de récupérer les focus
 *
 * @return	array Liste des focus
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 27/06/2014 by FI
 * @version 0.2 - 24/04/2015 by FI - Gestion de la traduction
 */	
	public function _get_focus() {
		
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Focus'.DS;		
		
		//On contrôle si le modèle est traduit
		$this->load_model('Focus');
		if($this->Focus->fieldsToTranslate) { $cacheFile = "website_".CURRENT_WEBSITE_ID.'_'.DEFAULT_LANGUAGE; } 
		else { $cacheFile = "website_".CURRENT_WEBSITE_ID; }
		
		$focus = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$focus) {
			
			//$this->load_model('Focus');
			$focus = $this->Focus->find(array(
				'conditions' => array('online' => 1), 
				'order' => 'order_by ASC, name ASC'
			));
		
			Cache::create_cache_file($cacheFolder, $cacheFile, $focus);
		}
		
		return $focus;
	}
	
/**
 * Cette fonction permet de récupérer les boutons colonne de droite
 *
 * @param	array Tableau de paramètres
 * @return	array Liste des boutons
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 05/09/2014 by FI
 */	
	public function _get_right_buttons($params) {
						
		$rightButtonsConditions = array(
			'conditions' => array('online' => 1), 
			'order' => 'order_by ASC, name ASC'
		);
		
		if(isset($params['homePage']) && $params['homePage']) { $rightButtonsConditions['conditions']['display_home_page'] = 1; }
		
		$this->load_model('RightButton');
		$rightButtons = $this->RightButton->find($rightButtonsConditions);
		
		return $rightButtons;
	}
	
/**
 * Cette fonction permet de récupérer les articles
 *
 * @return	array Liste des articles
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 27/06/2014 by FI
 * @version 0.2 - 24/04/2015 by FI - Gestion de la traduction
 */	
	public function _get_posts() {
		
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Posts'.DS;
		
		//On contrôle si le modèle est traduit
		$this->load_model('Post');
		if($this->Post->fieldsToTranslate) { $cacheFile = "home_page_website_".CURRENT_WEBSITE_ID.'_'.DEFAULT_LANGUAGE; } 
		else { $cacheFile = "home_page_website_".CURRENT_WEBSITE_ID; }
		
		$posts = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$posts) {	
		
			//////////////////////////////////////////////////////
			//   RECUPERATION DES CONFIGURATIONS DES ARTICLES   //
			require_once(LIBS.DS.'config_magik.php'); 										//Import de la librairie de gestion des fichiers de configuration des posts
			$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'posts.ini', false, false); 		//Création d'une instance
			$postsConfigs = $cfg->keys_values();											//Récupération des configurations
			//////////////////////////////////////////////////////
			
			$postsQuery = array(
				'conditions' => array('online' => 1, 'display_home_page' => 1),
				'limit' => '0, '.$postsConfigs['home_page_limit']
			);	
		
			if($postsConfigs['order'] == 'modified') { $postsQuery['order'] = 'modified DESC'; }
			else if($postsConfigs['order'] == 'created') { $postsQuery['order'] = 'created DESC'; }
			else if($postsConfigs['order'] == 'order_by') { $postsQuery['order'] = 'order_by ASC'; }			
								
			$posts = $this->Post->find($postsQuery);
		
			Cache::create_cache_file($cacheFolder, $cacheFile, $posts);
		}
		
		return $posts;
	}
	
/**
 * Cette fonction permet de récupérer les types d'articles
 *
 * @return	array Liste des types d'articles
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 27/06/2014 by FI
 * @version 0.2 - 24/04/2015 by FI - Gestion de la traduction
 */	
	public function _get_posts_types() {
		
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'PostsTypes'.DS;
		
		//On contrôle si le modèle est traduit
		$this->load_model('PostsType');
		if($this->PostsType->fieldsToTranslate) { $cacheFile = "home_page_website_".CURRENT_WEBSITE_ID.'_'.DEFAULT_LANGUAGE; } 
		else { $cacheFile = "home_page_website_".CURRENT_WEBSITE_ID; }
		
		$postsTypes = Cache::exists_cache_file($cacheFolder, $cacheFile);
		
		if(!$postsTypes) {			
			
			$postsTypes = $this->PostsType->get_for_front();
		
			Cache::create_cache_file($cacheFolder, $cacheFile, $postsTypes);
		}
		
		return $postsTypes;
	}

//////////////////////////////////////////////////////////////////////////////////////////
//									FONCTIONS PRIVEES									//
//////////////////////////////////////////////////////////////////////////////////////////
    
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
	   			
	   			$this->load_model('UsersLog');
	   			$logDatas = array(
	   				'url' => $_SERVER['REQUEST_URI'],
	   				'date' => date('Y-m-d H:i:s'),
	   				'type' => $type,
	   				'user_id' => $session['User']['id'],
	   				'website_id' => CURRENT_WEBSITE_ID
	   			);
	   			$this->UsersLog->save($logDatas);   			
	   			$this->unload_model('UsersLog');   			
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
 * @version 0.2 - 24/04/2015 by FI - Gestion de la traduction
 */       
    protected function _get_website_menu($websiteId) {
    	
    	$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Categories'.DS;
    	
    	//On contrôle si le modèle est traduit
    	$this->load_model('Category');
    	if($this->Category->fieldsToTranslate) { $cacheFile = "website_menu_".$websiteId.'_'.DEFAULT_LANGUAGE; } 
    	else { $cacheFile = "website_menu_".$websiteId; }
    	
    	$menuGeneral = Cache::exists_cache_file($cacheFolder, $cacheFile);
    	
    	if(!$menuGeneral) {
    	
    		//Récupération du menu général
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
    		
			$this->load_model('Contact');
			if($this->Contact->validates($this->request->data)) { //Si elles sont valides
		
				//Récupération du contenu à envoyer dans le mail
				$vars = $this->get('vars');
				$messageContent = $vars['websiteParams']['txt_mail_contact'];				
				$tplLayout = $vars['websiteParams']['tpl_layout'];
				
				if(defined('FRONTOFFICE_VIEWS')) { $emailElement = FRONTOFFICE_VIEWS.DS.'elements'.DS.'email'.DS.'contact'; }
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
				$message = '<p class="confirmation">'._('Votre demande a bien été prise en compte').'</p>';
				$messageOk = '<p>'._('Votre demande a bien été prise en compte').'</p>';
				
				$this->set('message', $message);
				$this->set('messageOk', $messageOk);
				////////////////////////////////////////////
				
				//Si le plugin mailing est installé on va alors procéder à l'ajout 
				if(isset($this->plugins['Mailings'])) {
				
					$this->load_model('MailingsEmail');
					$this->MailingsEmail->save(array(
						'name' => $this->request->data['name'],
						'email' => $this->request->data['email'],
						'etiquette' => $this->request->data['cpostal']	
					));				
					$this->unload_model('MailingsEmail');
				}
				
				$this->request->data = false;
				
			} else {
		
				//Gestion des erreurs
				$message = '<p class="error"><strong>'._('Merci de corriger vos informations').'</strong>';
				foreach($this->Contact->errors as $k => $v) { $message .= '<br />'.$v; }
				$message .= '</p>';
				$messageKo = '<p><strong>'._('Merci de corriger vos informations').'</strong>';
				foreach($this->Contact->errors as $k => $v) { $messageKo .= '<br />'.$v; }
				$messageKo .= '</p>';
				
				$this->set('message', $message);
				$this->set('messageKo', $messageKo);
			}
			
			//$this->unload_model('Contact');
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
    		$this->load_model('PostsComment'); //Chargement du modèle
    		if($this->PostsComment->validates($this->request->data)) { //Si elles sont valides
    	
    			//Récupération du contenu à envoyer dans le mail
    			$vars = $this->get('vars');
    			$messageContent = $vars['websiteParams']['txt_mail_comments'];				
				$tplLayout = $vars['websiteParams']['tpl_layout'];
				
				if(defined('FRONTOFFICE_VIEWS')) { $emailElement = FRONTOFFICE_VIEWS.DS.'elements'.DS.'email'.DS.'commentaire'; } 
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
    			$message = '<p class="confirmation">'._('Votre commentaire a bien été pris en compte, il sera diffusé après validation par notre modérateur').'</p>';
    			$messageOk = '<p>'._('Votre commentaire a bien été pris en compte, il sera diffusé après validation par notre modérateur').'</p>';
    			
    			$this->set('message', $message);
    			$this->set('messageOk', $messageOk);
				////////////////////////////////////////////
				
				//Si le plugin mailing est installé on va alors procéder à l'ajout 
				if(isset($this->plugins['Mailings'])) {
				
					$this->load_model('MailingsEmail');
					$this->MailingsEmail->save(array(
						'name' => $this->request->data['name'],
						'email' => $this->request->data['email'],
						'etiquette' => $this->request->data['cpostal']	
					));				
					$this->unload_model('MailingsEmail');
				}				
				
				$this->request->data = false;
				
    		} else {
    	
    			$message = '<p class="error"><strong>'.('Merci de corriger vos informations').'</strong>';
    			foreach($this->PostsComment->errors as $k => $v) { $message .= '<br />'.$v; }
    			$message .= '</p>';
    			$messageKo = '<p><strong>'._('Merci de corriger vos informations').'</strong>';
    			foreach($this->PostsComment->errors as $k => $v) { $messageKo .= '<br />'.$v; }
    			$messageKo .= '</p>';
    			
    			$this->set('message', $message);
    			$this->set('messageKo', $messageKo);
    		}
    		
    		//$this->unload_model('PostsComment'); //Déchargement du modèle
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
		$this->load_model('ModulesType'); //Chargement du modèle des types de modules
		$modulesTypes = $this->ModulesType->find(array('conditions' => array('online' => 1), 'order' => 'order_by ASC')); //On récupère les types de modules
		
		$leftMenus = array();
		foreach($modulesTypes as $v) { 
			$leftMenus[$v['id']] = array(
				'libelle' => $v['name'], 
				'icon' => $v['icon'], 
				'menus' => array()
			); 
		}						
		
		$this->load_model('Module');
		$leftMenuTMP = $this->Module->find(array('conditions' => array('online' => 1, 'no_display_in_menu' => 0), 'order' => 'order_by ASC'));
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