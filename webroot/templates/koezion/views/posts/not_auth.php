<?php 
$title_for_layout 		= $post['page_title']; 
$description_for_layout = $post['page_description']; 
$keywords_for_layout 	= $post['page_keywords'];

$this->element('breadcrumbs');
?>
<section id="not_auth" class="page_content">	
	<?php
	//////////////////////////////
	//    CONTENU DE LA PAGE    //
	?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<?php echo $post['txt_secure']; ?>
			</div> 
			<?php $this->element('forms/page_secure'); ?>	
			<div class="form">
				<div class="col-xs-12 col-sm-12 col-md-12 form_tip legacy"><?php echo $websiteParams['txt_after_form_user']; ?></div>
			</div>
		</div>
	</div>
</section>