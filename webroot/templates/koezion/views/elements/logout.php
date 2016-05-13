<?php 
$session = Session::read('Frontoffice');
if(isset($session['User']['id'])) {
	
	?><a href="<?php echo Router::url('users/frontoffice_logout'); ?>" title="<?php echo _("Me déconnecter"); ?>" class="logout_element"><?php echo _("Me déconnecter"); ?></a><?php	
}
?>