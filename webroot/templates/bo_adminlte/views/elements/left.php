<?php 
$currentController 	= $this->controller->request->controller;
$currentAction 		= $this->controller->request->action;
$currentUrl 		= $currentController.'/'.$currentAction; 
?>
<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<li class="header"><?php echo strtoupper(_("Menu principal")); ?></li>
			<?php 
			foreach($leftMenus as $leftMenu) { 
			
				if($leftMenu['menus']) {
					
					if(!empty($leftMenu['libelle'])) { 
					
						$moduleLiClass = '';
						if(count($leftMenu['menus']) == 1) {
							
							$module 			= current($leftMenu['menus']);
							$moduleController 	= $module['controller_name'];
							$moduleAction 		= !empty($module['action_name']) ? $module['action_name'] : 'index';
							$moduleActions 		= get_menu_actions($moduleController, $moduleAction, $module['others_actions'], $module['prohibited_actions']);
							$moduleLink 		= Router::url('backoffice/'.$moduleController.'/'.$moduleAction);
							if(in_array($currentUrl, $moduleActions)) { $moduleLiClass = ' active'; }
														
						} else { $moduleLink = '#'; }						
						?>
						<li class="treeview<?php echo $moduleLiClass; ?>">
							<a href="<?php echo $moduleLink; ?>">
								<?php if($leftMenu['icon']) { ?><i class="<?php echo $leftMenu['icon']; ?>"></i> <?php } ?>
								<span><?php echo strtoupper($leftMenu['libelle']); ?></span>
								<?php if(count($leftMenu['menus']) > 1) { ?><i class="fa fa-angle-left pull-right"></i><?php } ?>
							</a>
							<?php if(count($leftMenu['menus']) > 1) { ?><ul class="treeview-menu"><?php } ?>
						<?php  
					}				
					
					if(count($leftMenu['menus']) > 1) {
						
						foreach($leftMenu['menus'] as $subMenusKey => $subMenus) {
						
							if(!is_int($subMenusKey)) {						
								
								?>
								<li class="treeview">
									<?php if(!empty($subMenusKey)) { ?><a href="#"><span><?php echo $subMenusKey; ?></span><i class="fa fa-angle-left pull-right"></i></a><?php } ?>
									<ul class="treeview-menu">
										<?php 
										foreach($subMenus as $subMenu) {
											
											$moduleController 	= $subMenu['controller_name'];
											$moduleAction 		= !empty($subMenu['action_name']) ? $subMenu['action_name'] : 'index';
											$moduleActions 		= get_menu_actions($moduleController, $moduleAction, $subMenu['others_actions'], $subMenu['prohibited_actions']);
											$moduleLiClass 		= '';
											if(in_array($currentUrl, $moduleActions)) { $moduleLiClass = ' class="active"'; }
											?><li<?php echo $moduleLiClass; ?>><a href="<?php echo Router::url('backoffice/'.$moduleController.'/'.$moduleAction); ?>"><i class="fa fa-angle-double-right"></i> <?php echo $subMenu['name']; ?></a></li><?php 
										}
										?>
									</ul>
								</li>
								<?php						
							} else {
								
								$moduleController 	= $subMenus['controller_name'];				
								$moduleAction 		= !empty($subMenus['action_name']) ? $subMenus['action_name'] : 'index';
								$moduleActions 		= get_menu_actions($moduleController, $moduleAction, $subMenus['others_actions'], $subMenus['prohibited_actions']);
								$moduleLiClass 		= '';
								if(in_array($currentUrl, $moduleActions)) { $moduleLiClass = ' class="active"'; }								
								?><li<?php echo $moduleLiClass; ?>><a href="<?php echo Router::url('backoffice/'.$moduleController.'/'.$moduleAction); ?>"><i class="fa fa-angle-right"></i> <span><?php echo $subMenus['name']; ?></span></a></li><?php	
							}
						}
					}
					
					if(!empty($leftMenu['libelle'])) { 
						
						if(count($leftMenu['menus']) > 1) { ?></ul><?php }
						?></li><?php 
					}
				}
			} 
			?>
		</ul>
	</section>
</aside>
<?php 
function get_menu_actions($moduleController, $moduleAction, $submenuOthersActions, $submenuProhibitedActions) {
											
	//Liste des actions possibles
	$moduleActions = array(
		$moduleController.'/'.$moduleAction,
		$moduleController.'/add',
		$moduleController.'/edit',
	);
	
	//Liste des actions complémentaires
	if(!empty($submenuOthersActions)) { 
		
		$submenuOthersActions = explode(',', $submenuOthersActions);
		$moduleActions = am($moduleActions, $submenuOthersActions);
	}
	
	//Liste des actions interdites
	if(!empty($submenuProhibitedActions)) { 
		
		$submenuProhibitedActions = explode(',', $submenuProhibitedActions);
		$moduleActions = array_diff($moduleActions, $submenuProhibitedActions);
	}
	
	return $moduleActions;
}
?>