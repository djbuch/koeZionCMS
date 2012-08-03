<tfoot>
	<tr>
		<?php if($postsOrder == 'order_by') { $colspan = 6; } else { $colspan = 5; } ?>
		<td colspan="<?php echo $colspan; ?>" class="txtright"><a class="btn red deleteFormBox" onclick="return confirm('<?php echo _("Voulez vous vraiment supprimer?"); ?>');" href="formDelete" style="margin-top: 10px;"><span><?php echo _("SUPPRIMER"); ?></span></a></td>					
	</tr>
</tfoot>