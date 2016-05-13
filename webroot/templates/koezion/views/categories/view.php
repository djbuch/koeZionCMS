<?php
$title_for_layout 		= $category['page_title'];
$description_for_layout = $category['page_description'];
$keywords_for_layout 	= $category['page_keywords'];

////////////////////////////////////////////////////////////
//    SI ON DOIT CHARGER DES CSS ET DES JS SPECIFIQUES    //
//CSS
if(!empty($category['css_file'])) {

	$css = array('F'.str_replace(BASE_URL, '', $category['css_file']));
	$helpers['Html']->css($css, true);
}

//JS
if(!empty($category['js_file'])) {

	$js = array('F'.str_replace(BASE_URL, '', $category['js_file']));
	$helpers['Html']->js($js, true);
}
////////////////////////////////////////////////////////////
?>
<section id="category<?php echo $category['id']; ?>" class="page_content categories view">	
	<?php  
	$this->element('slides');
	$this->element('header_page', array(
		'pageIllustration' 		=> $category['illustration_1'], 
		'pageIllustrationBis' 	=> $category['illustration_2'],
		'subtitle1' 			=> $category['subtitle_1'], 
		'subtitle2' 			=> $category['subtitle_2'], 
		'pageTitle' 			=> $category['name']
	));
	
	$this->element('contact_map');
	$this->element('breadcrumbs');
	
	$this->element('focus');	
	
	//////////////////////////////
	//    CONTENU DE LA PAGE    //
	?>
	<div class="content container">
		<div class="row">
			<?php 
			if(count($children) == 0 && count($brothers) == 0 && count($postsTypes) == 0 && count($rightButtons) == 0) {
								
				$this->element('category_details', array('rightColumn' => false));
				
			} else {
				
				$this->element('column_page');
				?>
				<div class="col-md-9">
					<?php $this->element('category_details', array('rightColumn' => true)); ?>
				</div>				
				<?php 
			}
			?>
		</div>
	</div>
	<?php 
	$this->element('footer_page', array(
		'pageIllustration' 		=> $category['illustration_1'], 
		'pageIllustrationBis' 	=> $category['illustration_2'],
		'subtitle1' 			=> $category['subtitle_1'], 
		'subtitle2' 			=> $category['subtitle_2'], 
		'pageTitle' 			=> $category['name']
	));
	?>
</section>