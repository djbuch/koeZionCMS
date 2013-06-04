<?php 
$this->element('breadcrumbs'); 
$title_for_layout = $libellePage;
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout = $websiteParams['seo_page_keywords'];
?>
<div class="container_omega">			
	<h2 class="widgettitle"><?php echo $libellePage; ?></h2>
	<div class="hr"></div>	
	<div class="gs_8">
		
			<div class="gs_8 omega">
				<?php 
				if($pager['totalPages'] > 0) { 
					
					$this->element('posts_list'); 
					$this->element('pagination'); 				
				} else { 
					
					?>Aucun résultat<?php 
				} 
				?>
			</div>		
		
	</div>		
	<?php $this->element('colonne_droite'); ?>
	<div class="clearfix"></div>
</div>