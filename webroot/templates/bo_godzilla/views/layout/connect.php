<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php //echo $html->docType(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php 
		if(isset($websiteParams['seo_page_title']) && !empty($websiteParams['seo_page_title'])) { echo '<title>'.$websiteParams['seo_page_title'].'</title>'; echo "\n"; } 
		if(isset($websiteParams['seo_page_description']) && !empty($websiteParams['seo_page_description'])) { echo "\t\t"; echo '<meta name="description" content="'.$websiteParams['seo_page_description'].'" />'; echo "\n"; }
		if(isset($websiteParams['seo_page_keywords']) && !empty($websiteParams['seo_page_keywords'])) { echo "\t\t"; echo '<meta name="keywords" content="'.$websiteParams['seo_page_keywords'].'" />'; echo "\n"; }
		?>
		<meta name="generator" content="<?php echo GENERATOR_META; ?>" /><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE BALISE ?>		
		<?php
		$css = array(
			'bo_godzilla/css/style',
			'bo_godzilla/css/style_text',
			'bo_godzilla/css/login',
			'bo_godzilla/css/forms',
			'bo_godzilla/css/form-buttons',
			'bo_godzilla/css/system-messages',
			'bo_godzilla/css/smart_tab',			//Pour la mise en place des smarttabs
		);
		echo $helpers['Html']->css($css);	
	
		if(!empty($websiteParams['connect_background'])) {
		
			?>
			<style type="text/css">
				body{background: url("<?php echo $websiteParams['connect_background']; ?>");}	
			</style>
			<?php
		}
		
		if(!empty($websiteParams['connect_css_file'])) { ?><link href="<?php echo $websiteParams['connect_css_file']; ?>" rel="stylesheet" type="text/css" media="all" /><?php }
		?>
	</head>
	<body>
		<?php 
		if(!empty($websiteParams['connect_text'])) { ?><div class="connect_text"><?php echo $websiteParams['connect_text']; ?></div><?php }
		?>
		<div id="wrapper" class="login">		
			<div id="right">
				<div id="main">
					<?php $this->element(WEBROOT.DS.'templates'.DS.BACKOFFICE_TEMPLATE.DS.'views'.DS.'elements'.DS.'flash_messages'); ?>
					<div class="section">
						<div class="box">
							<div class="title">
								<h2><?php echo _("Zone sécurisée"); ?></h2>
							</div>
							<div class="content nopadding">							
								<div class="nobottom">									
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
							</div>							
						</div>						
					</div>										
				</div>
				<a href="<?php echo Router::url('/', 'html', true, 'http'); ?>" title="<?php echo _("Aller sur le site"); ?>" style="float:right;font-size:10px;font-style:italic;text-decoration:none;"><?php echo _("Aller sur le site"); ?></a>
			</div>
		</div>						
		<?php 
		////////////////////
		//   JAVASCRIPT   //
		////////////////////
		$js = array(
			'bo_godzilla/js/jquery-1.7.1.min', 	//Librairie JQuery
			'bo_godzilla/js/jquery.smartTab',		//Pour la mise en place des smarttabs			
			'/connect/custom', 					//Appel des différents plugins
		);
		echo $helpers['Html']->js($js);
		if(!empty($websiteParams['connect_js_file'])) { ?><script src="<?php echo $websiteParams['connect_js_file']; ?>" type="text/javascript"></script><?php }
		?>
	</body>
</html>