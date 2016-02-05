<div class="login-box">
	<?php if(!empty($websiteParams['connect_logo'])) { ?><div class="login-logo"><?php echo $websiteParams['connect_logo']; ?></div><?php } ?>			
	<div class="login-box-body">
		<?php $this->element(WEBROOT.DS.'templates'.DS.BACKOFFICE_TEMPLATE.DS.'views'.DS.'elements'.DS.'flash_messages'); ?>				
		<?php if(!empty($websiteParams['connect_text'])) { ?><div class="login-box-msg"><?php echo $websiteParams['connect_text']; ?></div><?php } ?>
		<form class="login-form" method="post" action="<?php echo Router::url('users/login'); ?>" id="UserLogin">
			<div class="form-group has-feedback">
				<input class="form-control" type="text" placeholder="<?php echo _("Login/Email"); ?>" name="login" id="inputlogin" />
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input class="form-control" type="password" placeholder="<?php echo _("Mot de passe"); ?>"  name="password" id="inputpassword" />
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
            	<div class="col-xs-4 pull-right">
            		<button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo _("Connexion"); ?></button>
	        	</div>
	    	</div>
    	</form>
	</div>
</div>