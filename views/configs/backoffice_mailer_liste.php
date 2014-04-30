<div class="section">
	<div class="box">
		<div class="title">
			<h2><?php echo _("Configuration des envois de mails"); ?></h2>
		</div>
		<div class="content nopadding">
			<?php			
			echo $helpers['Form']->create(array('id' => 'ConfigMailer', 'action' => Router::url('backoffice/configs/mailer_liste'), 'method' => 'post')); 
				echo $helpers['Form']->input('smtp_host', _('Adresse du serveur SMTP'), array('tooltip' => _("Indiquez ici l'adresse du serveur smtp (par exemple smtp.mondomaine.com)")));			 
				echo $helpers['Form']->input('smtp_port', _('Port smtp'), array('tooltip' => _("Indiquez ici le port smtp (par exemple 25)")));			 
				echo $helpers['Form']->input('smtp_user_name', _('Login'), array('tooltip' => _("Indiquez ici le login du compte")));			 
				echo $helpers['Form']->input('smtp_password', _('Mot de passe'), array('tooltip' => _("indiquez ici le mot de passe du compte"), 'type' => 'password'));			 
				echo $helpers['Form']->input('mail_set_from_email', _('Email expéditeur'), array('tooltip' => _("Indiquez ici l'adresse email qui apparaitra dans l'expéditeur")));	
				echo $helpers['Form']->input('mail_set_from_name', _('Nom expéditeur'), array('tooltip' => _("Indiquez ici le nom qui apparaitra dans l'expéditeur")));	
				echo $helpers['Form']->input('bcc_email', _('Copie cachée à'), array('tooltip' => _("Indiquez un email dans lequel vous recevrez une copie (Si plusieurs emails les séparer par des ;)")));
				
				$streamGetTransports = stream_get_transports(); //retourne un tableau indexé contenant les noms des transports de sockets disponibles pour le système. cf : http://php.net/manual/fr/function.stream-get-transports.php
				if(in_array('ssl', $streamGetTransports)) {
					
					echo $helpers['Form']->input('smtp_secure', 'Utiliser un protocole sécurisé (SSL) pour les envois de mails', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour activer le protocole sécurisé lors de l'envoi de vos emails"));
				}	
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>