<?php if($pager['totalPages'] > 1) { ?>
<div class="wp-pagenavi">
	<span class="pages"><?php echo _("Page"); ?> <?php echo $pager['currentPage']; ?> <?php echo _("sur"); ?> <?php echo $pager['totalPages']; ?></span>
	<?php echo $helpers['Paginator']->paginate($pager['totalPages'], $pager['currentPage']); ?>
</div>
<?php } ?>