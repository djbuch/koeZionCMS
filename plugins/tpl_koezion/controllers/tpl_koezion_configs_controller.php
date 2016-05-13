<?php
/**
 * Contrôleur permettant la gestion des configurations du template koéZion
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
class TplKoezionConfigsPluginController extends AppController {	

//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	
		
/**
 * Cette fonction permet la récupération de la valeur d'une configuration
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/05/2016 by FI
 */	
	public function get_config($code, $field = null) {
		
		if(isset($field)) {
		
			$config = $this->TplKoezionConfig->findFirst(array('conditions' => array(
				'code' => $code,
				'field' => $field
			)));
			if($config) { return $config['value']; }
			else { return false; }
			
		} else {
		
			$config = $this->TplKoezionConfig->find(array('conditions' => array('code' => $code)));
			if($config) { return $config; }
			else { return false; }
			
		}
	}
	
//////////////////////////////////////////////////////////////////////////////////////////	
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////

/**
 * Cette fonction permet la gestion des configurations par défaut
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 12/05/2016 by FI
 */
	public function backoffice_manage() {
		
        //Si des données sont postées
		if($this->request->data) {
			
			foreach($this->request->data as $code => $codeValues) {
				
				$this->TplKoezionConfig->deleteByName('code', $code); //Suppression des lignes en base

				//Parcours et sauvegarde des données
				foreach($codeValues as $field => $value) {				
	                
					$this->TplKoezionConfig->save(array(
						'code' => $code,
						'field' => $field,
						'value' => $value
					));
				}
			}
		} 
        //Récupération des informations en base
        else {
            
            $configs = $this->TplKoezionConfig->find();
            foreach($configs as $v) { $this->request->data[$v['code']][$v['field']] = $v['value']; }
        }
	}
}