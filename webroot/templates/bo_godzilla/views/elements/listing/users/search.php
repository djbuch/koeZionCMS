<div style="border:1px solid #E5E5E5;margin-bottom:30px;" class="intern_search">
	<div class="title">
		<h2><?php echo ("Moteur de recherche"); ?></h2>
		<div class="hide" style="opacity: 1;"></div>
	</div>
	<div class="content nopadding" <?php if(isset($this->controller->request->data['Search'])) { ?>style="display:block;"<?php } ?>>	
		<?php
		$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName']), 'method' => 'get', 'id' => 'formSearch');
		echo $helpers['Form']->create($formOptions);
		echo $helpers['Form']->input('Search.id', _('Identifiant'), array('tooltip' => ""));
		echo $helpers['Form']->input('Search.name', _('Libellé'), array('tooltip' => ""));
		echo $helpers['Form']->input('Search.login', _('Identifiant'), array('tooltip' => ""));
		echo $helpers['Form']->input('Search.email', _('Email'), array('tooltip' => ""));
		echo $helpers['Form']->end(true);
		?>
	</div>
</div>