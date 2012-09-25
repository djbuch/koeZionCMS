<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des formulaires
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
class FormulairesController extends AppController {
	
//////////////////////////////////////////////////////////////////////////////////////////
//										INSTALLATION									//
//////////////////////////////////////////////////////////////////////////////////////////	
		
/**
 * Cette fonction permet l'installation du plugin
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */	
	function install() {
	
		//Requête SQL de création de la table des catalogues
		$sql = "
		DROP TABLE IF EXISTS `formulaires`;
		CREATE TABLE IF NOT EXISTS `formulaires` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `form_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
		  `online` int(11) NOT NULL,
		  `created` datetime DEFAULT NULL,
		  `modified` datetime NOT NULL,
		  `created_by` int(11) NOT NULL,
		  `modified_by` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		
		INSERT INTO `formulaires` (`id`, `name`, `form_file`, `online`, `created`, `modified`, `created_by`, `modified_by`) VALUES
		(1, 'Formulaire de contact', 'contact.xml', 1, '2012-09-17 11:24:35', '2012-09-17 11:24:35', 1, 1),
		(2, 'Formulaire commentaire article', 'commentaire.xml', 1, '2012-09-17 11:32:21', '2012-09-17 20:56:13', 1, 1);		
		";
		$this->loadModel('Catalogue'); //Chargement du model
		$functionResult = $this->Catalogue->query($sql); //Lancement de la requête
				
		//Copie des formulaires de base
		chmod(CONFIGS_FORMS, 0755); //On fait un chmod sur le dossier de destination
		$sourceFolder = dirname(__FILE__).DS.'install';
		
		if (!copy($sourceFolder.DS.'contact.xml', CONFIGS_FORMS.DS.'contact.xml')) { $copy1Result = false; } else { $copy1Result = true; }
		if (!copy($sourceFolder.DS.'commentaire.xml', CONFIGS_FORMS.DS.'commentaire.xml')) { $copy2Result = false; } else { $copy2Result = true; }
		
		return $functionResult && $copy1Result && $copy2Result;
	}
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/07/2012 by FI
 */
	function backoffice_index() {
	
		$datas = parent::backoffice_index(true);
		$this->set($datas);
	
		$this->render(PLUGINS.DS.'formulaires'.DS.'views'.DS.'backoffice_index', false);
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/07/2012 by FI
 */
	function backoffice_add() {
	
		parent::backoffice_add(); //On fait appel à la fonction d'ajout parente	
		$this->render(PLUGINS.DS.'formulaires'.DS.'views'.DS.'backoffice_add', false);
	}
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 * @version 0.2 - 23/03/2012 by FI - Lors de la modification d'une catégorie, si le champ online de celle-ci est égal à 0 on va mettre à jour l'ensemble des champs online des catégories filles
 */
	function backoffice_edit($id) {
	
		parent::backoffice_edit($id); //On fait appel à la fonction d'édition parente
		$this->render(PLUGINS.DS.'formulaires'.DS.'views'.DS.'backoffice_edit', false);
	}	
	
	function backoffice_export($formFile) {
		
		$this->layout = 'export';
		$datas['type'] = 'application/xml';
		$datas['formFile'] = $formFile;
		$this->set($datas);
		
		$this->render(PLUGINS.DS.'formulaires'.DS.'views'.DS.'backoffice_export', false);				
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction permet l'initialisation des données frontoffice
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */
	function _init_frontoffice_datas($datas = null) {
		
		$controllerName = $this->params['controllerName']; //Contrôleur vourant
		$vars = $this->get('vars'); //Liste des variables passées
		
		//On teste quel est le contrôleur demandé
		if($controllerName == 'Posts') { $displayForm = $vars['post']['display_form']; }
		else if($controllerName == 'Categories') { $displayForm = $vars['category']['display_form']; }
				
		if(isset($displayForm) && $displayForm) {

			$this->loadModel('Formulaire');
			$formulaire = $this->Formulaire->findFirst(array('conditions' => array('id' => $displayForm)));
			
			$formPlugin = $this->components['Xmlform']->format_form($formulaire['form_file']);
			$this->set('formPlugin', $formPlugin);

			if(isset($this->request->data['type_formulaire'])) { FormulairesController::_send_mail($formPlugin['validate'], $formPlugin['formInfos']); } //Gestion du formulaire
		}		
	}	
	
/**
 * Cette fonction permet l'initialisation des données backoffice
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 25/09/2012 by FI
 */
	function _init_backoffice_datas($datas = null) {
		
		if($this->params['controllerName'] == 'Categories' || $this->params['controllerName'] == 'Posts') {
		
			$this->loadModel('Formulaire');
			$formulaires = $this->Formulaire->findList(array('conditions' => array('online' => 1)));
			$this->set('formulaires', $formulaires);
		}		
	}      
    
/**
 * Cette fonction permet le contrôle et l'envoi des formulaires de contact
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 02/08/2012 by FI
 * @version 0.2 - 17/09/2012 by FI - Modification de la gestion des règles de validation qui sont dynamiques
 */    
    function _send_mail($validate = null, $formInfos = null) {
    	   	
    	if(isset($this->request->data) && !empty($this->request->data)) { //Si le formulaire est posté
    		
    		//En fonction du type de formulaire on va charger le bon model
    		switch($this->request->data['type_formulaire']) {
    			
    			case 'contact': 	$model = 'Contact'; 	break;    			
    			case 'commentaire':	$model = 'PostsComment'; break;
    			default:			$model = 'Contact';		break;
    		}
    		
			$this->loadModel($model);
			if(isset($validate) && !empty($validate)) { $this->$model->validate = $validate; } //Réécriture des règles de validation
						
			if($this->$model->validates($this->request->data)) { //Si elles sont valides
						
				//En fonction du model chargé on va faire appel à la bonne fonction d'envoi de mail
				switch($model) {
				
					case 'Contact': FormulairesController::_send_mail_contacts(array('subject' => $formInfos['mail_subject'], 'confirm_message' => $formInfos['success_message'])); break;					
					case 'PostsComment': FormulairesController::_send_mail_comments(array('subject' => $formInfos['mail_subject'], 'confirm_message' => $formInfos['success_message'])); break;
				}		
				
			} else {
		
				//Gestion des erreurs
				$message = '<p class="error">'.$formInfos['error_message'];
				foreach($this->$model->errors as $k => $v) { $message .= '<br />'.$v; }
				$message .= '</p>';
				$this->set('message', $message);
			}
			
			$this->unloadModel($model);
		}
    }      
    
/**
 * Cette fonction permet l'envoi des formulaires de contacts
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 17/09/2012 by FI
 */      
    function _send_mail_contacts($params = array('subject' => '::Contact KoéZionCMS::', 'confirm_message' => 'Votre demande a bien été prise en compte')) {
    	    	    	
    	//Récupération du contenu à envoyer dans le mail
    	$vars = $this->get('vars');
    	$messageContent = $vars['websiteParams']['txt_mail_contact'];
    	
    	/////////////////////////////////////////////////////
    	//Création du complément du message envoyé par mail//
    	$formPlugin = $vars['formPlugin']; //Données du formulaire XML
    	$formulaireHtml = $formPlugin['formulaireHtml']; //Tableau de correspondance entre les libellés et les données postées
    	$donneesPostees = $this->request->data; //Données postées
    	
    	$messageComplement = '';
    	foreach($donneesPostees as $k => $v) {
    		
    		if($k == 'type_formulaire') continue;
    		if(isset($formulaireHtml[$k])) { $messageComplement .= $formulaireHtml[$k]." ".$v."<br />"; }    		
    	}
    	/////////////////////////////////////////////////////
    	
    	///////////////////////
    	//   ENVOI DE MAIL   //
    	$mailDatas = array(
    		'subject' => $params['subject'],
    		'to' => $this->request->data['email'],
    		'element' => 'frontoffice/email/html',
    		'vars' => array(
    			'formUrl' => $this->request->fullUrl,
    			'messageContent' => $messageContent,
    			'messageComplement' => $messageComplement
    		)
    	);
    	$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email
    	///////////////////////
    	
    	////////////////////////////////////////////
    	//   SAUVEGARDE DANS LA BASE DE DONNEES   //
    	$this->request->data['message'] = $messageComplement; //On met à jour le contenu complet du message
    	$this->Contact->save($this->request->data);
    	$message = '<p class="confirmation">'.$params['confirm_message'].'</p>';
    	$this->set('message', $message);
    	$this->request->data = false;
    	////////////////////////////////////////////
    }     
    
/**
 * Cette fonction permet le contrôle et l'envoi des formulaires de commentaires
 *
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 02/08/2012 by FI
 */      
    function _send_mail_comments($params = array('subject' => '::Commentaire article KoéZionCMS::', 'confirm_message' => 'Votre commentaire a bien été prise en compte, il sera diffusé après validation par notre modérateur')) {
	    	
    	//Récupération du contenu à envoyer dans le mail
		$vars = $this->get('vars');
    	$messageContent = $vars['websiteParams']['txt_mail_comments'];
    			
    	//On va rajouter l'identifiant de l'article
    	if(isset($vars['post']['id'])) { $this->request->data['post_id'] = $vars['post']['id']; }
    	else { $this->request->data['post_id'] = 0; }
    	
    	
    	/////////////////////////////////////////////////////
    	//Création du complément du message envoyé par mail//
    	$formPlugin = $vars['formPlugin']; //Données du formulaire XML
    	$formulaireHtml = $formPlugin['formulaireHtml']; //Tableau de correspondance entre les libellés et les données postées
    	$donneesPostees = $this->request->data; //Données postées
    	
    	$messageComplement = '';
    	foreach($donneesPostees as $k => $v) {
    	
    		if($k == 'type_formulaire') continue;
    		if(isset($formulaireHtml[$k])) { $messageComplement .= $formulaireHtml[$k]." ".$v."<br />"; }
    	}
    	/////////////////////////////////////////////////////
    	
    	///////////////////////
    	//   ENVOI DE MAIL   //
    	$mailDatas = array(
	    	'subject' => $params['subject'],
	    	'to' => $this->request->data['email'],
	    	'element' => 'frontoffice/email/html',
			'vars' => array(
				'formUrl' => $this->request->fullUrl,						
				'messageContent' => $messageContent,
    			'messageComplement' => $messageComplement
			)
    	);
    	$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email
    	///////////////////////
    			
		////////////////////////////////////////////
		//   SAUVEGARDE DANS LA BASE DE DONNEES   //    	
    	$this->request->data['message'] = $messageComplement; //On met à jour le contenu complet du message
    	$this->PostsComment->save($this->request->data);
    	$message = '<p class="confirmation">'.$params['confirm_message'].'</p>';
    	$this->set('message', $message);
    	$this->request->data = false;
		////////////////////////////////////////////
    }
}