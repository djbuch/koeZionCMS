<?php 
$this->element('breadcrumbs'); 
$title_for_layout 		= _("Résultats de votre recherche sur le terme : ").$q;
$description_for_layout = _("Résultats de votre recherche sur le terme : ").$q;
?>
<div id="searches" class="index">
	<h2><?php echo _("Résultat de la recherche sur le terme : ").$q; ?></h2>
	<?php
	if(isset($hits) && !empty($hits)) {
		 
		foreach($hits as $hit) {						
			
			?><h3><a href="<?php echo $hit['url']; ?>"><?php echo $hit['title']; ?></a></h3><?php 
			echo $hit['description'];
		 }
	} else { echo _("Aucun résultat");  }
	?>			
</div>