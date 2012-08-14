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
 * Tableau contenant la liste des rôles administrateur
 *
 * @var 	array
 * @access 	public
 * @author 	KoéZionCMS
 * @version 0.1 - 21/05/2012 by FI
 */
	var $adminRoles = array('admin', 'website_admin');	
	
/**
 * Variable contenant le nombre d'éléments à afficher par page
 *
 * @var 	integer
 * @access 	public
 * @author 	KoéZionCMS
 * @version 0.1 - 21/05/2012 by FI
 */
	var $backofficeElementPerPage = 50;	
	
	var $notAuth = array(
		'Configs' => array('database_liste', 'mailer_liste', 'router_liste', 'posts_liste', 'sessions_liste'),
		'Exports' => array('database', 'contacts'),
		'Users' => array('index', 'add', 'edit', 'delete'),
		'Websites' => array('index', 'add', 'edit', 'delete')
	);
	
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
				
    	$prefix = isset($this->request->prefix) ? $this->request->prefix : ''; //Récupération du préfixe
    	    	
    	//Si on est dans le backoffice    	
		if($prefix == 'backoffice') {
			
			$adminRole = Session::user('role'); //Récupération du rôle de l'administrateur connecté
			
			//Si pas loggé ou que le rôle n'est pas dans le tableau des rôles administrateur
			if(!Session::isLogged() || (!in_array($adminRole, $this->adminRoles))) { $this->redirect('users/login'); }
			
			//Dans le cas d'un administrateur de site il faut 'vérouiller' certaines pages dans le cas ou l'utilisateur tapes directement l'url dans la barre d'adresse
			$controllerName = $this->params['controllerName']; //Contrôleur courant
			$actionName = $this->params['action']; //Action courante
			if($adminRole == 'website_admin' && isset($this->notAuth[$controllerName]) && in_array($actionName, $this->notAuth[$controllerName])) { $this->redirect('backoffice/home/not_auth'); }
			
			//Récupération de l'identifiant du site courant
			$currentWebsite = Session::read('Backoffice.Websites.current');
			define('CURRENT_WEBSITE_ID', $currentWebsite);
			
			$this->layout = 'backoffice'; //Définition du layout pour le backoffice			
			$this->pager['elementsPerPage'] = $this->backofficeElementPerPage; //Nombre d'élément par page
			
			//Récupération des formulaires de contacts non validés
			$this->loadModel('Contact');
			$nbFormsContacts = $this->Contact->findCount(array('online' => 0));
			$this->set('nbFormsContacts', $nbFormsContacts);
			
			//Récupération des commentaires articles
			$this->loadModel('PostsComment');
			$nbPostsComments = $this->PostsComment->findCount(array('online' => 0));
			$this->set('nbPostsComments', $nbPostsComments);
			
		//Si on est dans le frontoffice			
		} else {
			
			//Dans tous les cas sauf si on est sur le formulaire de connexion
			if($this->params['controllerName'] != 'Users' && ($this->request->action != 'login' || $this->request->action != 'logout')) {
				
				//////////////////////////////////////////////////
				//   RECUPERATION DES DONNEES DU SITE COURANT   //
				$datas['websiteParams'] = $this->_get_website_datas();				
				if(empty($datas['websiteParams'])) {$datas['websiteParams']['secure_activ'] = 1; } //Si aucun site n'est retourné on affiche le formulaire de connexion
				//////////////////////////////////////////////////
				
				//////////////////////////////////////////////
				//   GESTION DES EVENTUELLES REDIRECTIONS   //				
				$this->_is_secure_activ($datas['websiteParams']['secure_activ'], $datas['websiteParams']['log_users_activ']); //Site sécurisé
				//////////////////////////////////////////////
				
				//////////////////////////////////////////////////////////
				//   MISE EN CACHE DE LA RECUPERATION DU MENU GENERAL   //
				$datas['menuGeneral'] = $this->_cache_menu($datas['websiteParams']['id']);				
				//////////////////////////////////////////////////////////
								
				$this->set($datas);
			}
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
    	
    	$prefix = isset($this->request->prefix) ? $this->request->prefix : ''; //Récupération du préfixe
    	
       	//Si on est dans le backoffice
    	if($prefix == 'backoffice' || ($this->params['controllerName'] == 'Users' && $this->params['action'] == 'login')) {
    		
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
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 09/03/2012 by FI - Mise en place de la variable $fields
 * @version 0.3 - 29/05/2012 by FI - Mise en place de la variable $order
 */    
    function backoffice_index($return = false, $fields = null, $order = null) {
    	    	
    	$controllerVarName =  $this->params['controllerVarName']; //On récupère la valeur de la variable du contrôleur
    	$modelName =  $this->params['modelName']; //On récupère la valeur du modèle

    	$tableShema = $this->$modelName->shema();
    	if(in_array('order_by', $tableShema)) { $orderBy = 'order_by ASC, name ASC'; } else { $orderBy = 'id ASC'; }
    	
    	$findConditions = array('fields' => $fields, 'order' => $orderBy);
    	
    	$datas['displayAll'] = false;
    	if(!isset($this->request->data['displayall'])) { $findConditions['limit'] = $this->pager['limit'].', '.$this->pager['elementsPerPage']; } else { $datas['displayAll'] = true; }	
    	if(isset($order)) { $findConditions['order'] = $order; }
    	
    	$datas[$controllerVarName] = $this->$modelName->find($findConditions);    	
    	   	
    	//////////////////////////////////
		//   GESTION DE LA PAGINATION   //
    	$this->pager['totalElements'] = $this->$modelName->findCount();
    	if(!$datas['displayAll']) { $this->pager['totalPages'] = ceil($this->pager['totalElements'] / $this->pager['elementsPerPage']); }
    	else { $this->pager['totalPages'] = 1; }
    	//////////////////////////////////
    	
    	if($return) { return $datas; }
    	else { $this->set($datas); }
    }    
    
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @param 	boolean $redirect 	Indique si il faut ou non rediriger après traitement
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */
    function backoffice_add($redirect = true) {
    
    	$modelName = $this->params['modelName']; //On récupère la valeur du modèle
    	
    	if($this->request->data) { //Si des données sont postées
    		
    		if($this->$modelName->validates($this->request->data)) { //Si elles sont valides
        			
    			$this->$modelName->save($this->request->data); //On les sauvegarde    			
    			    			
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
    	
    	$this->set('id', $id); //On stocke l'identifiant dans une variable

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
    
    		$findConditions = array('conditions' => array('id' => $id));
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
    	
    	$findConditions = array('conditions' => array('id' => $id));
    	$element = $this->$modelName->findFirst($findConditions);
    	
    	if(!isset($element['suppressible']) || (isset($element['suppressible']) && $element['suppressible'])) {
    	    		
	    	$this->$modelName->delete($id); //Suppression de l'élément	    	
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
    	$element = $this->$modelName->findFirst(array('conditions' => array('id' => $id))); //Récupération de l'élément
    	$online = $element['online']; //Récupération de la valeur actuelle du champ online
    	$newOnline = abs($online-1); //On génère la nouvelle valeur du champ online
    	$sql = 'UPDATE '.$this->$modelName->table.' SET online = '.$newOnline.' WHERE id = '.$id; //On construit la requête à effectuer
    	$this->$modelName->query($sql); //On lance la requête
    	Session::setFlash('Le statut a bien été modifié'); //Message de confirmation
    	if($redirect) { $this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); } //On retourne sur la page de listing
    	else { 
    		
    		//Dans le cas ou on ne redirige pas on va envoyer la nouvelle valeur du champ online
    		//Cette donnée sera utilisée par le contrôleur Categories
    		$this->set('newOnline', $newOnline);
    		return true; 
    	}
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
    	$modelTable =  $this->$modelName->table; //Récupération du nom de la table
    	$datas = $this->request->data; //Récupération des données
    	
    	$sql = ""; //Requête sql qui sera exécutée
    	foreach($datas['ligne'] as $position => $id) { $sql .= "UPDATE ".$modelTable." SET order_by = ".$position." WHERE id = ".$id."; "."\n"; } //Construction de la requête
    	$this->$modelName->query($sql); //Exécution de la requête
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
    	
    	Session::setFlash('Index du moteur de recherche reconstruit'); //Message de confirmation
    	$this->redirect('backoffice/'.$this->params['controllerFileName'].'/index'); //Redirection
    }	
	
/**
 * Migration anciennes versions
 */    
	function backoffice_migration() {
		
		$this->auto_render = false; //Pas de rendu
		set_time_limit(0);
		
		////////////////////////////////
		//Migration des types de posts//
		/*$this->loadModel('PostsType');
		$sql = "SELECT * FROM old_posts_types";
		foreach($this->PostsType->query($sql, true) as $k => $v) { $this->PostsType->save($v, true); }
		$this->unloadModel('PostsType');*/
		
		/////////////////////////
		//Migration des sliders//
		/*$this->loadModel('Slider');
		$sql = "SELECT * FROM old_sliders";
		foreach($this->Slider->query($sql, true) as $k => $v) { 
			
			//$img_name = $v['img_name'];
			//$img_path = $v['img_path'];			
			//$v['image'] = '<img alt="" src="'.str_replace('/upload', '/upload/images', $v['img_path']).$v['img_name'].'" style="width: 918px; height: 350px;" />';
			//unset($v['img_path']);
			//unset($v['img_path']);
			$this->Slider->save($v, true); 
		}
		$this->unloadModel('Slider');*/
		
		//////////////////////////////
		//Migration des utilisateurs//
		/*$this->loadModel('User');
		$sql = "SELECT * FROM old_users";
		foreach($this->User->query($sql, true) as $k => $v) { $this->User->save($v, true); }
		$this->unloadModel('User');*/
		
		//////////////////////////
		//Migration des contacts//
		/*$this->loadModel('Contact');
		$sql = "SELECT * FROM old_contacts";
		foreach($this->Contact->query($sql, true) as $k => $v) { $this->Contact->save($v, true); }
		$this->unloadModel('Contact');*/
		
		////////////////////////////////////////////////////////////////////
		//Migration de l'association entre les posts et les types de posts//
		/*$this->loadModel('PostsPostsType');
		$sql = "SELECT * FROM old_posts_posts_types";
		foreach($this->PostsPostsType->query($sql, true) as $k => $v) { $this->PostsPostsType->save($v, true); }
		$this->unloadModel('PostsPostsType');*/
		
		///////////////////////
		//Migration des posts//
		/*$this->loadModel('Post');
		$sql = "SELECT * FROM old_posts";
		foreach($this->Post->query($sql, true) as $k => $v) { 
			
			$v['page_title'] = $v['name'];
			//$v['order_by'] = $v['rang'];
			//unset($v['rang']);
			pr($v);
			$this->Post->save($v, true); 
		}
		$this->unloadModel('Post');*/
		
		////////////////////////////
		//Migration des catégories//
		/*$this->loadModel('Category');
		$sql = "SELECT * FROM old_categories";
		foreach($this->Category->query($sql, true) as $k => $v) { 
			
			$v['page_title'] = $v['name'];
			
			//$this->Category->add($v, true); 
			$sql = "
				INSERT INTO `categories` (`id`, `name`, `content`, `slug`, `page_title`, `page_description`, `page_keywords`, `title_children`, `title_brothers`, `title_posts_list`, `type`, `display_contact_form`, `display_children`, `display_brothers`, `is_secure`, `txt_secure`, `online`, `lft`, `rgt`, `level`, `parent_id`, `redirect_category_id`, `website_id`) 
				VALUES (
					".$v['id'].", 
					'".addslashes($v['name'])."', 
					'".addslashes($v['content'])."', 
					'".addslashes($v['slug'])."', 
					'".addslashes($v['name'])."', 
					'".addslashes($v['page_description'])."', 
					'".addslashes($v['page_keywords'])."', 
					'', 
					'".addslashes($v['title_brothers'])."', 
					'', 
					'".$v['type']."', 
					'".$v['display_contact_form']."', 
					'', 
					'".$v['display_brothers']."', 
					'', 
					'', 
					'".$v['online']."', 
					'".$v['lft']."', 
					'".$v['rgt']."', 
					'".$v['level']."', 
					'".$v['parent_id']."', 
					'".$v['redirect_category_id']."', 
					'1'
				);";			
			
			$this->Category->query($sql);
			pr($v);
		}
		$this->unloadModel('Category');*/		
		
		//////////////////////////
		//Migration des focus//
		/*$this->loadModel('Focus');
		$sql = "SELECT * FROM old_focus";
		foreach($this->Focus->query($sql, true) as $k => $v) { $this->Focus->save($v, true); }
		$this->unloadModel('Focus');*/
				
		//////////////////////////
		//Migration des groupes d'utilisateurs//
		/*$this->loadModel('UsersGroup');
		$sql = "SELECT * FROM old_users_groups";
		foreach($this->UsersGroup->query($sql, true) as $k => $v) { $this->UsersGroup->save($v, true); }
		$this->unloadModel('UsersGroup');*/
		
		//////////////////////////
		//Migration des groupes d'utilisateurs de sites//
		/*$this->loadModel('UsersGroupsWebsite');
		$sql = "SELECT * FROM old_users_groups_websites";
		foreach($this->UsersGroupsWebsite->query($sql, true) as $k => $v) { $this->UsersGroupsWebsite->save($v, true); }
		$this->unloadModel('UsersGroupsWebsite');*/
		
		//////////////////////////
		//Migration des groupes d'utilisateurs de sites//
		/*$this->loadModel('Website');
		$sql = "SELECT * FROM old_websites";
		foreach($this->Website->query($sql, true) as $k => $v) { $this->Website->save($v, true); }
		$this->unloadModel('Website');*/
	}    
    
//////////////////////////////////////////////////////////////////////////////////////////
//									FONCTIONS PRIVEES									//
//////////////////////////////////////////////////////////////////////////////////////////
    
/**
 * Cette fonction permet la récupération des données du site courant
 *
 * @return 	array Données du site Internet
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 * @version 0.2 - 14/06/2012 by FI - Modification de la récupération du site pour la boucle locale - On récupère le premier site de la liste et plus celui avec l'id 1 pour éviter les éventuelles erreurs
 */       
    function _get_website_datas() {
    	
    	$this->loadModel('Website'); //Chargement du modèle
    	$httpHost = $_SERVER["HTTP_HOST"]; //Récupération de l'url
    	
    	if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { 
    		
    		$websites = $this->Website->findList(array('order' => 'id ASC'));
    		$websiteId = current(array_keys($websites));    		
    		$websiteConditions = array('conditions' => array('id' => $websiteId, 'online' => 1));    	
    	}
    	else { $websiteConditions = array('conditions' => "url LIKE '%".$_SERVER['HTTP_HOST']."' AND online = 1"); }
    	
    	$website = $this->Website->findFirst($websiteConditions);
    	define('CURRENT_WEBSITE_ID', $website['id']);
    	
    	$this->layout = $website['tpl_layout'];
    	
    	return $website;
    }    
    
/**
 * Cette fonction permet de vérifier si le site courant est sécurisé ou pas
 *
 * @param 	integer $isSecure 			Si vrai alors le site est sécurisé
 * @param 	integer $isLogUsersActiv 	Si vrai alors la mise en place du log des utilisateurs sera activée
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 03/05/2012 by FI
 * @version 0.2 - 05/06/2012 by FI - Changement de la gestion de la sécurité du frontoffice, on test maintenant que l'utilisateur puisse avoir accès au site courant (via son groupe) 
 * @version 0.3 - 13/06/2012 by FI - Reprise des contrôles effectués pour savoir si le site est sécurisé 
 * @version 0.4 - 23/07/2012 by FI - Rajout du log des utilisateurs 
 * @todo Essayer d'alléger les if/else en cascade 
 */   
    
    function _is_secure_activ($isSecure, $isLogUsersActiv) {

    	$session = Session::read('Frontoffice');
    	
    	$redirectConnect = false;
    	
    	//Si le site est sécurisé on va procéder à quelques contrôles
    	//On évite le contrôleur Errors volontairement pour permettre l'affichage des erreurs
   		if($isSecure && $this->params['controllerName'] != "Errors") { 
   			
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
    
/**
 * Cette fonction permet de mettre en cache le menu
 *
 * @param 	integer $websiteId Identifiant du site Internet
 * @return 	array 	Liste des catégories
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 03/05/2012 by FI
 */       
    function _cache_menu($websiteId) {
    	
    	$cacheFolder = TMP.DS.'cache'.DS.'variables'.DS.'categories'.DS;
    	$cacheSeconds = 60*60*1; // 12 heures
    	$cacheName = 'array_Category_getTreeRecursive_frontoffice';
    	$cacheFile = $cacheFolder.md5($cacheName).'_WS'.$websiteId.'.cache';
    	
    	if(!is_dir($cacheFolder)) { mkdir($cacheFolder, 0777); }
    	
    	$cacheFileExists = (@file_exists($cacheFile)) ? @filemtime($cacheFile) : 0;
    	
    	if($cacheFileExists > time() - $cacheSeconds) { $menuGeneral = unserialize(file_get_contents($cacheFile)); }
    	else {
    	
    		//Récupération du menu général
    		$this->loadModel('Category');
    		$req = array('conditions' => array('online' => 1, 'type' => 1));
    		$menuGeneral = $this->Category->getTreeRecursive($req);
    		$pointeur = fopen($cacheFile, 'w');
    		fwrite($pointeur, serialize($menuGeneral));
    		fclose($pointeur);
    	}
    	
    	return $menuGeneral;
    }      
    
/**
 * Cette fonction permet le contrôle et l'envoi des formulaires de contact
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 02/08/2012 by FI
 */    
    function _send_mail_contact() {
		
    	if(isset($this->request->data['formulaire_contact'])) { //Si le formulaire de contact est posté
		
			$this->loadModel('Contact');
			if($this->Contact->validates($this->request->data)) { //Si elles sont valides
		
				//Récupération du contenu à envoyer dans le mail
				$vars = $this->get('vars');
				$messageContent = $vars['websiteParams']['txt_mail_contact'];
				
				///////////////////////
				//   ENVOI DE MAIL   //
				$mailDatas = array(
					'subject' => '::Contact::',
					'to' => $this->request->data['email'],
					'element' => 'frontoffice/email/contact',
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
				$this->set('message', $message);
				$this->request->data = false;
				////////////////////////////////////////////
				
			} else {
		
				//Gestion des erreurs
				$message = '<p class="error">Merci de corriger vos informations';
				foreach($this->Contact->errors as $k => $v) { $message .= '<br />'.$v; }
				$message .= '</p>';
				$this->set('message', $message);
			}
			$this->unloadModel('Contact');
		}
    }      
    
/**
 * Cette fonction permet le contrôle et l'envoi des formulaires de commentaires
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 02/08/2012 by FI
 */      
    function _send_mail_comments() {
    	
    	//////////////////////////////////////////
    	//   GESTION DU DEPOT DE COMMENTAIRES   //
    	if(isset($this->request->data['formulaire_commentaires'])) {
    	
    		$this->loadModel('PostsComment'); //Chargement du modèle
    		if($this->PostsComment->validates($this->request->data)) { //Si elles sont valides
    	
    			//Récupération du contenu à envoyer dans le mail
    			$vars = $this->get('vars');
    			$messageContent = $vars['websiteParams']['txt_mail_comments'];
    			
    			///////////////////////
    			//   ENVOI DE MAIL   //
    			$mailDatas = array(
	    			'subject' => '::Commentaire::',
	    			'to' => $this->request->data['email'],
	    			'element' => 'frontoffice/email/commentaire',
					'vars' => array(
						'formUrl' => $this->request->fullUrl,						
						'messageContent' => $messageContent
					)
    			);
    			$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email
    			///////////////////////
		
				////////////////////////////////////////////
				//   SAUVEGARDE DANS LA BASE DE DONNEES   //    	
    			$this->PostsComment->save($this->request->data);
    			$message = '<p class="confirmation">Votre commentaire a bien été prise en compte, il sera diffusé après validation par notre modérateur</p>';
    			$this->set('message', $message);
    			$this->request->data = false;
				////////////////////////////////////////////
    		} else {
    	
    			$message = '<p class="error">Merci de corriger vos informations';
    			foreach($this->PostsComment->errors as $k => $v) { $message .= '<br />'.$v; }
    			$message .= '</p>';
    			$this->set('message', $message);
    		}
    		$this->unloadModel('PostsComment'); //Déchargement du modèle
    	}
    	//////////////////////////////////////////
    }
}