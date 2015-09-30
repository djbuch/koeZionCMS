<section class="content-header">
	<h1><?php echo _("Configuration des envois de mails"); ?></h1>
</section>	
<section class="content">
	<div class="row">
    	<div class="add_edit_page col-md-12">    		     	
	    	<?php 
	    	$this->element('flash_messages');	    	
			echo $helpers['Form']->create(array('id' => 'ConfigMailer', 'action' => Router::url('backoffice/configs/mailer_liste'), 'method' => 'post'));
			?>
			<div class="box box-primary">
				<div class="box-body">
					<div class="col-md-12">
						<?php
						echo $helpers['Form']->input('smtp_host', _('Adresse du serveur SMTP'), array('tooltip' => _("Indiquez ici l'adresse du serveur smtp (par exemple smtp.mondomaine.com)")));			 
						echo $helpers['Form']->input('smtp_port', _('Port smtp'), array('tooltip' => _("Indiquez ici le port smtp (par exemple 25)")));			 
						echo $helpers['Form']->input('smtp_user_name', _('Login'), array('tooltip' => _("Indiquez ici le login du compte")));			 
						echo $helpers['Form']->input('smtp_password', _('Mot de passe'), array('tooltip' => _("indiquez ici le mot de passe du compte"), 'type' => 'password'));			 
						echo $helpers['Form']->input('mail_set_from_email', _('Email expéditeur'), array('tooltip' => _("Indiquez ici l'adresse email qui apparaitra dans l'expéditeur")));	
						echo $helpers['Form']->input('mail_set_from_name', _('Nom expéditeur'), array('tooltip' => _("Indiquez ici le nom qui apparaitra dans l'expéditeur")));	
						echo $helpers['Form']->input('bcc_email', _('Copie cachée à'), array('tooltip' => _("Indiquez un email dans lequel vous recevrez une copie (Si plusieurs emails les séparer par des ;)")));
						
						$streamGetTransportsTMP = stream_get_transports(); //retourne un tableau indexé contenant les noms des transports de sockets disponibles pour le système. cf : http://php.net/manual/fr/function.stream-get-transports.php
						
						if($streamGetTransportsTMP) {
							
							$streamGetTransports = array();
							foreach($streamGetTransportsTMP as $v) { $streamGetTransports[$v] = $v; }					
							echo $helpers['Form']->input('smtp_secure', _('Protocole envoi de mail'), array('type' => 'select', 'datas' => $streamGetTransports, 'firstElementList' => _("Sélectionnez un protocole")));					
							/*if(in_array('ssl', $streamGetTransports)) { echo $helpers['Form']->input('smtp_secure', _('Utiliser un protocole sécurisé (SSL) pour les envois de mails'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le protocole sécurisé lors de l'envoi de vos emails"))); }*/	
						}
						?>
					</div>
				</div>
				<div class="box-footer">
					<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>
				</div>
			</div>	
			<?php 
			echo $helpers['Form']->end(); 
			?>	
    	</div>
    </div>
</section>