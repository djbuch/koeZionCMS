<?php 
$currentController 	= $this->controller->request->controller;
$currentAction 		= $this->controller->request->action;
?>
<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<li class="header"><?php echo strtoupper(_("Menu principal")); ?></li>
			<?php 
			foreach($leftMenus as $leftMenu) { 
			
				if(!empty($leftMenu['libelle'])) { 
				
					?>
					<li class="treeview">
						<a href="#"><?php if($leftMenu['icon']) { ?><i class="<?php echo $leftMenu['icon']; ?>"></i> <?php } ?><span><?php echo strtoupper($leftMenu['libelle']); ?></span><i class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
					<?php  
				}				
				
				foreach($leftMenu['menus'] as $subMenusKey => $subMenus) {
				
					if(!is_int($subMenusKey)) {						
						
						?>
						<li class="treeview">
							<?php if(!empty($subMenusKey)) { ?><a href="#"><span><?php echo $subMenusKey; ?></span><i class="fa fa-angle-left pull-right"></i></a><?php } ?>
							<ul class="treeview-menu">
								<?php 
								foreach($subMenus as $subMenu) {
									
									$controller = $subMenu['controller_name'];
									$action 	= !empty($subMenu['action_name']) ? $subMenu['action_name'] : 'index';
									$class 		= '';
									if($controller == $currentController && $action == $currentAction) { $class = ' class="active"'; }
									?><li<?php echo $class; ?>><a href="<?php echo Router::url('backoffice/'.$subMenu['controller_name'].'/'.$action); ?>"><i class="fa fa-circle-o"></i> <?php echo $subMenu['name']; ?></a></li><?php 
								}
								?>
							</ul>
						</li>
						<?php						
					} else {
						
						$controller = $subMenus['controller_name'];				
						$action 	= !empty($subMenus['action_name']) ? $subMenus['action_name'] : 'index';
						$class 		= '';
						if($controller == $currentController && $action == $currentAction) { $class = ' class="active"'; }
						
						?><li<?php echo $class; ?>><a href="<?php echo Router::url('backoffice/'.$controller.'/'.$action); ?>"><i class="fa fa-circle-o"></i> <span><?php echo $subMenus['name']; ?></span></a></li><?php	
					}
				}
				
				if(!empty($leftMenu['libelle'])) { ?></ul></li><?php }
			} 
			?>
		</ul>
	</section>
</aside>