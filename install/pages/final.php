<?php 
if(isset($_GET['action']) && $_GET['action'] == 'sendmail') {
	
	require_once(CAKEPHP.DS.'inflector.php');
	require_once(KOEZION.DS.'object.php');
	require_once(KOEZION.DS.'model.php');
	require_once(MODELS.DS.'website.php');
	
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
<div id="right">		
	<div id="main">		
		<div class="box">
			<div class="title">
				<h2>FIN DE L'INSTALLATION</h2>
			</div>			
			<div class="content nopadding">	
				<div class="system succes">
					<b>Bravo!!!</b><br />
					Vous venez de terminer l'installation du CMS koéZion<br />					
				</div>
				<div class="system warning">
					<b>Accès au Backoffice</b><br />
					Pour vous connecter au backoffice utilisez l'adresse www.votrenomdedomaine.com/adm <br />
					Le login par défaut est <b>superadmin</b><br />
					Le mot de passe par défaut est <b>superadmin</b><br />
					<i>Pensez à le changer par un mot de passe que vous seul pourrez retrouver</i>
					
				</div>
				<div class="system info">
					<b>Astuce</b><br />
					Pendant le temps du paramétrage des différentes options de votre site Internet nous vous conseillons d'activer l'option permettant de sécuriser votre site Internet, ceci afin éviter que vos pages ne soient référencées durant la création de votre site Internet. 
				</div>
				<div class="system error">
					<b>ATTENTION INFORMATION IMPORTANTE</b><br />
					Veillez à supprimer le dossier install qui se trouve à la racine de votre dossier d'hébergement sur votre serveur
				</div>
				<?php //require_once(ROOT.DS.'core'.DS.'Koezion'.DS.'router.php'); ?>						
				<div class="row" style="overflow: hidden;">
					<a href="<?php echo Router::url('/'); ?>" target="_blank"><button class="medium grey" type="submit" style="float: right;"><span>Accéder à la page d'accueil de votre site koéZionCMS</span></button></a>
					<a href="<?php echo Router::url('/adm'); ?>" target="_blank"><button class="medium grey" type="submit" style="float: right;"><span>Accéder à l'espace d'administration de votre site koéZionCMS</span></button></a>						
				</div>
				
				<div class="row" style="overflow: hidden;">
					<div class="system info">
						<b>Information</b><br />
						Souhaitez vous nous envoyer un email pour que votre site apparaisse dans notre portfolio?<br />
						Si oui cliquez sur le bouton ci-dessous.
					</div>
					<form action="index.php?step=final&action=sendmail" method="post">
						<button class="medium grey" type="submit" style="float: right;"><span>Prévenir KoéZionCMS</span></button>
					</form>
				</div>					
			</div>
		</div>	
	</div>
</div>