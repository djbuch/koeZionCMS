<tfoot>
	<tr>
		<?php if($postsOrder == 'order_by') { $colspan = 6; } else { $colspan = 5; } ?>
		<td colspan="<?php echo $colspan; ?>" class="txtright"><?php echo $helpers['Html']->backoffice_delete_button($params['controllerFileName']); ?></td>					
	</tr>
</tfoot>