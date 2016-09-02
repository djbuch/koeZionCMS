<header class="main-header">
	<nav class="navbar navbar-static-top" role="navigation">
    	<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        	<span class="sr-only">Toggle navigation</span>
		</a>
		<p class="welcome"><?php echo _("Bonjour"); ?> <?php echo Session::read("Backoffice.User.name"); ?></p>
		<?php 
		//////////////////////////////////////
		//    GESTION DE LA LOCALISATION    //
		if(defined('PLUGIN_LOCALIZATION_ACTIV') && PLUGIN_LOCALIZATION_ACTIV) { ?><p><?php $this->element(PLUGINS.DS.'localization'.DS.'views'.DS.'elements'.DS.'backoffice'.DS.'top_bar'); ?></p><?php } 
		?>
        <div class="navbar-custom-menu">
        	<ul class="nav navbar-nav">
				<?php 
				/////////////////////////////////////////////////////////////////
				//    AFFICHAGE DU NOMBRE DE DEMANDE DE CONTACTS EN ATTENTE    //
				if($nbFormsContacts > 0) { 
				
					?>
	            	<li>
	                	<a href="<?php echo Router::url('backoffice/contacts/index'); ?>">
							<i class="fa fa-envelope-o"></i>
							<span class="label label-warning"><?php echo $nbFormsContacts; ?></span>
						</a>
					</li>
					<?php 
				}
				
				//////////////////////////////////////////////////////////
				//    AFFICHAGE DU NOMBRE DE COMMENTAIRES EN ATTENTE    //
				if($nbPostsComments > 0) { 
					
					?>
					<li>
						<a href="<?php echo Router::url('backoffice/posts_comments/index'); ?>">
							<i class="fa fa-comment-o"></i>
							<span class="label label-warning"><?php echo $nbPostsComments; ?></span>
						</a>
					</li>			
					<?php 
				} 
				
				?>
				<li>
					<a href="<?php echo Router::url('backoffice/configs/delete_cache'); ?>">
						<i class="fa fa-refresh"></i> <?php echo _("Supprimer le cache"); ?>
					</a>
				</li>
				<li>
					<a href="<?php echo Router::url('backoffice/configs/ckfinder_file_explorer'); ?>" target="_blank">
						<i class="fa fa-picture-o"></i> <?php echo _('Explorateur de fichiers'); ?>
					</a>
				</li>
				<?php 
				
				///////////////////////////////
				//    GESTION MULTI-SITES    //
				$websites 		= Session::read('Backoffice.Websites');
				$websitesListe 	= $websites['liste'];
				$currentWebsite = Session::read('Backoffice.Websites.current');				
				$currentUrl 	= str_replace('http://', '', $websites['details'][$currentWebsite]['url']);
				$currentUrl 	= str_replace('/', '', $currentUrl);
			
				if($_SERVER['HTTP_HOST']) { $wsUrl = Router::url('/', 'html', true)."?hack_ws_host=".$currentUrl; } 
				else { $wsUrl = $websites['details'][$currentWebsite]['url']; }
              	?>
				<li>
					<a href="<?php echo $wsUrl; ?>" target="_blank">
						<i class="fa fa-globe"></i>
						<span><?php echo _("Site courant").' : '.$websitesListe[$currentWebsite]; ?></span>
					</a>
				</li>
				<?php 
				if(count($websitesListe) > 1) { 
					
					?>
					<li class="dropdown tasks-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-exchange"></i>
							<span><?php echo _("Changer de site"); ?></span>
						</a>
	                	<ul class="dropdown-menu">
	                  		<li class="header"><?php echo _("Sélectionnez un site dans la liste"); ?></li>
	                  		<li>                    
	                    		<ul class="menu">
	                    			<?php foreach($websitesListe as $websiteId => $websiteLibelle) { ?>
			                      		<li>
			                        		<a href="<?php echo Router::url('backoffice/websites/change_default/'.$websiteId); ?>"><?php echo _("Utiliser").' '.$websiteLibelle; ?></a>
			                      		</li>
		                      		<?php } ?>
	                    		</ul>
	                  		</li>	                  
	                	</ul>
	              	</li>
              		<?php 
              	}
              	
				///////////////////////////////
				//    MENU CONFIGURATIONS    //
				if(Session::getRole() == 1) { 
					
					?>
	              	<li>
	                	<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
	              	</li>
	              	<?php 
				}  	
				?>
            	<li>
                	<a href="<?php echo Router::url('users/logout'); ?>">
						<i class="fa fa-sign-out"></i>
					</a>
				</li>
			</ul>
		</div>
	</nav>
</header>