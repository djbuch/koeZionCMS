<?php 
$cssBox 	= ' collapsed-box';
$iconBox 	= 'fa-plus';
if(isset($this->controller->request->data['Search'])) { 

	$cssBox 	= '';
	$iconBox 	= 'fa-minus';
}
?>
<div class="col-md-12">
	<div class="box <?php echo $cssBox; ?>">
		<?php
		$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName']), 'method' => 'get', 'id' => 'formSearch');
		echo $helpers['Form']->create($formOptions);
		?>
			<div class="box-header with-border">
		    	<h3 class="box-title"><?php echo _("Moteur de recherche"); ?></h3>
		    	<div class="box-tools pull-right">
					<a class="btn btn-box-tool" data-widget="collapse"><i class="fa <?php echo $iconBox; ?>"></i></a>
				</div>
			</div>
			<div class="box-body">
				<div class="col-md-12">
					<?php 
					if(!isset($searchFields)) {
						
						$searchFields = array(
							'id' => _('Identifiant'),
							'name' => _('Libellé'),
						);	
					}
					
					foreach($searchFields as $searchFieldName => $searchFieldLabel) { 
						
						echo $helpers['Form']->input('Search.'.$searchFieldName, $searchFieldLabel); 
					}
					?>               
				</div>
			</div>
		    <div class="box-footer">
		    	<div class="col-md-12"><?php echo $helpers['Form']->button(_('Rechercher')); ?></div>
		    </div>
	    <?php echo $helpers['Form']->end(); ?>
	</div>
</div>