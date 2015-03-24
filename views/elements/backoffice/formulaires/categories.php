<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#right_column"><?php echo _("Colonne de droite"); ?></a></li>
		<li><a href="#buttons"><?php echo _("Boutons"); ?></a></li>
		<li><a href="#seo"><?php echo _("SEO"); ?></a></li>
		<li><a href="#options"><?php echo _("Options"); ?></a></li>
		<li><a href="#secure"><?php echo _("Sécuriser la page"); ?></a></li>
		
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
			echo $helpers['Form']->input('parent_id', _('Page parente'), array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => _("Indiquez la page parente de cette page (Racine du site par défaut)")));
			echo $helpers['Form']->input('type', _('Type de page'), array('type' => 'select', 'datas' => $typesOfPage, 'tooltip' => _("Une page évènement n'est pas intégrée dans le menu général, vous pourrez faire des liens vers celle-ci via l'éditeur WYSIWYG")));
			echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre de la page. Ce champ sera utilisé (par défaut) comme titre de page dans les moteurs de recherche, 70 caractères maximum recommandé")));
			echo $helpers['Form']->input('content', _('Contenu'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Saisissez ici le contenu de votre page, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
			echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser cette page")));
			?>
		</div>
	</div>
	<div id="right_column">
		<div class="content nopadding">
			<?php
			//echo $helpers['Form']->input('title_colonne_droite', 'Titre colonne de droite', array('tooltip' => "Indiquez le titre qui sera affiché dans la colonne de droite"));			
			echo $helpers['Form']->input('display_children', _('Afficher les pages enfants dans la colonne de droite'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les enfants de la page seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite")));
			echo $helpers['Form']->input('title_children', _('Titre colonne enfants'), array('tooltip' => _("Indiquez le titre qui sera affiché dans la colonne de droite pour les enfants (Activé que si vous avez cliqué sur la case précédente)")));			
			echo $helpers['Form']->input('display_brothers', _('Afficher les pages du même niveau dans la colonne de droite'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les pages du même niveau seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite")));
			echo $helpers['Form']->input('title_brothers', _('Titre colonne même niveau'), array('tooltip' => _("Indiquez le titre qui sera affiché dans la colonne de droite pour les menus du même niveau (Activé que si vous avez cliqué sur la case précédente)")));			
			?>
		</div>
	</div>
	<div id="buttons">
		<div class="content nopadding">
			<div class="content">
			<p><?php echo _("Précisez ici le ou les boutons à rajouter à cette page"); ?>.</p>
			<?php echo $helpers['Form']->input('rightButtonsListId', '', array('type' => 'select', 'datas' => $rightButton, 'label' => false, 'div' => false, 'displayError' => false, 'firstElementList' => _("Sélectionnez un bouton"))); ?>
			<a id="addRightButton" class="btn blue btnselect"><span><?php echo _("Rajouter ce bouton"); ?></span></a>
		</div>		
		<?php
		if(isset($this->controller->request->data['right_button_id'])) {
		
			foreach($this->controller->request->data['right_button_id'] as $rightButtonId => $isActiv) {
			
				$this->element('backoffice/right_button_line', array('rightButtonId' => $rightButtonId, 'rightButtonName' => $rightButton[$rightButtonId]));
			}
		}
		?>
		</div>
	</div>
	<div id="seo">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('slug', _('Url'), array('tooltip' => _("Indiquez l'url que vous souhaitez mettre en place. ATTENTION n'utiliser que des caractères autorisés pour l'url (Que des lettres, des chiffres et -)")));
			echo $helpers['Form']->input('page_title', _('Meta title'), array('tooltip' => _("Titre de la page (70 caractères maximum recommandé, par défaut ce champ aura pour valeur le champ Titre)")));
			echo $helpers['Form']->input('page_description', _('Meta description'), array('tooltip' => _("Résumé de la page html, 160 caractères maximum recommandé")));
			echo $helpers['Form']->input('page_keywords', _('Meta keywords'), array('tooltip' => _("Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (Optionnel)")));		
			?>
		</div>
	</div>
	<div id="options">
		<div class="content nopadding">
			<?php 
			//On va supprimer la catégorie racine
			$racine = each($categoriesList);
			unset($categoriesList[$racine['key']]);			
			$categoriesList[-1] = "[&nbsp;&nbsp;&nbsp;"._("Redirection vers la page d'accueil")."&nbsp;&nbsp;&nbsp;]";
			echo $helpers['Form']->input('redirect_category_id', _('Rediriger vers'), array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => _("Vous permet de rediriger cette page vers une autre de la liste"), 'firstElementList' => _("Pas de redirection")));			
			echo $helpers['Form']->input('redirect_to', _('Redirection personnalisée'), array('tooltip' => _("Saisissez l'url de redirection")));
			
			echo $helpers['Form']->input('title_posts_list', _('Titre bloc article'), array('tooltip' => _("Indiquez le titre qui sera affiché au dessus de la liste des articles")));			
			if(!isset($formulaires)) { $formulaires = array (1 => _('Formulaire de contact')); } 
			echo $helpers['Form']->input('display_form', _('Formulaire'), array('type' => 'select', 'datas' => $formulaires, 'tooltip' => _("Indiquez le formulaire que vous souhaitez afficher sur la page"), 'firstElementList' => _("Sélectionnez un formulaire")));
			
			echo $helpers['Form']->upload_files('css_file', array('label' => _("Fichier css"), 'button_value' => _('Sélectionner un fichier CSS'), 'tooltip' => _("Vous pouvez uploader un fichier CSS supplémentaire si besoin. Attention ce fichier ne sera pris en compte que lors de l'affichage de cette page.")));
			echo $helpers['Form']->upload_files('js_file', array('label' => _("Fichier javascript"), 'button_value' => _('Sélectionner un fichier JS'), 'tooltip' => _("Vous pouvez uploader un fichier JS supplémentaire si besoin. Attention ce fichier ne sera pris en compte que lors de l'affichage de cette page.")));
			?>
		</div>
	</div>
	<div id="secure">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('is_secure', _('Activer la protection de la page'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer la protection de la page")));
			echo $helpers['Form']->input('txt_secure', _('Texte page sécurisée'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Saisissez ici le texte qui sera affiché si la page est sécurisée")));	
			?>
		</div>
	</div>
	<?php if($isSecure) { ?>			
		<div id="emailing">
			<div class="content nopadding">
				<?php 
				echo $helpers['Form']->input('send_mail', _("Envoyer un email pour informer les utilisateurs de l'ajout (ou de la modification)"), array('type' => 'checkbox', 'tooltip' => _("En cochant cette case un email sera automatiquement envoyer à l'ensemble des utilisateurs référencés dans le système")));
				echo $helpers['Form']->input('message_mail', _('Contenu email newsletter'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
				?>
			</div>
		</div>
	<?php } ?>
</div>
<?php include_once(JS.DS.'backoffice'.DS.'buttons_right_column.php'); ?>