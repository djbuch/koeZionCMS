<?php 
$typeList = array('admin' => "Administrateur", 'website_admin' => "Administrateur de site", 'user' => 'Utilisateur');
echo $helpers['Form']->input('role', "<i>(*)</i> Type d'utilisateur", array('type' => 'select', 'datas' => $typeList, 'firstElementList' => _("Sélectionnez un type"), 'tooltip' => "Indiquez le type de cet utilisateur"));			
echo $helpers['Form']->input('users_group_id', "<i>(*)</i> Groupe d'utilisateurs", array('type' => 'select', 'datas' => $usersGroupList, 'firstElementList' => _("Sélectionnez un groupe"), 'tooltip' => "Indiquez le groupe de cet utilisateur"));			
echo $helpers['Form']->input('name', '<i>(*)</i> Nom', array('tooltip' => "Indiquez le nom de l'utilisateur"));
echo $helpers['Form']->input('login', '<i>(*)</i> Identifiant', array('tooltip' => "Indiquez l'identifiant de l'utilisateur (Généralement un email)"));
echo $helpers['Form']->input('password', '<i>(*)</i> Mot de passe', array('tooltip' => "Indiquez le mot de passe de l'utilisateur", 'type' => 'password'));
echo $helpers['Form']->input('email', '<i>(*)</i> Email de contact', array('tooltip' => "Indiquez l'email de contact de cet utilisateur (peut être identique au login si celui-ci est un email)"));
echo $helpers['Form']->input('online', 'Actif', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour valider cet utilisateur"));
