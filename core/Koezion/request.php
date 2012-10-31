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
    
/**
 * Constructeur de la classe
 * 
 * 09/08/2012 --> Rajout de full URL
 */    
	public function __construct() {
				
		$this->url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/'; //Affectation de l'url
		$this->fullUrl = 'http://'.$_SERVER["HTTP_HOST"].Router::url($this->url, ''); //Affectation de l'url complète
				
		//Gestion de la pagination
		if(isset($_GET['page'])) {
			
			if(is_numeric($_GET['page'])) { 
				
				if($_GET['page'] > 0) {
					
					$this->currentPage = round($_GET['page']);
				}
			}

			unset($_GET['page']);
		}
		
		if(!empty($_GET)) {
			
			if(!$this->data) { $this->data = array(); }
			foreach($_GET as $k => $v) { 

				if(!is_array($v)) { $v = stripslashes($v); } //Hack OVH hébergement perso
				$this->data[$k] = $v; 
				$this->get[$k] = $v; 
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
				
				foreach($_FILES as $k => $v) { $this->data[$k] = $_FILES[$k]; }
			}
		}
	}    
}