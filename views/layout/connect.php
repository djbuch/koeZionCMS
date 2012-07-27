<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php //echo $html->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo _(".:: Zone sécurisée ::."); ?></title>
		
		<?php
		$css = array(
			'backoffice/style',
			'backoffice/style_text',
			'backoffice/login',
			'backoffice/forms',
			'backoffice/form-buttons',
			'backoffice/system-messages',
			'backoffice/smart_tab',			//Pour la mise en place des smarttabs
		);
		echo $helpers['Html']->css($css);			
		
		$js = array(
			'backoffice/jquery-1.7.1.min', 		//Librairie JQuery
			'backoffice/jquery.smartTab',		//Pour la mise en place des smarttabs			
			'connect/costum', 					//Appel des différents plugins
		);
		echo $helpers['Html']->js($js);
		?>
	</head>

	<body>
		<div id="wrapper" class="login">		
			<div id="right">
				<div id="main">
					<?php $this->element('backoffice/flash_messages'); ?>
					<div class="section">
						<div class="box">
							<div class="title">
								<h2><?php echo _("Zone sécurisée"); ?></h2>
							</div>
							<div class="content nopadding">
							
								<div class="smarttabs nobottom">
									
									<?php /* ?>
									<ul class="anchor">
										<li><a href="#tabs-1"><?php echo _("Login"); ?></a></li> 
										<li><a href="#tabs-2"><?php echo _("Mot de passe oublié?"); ?></a></li>
									</ul>
									<?php */ ?>
									
									<div id="tabs-1">
										<form method="post" action="<?php echo Router::url('users/login'); ?>" id="UserLogin">										
											<div class="row">
												<label><?php echo _("Identifiant"); ?></label>
												<div class="rowright"><input type="text" name="login" id="inputlogin" /></div>
											</div>
											<div class="row">
												<label><?php echo _("Mot de passe"); ?></label>
												<div class="rowright"><input type="password" name="password" id="inputpassword" /></div>
											</div>
											<div class="row">
												<div class="rowright button">
													<button type="submit" class="medium grey"><span><?php echo _("Connectez vous"); ?></span></button>
												</div>
											</div>
										</form>
									</div>
									
									<?php /* ?>									
									<div id="tabs-2">
										<form method="post" action="<?php echo Router::url('users/lost_password'); ?>" id="UserLostPassword">	
											<div class="row">
												<label><?php echo _("Identifiant"); ?></label>
												<div class="rowright"><input type="text" name="login" id="inputlogin" /></div>
											</div>
											<div class="row">
												<div class="rowright button">
													<button type="submit" class="medium grey"><span><?php echo _("Valider"); ?></span></button>
												</div>
											</div>
										</form>
									</div>
									<?php */ ?>	
								</div>
								
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</body>
</html>