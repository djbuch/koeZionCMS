<div class="section">
	<div class="box">
		<div class="title">
			<h2><?php echo _("Editer"); ?> un formulaire</h2>
			<a class="btn black" href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/index'); ?>" style="float: right; margin-top: 3px;"><span><?php echo _("Listing"); ?></span></a>
		</div>
		<div class="content nopadding">
			<?php 
			$formOptions = array('id' => $params['modelName'].'Edit', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$id), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions);
			echo $helpers['Form']->input('id', '', array('type' => 'hidden'));
			$this->element(PLUGINS.DS.'formulaires/views/elements/backoffice/formulaire', null, false);
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>