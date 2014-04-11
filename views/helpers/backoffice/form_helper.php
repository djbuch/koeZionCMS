<?php
require_once(HELPERS.DS.'form_parent_helper.php');
class FormHelper extends FormParentHelper {

/**
 * Cette fonction va créer le formulaire avec les options indiquées
 *
 * @param 	boolean $full Booléen indiquant si on ne retourne que le bouton ou le bouton plus une div autour
 * @return	varchar Chaine de caractères contenant la balise de fin de formulaire
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 20/01/2012 by FI
 */
	function end($full = false, $extraClass = '') {

		$html = parent::end();
		if($full) {

			//Rajout de la div autour du bouton
			$htmlFull = '<div class="row '.$extraClass.'" style="text-align: right;">';
			$htmlFull .= '<button class="medium grey" type="submit" style="opacity: 1;"><span>'._("Envoyer").'</span></button>';
			$htmlFull .= '</div>';

			$html = $htmlFull.$html;
		}

		return $html;
	}	

/**
 * Cette fonction va créer uniquement le bouton du formulaire
 *
 * @return	varchar Chaine de caractères contenant la balise de fin de formulaire
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 12/10/2012 by FI
 */
	function button($text = "", $more = '', $extraClass = '') {

		if(empty($text)) { $text = _("Envoyer"); }
		$html = '<div class="row '.$extraClass.'" style="text-align: right;">';
		$html .= '<button class="medium grey" type="submit" style="opacity: 1;" '.$more.'><span>'.$text.'</span></button>';
		$html .= '</div>';
		return $html;
	}

/**
 * Enter description here...
 *
 * @param unknown_type $input
 * @return unknown
 *
 */
	function ckeditor($input, $toolbar = null) {

		if(!is_array($input)) $input = array($input);

		ob_start();
		?>
		<script type="text/javascript">
			<?php
			foreach($input as $k => $v) {

				$inputIdText = 'input_'.$v;
				$inputIdText = str_replace('[', ' ', $inputIdText);
				$inputIdText = str_replace(']', ' ', $inputIdText);
				$inputIdText = Inflector::camelize(Inflector::variable($inputIdText));

				if(!isset($toolbar)) { ?>var ck_<?php echo $inputIdText; ?>_editor = CKEDITOR.replace('<?php echo $inputIdText; ?>');<?php }
				else if($toolbar == "image") { ?>var ck_<?php echo $inputIdText; ?>_editor = CKEDITOR.replace('<?php echo $inputIdText; ?>', {toolbar:[{name:'document',items:['Source']},{name:'insert',items:['Image', 'Flash', 'Iframe']},{name:'links',items:['Link','Unlink']}]});<?php }
				else if($toolbar == "empty") { ?>var ck_<?php echo $inputIdText; ?>_editor = CKEDITOR.replace('<?php echo $inputIdText; ?>', {toolbar:[{name:'document',items:['Source']}]});<?php }
				else if($toolbar == "onlyHtml") { ?>var ck_<?php echo $inputIdText; ?>_editor = CKEDITOR.replace('<?php echo $inputIdText; ?>', {toolbar:[{name:'document',items:['Source']},{name:'basicstyles',items:['Bold','Italic','Underline','Strike','Subscript','Superscript','RemoveFormat']},{name:'paragraph',items:['NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']},{name:'styles',items:['Font','FontSize']},{name:'colors',items:['TextColor','BGColor']}]});<?php }
				/*else if($toolbar == "onlyHtml") { ?>var ck_<?php echo $inputIdText; ?>_editor = CKEDITOR.replace('<?php echo $inputIdText; ?>', {toolbar:[{name:'document',items:['Source','Templates']},{name:'basicstyles',items:['Bold','Italic','Underline','Strike','Subscript','Superscript','RemoveFormat']},{name:'paragraph',items:['NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']},{name:'styles',items:['Font','FontSize']},{name:'colors',items:['TextColor','BGColor']}]});<?php }*/
				?>CKFinder.setupCKEditor(ck_<?php echo $inputIdText; ?>_editor, '<?php echo Router::webroot('/ck/ckfinder/'); ?>');<?php
			}
			?>
		</script>
		<?php
		return ob_get_clean();
	}

/**
 * Enter description here...
 *
 * @param unknown_type $input
 * @return unknown
 *
 */
	function upload_files($field, $params = null) {

		if(!isset($params) || empty($params)) {

			$params['label'] = "Fichier à importer";
			$params['tooltip'] = "Sélectionnez le fichier à importer";
			$params['button_value'] = "Sélectionnez le fichier";
			$params['display_input'] = true;
		} else {

			if(!isset($params['label'])) { $params['label'] = "Fichier à importer"; }
			if(!isset($params['tooltip'])) { $params['tooltip'] = "Sélectionnez le fichier à importer"; }
			if(!isset($params['button_value'])) { $params['button_value'] = "Sélectionnez le fichier"; }
			if(!isset($params['display_input'])) { $params['display_input'] = true; }
		}
				
		$inputNameText = $this->_set_input_name($field); //Mise en variable du name de l'input
		$inputIdText = $this->_set_input_id($inputNameText); //Mise en variable de l'id de l'input
		
		ob_start();
		?>
		<script type="text/javascript">
			function BrowseServer<?php echo $inputIdText; ?>() {

				// You can use the "CKFinder" class to render CKFinder in a page:
				var finder = new CKFinder();
				finder.basePath = './js/ckfinder/';	// The path for the installation of CKFinder (default = "/ckfinder/").
				finder.selectActionFunction = SetFileField<?php echo $inputIdText; ?>;
				finder.popup();

				// It can also be done in a single line, calling the "static"
				// popup( basePath, width, height, selectFunction ) function:
				// CKFinder.popup( '../', null, null, SetFileField ) ;
				//
				// The "popup" function can also accept an object as the only argument.
				// CKFinder.popup( { basePath : '../', selectActionFunction : SetFileField } ) ;
			}

			// This is a sample function which is called when a file is selected in CKFinder.
			function SetFileField<?php echo $inputIdText; ?>(fileUrl) { document.getElementById("<?php echo $inputIdText; ?>").value = fileUrl; }
		</script>
		<div class="row">
			<label>
				<?php echo $params['label']; ?>
				<img original-title="<?php echo $params['tooltip']; ?>" class="tip-w" style="float: left; margin-right: 5px; cursor: pointer;" alt="tooltip" src="<?php echo BASE_URL; ?>/img/backoffice/tooltip.png">
			</label>

			<div class="rowright">
				<?php				
				if($params['display_input']) { echo $this->input($field, '', array('tooltip' => false, 'div' => false, 'label' => false, 'class' => 'upload_file')); }
				echo $this->input('select_file', '<span>'.$params['button_value'].'</span>', array('type' => 'button', 'onclick' => 'BrowseServer'.$inputIdText.'();', 'displayError' => false, 'label' => false, 'div' => false, 'tooltip' => false, 'class' => 'medium black'));
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}


/**
 * Enter description here...
 *
 * @param unknown_type $input
 * @return unknown
 *
 */
	function upload_files_products() {

		ob_start();
		?>
		<script type="text/javascript">
			function BrowseServer(startupPath, functionData) {
				// You can use the "CKFinder" class to render CKFinder in a page:
				var finder = new CKFinder();

				// The path for the installation of CKFinder (default = "/ckfinder/").
				finder.basePath = '../';

				//Startup path in a form: "Type:/path/to/directory/"
				finder.startupPath = startupPath;

				// Name of a function which is called when a file is selected in CKFinder.
				finder.selectActionFunction = SetFileField;

				// Additional data to be passed to the selectActionFunction in a second argument.
				// We'll use this feature to pass the Id of a field that will be updated.
				finder.selectActionData = functionData;

				// Launch CKFinder
				finder.popup();
			}

			// This is a sample function which is called when a file is selected in CKFinder.
			function SetFileField(fileUrl, data) { document.getElementById( data["selectActionData"] ).value = fileUrl; }
		</script>
		<div class="row">
			<label>
				Fiche technique
				<img original-title="Sélectionnez le fichier à importer" class="tip-w" style="float: left; margin-right: 5px; cursor: pointer;" alt="tooltip" src="<?php echo BASE_URL; ?>/img/backoffice/tooltip.png">
			</label>

			<div class="rowright">
				<?php
				$id = $this->_set_input_id('doc');
				echo $this->input('select_file', '', array('type' => 'button', 'onclick' => "BrowseServer('Files:/', '".$id."');", 'displayError' => false, 'label' => false, 'div' => false, 'tooltip' => false, 'value' => "Sélectionnez le fichier"));
				echo $this->input('doc', '', array('tooltip' => false, 'div' => false, 'label' => false, 'class' => 'upload_file'));
				?>
			</div>
		</div>
		<div class="row">
			<label>
				Image
				<img original-title="Sélectionnez le fichier à importer" class="tip-w" style="float: left; margin-right: 5px; cursor: pointer;" alt="tooltip" src="<?php echo BASE_URL; ?>/img/backoffice/tooltip.png">
			</label>

			<div class="rowright">
				<?php
				$id = $this->_set_input_id('img');
				echo $this->input('select_file', '', array('type' => 'button', 'onclick' => "BrowseServer('Images:/', '".$id."');", 'displayError' => false, 'label' => false, 'div' => false, 'tooltip' => false, 'value' => "Sélectionnez le fichier"));
				echo $this->input('img', '', array('tooltip' => false, 'div' => false, 'label' => false, 'class' => 'upload_file'));
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
		
/**
 * Cette fonction va permettre de créer les checkbox slides
 *
 * @param unknown_type $input
 * @return	varchar Chaine de caractères contenant la balise de fin de formulaire
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 11/12/2013 by FI
 */
	function custom_checkbox_slide($name, $options = array()) {
	
		//Liste des options par défaut
		$defaultOptions = array(
			'value' => false
		);
		$options = array_merge($defaultOptions, $options); //Génération du tableau d'options utilisé dans la fonction
		
		$value = $this->_get_input_value($name, $options['value']); //Récupération de la valeur			
		$inputNameText = $this->_set_input_name($name); //Mise en variable du name de l'input
		$inputIdText = $this->_set_input_id($inputNameText); //Mise en variable de l'id de l'input	
		
		$html = '<div class="custom_chebox_slide">';
		$html .= '<input type="hidden" id="'.$inputIdText.'hidden" name="'.$inputNameText.'" value="0" />';
		$html .= '<input type="checkbox" id="'.$inputIdText.'" name="'.$inputNameText.'" value="1" '.(empty($value) ? '' : 'checked').' class="nocustomjs" />';
		$html .= '<label for="'.$inputIdText.'"></label>';
		$html .= '</div>';
		
		return $html;
	}

/**
 * A reprendre
 * @param unknown_type $name
 * @param unknown_type $value
 * @return string
 */
	function radiobutton_templates($name, $value, $templateName, $templateLayout, $templateCode, $templateColor) {

		$inputNameText = $this->_set_input_name($name); //Mise en variable du name de l'input
		$inputIdText = $this->_set_input_id($inputNameText); //Mise en variable de l'id de l'input

		$bddValue = Set::classicExtract($this->view->controller->request->data, $name);
		if($value == $bddValue) {
			$checked = 'checked="checked"';
			$selected = ' class="selected"';
		} else {
			$checked = '';
			$selected = '';
		}

		$imgFile = WEBROOT.DS.'img'.DS.'backoffice'.DS.'templates'.DS.$templateLayout.DS.$templateCode.DS.'background.png';
		if(file_exists($imgFile)) { $thumb = '<img src="'.BASE_URL.'/img/backoffice/templates/'.$templateLayout.'/'.$templateCode.'/background.png" />'; } 
		else if(!empty($templateColor)) { 
			
			$firstChar = $templateColor{0};
			if($firstChar == "#") { $thumb = '<span style="display:block;width:80px;height:72px;padding:0;margin:0 5px;background:'.$templateColor.'">&nbsp</span>'; }
			else { $thumb = '<img src="'.$templateColor.'" />'; } 
		}
		else { $thumb = ''; }
		
		return '<p '.$selected.'><input name="'.$inputNameText.'" id="'.$inputIdText.$value.'" value="'.$value.'" type="radio" '.$checked.' /><span>'.$templateName.'<br />'.$thumb.'</span></p>';
	}
	
/**
 * 
 * - div : si vrai la valeur retournée sera contenu dans une div
 * - divright : si vrai le champ input sera retourné dans un div
 * - fulllabelerror : si vrai l'affichage du message d'erreur se fera à part
 * 
 * @see FormHelper::input()
 */	
	function input($name, $label, $options = array()) {
		
		//====================    PARAMETRAGES    ====================//

		$escapeAttributes = array('div', 'divright', 'fulllabelerror', 'colorpicker');
		$this->escapeAttributes = am($escapeAttributes, $this->escapeAttributes);		
		
		//Liste des options par défaut
		$defaultOptions = array(
			'div' => true,
			'divright' => true,
			'fulllabelerror' => false,
			'colorpicker' => false,
			'onlyInput' => false
		);
		$options = array_merge($defaultOptions, $options); //Génération du tableau d'options utilisé dans la fonction
		
		$inputDatas = parent::input($name, $label, $options); //Appel fonction parente
				
		//====================    	TRAITEMENTS DES DONNEES    ====================//	

		//Cas du champ caché
		if($inputDatas['inputOptions']['type'] == 'hidden') { return $inputDatas['inputElement']; } 		

		//Cas du color picker
		if($inputDatas['inputOptions']['colorpicker']) { $inputDatas['inputElement'] = '<div class="color"><div><span style="background-color: '.$inputDatas['inputValue'].'"></span></div>'.$inputDatas['inputElement'].'</div></div>'; }		
		if($inputDatas['inputOptions']['onlyInput']) { return $inputDatas['inputElement']; }		
		
		$classError = '';
		if(!empty($inputDatas['inputError'])) { $classError = ' error'; }
				
		if($inputDatas['inputOptions']['div']) {

			if(isset($inputDatas['inputOptions']['divRowBorderTop']) && !$inputDatas['inputOptions']['divRowBorderTop']) { $styleDiv = ' style="border-top:none"'; } else { $styleDiv = ''; }

			if($inputDatas['inputOptions']['divright']) { 
				if($inputDatas['inputOptions']['fulllabelerror']) {
					return '<div class="row'.$classError.'"'.$styleDiv.'>'.$inputDatas['inputLabel'].'<div class="rowright">'.$inputDatas['inputElement'].'</div>'.$inputDatas['inputError'].'</div>';				
				} else {
					return '<div class="row'.$classError.'"'.$styleDiv.'>'.$inputDatas['inputLabel'].'<div class="rowright">'.$inputDatas['inputElement'].$inputDatas['inputError'].'</div>'.'</div>';
				} 
			}
			else { return '<div class="row'.$classError.'"'.$styleDiv.'>'.$inputDatas['inputLabel'].$inputDatas['inputElement'].$inputDatas['inputError'].'</div>'; }
		} else { return $inputDatas['inputLabel'].$inputDatas['inputElement'].$inputDatas['inputError']; }		
	}
}
?>