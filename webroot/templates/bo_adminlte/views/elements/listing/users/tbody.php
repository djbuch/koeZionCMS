<?php 
$websitesSession = Session::read('Backoffice.Websites');
$currentWebsite = $websitesSession['current'];
$logUsersActiv = $websitesSession['details'][$currentWebsite]['log_users_activ'];
?>
<tbody>
	<?php foreach($$params['controllerVarName'] as $k => $v): ?>
		<tr>
			<td class="text-center"><?php echo $v['id']; ?></td>
			<td class="text-center"><?php echo $helpers['Html']->backoffice_statut_link($params['controllerFileName'], $v['id'], $v['online']); ?></td>
			<td>
				<?php 
				echo $helpers['Html']->backoffice_edit_link($params['controllerFileName'], $v['id'], $v['name'].' - '.$v['second_name']); 
				$role = $this->request('Users', 'backoffice_get_user_role', array($v['users_group_id']));
				if($role == 3 && $logUsersActiv) { 
					?><a href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/logs/'.$v['id']); ?>"><img src="<?php echo BASE_URL; ?>/templates/bo_adminlte/img/users_log.png" alt="edit" style="float:right" /></a><?php 
				} 
				?>
			</td>
			<td class="text-center">	
				<?php echo $helpers['Html']->backoffice_edit_picto($params['controllerFileName'], $v['id']); ?>			
				<?php echo $helpers['Html']->backoffice_delete_picto($params['controllerFileName'], $v['id']); ?>			
			</td>
			<td class="text-center"><?php echo $helpers['Form']->input('delete.'.$v['id'], '', array('type' => 'checkbox', 'onlyInput' => true)); ?></td>
		</tr>
	<?php endforeach; ?>
</tbody>