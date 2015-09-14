<?php 
$websites = Session::read('Backoffice.Websites');
$websitesListe = $websites['liste'];
$currentWebsite = Session::read('Backoffice.Websites.current');	
?>
<section class="content">
	<div class="row">
		<div class="col-md-6">	
			<?php //CONTACTS ET COMMENTAIRES ?>
	    	<div class="col-md-12">
	    		<div class="box box-primary">
					<div class="box-header with-border">
				    	<h3 class="box-title"><?php echo _("Contacts et commentaires"); ?></h3>
					</div>
	    			<div class="box-body">   
						<?php 
						if($nbFormsContacts > 0) { ?><p><a href="<?php echo Router::url('backoffice/contacts/index'); ?>"><?php echo _("Vous avez"); ?> <?php echo $nbFormsContacts; ?> <?php echo _("messages d'internautes non lus"); ?></a></p><?php }
						else { ?><p><?php echo _("Aucun nouveau message d'Internaute"); ?></p><?php }
						if($nbPostsComments > 0) { ?><p><a href="<?php echo Router::url('backoffice/posts_comments/index'); ?>"><?php echo _("Vous avez"); ?> <?php echo $nbPostsComments; ?> <?php echo _("commentaires d'internautes non validés"); ?></a></p><?php } 
						else { ?><p><?php echo _("Aucun nouveau commentaire d'Internaute"); ?></p><?php }
						?>              
					</div>    		
				</div>
			</div>
			
			<?php //SITES ADMINISTRES ?>
	    	<div class="col-md-12">
	    		<div class="box box-primary">
					<div class="box-header with-border">
				    	<h3 class="box-title"><?php echo _("Sites administrés"); ?></h3>
					</div>
	    			<div class="box-body">
	    				<div class="callout callout-info">
	    					<?php echo _("Les données affichées sont celles du site"); ?> <b><?php echo $websitesListe[$currentWebsite]; ?></b>
	    				</div>
						<?php if(count($websitesListe) > 1) { ?>
							<p><?php echo _("Vous pouvez changer de site en cliquant sur un des liens ci-dessous"); ?></p>
							<table class="table">
								<?php foreach($websitesListe as $websiteId => $websiteLibelle) { ?>
									<tbody>
										<tr><td><a href="<?php echo Router::url('backoffice/websites/change_default/'.$websiteId); ?>"><?php echo _("Afficher les données du site").' '.$websiteLibelle; ?></a></td></tr>
									</tbody>
								<?php } ?>	
							</table>
						<?php } ?>          
					</div>    		
				</div>
			</div>
			
			<?php //MASSAGES CMS ?>
	    	<div class="col-md-12">
	    		<div class="box box-primary">
					<div class="box-header with-border">
				    	<h3 class="box-title"><?php echo _("Messages"); ?></h3>
					</div>
	    			<div class="box-body"> 
						<?php if(isset($soapErrorMessage)) { ?>
							<div class="system error">
								<?php echo $soapErrorMessage; ?>
								<br /><?php echo _("Cette extension est nécessaire pour récupérer les messages"); ?>
							</div>
						<?php } else { ?>
							<?php foreach($cmsMessage as $k => $v) { echo $v;  } ?>
						<?php } ?>	            
					</div>    		
				</div>
			</div>				
		</div>
		<div class="col-md-6">		
		
			<?php 
			//PRESENCE DOSSIER INSTALL
			if(!in_array($_SERVER["HTTP_HOST"], array('localhost', '127.0.0.1')) && FileAndDir::dexists(ROOT.DS.'install')) { 
				
				?>
		    	<div class="col-md-12">
		    		<div class="box box-warning box-solid">
						<div class="box-header with-border">
					    	<h3 class="box-title"><?php echo _("ATTENTION"); ?></h3>
						</div>
		    			<div class="box-body">             
							<?php echo _("Le dossier /install est toujours présent sur le serveur"); ?>.<br />
							<?php echo _("L'installation étant maintenant terminée vous devriez vous connecter à votre FTP et le supprimer."); ?>
						</div>    		
					</div>
				</div>
				<?php 
			} 
			?>
	    	
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
								
								echo "DELETE FROM `configs` WHERE `code` = 'numVersion';"."\n";
								echo "DELETE FROM `configs` WHERE `code` = 'nameVersion';"."\n";
								echo "DELETE FROM `configs` WHERE `code` = 'supervisorVersion';"."\n";								
								echo "INSERT INTO `configs` (`code`, `value`) VALUES ('numVersion', '".$bddVersion['remoteVersion']."'), ('nameVersion', '".$bddVersion['remoteName']."'), ('supervisorVersion', '".$bddVersion['remoteSupervisor']."');"."\n";
								
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
	</div>
</section>