<?php if(isset($displayPosts) && $displayPosts) { ?>
	<?php /* ?>
	<h2 class="widgettitle"><?php echo $titlePostsList; ?></h2>
	<div class="hr"></div>
	<?php */ ?>
	
	<?php if(!isset($cssZone)) { $cssZone = 'gs_8'; } ?>
	<div class="<?php echo $cssZone; ?> omega">
		<?php foreach($posts as $k => $v) { ?>
			<div class="post_holder">
				<h2 class="post_header"><a href="<?php echo Router::url('posts/view/id:'.$v['id'].'/slug:'.$v['slug'].'/prefix:'.$v['prefix']); ?>"><?php echo $v['name']; ?></a></h2>
				<p class="post_info">
					<?php 
					//Contrôle de la route à mettre en place
					if($this->params['controllerName'] == 'Categories') { 
						
						$categoryName = $category['name'];
						$postBaseRoute = Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']);					 
					} else { 
						
						//$postBaseRoute = Router::url('posts/listing');					
						$categoryLink = $this->request('Categories', 'get_category_link', array($v['category_id']));
						$categoryName = $categoryLink['name'];
						$postBaseRoute = Router::url('categories/view/id:'.$categoryLink['id'].'/slug:'.$categoryLink['slug']);				
					}
					
					$postDate = $this->vars['components']['Text']->date_sql_to_human($v['modified']);
					echo '<a href="'.$postBaseRoute.'?date='.$postDate['sql'].'">'.$postDate['txt'].'</a>';
						
					$nbComments = $this->request('PostsComments', 'get_nb_comments', array($v['id']));
					if($nbComments == 0) { $nbCommentsTxt = _('aucun'); } else { $nbCommentsTxt = $nbComments; }				
					echo '|'.$nbCommentsTxt.' commentaire(s)';
									
					$writer = $this->request('Users', 'get_user_libelle', array($v['modified_by']));				
					echo '| '._('par').' '.'<a href="'.$postBaseRoute.'?writer='.$writer['id'].'">'.$writer['name'].'</a>';				
					
					$inTypes = $this->request('PostsTypes', 'get_posts_types', array($v['id']));
					if(count($inTypes) > 0) {
						
						echo '| tags ';
						$inTypesDisplay = array();
						foreach($inTypes as $kType => $vType) { $inTypesDisplay[] = '<a href="'.$postBaseRoute.'?typepost='.$vType['id'].'">'.$vType['name'].'</a>'; }					
						
						echo implode(', ', $inTypesDisplay);
					}
					
					echo '| '._('dans').' '.'<a href="'.$postBaseRoute.'">'.$categoryName.'</a>';	
					
					//if(isset($v['code']) && !empty($v['code'])) { echo '| '.str_replace('[ARTICLE_ID]', $v['id'], $v['code']); }
					?>
				</p>
				<div class="hr"></div>
				<?php		
				echo $this->vars['components']['Text']->format_content_text($v['short_content']); 
				//echo $v['short_content']; 
				if($v['display_link']) { ?><p class="post_link"><a href="<?php echo Router::url('posts/view/id:'.$v['id'].'/slug:'.$v['slug'].'/prefix:'.$v['prefix']); ?>" class="superbutton"><?php echo _('En savoir +'); ?></a></p><?php } 
				?>
			</div>
			<div class="clearfix"></div>
		<?php } ?>			
	</div>
	<?php $this->element('pagination'); ?>
	
<?php } ?>