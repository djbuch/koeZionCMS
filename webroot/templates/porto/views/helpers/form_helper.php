<?php
require_once(HELPERS.DS.'form_parent_helper.php');
class FormHelper extends FormParentHelper {
	
/**
 * 
 * @see FormHelper::input()
 */	
	function input($name, $label, $options = array()) {
				
		$escapeAttributes = array();
		$this->escapeAttributes = am($escapeAttributes, $this->escapeAttributes);
		
		//Liste des options par défaut
		$defaultOptions = array();
		$options = array_merge($defaultOptions, $options); //Génération du tableau d'options utilisé dans la fonction
		
		$inputDatas = parent::input($name, $label, $options); //Appel fonction parente
				
		//====================    	TRAITEMENTS DES DONNEES    ====================//	
		
		if($inputDatas['inputOptions']['type'] == 'hidden') { return $inputDatas['inputElement']; } //Cas du champ caché
		
		
		$classError = '';
		if(!empty($inputDatas['inputError'])) { $classError = ' error'; }
		
		ob_start();
		if($inputDatas['inputOptions']['onlyInput']) { echo $inputDatas['inputElement']; } 
		else {		
		
			if($inputDatas['inputOptions']['rowFluid']) { ?><div class="row-fluid"><?php }
			if($inputDatas['inputOptions']['wrapperDiv']) { ?><div class="<?php echo $inputDatas['inputOptions']['wrapperDivCss']; ?>"><?php }
			if($inputDatas['inputOptions']['controlGroup']) { ?><div class="control-group"><?php } ?>						
				<?php echo $inputDatas['inputLabel']; ?>
				<?php if($inputDatas['inputOptions']['controls']) { ?><div class="controls"><?php } ?>
					<?php 
					echo $inputDatas['inputElement']; 
					echo $inputDatas['inputError'];
					?>
				<?php if($inputDatas['inputOptions']['controls']) { ?></div><?php } ?>
			<?php if($inputDatas['inputOptions']['controlGroup']) { ?></div><?php } ?>
			<?php if($inputDatas['inputOptions']['wrapperDiv']) { ?></div><?php } ?>
			<?php if($inputDatas['inputOptions']['rowFluid']) { ?></div><?php } ?>
		<?php
		}
		return ob_get_clean();
	}	
}
?>