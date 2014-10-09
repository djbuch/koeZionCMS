<?php 
$title_for_layout = $websiteParams['seo_page_title'];
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout = $websiteParams['seo_page_keywords'];
?>
<section class="page-top">
					<div class="container">
						<?php $this->element('breadcrumbs'); ?>
						<div class="row">
							<div class="col-md-12">
								<h2>404 - Page Not Found</h2>
							</div>
						</div>
					</div>
				</section>

				<div class="container">

					<section class="page-not-found">
						<div class="row">
							<div class="col-md-6 col-md-offset-1">
								<div class="page-not-found-main">
									<h2>404 <i class="fa fa-file"></i></h2>
									<p><?php echo _("Désolé, mais il semble que la page que vous cherchez ait été supprimée, ait changée de nom ou soit temporairement indisponible."); ?></p>
									<?php 
									$httpHost = $_SERVER["HTTP_HOST"];
									if($httpHost == 'localhost' || $httpHost == '127.0.0.1') {
								
										$redirectMessage = Session::read('redirectMessage');
										?><p><?php echo $redirectMessage; ?></p><?php 
									}									
									?>
								</div>
							</div>
							<div class="col-md-4">
								<h4><?php echo _("Pour poursuivre votre navigation utilisez un des liens suivants"); ?></h4>
								<ul class="nav nav-list primary">
									<li><a href="#">Home</a></li>
									<li><a href="#">About Us</a></li>
									<li><a href="#">FAQ's</a></li>
									<li><a href="#">Sitemap</a></li>
									<li><a href="#">Contact Us</a></li>
								</ul>
							</div>
						</div>
					</section>

				</div>