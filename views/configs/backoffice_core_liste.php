<div class="section">
	<div class="box">
		<div class="title"><h2><?php echo _("Configuration du coeur de KoéZionCMS"); ?></h2></div>
		<div class="content nopadding">
			<?php echo $helpers['Form']->create(array('id' => 'ConfigCore', 'action' => Router::url('backoffice/configs/core_liste'), 'method' => 'post')); ?>
			<div class="smarttabs nobottom">
				<ul class="anchor">
					<li><a href="#general"><?php echo _("Général"); ?></a></li>
					<li><a href="#ckfinder"><?php echo _("CK Finder"); ?></a></li>
				</ul>								
				<div id="general">
					<div class="content nopadding">
						<?php
						echo $helpers['Form']->input('check_password_local', _('Tester le mot de passe en local'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le contrôle du mot de passe en local")));
						echo $helpers['Form']->input('hash_password', _('Crypter les mots de passe utilisateurs'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer hashage des mots de passe dans la base de données")));
						echo $helpers['Form']->input('log_sql', _('Activer le log SQL'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le log des reqêtes SQL effectuées")));
						echo $helpers['Form']->input('log_php', _('Activer le log PHP'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le log des erreurs PHP")));
						echo $helpers['Form']->input('display_php_error', _('Afficher les erreurs PHP'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour afficher les erreurs PHP")));
						echo $helpers['Form']->input('local_storage_session', _('Stocker les variables de sessions localement'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le stockage local des variables de sessions")));
						echo $helpers['Form']->input('backoffice_home_page', _("Page d'accueil du backoffice"), array('tooltip' => _("Indiquez ici l'adresse de la première page du backoffice")));
						$txtAfterInput = '<br />'._('Pour plus d\'informations sur les timezones').' <a href="http://php.net/manual/en/timezones.php" target="_blank">'._('cliquez-ici').'</a>';	
						echo $helpers['Form']->input('date_default_timezone', _("Timezone par défaut"), array('txtAfterInput' => $txtAfterInput));
						?>
					</div>
				</div>
				<div id="ckfinder">
					<div class="content nopadding">
						<?php 
						echo $helpers['Form']->input('ckfinder_only_one_folder', _('Dossier de stockage unique'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour n'avoir à gérer qu'un seul dossier de stockage")));
						echo $helpers['Form']->input('ckfinder_files_type', _("Extensions fichiers autorisées"), array('tooltip' => _("Indiquez ici la liste des extensions de fichiers autorisées séparées par une virgule"), 'value' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip,kml,css,html,js'));
						echo $helpers['Form']->input('ckfinder_images_type', _("Extensions images autorisées"), array('tooltip' => _("Indiquez ici la liste des extensions d'images autorisées séparées par une virgule"), 'value' => 'bmp,gif,jpeg,jpg,png'));
						echo $helpers['Form']->input('ckfinder_flash_type', _("Extensions flash autorisées"), array('tooltip' => _("Indiquez ici la liste des extensions de fichiers flash autorisées séparées par une virgule"), 'value' => 'swf,flv'));
						?>
					</div>
				</div>
			</div>
			<?php echo $helpers['Form']->end(true); ?>
		</div>
	</div>
</div>