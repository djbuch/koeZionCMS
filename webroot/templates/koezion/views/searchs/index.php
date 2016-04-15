<?php
$this->element('breadcrumbs'); 
$title_for_layout = _("Résultats de votre recherche sur le terme : ").$q;
$description_for_layout = _("Résultats de votre recherche sur le terme : ").$q;
?>
<div class="container_omega min_height">
	<h2 class="widgettitle"><?php echo _("Résultat de la recherche sur le terme : ").$q; ?></h2>
	<div class="hr"></div>	
	<?php
	if(isset($hits) && !empty($hits)) { 
		foreach ($hits as $hit) {
						
			?>
			<h3><a href="<?php echo $hit['url']; ?>"><?php echo $hit['title']; ?></a></h3>			
			<?php echo "<p>".$hit['description']."</p>"; ?>
			<?php /* ?><p><a href="<?php echo $hit['url']; ?>" class="superbutton"><?php echo _("En savoir +"); ?></a></p><?php */ ?>
			<div class="hr"><div class="inner_hr"></div></div>
			<?php
		 }
	} else { echo _("Aucun résultat");  }
	?>			
	<div class="clearfix"></div>
</div>