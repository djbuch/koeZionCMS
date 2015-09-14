<?php foreach($$params['controllerVarName'] as $rubrique => $rubriqueDatas): ?>
	<tbody class="list_elements">
		<tr class="table_line_title">
			<th colspan="6"><?php echo _('Rubrique').' : '.$rubrique; ?></th>
		</tr>
		<?php foreach($rubriqueDatas as $k => $v): ?>
			<tr <?php if($displayAll) { echo 'class="sortable" id="ligne_'.$v['id'].'"'; } ?>>
				<td class="text-center">
					<?php 
					if($displayAll) { echo $helpers['Html']->img('bo_adminlte/img/move.png', array('alt' => _('Déplacer'), 'title' => _('Déplacer'))); }
					else { echo '&nbsp;'; }
					?>
				</td>
				<td class="text-center"><?php echo $v['id']; ?></td>
				<td class="text-center"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
				<td><?php echo $helpers['Html']->backoffice_edit_link($params['controllerFileName'], $v['id'], $v['name']); ?></td>
				<td class="text-center">	
					<?php echo $helpers['Html']->backoffice_edit_picto($params['controllerFileName'], $v['id']); ?>			
					<?php echo $helpers['Html']->backoffice_delete_picto($params['controllerFileName'], $v['id']); ?>			
				</td>
				<td class="text-center"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'onlyInput' => true)); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>		
<?php endforeach; ?>