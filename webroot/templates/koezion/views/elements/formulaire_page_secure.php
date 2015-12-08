<?php 
if(isset($messageType)) { 
	
	if($messageType == 'loginError') { echo '<p class="error">'.$message.'</p>'; } 
	else if($messageType == 'registerError') { 
		
		echo '<p class="error">';
		foreach($errors as $error) { echo $error.'<br />'; }		
		echo '</p>'; 
	} 
	else if($messageType == 'registerSuccess') { 
		
		echo '<p class="confirmation">';
		echo $message;
		echo '</p>'; 
	} 
}

if(isset($post)) { $url = Router::url('posts/view/id:'.$post['id'].'/slug:'.$post['slug'].'/prefix:'.$post['prefix']); }
else if(isset($category)) { $url = Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']); }
else { $url = '#'; }
$formOptions = array('id' => 'FormSecure', 'action' => $url, 'method' => 'post');
$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
?>
<div id="form_container">		
	<div id="form_login" class="gs_5">
		<h3><?php echo _("Connectez-vous avec vos identifiants"); ?></h3>
		<?php  
		echo $helpers['Form']->create($formOptions);
		echo $helpers['Form']->input('Login.login', _('Identifiant'), array('divright' => false));
		echo $helpers['Form']->input('Login.password', _('Mot de passe'), array('type' => 'password', 'divright' => false));
		?>
		<p><?php echo $helpers['Form']->input('connecter', _('Me connecter'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Me connecter'))));  ?></p>
		<?php echo $helpers['Form']->end(); ?>
		
		<h3><?php echo _("Mot de passe oublié"); ?></h3>
		<?php  
		echo $helpers['Form']->create($formOptions);
		echo $helpers['Form']->input('Lost.login', _('Identifiant'), array('divright' => false));
		?>
		<p><?php echo $helpers['Form']->input('recuperer', _('Récupérer'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Récupérer mon mot de passe'))));  ?></p>
		<?php echo $helpers['Form']->end(); ?>
	</div>	
	<div id="form_register" class="gs_5">
		<h3><?php echo _("Créez un compte"); ?></h3>
		<?php  
		echo $helpers['Form']->create($formOptions);
		echo $helpers['Form']->input('Register.name', _('Nom'), array('divright' => false));
		echo $helpers['Form']->input('Register.email', _('Email'), array('divright' => false));
		echo $helpers['Form']->input('Register.password', _('Mot de passe'), array('type' => 'password', 'divright' => false));
		?>
		<p><?php echo $helpers['Form']->input('creer', _('Créer un compte'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Créer un compte'))));  ?></p>
		<?php echo $helpers['Form']->end(); ?>
	</div>
</div>
