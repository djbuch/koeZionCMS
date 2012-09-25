<tbody class="list_elements">
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr <?php if($displayAll) { echo 'class="sortable" id="ligne_'.$v['id'].'"'; } ?>>
			<td class="txtcenter xxs"><?php echo $v['id']; ?></td>			
			<td class="txtcenter xs"><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/statut/'.$v['id']); ?>"><span class="label <?php echo ($v['online'] == 1) ? 'success' : 'error'; ?> chgstatut"><?php echo ($v['online'] == 1) ? '&nbsp;' : '&nbsp;'; ?></span></a></td>
			<td class="txtcenter xxs"><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/export/'.$v['form_file'], null); ?>"><img src="<?php echo BASE_URL; ?>/img/backoffice/xml.png" alt="Télécharger le XML" /></a></td>
			<td class="auto_size_td"><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$v['id']); ?>" class="edit_link"><?php echo $v['name']; ?></a></td>
			<td class="txtcenter xs">				
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$v['id']); ?>"><img src="<?php echo BASE_URL; ?>/img/backoffice/thumb-edit.png" alt="edit" /></a>
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/delete/'.$v['id']); ?>" class="deleteBox" onclick="return confirm('<?php echo _("Voulez vous vraiment supprimer?"); ?>');"><img src="<?php echo BASE_URL; ?>/img/backoffice/thumb-delete.png" alt="delete" /></a>
			</td>
			<td class="txtcenter xxs"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>