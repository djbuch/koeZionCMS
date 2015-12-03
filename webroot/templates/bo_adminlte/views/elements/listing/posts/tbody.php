<?php $this->element('listing/commun/tbody_display'); ?>
<?php /*foreach($$params['controllerVarName'] as $categoryId => $postsList): ?>
	<tr class="table_line_title">
		<?php if($postsOrder == 'order_by') { $colspan = 6; } else { $colspan = 5; } ?>
		<th colspan="<?php echo $colspan; ?>">
			<?php 
			$category = $this->request('Categories', 'get_category_link', array('id' => $categoryId));
			echo _('Page').' : '.$category['name']; 
			?>
		</th>
	</tr>		
	<tbody class="list_elements">
		<?php foreach($postsList as $k => $v): ?>		
			<tr <?php if($displayAll) { echo 'class="sortable" id="ligne_'.$v['id'].'"'; } ?>>			
				<?php if($postsOrder == 'order_by') { ?>
					<td class="text-center">
						<?php 
						if($displayAll) { echo $helpers['Html']->img('bo_adminlte/img/move.png', array('alt' => _('Déplacer'), 'title' => _('Déplacer'))); }
						//else { echo $v['order_by']; }
						?>
					</td>
				<?php } ?>
				<td class="text-center"><?php echo $v['id']; ?></td>				
				<td class="text-center"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
				<td><?php echo $helpers['Html']->backoffice_edit_link($params['controllerFileName'], $v['id'], $v['name']); ?></td>
				<td class="actions text-center">	
					<?php echo $helpers['Html']->backoffice_edit_picto($params['controllerFileName'], $v['id']); ?>			
					<?php echo $helpers['Html']->backoffice_delete_picto($params['controllerFileName'], $v['id']); ?>			
				</td>
				<td class="text-center"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'onlyInput' => true)); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
<?php endforeach;*/ ?>