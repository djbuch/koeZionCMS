<?php 
$currentAction = $this->controller->request->action;
if($currentAction == 'index') { $pageTitle = _("Contacts internautes"); } 
if($currentAction == 'newsletter') { $pageTitle = _("Inscription newsletter"); } 
else if($currentAction == 'add') { $pageTitle = _("Ajouter un contact"); } 
else if($currentAction == 'edit') { $pageTitle = _("Editer un contact"); }
?>
<section class="content-header">
	<h1>
        <?php echo $pageTitle; ?>
    	<?php if($currentAction == 'index') { ?><small> - <?php echo ($pager['totalElements'] > 0) ? $pager['totalElements'] : _('Aucun'); ?> <?php echo _('éléments'); ?></small><?php } ?>
	</h1>	
	<div class="page_menu" >
  		<?php 
  		if($currentAction == 'index') { echo $helpers['Html']->backoffice_button_title($params['controllerFileName'], 'add', '<i class="fa fa-save"></i>&nbsp;&nbsp;'.strtoupper(_("Ajouter")), null, 'html', 'btn-success btn-xs'); }
  		if($currentAction == 'newsletter') { echo $helpers['Html']->backoffice_button_title($params['controllerFileName'], 'add', '<i class="fa fa-save"></i>&nbsp;&nbsp;'.strtoupper(_("Ajouter")), null, 'html', 'btn-success btn-xs'); }
  		else { 
  			
  			$requestData = $this->controller->request->data;
  			if($requestData['cpostal'] != '') { $action = 'index'; }
  			else { $action = 'newsletter'; }
  			echo $helpers['Html']->backoffice_button_title($params['controllerFileName'], $action, '<i class="fa fa-list-ul"></i>&nbsp;&nbsp;'.strtoupper(_("Retour listing")), null, 'html', 'btn-default btn-xs'); 
  		} 
  		?>
	</div>
</section>