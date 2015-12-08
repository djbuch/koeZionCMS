<?php 
$session = Session::read('Frontoffice');
if(isset($session['User']['id'])) {
	
	?><a href="<?php echo Router::url('users/frontoffice_logout'); ?>" title="<?php echo _("Me déconnecter"); ?>" style="position:fixed;top:10px;right:10px;display:block"><?php echo _("Me déconnecter"); ?></a><?php	
}
?>