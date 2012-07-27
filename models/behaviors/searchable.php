<?php
/**
 * Cette classe permet la gestion du moteur de recherche
 * Elle est basé sur l'utilisation du moteur de recherche de Zend
 * Zend_Search_Lucene
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
class Searchable {

	private $index = false;
	private $document = false;
	
	function __construct() {
	
		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . CORE.DS); //Modification de include_path
		require_once('Zend'.DS.'Search'.DS.'Lucene.php'); //Inclusion de la librairie de Zend
	
		//On va en premier lieu tester si l'index existe
		//Si il existe on l'ouvre sinon on le créé
		if(is_dir(SEARCH_LUCENE)) { $this->index = Zend_Search_Lucene::open(SEARCH_LUCENE); }
		else { $this->index = Zend_Search_Lucene::create(SEARCH_LUCENE); }
		
		Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive());
	}
	
	function deleteEntries($id, $model) {
	
		//On va tester que la donnée à indexer ne le soit pas déjà, si elle l'est on la supprime de l'index
		foreach($this->index->find('pkdelete:'.$model.$id) as $hit) { $this->index->delete($hit->id); }
	}
	
/**
 * Optimisation d'index
 *
 * L'augmentation du nombre de segments réduit la qualité de l'index, mais l'optimisation de l'index remédie à ce problème.
 * L'optimisation a pour principale activité de fusionner plusieurs segments en un seul.
 * Ce processus ne met pas à jour les segments.
 * Il génère un nouveau segment plus gros et met à jour la liste des segments ('segments' file).
 *
 * @see http://framework.zend.com/manual/fr/zend.search.lucene.index-creation.html#zend.search.lucene.index-creation.optimization
 */
	function optimize() { $this->index->optimize(); }
	
	function createDocument() { $this->document = new Zend_Search_Lucene_Document(); }
	
	/**
	 *
	 * @param unknown_type $type --> UnIndexed, Text, etc...
	 * @param unknown_type $field nom du champ
	 * @param unknown_type $value valeur du champ
	 */
	function addField($type, $field, $value) { $this->document->addField(Zend_Search_Lucene_Field::$type($field, $value, 'UTF-8')); }
	
/**
 * Création d'une nouvelle instance de document
 */	
	function addDocument() { $this->index->addDocument($this->document); }
	function commit() { $this->index->commit(); }	
	
/**
 * Chercher dans un index
 * @see http://framework.zend.com/manual/fr/zend.search.lucene.searching.html
 */
	function query($query) { 
		
		return $this->index->find($query); 
	}	
	
/**
 * Récupération de la taille de l'index
 *
 * Il existe deux méthodes pour récupérer la taille d'un index dans Zend_Search_Lucene.
 *
 * La méthode Zend_Search_Lucene::maxDoc()
 * retourne un de plus que le plus grand nombre possible de documents. Il s'agit en fait du nombre total de documents dans l'index, y compris les documents supprimés.
 * Cette méthode a une méthode synonyme : Zend_Search_Lucene::count().
 *
 * La méthode Zend_Search_Lucene::numDocs()
 * retourne le nombre total de documents non supprimés.
 *
 * @see http://framework.zend.com/manual/fr/zend.search.lucene.index-creation.html#zend.search.lucene.index-creation.counting
 *
 */
	function maxDocs() { return $this->index->maxDoc(); }
	function count() { return $this->index->count(); }
	function numDocs() { return $this->index->numDocs(); }	
}