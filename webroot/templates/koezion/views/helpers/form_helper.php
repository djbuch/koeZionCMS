<?php
require_once(HELPERS.DS.'form_parent_helper.php');
class FormHelper extends FormParentHelper {
	
	function input($name, $label, $options = array()) {
		
		//====================    PARAMETRAGES    ====================//

		$escapeAttributes = array('divFormGroup', 'divFormGroupStyle', 'divInputGroup', 'spanInputGroupAddon', 'wrapperDiv', 'wrapperDivClass');
		$this->escapeAttributes = am($escapeAttributes, $this->escapeAttributes);		
		
		//Liste des options par défaut
		$defaultOptions = array(
			'labelClass' => 'control-label',
			'divFormGroup' => true,
			'divFormGroupStyle' => '',
			'divInputGroup' => false,
			'spanInputGroupAddon' => '',
			'onlyInput' => false,
			'wrapperDiv' => false,
			'wrapperDivClass' => '',
			'class' => 'form-control'
		);
		$options = array_merge($defaultOptions, $options); //Génération du tableau d'options utilisé dans la fonction
		
		$inputDatas = parent::input($name, $label, $options); //Appel fonction parente
				
		//====================    	TRAITEMENTS DES DONNEES    ====================//	
		
		if($inputDatas['inputOptions']['type'] == 'hidden') { return $inputDatas['inputElement']; } //Cas du champ caché	
		if($inputDatas['inputOptions']['onlyInput']) { return $inputDatas['inputElement']; }
		
		$classError = '';
		if(!empty($inputDatas['inputError'])) { $classError = ' has-error'; }				
		
		$inputReturn = $inputDatas['inputLabel'].$inputDatas['inputElement'].$inputDatas['inputError'];
		
		if($inputDatas['inputOptions']['wrapperDiv']) { $inputReturn = $inputDatas['inputLabel'].'<div class="row"><div class="'.$inputDatas['inputOptions']['wrapperDivClass'].'">'.$inputDatas['inputElement'].'</div></div>'.$inputDatas['inputError']; }
		
		if($inputDatas['inputOptions']['divInputGroup']) { 
			
			if($inputDatas['inputOptions']['wrapperDiv']) { $inputReturn = '<div class="row"><div class="'.$inputDatas['inputOptions']['wrapperDivClass'].'">'.$inputDatas['inputElement'].'</div></div>'; }			
			$inputReturn = $inputDatas['inputOptions']['spanInputGroupAddon'].$inputReturn;
			$inputReturn = $inputDatas['inputLabel'].'<div class="input-group">'.$inputReturn.'</div>'.$inputDatas['inputError']; 
		}
		
		if($inputDatas['inputOptions']['divFormGroup']) { return '<div class="form-group'.$classError.'"'.$inputDatas['inputOptions']['divFormGroupStyle'].'>'.$inputReturn.'</div>'; } 
		else { return $inputReturn; }		
	}
}
?>