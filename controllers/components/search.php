<?php
/**
 * Classe permettant l'utilisation du moteur de recherche
 *
 *
 * @todo gérer l'init lorsqu'il n'y a pas d'index de recherche
 *
 */
class Search {

	var $searchable = false;
	
	function __construct() {
				
		require_once(BEHAVIORS.DS.'searchable.php'); //Inclusion de la librairie
		$this->searchable = new Searchable(); //Création d'un objet Searchable
	}
		
	function query($query) { return $this->searchable->query($query); }
}