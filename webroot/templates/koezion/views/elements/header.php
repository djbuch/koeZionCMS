<header>
	<div class="container">
    	<div class="row"> 
			<div class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only"><?php echo _("Navigation"); ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="logo"><?php echo $websiteParams['tpl_logo']; ?></div>
				</div>
				<div class="navbar-collapse collapse">
					<?php 
					if(!isset($breadcrumbs)) $breadcrumbs = array();
					$helpers['Nav']->generate_menu($menuGeneral, $breadcrumbs);
									
					if($this->request('BootstrapKoezionConfigs', 'get_config', array('SEARCH_ENGINE', 'activate'))) {
						
						///////////////////////////////
						//    MOTEUR DE RECHERCHE    //
						echo $helpers['Form']->create(array('id' => 'Search', 'action' => Router::url('searchs/rechercher'), 'method' => 'post', 'class' => 'form navbar-form navbar-right', 'role' => "search"));
						echo $helpers['Form']->input('q', _('Rechercher'), array('label' => false, 'placeholder' => _('Rechercher')));
						?><button type="submit" class="btn btn-default"><?php echo _("OK"); ?></button><?php 
						echo $helpers['Form']->end(); 
					}	
					?>
				</div>
			</div>    	
    	</div>
	</div>
</header>