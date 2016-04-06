<section class="content-header">
	<h1><?php echo _("Configuration du coeur de KoéZionCMS"); ?></h1>
</section>	
<section class="content">
	<div class="row">
    	<div class="add_edit_page col-md-12">    		     	
	    	<?php 
	    	$this->element('flash_messages');	    	
			echo $helpers['Form']->create(array('id' => 'ConfigCore', 'action' => Router::url('backoffice/configs/core_liste'), 'method' => 'post'));
			?>
			<div class="row">
				<div class="nav-tabs-custom">
					<div class="col-md-2 left">
						<div class="box box-primary">
				    		<div class="box-body">
								<ul class="nav nav-tabs nav-stacked col-md-12">
							    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
							        <li><a href="#ckfinder" data-toggle="tab"><i class="fa fa-file-image-o"></i> <?php echo _("CKEditor / CKFinder"); ?></a></li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="col-md-10 right">
						<div class="box box-primary">
				    		<div class="box-body">		
							    <div class="tab-content col-md-12">
							    	<div class="tab-pane active" id="general">	
							    		<div class="box-header bg-light-blue">
											<h4><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></h4>                  
			                			</div>	
										<?php						
										echo $helpers['Form']->input('backoffice_template', _('Template de backoffice'), array('type' => 'select', 'datas' => array('bo_adminlte' => 'Admin LTE', 'bo_godzilla' => 'Godzilla'), 'tooltip' => _("Sélectionnez le template BO")));
										echo $helpers['Form']->input('check_password_local', _('Tester le mot de passe en local'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le contrôle du mot de passe en local")));
										echo $helpers['Form']->input('hash_password', _('Crypter les mots de passe utilisateurs'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer hashage des mots de passe dans la base de données")));
										echo $helpers['Form']->input('log_sql', _('Activer le log SQL'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le log des reqêtes SQL effectuées")));
										echo $helpers['Form']->input('log_php', _('Activer le log PHP'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le log des erreurs PHP")));
										echo $helpers['Form']->input('display_php_error', _('Afficher les erreurs PHP'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour afficher les erreurs PHP")));
										echo $helpers['Form']->input('outpout_compression', _('Activer la compression ZLIB'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer la compression des pages avec la librairie ZLIB de PHP")));
										echo $helpers['Form']->input('local_storage_session', _('Stocker les variables de sessions localement'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le stockage local des variables de sessions")));										
										$txtAfterInput = '<p class="help-block">'._('ATTENTION vous devez avoir un certificat SSL installé sur le serveur pour que cette option soit fonctionnelle.').'<br />'._("Rapprochez vous de votre hébergeur pour plus d'informations.").'</p>';
										$txtAfterInput .= '<br />'._("Urls à sécuriser").' <i>'._("(Indiquez ici les urls à sécuriser, si plusieurs les séparer par ;)").'</i><br />'.$helpers['Form']->input('https_secure_urls', '', array('onlyInput' => true));
										echo $helpers['Form']->input('https_activated', _("Activer l'HTTPS pour les espaces sécurisés (Backoffice par exemple)"), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer la navigation via HTTPS"), 'txtAfterInput' => $txtAfterInput));										
										$txtAfterInput = '<p class="help-block">'._("Vous devez renseigner l'ensemble des filtres à mettre en place dans le ").'<a href="'.Router::url("adm/unwanted_crawlers").'">'._("module crawlers").'</a></p>';
										echo $helpers['Form']->input('filtering_crawlers', _('Activer le filtrage des crawlers'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le filtrages des crawlers"), 'txtAfterInput' => $txtAfterInput));										
										echo $helpers['Form']->input('backoffice_home_page', _("Page d'accueil du backoffice"), array('tooltip' => _("Indiquez ici l'adresse de la première page du backoffice")));
										$txtAfterInput = '<p class="help-block">'._('Pour plus d\'informations sur les timezones').' <a href="http://php.net/manual/en/timezones.php" target="_blank">'._('cliquez-ici').'</a></p>';	
										echo $helpers['Form']->input('date_default_timezone', _("Timezone par défaut"), array('txtAfterInput' => $txtAfterInput));						
										?>
							    	</div>
							    	<div class="tab-pane" id="ckfinder">	
							    		<div class="box-header bg-light-blue">
											<h4><i class="fa fa-file-image-o"></i> <?php echo _("CKEditor / CKFinder"); ?></h4>                  
			                			</div>		
			                			<h5 class="form-title"><?php echo _("CKEditor"); ?></h5>
			                			<?php 
			                			$ckeditorVersions = array(
			                				'3.6.2' => _('Version 3.6.2'), 
			                				'4.5.7' => _('Version 4.5.7')
			                			); 
			                			echo $helpers['Form']->input('ckeditor_version', _('Version de CKEditor'), array('type' => 'select', 'datas' => $ckeditorVersions, 'tooltip' => _("Sélectionnez une version")));
			                			?>
			                			<h5 class="form-title"><?php echo _("CKFinder"); ?></h5>
										<?php 
			                			$ckfinderVersions = array(
			                				'2.3' => _('Version 2.3'), 
			                				'3.3' => _('Version 3.3')
			                			); 
			                			echo $helpers['Form']->input('ckfinder_version', _('Version de CKFinder'), array('type' => 'select', 'datas' => $ckfinderVersions, 'tooltip' => _("Sélectionnez une version")));			                			 
										echo $helpers['Form']->input('ckfinder_only_one_folder', _('Dossier de stockage unique'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour n'avoir à gérer qu'un seul dossier de stockage")));
										echo $helpers['Form']->input('ckfinder_files_type', _("Extensions fichiers autorisées"), array('tooltip' => _("Indiquez ici la liste des extensions de fichiers autorisées séparées par une virgule"), 'value' => '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip,kml,css,html,js'));
										echo $helpers['Form']->input('ckfinder_images_type', _("Extensions images autorisées"), array('tooltip' => _("Indiquez ici la liste des extensions d'images autorisées séparées par une virgule"), 'value' => 'bmp,gif,jpeg,jpg,png'));
										echo $helpers['Form']->input('ckfinder_flash_type', _("Extensions flash autorisées"), array('tooltip' => _("Indiquez ici la liste des extensions de fichiers flash autorisées séparées par une virgule"), 'value' => 'swf,flv'));
										echo $helpers['Form']->input('ckfinder_license_name', _("Licence name"), array('tooltip' => _("Indiquez ici le nom de la licence si vous en avez acheté une")));
										echo $helpers['Form']->input('ckfinder_license_key', _("Licence key"), array('tooltip' => _("Indiquez ici la clée de la licence si vous en avez acheté une")));
										?>
							    	</div>
							    </div>
							</div>
							<div class="box-footer">
			                	<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div> 
							</div>
						</div>
					</div>
				</div>
			</div>			
			<?php 
			echo $helpers['Form']->end(); 
			?>	
    	</div>
    </div>
</section>