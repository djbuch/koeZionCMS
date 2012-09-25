<tbody class="list_elements">
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr>			
			<td class="txtcenter xxs"><?php echo $v['id']; ?></td>
			<td class="txtcenter xs"><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/statut/'.$v['id']); ?>"><span class="label <?php echo ($v['online'] == 1) ? 'success' : 'error'; ?> chgstatut"><?php echo ($v['online'] == 1) ? '&nbsp;' : '&nbsp;'; ?></span></a></td>
			<td class="txtcenter xs"><?php echo $v['code']; ?></td>
			<td class="auto_size_td">
				<?php echo $v['name']; ?><br />
				<?php echo $v['description']; ?>
			</td>			
			<td class="txtcenter xs">				
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/delete/'.$v['id']); ?>" class="deleteBox" onclick="return confirm('<?php echo _("Voulez vous vraiment supprimer?"); ?>');"><img src="<?php echo BASE_URL; ?>/img/backoffice/thumb-delete.png" alt="delete" /></a>
			</td>
			<td class="txtcenter xxs"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>