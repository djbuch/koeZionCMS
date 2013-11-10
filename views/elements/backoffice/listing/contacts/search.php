<div style="border:1px solid #E5E5E5;margin-bottom:30px;">
	<div class="title">
		<h2>Moteur de recherche</h2>
		<?php 
		$defaultCss = '';
		if(!isset($this->controller->request->data['Search'])) { $defaultCss = "default"; } 
		?>
		<div class="hide <?php echo $defaultCss; ?>" style="opacity: 1;"></div>
	</div>	
	<div class="content nopadding">	
		<?php
		$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName']), 'method' => 'get', 'id' => 'formSearch');
		echo $helpers['Form']->create($formOptions);
		echo $helpers['Form']->input('Search.id', 'Identifiant', array('tooltip' => ""));
		echo $helpers['Form']->input('Search.name', 'Libellé', array('tooltip' => ""));
		echo $helpers['Form']->input('Search.email', 'Email', array('tooltip' => ""));
		echo $helpers['Form']->end(true);
		?>
	</div>
</div>