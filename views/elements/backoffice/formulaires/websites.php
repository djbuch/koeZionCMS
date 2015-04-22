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
			echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du site Internet")));
			echo $helpers['Form']->input('url', _('Url'), array('compulsory' => true, 'tooltip' => _("Indiquez l'url complète du site Internet (avec http:// et sans le / à la fin), sautez une ligne entre chaque domaine.")));
			echo $helpers['Form']->input('url_alias', _('Alias du domaine'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => _("Indiquez ici le ou les alias de ce domaine (url complète avec http:// ou non sans le / à la fin), sautez une ligne entre chaque domaine.")));
			echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce site Internet")));
			?>
		</div>
	</div>
	<div id="header">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('tpl_logo', _('Logo'), array('type' => 'textarea', 'toolbar' => 'image', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Sélectionnez votre logo à l'aide de l'explorateur de fichier")));
			echo $helpers['Form']->input('header_txt', _('Texte header (Optionnel)'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Texte affiché dans le header (Optionnel selon le template sélectionné)")));
			//echo $helpers['Form']->input('tpl_header', _('Header'), array('type' => 'textarea', 'toolbar' => 'image', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Sélectionnez l'image du header à l'aide de l'explorateur de fichier")));
			?>
		</div>
	</div>
	<div id="tpl">
		<div class="content nopadding">		
			<?php 
			if(count($templatesFilter) == 1) { $templatesFilter = current($templatesFilter); } //On à qu'un seul template on ne va afficher que les informations de ce template
			echo $helpers['Form']->input('filter_template', _('Filtrer les templates'), array('type' => 'select', 'datas' => $templatesFilter, 'firstElementList' => _("Sélectionnez une version de template pour filtrer les résultats"))); 
			
			$this->element('backoffice/formulaires/websites_templates');
			?>
		</div>
	</div>
	<div id="txt">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('txt_slogan', _('Slogan (accueil)'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le slogan du site")));
			echo $helpers['Form']->input('txt_posts', _('Articles (accueil)'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte de présentation des articles sur la page d'accueil")));
			echo $helpers['Form']->input('txt_newsletter', _('Page newsletter'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte de la page newsletter")));
			//echo $helpers['Form']->input('txt_social', _('Texte social'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => _("")));
			?>
		</div>
	</div>
	<div id="txtemails">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('txt_after_form_contact', _('Mentions après formulaire de contact'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte qui sera affiché après le formulaire de contact")));
			echo $helpers['Form']->input('txt_mail_contact', _('Contenu email contact'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
			
			echo $helpers['Form']->input('txt_after_form_comments', _('Mentions après formulaire commentaires'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte qui sera affiché après le formulaire de commentaires article")));
			echo $helpers['Form']->input('txt_mail_comments', _('Contenu email commentaires'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
			
			echo $helpers['Form']->input('txt_after_newsletter', _('Mentions après formulaire newsletter'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte qui sera affiché après le formulaire d'inscription à la newsletter")));
			echo $helpers['Form']->input('txt_mail_newsletter', _('Contenu email newsletter'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
			?>
		</div>
	</div>
	<div id="seo">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('seo_page_title', _('Meta title'), array('tooltip' => _("Résumé de la page html, 160 caractères maximum recommandé")));
			echo $helpers['Form']->input('seo_page_description', _('Meta description'), array('tooltip' => _("Résumé de la page html, 160 caractères maximum recommandé")));
			echo $helpers['Form']->input('seo_page_keywords', _('Meta keywords'), array('tooltip' => _("Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (Optionnel)")));		
			?>
		</div>
	</div>
	<div id="foot">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('footer_gauche', _('Colonne de gauche'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));			
			echo $helpers['Form']->input('footer_droite', _('Colonne de droite'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));
			echo $helpers['Form']->input('footer_bottom', _('Baseline'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));
			echo $helpers['Form']->input('footer_social', _('Texte social'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => _("Indiquez ici, par exemple, le texte du module social de Facebook. Attention si vous activez cette zone la zone newsletter sera supprimée.")));
			echo $helpers['Form']->input('footer_addthis', _('Module AddThis'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => _("Indiquez ici le code pour le module AddThis.")));
			?>
		</div>
	</div>
	<div id="options">
		<div class="content nopadding">
			<?php 
			$positionList = array('header' => _("Dans le header"), 'menu' => _("Dans le menu"));
			echo $helpers['Form']->input('search_engine_position', _('Position du moteur de recherche'), array('type' => 'select', 'datas' => $positionList, 'firstElementList' => _("Pas de moteur de recherche")));			
			$sliderTypesList = array(
				1 => _("Slider simple"), 
				2 => _("Slider 3D"), 
				3 => _("Slider Vidéo")
			);
			echo $helpers['Form']->input('slider_type', _('Type de slider'), array('type' => 'select', 'datas' => $sliderTypesList));
			$txtSecure = _('Sécuriser le site. <i>Seuls les utilisateurs enregistrés pourront se connecter.').' <a href="'.Router::url('backoffice/users/index').'">'._("Ajouter un utilisateur").'</a></i>';
			echo $helpers['Form']->input('secure_activ', $txtSecure, array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer la sécurité sur le site")));			
			$txtLog = _("Logger les utilisateurs. <i>Attention cette option ne fonctionne que dans le cas de sites sécurisés.</i>");
			echo $helpers['Form']->input('log_users_activ', $txtLog, array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le log des utilisateurs. La mise en place de cette option peut ralentir l'affichage des pages")));
			echo $helpers['Form']->upload_files('favicon', array('label' => _("Icône du site")));
			echo $helpers['Form']->input('hook_filename', _('Nom du fichier hooks'), array('tooltip' => _("Indiquez ici le nom du du fichier hooks, ce nom sera recherché dans les dossiers /configs/hooks/* (Si plusieurs fichiers les séparer par un ;)")));
			?>
		</div>
	</div>
	<div id="googleanalytics">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('ga_login', _('Google analytics Login'), array('tooltip' => _("Indiquez ici votre identifiant de connexion à Google Analytics")));
			echo $helpers['Form']->input('ga_password', _('Google analytics Password'), array('tooltip' => _("Indiquez ici votre mot de passe de connexion à Google Analytics")));
			echo $helpers['Form']->input('ga_id', _('Google analytics ID'), array('tooltip' => _("Indiquez ici l'ID du profil Google Analytics (Disponible les paramètres du profil)")));
			echo $helpers['Form']->input('ga_code', _('Code Google Analytics'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => _("Indiquez ici le code de suivi Google Analytics")));			
			?>
		</div>
	</div>
	<div id="connect">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->upload_files('connect_background', array('label' => _("Image de fond")));
			echo $helpers['Form']->input('connect_text', _('Texte'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));	
			echo $helpers['Form']->upload_files('connect_css_file', array('label' => _("Ficher CSS complémentaire")));		
			echo $helpers['Form']->upload_files('connect_js_file', array('label' => _("Ficher JS complémentaire")));		
			?>
		</div>
	</div>
	<div id="cssjs">
		<div class="content nopadding">
			<?php	
			echo $helpers['Form']->upload_files('css_hack_file', array('label' => _("Ficher CSS complémentaire")));		
			echo $helpers['Form']->upload_files('js_hack_file', array('label' => _("Ficher JS complémentaire")));
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