<?php 
$title_for_layout 		= $websiteParams['seo_page_title'];
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout 	= $websiteParams['seo_page_keywords'];
?>
<section class="home index">
	<?php
	$this->element('slides');
	$this->element('focus');
	$this->element('slogan');
	$this->element('posts/list', array('container' => true, 'postClass' => 'col-xs-12 col-sm-6 col-md-4'));
	?>
</section>