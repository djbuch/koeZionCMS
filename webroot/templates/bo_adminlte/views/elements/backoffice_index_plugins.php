<?php $this->element('backoffice/title', null, true); ?>		
<section class="content">
	<div class="row">
		<?php $this->element('backoffice/search', null, true); ?>
    	<div class="col-md-12">
    		<?php $this->element('flash_messages'); ?>
    		<div class="box box-primary">
    			<div class="box-body">    
    				<div class="col-md-12">              
						<?php					
						if($pager['totalElements'] > 0) { 
							
							$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName'].'/massive_delete'), 'method' => 'post', 'id' => 'formDelete');
							echo $helpers['Form']->create($formOptions);
							?>
							<table class="table table-bordered table-hover">					
								<?php $this->element('backoffice/thead', null, true); ?>
								<?php $this->element('backoffice/tbody', null, true); ?>
								<?php $this->element('backoffice/tfoot', null, true); ?>					
							</table>
							<?php 
							echo $helpers['Form']->end(); 
							$this->element('pagination');
						} else { 
							
							?><p><?php echo _("Aucun élément"); ?></p><?php 
						}
						?>                
					</div>
				</div>    		
			</div>
		</div>
	</div>
</section>