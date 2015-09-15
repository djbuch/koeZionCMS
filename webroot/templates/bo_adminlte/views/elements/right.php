<?php if(Session::getRole() == 1) { ?>
	<aside class="control-sidebar control-sidebar-dark">
		<div class="tab-content">
			<h3 class="control-sidebar-heading"><?php echo ("Configurations"); ?></h3>
            <ul class="control-sidebar-menu">
              <li><a href="<?php echo Router::url('backoffice/configs/core_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Coeur du système"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/database_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Base de données"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/mailer_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Envoi de mails"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/router_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Gestion des routes"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/posts_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Articles"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/security_code_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Code sécurité tâches CRON"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/delete_cache'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Supprimer le cache"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/phpinfo'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("PHPINFO"); ?></a></li>              
            </ul>

            <h3 class="control-sidebar-heading"><?php echo ("Gestion des modules"); ?></h3>
            <ul class="control-sidebar-menu">
              	<li><a href="<?php echo Router::url('backoffice/modules/index'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Modules"); ?></a></li>
            	<li><a href="<?php echo Router::url('backoffice/modules_types/index'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Types de modules"); ?></a></li>
			</ul>          
    	</div>
	</aside>
	<div class="control-sidebar-bg"></div>
<?php } ?>