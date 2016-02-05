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
		<?php 
		echo $content_for_layout;
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