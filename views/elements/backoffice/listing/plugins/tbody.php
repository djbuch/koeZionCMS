<tbody class="list_elements">
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr>			
			<td class="txtcenter xxs"><?php echo $v['id']; ?></td>
			<td class="txtcenter xs"><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/statut/'.$v['id']); ?>"><span class="label <?php echo ($v['online'] == 1) ? 'success' : 'error'; ?> chgstatut"><?php echo ($v['online'] == 1) ? '&nbsp;' : '&nbsp;'; ?></span></a></td>
			<?php /* ?><td class="txtcenter xs"><?php echo $v['code']; ?></td><?php */ ?>
			<td class="auto_size_td">
				<?php echo $v['name']; ?><br />
				<?php echo $v['description']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</tbody>