<?php
/**
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
class Object {	

/**
 * Cette fonction permet le chargement d'un model
 *
 * @param varchar $name Nom du model à charger
 * @version 0.1 - 23/12/2011
 */
	function loadModel($name, $return = false) {
		
		//En premier lieu on test si le model n'est pas déjà instancié
		//et si il ne l'est pas on procède à son intenciation
		if(!isset($this->$name)) {
			
			$file_path = '';				
			$file_name = Inflector::underscore($name).'.php'; //Nom du fichier à charger		
			$file_path_default = ROOT.DS.'models'.DS.$file_name; //Chemin vers le fichier à charger
			
			//Pour déterminer le dossier du plugin nous devons transformer le nom du model à charger
			//Etape 1 : passage au pluriel
			//Etape 2 : transformation du camelCased en _
			$pluralizeName = Inflector::pluralize($name);		
			$underscoreName = Inflector::underscore($pluralizeName);		
			$file_path_plugin = PLUGINS.DS.$underscoreName.DS.'model.php'; //Chemin vers le fichier plugin à charger
			
			//////////////////////////////////////////////
			//   RECUPERATION DES CONNECTEURS PLUGINS   //
			$pluginsConnectors = get_plugins_connectors();
			//Inflector::pluralize(Inflector::underscore($name))
			if(isset($pluginsConnectors[$underscoreName])) {
					
				$connectorModel = $pluginsConnectors[$underscoreName];
				$file_path_plugin = PLUGINS.DS.$connectorModel.DS.'model.php';				
			}
			//////////////////////////////////////////////		
			
			if(file_exists($file_path_plugin)) { $file_path = $file_path_plugin; } //Si il y a un plugin on le charge par défaut		
			else if(file_exists($file_path_default)) { $file_path = $file_path_default; } //Sinon on test si le model par défaut existe
			
			if(!file_exists($file_path)) { 				
				
				Session::write('redirectMessage', "Impossible de charger le modèle ".$name." dans le fichier controller");
				$this->redirect('home/e404');
				die();
			} //On va tester l'existence de ce fichier
			
			require_once($file_path); //Inclusion du fichier
			
			if(isset($this->request->fullUrl)) { $url = $this->request->fullUrl; } else { $url = null; }			
			if($return) { return new $name($url); }
			else { $this->$name = new $name($url); } //Création d'un objet Model de type $name que l'on va instancier dans la classe
		}
	}
	
	/**
	 * Cette fonction permet le "déchargement" d'un model dans un controller
	 *
	 * @param varchar $name Nom du model à "décharger"
	 * @version 0.1 - 25/01/2011
	 */
	function unloadModel($name) {	
	
		//En premier lieu on test si le model n'est pas déjà instancié
		//et si il ne l'est pas on procède à son intenciation
		if(isset($this->$name)) { unset($this->$name); }
	}
	
	function load_component($component, $path = null) {		
		
		$componentFileName = Inflector::underscore($component); //Nom du fichier
		$componentObjectName = $component.'Component'; //Nom du fichier
		if(!isset($path)) { $componentPath = COMPONENTS; }
		else { $componentPath = $path; } 
		require_once $componentPath.DS.$componentFileName.'.php'; //Inclusion du fichier
		$this->components[$component] = new $componentObjectName($this); //Et on insère l'objet
	}
	
	/*
	function dispatchMethod($method, $params = array()) {
		
		switch (count($params)) {
			case 0:
				return $this->{$method}();
			case 1:
				return $this->{$method}($params[0]);
			case 2:
				return $this->{$method}($params[0], $params[1]);
			case 3:
				return $this->{$method}($params[0], $params[1], $params[2]);
			case 4:
				return $this->{$method}($params[0], $params[1], $params[2], $params[3]);
			case 5:
				return $this->{$method}($params[0], $params[1], $params[2], $params[3], $params[4]);
			default:
				return call_user_func_array(array(&$this, $method), $params);
			break;
		}
	}	
		
	//CREATION D'UN FICHIER AVEC UNE DATE LIMITE
	$cacheFolder = TMP.DS.'cache'.DS.'models'.DS;
	$cacheSeconds = 60*60*24*30; //1 mois
	$cacheFile = $cacheFolder.$this->table.'.cache';
	
	if(!is_dir($cacheFolder)) { mkdir($cacheFolder, 0777); }
	
	$cacheFileExists = (@file_exists($cacheFile)) ? @filemtime($cacheFile) : 0;
	
	if($cacheFileExists > time() - $cacheSeconds) { $shema = unserialize(file_get_contents($cacheFile)); }
	else {
	
		$sql = "SHOW COLUMNS FROM ".$this->table;			
		$result = $this->query($sql, true);
		foreach($result as $k => $v) { $shema[] = $v['Field']; }
		
		$pointeur = fopen($cacheFile, 'w');
		fwrite($pointeur, serialize($shema));
		fclose($pointeur);
	}
	*/

	/*function backoffice_migration() {
	
		$this->auto_render = false; //Pas de rendu
		set_time_limit(0);*/
	
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
	//}
		
}