<?php 
$this->element('frontoffice/breadcrumbs');
$title_for_layout = $post['page_title']; 
$description_for_layout = $post['page_description']; 
$keywords_for_layout = $post['page_keywords'];

?>
<div class="container_omega">
	<?php 		
	echo $this->vars['components']['Text']->format_content_text($post['content']);
	//echo $post['content']; 	
	/*if(isset($post['code']) && !empty($post['code'])) { ?><p class="information"><?php echo str_replace('[ARTICLE_ID]', $post['id'], $post['code']); ?></p><?php }*/ 
	?>	
	<div class="clearfix"></div>
	<?php 
	//Page catégorie avec formulaire de contact
	//01/08/2012 - On n'affiche les commentaires que si l'Internaute peut en déposer
	if($post['display_form']) { $this->element('frontoffice/formulaire', array('formulaire' => $formulaire, 'formInfos' => $formInfos)); }
	//if($post['display_form'] == 1) { $this->element('frontoffice/formulaire_commentaires'); } 
	//else if($post['display_form'] == 2) { $this->element('frontoffice/formulaire_contact'); } 
 		
	if($postsComments) {
		
		?><div class="clearfix"></div><?php
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