<?php 
$formOptions = array('id' => 'websiteForm', 'action' => Router::url($this->controller->request->url, '').'#formulaire', 'method' => 'post');
echo $helpers['Form']->create($formOptions);
?>			
<div id="formulaire">
	<h3><?php echo _("Laissez un commentaire"); ?></h3>
	<?php 	
	if(isset($message)) { echo $message; }			 
	echo $helpers['Form']->input('type_formulaire', '', array('type' => 'hidden', 'value' => 'comment')); 
	echo $helpers['Form']->input('post_id', '', array('type' => 'hidden', 'value' => $post['id']));
	echo $helpers['Form']->input('name', _('Nom'));
	echo $helpers['Form']->input('email', _('Email'));
	echo $helpers['Form']->input('cpostal', _('Code postal'));
	echo $helpers['Form']->input('message', _('Message'), array('type' => 'textarea', 'rows' => '5', 'cols' => '10'));
	?>
	<p><?php echo $helpers['Form']->input('envoyer', _('Envoyer'), array('type' => 'submit', 'value' => _('Envoyer')));  ?></p>
</div>
<?php 
echo $websiteParams['txt_after_form_comments']; 
echo $helpers['Form']->end();