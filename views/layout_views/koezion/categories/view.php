<?php 
$this->element($websiteParams['tpl_layout'].'/breadcrumbs');
$title_for_layout = $category['page_title'];
$description_for_layout = $category['page_description'];
$keywords_for_layout = $category['page_keywords'];

$contentPage = $this->vars['components']['Text']->format_content_text($category['content']);

if(isset($sliders) && count($sliders) > 0) { $this->element($websiteParams['tpl_layout'].'/slider'); } //Plugin Sliders Catégories
if(isset($focus) && count($focus) > 0) { $this->element($websiteParams['tpl_layout'].'/focus'); } //Plugin Focus Catégories
if(isset($googleMapAPI) && $mapPosition == 'topPage') { $this->element(PLUGINS.DS.'gmaps/views/elements/gmaps/frontoffice/map', null, false); } //Plugin Google Maps
?>
<div class="container_omega">
	<?php	
	
	if(count($children) == 0 && count($brothers) == 0 && count($postsTypes) == 0 && count($rightButtons) == 0) { 
		
		echo $contentPage;
		if(isset($googleMapAPI) && $mapPosition == 'afterTxt') { $this->element(PLUGINS.DS.'gmaps/views/elements/gmaps/frontoffice/map', null, false); } //Plugin Google Maps
		
		if(isset($displayCatalogues) && $displayCatalogues) {
			
			$this->element(PLUGINS.DS.'catalogues/views/elements/catalogues/frontoffice/list', null, false);
			$this->element($websiteParams['tpl_layout'].'/pagination');
		}

		if(isset($displayWinesearchers) && $displayWinesearchers) {
		
			$this->element(PLUGINS.DS.'winesearchers/views/elements/winesearchers/frontoffice/list', null, false);
			$this->element($websiteParams['tpl_layout'].'/pagination');
		}

		if(isset($portfolio) && !empty($portfolio)) { $this->element(PLUGINS.DS.'portfolios/views/elements/portfolios/frontoffice/portfolio', array('fullPage' => true), false); }
		
		if($category['display_form']) {
			
			if(isset($formPlugin)) { $this->element(PLUGINS.DS.'formulaires/views/elements/formulaires/frontoffice/formulaire', null, false); }
			else { $this->element($websiteParams['tpl_layout'].'/formulaires/formulaire_contact'); } 
		}	
		
		$this->element($websiteParams['tpl_layout'].'/posts_list', array('cssZone' => ''));
		
	} else { 

		?>		
		<div class="gs_8">
			<div class="gs_8 omega">
				<?php		
				echo $contentPage;
				if(isset($googleMapAPI) && $mapPosition == 'afterTxt') { $this->element(PLUGINS.DS.'gmaps/views/elements/gmaps/frontoffice/map', null, false); } //Plugin Google Maps
								
				if(isset($portfolio) && !empty($portfolio)) { $this->element(PLUGINS.DS.'portfolios/views/elements/frontoffice/portfolio', array('fullPage' => false), false); }
				
				if($category['display_form']) { 
			
					if(isset($formPlugin)) { $this->element(PLUGINS.DS.'formulaires/views/elements/formulaires/frontoffice/formulaire', null, false); }
					else { $this->element($websiteParams['tpl_layout'].'/formulaires/formulaire_contact'); } 
				}
				?>
			</div>		
			
			<?php $this->element($websiteParams['tpl_layout'].'/posts_list'); ?>
		</div>		
		<?php 
		$this->element($websiteParams['tpl_layout'].'/colonne_droite'); 
	}
	?>
	<div class="clearfix"></div>
</div>
<?php 
if(isset($googleMapAPI) && $mapPosition == 'bottomPage') { $this->element(PLUGINS.DS.'gmaps/views/elements/gmaps/frontoffice/map', null, false); } //Plugin Google Maps	
	 
//On contrôle la nécessité de l'utilisation de la coloration syntaxique
if(substr_count($contentPage, '<pre class="brush')) {
	
	$css = array(
		'layout/'.$websiteParams['tpl_layout'].'/syntaxhighlighter/shCore',
		'layout/'.$websiteParams['tpl_layout'].'/syntaxhighlighter/shCoreDefault'
	);
	echo $helpers['Html']->css($css, true);
	
	$js = array(
		'layout/'.$websiteParams['tpl_layout'].'/syntaxhighlighter/shCore',
		'layout/'.$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushCss',
		'layout/'.$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushJScript',
		'layout/'.$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushPhp',
		'layout/'.$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushPlain',
		'layout/'.$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushSql',
		'layout/'.$websiteParams['tpl_layout'].'/syntaxhighlighter/shBrushXml'
	);
	echo $helpers['Html']->js($js);
	?><script type="text/javascript">SyntaxHighlighter.all()</script><?php 
}
?>