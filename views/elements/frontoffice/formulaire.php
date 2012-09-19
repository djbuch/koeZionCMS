<?php
$formOptions = array('id' => 'websiteForm', 'action' => Router::url($this->controller->request->url, '').'#formulaire', 'method' => 'post');
echo $helpers['Form']->create($formOptions);
$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
?>
	<div id="form_container">		
		<div id="formulaire">
			<?php 
			if(isset($message)) { echo $message; }
			echo $helpers['Form']->create_form($formulaire, $formInfos); 
			?>
			<p style="position:relative;min-height:28px;"><?php echo $helpers['Form']->input('envoyer', _('Envoyer'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Envoyer'))));  ?></p>
		</div>
	</div>
<?php echo $helpers['Form']->end(); ?>
<div class="clearfix">&nbsp;</div>
