<?php
/**
 * Modèle permettant la gestion de l'association entre les utilisateurs et les sites Internet
 */
class UsersWebsite extends Model {
	
	public $manageWebsiteId = false; //Permet d'éviter de prendre en compte la recherche basée sur le champ website_id ainsi que l'insertion automatique de ce champ
}