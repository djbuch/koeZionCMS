<div class="box box-primary">
	<div class="box-body">
		<div class="col-md-12">
			<?php 	 
			echo $helpers['Form']->input('parent_id', _('Page parente'), array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => _("Indiquez la page parente de cette page (Racine du site par défaut)")));
			$typeList = array(1 => _("Page catégorie (Intégrée dans le menu)"), 2 => _("Page évènement (Non intégrée dans le menu)"));
			echo $helpers['Form']->input('type', _('Type de page'), array('type' => 'select', 'datas' => $typeList, 'tooltip' => _("Une page évènement n'est pas intégrée dans le menu général, vous pourrez faire des liens vers celle-ci via l'éditeur WYSIWYG")));
			
			echo $helpers['Form']->input('name_list', _('Titre'), array('type' => 'textarea'));
			echo $helpers['Form']->input('content', _('Contenu'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Saisissez ici le contenu de votre page, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
			
			echo $helpers['Form']->input('display_children', _('Afficher les pages enfants dans la colonne de droite'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les enfants de la page seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite")));
			echo $helpers['Form']->input('title_children', _('Titre colonne de droite'), array('tooltip' => _("Indiquez le titre qui sera affiché dans la colonne de droite pour les enfants (Activé que si vous avez cliqué sur la case précédente)")));
			
			echo $helpers['Form']->input('display_brothers', _('Afficher les pages du même niveau dans la colonne de droite'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les pages du même niveau seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite")));
			echo $helpers['Form']->input('title_brothers', _('Titre colonne de droite'), array('tooltip' => _("Indiquez le titre qui sera affiché dans la colonne de droite pour les menus du même niveau (Activé que si vous avez cliqué sur la case précédente)")));
			
			//echo $helpers['Form']->input('content', _('Contenu'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Saisissez ici le contenu de votre page, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
			echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser cette page")));
			?>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>
	</div>
</div>