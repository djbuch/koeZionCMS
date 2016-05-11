<?php if(Session::getRole() == 1) { ?>
	<aside class="control-sidebar control-sidebar-dark">
		<div class="tab-content">
			<h3 class="control-sidebar-heading"><?php echo ("Configurations"); ?></h3>
            <ul class="control-sidebar-menu">
              <li><a href="<?php echo Router::url('backoffice/configs/core_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Coeur du système"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/database_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Base de données"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/mailer_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Envoi de mails"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/router_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Gestion des routes"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/security_code_liste'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Code sécurité tâches CRON"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/delete_cache'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Supprimer le cache"); ?></a></li>
              <li><a href="<?php echo Router::url('backoffice/configs/phpinfo'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("PHPINFO"); ?></a></li>              
            </ul>

            <h3 class="control-sidebar-heading"><?php echo ("Gestion des modules"); ?></h3>
            <ul class="control-sidebar-menu">
              	<li><a href="<?php echo Router::url('backoffice/modules/index'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Modules"); ?></a></li>
            	<li><a href="<?php echo Router::url('backoffice/modules_types/index'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Types de modules"); ?></a></li>
			</ul> 

            <h3 class="control-sidebar-heading"><?php echo ("Gestion des templates"); ?></h3>
            <ul class="control-sidebar-menu">
              	<li><a href="<?php echo Router::url('backoffice/templates/index'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Templates"); ?></a></li>
			</ul>
			
			<h3 class="control-sidebar-heading"><?php echo ("Editeur de texte"); ?></h3>
            <ul class="control-sidebar-menu">
              	<li><a href="<?php echo Router::url('backoffice/ckeditor_templates/index'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Gestion des modèles de page"); ?></a></li>
              	<li><a href="<?php echo Router::url('backoffice/ckeditor_styles/index'); ?>"><i class="fa fa-caret-right"></i> <?php echo _("Gestion des styles"); ?></a></li>
			</ul>         
    	</div>
    	<hr />
		<div class="tab-content">
			<p style="font-size:11px;text-align:center;"><strong>Copyright &copy; 2010 - <?php echo date('Y'); ?> <a href="http://www.koezion-cms.com">koéZion CMS</a></strong></p>
			<p style="font-size:11px;text-align:center;margin-bottom:0;">Template by <a href="https://almsaeedstudio.com/AdminLTE">Almsaeed Studio</a></p>
		</div>
		<hr />
	</aside>
	<div class="control-sidebar-bg"></div>
<?php } ?>