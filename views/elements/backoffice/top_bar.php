<div id="top-bar">
	<?php if(isset($this->controller->plugins['Localization'])) { $this->element(PLUGINS.DS.'localization'.DS.'views'.DS.'elements'.DS.'backoffice'.DS.'top_bar'); } ?>
	<ul>		
		<li class="nolink"><?php echo _("Bonjour"); ?> <?php echo Session::read("Backoffice.User.name"); ?></li>		
		<?php 
		if($nbFormsContacts > 0) { 
			
			?><li><a href="<?php echo Router::url('backoffice/contacts/index'); ?>"><?php echo $helpers['Html']->img('/backoffice/icon-message.png', array('alt' => _("Messages Internautes"))); ?> <?php echo _("Messages"); ?><span><?php echo $nbFormsContacts; ?></span></a></li><?php
		}
		
		if($nbPostsComments > 0) { 
			
			?><li><a href="<?php echo Router::url('backoffice/posts_comments/index'); ?>"><?php echo $helpers['Html']->img('/backoffice/icon-message.png', array('alt' => _("Commentaires Internautes"))); ?> <?php echo _("Commentaires articles"); ?><span><?php echo $nbPostsComments; ?></span></a></li><?php 
		}
		
		$websites = Session::read('Backoffice.Websites');
		$websitesListe = $websites['liste'];
		$currentWebsite = Session::read('Backoffice.Websites.current');		
		?>		
		<li class="browse_website">
			<?php 
			$currentUrl = str_replace('http://', '', $websites['details'][$currentWebsite]['url']);
			$currentUrl = str_replace('/', '', $currentUrl);
			
			if($_SERVER['HTTP_HOST']) { $wsUrl = Router::url('/', 'html', true)."?hack_ws_host=".$currentUrl; } 
			else { $wsUrl = $websites['details'][$currentWebsite]['url']; }
			?>			
			<a href="<?php echo $wsUrl; ?>" target="_blank"><?php echo $helpers['Html']->img('/backoffice/website.png', array('alt' => _("Sites Internet"))); ?></a>
		</li>
		<li class="noborder">
			<?php			
			echo _("Site courant").' : '.$websitesListe[$currentWebsite];
			if(count($websitesListe) > 1) {
				?>
				<ul class="websites">
					<?php foreach($websitesListe as $websiteId => $websiteLibelle) { ?>
						<li><a href="<?php echo Router::url('backoffice/websites/change_default/'.$websiteId); ?>"><?php echo _("Utiliser").' '.$websiteLibelle; ?></a></li>			
					<?php } ?>				
				</ul>				
				<?php 
			}
			?>
		</li>
		<?php if(Session::getRole() == 1) { ?>
			<li>
				<img src="<?php echo BASE_URL; ?>/img/backoffice/icon-settings.png" alt="Settings" /> <?php echo _("Configurations"); ?>
				<ul>
					<li><a href="<?php echo Router::url('backoffice/configs/core_liste'); ?>"><?php echo _("Coeur du système"); ?></a></li>			
					<li><a href="<?php echo Router::url('backoffice/configs/database_liste'); ?>"><?php echo _("Base de données"); ?></a></li>			
					<li><a href="<?php echo Router::url('backoffice/configs/mailer_liste'); ?>"><?php echo _("Envoi de mails"); ?></a></li>			
					<li><a href="<?php echo Router::url('backoffice/configs/router_liste'); ?>"><?php echo _("Gestion des routes"); ?></a></li>			
					<li><a href="<?php echo Router::url('backoffice/configs/posts_liste'); ?>"><?php echo _("Articles"); ?></a></li>
					<li><a href="<?php echo Router::url('backoffice/configs/security_code_liste'); ?>"><?php echo _("Code sécurité tâches CRON"); ?></a></li>
					<li><a href="<?php echo Router::url('backoffice/configs/delete_cache'); ?>"><?php echo _("Supprimer le cache"); ?></a></li>
					<li><a href="<?php echo Router::url('backoffice/configs/phpinfo'); ?>"><?php echo _("PHPINFO"); ?></a></li>
					<li class="nolink center"><?php echo _("GESTIONS DES MODULES"); ?></li>
					<li><a href="<?php echo Router::url('backoffice/modules/index'); ?>"><?php echo _("Modules"); ?></a></li>			
					<li><a href="<?php echo Router::url('backoffice/modules_types/index'); ?>"><?php echo _("Types de modules"); ?></a></li>
				</ul>
			</li>
		<?php } ?>			
		
		<li class="logout"><a href="<?php echo Router::url('users/logout'); ?>" title="Logout"><?php echo $helpers['Html']->img('/backoffice/logout.png', array('alt' => _("Déconnexion"))); ?></a></li>
	</ul>
</div>