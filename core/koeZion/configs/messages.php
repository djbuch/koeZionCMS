<?php
$Errorsmessages['Category']['parent_id'] = _('Une catégorie ne peut être son propre parent.');
$Errorsmessages['Category']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['Category']['slug']['rule1'] = _('La valeur de ce champ est de 3 caractères minimum.');
$Errorsmessages['Category']['slug']['rule2'] = _('Il y a des caractères non autorisés dans ce champ.');
$Errorsmessages['Category']['redirect_category_id'] = _('Une catégorie ne peut pas être redirigée vers elle même.');

$Errorsmessages['Contact']['name'] = _('Veuillez saisir votre nom');
$Errorsmessages['Contact']['phone'] = _('Veuillez saisir votre téléphone');
$Errorsmessages['Contact']['email'] = _('Vous devez indiquer un email valide.');
$Errorsmessages['Contact']['cpostal'] = _('Vous devez indiquer votre code postal.');
$Errorsmessages['Contact']['message'] = _('Veuillez saisir votre message');

$Errorsmessages['Focus']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');

$Errorsmessages['Module']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['Module']['controller_name'] = _('La valeur de ce champ est de 2 caractères minimum.');

$Errorsmessages['Plugin']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['Plugin']['code'] = _('La valeur de ce champ est de 5 caractères exactement.');

$Errorsmessages['Post']['category_id'] = _('Vous devez sélectionner une catégorie.');
$Errorsmessages['Post']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['Post']['slug']['rule1'] = _('La valeur de ce champ est de 3 caractères minimum.');
$Errorsmessages['Post']['slug']['rule2'] = _('Il y a des caractères non autorisés dans ce champ.');
$Errorsmessages['Post']['redirect_to'] = _("L'url de redirection que vous avez indiqué n'est pas correcte.");
$Errorsmessages['Post']['prefix']['rule1'] = _('La valeur de ce champ est de 3 caractères minimum.');
$Errorsmessages['Post']['prefix']['rule2'] = _('Il y a des caractères non autorisés dans ce champ.');

$Errorsmessages['PostsComment']['name'] = _('Veuillez saisir votre nom');
$Errorsmessages['PostsComment']['email'] = _('Vous devez indiquer un email valide.');
$Errorsmessages['PostsComment']['cpostal'] = _('Vous devez indiquer votre code postal.');
$Errorsmessages['PostsComment']['message'] = _('Veuillez saisir votre message');

$Errorsmessages['PostsType']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['PostsType']['column_title'] = _('La valeur de ce champ est de 2 caractères minimum.');

$Errorsmessages['RightButton']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');

$Errorsmessages['Slider']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');

$Errorsmessages['User']['role'] = _('Vous devez sélectionner une valeur.');
$Errorsmessages['User']['group'] = _('Vous devez sélectionner une valeur.');
$Errorsmessages['User']['name'] = _('Vous devez saisir votre nom (2 caractères minimum).');
$Errorsmessages['User']['login'] = _('La valeur de ce champ est de 4 caractères minimum.');
$Errorsmessages['User']['password'] = _('Vous devez saisir un mot de passe (4 caractères minimum).');
$Errorsmessages['User']['email1'] = _('Vous devez indiquer un email valide.');
$Errorsmessages['User']['email2'] = _('Désolé mais cet email est déjà utilisé.');

$Errorsmessages['UsersGroup']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['UsersGroup']['role_id'] = _('Vous devez sélectionner une valeur.');

$Errorsmessages['Website']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['Website']['url'] = _('Vous devez indiquer une url valide.');
//$Errorsmessages['Website']['url_alias'] = _('Vous devez indiquer une url valide pour les alias.');
$Errorsmessages['Website']['template'] = _('Vous devez indiquer sélectionner un template.');

$Errorsmessages['Template']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['Template']['layout'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['Template']['version'] = _('La valeur de ce champ est de 2 caractères minimum.');
$Errorsmessages['Template']['code'] = _('La valeur de ce champ est de 2 caractères minimum.');

$Errorsmessages['UnwantedCrawler']['name'] = _('La valeur de ce champ est de 2 caractères minimum.');

////////////////////////////////////////////
//   MESSAGES D'ERREURS SUPPLEMENTAIRES   //
$moreMessages = CONFIGS_PLUGINS.DS.'errors_messages';
if(is_dir($moreMessages)) {

	foreach(FileAndDir::directoryContent($moreMessages) as $moreMessage) {
		include($moreMessages.DS.$moreMessage);
	}
}