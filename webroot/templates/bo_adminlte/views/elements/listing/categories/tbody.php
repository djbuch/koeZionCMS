<tbody>
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr>
			<td class="text-center"><?php echo $v['id']; ?></td>
			<td class="text-center">
				<?php										 
				if($v['type'] == 1) { echo $helpers['Html']->img('bo_adminlte/img/in_arbo.png', array('alt' => _('Page catégorie'), 'title' => _('Page catégorie'))); } 
				else if($v['type'] == 2) { echo $helpers['Html']->img('bo_adminlte/img/event.png', array('alt' => _('Page évènement'), 'title' => _('Page évènement'))); }
				else if($v['type'] == 3) { echo $helpers['Html']->img('bo_adminlte/img/root.png', array('alt' => _('Racine'), 'title' => _('Racine'))); } 
				else if($v['type'] == 4) { echo $helpers['Html']->img('bo_adminlte/img/page_volante.png', array('alt' => _('Page volante'), 'title' => _('Page volante'))); } 
				?>
			</td>
			<td class="text-center"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
			<td class="text-center"><?php echo $v['level']; ?></td>
			<td>
				<?php				
				$name = $v['name'];
				if($v['level'] == 0) { $level = $v['level']; } 
				else { $level = $v['level']-1; }
				
				if($level==0) { $name = '<b>'.$name.'</b>'; }
				
				$name = str_repeat('____', $level).$name;	
				echo $helpers['Html']->backoffice_edit_link($params['controllerFileName'], $v['id'], $name);
				?>
			</td>
			<?php /* ?><td class="text-center"><?php echo $v['lft']; ?></td>
			<td class="text-center"><?php echo $v['rgt']; ?></td><?php */ ?>
			<td class="actions text-center">				
				<?php echo $helpers['Html']->backoffice_move2prev_picto($params['controllerFileName'], $v['id']); ?>			
				<?php echo $helpers['Html']->backoffice_move2next_picto($params['controllerFileName'], $v['id']); ?>
				<?php echo $helpers['Html']->backoffice_picto($params['controllerFileName'], 'add', '/templates/bo_adminlte/img/thumb-add.png', $v['id']); ?>
				<?php echo $helpers['Html']->backoffice_edit_picto($params['controllerFileName'], $v['id']); ?>			
				<?php echo $helpers['Html']->backoffice_delete_picto($params['controllerFileName'], $v['id']); ?>			
			</td>
			<td class="text-center"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'onlyInput' => true)); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>