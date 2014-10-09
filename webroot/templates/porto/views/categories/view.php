<?php
$title_for_layout 		= $category['page_title'];
$description_for_layout = $category['page_description'];
$keywords_for_layout 	= $category['page_keywords'];

$contentPage = $this->vars['components']['Text']->format_content_text($category['content']);
?>
<div id="category<?php echo $category['id']; ?>">
	<section class="page-top">
		<div class="container">
			<?php $this->element('breadcrumbs'); ?>
			<div class="row">
				<div class="col-md-12">
					<h2><?php echo $category['name']; ?></h2>
				</div>
			</div>
		</div>
	</section>
	<div class="container">		
		<?php if(count($children) == 0 && count($brothers) == 0 && count($postsTypes) == 0 && count($rightButtons) == 0) { ?>
			<?php echo $contentPage; ?>
		<?php } else { ?>
			<div class="row">
				<div class="col-md-9">
					<?php echo $contentPage; ?>
				</div>
				<div class="col-md-3">
					<aside class="sidebar">
						<h4>Categories</h4>
						<ul class="nav nav-list primary push-bottom">
							<li><a href="#">Design</a></li>
							<li><a href="#">Photos</a></li>
							<li><a href="#">Videos</a></li>
							<li><a href="#">Lifestyle</a></li>
							<li><a href="#">Technology</a></li>
						</ul>								
					</aside>
				</div>
			</div>
		<?php } ?>		
	</div>
</div>