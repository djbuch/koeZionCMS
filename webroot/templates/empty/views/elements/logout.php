<?php 
$session = Session::read('Frontoffice');
if(isset($session['User']['id'])) {
	?><a href="<?php echo Router::url('users/logout'); ?>" title="Me déconnecter">Me déconnecter</a><?php	
}
?>