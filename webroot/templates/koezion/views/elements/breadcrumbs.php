<?php if((isset($breadcrumbs) && !empty($breadcrumbs)) || (isset($breadcrumbsPost) && !empty($breadcrumbsPost))) { ?>
	<div id="breadcrumbs_element">
		<div class="container">
			<div class="row">		
				<div class="col-xs-12 col-sm-12 col-md-12">		
					<ol class="breadcrumb">
						<li><a href="<?php echo Router::url('home/index'); ?>" class="home"><?php echo _("Accueil"); ?></a></li>
						<?php 
						if(isset($breadcrumbs)) {						
							foreach($breadcrumbs as $breadcrumbsKey => $breadcrumbsValue) { 
							
								$url = Router::url('categories/view/id:'.$breadcrumbsValue['id'].'/slug:'.$breadcrumbsValue['slug']);
								?><li><a href="<?php echo $url; ?>"><?php echo $breadcrumbsValue['name']; ?></a></li><?php 
							} 
						}
						
						if(isset($breadcrumbsPost)) {
							foreach($breadcrumbsPost as $breadcrumbsKey => $breadcrumbsValue) { 
							
								$url = Router::url('posts/view/id:'.$breadcrumbsValue['id'].'/slug:'.$breadcrumbsValue['slug'].'/prefix:'.$breadcrumbsValue['prefix']);
								?><li><a href="<?php echo $url; ?>"><?php echo $breadcrumbsValue['name']; ?></a></li><?php 
							} 
						}
						
						if(isset($breadcrumbsPortfolio)) {
							foreach($breadcrumbsPortfolio as $breadcrumbsKey => $breadcrumbsValue) { 
							
								$url = Router::url('portfolios/view/id:'.$breadcrumbsValue['id'].'/slug:'.$breadcrumbsValue['slug'].'/prefix:'.$breadcrumbsValue['prefix']);
								?><li><a href="<?php echo $url; ?>"><?php echo $breadcrumbsValue['name']; ?></a></li><?php 
							} 
						}
						?>
					</ol>
				</div>
			</div>
		</div>
	</div>
<?php } ?>