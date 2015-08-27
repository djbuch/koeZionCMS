<tbody>
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr>
			<td class="txtcenter xxs"><?php echo $v['id']; ?></td>
			<td class="txtcenter xxs">
				<?php										 
				if($v['type'] == 1) { $helpers['Html']->img('bo_godzilla/img/in_arbo.png', array('alt' => _('Page catégorie'), 'title' => _('Page catégorie'))); } 
				else if($v['type'] == 2) { $helpers['Html']->img('bo_godzilla/img/event.png', array('alt' => _('Page évènement'), 'title' => _('Page évènement'))); }
				else if($v['type'] == 3) { $helpers['Html']->img('bo_godzilla/img/root.png', array('alt' => _('Racine'), 'title' => _('Racine'))); } 
				else if($v['type'] == 4) { $helpers['Html']->img('bo_godzilla/img/page_volante.png', array('alt' => _('Page volante'), 'title' => _('Page volante'))); } 
				?>
			</td>
			<td class="txtcenter xs"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
			<td>
				<?php				
				if($v['level'] == 0) { $level = $v['level']; }
				else { $level = $v['level']-1; }
				$name = str_repeat('____', $level).$v['name'];	
				echo $helpers['Html']->backoffice_edit_link($params['controllerFileName'], $v['id'], $name);
				?>
			</td>
			<td class="txtcenter xxs"><?php echo $v['lft']; ?></td>
			<td class="txtcenter xxs"><?php echo $v['rgt']; ?></td>
			<td class="txtcenter l">				
				<?php echo $helpers['Html']->backoffice_move2prev_picto($params['controllerFileName'], $v['id']); ?>			
				<?php echo $helpers['Html']->backoffice_move2next_picto($params['controllerFileName'], $v['id']); ?>
				<?php echo $helpers['Html']->backoffice_edit_picto($params['controllerFileName'], $v['id']); ?>			
				<?php echo $helpers['Html']->backoffice_delete_picto($params['controllerFileName'], $v['id']); ?>			
			</td>
			<td class="txtcenter xxs"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>