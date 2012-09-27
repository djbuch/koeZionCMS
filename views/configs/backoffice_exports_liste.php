<div class="section">
	<div class="box">
		<div class="title"><h2><?php echo _("Code de validation pour l'export"); ?></h2></div>
		<div class="content nopadding">
			<?php 			
			echo $helpers['Form']->create(array('id' => 'ConfigExports', 'action' => Router::url('backoffice/configs/exports_liste'), 'method' => 'post')); 
				echo $helpers['Form']->input('export_code', _("Code pour export par CRON"), array('tooltip' => _("Indiquez ici le code de sécurité permettant de générer les fichiers de backup de la base de données par CRON")));				
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>