<?php 
$websites = Session::read('Backoffice.Websites');
$websitesListe = $websites['liste'];
$currentWebsite = Session::read('Backoffice.Websites.current');	
?>
<div class="section">
	<div class="half">
		
		<?php //CONTACTS ET COMMENTAIRES ?>
		<div class="box">
			<div class="title"><h2>Contacts et commentaires</h2></div>
			<div class="content">
				<?php 
				if($nbFormsContacts > 0) { ?><p><?php echo $helpers['Html']->img('/backoffice/icon-message.png', array('alt' => _("Messages Internautes"))); ?> <a href="<?php echo Router::url('backoffice/contacts/index'); ?>" class="basic_link">Vous avez <?php echo $nbFormsContacts; ?> messages d'internautes non lus</a></p><?php }
				else { ?><p>Aucun nouveau message d'Internaute</p><?php }
				if($nbPostsComments > 0) { ?><p><?php echo $helpers['Html']->img('/backoffice/icon-message.png', array('alt' => _("Commentaires Internautes"))); ?> <a href="<?php echo Router::url('backoffice/posts_comments/index'); ?>" class="basic_link">Vous avez <?php echo $nbPostsComments; ?> commentaires d'internautes non validés</a></p><?php } 
				else { ?><p>Aucun nouveau commentaire d'Internaute</p><?php }
				?>		
			</div>
		</div>
		
		<?php //SITES ADMINISTRES ?>
		<div class="box">
			<div class="title"><h2>Sites administrés</h2></div>
			<div class="content">
				<h3>Les données affichées sont celles du site <b><?php echo $websitesListe[$currentWebsite]; ?></b></h3>
				<?php if(count($websitesListe) > 1) { ?>
					<p>Vous pouvez changer de site en cliquant sur un des liens ci-dessous</p>
					<table cellspacing="0" cellpadding="0" border="0">
						<thead><tr><th>LISTE DES SITES INTERNET ADMINISTRES</th></tr></thead>
						<?php foreach($websitesListe as $websiteId => $websiteLibelle) { ?>
							<tbody>
								<tr><td><a href="<?php echo Router::url('backoffice/websites/change_default/'.$websiteId); ?>" class="basic_link"><?php echo _("Afficher les données du site").' '.$websiteLibelle; ?></a></td></tr>
							</tbody>
						<?php } ?>	
					</table>
				<?php } ?>		
			</div>
		</div>
	</div>
	
	<div class="half">	
		<div class="box">
			<div class="title">
				<h2>VÉRIFICATION DE LA VERSION DE LA BASE DE DONNEES</h2>
			</div>
			<div class="content">
				<div class="system info">
					Version locale de la base de données : <b><?php echo $bddVersion['localVersion']; ?></b><br />
					Version distante de la base de données : <b><?php echo $bddVersion['remoteVersion']; ?></b> 
				</div>
				<?php if($bddVersion['localVersion'] < $bddVersion['remoteVersion']) { ?>
					<div class="system error">Votre base de données nécessite une mise à jour.</div>
				<?php } else { ?>
					<div class="system succes">Votre base de données ne nécessite aucune mise à jour.</div>
				<?php } ?>		
			</div>
		</div>
		<?php if(isset($bddVersion['updates']) && !empty($bddVersion['updates'])) { ?>
			<div class="box">
				<div class="title">
					<h2>REQUETES DE MISE A JOUR</h2>
				</div>
				<div class="content">					
					<pre>
					<?php ob_start(); ?>
					<?php 
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
					$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName'].'/index'), 'method' => 'post');
					echo $helpers['Form']->create($formOptions);
					echo $helpers['Form']->input('update_bdd', '', array('type' => 'hidden', 'value' => 1));
					?>
						<div class="row" style="text-align: right; padding-right: 0; padding-bottom: 0; border-top: none;"><button class="medium grey" type="submit"><span>Exécuter automatiquement la mise à jour</span></button></div>
					<?php echo $helpers['Form']->end(false); ?>								
				</div>
			</div>
		<?php } ?>
		
		<div class="box">
			<div class="title">
				<h2>VÉRIFICATION DE LA VERSION DE KOEZION CMS</h2>
			</div>
			<div class="content">
				<div class="system info">
					Version locale de KoézionCMS : <b><?php echo $cmsVersion['localVersion']; ?></b><br />
					Version distante de KoéZionCMS : <b><?php echo $cmsVersion['remoteVersion']; ?></b> 
				</div>
				<?php if($cmsVersion['localVersion'] < $cmsVersion['remoteVersion']) { ?>
					<div class="system error">Votre code nécessite une mise à jour.</div>
				<?php } else { ?>
					<div class="system succes">Votre code ne nécessite aucune mise à jour.</div>
				<?php } ?>		
			</div>
		</div>
	
		<div class="box">
			<div class="title">
				<h2>Message de KoéZion CMS</h2>
			</div>
			<div class="content">
				<?php 
				$koezionCmsMessage = file_get_contents('http://www.koezion-cms.com/__MESSAGES__/');
				echo $koezionCmsMessage;
				?>
			</div>
		</div>
	</div>
</div>