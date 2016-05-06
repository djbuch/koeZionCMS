<?php
$atLeast2Chars = _('La valeur de ce champ est de 2 caractères minimum.');
$atLeast3Chars = _('La valeur de ce champ est de 3 caractères minimum.');
$atLeast5Chars = _('La valeur de ce champ est de 5 caractères minimum.');

$Errorsmessages['Category']['parent_id'] 				= _('Une catégorie ne peut être son propre parent.');
$Errorsmessages['Category']['name'] 					= $atLeast2Chars;
$Errorsmessages['Category']['slug']['rule1'] 			= $atLeast3Chars;
$Errorsmessages['Category']['slug']['rule2'] 			= _('Il y a des caractères non autorisés dans ce champ.');
$Errorsmessages['Category']['redirect_category_id'] 	= _('Une catégorie ne peut pas être redirigée vers elle même.');

$Errorsmessages['Slider']['name'] 						= $atLeast2Chars;

$Errorsmessages['Focus']['name'] 						= $atLeast2Chars;

$Errorsmessages['RightButton']['name'] 					= $atLeast2Chars;

$Errorsmessages['Post']['category_id'] 					= _('Vous devez sélectionner une catégorie.');
$Errorsmessages['Post']['name'] 						= $atLeast2Chars;
$Errorsmessages['Post']['slug']['rule1'] 				= $atLeast3Chars;
$Errorsmessages['Post']['slug']['rule2'] 				= _('Il y a des caractères non autorisés dans ce champ.');
$Errorsmessages['Post']['redirect_to'] 					= _("L'url de redirection que vous avez indiqué n'est pas correcte.");
$Errorsmessages['Post']['prefix']['rule1'] 				= $atLeast3Chars;
$Errorsmessages['Post']['prefix']['rule2'] 				= _('Il y a des caractères non autorisés dans ce champ.');

$Errorsmessages['PostsType']['name'] 					= $atLeast2Chars;
$Errorsmessages['PostsType']['column_title'] 			= $atLeast2Chars;

$Errorsmessages['PostsComment']['name'] 				= _('Veuillez saisir votre nom.');
$Errorsmessages['PostsComment']['email'] 				= _('Vous devez indiquer un email valide.');
$Errorsmessages['PostsComment']['cpostal'] 				= _('Vous devez indiquer votre code postal.');
$Errorsmessages['PostsComment']['message'] 				= _('Veuillez saisir votre message.');

$Errorsmessages['Contact']['name'] 						= _('Veuillez saisir votre nom.');
$Errorsmessages['Contact']['phone'] 					= _('Veuillez saisir votre téléphone.');
$Errorsmessages['Contact']['email'] 					= _('Vous devez indiquer un email valide.');
$Errorsmessages['Contact']['cpostal'] 					= _('Vous devez indiquer votre code postal.');
$Errorsmessages['Contact']['message'] 					= _('Veuillez saisir votre message.');

$Errorsmessages['Portfolio']['category_id'] 			= _("Vous devez indiquer la catégorie.");
$Errorsmessages['Portfolio']['name'] 					= $atLeast2Chars;

$Errorsmessages['PortfoliosElement']['portfolio_id'] 	= _("Vous devez indiquer le portfolio.");
$Errorsmessages['PortfoliosElement']['name'] 			= $atLeast2Chars;

$Errorsmessages['Website']['name'] 						= $atLeast2Chars;
$Errorsmessages['Website']['url'] 						= _('Vous devez indiquer une url valide.');
//$Errorsmessages['Website']['url_alias'] 				= _('Vous devez indiquer une url valide pour les alias.');
$Errorsmessages['Website']['template'] 					= _('Vous devez indiquer sélectionner un template.');

$Errorsmessages['User']['role'] 						= _('Vous devez sélectionner une valeur.');
$Errorsmessages['User']['group'] 						= _('Vous devez sélectionner une valeur.');
$Errorsmessages['User']['name'] 						= _('Vous devez saisir votre nom (2 caractères minimum).');
$Errorsmessages['User']['login'] 						= _('La valeur de ce champ est de 4 caractères minimum.');
$Errorsmessages['User']['password'] 					= _('Vous devez saisir un mot de passe (4 caractères minimum).');
$Errorsmessages['User']['email1'] 						= _('Vous devez indiquer un email valide.');
$Errorsmessages['User']['email2'] 						= _('Désolé mais cet email est déjà utilisé.');

$Errorsmessages['UsersGroup']['name'] 					= $atLeast2Chars;
$Errorsmessages['UsersGroup']['role_id'] 				= _('Vous devez sélectionner une valeur.');

////////////////
//   DIVERS   //
$Errorsmessages['Plugin']['code'] 						= $atLeast5Chars;
$Errorsmessages['Plugin']['name'] 						= $atLeast5Chars;
$Errorsmessages['Plugin']['description'] 				= $atLeast2Chars;
$Errorsmessages['Plugin']['author'] 					= $atLeast2Chars;

$Errorsmessages['Module']['name'] 						= $atLeast2Chars;
$Errorsmessages['Module']['controller_name'] 			= $atLeast2Chars;

$Errorsmessages['Template']['name'] 					= $atLeast2Chars;
$Errorsmessages['Template']['layout'] 					= $atLeast2Chars;
$Errorsmessages['Template']['version'] 					= $atLeast2Chars;

$Errorsmessages['UnwantedCrawler']['name'] 				= $atLeast2Chars;

////////////////////////////////////////////
//   MESSAGES D'ERREURS SUPPLEMENTAIRES   //
$moreMessages = CONFIGS_PLUGINS.DS.'errors_messages';
if(is_dir($moreMessages)) {

	foreach(FileAndDir::directoryContent($moreMessages) as $moreMessage) {
		include($moreMessages.DS.$moreMessage);
	}
}