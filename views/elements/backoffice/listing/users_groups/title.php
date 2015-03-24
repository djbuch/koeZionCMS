<div class="title">
	<h2>
		<?php echo _("Groupe d'utilisateurs")." - "; ?> 
		<?php echo ($pager['totalElements'] > 0) ? $pager['totalElements'] : _('Aucun'); ?> <?php echo _('éléments'); ?>
	</h2>	
	<?php 
	echo $helpers['Html']->backoffice_button_title($params['controllerFileName'], 'add', _("Ajouter"));
	echo $helpers['Html']->backoffice_button_title('users', 'index', _("Listing des utilisateurs"));
	?>
</div>