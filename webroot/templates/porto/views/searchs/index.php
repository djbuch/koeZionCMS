<?php 
$title_for_layout 		= _("Résultats de votre recherche sur le terme : ").$q;
$description_for_layout = _("Résultats de votre recherche sur le terme : ").$q;
?>
<section class="page-top">
	<div class="container">
		<div class="row">
			<?php $this->element('breadcrumbs'); ?>
			<div class="row">
				<div class="col-md-12">
					<h2><?php echo _("Résultat de la recherche sur le terme : ").$q; ?></h2>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="container">
	<div class="search-results">
		<?php
		if(isset($hits) && !empty($hits)) {
			 
			foreach($hits as $hit) {						
				
				?><h3><a href="<?php echo $hit['url']; ?>"><?php echo $hit['title']; ?></a></h3><?php 
				echo $hit['description'];
			 }
		} else { echo _("Aucun résultat");  }
		?>		
	</div>
</div>