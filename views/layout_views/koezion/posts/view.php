<?php 
$this->element($websiteParams['tpl_layout'].'/breadcrumbs');
$title_for_layout = $post['page_title']; 
$description_for_layout = $post['page_description']; 
$keywords_for_layout = $post['page_keywords'];

?>
<div class="container_omega">
	
	<?php echo $this->vars['components']['Text']->format_content_text($post['content']); ?>	
	<div class="clearfix"></div>
	<?php
	if($post['display_form']) { 
		
		//$this->element($websiteParams['tpl_layout'].'/formulaire', array('formulaire' => $formulaire, 'formInfos' => $formInfos));
		if(isset($formPlugin)) { $this->element(PLUGINS.DS.'formulaires/views/formulaires/elements/frontoffice/formulaire', null, false); } 
		else { $this->element($websiteParams['tpl_layout'].'/formulaires/formulaire_commentaires'); } 
	}
 		
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