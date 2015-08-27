<div class="section">
	<div class="box">
		
		<?php $this->element('listing/'.$this->vars['params']['controllerFileName'].'/title'); ?>		
		<div class="content">
			<?php 			
			if(file_exists(ELEMENTS.DS.'backoffice'.DS.'listing'.DS.$this->vars['params']['controllerFileName'].DS.'search.php')) {
				
				$this->element('listing/'.$this->vars['params']['controllerFileName'].'/search');
			}
			
			if($pager['totalElements'] > 0) { 
				
				$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName'].'/massive_delete'), 'method' => 'post', 'id' => 'formDelete');
				echo $helpers['Form']->create($formOptions);
				?>
				<table cellspacing="0" cellpadding="0" border="0">					
					<?php $this->element('listing/'.$this->vars['params']['controllerFileName'].'/thead'); ?>
					<?php $this->element('listing/'.$this->vars['params']['controllerFileName'].'/tbody'); ?>
					<?php $this->element('listing/'.$this->vars['params']['controllerFileName'].'/tfoot'); ?>					
				</table>
				<?php 
				echo $helpers['Form']->end(false); 
				$this->element('pagination');
			} else { 
				
				?><p><?php echo _("Aucun élément"); ?></p><?php 
			}
			?>
		</div>
	</div>
</div>