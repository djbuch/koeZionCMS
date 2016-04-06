<?php
require_once(HELPERS.DS.'form_parent_helper.php');
class FormHelper extends FormParentHelper {

/**
 * Cette fonction va créer uniquement le bouton du formulaire
 *
 * @param 	varchar $text Texte du bouton
 * @param 	varchar $moreAttributes Attributs complémentaires
 * @param 	varchar $moreCss CSS complémentaires
 * @param 	varchar $css Classes CSS
 * @return	varchar Chaine de caractères contenant le bouton
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 09/09/2015 by FI
 * @version 0.2 - 28/10/2015 by FI - Rajout de $moreCss et $css
 */
	function button($text = null, $moreAttributes = null, $moreCss = null, $css = null) {

		if(empty($text)) { $text = _("Envoyer"); }		
		if(!isset($css)) { $css = 'btn btn-primary pull-right'; }
		if(isset($moreCss)) { $css .= ' '.$moreCss; }
		$html = '<button type="submit" class="'.$css.'" '.$moreAttributes.'>'.$text.'</button>';
		return $html;
	}

/**
 * Cette fonction permet la mise en place des éditeurs de texte WYSIWYG
 * 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 09/09/2015 by FI
 */
	function ckeditor() {
		
		if($this->editorFields) {
		
			?><script type="text/javascript"><?php 
			foreach($this->editorFields as $editor) {
				
				$inputName 		= $editor['inputName'];
				$inputToolbar 	= $editor['inputToolbar'];				
				
				$inputIdText 	= 'input_'.$inputName;
				$inputIdText 	= str_replace('[', ' ', $inputIdText);
				$inputIdText 	= str_replace(']', ' ', $inputIdText);
				$inputIdText 	= Inflector::camelize(Inflector::variable($inputIdText));

				if(!isset($inputToolbar)) { ?>var ck_<?php echo $inputIdText; ?>_editor = CKEDITOR.replace('<?php echo $inputIdText; ?>');<?php }
				else if($inputToolbar == "image") { ?>var ck_<?php echo $inputIdText; ?>_editor = CKEDITOR.replace('<?php echo $inputIdText; ?>', {toolbar:[{name:'document',items:['Source']},{name:'insert',items:['Image', 'Flash', 'Iframe']},{name:'links',items:['Link','Unlink']}]});<?php }
				else if($inputToolbar == "empty") { ?>var ck_<?php echo $inputIdText; ?>_editor = CKEDITOR.replace('<?php echo $inputIdText; ?>', {toolbar:[{name:'document',items:['Source']}]});<?php }
				else if($inputToolbar == "onlyHtml") { ?>var ck_<?php echo $inputIdText; ?>_editor = CKEDITOR.replace('<?php echo $inputIdText; ?>', {toolbar:[{name:'document',items:['Source']},{name:'basicstyles',items:['Bold','Italic','Underline','Strike','Subscript','Superscript','RemoveFormat']},{name:'paragraph',items:['NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']},{name:'styles',items:['Font','FontSize']},{name:'colors',items:['TextColor','BGColor']}]});<?php }
				?>CKFinder.setupCKEditor(ck_<?php echo $inputIdText; ?>_editor, '<?php echo Router::url('/ck/finder/'.CKFINDER_VERSION.'/', '', false); ?>');<?php					
			}
			?></script><?php
		}
	}

/**
 * Fonction qui permet de générer un champ d'upload via CKFInder
 *
 * @param 	varchar $field Nom du champ input
 * @param 	array 	$params Paramètres du champ input
 * @return 	varchar Code HTML du champ d'upload
 * @version 0.1 - 10/07/2015 by FI - Rajout de txtAfterInput
 * @version 0.2 - 29/09/2015 by FI - Rajout de $params['wrapperDivClass']
 * @version 0.3 - 06/04/2016 by FI - Modification appel de la popup suite à la mise à jour de CKFinder
 */
	function upload_files($field, $params = null) {
		
		if(!isset($params) || empty($params)) {

			$params['label'] = _("Fichier à importer");
			$params['tooltip'] = _("Sélectionnez le fichier à importer");
			$params['button_value'] = _("Sélectionnez le fichier");
			$params['display_input'] = true;
			$params['wrapperDivClass'] = 'form-group'; 
		} else {

			if(!isset($params['label'])) { $params['label'] = _("Fichier à importer"); }
			if(!isset($params['tooltip'])) { $params['tooltip'] = _("Sélectionnez le fichier à importer"); }
			if(!isset($params['button_value'])) { $params['button_value'] = _("Sélectionnez le fichier"); }
			if(!isset($params['display_input'])) { $params['display_input'] = true; }
			if(!isset($params['wrapperDivClass'])) { $params['wrapperDivClass'] = 'form-group';  }
		}
				
		$inputNameText = $this->_set_input_name($field); //Mise en variable du name de l'input
		$inputIdText = $this->_set_input_id($inputNameText); //Mise en variable de l'id de l'input
		
		ob_start();
		
		?><script><?php 
		if(CKFINDER_VERSION == '2.3') {
			
			?>
			function openPopup<?php echo $inputIdText; ?>() {

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
			<?php 
			
		} else if(CKFINDER_VERSION == '3.3') {
			?>
			function openPopup<?php echo $inputIdText; ?>() {
				CKFinder.popup({
					chooseFiles: true,
					onInit: function(finder) {
						finder.on('files:choose', function(evt) {
							var file = evt.data.files.first();
							document.getElementById("<?php echo $inputIdText; ?>").value = file.getUrl();
						});
						finder.on('file:choose:resizedImage', function(evt) {
							document.getElementById("<?php echo $inputIdText; ?>").value = evt.data.resizedUrl;
						});
					}
				});
			}			
			<?php
		}
		?>		
     	</script>
		<div class="<?php echo $params['wrapperDivClass']; ?>">
			<label>
				<?php 			
				echo $params['label'];
				echo $this->_tooltip($params['tooltip']);				
				?>
			</label>
			<div class="input-group">
				<?php				
				if($params['display_input']) { echo $this->input($field, '', array('onlyInput' => true, 'class' => 'form-control')); }
				?>
				<span class="input-group-btn"> 
				<?php 
				echo $this->input('select_file', $params['button_value'], array('type' => 'button', 'onclick' => 'openPopup'.$inputIdText.'();', 'onlyInput' => true, 'class' => 'btn'));
				if(isset($params['txtAfterInput'])) { echo $params['txtAfterInput']; }
				?>
				</span>
			</div>
		</div>
		<?php
		return ob_get_clean();		
	}
	
	/*
	 * ANCIENNE VERSION
	function upload_files($field, $params = null) {

		if(!isset($params) || empty($params)) {

			$params['label'] = _("Fichier à importer");
			$params['tooltip'] = _("Sélectionnez le fichier à importer");
			$params['button_value'] = _("Sélectionnez le fichier");
			$params['display_input'] = true;
			$params['wrapperDivClass'] = 'form-group'; 
		} else {

			if(!isset($params['label'])) { $params['label'] = _("Fichier à importer"); }
			if(!isset($params['tooltip'])) { $params['tooltip'] = _("Sélectionnez le fichier à importer"); }
			if(!isset($params['button_value'])) { $params['button_value'] = _("Sélectionnez le fichier"); }
			if(!isset($params['display_input'])) { $params['display_input'] = true; }
			if(!isset($params['wrapperDivClass'])) { $params['wrapperDivClass'] = 'form-group';  }
		}
				
		$inputNameText = $this->_set_input_name($field); //Mise en variable du name de l'input
		$inputIdText = $this->_set_input_id($inputNameText); //Mise en variable de l'id de l'input
		
		ob_start();
		?>
		<script type="text/javascript">
			
		</script>
		<div class="<?php echo $params['wrapperDivClass']; ?>">
			<label>
				<?php 			
				echo $params['label'];
				echo $this->_tooltip($params['tooltip']);				
				?>
			</label>
			<div class="input-group">
				<?php				
				if($params['display_input']) { echo $this->input($field, '', array('onlyInput' => true, 'class' => 'form-control')); }
				?>
				<span class="input-group-btn"> 
				<?php 
				echo $this->input('select_file', $params['button_value'], array('type' => 'button', 'onclick' => 'BrowseServer'.$inputIdText.'();', 'onlyInput' => true, 'class' => 'btn'));
				if(isset($params['txtAfterInput'])) { echo $params['txtAfterInput']; }
				?>
				</span>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	*/
		
/**
 * Cette fonction va permettre de créer les checkbox slides
 *
 * @param 	varchar $name		Nom de l'input
 * @param 	array 	$options	Options de l'input
 * @return	varchar Chaine de caractères contenant le code HTML de l'input
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 11/12/2013 by FI
 * @version 0.2 - 20/08/2015 by FI - Correction passage de paramètres fonction _get_input_value
 */
	function custom_checkbox_slide($name, $options = array()) {
	
		//Liste des options par défaut
		$defaultOptions = array(
			'value' => false,
			'forceDefaultValue' => false
		);
		$options = array_merge($defaultOptions, $options); //Génération du tableau d'options utilisé dans la fonction
		
		$value = $this->_get_input_value($name, $options['value'], $options['forceDefaultValue']); //Récupération de la valeur			
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
 * 
 * - div : si vrai la valeur retournée sera contenu dans une div
 * - divright : si vrai le champ input sera retourné dans un div
 * - fulllabelerror : si vrai l'affichage du message d'erreur se fera à part
 * @version 25/03/2015 by FI - Correction de la gestion du champ traduit pour tester l'exitence d'un model
 * @version 30/03/2015 by FI - Correction du test sur le champ traduit pour prendre en compte les noms de champs utilisants la notation.
 * @version 25/09/2015 by FI - Rajout de wrapperDivInput et de wrapperDivInputClass
 * 
 * @see FormHelper::input()
 */	
	function input($name, $label, $options = array()) {
				
		////////////////////////
		//    PARAMETRAGES    //
		$modelName 				= isset($this->view->controller->params['modelName']) ? $this->view->controller->params['modelName'] : ''; //Récupération du model courant
		$escapeAttributes 		= array('wrapperDiv', 'wrapperDivClass', 'wrapperDivInput', 'wrapperDivInputClass', 'inputDatas');
		$this->escapeAttributes = am($escapeAttributes, $this->escapeAttributes);		
		
		///////////////////////////////
		//    GESTION DES OPTIONS    //
		$defaultOptions = array(
			'wrapperDiv' => true, //Div qui va entourer le label et l'input
			'wrapperDivClass' => 'form-group', //Classe par défaut de la div 
			'wrapperDivInput' => false, //Div qui va entourer uniquement l'input
			'wrapperDivInputClass' => 'input-group', //Classe par défaut
			'inputDatas' => false,
			'class' => 'form-control'
		);
		
		if(isset($options['type']) && in_array($options['type'], array("checkbox", "radio"))) {
			
			//if($defaultOptions['wrapperDivClass'] == 'form-group') { $defaultOptions['wrapperDivClass'] = 'checkbox'; }
			if($defaultOptions['class'] == 'form-control') { $defaultOptions['class'] = ''; }
		}
		
		$options = array_merge($defaultOptions, $options); //Génération du tableau d'options utilisé dans la fonction		
		
		$htmlInput = ''; //Chaine de caractères qui va contenir l'élément input demandé
		
		////////////////////////////////////
		//    GESTION DES TRADUCTIONS    //
		//On va contrôler si l'input à générer est "traductible"
		//On va prendre en compte les names utilisants la notation AAA.BBB.CCC et récupérer le dernier élément du tableau pour vérifier s'il est lui aussi présent dans la liste
		$explodeName = explode('.', $name);		
		$translatedInput = (
			isset($this->view->controller->$modelName) 
			&& !empty($modelName) 
			&& $this->view->controller->$modelName->fieldsToTranslate 
			&& in_array(end($explodeName), $this->view->controller->$modelName->fieldsToTranslate)
		);
		
		//Input traduit
		if($translatedInput) {
			
			$fieldsToTranslate 	= $this->view->controller->$modelName->fieldsToTranslate;
			$languages = Session::read('Backoffice.Languages');
			
			foreach($languages as $language) {
			
				$languageLabel = $label.' <img src="'.$language['picture'].'" style="float: left;margin-right: 5px;" />';
				$inputDatas = parent::input($name.'.'.$language['code'], $languageLabel, am($options, array('wrapperDivClass' => 'form-group row_language row_'.$language['code']))); //Appel fonction parente
				$htmlInput .= $this->_html_input($inputDatas);
			}
		
		//Cas général
		} else { 
			
			$inputDatas = parent::input($name, $label, $options); //Appel fonction parente			
			$htmlInput = $this->_html_input($inputDatas);
		}
		
		return $htmlInput;		
	}
		
/**
 * Cette fonction va permettre de créer les inputs
 *
 * @param 	array $inputDatas Tableau contenant les données de paramétrage de l'input
 * @return	varchar Chaine de caractères contenant le code HTML de l'input
 * @access	protected
 * @author	koéZionCMS
 * @version 0.1 - 18/03/2015 by FI
 * @version 0.2 - 04/09/2015 by FI - Gestion du tooltips
 * @version 0.3 - 25/09/2015 by FI - Rajout de wrapperDivInput et de wrapperDivInputClass
 */	
	protected function _html_input($inputDatas) {
				
		//====================    	TRAITEMENTS DES DONNEES    ====================//	

		//Cas du champ caché
		if($inputDatas['inputOptions']['type'] == 'hidden') { return $inputDatas['inputElement']; } 	

		//Gestion du tooltips
		if($inputDatas['inputOptions']['tooltip']) {
						
			$tooltips = $this->_tooltip($inputDatas['inputOptions']['tooltip']);
			$inputDatas['inputLabelDetails']['end'] .= $tooltips;
			$inputDatas['inputLabel'] = $inputDatas['inputLabelDetails']['start'].$inputDatas['inputLabelDetails']['content'].$inputDatas['inputLabelDetails']['end'];
		}
		
		if($inputDatas['inputOptions']['onlyInput']) { return $inputDatas['inputElement']; }		
		if($inputDatas['inputOptions']['inputDatas']) { return $inputDatas; }		
		
		if(!empty($inputDatas['inputError'])) { $inputDatas['inputOptions']['wrapperDivClass'] .= ' has-error'; }
				
		if($inputDatas['inputOptions']['wrapperDiv']) {

			$return = '<div class="'.$inputDatas['inputOptions']['wrapperDivClass'].'">';
			
			//Cas particulier des checkbox
			if(in_array($inputDatas['inputOptions']['type'], array("checkbox", "radio"))) {
				
				$return .= '<div class="'.$inputDatas['inputOptions']['type'].'">';
				$return .= $inputDatas['txtBeforeInput'];
				$return .= $inputDatas['inputLabelDetails']['start'];
				$return .= $inputDatas['inputElement'];
				$return .= $inputDatas['inputLabelDetails']['content'];
				$return .= $inputDatas['inputLabelDetails']['end'];
				$return .= $inputDatas['txtAfterInput'];
				$return .= '</div>';
			}
			
			//Cas générique
			else { 
				
				if($inputDatas['inputOptions']['label']) { $return .= $inputDatas['inputLabel']; }
				if($inputDatas['inputOptions']['wrapperDivInput']) { $return .= '<div class="'.$inputDatas['inputOptions']['wrapperDivInputClass'].'">'; }
				$return .= $inputDatas['txtBeforeInput'].$inputDatas['inputElement'].$inputDatas['txtAfterInput'];
				if($inputDatas['inputOptions']['wrapperDivInput']) { $return .= '</div>'; }
				$return .= $inputDatas['inputError']; 
			
			}
			$return .= '</div>';
			return $return;
			
		} else { return $inputDatas['inputLabel'].$inputDatas['txtBeforeInput'].$inputDatas['inputElement'].$inputDatas['txtAfterInput'].$inputDatas['inputError']; }	
		
	}
		
/**
 * Fonction générant l'élément tooltip
 * 
 * @param 	varchar $tooltipContent Contenu du message d'information
 * @return	varchar Chaine de caractères contenant le code HTML
 * @access	protected
 * @author	koéZionCMS
 * @version 0.1 - 10/09/2015 by FI
 */	
	protected function _tooltip($tooltipContent) {
		
		$htmlHelper = new HtmlHelper($this->view); //Chargement du helper Html
		return '&nbsp;&nbsp;<a href="#" class="infos" data-container="body" data-toggle="popover" data-placement="right" data-content="'.$tooltipContent.'"><i class="fa fa-question-circle"></i></a>';		
		//return $htmlHelper->img('bo_adminlte/img/tooltip.png', array('class' => _('tooltips'), 'data-container' => "body", 'data-toggle' => "popover", 'data-placement' => "right", 'data-content' => $tooltipContent, 'alt' => "tooltip", 'style' => "margin-top:-2px;margin-left:5px;cursor:pointer;")).' ';		
	}
}
?>