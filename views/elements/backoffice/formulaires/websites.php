<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#header"><?php echo _("header"); ?></a></li>
		<li><a href="#tpl"><?php echo _("Template"); ?></a></li>
		<li><a href="#txt"><?php echo _("Textes"); ?></a></li>
		<li><a href="#seo"><?php echo _("SEO"); ?></a></li>
		<?php /* ?><li><a href="#wait"><?php echo _("Page d'attente"); ?></a></li><?php */ ?>
		<li><a href="#foot"><?php echo _("Footer"); ?></a></li>
		<li><a href="#options"><?php echo _("Options"); ?></a></li>
	</ul>
	<div id="general">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('name', '<i>(*)</i> Titre', array('tooltip' => "Indiquez le titre du site Internet"));
			echo $helpers['Form']->input('url', '<i>(*)</i> Url', array('tooltip' => "Indiquez l'url complète du site Internet (avec http://)"));
			echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser cette couleur"));
			?>
		</div>
	</div>
	<div id="header">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('tpl_logo', 'Logo', array('type' => 'textarea', 'toolbar' => 'image', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Sélectionnez votre logo à l'aide de l'explorateur de fichier"));
			echo $helpers['Form']->input('tpl_header', 'Header', array('type' => 'textarea', 'toolbar' => 'image', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Sélectionnez l'image du header à l'aide de l'explorateur de fichier"));
			?>
		</div>
	</div>
	<div id="tpl">
		<div class="content nopadding">
			<div class="prettyRadiobuttons clearfix">
				<input type="hidden" id="InputTemplateId0" name="template_id" value="0" />
				<?php foreach($templatesList as $k => $templateValue) { echo $helpers['Form']->radiobutton('template_id', $templateValue['id'], $templateValue['name'], $templateValue['layout'], $templateValue['code']); } ?>
			</div>
		</div>
	</div>
	<div id="txt">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('txt_slogan', 'Slogan (accueil)', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le slogan du site"));
			echo $helpers['Form']->input('txt_posts', 'Articles (accueil)', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte de présentation des articles sur la page d'accueil"));
			echo $helpers['Form']->input('txt_newsletter', 'Page newsletter', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte de la page newsletter"));
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
	<?php /* ?>
	<div id="wait">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('wait_activ', "Activer la page d'attente", array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour activer la page d'attente"));
			echo $helpers['Form']->input('wait_txt', "Texte page d'attente", array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Saisissez ici le texte qui sera affiché sur la page d'attente"));	
			?>
		</div>
	</div>
	<?php */ ?>
	<div id="foot">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('footer_gauche', 'Colonne de gauche', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));
			echo $helpers['Form']->input('footer_social', 'Code AddThis', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge'));
			echo $helpers['Form']->input('footer_droite', 'Colonne de droite', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));
			echo $helpers['Form']->input('footer_bottom', 'Baseline', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge'));
			?>
		</div>
	</div>
	<div id="options">
		<div class="content nopadding">
			<?php 
			$positionList = array('header' => "Dans le header", 'menu' => "Dans le menu");
			echo $helpers['Form']->input('search_engine_position', 'Position du moteur de recherche', array('type' => 'select', 'datas' => $positionList));
			echo $helpers['Form']->input('ga_code', 'Code Google Analytics', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge'));
			
			$txtSecure = 'Sécuriser le site. <i>Seuls les utilisateurs enregistrés pourront se connecter. Pour rajouter un utilisateurs utilisez la page suivante : <a href="'.Router::url('backoffice/users/index').'">'.$helpers['Html']->img('backoffice/icon-profile.png', array('alt' => _("Gestion utilisateurs")))._("Utilisateurs").'</a></i>';
			echo $helpers['Form']->input('secure_activ', $txtSecure, array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour activer la sécurité sur le site"));
			
			$txtLog = "Logger les utilisateurs. <i>Attention cette option ne fonctionne que dans le cas de sites sécurisés. La mise en place de cette option peut ralentir l'affichage des pages</i>";
			echo $helpers['Form']->input('log_users_activ', $txtLog, array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour activer le log des utilisateurs"));
			?>
		</div>
	</div>
</div>