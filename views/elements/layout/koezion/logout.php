<?php 
$session = Session::read('Frontoffice');
if(isset($session['User']['id'])) {
	?><a href="<?php echo Router::url('users/logout'); ?>" title="Me déconnecter" style="position:fixed;width:15px;height:15px;top:10px;right:3px;background:url(./templates/koezion/img/logout.png) no-repeat top right transparent;text-indent:-9999px;display:block">Me déconnecter</a><?php	
}
?>