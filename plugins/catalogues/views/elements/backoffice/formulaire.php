<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#documents"><?php echo _("Documents"); ?></a></li>
		<li><a href="#options"><?php echo _("Options"); ?></a></li>
	</ul>
	<div id="general">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('category_id', 'Page parente', array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => "Indiquez dans qu'elle page sera diffusé ce produit", 'firstElementList' => 'Sélectionnez la page parente'));			
			echo $helpers['Form']->input('name', 'Titre', array('tooltip' => "Indiquez le titre du produit. Ce champ sera utilisé (par défaut) comme titre de page dans les moteurs de recherche, 70 caractères maximum recommandé"));
			echo $helpers['Form']->input('reference', 'Référence', array('tooltip' => "Indiquez la référence du produit"));
			echo $helpers['Form']->input('price', 'Prix', array('tooltip' => "Indiquez le prix du produit"));
			echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser cette page"));
			?>
		</div>
	</div>
	<div id="documents">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->upload_files_products();		
			?>
		</div>
	</div>	
	<div id="options">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('is_coup_coeur', 'Coup de coeur', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce produit en coup de coeur"));
			echo $helpers['Form']->input('disponibility', 'Disponible', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour indiquer que ce produit est disponible"));
			?>
		</div>
	</div>	
</div>