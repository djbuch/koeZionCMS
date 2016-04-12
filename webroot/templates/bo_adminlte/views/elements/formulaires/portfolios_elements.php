<div class="row">
	<div class="nav-tabs-custom">
		<div class="col-md-2 left">
			<div class="box box-primary">
	    		<div class="box-body">
					<ul class="nav nav-tabs nav-stacked col-md-12">
				    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
				    	<li><a href="#textes" data-toggle="tab"><i class="fa fa-file-word-o"></i> <?php echo _("Illustrations et descriptifs"); ?></a></li>
				    	<li><a href="#options" data-toggle="tab"><i class="fa fa-plug"></i> <?php echo _("Options"); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="col-md-10 right">
			<div class="box box-primary">
	    		<div class="box-body">		
				    <div class="tab-content col-md-12">
				    	<div class="tab-pane active" id="general">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></h4>                  
                			</div>  
							<?php 
							echo $helpers['Form']->input('portfolio_id', _('Portfolio'), array('compulsory' => true, 'type' => 'select', 'datas' => $portfoliosList, 'tooltip' => _("Indiquez le portfolio dans laquelle sera affiché cet élément"), 'firstElementList' => _("Sélectionnez un portfolio")));
							echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre de l'élément")));
							$illustrationParams = array(
								'label' => _("Illustration"),
								'tooltip' => _("Sélectionnez une image"),
								'button_value' => _("Sélectionner l'image")		
							);
							echo $helpers['Form']->upload_files('illustration', $illustrationParams);							
							echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser cet élément")));
							?>	             			
                		</div>
				    	<div class="tab-pane" id="textes">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-file-word-o"></i> <?php echo _("Descriptifs"); ?></h4>                  
                			</div>                	
							<?php 
							echo $helpers['Form']->input('description_line_1', _('Description ligne 1'), array('tooltip' => _("Ligne de description")));
							echo $helpers['Form']->input('description_line_2', _('Description ligne 2'), array('tooltip' => _("Ligne de description")));
							echo $helpers['Form']->input('description_line_3', _('Description ligne 3'), array('tooltip' => _("Ligne de description")));
							echo $helpers['Form']->input('description_line_4', _('Description ligne 4'), array('tooltip' => _("Ligne de description")));
							?>		
                		</div>
				    	<div class="tab-pane" id="options">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-plug"></i> <?php echo _("Options"); ?></h4>                  
                			</div>           
							<?php
							echo $helpers['Form']->input('link', _('Lien externe'), array('tooltip' => _("Indiquez un lien externe")));
							echo $helpers['Form']->input('link_text', _('Texte lien externe'), array('tooltip' => _("Indiquez un texte pour le lien externe")));
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