<?php
$formOptions = array('id' => 'websiteForm', 'action' => Router::url($this->controller->request->url, '').'#formulaire', 'method' => 'post');
echo $helpers['Form']->create($formOptions);
$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
?>
	<div id="form_container">		
		<div id="formulaire">
			<?php 
			if(isset($message)) { echo $message; }
			echo create_form($formPlugin['formulaire'], $formPlugin['formInfos'], $helpers); 
			?>
			<p style="position:relative;min-height:28px;"><?php echo $helpers['Form']->input('envoyer', _('Envoyer'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Envoyer'))));  ?></p>
		</div>
	</div>
<?php echo $helpers['Form']->end(); ?>
<div class="clearfix">&nbsp;</div>
<?php 
	
/**
 * function create_form
 * 
 * Pour le moment elle est ici pour aller plus vite car il faudra surcharger les helpers si le plugins en a besoin
 * Ce n'est pas totalement incompatible avec la notion MVC car au final cette fonction ne fait qu'afficher de l'html
 * 
 * Cette fonction va permettre la génération du formulaire html des formulaires
 * Elle prend en paramètre un tableau contenant la liste de différentes questions
 * 
 * @param array $formulaire Tableau contenant les différentes questions du formulaire de demande de devis
 */	
	function create_form($formulaire, $formInfos, $helpers) {
			
		if(!empty($formInfos['libelle'])) { ?><h3 class="widgettitle"><?php echo $formInfos['libelle']; ?></h3><?php } 
	
		$cptLigne = 0; //Compteur de ligne
		
		//On va parcourir le formulaire et afficher les différentes questions
		foreach($formulaire as $numeroQuestion => $question) {
								
			if($question['constraint'] == 'notEmpty') { $sTxtConstraint = ' <i class="required">*</i> '; } else { $sTxtConstraint = ""; }	
			$cptLigne++;					
								
			//Test sur le type d'élément
			switch($question['type']) {
				case 'select':
					
					$inputName = $question['name'][0];
					$params = array('type' => 'select');
					if(isset($question['input_params']) && !empty($question['input_params'])) {
						
						foreach($question['input_params'] as $k => $v) { $params[$k] = $v; }
					}	
					$inputValues = $question['option'];	
					$params['datas'] = $inputValues;										
					echo $helpers['Form']->input($inputName, $sTxtConstraint.$question['label'], $params);
					
				break;
				
				case 'text':
					
					$inputName = $question['name'][0];
					$params = array('displayError' => false);
					if(isset($question['input_params']) && !empty($question['input_params'])) {
						
						foreach($question['input_params'] as $k => $v) { $params[$k] = $v; }
					}							
					echo $helpers['Form']->input($inputName, $sTxtConstraint.$question['label'], $params);
					
				break;
				
				case 'textarea':
					$inputName = $question['name'][0];
					$params = array('displayError' => false, 'type' => 'textarea', 'rows' => '5', 'cols' => '10');
					if(isset($question['input_params']) && !empty($question['input_params'])) {
						
						foreach($question['input_params'] as $k => $v) { $params[$k] = $v; }
					}
					echo $helpers['Form']->input($inputName, $sTxtConstraint.$question['label'], $params);
					
				break;
				
				case 'checkbox':							
					
					$aInputName = $question['name'];
					$inputValues = $question['option'];
					$cptName = 0;
					
					$donneesPostees = $this->controller->request->data;
					
					echo '<div class="row">';
					foreach($inputValues as $value => $sLibelle) {							
						
						if(!empty($question['label']) && $question['label'] != '' && $cptName == 0) { echo '<label>'.$sTxtConstraint.$question['label'].'</label>'; }
						
						$inputNameText = $helpers['Form']->_set_input_name($aInputName[$cptName]); //Mise en variable du name de l'input
						$inputIdText = $helpers['Form']->_set_input_id($inputNameText); //Mise en variable de l'id de l'input
						
						echo '<div class="rowright">';

							if(isset($donneesPostees[$inputNameText]) && $donneesPostees[$inputNameText] == $value) { $checked = " checked"; }
							else { $checked = ""; }
							echo '<input type="hidden" id="'.$inputIdText.'hidden" name="'.$inputNameText.'" value="" />';
							echo '<input type="checkbox" id="'.$inputIdText.'" name="'.$inputNameText.'" value="'.$value.'"'.$checked.' />';			
							echo ' '.$value;						
						echo '</div>';
														
						$cptName++;							
					}	
					echo '</div>';
																
				break;
				
				case 'radio':
					
					$inputName = $question['name'][0];
					$inputNameText = $helpers['Form']->_set_input_name($inputName); //Mise en variable du name de l'input
					$inputIdText = $helpers['Form']->_set_input_id($inputNameText); //Mise en variable de l'id de l'input
					
					$donneesPostees = $this->controller->request->data;
					
					$inputValues = $question['option'];
					$cptName = 0;
					
					echo '<div class="row">';
					echo '<input type="hidden" id="'.$inputIdText.'hidden" name="'.$inputNameText.'" value="" />';
					foreach($inputValues as $value => $sLibelle) {								
						
						if(!empty($question['label']) && $question['label'] != '' && $cptName == 0) { echo '<label>'.$sTxtConstraint.$question['label'].'</label>'; }
						echo '<div class="rowright">';	

							if(isset($donneesPostees[$inputNameText]) && $donneesPostees[$inputNameText] == $value) { $checked = " checked"; }
							else { $checked = ""; }
							echo '<input type="radio" id="'.$inputIdText.'" name="'.$inputNameText.'" value="'.$value.'"'.$checked.' />';			
							echo ' '.$value;						
						echo '</div>';
						
						$cptName++;							
					}
					echo '</div>';
					
				break;
				
				case 'hidden':
					
					$inputName = $question['name'][0];
					echo $helpers['Form']->input($inputName, '', array('type' => 'hidden', 'value' => $question['default']));
					
				break;
				
			}						
		}
	}
?>
