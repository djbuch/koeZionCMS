<?php
$formOptions = array('id' => 'websiteForm', 'action' => Router::url($this->controller->request->url, '').'#formulaire', 'method' => 'post');
echo $helpers['Form']->create($formOptions);
?>		
<div id="formulaire">
	<?php 
	if(isset($message)) { echo $message; } 
	echo $helpers['Form']->input('type_formulaire', '', array('type' => 'hidden', 'value' => 'contact')); 
	echo $helpers['Form']->input('name', _('Nom')); 
	echo $helpers['Form']->input('phone', _('Téléphone')); 
	echo $helpers['Form']->input('email', _('Email'));
	echo $helpers['Form']->input('cpostal', _('Code postal'));
	echo $helpers['Form']->input('message', _('Message'), array('type' => 'textarea', 'rows' => '5', 'cols' => '10'));
	?>
	<p><?php echo $helpers['Form']->input('envoyer', _('Envoyer'), array('type' => 'submit', 'value' => _('Envoyer')));  ?></p>
</div>
<?php echo $websiteParams['txt_after_form_contact']; ?>
<?php echo $helpers['Form']->end(); ?>
