<?php 
if(isset($portfolios) && count($portfolios) > 0) {  
	
	$portfoliosListTitle = $category['title_portfolios_list'];
	?>
	<div id="portfolios_element">
		
		<?php if($portfoliosListTitle) { ?>
			<div class="col-xs-12 col-sm-12 col-md-12">
				<h2 class="page_title"><?php echo $portfoliosListTitle; ?></h2>
			</div>
		<?php } ?>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="row masonry">
				<?php				
				foreach($portfolios as $portfolio) {
				
					$portfolioUrl = Router::url('portfolios/view/id:'.$portfolio['id'].'/slug:'.$portfolio['slug'].'/prefix:'.$portfolio['prefix']);
					if(!empty($portfolio['redirect_to'])) { $portfolioUrl = $portfolio['redirect_to']; }
		
					//Contrôle de la route à mettre en place
					if($this->params['controllerName'] == 'Categories') { 
						
						$categoryName 	= $category['name'];
						$portfolioBaseRoute 	= Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']);					 
					} else { 
						
						$categoryLink 	= $this->request('Categories', 'get_category_link', array($portfolio['category_id']));
						$categoryName 	= $categoryLink['name'];
						$portfolioBaseRoute 	= Router::url('categories/view/id:'.$categoryLink['id'].'/slug:'.$categoryLink['slug']);				
					}	
					
					$writer 	= $this->request('Users', 'get_user_libelle', array($portfolio['created_by'])); 
					$portfolioDate 	= $this->vars['components']['Text']->date_sql_to_human($portfolio['modified']);
					$inTypes 	= $this->request('PortfoliosTypes', 'get_portfolios_types', array($portfolio['id']));					
					?>
					<div class="masonry_item col-xs-12 col-md-6">
						<article class="thumbnail">
							<?php /*IMAGE D'ILLUSTRATION*/ ?>
							<?php if(!empty($portfolio['short_content_illustration'])) { ?>
								<a href="<?php echo $portfolioUrl; ?>"><img src="<?php echo $portfolio['short_content_illustration']; ?>" alt="<?php echo $portfolio['name']; ?>" /></a>
							<?php } ?>
							<div class="caption">
							
								<?php /*LIBELLE*/ ?>
								<div class="article_libelle">
									<a href="<?php echo $portfolioUrl; ?>"><h4><?php echo $portfolio['name']; ?></h4></a>
								</div>
								
								<?php /*REDACTEUR - DATE DE PARUTION*/ ?>
			                    <div class="article_infos">			                     
			                    	<a href="<?php echo $portfolioBaseRoute; ?>?date=<?php echo $portfolioDate['sql']; ?>" class="date"><i class="glyphicon glyphicon-calendar"></i> <?php echo $portfolioDate['txt']; ?></a> | 
			                    	<a href="<?php echo $portfolioBaseRoute; ?>?writer=<?php echo $writer['id']; ?>" class="writer"><i class="glyphicon glyphicon-user"></i> <?php echo $writer['name']; ?></a>
								</div>
								
								<?php /*TYPES*/ ?>
			                    <?php           
			                    if(!empty($inTypes)) {
			                    	?>
				                    <div class="tags">
				                    	<?php foreach($inTypes as $kType => $vType) { ?><a href="<?php echo $portfolioBaseRoute; ?>?typepost=<?php echo $vType['id']; ?>" class="tag"><?php echo $vType['name']; ?></a><?php } ?>
									</div>
									<?php 
			                    }
								?>
								
								<?php /*CONTENU*/ ?>
								<div class="article_content">
									<?php echo $this->vars['components']['Text']->format_content_text($portfolio['short_content']); ?>
								</div>
	                    
	                    		<?php /*BOUTON*/ ?>
	                    		<div class="article_button">
	                    			<p><a href="<?php echo $portfolioUrl; ?>" class="btn btn-default" role="button"><?php echo _('En savoir +'); ?></a></p>		                    			
								</div>  							
							</div>
						</article>
					</div>
					<?php 
				} 
				?>	
			</div>
		</div>		
	</div>
	<?php 
} 