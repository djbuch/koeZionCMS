<tbody>
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr>
			<td class="txtcenter xxs"><?php echo $v['id']; ?></td>
			<td class="txtcenter xs"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
			<td class="txtcenter m">
				<?php 
				$dateContact = $this->vars['components']['Text']->date_sql_to_human($v['created']);
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
			<td class="txtcenter xs">	
				<?php echo $helpers['Html']->backoffice_edit_picto($params['controllerFileName'], $v['id']); ?>			
				<?php echo $helpers['Html']->backoffice_delete_picto($params['controllerFileName'], $v['id']); ?>			
			</td>
			<td class="txtcenter xxs"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>