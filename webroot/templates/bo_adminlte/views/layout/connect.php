<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <title><?php echo ".:: "._("Connexion backoffice"); ?></title>
	    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="generator" content="<?php echo GENERATOR_META; ?>" /><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE BALISE ?>
		<meta name="robots" content="noindex">			
		<?php
		$css = array(
			'bo_adminlte/bootstrap/css/bootstrap.min',
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
			'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
			'bo_adminlte/css/AdminLTE.min',
		);
		echo $helpers['Html']->css($css);
	
		if(!empty($websiteParams['connect_background'])) {
		
			?>
			<style type="text/css">
				.login-page{background-image: url("<?php echo $websiteParams['connect_background']; ?>");}	
			</style>
			<?php
		}
		
		if(!empty($websiteParams['connect_css_file'])) { ?><link href="<?php echo $websiteParams['connect_css_file']; ?>" rel="stylesheet" type="text/css" media="all" /><?php }
		?>
    	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	<body class="hold-transition login-page" data-baseurl="<?php echo BASE_URL; ?>">
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
		<?php 
		////////////////////
		//   JAVASCRIPT   //
		////////////////////
		$js = array(
			'bo_adminlte/plugins/jQuery/jQuery-2.1.4.min',
			'bo_adminlte/bootstrap/js/bootstrap.min'
		);
		echo $helpers['Html']->js($js);
		if(!empty($websiteParams['connect_js_file'])) { ?><script src="<?php echo $websiteParams['connect_js_file']; ?>" type="text/javascript"></script><?php }
		?>
	</body>
</html>