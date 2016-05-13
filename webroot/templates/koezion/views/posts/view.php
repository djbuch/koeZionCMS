<?php
$title_for_layout 		= $post['page_title']; 
$description_for_layout = $post['page_description']; 
$keywords_for_layout 	= $post['page_keywords'];
?>
<section id="post<?php echo $post['id']; ?>" class="page_content posts view">	
	<?php
	$this->element('header_page', array(
		'pageIllustration' 	=> $post['content_illustration'], 
		'pageTitle' 		=> $post['name']
	));
	$this->element('breadcrumbs');
	
	////////////////////////////////
	//    CONTENU DE L'ARTICLE    //
	?>
	<div class="content container">
		<div class="row">
			<?php 
			if(count($children) == 0 && count($brothers) == 0 && count($postsTypes) == 0 && count($rightButtons) == 0) {
				
				$this->element('posts/details');
				
			} else {
				
				$this->element('column_page');
				?>
				<div class="col-md-9">
					<?php $this->element('posts/details'); ?>
				</div>				
				<?php 
			}
			?>
		</div>
	</div>
</section>