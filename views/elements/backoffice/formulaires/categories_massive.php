<?php 
echo $helpers['Form']->input('parent_id', 'Page parente', array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => "Indiquez la page parente de cette page (Racine du site par défaut)"));
$typeList = array(1 => "Page catégorie (Intégrée dans le menu)", 2 => "Page évènement (Non intégrée dans le menu)");
echo $helpers['Form']->input('type', 'Type de page', array('type' => 'select', 'datas' => $typeList, 'tooltip' => "Une page évènement n'est pas intégrée dans le menu général, vous pourrez faire des liens vers celle-ci via l'éditeur WYSIWYG"));

echo $helpers['Form']->input('name_list', 'Titre', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge'));
echo $helpers['Form']->input('content', 'Contenu', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Saisissez ici le contenu de votre page, n'hésitez pas à utiliser les modèles de pages pour vous aider"));

echo $helpers['Form']->input('display_children', 'Afficher les pages enfants dans la colonne de droite', array('type' => 'checkbox', 'tooltip' => "En cliquant sur cette case les enfants de la page seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite"));
echo $helpers['Form']->input('title_children', 'Titre colonne de droite', array('tooltip' => "Indiquez le titre qui sera affiché dans la colonne de droite pour les enfants (Activé que si vous avez cliqué sur la case précédente)"));

echo $helpers['Form']->input('display_brothers', 'Afficher les pages du même niveau dans la colonne de droite', array('type' => 'checkbox', 'tooltip' => "En cliquant sur cette case les pages du même niveau seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite"));
echo $helpers['Form']->input('title_brothers', 'Titre colonne de droite', array('tooltip' => "Indiquez le titre qui sera affiché dans la colonne de droite pour les menus du même niveau (Activé que si vous avez cliqué sur la case précédente)"));

//echo $helpers['Form']->input('content', 'Contenu', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Saisissez ici le contenu de votre page, n'hésitez pas à utiliser les modèles de pages pour vous aider"));
echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser cette page"));