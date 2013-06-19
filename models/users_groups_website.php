<?php
/**
 * Modèle permettant la gestion de l'association entre les groupes d'utilisateurs et les sites Internet
 */
class UsersGroupsWebsite extends Model {
	
	public $manageWebsiteId = false; //Permet d'éviter de prendre en compte la recherche basée sur le champ website_id ainsi que l'insertion automatique de ce champ
}