<tbody>
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr>
			<td class="txtcenter xxs"><?php echo $v['id']; ?></td>
			<td class="txtcenter xxs">
				<?php										 
				if($v['type'] == 1) { echo $helpers['Html']->img('backoffice/in_arbo.png', array('alt' => _('Page catégorie'), 'title' => _('Page catégorie'))); } 
				else if($v['type'] == 2) { echo $helpers['Html']->img('backoffice/event.png', array('alt' => _('Page évènement'), 'title' => _('Page évènement'))); }
				else if($v['type'] == 3) { echo $helpers['Html']->img('backoffice/root.png', array('alt' => _('Racine'), 'title' => _('Racine'))); } 
				?>
			</td>
			<td class="txtcenter xs"><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/statut/'.$v['id']); ?>"><span class="label <?php echo ($v['online'] == 1) ? 'success' : 'error'; ?> chgstatut"><?php echo ($v['online'] == 1) ? '&nbsp;' : '&nbsp;'; ?></span></a></td>
			<?php /* ?><td class="txtcenter xs"><?php echo $v['level']; ?></td><?php */ ?>
			<td>
				<?php				
				if($v['level'] == 0) { $level = $v['level']; }
				else { $level = $v['level']-1; }
				echo str_repeat('____', $level);				
				?><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$v['id']); ?>" class="edit_link"><?php 
				echo $v['name'];
				?></a>
			</td>
			<?php /* ?><td class="txtcenter xxs"><?php echo $v['lft']; ?></td><?php */ ?>
			<?php /* ?><td class="txtcenter xxs"><?php echo $v['rgt']; ?></td><?php */ ?>
			<td class="txtcenter m">	
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/move2prev/'.$v['id']); ?>"><img src="<?php echo BASE_URL; ?>/img/backoffice/up.png" alt="up" /></a>
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/move2next/'.$v['id']); ?>"><img src="<?php echo BASE_URL; ?>/img/backoffice/down.png" alt="down" /></a>
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$v['id']); ?>"><img src="<?php echo BASE_URL; ?>/img/backoffice/thumb-edit.png" alt="edit" /></a>
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/delete/'.$v['id']); ?>" class="deleteBox" onclick="return confirm('<?php echo _("Voulez vous vraiment supprimer?"); ?>');"><img src="<?php echo BASE_URL; ?>/img/backoffice/thumb-delete.png" alt="delete" /></a>
			</td>
			<td class="txtcenter xxs"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>