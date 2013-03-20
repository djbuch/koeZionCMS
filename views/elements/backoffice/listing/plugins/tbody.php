<?php foreach($$params['controllerVarName'] as $k => $v): ?>
	<tr>			
		<td class="txtcenter xxs"><?php echo $v['id']; ?></td>
		<td class="txtcenter xs"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
		<td class="auto_size_td">
			<?php echo $v['name']; ?><br />
			<?php echo $v['description']; ?>
		</td>
	</tr>
<?php endforeach; ?>
