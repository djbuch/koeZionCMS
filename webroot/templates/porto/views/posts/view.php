<?php 
$this->element('breadcrumbs');
$title_for_layout 		= $post['page_title']; 
$description_for_layout = $post['page_description']; 
$keywords_for_layout 	= $post['page_keywords'];

$contentPage = $this->vars['components']['Text']->format_content_text($post['content']);

if(count($children) == 0 && count($brothers) == 0 && count($postsTypes) == 0 && count($rightButtons) == 0) { $column = false; } 
else { $column = true; }
?>
<div id="posts" class="view post<?php echo $post['id']; ?>">
	<?php 
	if($column) { $this->element('column'); }	
	?>
	<h2 class="post_title"><?php echo $post['name']; ?></h2>	
	<p class="post_details">
		<?php 					
		$categoryLink = $this->request('Categories', 'get_category_link', array($post['category_id']));
		$categoryName = $categoryLink['name'];
		$postBaseRoute = Router::url('categories/view/id:'.$categoryLink['id'].'/slug:'.$categoryLink['slug']);				
		
		$postDate = $this->vars['components']['Text']->date_sql_to_human($post['modified']);
		echo '<a href="'.$postBaseRoute.'?date='.$postDate['sql'].'">'.$postDate['txt'].'</a>';
			
		if($post['display_form']) {
			
			$nbComments = $this->request('PostsComments', 'get_nb_comments', array($post['id']));
			if($nbComments == 0) { $nbCommentsTxt = _('aucun'); } else { $nbCommentsTxt = $nbComments; }				
			echo '|'.$nbCommentsTxt.' commentaire(s)';
		}
						
		$writer = $this->request('Users', 'get_user_libelle', array($post['created_by']));				
		echo '| '._('par').' '.'<a href="'.$postBaseRoute.'?writer='.$writer['id'].'">'.$writer['name'].'</a>';				
		
		$inTypes = $this->request('PostsTypes', 'get_posts_types', array($post['id']));
		if(count($inTypes) > 0) {
			
			echo '| tags ';
			$inTypesDisplay = array();
			foreach($inTypes as $kType => $vType) { $inTypesDisplay[] = '<a href="'.$postBaseRoute.'?typepost='.$vType['id'].'">'.$vType['name'].'</a>'; }					
			
			echo implode(', ', $inTypesDisplay);
		}
		
		echo '| '._('dans').' '.'<a href="'.$postBaseRoute.'">'.$categoryName.'</a>';
					
		if(!empty($post['shooting_time'])) {
			
			echo '| '.$helpers['Html']->img('koezion/img/watch_time.png', array('style' => "position:relative;top:2px;")).' '.$post['shooting_time'];	
		}
		?>
	</p>	
	<?php 
	echo $contentPage; 
	if($post['display_form']) { $this->element('formulaires/formulaire_commentaires'); } 		
	if($postsComments) {
		
		foreach($postsComments as $k => $v) {
			
			?>
			<div class="posts_comments">					
				<p class="post_message"><?php echo $v['message']; ?></p>
				<p class="post_name">par <?php echo $v['name']; ?></p>
			</div>
			<?php 
		}		
	}
	?>
</div>