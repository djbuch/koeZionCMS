<div class="section">
	<div class="box">
		<div class="title"><h2><?php echo _("Configuration du coeur de KoéZionCMS"); ?></h2></div>
		<div class="content nopadding">
			<?php 			
			echo $helpers['Form']->create(array('id' => 'ConfigCore', 'action' => Router::url('backoffice/configs/core_liste'), 'method' => 'post')); 
				echo $helpers['Form']->input('log_sql', 'Activer le log SQL', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour activer le log des reqêtes SQL effectuées"));
				echo $helpers['Form']->input('log_php', 'Activer le log PHP', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour activer le log des erreurs PHP"));
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>