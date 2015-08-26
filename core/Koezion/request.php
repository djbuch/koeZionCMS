<?php
/**
 * Cette classe va faire les actions suivantes : 
 * - Récupération de l'url courante
 * - Gestion des variables passées en GET
 * - Gestion des variables passées en POST
 * - Gestion des champs upload
 */
class Request {
    
	public $url; //Url appellée par l'utilisateur
	public $fullUrl; //Url appellée par l'utilisateur (avec http://...)
    public $currentPage = 1; //Page à afficher
    public $prefix = false; //Par défaut on considère qu'il n'y a pas de prefixe
    public $data = false; //Permet de récupérer l'ensemble des données postés par l'utilisateur
    public $get = false; //Permet de récupérer l'ensemble des données postés en GET par l'utilisateur
    public $post = false; //Permet de récupérer l'ensemble des données postés en POST par l'utilisateur
    public $files = false; //Permet de récupérer l'ensemble des fichiers postés via un formulaire
    
/**
 * Constructeur de la classe
 * 
 * 09/08/2012 --> Rajout de full URL
 * 10/01/2014 --> Rajout du referer
 * 02/10/2014 --> Modification gestion variable page
 * 11/10/2014 --> Rajout de $this->files
 * 27/11/2014 --> Mise en place du hooks request - le nom du fichier est égal au nom de l'hôte
 * 28/05/2015 --> Rajout d'un test sur $this->url avant traitement, rajout de $protocol
 * 13/08/2015 --> Rajout de $this->protocol et $this->domain
 * 19/08/2015 --> Correction sur $this->fullUrl
 * 26/08/2015 --> Déplacement $protocol et $this->domain et $this->protocol
 */    
	public function __construct() {
			
		$protocol = 'http';
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https'; }
		
		//Mise en place d'un hook pour la récupération des données url et fullUrl car sur certains serveurs (JAG) le fonctionnement diffère
		$hookRequestFile = CONFIGS.DS.'hooks'.DS.'request'.DS.$_SERVER['SERVER_ADDR'].'.php';
		if(file_exists($hookRequestFile)) { require_once($hookRequestFile); }
		else {
			
			//$this->url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/'; //Affectation de l'url
			if(isset($_SERVER['PATH_INFO'])) { $this->url = $_SERVER['PATH_INFO']; } 
			else if(isset($_SERVER['SCRIPT_URL'])) { $this->url = $_SERVER['SCRIPT_URL']; } //1and1 hack!!
			else if(isset($_SERVER['ORIG_PATH_INFO'])) { $this->url = $_SERVER['ORIG_PATH_INFO']; } //1and1 hack!!
			else {  $this->url = '/'; }
			
			if(in_array($this->url, array('/webroot', 'webroot', '/index.php', 'index.php', '/webroot/index.php'))) { $this->url = '/'; }
			
			$this->fullUrl = Router::url($this->url, '', true, $protocol); //Affectation de l'url complète
			//$this->fullUrl = $protocol.'://'.$_SERVER["HTTP_HOST"].Router::url($this->url, ''); //Affectation de l'url complète			
		}
		
		$this->domain = $_SERVER["HTTP_HOST"]; //Affectation du domaine		
		$this->protocol = $protocol; //Affectation du protocole	
		
		$this->queryString = $_SERVER['QUERY_STRING'];
		$this->referer = '';
		if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) { $this->referer = $_SERVER['HTTP_REFERER']; }
				
		//Gestion de la pagination
		if(isset($_GET['page'])) {
			
			if(is_numeric($_GET['page'])) { 
				
				if($_GET['page'] > 0) {
					
					$this->currentPage = round($_GET['page']);
				}
			}

			//unset($_GET['page']);
		}
		
		if(!empty($_GET)) {
			
			if(!$this->data) { $this->data = array(); }
			foreach($_GET as $k => $v) { 

				if($k != 'page') { //On ne traite pas la variable page passé en GET

					if(!is_array($v)) { $v = stripslashes($v); } //Hack OVH hébergement perso
					$this->data[$k] = $v; 
					$this->get[$k] = $v; 
				}
			}
		}
		
		//Gestion des variables envoyée via POST
		if(!empty($_POST)) {
			
			if(!$this->data) { $this->data = array(); }
			foreach($_POST as $k => $v) {

				if(!is_array($v)) { $v = stripslashes($v); } //Hack OVH hébergement perso 
				$this->data[$k] = $v;
				$this->post[$k] = $v;
			}
			
			//Gestion des fichiers
			if(!empty($_FILES)) {
				
				foreach($_FILES as $k => $v) { 
					$this->data[$k] = $_FILES[$k]; 
					$this->files[$k] = $_FILES[$k]; 
				}
			}
		}
	}    
}