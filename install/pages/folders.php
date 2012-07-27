<?php 
//Liste des dossiers à contrôler
$folders = array(
	array(
		'checkFolder' 	=> CONFIGS_FILES,
		'txtFolder' 	=> DS.'configs'.DS.'files',
		'check'			=> 'chmod'
	),
	array(
		'checkFolder' 	=> TMP,
		'txtFolder' 	=> DS.'tmp',
		'check'			=> 'chmod'
	),
	array(
		'checkFolder' 	=> WEBROOT_FILES,
		'txtFolder' 	=> DS.'webroot'.DS.'files',
		'check'			=> 'chmod'			
	),
	array(
		'checkFolder' 	=> WEBROOT_FILES.DS.'search',
		'txtFolder' 	=> DS.'webroot'.DS.'files'.DS.'search',
		'check'			=> 'exist'			
	),
	array(
		'checkFolder' 	=> WEBROOT_UPLOAD,
		'txtFolder' 	=> DS.'webroot'.DS.'upload',
		'check'			=> 'chmod'
	)
);
?>
<div id="right">		
	<div id="main">		
		<div class="box">
			<div class="title">
				<h2>PARAMETRAGES DES DOSSIERS</h2>
			</div>			
			<div class="content">	
				<?php 
				$result = true;
				
				//On va parcourir l'ensemble des dossiers
				foreach($folders as $folder) {
				
					$checkFolder = $folder['checkFolder'];
					$txtFolder = $folder['txtFolder'];
					$check = $folder['check'];
				
					switch($check) {
						
						case 'chmod':
							
							if(is_writable($checkFolder)) { ?><div class="system succes">Le dossier <b><?php echo $txtFolder; ?></b> est correctement paramétré.</div><?php } 
							else { 
								
								$result = false;
								?><div class="system error">
									Le dossier <b><?php echo $txtFolder; ?></b> n'est pas correctement paramétré.<br />
									Le  chmod doit être 0777 pour le dossier et tous les éléments qui le compose.
								</div><?php
							}
							
						break;
						
						case 'exist':
									
							if(is_dir($checkFolder)) { 
								$result = false;
								?><div class="system error">Le dossier <b><?php echo $txtFolder; ?></b> doit être supprimé.</div><?php 
							}
						break;						
					}					
				}
				
				if($result) {
					
					$httpHost = $_SERVER["HTTP_HOST"];
					if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $section = 'localhost'; } else { $section = 'online'; }
					?>
					<form action="index.php?step=database" method="post">
						<input type="hidden" name="section" value="<?php echo $section; ?>" />
						<div class="row" style="text-align: right; padding-right: 0; padding-bottom: 0;"><button class="medium grey" type="submit"><span>Configurer la base de données</span></button></div>
					</form>
					<?php 
				} 
				?>
			</div>
		</div>	
	</div>
</div>