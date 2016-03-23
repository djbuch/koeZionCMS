<div class="row">
	<div class="nav-tabs-custom">
		<div class="col-md-2 left">
			<div class="box box-primary">
	    		<div class="box-body">
					<ul class="nav nav-tabs nav-stacked col-md-12">
				    	<li class="active"><a href="#tab1" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("TITRE UN"); ?></a></li>
				        <li><a href="#tab2" data-toggle="tab"><i class="fa fa-navicon"></i> <?php echo _("TITRE DEUX"); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="col-md-10 right">
			<div class="box box-primary">
	    		<div class="box-body">		
				    <div class="tab-content col-md-12">
				    	<div class="tab-pane active" id="tab1">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-file-text-o"></i> <?php echo _("TITRE UN"); ?></h4>                  
                			</div>			    	
				    		<?php 
							echo $helpers['Form']->input('input_form1', _('INPUT 1'));
							echo $helpers['Form']->input('input_form2', _('INPUT 2'));
							?>
				    	</div>
				        <div class="tab-pane" id="tab2">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-navicon"></i> <?php echo _("TITRE DEUX"); ?></h4>                  
                			</div>	
				        	<?php
							echo $helpers['Form']->input('input_form3', _('INPUT 3'));
							echo $helpers['Form']->input('input_form4', _('INPUT 4'));			
							?>
				        </div>				        
					</div>
				</div>
				<div class="box-footer">
                	<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>    
				</div>
			</div>
		</div>
	</div>
</div>