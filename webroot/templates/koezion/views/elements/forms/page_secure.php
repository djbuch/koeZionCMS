<?php 
if(isset($messageType)) { 
	
	?>
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div id="formsmessage">
			<?php	
			if($messageType 		== 'loginError') { ?><p class="error"><?php echo $message; ?></p><?php } 
			else if($messageType 	== 'registerError') { ?><p class="error"><?php foreach($errors as $error) { echo $error.'<br />'; } ?></p><?php } 
			else if($messageType 	== 'registerSuccess') { ?><p class="confirmation"><?php echo $message; ?></p><?php }
			?>
		</div>
	</div>
	<?php
}

if(isset($post)) { $url = Router::url('posts/view/id:'.$post['id'].'/slug:'.$post['slug'].'/prefix:'.$post['prefix']); }
else if(isset($category)) { $url = Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']); }
else { $url = '#'; }
$formOptions = array('id' => 'FormSecure', 'action' => $url, 'method' => 'post');
?>
<div id="form_login" class="col-xs-12 col-md-6">
	<div class="row">
		<div id="form_login" class="col-xs-12 col-sm-12 col-md-12 form connect_form">
			<h3 class="page_title no_border"><?php echo _("Connectez-vous avec vos identifiants"); ?></h3>
			<?php  
			echo $helpers['Form']->create($formOptions);
			echo $helpers['Form']->input('Login.login', _('Identifiant'), array('label' => false, 'placeholder' => _('Identifiant')));
			echo $helpers['Form']->input('Login.password', _('Mot de passe'), array('type' => 'password', 'label' => false, 'placeholder' => _('Mot de passe')));
			?>
			<button type="submit" class="btn btn-default"><?php echo _("Me connecter"); ?></button>
			<?php echo $helpers['Form']->end(); ?>
		</div>
	</div>
	<div class="row">
		<div id="form_lost_password" class="col-xs-12 col-sm-12 col-md-12 form lost_password_form">
			<h3 class="page_title no_border"><?php echo _("Mot de passe oublié"); ?></h3>
			<?php  
			echo $helpers['Form']->create($formOptions);
			echo $helpers['Form']->input('Lost.login', _('Identifiant'), array('label' => false, 'placeholder' => _('Identifiant')));
			?>
			<button type="submit" class="btn btn-default"><?php echo _("Récupérer mon mot de passe"); ?></button>
			<?php echo $helpers['Form']->end(); ?>
		</div>
	</div>	
</div>	
<div id="form_register" class="col-xs-12 col-md-6 form register_form">
	<h3 class="page_title no_border"><?php echo _("Créez un compte"); ?></h3>
	<?php  
	echo $helpers['Form']->create($formOptions);
	echo $helpers['Form']->input('Register.name', _('Nom'), array('label' => false, 'placeholder' => _('Nom')));
	echo $helpers['Form']->input('Register.email', _('Email'), array('label' => false, 'placeholder' => _('Email')));
	echo $helpers['Form']->input('Register.password', _('Mot de passe'), array('type' => 'password', 'label' => false, 'placeholder' => _('Mot de passe')));
	?>
	<button type="submit" class="btn btn-default"><?php echo _("Créer un compte"); ?></button>
	<?php echo $helpers['Form']->end(); ?>
</div>