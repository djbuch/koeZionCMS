<div class="title">
	<h2>
		<?php echo _("Catalogue produits")." - "; ?> 
		<?php echo ($pager['totalElements'] > 0) ? $pager['totalElements'] : 'Aucun'; ?> éléments
	</h2>
	<a class="btn black" href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/add'); ?>" style="float: right; margin-top: 3px;"><span><?php echo _("Ajouter"); ?></span></a>
	<a class="btn black" href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/import'); ?>" style="float: right; margin-top: 3px;"><span><?php echo _("Importer"); ?></span></a>
</div>