<?php 
$this->element('frontoffice/breadcrumbs');
$title_for_layout = $category['page_title'];
$description_for_layout = $category['page_description'];
$keywords_for_layout = $category['page_keywords'];

if(isset($sliders) && count($sliders) > 0) { $this->element('frontoffice/nivo_slider'); } //Plugin Sliders Catégories
if(isset($googleMapAPI) && $mapPosition == 'topPage') { $this->element(PLUGINS.DS.'gmaps/views/gmaps/elements/frontoffice/map', null, false); } //Plugin Google Maps
?>
<div class="container_omega">
	<?php	
	if($is_full_page) { 
		
		echo $this->vars['components']['Text']->format_content_text($category['content']);
		if(isset($googleMapAPI) && $mapPosition == 'afterTxt') { $this->element(PLUGINS.DS.'gmaps/views/gmaps/elements/frontoffice/map', null, false); } //Plugin Google Maps
		
		if(isset($displayCatalogues) && $displayCatalogues) {
			
			$this->element(PLUGINS.DS.'catalogues/views/elements/frontoffice/list', null, false);
			$this->element('frontoffice/pagination');
		}		
		
		if($category['display_form']) { 
			
			if(isset($formPlugin)) { $this->element(PLUGINS.DS.'formulaires/views/elements/frontoffice/formulaire', null, false); }
			else { $this->element('frontoffice/formulaires/formulaire_contact'); } 
		}	
	} else { 

		?>		
		<div class="gs_8">
			<div class="gs_8 omega">
				<?php		
				echo $this->vars['components']['Text']->format_content_text($category['content']);
				if(isset($googleMapAPI) && $mapPosition == 'afterTxt') { $this->element(PLUGINS.DS.'gmaps/views/gmaps/elements/frontoffice/map', null, false); } //Plugin Google Maps

				if($category['display_form']) { 
					
					if(isset($formPlugin)) { $this->element(PLUGINS.DS.'formulaires/views/elements/frontoffice/formulaire', null, false); } 
					else { $this->element('frontoffice/formulaires/formulaire_contact'); } 
				}
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
<?php if(isset($googleMapAPI) && $mapPosition == 'bottomPage') { $this->element(PLUGINS.DS.'gmaps/views/gmaps/elements/frontoffice/map', null, false); } //Plugin Google Maps ?>