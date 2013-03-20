<div class="section">
	<div class="box">
		<div class="title">
			<h2><?php echo _("Ajout massif de catégories"); ?></h2>
			<?php echo $helpers['Html']->backoffice_button_title($params['controllerFileName'], 'index', "Listing"); ?>
		</div>
		<div class="content nopadding">
			<?php 
			$formOptions = array('id' => $params['modelName'].'MassivAdd', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/massive_add'), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions); 
			$this->element('backoffice/formulaires/'.$params['controllerFileName'].'_massive');
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>