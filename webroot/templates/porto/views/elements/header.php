<header id="header" class="colored flat-menu darken-top-border">
	<div class="header-top">
		<div class="container">
			<?php 
			$headerTopText = $this->request('PortoConfigs', 'get_config', array('header_top_text'));
			if($headerTopText) { echo $headerTopText; }
			
			$this->element('search'); 
			?>			
		</div>
	</div>
	<div class="container">
		<h1 class="logo"><?php echo $websiteParams['tpl_logo']; ?></h1>
		<button class="btn btn-responsive-nav btn-inverse" data-toggle="collapse" data-target=".nav-main-collapse">
			<i class="fa fa-bars"></i>
		</button>
	</div>
	<div class="navbar-collapse nav-main-collapse collapse">
		<div class="container">
			<nav class="nav-main mega-menu">
				<?php 
				if(!isset($breadcrumbs)) $breadcrumbs = array();
				$helpers['Nav']->generateMenu($menuGeneral, $breadcrumbs, null, 1); 
				?>
			</nav>
		</div>
	</div>
</header>