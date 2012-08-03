<tbody class="list_elements">
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
		<?php foreach($postsList as $k => $v): ?>		
			<tr <?php if($displayAll) { echo 'class="sortable" id="ligne_'.$v['id'].'"'; } ?>>			
				<?php if($postsOrder == 'order_by') { ?>
					<td class="txtcenter xxs2">
						<?php 
						if($displayAll) { echo $helpers['Html']->img('backoffice/move.png', array('alt' => _('Déplacer'), 'title' => _('Déplacer'))); }
						else { echo $v['order_by']; }
						?>
					</td>
				<?php } ?>
				<td class="txtcenter xxs"><?php echo $v['id']; ?></td>
				<td class="txtcenter xs"><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/statut/'.$v['id']); ?>"><span class="label <?php echo ($v['online'] == 1) ? 'success' : 'error'; ?> chgstatut"><?php echo ($v['online'] == 1) ? '&nbsp;' : '&nbsp;'; ?></span></a></td>
				<td class="auto_size_td"><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$v['id']); ?>" class="edit_link"><?php echo $v['name']; ?></a></td>
				<td class="txtcenter xs">				
					<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$v['id']); ?>"><img src="<?php echo BASE_URL; ?>/img/backoffice/thumb-edit.png" alt="edit" /></a>
					<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/delete/'.$v['id']); ?>" class="deleteBox" onclick="return confirm('<?php echo _("Voulez vous vraiment supprimer?"); ?>');"><img src="<?php echo BASE_URL; ?>/img/backoffice/thumb-delete.png" alt="delete" /></a>
				</td>
				<td class="txtcenter xxs"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?></td>
			</tr>
		<?php endforeach; ?>
	<?php endforeach; ?>
</tbody>