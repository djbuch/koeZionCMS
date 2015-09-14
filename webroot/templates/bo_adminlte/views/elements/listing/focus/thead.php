<thead>
	<tr>
		<th class="text-center xxs"><?php if(!$displayAll) { ?><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/index.html?displayall=1', ''); ?>"><?php echo $helpers['Html']->img('bo_adminlte/img/activ_sortable.png', array('alt' => _('Activer le rang'))); ?></a><?php } ?></th>
		<th class="text-center xxs">#</th>
		<th class="text-center xs"><?php echo _("Statut"); ?></th>
		<th><?php echo _("Libellé"); ?></th>
		<th class="text-center s"><?php echo _("Actions"); ?></th>
		<th class="text-center xxs"><input type="checkbox" class="checkall" /></th>
	</tr>
</thead>