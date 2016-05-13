<section class="content-header">
	<h1><?php echo _("Gestion des configurations du template Bootstrap koéZion"); ?></h1>
</section>	
<section class="content">
	<div class="row">
    	<div class="add_edit_page col-md-12">   
			<?php 
	    	$this->element('flash_messages');	    	
			echo $helpers['Form']->create(array('id' => $params['modelName'].'Configs', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/manage')));
			?>	
			<div class="box box-primary">
				<div class="box-body">
					<div class="col-md-12">
				
						<?php 
						echo $helpers['Form']->input('SEARCH_ENGINE.activate', _('Activer le moteur de recherche'), array('type' => 'checkbox', 'tooltip' => _("Cocher cette case pour activer le moteur de recherche du header")));
						echo $helpers['Form']->input('NEWSLETTER.activate', _('Activer la zone newsletter'), array('type' => 'checkbox', 'tooltip' => _("Cocher cette case pour activer la zone d'inscription à la newsletter")));
						?>			
					</div>
				</div>
				<div class="box-footer">
                	<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>    
				</div>
			</div>
			<?php 
			echo $helpers['Form']->end(); 
			?>	
    	</div>
    </div>
</section>