<?php if(Configure::read('debug') > 0) { ?>
<div style="margin-top: 30px; bottom: 0; background: #900; color: #FFF; line-height: 30px; height: 30px; left: 0; right: 0; padding-left: 10px;">
	<?php echo _("Page générée en"); ?> <?php echo Configure::read('timerExec'); ?> <?php echo _("secondes"); ?>
</div>
<?php } ?>