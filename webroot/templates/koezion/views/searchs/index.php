<?php 
$title_for_layout 		= _("Résultats de votre recherche sur le terme : ").$q;
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout 	= $websiteParams['seo_page_keywords'];
?>
<section id="search" class="page_content">	
	<?php
	$this->element('breadcrumbs');
	
	//////////////////////////////
	//    CONTENU DE LA PAGE    //
	?>
	<div class="content container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<h2><?php echo _("Résultat de la recherche sur le terme : ").$q; ?></h2>
				<?php
				if(isset($hits) && !empty($hits)) { 
					
					foreach ($hits as $hit) {
									
						?>
						<h3><a href="<?php echo $hit['url']; ?>"><?php echo $hit['title']; ?></a></h3>			
						<?php echo "<p>".$hit['description']."</p>"; ?>
						<div class="hr"><div class="inner_hr"></div></div>
						<?php
					 }
				} else { echo _("Aucun résultat");  }
				?>
			</div>			
		</div>
	</div>
</section>