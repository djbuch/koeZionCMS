<?php 
$websitesSession = Session::read('Backoffice.Websites');
$currentWebsite = $websitesSession['current'];
$logUsersActiv = $websitesSession['details'][$currentWebsite]['log_users_activ'];
?>

<tbody>
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr>
			<td class="txtcenter xxs"><?php echo $v['id']; ?></td>
			<td class="txtcenter xs"><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/statut/'.$v['id']); ?>"><span class="label <?php echo ($v['online'] == 1) ? 'success' : 'error'; ?> chgstatut"><?php echo ($v['online'] == 1) ? '&nbsp;' : '&nbsp;'; ?></span></a></td>
			<td>
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$v['id']); ?>" class="edit_link"><?php echo $v['name'].' - '.$v['second_name']; ?></a>
				<?php if($v['role'] == 'user' && $logUsersActiv) { ?>
					<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/logs/'.$v['id']); ?>"><img src="<?php echo BASE_URL; ?>/img/backoffice/users_log.png" alt="edit" style="float:right" /></a>
				<?php } ?>
			</td>
			<td class="txtcenter xs">				
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$v['id']); ?>"><img src="<?php echo BASE_URL; ?>/img/backoffice/thumb-edit.png" alt="edit" /></a>
				<a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/delete/'.$v['id']); ?>" class="deleteBox" onclick="return confirm('<?php echo _("Voulez vous vraiment supprimer?"); ?>');"><img src="<?php echo BASE_URL; ?>/img/backoffice/thumb-delete.png" alt="delete" /></a>
			</td>
			<td class="txtcenter xxs"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'div' => false, 'label' => false)); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>