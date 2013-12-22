<?php
/**
 * Ce helpers permet la mise en place et la gestion des forulaires de l'application
 *
 * @toto mutualiser la generation de l'id avec le helper html pour la mise en place de ckeditor
 */
class Form {

/**
 * Variable contenant un entier qui servira à afficher des input radio associés ensembles
 *
 * @var 	int
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 03/05/2012 by AA
 */
	private $radio_count = 0;

/**
 * Variable contenant l'objet vue ayant fait appel au formulaire
 * On va faire correspondre la vue avec cette classe pour renseigner automatiquement les champs lors d'éventuelles erreurs
 *
 * @var 	object
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 20/01/2012 by FI
 */
	public $view;

/**
 * Variable contenant les options à ne pas prendre en compte lors de la mise en place des attributs des input
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 20/01/2012 by FI
 */
	var $escapeAttributes = array('type', 'displayError', 'label', 'div', 'divright', 'datas', 'value', 'divRowBorderTop', 'tooltip', 'fulllabelerror');

/**
 * Constructeur de la classe
 *
 * @param 	object $view Vue par laquelle le classe est utilisée
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 20/01/2012 by FI
 */
	function __construct($view = null) { $this->view = $view; }

/**
 * Cette fonction va créer le formulaire avec les options indiquées
 *
 * @param 	array $options Tableau des options possibles
 * @return	varchar Chaine de caractères contenant la balise de début de formulaire
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 20/01/2012 by FI
 * @version 0.2 - 26/10/2013 by FI - Mise en place d'options par défaut
 */
	function create($options) {

		//Liste des options par défaut
		$defaultOptions = array(
			'method' => 'post',
			'enctype' => 'multipart/form-data'
		);
		$options = array_merge($defaultOptions, $options); //Génération du tableau d'options utilisé dans la fonction
		
		$html = '<form';
		foreach($options as $k => $v) { $html .= ' '.$k.'="'.$v.'"'; } //Parcours des options
		$html .= '>';
		return $html;
	}

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

		$html = '</form>';
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
 * Cette fonction permet la mise en place des champs input dans les formulaires
 * Elle permet également de gérer l'internationnalisation
 *
 * @see _input()
 */
	function input($name, $label, $options = array()) {

		$modelName = isset($this->view->controller->params['modelName']) ? $this->view->controller->params['modelName'] : ''; //Récupération du model courant
		return $this->_input($name, $label, $options);

		/*//Récupération éventuelle des champs à traduire
		if($locale && !empty($modelName) && isset($this->view->controller->$modelName->fields_to_translate)) {

			$fieldsToTranslate = $this->view->controller->$modelName->fields_to_translate;
			if(in_array($name, $fieldsToTranslate)) {

				$html = '<div class="row_locale">';
				foreach(Session::read('Backoffice.Locale') as $codeLocale => $nameLocale) { $html .= $this->_input($name.'.'.$codeLocale, $label.' '.$nameLocale, $options); }
				return $html.'</div>';

			} else { return $this->_input($name, $label, $options); }
		} else { return $this->_input($name, $label, $options); }*/
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
				?>CKFinder.setupCKEditor(ck_<?php echo $inputIdText; ?>_editor, '<?php echo Router::webroot('/js/ckfinder/'); ?>');<?php
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
		} else {

			if(!isset($params['label'])) { $params['label'] = "Fichier à importer"; }
			if(!isset($params['tooltip'])) { $params['tooltip'] = "Sélectionnez le fichier à importer"; }
			if(!isset($params['button_value'])) { $params['button_value'] = "Sélectionnez le fichier"; }
		}

		$inputFieldId = $this->_set_input_id($field);
		ob_start();
		?>
		<script type="text/javascript">
			function BrowseServer<?php echo $inputFieldId; ?>() {

				// You can use the "CKFinder" class to render CKFinder in a page:
				var finder = new CKFinder();
				finder.basePath = './js/ckfinder/';	// The path for the installation of CKFinder (default = "/ckfinder/").
				finder.selectActionFunction = SetFileField<?php echo $inputFieldId; ?>;
				finder.popup();

				// It can also be done in a single line, calling the "static"
				// popup( basePath, width, height, selectFunction ) function:
				// CKFinder.popup( '../', null, null, SetFileField ) ;
				//
				// The "popup" function can also accept an object as the only argument.
				// CKFinder.popup( { basePath : '../', selectActionFunction : SetFileField } ) ;
			}

			// This is a sample function which is called when a file is selected in CKFinder.
			function SetFileField<?php echo $inputFieldId; ?>(fileUrl) { document.getElementById("<?php echo $inputFieldId; ?>").value = fileUrl; }
		</script>
		<div class="row">
			<label>
				<?php echo $params['label']; ?>
				<img original-title="<?php echo $params['tooltip']; ?>" class="tip-w" style="float: left; margin-right: 5px; cursor: pointer;" alt="tooltip" src="<?php echo BASE_URL; ?>/img/backoffice/tooltip.png">
			</label>

			<div class="rowright">
				<?php
				echo $this->input('select_file', '', array('type' => 'button', 'onclick' => 'BrowseServer'.$inputFieldId.'();', 'displayError' => false, 'label' => false, 'div' => false, 'tooltip' => false, 'value' => $params['button_value']));
				echo $this->input($field, '', array('tooltip' => false, 'div' => false, 'label' => false, 'class' => 'upload_file'));
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
	/*function upload_files_multiple() {

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
				echo $this->input('doc', '', array('tooltip' => false, 'div' => false, 'label' => false, 'class' => 'upload_file'));
				echo $this->input('select_file', '', array('type' => 'button', 'onclick' => "BrowseServer('Files:/', '".$id."');", 'displayError' => false, 'label' => false, 'div' => false, 'tooltip' => false, 'value' => "Sélectionnez le fichier"));
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
				echo $this->input('img', '', array('tooltip' => false, 'div' => false, 'label' => false, 'class' => 'upload_file'));
				echo $this->input('select_file', '', array('type' => 'button', 'onclick' => "BrowseServer('Images:/', '".$id."');", 'displayError' => false, 'label' => false, 'div' => false, 'tooltip' => false, 'value' => "Sélectionnez le fichier"));
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}*/

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
 * Cette fonction permet la création des champs input
 *
 * Les valeurs possibles pour le paramètre options sont :
 * - type : type de champ input --> hidden, text, textarea, checkbox, radio, file, password, select
 * - label : si vrai la valeur retournée contiendra le champ label
 * - div : si vrai la valeur retournée sera contenu dans une div
 * - divright : si vrai le champ input sera retourné dans un div
 * - displayError : si vrai affiche les erreurs sous les champs imput
 * - value : Si renseignée cette valeur sera insérée dans le champ input
 * - tooltip : Si renseignée affichera un tooltip à coté du label
 * - wysiswyg : si renseigné et à vrai alors le code de l'éditeur sera généré
 * - fulllabelerror : si vrai l'affichage du message d'erreur se fera à part
 * - modelToCheck : permet, si renseigné, de surcharger la récupération des messages d'erreurs dans un autre modèle que le modèle courant
 *
 * @param 	varchar $name 		Nom du champ input
 * @param 	varchar $label 		Label pour le champ input
 * @param	array	$options	Options par défaut
 * @return 	varchar Chaine html
 * @access	private
 * @author	koéZionCMS
 * @version 0.1 - 20/01/2012 by FI
 * @version 0.2 - 22/02/2012 by FI - Modification de la gestion des options par défaut, utilisation de array_merge plus souple
 * @version 0.3 - 22/02/2012 by FI - Gestion de l'affichage du tooltip
 * @version 0.4 - 06/04/2012 by FI - Passage de la fonction en privée pour la gestion de l'internationnalisation
 * @version 0.5 - 09/12/2013 by FI - Reprise de la gestion des erreurs pour intégrer la notion de profondeur dans la récupération des erreurs
 * @version 0.6 - 09/12/2013 by FI - Rajout de l'option fulllabelerror
 * @version 0.7 - 09/12/2013 by FI - Mise en place d'une surcharge pour la récupération de message d'erreur hors du modèle courant
 * @version 0.8 - 17/12/2013 by FI - Reprise de la gestion des select pour pouvoir gérer optgroup
 * @todo Input de type submit etc..., input radio
 * @todo Voir si utile de gérer en récursif la gestion de optgroup pour le select
 */
	function _input($name, $label, $options = array()) {

		//Liste des options par défaut
		$defaultOptions = array(
			'type' => 'text',
			'label' => true,
			'div' => true,
			'divright' => true,
			'displayError' => true,
			'value' => false,
			'tooltip' => false,
			'fulllabelerror' => false
		);
		$options = array_merge($defaultOptions, $options); //Génération du tableau d'options utilisé dans la fonction

		$error = false; 	//Par défaut on considère qu'il n'y a pas d'erreur
		$classError = ''; 	//Par conséquent par défaut pas de classe css d'erreur

		if($options['displayError']) {

			if(isset($options['modelToCheck'])) { $modelName = $options['modelToCheck']; } //Surcharge de la récupération du modèle courant
			else { $modelName = $this->view->controller->params['modelName']; } //Récupération du model courant
			if(isset($this->view->controller->$modelName->errors)) $errors = $this->view->controller->$modelName->errors; //Récupération des erreurs du formulaires
		}

		//On va contrôler si on a des erreurs
		//if(isset($errors[$name])) {
		if(isset($errors) && Set::check($errors, $name)) {

			//$error = $errors[$name]; //La valeur de l'erreur est stockée
			$error = Set::classicExtract($errors, $name); //La valeur de l'erreur est stockée
			$classError = ' error'; //La classe est modifiée
			//unset($this->view->controller->$modelName->errors[$name]);
		}

		$value = $this->_get_input_value($name, $options['value']);

		//Si la clée n'est pas définie dans les valeurs postées on initialise data à vide
		//if(!isset($this->view->controller->request->data->$name)) { $value = ''; }
		//Si elle est définie on va initialiser le champ input avec cette valeur
		//else { $value = $this->view->controller->request->data->$name; }

		$inputNameText = $this->_set_input_name($name); //Mise en variable du name de l'input
		$inputIdText = $this->_set_input_id($inputNameText); //Mise en variable de l'id de l'input

		//Cas particulier : le champ hidden
		//--> lorsque l'on est sur un champ de type hidden on va renvoyer directement la valeur
		if($options['type'] == 'hidden') { return '<input type="hidden" id="'.$inputIdText.'" name="'.$inputNameText.'" value="'.$value.'" />'; }

		$inputReturn = ''; //Variable qui va contenir la chaine de caractère de l'input

		//Gestion du label de l'input
		if($options['label']) {

			$labelReturn = '<label for="'.$inputIdText.'">';
			if($options['tooltip']) {

				$labelReturn .= '<img src="'.BASE_URL.'/img/backoffice/tooltip.png" alt="tooltip" style="float: left; margin-right: 5px; cursor: pointer;" class="tip-w" original-title="'.$options['tooltip'].'" />';
			}
			$labelReturn .= $label.'</label>';
		}
		else { $labelReturn = ''; }

		///////////////////////////////
		//   Gestion des attributs   //
		$attributes = ''; //Création de la variables qui va contenir les attributs, par défaut elle est vide
		foreach($options as $k => $v) { //Parcours de l'ensemble des options

			//On va éviter d'ajouter dans les attributs des valeurs non conforme
			if(!in_array($k, $this->escapeAttributes)) { $attributes .= ' '.$k.'="'.$v.'"'; }
		}
		///////////////////////////////

		//Génération du champ input
		switch($options['type']) {

			//   INPUT DE TYPE TEXT   //
			case 'text': $inputReturn .= '<input type="text" id="'.$inputIdText.'" name="'.$inputNameText.'" value="'.$value.'"'.$attributes.' />'; break;

			//   INPUT DE TYPE TEXTAREA   //
			case 'textarea':

				if(isset($options['wysiswyg']) && $options['wysiswyg']) { $value = str_replace('&', '&amp;', $value); } //Hack pour l'affichage de code source dans l'éditeur
				$inputReturn .= '<textarea id="'.$inputIdText.'" name="'.$inputNameText.'"'.$attributes.'>'.$value.'</textarea>';
				if(isset($options['wysiswyg']) && $options['wysiswyg']) {

					if(isset($options['toolbar']) && $options['toolbar']) { $toolbar = $options['toolbar']; } else { $toolbar = null; }
					$inputReturn .= $this->ckeditor(array($inputNameText), $toolbar);
				}
			break;

			//   INPUT DE TYPE CHECKBOX   //
			case 'checkbox':

				//Par défaut le champ hidden permettra de mettre à 0 la valeur du champ si la case n'est pas cochée
				$inputReturn .= '<input type="hidden" id="'.$inputIdText.'hidden" name="'.$inputNameText.'" value="0" />';
				$inputReturn .= '<input type="checkbox" id="'.$inputIdText.'" name="'.$inputNameText.'" value="1" '.(empty($value) ? '' : 'checked').' '.$attributes.' />';
			break;

			//   INPUT DE TYPE RADIO   //
			// Pour l'input radio, il faut toujours utiliser l'attribut "value" pour les différencier lors de l'envoi du formulaire
			// 03/05/2013 by AA
			case 'radio':

				$requestvalue = Set::classicExtract($this->view->controller->request->data, $name);//On récupère la valeur dans request
				$checked = $value == $requestvalue ? ' checked="checked"' : '';//Si la valeur dans request est la même que celle passée en paramètre, alors l'input est sélectionné

				$inputIdText .= $this->radio_count;//On concatène l'identifiant pour qu'il soit correctement indiqué sur le label et l'input
				$inputReturn .= '<input type="radio" id="'.$inputIdText.'" name="'.$inputNameText.'" value="'.$value.'"'.$checked.' '.$attributes.' />';

				//Gestion du label de l'input
				//On recrée le label afin d'être sûrs de l'identifiant de celui-ci
				if($options['label']) {
					$labelReturn = '<label for="'.$inputIdText.'">'.$label;
					if($options['tooltip']) {
						$labelReturn .= '<img src="'.BASE_URL.'/img/backoffice/tooltip.png" alt="tooltip" style="float: left; margin-right: 5px; cursor: pointer;" class="tip-w" original-title="'.$options['tooltip'].'" />';
					}
					$labelReturn .= '</label>';
				} else {
					$labelReturn = '';
				}
				$this->radio_count++;//On incrémente le label, de cette façon, au prochain input créé, l'id sera différent du précédent.
			break;

			//   INPUT DE TYPE FILE   //
			case 'file':  $inputReturn .= '<input type="file" id="'.$inputIdText.'" name="'.$inputNameText.'"'.$attributes.' />'; break;

			//   INPUT DE TYPE PASSWORD   //
			case 'password': $inputReturn .= '<input type="password" id="'.$inputIdText.'" name="'.$inputNameText.'" value="'.$value.'"'.$attributes.' />'; break;

			//   INPUT DE TYPE SELECT   //
			case 'select':

				$inputReturn .= '<select id="'.$inputIdText.'" name="'.$inputNameText.'"';
				if(isset($options['multiple']) && $options['multiple']) { $inputReturn .= ' multiple="multiple"'; } //Dans le cas d'un select multiple
				$inputReturn .= $attributes.'>';

				if(isset($options['firstElementList'])) { $inputReturn .= '<option value="">'.$options['firstElementList'].'</option>'; }

				//Parcours de l'ensemble des données du select
				foreach($options['datas'] as $k => $v) {
					
					if(!is_array($v)) {
						
						if($value != '' && $value == $k) { $selected=' selected="selected"'; } else { $selected = ''; }
						$inputReturn .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';						
					} else {
						
						$inputReturn .= '<optgroup label="'.$k.'">';
						foreach($v as $k1 => $v1) {
						
							if($value != '' && $value == $k1) { $selected=' selected="selected"'; } else { $selected = ''; }
							$inputReturn .= '<option value="'.$k1.'"'.$selected.'>'.$v1.'</option>';							
						}
						$inputReturn .= ' </optgroup>';						
					}
				}
				if(count($options['datas']) == 0) { $inputReturn .= '<option></option>'; }
				$inputReturn .= '</select>';
			break;

			//   INPUT DE TYPE SUBMIT   //
			case 'submit': $inputReturn .= '<input type="submit" id="'.$inputIdText.'" name="'.$inputNameText.'" value="'.$value.'"'.$attributes.' />'; break;

			//   INPUT DE TYPE BUTTON   //
			case 'button': $inputReturn .= '<input type="button" id="'.$inputIdText.'" name="'.$inputNameText.'" value="'.$value.'"'.$attributes.' />'; break;
		}

		//Si on a une erreur et que l'on souhaite afficher les erreurs directement dans le champ input
		$errorLabel = '';
		if($error && $options['displayError']) {

			$errorLabel = '<label for="'.$inputIdText.'" class="error">';
			if(is_array($error)) {

				foreach($error as $k => $v) { $errorLabel .= $v.'<br />'; }
			} else { $errorLabel .= $error; }

			$errorLabel .= '</label>';
		}

		if($options['div']) {

			if(isset($options['divRowBorderTop']) && !$options['divRowBorderTop']) { $styleDiv = ' style="border-top:none"'; } else { $styleDiv = ''; }

			if($options['divright']) { 
				if($options['fulllabelerror']) {
					return '<div class="row'.$classError.'"'.$styleDiv.'>'.$labelReturn.'<div class="rowright">'.$inputReturn.'</div>'.$errorLabel.'</div>';				
				} else {
					return '<div class="row'.$classError.'"'.$styleDiv.'>'.$labelReturn.'<div class="rowright">'.$inputReturn.$errorLabel.'</div>'.'</div>';
				} 
			}
			else { return '<div class="row'.$classError.'"'.$styleDiv.'>'.$labelReturn.$inputReturn.$errorLabel.'</div>'; }
		} else { return $labelReturn.$inputReturn.$errorLabel; }
	}

/**
 * Cette fonction permet la création de la chaine de caractère qui sera le name du champ input
 * Le paramètre principal est une chaine de caractères qui sera de la forme :
 * -> Category.id, Category.descriptif.fr ou Category.descriptif.en
 *
 * En retour celle-ci donnera une chaine du type Category[descriptif][fr] etc...
 *
 * @param 	varchar $name 		Nom du champ input
 * @return 	varchar Chaine de caractère contenant la valeur de l'attribut name du champ input
 * @access	private
 * @author	koéZionCMS
 * @version 0.1 - 25/01/2012 by FI
 */
	function _set_input_name($name) {

		$varName = explode('.', $name); //On créé un tableau par rapport au caractère . --> Category.id donnera un tableau avec deux valeurs
		$return = ''; //Variable retournée par défaut vide
		foreach($varName as $k => $v) { //On parcours le nombre d'éléments du tableau

			//Par défaut lors du premier passage on ne va pas mettre les []
			//Elles ne seront mise qu'à partir du second niveau
			if(strlen($return) == 0) { $return .= $v; }
			else { $return .= '['.$v.']'; }
		}
		return $return;
	}

/**
 * Cette fonction permet la création de la chaine de caractère qui sera le ID du champ input
 * Le paramètre principal est le name du champ input
 *
 * @param 	varchar $id ID du champ input
 * @return 	varchar Chaine de caractère contenant la valeur de l'identifiant du champ input
 * @access	private
 * @author	koéZionCMS
 * @version 0.1 - 25/01/2012 by FI
 */
	function _set_input_id($id) {

		$return = 'input_'.$id;
		$return = str_replace('[', ' ', $return);
		$return = str_replace(']', ' ', $return);
		$return = Inflector::camelize(Inflector::variable($return));
		return $return;
	}

/**
 * Cette fonction permet la récupération de la valeur par défaut du champ input
 *
 * @param 	varchar $name Nom du champ
 * @param 	mixed	$defaultValue Valeur par défaut
 * @return 	mixed Valeur du champ input
 * @access	private
 * @author	koéZionCMS
 * @version 0.1 - 25/01/2012 by FI
 */
	function _get_input_value($name, $defaultValue) {

		//Données postées
		if(Set::check($this->view->controller->request->data, $name)) { return Set::classicExtract($this->view->controller->request->data, $name); } 
		
		//Données non postées
		else if(!isset($this->view->controller->request->data[$name]) && $defaultValue) { return $defaultValue; }
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

		if(empty($templateColor)) { $thumb = '<img src="'.BASE_URL.'/img/backoffice/templates/'.$templateLayout.'/'.$templateCode.'/background.png" />'; } 
		else { $thumb = '<img src="'.$templateColor.'" />'; }
		return '<p '.$selected.'><input name="'.$inputNameText.'" id="'.$inputIdText.$value.'" value="'.$value.'" type="radio" '.$checked.' /><span>'.$templateName.'<br />'.$thumb.'</span></p>';
	}
}
