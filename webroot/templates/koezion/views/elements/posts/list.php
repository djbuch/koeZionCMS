<?php 
if(isset($posts) && count($posts) > 0) {  
	
	$postsListTitle = $websiteParams['txt_posts'];
	?>
	<div id="posts_element">
		<?php if($container) { ?><div class="container"><?php } ?>
			<?php if($postsListTitle) { ?><h2 class="page_title"><?php echo $postsListTitle; ?></h2><?php } ?>
			<div class="row masonry">
				<?php				
				foreach($posts as $post) {
				
					$postUrl = Router::url('posts/view/id:'.$post['id'].'/slug:'.$post['slug'].'/prefix:'.$post['prefix']);
					if(!empty($post['redirect_to'])) { $postUrl = $post['redirect_to']; }
		
					//Contrôle de la route à mettre en place
					if($this->params['controllerName'] == 'Categories') { 
						
						$categoryName 	= $category['name'];
						$postBaseRoute 	= Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']);					 
					} else { 
						
						$categoryLink 	= $this->request('Categories', 'get_category_link', array($post['category_id']));
						$categoryName 	= $categoryLink['name'];
						$postBaseRoute 	= Router::url('categories/view/id:'.$categoryLink['id'].'/slug:'.$categoryLink['slug']);				
					}	
					
					$writer 	= $this->request('Users', 'get_user_libelle', array($post['created_by'])); 
					$postDate 	= $this->vars['components']['Text']->date_sql_to_human($post['modified']);
					$inTypes 	= $this->request('PostsTypes', 'get_posts_types', array($post['id']));					
					?>
					<div class="masonry_item <?php echo $postClass; ?>">
						<article class="thumbnail">
							<?php /*IMAGE D'ILLUSTRATION*/ ?>
							<?php if(!empty($post['short_content_illustration'])) { ?>
								<a href="<?php echo $postUrl; ?>"><img src="<?php echo $post['short_content_illustration']; ?>" alt="<?php echo $post['name']; ?>" /></a>
							<?php } ?>
							<div class="caption">
							
								<?php /*LIBELLE*/ ?>
								<div class="article_libelle">
									<a href="<?php echo $postUrl; ?>"><h4><?php echo $post['name']; ?></h4></a>
								</div>
								
								<?php /*REDACTEUR - DATE DE PARUTION*/ ?>
			                    <div class="article_infos">			                     
			                    	<a href="<?php echo $postBaseRoute; ?>?date=<?php echo $postDate['sql']; ?>" class="date"><i class="glyphicon glyphicon-calendar"></i> <?php echo $postDate['txt']; ?></a> | 
			                    	<a href="<?php echo $postBaseRoute; ?>?writer=<?php echo $writer['id']; ?>" class="writer"><i class="glyphicon glyphicon-user"></i> <?php echo $writer['name']; ?></a>
			                    	<?php 
									if($post['display_form']) {
										
										$nbComments = $this->request('PostsComments', 'get_nb_comments', array($post['id']));
										if($nbComments == 0) { $nbCommentsTxt = _('aucun commentaire'); } else { $nbCommentsTxt = $nbComments.' '._('commentaires'); }
										?> | <span class="comments"><i class="glyphicon glyphicon-comment"></i> <?php echo $nbCommentsTxt; ?></span><?php
									}
									if(!empty($post['shooting_time'])) { ?> | <span class="time"><i class="glyphicon glyphicon-time"></i> <?php echo $post['shooting_time']; ?></span><?php } 
									?>
								</div>
								
								<?php /*TYPES*/ ?>
			                    <?php           
			                    if(!empty($inTypes)) {
			                    	?>
				                    <div class="tags">
				                    	<?php foreach($inTypes as $kType => $vType) { ?><a href="<?php echo $postBaseRoute; ?>?typepost=<?php echo $vType['id']; ?>" class="tag"><?php echo $vType['name']; ?></a><?php } ?>
									</div>
									<?php 
			                    }
								?>
								
								<?php /*CONTENU*/ ?>
								<div class="article_content">
									<?php echo $this->vars['components']['Text']->format_content_text($post['short_content']); ?>
								</div>
	                    
	                    		<?php /*BOUTON*/ ?>
	                    		<?php if($post['display_link']) { ?>
		                    		<div class="article_button">
		                    			<p><a href="<?php echo $postUrl; ?>" class="btn btn-default" role="button"><?php echo _('En savoir +'); ?></a></p>		                    			
									</div>  
								<?php } ?>								
							</div>
						</article>
					</div>
					<?php 
				} 
				?>	
			</div>
		<?php if($container) { ?></div><?php } ?>
	</div>
	<?php 
} 