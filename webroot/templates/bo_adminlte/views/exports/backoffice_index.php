<section class="content-header">
	<h1><?php echo _("Exports"); ?></h1>
</section>	
<section class="content">
	<div class="row">
    	<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-body">
					<div class="col-md-12">
						<p><a class="btn blue" href="<?php echo Router::url('backoffice/exports/database', 'sql'); ?>" target="_blank"><span><?php echo _("Sauvegarde de la base de données"); ?></span></a></p>
						<p><a class="btn blue" href="<?php echo Router::url('backoffice/exports/contacts', 'csv'); ?>" target="_blank"><span><?php echo _("Sauvegarde de tous les contacts"); ?></span></a></p>
						<p><a class="btn blue" href="<?php echo Router::url('backoffice/exports/contacts/1', 'csv'); ?>" target="_blank"><span><?php echo _("Sauvegarde des contacts actifs"); ?></span></a></p>
						<p><a class="btn blue" href="<?php echo Router::url('backoffice/exports/contacts/0', 'csv'); ?>" target="_blank"><span><?php echo _("Sauvegarde des contacts inactifs"); ?></span></a></p>
					</div>
				</div>
			</div>	
			<div class="box box-primary">
				<div class="box-body">				
					<div class="col-md-12">
						<p><?php echo _("Ce module vous permet d'activer des sauvegarde automatique de votre base de données par le mise en place d'une tâche"); ?> <a href="http://fr.wikipedia.org/wiki/Cron" target="_blank">CRON</a></p>
						<p><?php echo ("Vous pouvez utiliser des services CRON gratuits comme par exemple"); ?> <a href="http://www.cronoo.com/" target="_blank">Cronoo</a></p>
						<?php 			
						$websiteUrl = Session::read('Backoffice.Websites.details.'.CURRENT_WEBSITE_ID.'.url');
						
						require_once(LIBS.DS.'config_magik.php');
						$cfg = new ConfigMagik(CONFIGS_FILES.DS.'security_code.ini', true, false);
						$exportCode = $cfg->keys_values();
						
						if(empty($exportCode['security_code'])) {
							
							?><p><?php echo _("Pour utiliser cette fonctionnalité vous devez en premier lieu"); ?> <a href="<?php echo Router::url('backoffice/configs/security_code_liste'); ?>"><?php echo _("paramétrer le code de sécurité"); ?></a> <?php echo _("utilisé pour pouvoir lancer cette procédure"); ?></p><?php 	
							
						} else {
							
							?>
							<p><?php echo _("Vous pouvez lancer vos backups automatiques par l'url suivante"); ?> <?php echo $websiteUrl; ?>/exports/database.xml?export_code=<?php echo $exportCode['security_code']; ?></p>
							<p><?php echo _("Le type du format de retour est l'XML"); ?></p>
							<p>
							&lt;export&gt;<br />
							&nbsp;&nbsp;&nbsp;&nbsp;&lt;result&gt;<?php echo _("EXPORT EFFECTUE"); ?>&lt;/result&gt;<br />
							&nbsp;&nbsp;&nbsp;&nbsp;&lt;message&gt;<?php echo _("Votre export est disponible à l'endroit suivant CHEMIN VERS LE FICHIER SUR VOTRE FTP"); ?>&lt;/message&gt;<br />
							&lt;/export&gt;
							</p>				
							<?php 				
						}
						?>					
					</div>
				</div>
			</div>
    	</div>
    	<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-body">
					
					<div class="col-md-12">
						<?php 
						//Récupération de l'ensemble des backup
						$bddBackupPath = TMP.DS.'backup_bdd';
						$bddBackupFiles = FileAndDir::directoryContent($bddBackupPath);
						if(!empty($bddBackupFiles)) {
							
							rsort($bddBackupFiles);
							?>
							<table cellspacing="0" cellpadding="0" border="0">	
								<thead>
									<tr>
										<th><?php echo _("Fichier de backup"); ?></th>
									</tr>
								</thead>	
								<tbody class="list_elements">
									<?php foreach($bddBackupFiles as $bddBackupFile): ?>
										<tr>
											<td class="auto_size_td"><a href="<?php echo Router::url('adm/exports/get_bdd_backup'); ?>?file=<?php echo $bddBackupFile; ?>"><?php echo $bddBackupFile; ?></a></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
							<?php 
						} 
						?>					
					</div>
				</div>
			</div>
		</div>	
    </div>
</section>