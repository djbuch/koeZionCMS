<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#textes"><?php echo _("Descriptifs court et long"); ?></a></li>
		<li><a href="#types"><?php echo _("Types d'article"); ?></a></li>
		<li><a href="#seo"><?php echo _("SEO"); ?></a></li>
		<li><a href="#options"><?php echo _("Options"); ?></a></li>
		
		<?php 
		//On ne va afficher ce menu que si le site courant est sécurisé
		$websitesSession = Session::read('Backoffice.Websites'); //Récupération de la variable de session
		$currentWebsite = $websitesSession['current']; //Récupération du site courant
		$websiteDetails = $websitesSession['details'][$currentWebsite]; //Récupération du détail du site courant
		$isSecure = $websiteDetails['secure_activ']; //On va vérifier si celui-ci est sécurisé
		if($isSecure) {
			
			?><li><a href="#emailing"><?php echo _("Emailing"); ?></a></li><?php 
		}
		?>
	</ul>
	<div id="general">
		<div class="content nopadding">
		<?php 
		echo $helpers['Form']->input('category_id', 'Catégorie parente', array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => "Indiquez la catégorie parente de cet article, c'est à partir de cette catégorie que cet article sera accessible"));
		echo $helpers['Form']->input('name', "<i>(*)</i> Titre de l'article", array('tooltip' => "Indiquez le titre de l'article. Ce champ sera utilisé comme titre de page dans les moteurs de recherche, 70 caractères maximum recommandé"));
		echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser cet article"));
		?>		
		</div>
	</div>
	<div id="textes">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('short_content', 'Descriptif court', array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Saisissez ici le descriptif court de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider"));
			echo $helpers['Form']->input('content', 'Descriptif long', array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Saisissez ici le descriptif long de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider"));
			?>
		</div>
	</div>
	<div id="types">
		<div class="content nopadding">
			<div class="row" style="overflow:hidden;">
				<label>
					<?php echo $helpers['Html']->img('backoffice/tooltip.png', array("original-title" => "Cochez les types d'article (Plusieurs choix possibles)", "class" => "tip-w", "style" => "float: left; margin-right: 5px; cursor: pointer;", "alt" => "tooltip")); ?>
					Type d'article
				</label>
				<div class="rowright">
					<?php 
					foreach($postsTypes as $k => $v) {
						?><span class="checkbox" style="float: left; display: block; margin: 0 20px 20px 0; width: 30%; line-height: 15px;"><?php
						echo $helpers['Form']->input('posts_type_id.'.$v['id'], $v['name'].' (<i>dans '.$v['column_title'].'</i>)', array('type' => 'checkbox', 'div' => false));
						?></span><?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div id="seo">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('prefix', "<i>(*)</i> Préfixe d'url", array('value' => POST_PREFIX, 'tooltip' => "Indiquez le préfixe à utiliser pour accéder à cet article (Le préfixe est situé juste avant l'url et vous permet de rajouter un ou deux mots clés dans l'url) <br />ATTENTION n'utiliser que des caractères autorisés pour le préfixe (Que des lettres, des chiffres et -)")); //Voir dans le fichier routes.php pour l'initialisation		
			echo $helpers['Form']->input('slug', 'Url', array('tooltip' => "Indiquez l'url que vous souhaitez mettre en place. ATTENTION n'utiliser que des caractères autorisés pour l'url (Que des lettres, des chiffres et -)"));
			echo $helpers['Form']->input('page_title', 'Meta title', array('tooltip' => "Titre de la page (70 caractères maximum recommandé, par défaut ce champ aura pour valeur le champ Titre)"));
			echo $helpers['Form']->input('page_description', 'Meta description', array('tooltip' => "Résumé de la page html, 160 caractères maximum recommandé"));
			echo $helpers['Form']->input('page_keywords', 'Meta keywords', array('tooltip' => "Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (optionnel)"));
			?>
		</div>
	</div>
	<div id="options">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('display_link', "Afficher un lien sous forme de bouton à la suite de l'article", array('type' => 'checkbox', 'tooltip' => "En cochant cette case vous afficherez automatiquement le lien pour se rendre sur le détail de l'article, par défaut le titre de l'article sera également cliquable"));						
			echo $helpers['Form']->input('display_home_page', "Afficher cet article sur la la page d'accueil", array('type' => 'checkbox', 'tooltip' => "En cochant cette case vous afficherez cet article sur la page d'accueil du site"));

			if(!isset($formulaires)) { $formulaires = array (2 => 'Formulaire commentaire article'); } 
			echo $helpers['Form']->input('display_form', 'Formulaire', array('type' => 'select', 'datas' => $formulaires, 'tooltip' => "Indiquez le formulaire que vous souhaitez afficher sur la page", 'firstElementList' => "Sélectionnez un formulaire"));

			
			echo $helpers['Form']->input('publication_date', 'Date de publication', array("class" => "datepicker", "placeholder" => "dd.mm.yy", 'tooltip' => "Indiquez la date à laquelle cet article sera publié"));
			
			?>
		</div>
	</div>
	<?php if($isSecure) { ?>			
		<div id="emailing">
			<div class="content nopadding">
				<?php 
				echo $helpers['Form']->input('send_mail', "Envoyer un email pour informer les utilisateurs de l'ajout (ou de la modification)", array('type' => 'checkbox', 'tooltip' => "En cochant cette case un email sera automatiquement envoyer à l'ensemble des utilisateurs référencés dans le système"));
				echo $helpers['Form']->input('message_mail', 'Contenu email newsletter', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte qui sera envoyé par email"));
				?>
			</div>
		</div>
	<?php } ?>
</div>