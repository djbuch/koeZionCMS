<?php 
$process_mail = false;

//Si les données sont postées
if(isset($_POST) && !empty($_POST)) {
		
	$datas = $_POST; //Création d'une variable contenant les données postées

	require_once(INSTALL_VALIDATE.DS.'smtp.php'); //Inclusion des règles de validation des champs
		
	//Si pas d'erreur de validation
	if(!isset($formerrors)) {
			
		require_once(LIBS.DS.'config_magik.php'); //Import de la librairie de gestion des fichiers de configuration
		$cfg = new ConfigMagik(CONFIGS_FILES.DS.'mailer.ini', true, false); //Création d'une instance 
			
		//On va parcourir les données postées et mettre à jour le fichier ini
		foreach($datas as $k => $v) { $cfg->set($k, $v); }
			
		$cfgSmtp = $cfg->save(); //On sauvegarde le fichier de configuration
		
		//On va tester l'envoi du mail de test
		if($cfgSmtp) {
			
			require_once(COMPONENTS.DS.'email.php'); //Import de la librairie de gestion des emails
			$email = new Email();
			$email_sent = $email->send_test();
			
			$process_mail = $cfgSmtp && $email_sent;
		}
	}
}
?>
<div id="right">		
	<div id="main">				
		
		<div class="box">			
			<div class="title">
				<h2>CONFIGURATION DU SERVEUR SMTP</h2>
			</div>
			<div class="content nopadding">				
				<?php				
				if(!$process_mail) {
						
					//Si le check de connexion à la bdd n'a pas fonctionné
					if(isset($cfgSmtp) && !$process_mail) { ?><div class="system error">Impossible de paramétrer le serveur SMTP avec les informations communiquées, veuillez recommencer.</div><?php } 
					require_once(INSTALL_INCLUDE.DS.'smtp_form.php');
				} else { 
					
					?>					
					<div class="system succes">
						Votre serveur SMTP est maintenant paramétré.<br /><br />
						Si toutes les informations que vous avez saisies sont correctes vous allez recevoir un email de confirmation.
					</div>
					<div class="system warning">						
						Si vous ne revecez pas cet email vérifiez également les messages passés en spam si vous n'avez pas reçu de message de l'expéditeur <b>noreply@koezion-cms.com</b>.<br /><br />
						Si toutefois l'email n'était arrivé vérifiez bien que les informations saisies ne soient pas fausses.
					</div>
					<form action="index.php?step=final" method="post">					
						<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Finaliser de l'installation</span></button></div>
					</form>	
					<?php 
				} 
				?>
			</div>			
		</div>	
	</div>
</div>