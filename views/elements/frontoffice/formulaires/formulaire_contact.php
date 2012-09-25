<?php
$formOptions = array('id' => 'websiteForm', 'action' => Router::url($this->controller->request->url, '').'#form_contact', 'method' => 'post');
echo $helpers['Form']->create($formOptions);
$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
?>
	<div id="form_container">		
		<div id="form_contact">
			<?php 
			if(isset($message)) { echo $message; } 
			echo $helpers['Form']->input('type_formulaire', '', array('type' => 'hidden', 'value' => 'contact')); 
			echo $helpers['Form']->input('name', _('Nom'), am($commonOptions, array("value" => _('Indiquez votre nom'), "title" => _('Indiquez votre nom')))); 
			echo $helpers['Form']->input('phone', _('Téléphone'), am($commonOptions, array("value" => _('Indiquez votre téléphone'), "title" => _('Indiquez votre téléphone')))); 
			echo $helpers['Form']->input('email', _('Email'), am($commonOptions, array("value" => _('Indiquez votre email'), "title" => _('Indiquez votre email'))));
			echo $helpers['Form']->input('message', _('Message'), am($commonOptions, array("value" => _('Indiquez votre message'), "title" => _('Indiquez votre message'), 'type' => 'textarea', 'rows' => '5', 'cols' => '10')));
			?>
			<p><?php echo $helpers['Form']->input('envoyer', _('Envoyer'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Envoyer'))));  ?></p>
		</div>
	</div>
<?php echo $helpers['Form']->end(); ?>
<div class="clearfix">&nbsp;</div>
