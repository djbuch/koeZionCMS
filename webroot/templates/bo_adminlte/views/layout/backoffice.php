<!DOCTYPE html>
<html>
	<head>
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<title><?php echo ".:: "._("Système d'administration")." | ".$this->params['controllerName']." - ".$this->params['action']." ::."; ?></title>
	  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">    
    	<?php
		$css = array(
			'bo_adminlte/bootstrap/css/bootstrap.min', /*Bootstrap 3.3.5*/
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', /*Font Awesome*/
			'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css', /*Ionicons*/
			'bo_adminlte/css/hooks/jquery-ui', /*jQuery UI*/
			'bo_adminlte/css/AdminLTE', /*Theme style*/
			'bo_adminlte/css/skins/skin-blue.min', /*AdminLTE Skins*/
			'bo_adminlte/plugins/iCheck/flat/blue', /*iCheck*/
			'bo_adminlte/plugins/morris/morris', /*Morris chart*/
			'bo_adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2', /*jvectormap*/
			'bo_adminlte/plugins/datepicker/datepicker3', /*Date Picker*/
			'bo_adminlte/plugins/daterangepicker/daterangepicker-bs3', /*Daterange picker*/
			'bo_adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min', /*bootstrap wysihtml5 - text editor*/
			'bo_adminlte/css/hooks/alerts', /*jQuery Alerts*/
			'bo_adminlte/css/hooks/backoffice', /*Hooks Backoffice*/
		);
		echo $helpers['Html']->css($css);
		?>
	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	<body data-baseurl="<?php echo BASE_URL; ?>" class="hold-transition skin-blue sidebar-mini"><?php /*sidebar-collapse */ ?>
    	<div class="wrapper">
			<?php $this->element('top_bar'); ?>
			<?php $this->element('left'); ?>
      		<div class="content-wrapper">
	        	<?php echo $content_for_layout; ?>
			</div>      
      		<?php $this->element('footer'); ?>
      		<?php $this->element('right'); ?>      
    	</div>
		<?php	    
		$js = array(
			'bo_adminlte/plugins/jQuery/jQuery-2.1.4.min', /*jQuery 2.1.4*/
			'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js', /*jQuery UI 1.11.4*/
			'bo_adminlte/js/resolve_conflict.php', //Resolve conflict in jQuery UI tooltip with Bootstrap tooltip		
			'bo_adminlte/bootstrap/js/bootstrap.min', /*Bootstrap 3.3.5*/
			'/commun/jquery.livequery', /*Utile pour le chargement des pages en ajax*/
			'/commun/scripts', /*Scripts perso*/
			'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js', /*Morris.js charts*/
			'bo_adminlte/plugins/morris/morris.min', /*Morris.js charts*/
			'bo_adminlte/plugins/sparkline/jquery.sparkline.min', /*Sparkline*/
			'bo_adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min', /*jvectormap*/
			'bo_adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en', /*jvectormap*/
			'bo_adminlte/plugins/knob/jquery.knob', /*jQuery Knob Chart*/
			'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js', /*daterangepicker*/
			'bo_adminlte/plugins/daterangepicker/daterangepicker', /*daterangepicker*/
			'bo_adminlte/plugins/datepicker/bootstrap-datepicker', /*datepicker*/
			'bo_adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min', /*Bootstrap WYSIHTML5*/
			'bo_adminlte/plugins/slimScroll/jquery.slimscroll.min', /*Slimscroll*/
			'bo_adminlte/plugins/fastclick/fastclick.min', /*FastClick*/
			'bo_adminlte/js/app.min', /*AdminLTE App*/	
			'/../ck/ckeditor/ckeditor', /*Librairie CKEditor*/
			'/../ck/ckfinder/ckfinder', /*Librairie CKFinder*/
			'bo_adminlte/js/hooks/jquery.alerts', /*jQuery Alerts*/
			'bo_adminlte/js/hooks/backoffice' /*Hooks Backoffice*/
		);
		echo $helpers['Html']->js($js);
		
		$helpers['Form']->ckeditor();		
		include_once(ELEMENTS.DS.'js'.DS.'buttons_right_column.php'); 
		?>	
		<script type="text/javascript">
			// do this before the first CKEDITOR.replace( ... )
			CKEDITOR.timestamp = Math.random().toString(36).substring(0, 5);
        </script>
	</body>
</html>