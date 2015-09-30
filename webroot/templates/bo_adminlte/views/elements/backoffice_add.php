<?php $this->element('listing/'.$this->vars['params']['controllerFileName'].'/title'); ?>		
<section class="content">
	<div class="row">
    	<div class="add_edit_page col-md-12">    		     	
	    	<?php 
	    	$this->element('flash_messages');	    	
	    	
			$formOptions = array('id' => $params['modelName'].'Add', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/add'), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions); 
			$this->element('formulaires/'.$params['controllerFileName']);
			echo $helpers['Form']->end(); 
			?>	
    	</div>
    </div>
</section>