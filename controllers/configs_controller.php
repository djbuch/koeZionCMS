<?php
/**
 * Ce contrôleur permet la gestion de la configuration de l'application
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
 * 
 * @todo	Essayer de généraliser un peu plus ce contrôleur		
 */
class ConfigsController extends AppController {

	//public $auto_load_model = false;	
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	

/**
 * Cette fonction va permettre l'affichage des configurations de la base de données
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/03/2012 by FI
 * @version 0.2 - 18/04/2012 by FI - Modification de la procédure de gestion des configurations de la base de données, maintenant uniquement deux configurations locale et production
 */
	function backoffice_database_liste() { 
		
		//Import de la librairie de gestion des fichiers de configuration
		require_once(LIBS.DS.'config_magik.php');
		$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'database.ini', true, true);
		
		//Si des données sont postées
		if($this->request->data) {
		
			foreach($this->request->data as $section => $config) { 
				
				foreach($config as $k => $v) { $cfg->set($k, $v, $section); } 
			}
			
			Session::setFlash("Fichier de configuration modifié"); //Message de confirmation
			$this->redirect('backoffice/configs/database_liste'); //Redirection
		}
		
		//On va récupérer la liste des données de configuration de la base de données (Configurations locale et de production)		
		$sections = $cfg->listSections(); //Récupération des différentes sections du fichier de configuration
		foreach($sections as $section) { $this->request->data[$section] = $cfg->keys_values($section); } //On parcours les sections et on récupère les données que l'on affecte "aux données postées" 
	}

/**
 * Cette fonction va permettre l'affichage des configurations des envois de mails
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/03/2012 by FI
 * @version 0.2 - 18/04/2012 by FI - Passage des traitements dans une fonction privée pour mutualiser
 */
	function backoffice_mailer_liste() { 
		
		$currentWebsite = Session::read('Backoffice.Websites.current'); //Site courant
		$websitesList = Session::read('Backoffice.Websites.details'); //Liste des sites
		$currentWebsiteUrl = $websitesList[$currentWebsite]['url']; //Url du site courant
		$this->_proceed_datas_ini(CONFIGS.DS.'files'.DS.'mailer.ini', 'backoffice/configs/mailer_liste', CURRENT_WEBSITE_ID, $currentWebsiteUrl); 
	}
	
/**
 * Cette fonction va permettre l'affichage des configurations des routes
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/03/2012 by FI
 * @version 0.2 - 18/04/2012 by FI - Passage des traitements dans une fonction privée pour mutualiser
 */
	function backoffice_router_liste() { $this->_proceed_datas_ini(CONFIGS.DS.'files'.DS.'routes.ini', 'backoffice/configs/router_liste'); }

/**
 * Cette fonction va permettre l'affichage des configurations des posts
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 22/03/2012 by FI
 * @version 0.2 - 18/04/2012 by FI - Passage des traitements dans une fonction privée pour mutualiser
 */
	function backoffice_posts_liste() { $this->_proceed_datas_ini(CONFIGS.DS.'files'.DS.'posts.ini', 'backoffice/configs/posts_liste'); }
	
/**
 * Cette fonction va permettre l'affichage du code de sécurité utilisé pour les taches planifiées
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 10/09/2012 by FI
 */
	function backoffice_security_code_liste() { $this->_proceed_datas_ini(CONFIGS.DS.'files'.DS.'security_code.ini', 'backoffice/configs/security_code_liste'); }	

/**
 * Cette fonction va permettre de supprimer les fichiers de cache
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 07/01/2013 by FI
 */
	function backoffice_delete_cache() {
		
		Cache::delete_cache_directory(TMP.DS.'cache'.DS);
		Session::setFlash("Cache supprimé"); //Message de confirmation
		$this->redirect('backoffice/configs/delete_cache_result'); //Redirection
	}	
	
/**
 * Cette fonction va permettre l'affichage du message de confirmation lors de la suppression du cache
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/01/2013 by FI
 */
	function backoffice_delete_cache_result() {}
	
/**
 * Cette fonction permet l'affichage de phpinfo
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 19/10/2013 by FI
 */
	function backoffice_phpinfo() {}	

//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////	

/**
 * Cette fonction va permettre l'affichage et la modification des fichiers ini sans section
 *
 * @param	varchar $file		Fichier ini à charger
 * @param	varchar $redirect	Page de redirection
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 18/04/2012 by FI
 */
	function _proceed_datas_ini($file, $redirect, $section = null, $websiteUrl = null) {
	
		require_once(LIBS.DS.'config_magik.php'); //Import de la librairie de gestion des fichiers de configuration
		
		//Création d'une instance
		if(isset($section)) { $cfg = new ConfigMagik($file, true, true); }
		else { $cfg = new ConfigMagik($file, true, false); }
	
		//Si des données sont postées
		if($this->request->data) {
			
			if(isset($section)) {
				
				foreach($this->request->data as $k => $v) { $cfg->set($k, $v, $section); }
				$cfg->set('website_url', $websiteUrl, $section); //On va rajouter l'url du site dans les configurations pour information
				
			} else {

				//On va parcourir les données postées et mettre à jour le fichier ini
				foreach($this->request->data as $k => $v) { $cfg->set($k, $v); }
			}

			Session::setFlash("Fichier de configuration modifié"); //Message de confirmation
			$this->redirect($redirect); //Redirection			
		}	

		//Récupération des configurations
		if(isset($section)) { $this->request->data = $cfg->keys_values($section); }
		else { $this->request->data = $cfg->keys_values(); }
	}
}