<?php $this->element('backoffice/title', null, true); ?>		
<section class="content">
	<div class="row">
    	<div class="add_edit_page col-md-12">    		     	
	    	<?php 
	    	$this->element('flash_messages');	    	
	    	
			$formOptions = array('id' => $params['modelName'].'Edit', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$id), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions); 
			echo $helpers['Form']->input('id', '', array('type' => 'hidden'));
			$this->element('backoffice/formulaire', null, true);
			echo $helpers['Form']->end(); 
			?>	
    	</div>
    </div>
</section>