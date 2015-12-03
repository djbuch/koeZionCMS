<?php 
$websitesSession 	= Session::read('Backoffice.Websites'); //Récupération de la variable de session
$currentWebsite 	= $websitesSession['current']; //Récupération du site courant
?>
<div class="row">
	<div class="nav-tabs-custom">
		<div class="col-md-2 left">
			<div class="box box-primary">
	    		<div class="box-body">
					<ul class="nav nav-tabs nav-stacked col-md-12">
				    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
						<li><a href="#publication" data-toggle="tab"><i class="fa fa-copy"></i> <?php echo _("Publication"); ?></a></li>
				        <li><a href="#details_content" data-toggle="tab"><i class="fa fa-indent"></i> <?php echo _("Contenu détaillé"); ?></a></li>
				        <li><a href="#brut_content" data-toggle="tab"><i class="fa fa-align-justify"></i> <?php echo _("Contenu brut"); ?></a></li>				        
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
							echo $helpers['Form']->input('name', _('Libellé'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du focus")));
							echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce focus")));
							?>
				    	</div>   	
				    	<div class="tab-pane" id="publication">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-copy"></i> <?php echo _("Publication"); ?></h4>                  
                			</div>               
                			<div class="callout callout-info">
			                	<p><?php echo _('Pour publier ce focus dans un ou plusieurs site cochez la ou les cases correspondantes')?>.</p>
							</div> 		
							<?php 
							foreach($websitesSession['liste'] as $websiteId => $websiteName) {
								
								echo $helpers['Form']->input('FocusWebsite.'.$websiteId.'.display', _('Diffuser dans le site').' '.$websiteName, array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce focus dans le site".' '.$websiteName)));
							}
							?>	
                		</div>
				        <div class="tab-pane" id="details_content">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-indent"></i> <?php echo _("Contenu détaillé"); ?></h4>                  
                			</div>	
				       		<?php 
							echo $helpers['Form']->input('details_title', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre qui sera affiché")));
							echo $helpers['Form']->upload_files('details_img', array('label' => _("Image ou icône")));
							echo $helpers['Form']->input('details_content', _('Contenu'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le contenu de votre focus")));
							?>
				        </div>
				        <div class="tab-pane" id="brut_content">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-align-justify"></i> <?php echo _("Contenu brut"); ?></h4>                  
                			</div>	
				       		<?php echo $helpers['Form']->input('content', _('Contenu brut'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le contenu brut de votre focus, n'hésitez pas à utiliser le modèle de focus pour vous aider"))); ?>
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