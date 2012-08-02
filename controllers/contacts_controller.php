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
 */	
	function newsletter() {
		
		if($this->request->data) { //Si des données sont postées
			
			if($this->Contact->validates($this->request->data)) { //Si elles sont valides
    	
    			//Récupération du contenu à envoyer dans le mail
    			$vars = $this->get('vars');
    			$messageContent = $vars['websiteParams']['txt_mail_newsletter'];
			
				///////////////////////
				//   ENVOI DE MAIL   //
				$mailDatas = array(
					'subject' => '::Newsletter::',
					'to' => $this->request->data['email'],
					'element' => 'frontoffice/email/newsletter',
					'vars' => array('messageContent' => $messageContent)
				);
				$this->components['Email']->send($mailDatas, $this); //On fait appel au composant email
				///////////////////////
				
				$this->Contact->save($this->request->data);	//On procède à la sauvegarde des données
				
				if(isset($this->request->data['id'])) { 
					
					$message = '<p class="confirmation">Votre demande a bien été prise en compte.</p>'; 
					$this->request->data = false;
				} 
				else { 
					
					$message = '<p class="confirmation">Votre demande a bien été prise en compte. <br /> Vous pouvez compléter vos informations si vous le souhaitez</p>'; 
					$this->set('newsletter_id', $this->Contact->id);
				}
				
				$this->set('message', $message);				
			} else {
				
				//Gestion des erreurs
				$message = '<p class="error">Merci de corriger vos informations';
				foreach($this->Contact->errors as $k => $v) { $message .= '<br />'.$v; }
				$message .= '</p>';		
				$this->set('message', $message);
				
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
 */
	function backoffice_index() { parent::backoffice_index(false, array('id', 'name', 'email', 'online')); }	
			
}