<?php 
$this->element('frontoffice/breadcrumbs'); 
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
					
					$this->element('frontoffice/posts_list'); 
					$this->element('frontoffice/pagination'); 				
				} else { 
					
					?>Aucun résultat<?php 
				} 
				?>
			</div>		
		
	</div>		
	<?php $this->element('frontoffice/colonne_droite'); ?>
	<div class="clearfix"></div>
</div>