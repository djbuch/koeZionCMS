<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#documents"><?php echo _("Documents"); ?></a></li>
		<li><a href="#seo"><?php echo _("SEO"); ?></a></li>
		<li><a href="#options"><?php echo _("Options"); ?></a></li>
	</ul>
	<div id="general">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('category_id', 'Page parente', array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => "Indiquez dans qu'elle page sera diffusé ce produit"));			
			echo $helpers['Form']->input('category_name', 'Titre de la catégorie', array('tooltip' => "Indiquez le titre de la catégorie du produit."));
			echo $helpers['Form']->input('name', 'Titre', array('tooltip' => "Indiquez le titre du produit. Ce champ sera utilisé (par défaut) comme titre de page dans les moteurs de recherche, 70 caractères maximum recommandé"));
			echo $helpers['Form']->input('reference', 'Référence', array('tooltip' => "Indiquez la référence du produit"));
			echo $helpers['Form']->input('family', 'Famille', array('tooltip' => "Indiquez la famille du produit"));
			echo $helpers['Form']->input('country', 'Région', array('tooltip' => "Indiquez la région du produit"));
			echo $helpers['Form']->input('color', 'Couleur', array('tooltip' => "Indiquez la couleur du produit"));
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
	<div id="seo">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('slug', 'Url', array('tooltip' => "Indiquez l'url que vous souhaitez mettre en place. ATTENTION n'utiliser que des caractères autorisés pour l'url (Que des lettres, des chiffres et -)"));
			echo $helpers['Form']->input('prefix', "Préfixe d'url", array('value' => CATALOGUE_PREFIX, 'tooltip' => "Indiquez le préfixe à utiliser pour accéder à ce produit (Le préfixe est situé juste avant l'url et vous permet de rajouter un ou deux mots clés dans l'url) <br />ATTENTION n'utiliser que des caractères autorisés pour le préfixe (Que des lettres, des chiffres et -)")); //Voir dans le fichier routes.php pour l'initialisation
			echo $helpers['Form']->input('page_title', 'Meta title', array('tooltip' => "Titre de la page (70 caractères maximum recommandé, par défaut ce champ aura pour valeur le champ Titre)"));
			echo $helpers['Form']->input('page_description', 'Meta description', array('tooltip' => "Résumé de la page html, 160 caractères maximum recommandé"));
			echo $helpers['Form']->input('page_keywords', 'Meta keywords', array('tooltip' => "Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (Optionnel)"));		
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