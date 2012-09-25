<div class="section">
	<div class="box">
		<div class="title">
			<h2><?php echo _("Ajouter"); ?> un catalogue</h2>
			<a class="btn black" href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/index'); ?>" style="float: right; margin-top: 3px;"><span><?php echo _("Listing"); ?></span></a>
		</div>
		<div class="content nopadding">
			<?php 
			$formOptions = array('id' => $params['modelName'].'Add', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/add'), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions);
			$this->element(PLUGINS.DS.'catalogues/views/elements/backoffice/formulaire', null, false);
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>