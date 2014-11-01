<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#header"><?php echo _("header"); ?></a></li>
		<li><a href="#tpl"><?php echo _("Template"); ?></a></li>
		<li><a href="#txt"><?php echo _("Textes"); ?></a></li>
		<li><a href="#txtemails"><?php echo _("Textes emails"); ?></a></li>
		<li><a href="#seo"><?php echo _("SEO"); ?></a></li>
		<li><a href="#foot"><?php echo _("Footer"); ?></a></li>
		<li><a href="#options"><?php echo _("Options"); ?></a></li>
		<li><a href="#googleanalytics"><?php echo _("Google Analytics"); ?></a></li>
		<li><a href="#connect"><?php echo _("Page de connexion"); ?></a></li>
		<li><a href="#cssjs"><?php echo _("CSS & JS"); ?></a></li>
	</ul>
	<div id="general">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le titre du site Internet"));
			echo $helpers['Form']->input('url', '<i>(*)</i> Url', array('tooltip' => "Indiquez l'url complète du site Internet (avec http:// et sans le / à la fin), sautez une ligne entre chaque domaine."));
			//echo $helpers['Form']->input('url_alias', 'Alias du domaine', array('type' => 'textarea', 'rows' => 1, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Indiquez ici le ou les alias de ce domaine (url complète avec http:// et sans le / à la fin), sautez une ligne entre chaque domaine."));
			echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser ce site Internet"));
			?>
		</div>
	</div>
	<div id="header">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('tpl_logo', 'Logo', array('type' => 'textarea', 'toolbar' => 'image', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Sélectionnez votre logo à l'aide de l'explorateur de fichier"));
			//echo $helpers['Form']->input('tpl_header', 'Header', array('type' => 'textarea', 'toolbar' => 'image', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Sélectionnez l'image du header à l'aide de l'explorateur de fichier"));
			?>
		</div>
	</div>
	<div id="tpl">
		<div class="content nopadding">		
			<?php 
			if(count($templatesFilter) == 1) { $templatesFilter = current($templatesFilter); } //On à qu'un seul template on ne va afficher que les informations de ce template
			echo $helpers['Form']->input('filter_template', 'Filtrer les templates', array('type' => 'select', 'datas' => $templatesFilter, 'firstElementList' => "Sélectionnez une version de template pour filtrer les résultats")); 
			
			$this->element('backoffice/formulaires/websites_templates');
			?>
		</div>
	</div>
	<div id="txt">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('txt_slogan', 'Slogan (accueil)', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le slogan du site"));
			echo $helpers['Form']->input('txt_posts', 'Articles (accueil)', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte de présentation des articles sur la page d'accueil"));
			echo $helpers['Form']->input('txt_newsletter', 'Page newsletter', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte de la page newsletter"));
			//echo $helpers['Form']->input('txt_social', 'Texte social', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => ""));
			?>
		</div>
	</div>
	<div id="txtemails">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('txt_after_form_contact', 'Mentions après formulaire de contact', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte qui sera affiché après le formulaire de contact"));
			echo $helpers['Form']->input('txt_mail_contact', 'Contenu email contact', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte qui sera envoyé par email"));
			
			echo $helpers['Form']->input('txt_after_form_comments', 'Mentions après formulaire commentaires', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte qui sera affiché après le formulaire de commentaires article"));
			echo $helpers['Form']->input('txt_mail_comments', 'Contenu email commentaires', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte qui sera envoyé par email"));
			
			echo $helpers['Form']->input('txt_after_newsletter', 'Mentions après formulaire newsletter', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte qui sera affiché après le formulaire d'inscription à la newsletter"));
			echo $helpers['Form']->input('txt_mail_newsletter', 'Contenu email newsletter', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte qui sera envoyé par email"));
			?>
		</div>
	</div>
	<div id="seo">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('seo_page_title', 'Meta title', array('tooltip' => "Résumé de la page html, 160 caractères maximum recommandé"));
			echo $helpers['Form']->input('seo_page_description', 'Meta description', array('tooltip' => "Résumé de la page html, 160 caractères maximum recommandé"));
			echo $helpers['Form']->input('seo_page_keywords', 'Meta keywords', array('tooltip' => "Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (Optionnel)"));		
			?>
		</div>
	</div>
	<div id="foot">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('footer_gauche', 'Colonne de gauche', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));			
			echo $helpers['Form']->input('footer_droite', 'Colonne de droite', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));
			echo $helpers['Form']->input('footer_bottom', 'Baseline', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));
			echo $helpers['Form']->input('footer_social', 'Texte social', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Indiquez ici, par exemple, le texte du module social de Facebook. Attention si vous activez cette zone la zone newsletter sera supprimée."));
			echo $helpers['Form']->input('footer_addthis', 'Module AddThis', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Indiquez ici le code pour le module AddThis."));
			?>
		</div>
	</div>
	<div id="options">
		<div class="content nopadding">
			<?php 
			$positionList = array('header' => "Dans le header", 'menu' => "Dans le menu");
			echo $helpers['Form']->input('search_engine_position', 'Position du moteur de recherche', array('type' => 'select', 'datas' => $positionList, 'firstElementList' => "Pas de moteur de recherche"));			
			$sliderTypesList = array(
				1 => "Slider simple", 
				2 => "Slider 3D", 
				3 => "Slider Vidéo"
			);
			echo $helpers['Form']->input('slider_type', 'Type de slider', array('type' => 'select', 'datas' => $sliderTypesList));
			$txtSecure = 'Sécuriser le site. <i>Seuls les utilisateurs enregistrés pourront se connecter. <a href="'.Router::url('backoffice/users/index').'">'._("Ajouter un utilisateur").'</a></i>';
			echo $helpers['Form']->input('secure_activ', $txtSecure, array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour activer la sécurité sur le site"));			
			$txtLog = "Logger les utilisateurs. <i>Attention cette option ne fonctionne que dans le cas de sites sécurisés.</i>";
			echo $helpers['Form']->input('log_users_activ', $txtLog, array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour activer le log des utilisateurs. La mise en place de cette option peut ralentir l'affichage des pages"));
			echo $helpers['Form']->upload_files('favicon', array('label' => "Icône du site"));
			echo $helpers['Form']->input('hook_filename', 'Nom du fichier hooks', array('tooltip' => "Indiquez ici le nom du du fichier hooks, ce nom sera recherché dans les dossiers /configs/hooks/elements/ et /configs/hooks/views/ (Si plusieurs fichiers les séparer par un ;)"));
			?>
		</div>
	</div>
	<div id="googleanalytics">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('ga_login', 'Google analytics Login', array('tooltip' => "Indiquez ici votre identifiant de connexion à Google Analytics"));
			echo $helpers['Form']->input('ga_password', 'Google analytics Password', array('tooltip' => "Indiquez ici votre mot de passe de connexion à Google Analytics"));
			echo $helpers['Form']->input('ga_id', 'Google analytics ID', array('tooltip' => "Indiquez ici l'ID du profil Google Analytics (Disponible les paramètres du profil)"));
			echo $helpers['Form']->input('ga_code', 'Code Google Analytics', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Indiquez ici le code de suivi Google Analytics"));			
			?>
		</div>
	</div>
	<div id="connect">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->upload_files('connect_background', array('label' => "Image de fond"));
			echo $helpers['Form']->input('connect_text', 'Texte', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));	
			echo $helpers['Form']->upload_files('connect_css_file', array('label' => "Ficher CSS complémentaire"));		
			echo $helpers['Form']->upload_files('connect_js_file', array('label' => "Ficher JS complémentaire"));		
			//echo $helpers['Form']->input('connect_css', 'Rajout de code css', array('type' => 'textarea', 'rows' => 30, 'cols' => 10, 'class' => 'xxlarge'));
			//echo $helpers['Form']->input('connect_js', 'Rajout de code js', array('type' => 'textarea', 'rows' => 30, 'cols' => 10, 'class' => 'xxlarge'));
			?>
		</div>
	</div>
	<div id="cssjs">
		<div class="content nopadding">
			<?php	
			echo $helpers['Form']->upload_files('css_hack_file', array('label' => "Ficher CSS complémentaire"));		
			echo $helpers['Form']->upload_files('js_hack_file', array('label' => "Ficher JS complémentaire"));
			//echo $helpers['Form']->input('css_hack', 'Rajout de code css', array('type' => 'textarea', 'rows' => 30, 'cols' => 10, 'class' => 'xxlarge'));
			//echo $helpers['Form']->upload_files('css_hack_file', array('label' => "Fichier(s) css", 'button_value' => 'Vous pouvez uploader de(s) fichier(s) CSS supplémentaire(s) si besoin', 'tooltip' => "Attention ce fichier doit obligatoirement se trouver dans le dossier Files/css (Si celui-ci n'existe pas vous devrez le créer).", 'display_input' => false));			
			//echo $helpers['Form']->input('js_hack', 'Rajout de code js', array('type' => 'textarea', 'rows' => 30, 'cols' => 10, 'class' => 'xxlarge'));
			//echo $helpers['Form']->upload_files('js_hack_file', array('label' => "Fichier(s) javascript", 'button_value' => 'Vous pouvez uploader de(s) fichier(s) JS supplémentaire(s) si besoin', 'tooltip' => "Attention ce fichier doit obligatoirement se trouver dans le dossier Files/js (Si celui-ci n'existe pas vous devrez le créer).", 'display_input' => false));
			?>
		</div>
	</div>
</div>
<script type="text/javascript">			
	$(document).ready(function() {		

		$("#InputFilterTemplate").change(function() {
		
			var value = $(this).val();
			var host = $.get_host(); //Récupération du host
			var action = host + 'websites/ajax_get_templates/' + value + '.html'; //On génère l'url
									
			/*console.log('HOST --> ' + host);
			console.log('numArticle --> ' + numArticle);
			console.log('action --> ' + action);*/
						
			$.get(action, function(datas) { //On récupère les données en GET
				
				$("#tpl .prettyRadiobuttons").replaceWith(datas); //On rajoute une nouvelle ligne
			});			
		});
	});
</script>