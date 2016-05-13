<?php
$title_for_layout 		= $portfolio['page_title']; 
$description_for_layout = $portfolio['page_description']; 
$keywords_for_layout 	= $portfolio['page_keywords'];	

$css = array(
	$websiteParams['tpl_layout'].'/assets/fancyapps-fancyBox-v2.1.5/source/jquery.fancybox',
	$websiteParams['tpl_layout'].'/assets/fancyapps-fancyBox-v2.1.5/source/helpers/jquery.fancybox-buttons',
	$websiteParams['tpl_layout'].'/assets/fancyapps-fancyBox-v2.1.5/source/helpers/jquery.fancybox-thumbs'
);		
echo $helpers['Html']->css($css, true);

$js = array(
	$websiteParams['tpl_layout'].'/assets/fancyapps-fancyBox-v2.1.5/lib/jquery.mousewheel-3.0.6.pack',
	$websiteParams['tpl_layout'].'/assets/fancyapps-fancyBox-v2.1.5/source/jquery.fancybox',
	$websiteParams['tpl_layout'].'/assets/fancyapps-fancyBox-v2.1.5/source/helpers/jquery.fancybox-buttons',
	$websiteParams['tpl_layout'].'/assets/fancyapps-fancyBox-v2.1.5/source/helpers/jquery.fancybox-thumbs',
	$websiteParams['tpl_layout'].'/assets/fancyapps-fancyBox-v2.1.5/source/helpers/jquery.fancybox-media',
	$websiteParams['tpl_layout'].'/js/fancybox'
);
echo $helpers['Html']->js($js, true);
?>
<section id="portfolio<?php echo $portfolio['id']; ?>" class="page_content portfolios view">	
	<?php 
	$this->element('header_page', array(
		'pageIllustration' 	=> $portfolio['content_illustration'], 
		'pageTitle' 		=> $portfolio['name']
	));
	$this->element('breadcrumbs');
	////////////////////////////////
	//    CONTENU DU PORTFOLIO    //
	?>
	<div class="content container">
		<div class="row">
			<?php
			$contentPage 		= $this->vars['components']['Text']->format_content_text($portfolio['content']);
			$categoryLink 		= $this->request('Categories', 'get_category_link', array($category['id']));
			$categoryName 		= $categoryLink['name'];
			$portfolioBaseRoute = Router::url('categories/view/id:'.$categoryLink['id'].'/slug:'.$categoryLink['slug']);
			$portfolioDate 		= $this->vars['components']['Text']->date_sql_to_human($portfolio['modified']);
			$writer 			= $this->request('Users', 'get_user_libelle', array($portfolio['created_by']));
			$inTypes 			= $this->request('PortfoliosTypes', 'get_portfolios_types', array($portfolio['id']));
			?>
			<div class="article_libelle">
				<h2><?php echo $portfolio['name']; ?></h2>
			</div>
			<div class="article_infos">
				<?php /*DATE*/ ?>
				<a href="<?php echo $portfolioBaseRoute; ?>?date=<?php echo $portfolioDate['sql']; ?>"><i class="glyphicon glyphicon-calendar"></i> <?php echo $portfolioDate['txt']; ?></a>
				<span class="separator">&nbsp;</span>
				
				<?php 
				/////////////////////
				//    REDACTEUR    // 
				?>
				<a href="<?php echo $portfolioBaseRoute; ?>?writer=<?php echo $writer['id']; ?>"><i class="glyphicon glyphicon-user"></i> <?php echo $writer['name']; ?></a>
				<span class="separator">&nbsp;</span>
			</div>
			<?php       
			///////////////////////////
			//    TYPES D'ARTICLE    //
			if(!empty($inTypes)) {
				?>
			    <div class="tags">
			    	<?php foreach($inTypes as $kType => $vType) { ?><a href="<?php echo $portfolioBaseRoute; ?>?typepost=<?php echo $vType['id']; ?>" class="tag"><?php echo $vType['name']; ?></a><?php } ?>
				</div>
				<?php 
			}
			?>
			<div class="article_content">
				<?php echo $contentPage; ?>
				<div class="masonry">					
					<?php 
					if($portfolio['automatic_scan']) {
						
						//Transformation du chemin de récupération des images
						$folder 			= dirname($portfolio['source_folder']);
						$folder 			= str_replace(BASE_URL.'/webroot', '', $folder);
						$folder 			= str_replace('/', DS, $folder);
						$directoryContent 	= FileAndDir::directoryContent(WEBROOT.$folder);
						
						foreach($directoryContent as $content) {
							
							$filePath = str_replace('//', '/', BASE_URL.'/'.$folder.'/'.$content);
							?>
							<div class="col-xs-12 col-md-2 masonry_item">
								<a class="fancybox" href="<?php echo $filePath; ?>" data-fancybox-group="gallery">
									<img src="<?php echo $filePath; ?>" class="img-responsive thumbnail" />
								</a>
							</div>
							<?php 
							
						}
					} else if(isset($portfoliosElements)) {
						
						foreach($portfoliosElements as $portfoliosElement) {
							
							$descriptif = array();
							if(!empty($portfoliosElement['description_line_1'])) { $descriptif[] = $portfoliosElement['description_line_1']; }
							if(!empty($portfoliosElement['description_line_2'])) { $descriptif[] = $portfoliosElement['description_line_2']; }
							if(!empty($portfoliosElement['description_line_3'])) { $descriptif[] = $portfoliosElement['description_line_3']; }
							if(!empty($portfoliosElement['description_line_4'])) { $descriptif[] = $portfoliosElement['description_line_4']; }						
							?>
							<div class="col-xs-12 col-md-2 masonry_item">
								<a 
									class="fancybox" 
									href="<?php echo $portfoliosElement['illustration']; ?>" 
									data-fancybox-group="gallery" 
									title="<?php if(!empty($descriptif)) { echo implode('<br />', $descriptif); } ?>"
									<?php if(!empty($portfoliosElement['link'])) { ?>  
									data-link="<?php echo $portfoliosElement['link']; ?>" 
									data-linktext="<?php echo !empty($portfoliosElement['link_text']) ? $portfoliosElement['link_text'] : _("En savoir plus..."); ?>" 
									<?php } ?>
								>
									<img src="<?php echo $portfoliosElement['illustration']; ?>" class="img-responsive thumbnail" />
								</a>
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>