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
			
			$document = $hit->getDocument();
			$title = $document->getField('title')->value;
			$description = $document->getField('description')->value;
			$slug = $document->getField('slug')->value;
			$id = $document->getField('pk')->value;
			$model = $document->getField('model')->value;			
			?>
			<h3><?php echo $title; ?></h3>			
			<?php 						
			echo "<p>".$description."</p>";
			
			switch($model) {

				case 'Category': $url = Router::url('categories/view/id:'.$id.'/slug:'.$slug); break;				
				case 'Post': 
					
					$prefix = $document->getField('prefix')->value;
					$url = Router::url('posts/view/id:'.$id.'/slug:'.$slug.'/prefix:'.$prefix); 
				break;
			}
			?>
			<p><a href="<?php echo $url; ?>" class="superbutton"><?php echo _("En savoir +"); ?></a></p>
			<div class="hr"><div class="inner_hr"></div></div>
			<?php
		 }
	} else { echo _("Aucun résultat");  }
	?>			
	<div class="clearfix"></div>
</div>