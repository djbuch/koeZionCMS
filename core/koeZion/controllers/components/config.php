<?php
/**
 * Cette classe permet la manipulation des données de configurations
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
class ConfigComponent extends Component {
	
/**
 * Cette fonction permet la récupération de configurations en backoffice
 *
 * @param 	array 	$datas 	Données postées lors de la mise à jour
 * @param 	object 	$model 	Objet model courant
 * @param 	varchar $code 	Filtrage par code
 * @return 	array Données de configuration
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 13/10/2016 by FI
 */
	public function backoffice_manage($datas, $model, $code = null) {
		
		if($datas) {
		
			foreach($datas as $code => $codeValues) {
		
				$model->deleteByName('code', $code); //Suppression des lignes en base
		
				//Parcours et sauvegarde des données
				foreach($codeValues as $field => $value) {
		
					$model->save(array(
						'code' => $code,
						'field' => $field,
						'value' => $value
					));
				}
			}
		}
		
		//Récupération des informations en base
		else {
		
			$conditions = array();
			if(isset($code)) { $conditions = array('conditions' => array('code' => $code)); }
			$configs = $model->find($conditions);
			pr($configs);
			foreach($configs as $v) { $datas[$v['code']][$v['field']] = $v['value']; }
		}
		
		return $datas;
	}
}