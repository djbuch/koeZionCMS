<?php if(isset($displayPosts) && $displayPosts) { ?>
	
	<?php if(!isset($cssZone)) { $cssZone = 'gs_8'; } ?>
	<div class="<?php echo $cssZone; ?> omega">
		<?php
		foreach($posts as $k => $v) { 
			
			$postUrl = Router::url('posts/view/id:'.$v['id'].'/slug:'.$v['slug'].'/prefix:'.$v['prefix']);
			if(!empty($v['redirect_to'])) { $postUrl = $v['redirect_to']; }
			?>
			<div class="post_holder">
				<h2 class="post_header"><a href="<?php echo $postUrl; ?>"><?php echo $v['name']; ?></a></h2>
				<p class="post_info">
					<?php 
					//Contrôle de la route à mettre en place
					if($this->params['controllerName'] == 'Categories') { 
						
						$categoryName = $category['name'];
						$postBaseRoute = Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']);					 
					} else { 
						
						$categoryLink = $this->request('Categories', 'get_category_link', array($v['category_id']));
						$categoryName = $categoryLink['name'];
						$postBaseRoute = Router::url('categories/view/id:'.$categoryLink['id'].'/slug:'.$categoryLink['slug']);				
					}
					
					$postDate = $this->vars['components']['Text']->date_sql_to_human($v['modified']);
					echo '<a href="'.$postBaseRoute.'?date='.$postDate['sql'].'">'.$postDate['txt'].'</a>';
						
					if($v['display_form']) {
						
						$nbComments = $this->request('PostsComments', 'get_nb_comments', array($v['id']));
						if($nbComments == 0) { $nbCommentsTxt = _('aucun'); } else { $nbCommentsTxt = $nbComments; }				
						echo '|'.$nbCommentsTxt.' commentaire(s)';
					}		
					$writer = $this->request('Users', 'get_user_libelle', array($v['created_by']));				
					echo '| '._('par').' '.'<a href="'.$postBaseRoute.'?writer='.$writer['id'].'">'.$writer['name'].'</a>';				
					
					$inTypes = $this->request('PostsTypes', 'get_posts_types', array($v['id']));
					if(count($inTypes) > 0) {
						
						echo '| tags ';
						$inTypesDisplay = array();
						foreach($inTypes as $kType => $vType) { $inTypesDisplay[] = '<a href="'.$postBaseRoute.'?typepost='.$vType['id'].'">'.$vType['name'].'</a>'; }					
						
						echo implode(', ', $inTypesDisplay);
					}
					
					echo '| '._('dans').' '.'<a href="'.$postBaseRoute.'">'.$categoryName.'</a>';	
					
					if(!empty($v['shooting_time'])) {
						
						echo '| '.$helpers['Html']->img('koezion/img/watch_time.png', array('style' => "position:relative;top:2px;")).' '.$v['shooting_time'];	
					}
					?>
				</p>
				<div class="hr"></div>
				<?php		
				echo $this->vars['components']['Text']->format_content_text($v['short_content']); 
				if($v['display_link']) { ?><p class="post_link"><a href="<?php echo $postUrl; ?>" class="superbutton"><?php echo _('En savoir +'); ?></a></p><?php } 
				?>
			</div>
			<div class="clearfix"></div>
			<?php 
		} 
		?>			
	</div>
	<?php $this->element('pagination'); ?>
	
<?php } ?>