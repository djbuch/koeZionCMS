<?php foreach($$params['controllerVarName'] as $categoryId => $postsList): ?>
	<tr>
		<?php if($postsOrder == 'order_by') { $colspan = 6; } else { $colspan = 5; } ?>
		<th colspan="<?php echo $colspan; ?>">
			<?php 
			$category = $this->request('Categories', 'get_category_link', array('id' => $categoryId));
			echo $category['name']; 
			?>
		</th>
	</tr>		
	<tbody class="list_elements">
		<?php foreach($postsList as $k => $v): ?>		
			<tr <?php if($displayAll) { echo 'class="sortable" id="ligne_'.$v['id'].'"'; } ?>>			
				<?php if($postsOrder == 'order_by') { ?>
					<td class="txtcenter xxs2">
						<?php 
						if($displayAll) { echo $helpers['Html']->img('/backoffice/move.png', array('alt' => _('Déplacer'), 'title' => _('Déplacer'))); }
						//else { echo $v['order_by']; }
						?>
					</td>
				<?php } ?>
				<td class="txtcenter xxs"><?php echo $v['id']; ?></td>				
				<td class="txtcenter xs"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
				<td class="auto_size_td"><?php echo $helpers['Html']->backoffice_edit_link($params['controllerFileName'], $v['id'], $v['name']); ?></td>
				<td class="txtcenter xs">	
					<?php echo $helpers['Html']->backoffice_edit_picto($params['controllerFileName'], $v['id']); ?>			
					<?php echo $helpers['Html']->backoffice_delete_picto($params['controllerFileName'], $v['id']); ?>			
				</td>
				<td class="txtcenter xxs"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
<?php endforeach; ?>