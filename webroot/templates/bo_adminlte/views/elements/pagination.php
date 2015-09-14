<?php if($pager['totalPages'] > 1) { ?>
	<span class="pages"><?php echo _("Page"); ?> <?php echo $pager['currentPage']; ?> <?php echo _("sur"); ?> <?php echo $pager['totalPages']; ?></span>
	<ul class="pagination pagination-sm no-margin pull-right">
		<?php echo $helpers['Paginator']->paginate($pager['totalPages'], $pager['currentPage']); ?>
	</ul>
<?php } ?>


                  