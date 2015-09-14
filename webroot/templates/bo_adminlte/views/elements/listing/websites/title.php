<?php 
$currentAction = $this->controller->request->action;
if($currentAction == 'index') { $pageTitle = _("Sites Internet"); } 
else if($currentAction == 'add') { $pageTitle = _("Ajouter un site Internet"); } 
else if($currentAction == 'edit') { $pageTitle = _("Editer un site Internet"); }
?>
<section class="content-header">
	<h1>
        <?php echo $pageTitle; ?>
    	<?php if($currentAction == 'index') { ?><small> - <?php echo ($pager['totalElements'] > 0) ? $pager['totalElements'] : _('Aucun'); ?> <?php echo _('éléments'); ?></small><?php } ?>
	</h1>	
	<div class="page_menu" >
  		<?php 
  		if($currentAction == 'index') { echo $helpers['Html']->backoffice_button_title($params['controllerFileName'], 'add', '<i class="fa fa-save"></i>&nbsp;&nbsp;'.strtoupper(_("Ajouter")), null, 'html', 'btn-success btn-xs'); }
  		else { echo $helpers['Html']->backoffice_button_title($params['controllerFileName'], 'index', '<i class="fa fa-list-ul"></i>&nbsp;&nbsp;'.strtoupper(_("Retour listing")), null, 'html', 'btn-default btn-xs'); } 
  		?>
	</div>
</section>