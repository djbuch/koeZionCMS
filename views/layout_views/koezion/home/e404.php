<?php 
$this->element($websiteParams['tpl_layout'].'/breadcrumbs'); 
$title_for_layout = $websiteParams['seo_page_title'];
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout = $websiteParams['seo_page_keywords'];
?>
<div class="container_omega">
	<h1 id="title404" class="e404 alignleft"><strong>[404]</strong></h1>
	<h1><?php echo _("La page n'a pu être trouvée."); ?></h1>
	<h6 class="e404"><?php echo _("Désolé, mais il semble que la page que vous cherchez ait été supprimée, ait changée de nom ou soit temporairement indisponible."); ?></h6>
	<?php 
	$httpHost = $_SERVER["HTTP_HOST"];
	if($httpHost == 'localhost' || $httpHost == '127.0.0.1') {

		$redirectMessage = Session::read('redirectMessage');
		?><p><?php echo $redirectMessage; ?></p><?php 
	}
	
	?>	
	<p><a href="<?php echo Router::url('home/index'); ?>" class="superbutton"><?php echo _("Retour sur la page d'accueil"); ?></a></p>	
	<div class="clearfix"></div>
</div>