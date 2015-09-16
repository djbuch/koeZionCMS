<?php 
$websites = Session::read('Backoffice.Websites');
$websitesListe = $websites['liste'];
$currentWebsite = Session::read('Backoffice.Websites.current');	
?>
<section class="content">
	<div class="row">            
		<div class="col-md-6">	
			<div class="row">
				
				<?php //CONTACTS ET COMMENTAIRES ?>
				<div class="col-lg-6 col-xs-12">
	            	<div class="small-box bg-aqua">
	                	<div class="inner">
	                  		<h3><?php echo $nbFormsContacts; ?></h3>
	                  		<p>
	                  			<?php 
	                  			if($nbFormsContacts > 0) { echo _("messages non lus"); } 
	                  			else { echo _("message d'internautes"); }
	                  			?>
	                  		</p>
	                	</div>
	                	<div class="icon">
	                  		<i class="fa fa-envelope"></i>
	                	</div>
	                	<a href="<?php echo Router::url('backoffice/contacts/index'); ?>" class="small-box-footer">
	                  		<?php echo _("Voir tous les messages"); ?> <i class="fa fa-arrow-circle-right"></i>
	                	</a>
	              	</div>
				</div>
	            <div class="col-lg-6 col-xs-12">
	            	<div class="small-box bg-aqua">
	                	<div class="inner">
	                  		<h3><?php echo $nbPostsComments; ?></h3>
	                  		<p>
	                  			<?php 
	                  			if($nbFormsContacts > 0) { echo _("commentaires non validés"); } 
	                  			else { echo _("commentaire d'internautes"); }
	                  			?>
	                  		</p>
	                	</div>
	                	<div class="icon">
	                  		<i class="fa fa-comments"></i>
	                	</div>
	                	<a href="<?php echo Router::url('backoffice/posts_comments/index'); ?>" class="small-box-footer">
	                  		<?php echo _("Voir tous les commentaires"); ?> <i class="fa fa-arrow-circle-right"></i>
	                	</a>
					</div>
				</div>
			
				<?php //SITES ADMINISTRES ?>
	            <div class="col-lg-12 col-xs-12">
	            	<div class="small-box bg-aqua">
	                	<div class="inner">
	                  		<h3><?php echo _("Sites administrés"); ?></h3>
	                  		<p>
	                  			<?php echo _("Les données affichées sont celles du site"); ?> <b><?php echo $websitesListe[$currentWebsite]; ?></b>
	                  			<?php if(count($websitesListe) > 1) { ?>
									<p><?php echo _("Vous pouvez changer de site en cliquant sur un des liens ci-dessous"); ?></p>
								<?php } ?>
	                  		</p>
	                	</div>
	                	<div class="icon">
	                  		<i class="fa fa-globe"></i>
	                	</div>
	                	<?php 
	                	if(count($websitesListe) > 1) { 
	                		
	                		foreach($websitesListe as $websiteId => $websiteLibelle) {
	                			
		                		?><a href="<?php echo Router::url('backoffice/websites/change_default/'.$websiteId); ?>" class="small-box-footer text-left"><i class="fa fa-arrow-circle-right"></i> <?php echo _("Afficher les données du site").' '.$websiteLibelle; ?></a><?php
	                		} 
	                	}	
	                	?>
					</div>
				</div>		
			</div>			
		</div>
		<div class="col-md-6">		
			<div class="row">
			
				<?php 
				//VERSION CMS
				if($cmsVersion['localVersion'] < $cmsVersion['remoteVersion']) { 
				
					?>		
					<div class="col-lg-6 col-xs-12">
						<div class="small-box bg-yellow">
		                	<div class="inner">
		                  		<h3><?php echo _("Version CMS"); ?></h3>
		                  		<p>
		                  			<?php echo _("La version de votre CMS nécessite"); ?><br />
		                  			<?php echo _("une mise à jour"); ?>
		                  		</p>
							</div>
							<div class="icon">
								<i class="fa fa-bug"></i>
							</div>
		                	<a href="<?php echo Router::url('backoffice/dashboard/version'); ?>" class="small-box-footer">
		                  		<?php echo _("En savoir plus"); ?> <i class="fa fa-arrow-circle-right"></i>
		                	</a>
						</div>
					</div>
					<?php 
				} 
				
				//VERSION BDD
				if($bddVersion['localVersion'] < $bddVersion['remoteVersion']) { 
				
					?>
					<div class="col-lg-6 col-xs-12">
						<div class="small-box bg-yellow">
		                	<div class="inner">
		                  		<h3><?php echo _("Version BDD"); ?></h3>
		                  		<p>
		                  			<?php echo _("La version de votre BDD nécessite"); ?><br />
		                  			<?php echo _("une mise à jour"); ?>
		                  		</p>
							</div>
							<div class="icon">
								<i class="fa fa-database"></i>
							</div>
		                	<a href="<?php echo Router::url('backoffice/dashboard/version'); ?>" class="small-box-footer">
		                  		<?php echo _("En savoir plus"); ?> <i class="fa fa-arrow-circle-right"></i>
		                	</a>
						</div>
					</div>
					<?php 
				}
				
				//PRESENCE DOSSIER INSTALL
				if(!in_array($_SERVER["HTTP_HOST"], array('localhost', '127.0.0.1')) && FileAndDir::dexists(ROOT.DS.'install')) { 
					
					?>					
					<div class="col-lg-12 col-xs-12">
						<div class="small-box bg-yellow">
		                	<div class="inner">
		                  		<h3><?php echo _("ATTENTION"); ?></h3>
		                  		<p>
									<?php echo _("Le dossier /install est toujours présent sur le serveur"); ?>.<br />
									<?php echo _("L'installation étant terminée vous devriez vous connecter à votre FTP et le supprimer."); ?>
		                  		</p>
							</div>
							<div class="icon">
								<i class="fa fa-warning"></i>
							</div>
						</div>
					</div>
					<?php 
				} 
				
				//MESSAGES CMS 
				?>
		    	<div class="col-md-12">
		    		<div class="box box-primary">
						<div class="box-header with-border">
					    	<h3 class="box-title"><?php echo _("Messages"); ?></h3>
						</div>
		    			<div class="box-body"> 
							<?php 
							if(isset($soapErrorMessage)) { 
								
								?>
								<div class="system error">
									<?php echo $soapErrorMessage; ?>
									<br /><?php echo _("Cette extension est nécessaire pour récupérer les messages"); ?>
								</div>
								<?php 
							} else { 
								
								foreach($cmsMessage as $k => $v) { echo $v;  } 
							} 
							?>	            
						</div>    		
					</div>
				</div>	
			</div>	
		</div>
	</div>
</section>