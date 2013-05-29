<?php 
if(isset($message)) { echo $message; }
$formOptions = array('id' => 'FormSecure', 'action' => Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']), 'method' => 'post');
echo $helpers['Form']->create($formOptions);
$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
?>
	<div id="form_container">		
		<div id="form_contact">
			<?php 
			echo $helpers['Form']->input('formulaire_secure', '', array('type' => 'hidden', 'value' => 1)); 
			echo $helpers['Form']->input('login', 'Identifiant');
			echo $helpers['Form']->input('password', 'Mot de passe', array('type' => 'password'));
			?>
			<p><?php echo $helpers['Form']->input('envoyer', _('Envoyer'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Envoyer'))));  ?></p>
		</div>
	</div>
<?php echo $helpers['Form']->end(); ?>