<?php 
if(isset($_GET['action']) && $_GET['action'] == 'sendmail') {
	
	require_once CAKEPHP.DS.'inflector.php';
	require_once SYSTEM.DS.'object.php';
	require_once SYSTEM.DS.'model.php';
	require_once MODELS.DS.'website.php';
	
	$message = 'Adresse : ';
	
	$websiteModel = new Website();
	$website = $websiteModel->findFirst(array('conditions' => array('online' => 1)));
	$urls = explode("\n", $website['url']);	
	
	foreach($urls as $url) { $message .= trim($url).", "; }	
	$message = substr($message, 0, strlen($message) - 2).'.';
	$message .= "\n".'Penser a demander la description au webmaster pour insertion dans le portfolio';
	
	$to      = 'contact@koezion-cms.com';
	$subject = '..:: Nouveau Site KoéZion Installé ::..';
	
	$headers = 'From: contact@koezion-cms.com'."\r\n".'Reply-To: contact@koezion-cms.com' . "\r\n".'X-Mailer: PHP/' . phpversion();
	$checkSendMail = @mail($to, $subject, $message, $headers);
}
?>
<div class="box box-primary">
	<div class="box-header bg-light-blue">
		<h4><i class="fa fa-folder-open"></i> <?php echo _("FIN DE L'INSTALLATION"); ?></h4>                  
	</div>    		
	<div class="box-body">
		
		<div class="alert alert-success alert-dismissable">
        	<h4><i class="icon fa fa-check"></i> <?php echo _("Bravo"); ?>!</h4>
            <?php echo _("Vous venez de terminer l'installation d KoéZioN CMS"); ?>
		</div>		
		<div class="alert alert-warning alert-dismissable">
			<h4><i class="icon fa fa-warning"></i> <?php echo _("Accès au Backoffice"); ?></h4>
            <?php echo _("Pour vous connecter au backoffice utilisez l'adresse www.votrenomdedomaine.com/adm"); ?><br />
            <?php echo _("Le login par défaut est"); ?> <b>superadmin</b><br />
            <?php echo _("Le mot de passe par défaut est"); ?> <b>superadmin</b><br />
            <?php echo _("Pensez à le changer par un mot de passe que vous seul pourrez retrouver"); ?><br />
		</div>
		<div class="alert alert-info alert-dismissable">
            <h4><i class="icon fa fa-info"></i> <?php echo _("Astuce"); ?>!</h4>
        	<?php echo _("Pendant le temps du paramétrage des différentes options de votre site Internet nous vous conseillons d'activer l'option permettant de sécuriser votre site Internet, ceci afin éviter que vos pages ne soient référencées durant la création de votre site Internet."); ?>
		</div>		
		<div class="alert alert-danger  alert-dismissable">
			<h4><i class="icon fa fa-ban"></i> <?php echo _("INFORMATION IMPORTANTE"); ?>!</h4>
			<?php echo _("Veillez à supprimer le dossier install qui se trouve à la racine de votre dossier d'hébergement sur votre serveur."); ?>
		</div>
		
		<div style="margin-top:50px;overflow: hidden;">
			<a href="<?php echo Router::url('/'); ?>" target="_blank"><button class="btn btn-primary btn-flat pull-left"><?php echo _("Accéder au frontoffice de votre site KoéZioN CMS"); ?></button></a>
			<a href="<?php echo Router::url('/adm'); ?>" target="_blank"><button class="btn btn-primary btn-flat pull-right"><?php echo _("Accéder au backoffice de votre site KoéZioN CMS"); ?></button></a>						
		</div>	
	</div>
	<div class="box-footer"> 
		<div class="callout callout-info">
			<b><?php echo _("Information"); ?></b><br />
			<?php echo _("Souhaitez vous nous envoyer un email pour que votre site apparaisse dans notre portfolio"); ?>?<br />
			<?php echo _("Si oui cliquez sur le bouton ci-dessous"); ?>.
			<div class="text-right">
				<form action="index.php?step=final&action=sendmail" method="post">
					<button class="btn btn-warning btn-flat" type="submit"><?php echo _("Prévenir KoéZionCMS"); ?></button>
				</form>
			</div>
		</div>
	</div>	
</div>
