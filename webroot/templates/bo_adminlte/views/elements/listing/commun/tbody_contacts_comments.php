<tbody>
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr>
			<td class="text-center"><?php echo $v['id']; ?></td>
			<td class="text-center"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
			<td class="text-center">
				<?php 
				$dateContact = $this->vars['components']['Date']->date_sql_to_human($v['created']);
				echo $dateContact['date']['fullNumber'];
				?>				
			</td>
			<td>
				<?php 
				$name = $v['name']; 
				if(!empty($v['name'])) { $name .= ' - '; }
				$name .= $v['email'];				
				echo $helpers['Html']->backoffice_edit_link($params['controllerFileName'], $v['id'], $name); 
				?>				
			</td>
			<td class="actions text-center">	
				<?php echo $helpers['Html']->backoffice_edit_picto($params['controllerFileName'], $v['id']); ?>			
				<?php echo $helpers['Html']->backoffice_delete_picto($params['controllerFileName'], $v['id']); ?>			
			</td>
			<td class="text-center"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'onlyInput' => true)); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>