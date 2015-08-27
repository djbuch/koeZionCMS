<?php
/**
 * Ce contrôleur permet la gestion des contacts du site
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
 */
class ContactsController extends AppController {	
	
//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction permet la gestion de l'inscription à la lettre d'informations
 * 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 10/02/2012 by FI 
 * @version 0.2 - 27/06/2013 by FI - Correction sur la gestion de l'élément suite au changement dans la gestion des templates plus nettoyage des données
 */	
	function newsletter() {
		
		if($this->request->data) { //Si des données sont postées
			
			if($this->Contact->validates($this->request->data)) { //Si elles sont valides
    	
    			//Récupération du contenu à envoyer dans le mail
    			$vars = $this->get('vars');
    			$messageContent = $vars['websiteParams']['txt_mail_newsletter'];
			
    			if(defined('FRONTOFFICE_VIEWS')) { $emailElement = FRONTOFFICE_VIEWS.DS.'elements'.DS.'email'.DS.'newsletter'; }
    			else { $emailElement = ELEMENTS.DS.'email'.DS.'default'; }
    			
    			$this->request->data = Sanitize::clean($this->request->data, array('remove_html' => true)); //Petit nettoyage des données avant envoi et insertion
    			
				///////////////////////
				//   ENVOI DE MAIL   //
				$mailDatas = array(
					'subject' => '::Newsletter::',
					'to' => $this->request->data['email'],
					'element' => $emailElement,
					'vars' => array('messageContent' => $messageContent)
				);
				$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email
				///////////////////////
				
				$this->Contact->save($this->request->data);	//On procède à la sauvegarde des données
				
				if(isset($this->request->data['id']) && isset($this->request->data['name'])) {
					
					$message = '<p class="confirmation">Votre demande a bien été prise en compte.</p>';
					$messageOk = '<p>Votre demande a bien été prise en compte.</p>';					
					$this->request->data = false;
					
				} else { 
					
					$message = '<p class="confirmation">Votre demande a bien été prise en compte. <br /> Vous pouvez compléter vos informations si vous le souhaitez.</p>';
					$messageOk = '<p>Votre demande a bien été prise en compte. <br /> Vous pouvez compléter vos informations si vous le souhaitez.</p>';					
					$this->set('newsletter_id', $this->Contact->id);
					
				}
				
				$this->set('message', $message);
				$this->set('messageOk', $messageOk);
				 			
			} else {
		
				//Gestion des erreurs
				$message = '<p class="error"><strong>Merci de corriger vos informations</strong>';
				foreach($this->Contact->errors as $k => $v) { $message .= '<br />'.$v; }
				$message .= '</p>';
				$messageKo = '<p><strong>Merci de corriger vos informations</strong>';
				foreach($this->Contact->errors as $k => $v) { $messageKo .= '<br />'.$v; }
				$messageKo .= '</p>';
				
				$this->set('message', $message);
				$this->set('messageKo', $messageKo);
				
				if(isset($this->request->data['id'])) { $this->set('newsletter_id', $this->request->data['id']); }
			}	
		}
	}
	
//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction permet l'affichage de la liste des éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 09/03/2012 by FI
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */
	function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) { 
		
		parent::backoffice_index(false, array('id', 'name', 'email', 'online', 'created'), 'created DESC, name ASC'); 
	}	
			
}