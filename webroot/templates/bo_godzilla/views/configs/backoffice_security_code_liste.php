<div class="section">
	<div class="box">
		<div class="title"><h2><?php echo _("Code de sécurité pour les tâches planifiées"); ?></h2></div>
		<div class="content nopadding">
			<?php 			
			echo $helpers['Form']->create(array('id' => 'ConfigSecurityCode', 'action' => Router::url('backoffice/configs/security_code_liste'), 'method' => 'post'));				
				echo $helpers['Form']->input('security_code', _("Code pour les tâches planifiées"), array('tooltip' => _("Indiquez ici le code de sécurité permettant de sécuriser les accès aux procédures CRON"), 'class' => 'quarter-input'));				
				echo $helpers['Form']->input('nb_bdd_backups', _("Nombre de backups BDD"), array('tooltip' => _("Indiquez ici le nombre de backups de base de données à conserver"), 'class' => 'quarter-input'));				
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>