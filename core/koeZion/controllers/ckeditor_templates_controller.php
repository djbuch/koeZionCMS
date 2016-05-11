<?php
/**
 * Contrôleur permettant la gestion de l'ensemble des modèles de page CK Editor
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
class CkeditorTemplatesController extends AppController {	
	
/**
 * Cette fonction permet l'affichage de la liste des éléments
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 09/05/2016 by FI
 */
	function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) { 
		
		$datas = parent::backoffice_index(true, array('id', 'name', 'layout', 'online'));
		
		$ckeditorTemplates = array();
		foreach($datas['ckeditorTemplates'] as $k => $v) { $ckeditorTemplates[$v['layout']][] = $v; }
		$datas['ckeditorTemplates'] = $ckeditorTemplates;		
		$this->set($datas);
	}
	
/**
 * Cette fonction permet l'ajout d'un élément
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 09/05/2016 by FI
 */
	function backoffice_add($redirect = false, $forceInsert = false) {
		
		$this->load_model('Template');
		$templatesList 	= $this->Template->find(array('conditions' => array('online' => 1), 'order' => 'name'));
		$layoutsList 	= array();
		foreach($templatesList as $k => $v) { $layoutsList[$v['layout']] = _("Layout")." ".$v['layout']; }
		$this->set('layoutsList', $layoutsList); //On les envois à la vue
		
		$parentAdd = parent::backoffice_add($redirect, $forceInsert); //On fait appel à la fonction d'ajout parente
		
		if($parentAdd) { 
			
			$this->_generate_file();
			$this->redirect('backoffice/ckeditor_templates/index');
		}
	}	
	
/**
 * Cette fonction permet l'édition d'un élément
 *
 * @param 	integer $id Identifiant de l'élément à modifier
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 09/05/2016 by FI
 */
	function backoffice_edit($id, $redirect = false) {
		
		$this->load_model('Template');
		$templatesList 	= $this->Template->find(array('conditions' => array('online' => 1), 'order' => 'name'));
		$layoutsList 	= array();
		foreach($templatesList as $k => $v) { $layoutsList[$v['layout']] = _("Layout")." ".$v['layout']; }
		$this->set('layoutsList', $layoutsList); //On les envois à la vue
		
		$parentEdit = parent::backoffice_edit($id, $redirect); //On fait appel à la fonction d'ajout parente
		
		if($parentEdit) { 
			
			$this->_generate_file();
			$this->redirect('backoffice/ckeditor_templates/index');
		}
	}
	
/**
 * Cette fonction permet la génération du fichier des modèles de page
 *
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 10/05/2016 by FI
 */	
	protected function _generate_file() {
		
		//////////////////////////////////
		//    RECUPERATION DU LAYOUT    //
			//On ne va mettre à jour que les modèles concernés par le layout récupéré dans $this->request->data['layout']		
			$websiteLayout = $this->request->data['layout'];
		
		/////////////////////////////////////////////
		//    CREATION DU DOSSIER ET DU FICHIER    //
			$path = FILES.DS.'ck'.DS.$websiteLayout;
			FileAndDir::createPath($path);
			$file = $path.DS.'default_templates.js';
				
		////////////////////////////////////////////
		//    RECUPERATION DES MODELES DE PAGE    //		
			$this->load_model('CkeditorTemplate');
			$ckeditorTemplates = $this->CkeditorTemplate->find(array('conditions' => array(
				'online' => 1, 
				'layout' => $websiteLayout
			)));
					
			$templates = array();
			foreach($ckeditorTemplates as $ckeditorTemplate) {
	
				$templates[] = array(
					'title' => $ckeditorTemplate['name'],
					'image' => str_replace(BASE_URL, '', $ckeditorTemplate['illustration']),
					'description' => $ckeditorTemplate['description'],
					'html' => $ckeditorTemplate['html']
				);
			}

		///////////////////////////////////////////
		//    INSERTION DU CONTENU DU FICHIER    //
		FileAndDir::put($file, "CKEDITOR.addTemplates('default',".json_encode(array('imagesPath' => BASE_URL, 'templates' => $templates)).");");
	}
}