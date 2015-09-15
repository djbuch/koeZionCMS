<?php 
//Liste des dossiers à contrôler
$folders = array(
		
	//DOSSIER CONFIGS
	array(
		'checkFolder' 	=> CONFIGS_FILES,
		'txtFolder' 	=> DS.'configs'.DS.'files',
		'check'			=> 'chmod'
	),
	array(
		'checkFolder' 	=> CONFIGS_HOOKS,
		'txtFolder' 	=> DS.'configs'.DS.'hooks',
		'check'			=> 'chmod'
	),
	array(
		'checkFolder' 	=> CONFIGS_PLUGINS,
		'txtFolder' 	=> DS.'configs'.DS.'plugins',
		'check'			=> 'chmod'
	),
		
	//DOSSIER TMP
	array(
		'checkFolder' 	=> TMP.DS.'backup_bdd',
		'txtFolder' 	=> DS.'tmp'.DS.'backup_bdd',
		'check'			=> 'chmod'
	),
	array(
		'checkFolder' 	=> TMP.DS.'cache'.DS.'models',
		'txtFolder' 	=> DS.'tmp'.DS.'cache'.DS.'models',
		'check'			=> 'chmod'
	),
	array(
		'checkFolder' 	=> TMP.DS.'cache'.DS.'variables',
		'txtFolder' 	=> DS.'tmp'.DS.'cache'.DS.'variables',
		'check'			=> 'chmod'
	),
	array(
		'checkFolder' 	=> TMP.DS.'logs'.DS.'models',
		'txtFolder' 	=> DS.'tmp'.DS.'logs'.DS.'models',
		'check'			=> 'chmod'
	),
	array(
		'checkFolder' 	=> TMP.DS.'logs'.DS.'php',
		'txtFolder' 	=> DS.'tmp'.DS.'logs'.DS.'php',
		'check'			=> 'chmod'
	),
	array(
		'checkFolder' 	=> TMP.DS.'upload',
		'txtFolder' 	=> DS.'tmp'.DS.'upload',
		'check'			=> 'chmod'
	),		
		
	//DOSSIER WEBROOT
	array(
		'checkFolder' 	=> WEBROOT_FILES,
		'txtFolder' 	=> DS.'webroot'.DS.'files',
		'check'			=> 'chmod'			
	),
	array(
		'checkFolder' 	=> WEBROOT_PLUGINS,
		'txtFolder' 	=> DS.'webroot'.DS.'plugins',
		'check'			=> 'chmod'			
	),
	array(
		'checkFolder' 	=> WEBROOT_UPLOAD,
		'txtFolder' 	=> DS.'webroot'.DS.'upload',
		'check'			=> 'chmod'
	)
);
?>
<div class="box box-primary">
	<div class="box-header bg-light-blue">
		<h4><i class="fa fa-folder-open"></i> <?php echo _("PARAMETRAGES DES DOSSIERS"); ?></h4>                  
	</div>
    <div class="box-body">
		<?php 
		$result = true;
		
		//On va parcourir l'ensemble des dossiers
		foreach($folders as $folder) {
		
			$checkFolder = $folder['checkFolder'];
			$txtFolder = $folder['txtFolder'];
			$check = $folder['check'];
		
			switch($check) {
				
				case 'chmod':
					
					if(FileAndDir::dwritable($checkFolder)) { ?><div class="callout callout-success">Le dossier <b><?php echo $txtFolder; ?></b> est correctement paramétré.</div><?php } 
					else { 
						
						$result = false;
						?><div class="callout callout-warning">
							Le dossier <b><?php echo $txtFolder; ?></b> n'est pas correctement paramétré.<br />
							Le  chmod doit être 0777 pour le dossier et tous les éléments qui le compose.
						</div><?php
					}
					
				break;
				
				case 'exist':
							
					if(FileAndDir::dexists($checkFolder)) { 
						$result = false;
						?><div class="callout callout-warning">Le dossier <b><?php echo $txtFolder; ?></b> doit être supprimé.</div><?php 
					}
				break;						
			}					
		}
		?>
	</div>
	<?php		 
	if($result) {					
		
		///////////////////////////////////////////////////
		//   CREATION DES DOSSIERS DANS WEBROOT/UPLOAD   //
		$foldersToCreate = array(
				WEBROOT_UPLOAD.DS.'_thumbs',
				WEBROOT_UPLOAD.DS.'_thumbs'.DS.'Files',
				WEBROOT_UPLOAD.DS.'_thumbs'.DS.'Flash',
				WEBROOT_UPLOAD.DS.'_thumbs'.DS.'Images',
				WEBROOT_UPLOAD.DS.'files',
				WEBROOT_UPLOAD.DS.'flash',
				WEBROOT_UPLOAD.DS.'images'
		);
		foreach($foldersToCreate as $folder) { FileAndDir::createPath($folder); }
		
		FileAndDir::fcopy(INSTALL_FILES.DS.'core.ini', CONFIGS_FILES.DS.'core.ini');
		FileAndDir::fcopy(INSTALL_FILES.DS.'posts.ini', CONFIGS_FILES.DS.'posts.ini');
		FileAndDir::fcopy(INSTALL_FILES.DS.'routes.ini', CONFIGS_FILES.DS.'routes.ini');
						
		$httpHost = $_SERVER["HTTP_HOST"];
		if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $section = 'localhost'; } else { $section = 'online'; }
		?>
		<div class="box-footer"> 
			<form action="index.php?step=database_params" method="post">
				<input type="hidden" name="section" value="<?php echo $section; ?>" />
				<button class="btn btn-primary btn-flat pull-right" type="submit"><?php echo _('Configurer la base de données'); ?></button>
			</form>
		</div>
		<?php 
	} 
	?>
</div>