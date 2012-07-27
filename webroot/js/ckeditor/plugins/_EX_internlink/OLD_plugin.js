// Ici je crée une action FCK qui s'appelle Internlink, et qui chargera le fichier test.html (qui sera au même niveau que ce fichier) lors de son appel
FCKCommands.RegisterCommand('Internlink', new FCKDialogCommand('Internlink','Ajout lien interne',FCKConfig.PluginsPath+'internlink/test.html', 300, 300 ));
 
// Ici je crée un bouton pour la toolbar auquel j'associe l'action précédemment définie
FCKToolbarItems.RegisterItem( 'Internlink', new FCKToolbarButton( 'Internlink', 'Lien interne', null, FCK_TOOLBARITEM_ICONTEXT, true, true, 1 ) ) ;