<?php $this->element('backoffice/backoffice_index'); ?>
<p class="mentions">
	Pour effectuer la traduction des textes de votre site vous devrez utiliser le logiciel Poedit.<br />
	Celui peut être téléchargé sur <a href="http://www.poedit.net/download.php">le site de l'éditeur</a>.<br />
	Une fois téléchargé il vous suffira d'ouvrir le logiciel puis de cliquer sur le menu Fichier > Nouveau catalogue depuis un fichier POT...<br >
	Il ne vous reste plus qu'à traduire les différentes expressions.<br />
	Une fois le fichier traduit vous devrez télécharger celui-ci dans le dossier correspondant sur le serveur (CODE_LANGUE/LC_MESSAGES). 
</p>
<a class="btn blue" href="<?php echo Router::url('adm/exports/get_pot', 'pot'); ?>" style="float: right; margin-top: 20px;" target="_blank"><span><?php echo ("Télécharger le fichier source des traductions"); ?></span></a>