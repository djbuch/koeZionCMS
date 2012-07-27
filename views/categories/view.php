<?php 
$this->element('frontoffice/breadcrumbs');
$title_for_layout = $category['page_title'];
$description_for_layout = $category['page_description'];
$keywords_for_layout = $category['page_keywords'];
?>
<div class="container_omega">
	<?php 
	if($is_full_page) { 
		
		echo $category['content'];
		if($category['display_contact_form']) { $this->element('frontoffice/formulaire_contact'); }
	} 
	else { 
		?>		
		<div class="gs_8">
			<div class="gs_8 omega">
				<?php 
				echo $category['content']; 
				if($category['display_contact_form']) { $this->element('frontoffice/formulaire_contact'); }
				?>
			</div>		
			
			<?php if(isset($displayPosts) && $displayPosts) { ?>
				<h2 class="widgettitle"><?php echo $libellePage; ?></h2>
				<div class="hr"></div>	
				<div class="gs_8 omega">
					<?php $this->element('frontoffice/posts_list'); ?>
					<?php $this->element('frontoffice/pagination'); ?>
				</div>		
			<?php } ?>
		</div>		
		<?php 
		$this->element('frontoffice/colonne_droite'); 
	}
	?>
	<div class="clearfix"></div>
</div>