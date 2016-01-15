<?php
/**
 * Modèle permettant la gestion de l'association entre les catégories, les focus et les sites Internet
 */
class CategoriesFocusWebsite extends Model {
	
	public $manageWebsiteId = false; //Permet d'éviter de prendre en compte la recherche basée sur le champ website_id ainsi que l'insertion automatique de ce champ
}