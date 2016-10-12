<?php 
$websites = Session::read('Backoffice.Websites');
$websitesListe = $websites['liste'];
$currentWebsite = Session::read('Backoffice.Websites.current');	
?>
<section class="content">
	<div class="row">
    	<?php //VERSION CMS ?>
    	<div class="col-md-12">
    		<div class="box box-primary">
				<div class="box-header with-border">
			    	<h3 class="box-title"><?php echo _("VÉRIFICATION DE LA VERSION DU CMS"); ?></h3>
				</div>
    			<div class="box-body"> 
					<?php if(isset($soapErrorMessage)) { ?>
						<div class="callout callout-danger">
							<?php echo $soapErrorMessage; ?>
							<br /><?php echo _("Cette extension est nécessaire pour effetuer le contrôle des versions"); ?>
						</div>
					<?php } else { ?>
						<div class="callout callout-info">
							<?php echo _("Version locale du CMS"); ?> : <b><?php echo $cmsVersion['localVersion']; ?></b><br />
							<?php echo _("Version distante du CMS"); ?> : <b><?php echo $cmsVersion['remoteVersion']; ?></b> 
						</div>
						<?php if($cmsVersion['localVersion'] < $cmsVersion['remoteVersion']) { ?>
							<div class="callout callout-danger"><?php echo _("Votre code nécessite une mise à jour"); ?>.</div>
						<?php } else { ?>
							<div class="callout callout-success"><?php echo _("Votre code ne nécessite aucune mise à jour"); ?>.</div>
						<?php } ?>		
					<?php } ?>	            
				</div>    		
			</div>
		</div>		
    	<?php //VERSION BDD ?>
    	<div class="col-md-12">
    		<div class="box box-primary">
				<div class="box-header with-border">
			    	<h3 class="box-title"><?php echo _("VÉRIFICATION DE LA VERSION DE LA BASE DE DONNEES"); ?></h3>
				</div>
    			<div class="box-body"> 
					<?php if(isset($soapErrorMessage)) { ?>
						<div class="callout callout-danger">
							<?php echo $soapErrorMessage; ?>
							<br /><?php echo _("Cette extension est nécessaire pour effetuer le contrôle des versions"); ?>
						</div>
					<?php } else { ?>
						<div class="callout callout-info">
							<?php echo _("Version locale de la base de données"); ?> : <b><?php echo $bddVersion['localVersion']; ?></b><br />
							<?php echo _("Version distante de la base de données"); ?> : <b><?php echo $bddVersion['remoteVersion']; ?></b> 
						</div>
						<?php
						//On va contrôler à la fois la version locale (celle du fichier) et celle de la bdd qui est mise à jour lorsqu'un update est effectué 
						if($bddVersion['localVersion'] < $bddVersion['remoteVersion']) { 
							
							$displayUpdate = true;
							?><div class="callout callout-danger"><?php echo _("Votre base de données nécessite une mise à jour"); ?>.</div><?php 
							
						} else { 
							
							$displayUpdate = false;
							?><div class="callout callout-success"><?php echo _("Votre base de données ne nécessite aucune mise à jour"); ?>.</div><?php 
							
						} 
						?>		
					<?php } ?>            
				</div>    		
			</div>
		</div>
		<?php if(isset($displayUpdate) && $displayUpdate && isset($bddVersion['updates']) && !empty($bddVersion['updates'])) { ?>
	    	<div class="col-md-12">
	    		<div class="box box-primary">
					<div class="box-header with-border">
				    	<h3 class="box-title"><?php echo _("REQUETES DE MISE A JOUR"); ?></h3>
					</div>
	    			<div class="box-body"> 
						<pre>
							<?php 
							echo "\n";
							ob_start(); 
							
							foreach($bddVersion['updates'] as $update) { 
								
								$update = trim($update);									
								$strLen = strlen($update);
								if(substr($update, $strLen-1, 1) != ";") { $update.=";"; }
								echo $update."\n"; 
							} 
														
							echo "DELETE FROM `configs` WHERE `code` = 'KOEZION';"."\n";
							echo "INSERT INTO `configs` (`id`, `code`, `field`, `value`, `created`, `modified`, `website_id`)
							VALUES
							(NULL, 'KOEZION', 'numVersion', '".$bddVersion['remoteVersion']."', NOW(), NOW(), 0),
							(NULL, 'KOEZION', 'nameVersion', '".$bddVersion['remoteName']."', NOW(), NOW(), 0),
							(NULL, 'KOEZION', 'supervisorVersion', '".$bddVersion['remoteSupervisor']."', NOW(), NOW(), 0);"."\n";	
							$updateSql = ob_get_clean();
							echo $updateSql;
							Session::write('Update.sql', $updateSql);
							?>
						</pre>							
						<?php 
						$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName'].'/version'), 'method' => 'post');
						echo $helpers['Form']->create($formOptions);
						echo $helpers['Form']->input('update_bdd', '', array('type' => 'hidden', 'value' => 1));
						echo $helpers['Form']->button(_("Exécuter la mise à jour et vider le cache automatiquement"));
						?>	            
					</div>    		
				</div>
			</div>
		<?php } ?>
	</div>
</section>