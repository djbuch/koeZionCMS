<?php 
$title_for_layout 		= $websiteParams['seo_page_title'];
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout 	= $websiteParams['seo_page_keywords'];
?>
<div class="home e404">
	<div class="container">
		<div class="row">
			<div class="jumbotron">
				<h1>[404]</h1>
				<p><?php echo _("La page n'a pu être trouvée."); ?></p>
				<p><?php echo _("Désolé, mais il semble que la page que vous cherchez ait été supprimée, ait changée de nom ou soit temporairement indisponible."); ?></p>
				<?php 
				$httpHost = $_SERVER["HTTP_HOST"];
				if($httpHost == 'localhost' || $httpHost == '127.0.0.1') {

					$redirectMessage = Session::read('redirectMessage');
					?><p><?php echo $redirectMessage; ?></p><?php 
				}
				?>
				<p><a href="<?php echo Router::url('home/index'); ?>" class="btn btn-primary btn-lg"><?php echo _("Retour sur la page d'accueil"); ?></a></p>
			</div>
		</div>
	</div>
</div>