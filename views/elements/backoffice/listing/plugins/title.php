<div class="title">
	<h2>
		<?php echo _("Plugins")." - "; ?> 
		<?php echo ($pager['totalElements'] > 0) ? $pager['totalElements'] : _('Aucun'); ?> <?php echo _('éléments'); ?>
	</h2>
</div>
<?php
if(extension_loaded('zip')) {

	?><div style="padding: 20px;margin: 20px;background: #f2f0f0;"><?php
	$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName'].'/install_by_zip'), 'method' => 'post');
	echo $helpers['Form']->create($formOptions);
	?><div style="float: left;"><?php
	echo $helpers['Form']->input('install_by_zip', '', array('type' => 'hidden', 'value' => 1));
	echo $helpers['Form']->input('plugin_zip_file', _('Installer un plugin via un fichier ZIP'), array('type' => 'file', 'class' => 'input-file', 'div' => false));
	?></div><?php
	?><button class="medium grey" type="submit" style="opacity: 1;margin: 0;margin-left: 20px;"><span><?php echo _("Envoyer"); ?></span></button><?php 
	echo $helpers['Form']->end();
	?></div><?php
}
?>