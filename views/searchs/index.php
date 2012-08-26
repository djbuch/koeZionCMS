<?php 
$this->element('frontoffice/breadcrumbs'); 
$title_for_layout = _("Résultats de votre recherche");
$description_for_layout = _("Résultats de votre recherche");
?>
<div class="container_omega min_height">
	<h2 class="widgettitle"><?php echo _("Résultat de la recherche"); ?></h2>
	<div class="hr"></div>	
	<?php
	if(isset($hits) && !empty($hits)) { 
		foreach ($hits as $hit) {
						
			?>
			<h3><?php echo $hit['title']; ?></h3>			
			<?php echo "<p>".$hit['description']."</p>"; ?>
			<p><a href="<?php echo $hit['url']; ?>" class="superbutton"><?php echo _("En savoir +"); ?></a></p>
			<div class="hr"><div class="inner_hr"></div></div>
			<?php
		 }
	} else { echo _("Aucun résultat");  }
	?>			
	<div class="clearfix"></div>
</div>