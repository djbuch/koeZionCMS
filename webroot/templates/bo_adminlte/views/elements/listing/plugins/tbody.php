<?php foreach($$params['controllerVarName'] as $k => $v): ?>
	<tr>			
		<td class="text-center"><?php echo $v['id']; ?></td>
		<td class="text-center"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
		<td>
			<?php echo $v['name']; ?><br />
			<?php echo $v['description']; ?>
		</td>
		<td class="text-center"><?php if($v['installed']) { echo $helpers['Html']->backoffice_delete_picto($params['controllerFileName'], $v['id'], 'uninstall'); } ?></td>
	</tr>
<?php endforeach; ?>
