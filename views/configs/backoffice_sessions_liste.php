<div class="section">
	<div class="box">
		<div class="title">
			<h2><?php echo _("Configuration des variables de sessions"); ?></h2>
		</div>
		<div class="content nopadding">
			<?php 			
			echo $helpers['Form']->create(array('id' => 'ConfigSessions', 'action' => Router::url('backoffice/configs/sessions_liste'), 'method' => 'post')); 
				echo $helpers['Form']->input('name', _('Nom de la variable de session'), array('tooltip' => _("Indiquez ici le nom de la variable de session (Par exemple le nom du site en majuscules et sans caractères spéciaux)")));			 
				echo $helpers['Form']->input('time_to_live', _('Durée de vie'), array('tooltip' => _("la durée de vie est exprimée en secondes")));	
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>