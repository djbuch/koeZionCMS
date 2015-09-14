<section class="content-header">
	<h1><?php echo _("Code de sécurité pour les tâches planifiées"); ?></h1>
</section>	
<section class="content">
	<div class="row">
    	<div class="add_edit_page col-md-12">    		     	
	    	<?php 
	    	$this->element('flash_messages');	    	
			echo $helpers['Form']->create(array('id' => 'ConfigSecurityCode', 'action' => Router::url('backoffice/configs/security_code_liste'), 'method' => 'post'));
			?>
			<div class="box box-primary">
				<div class="box-body">
					<?php			 
					echo $helpers['Form']->input('security_code', _("Code pour les tâches planifiées"), array('tooltip' => _("Indiquez ici le code de sécurité permettant de sécuriser les accès aux procédures CRON")));				
					echo $helpers['Form']->input('nb_bdd_backups', _("Nombre de backups BDD"), array('tooltip' => _("Indiquez ici le nombre de backups de base de données à conserver")));
					?>
				</div>
				<div class="box-footer">
					<?php echo $helpers['Form']->button(); ?>
				</div>
			</div>	
			<?php 
			echo $helpers['Form']->end(); 
			?>	
    	</div>
    </div>
</section>