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
						<li><a href="#diffusion" data-toggle="tab"><i class="fa fa-copy"></i> <?php echo _("Diffusion"); ?></a></li>
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
							echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du slider")));
							echo $helpers['Form']->input('image', _('Image'), array('type' => 'textarea', 'wysiswyg' => true, 'toolbar' => 'image', 'tooltip' => _("Insérez l'image à l'aide du module fournit par l'éditeur de texte, Taille 918/350px, Mode RVB, Résolution 72dpi")));
							echo $helpers['Form']->input('content', _('Contenu'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le descriptif de votre slider, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
							echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce slider")));
							?>             			
                		</div>                	
				    	<div class="tab-pane" id="diffusion">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-copy"></i> <?php echo _("Diffusion"); ?></h4>                  
                			</div>               
                			<div class="callout callout-info">
			                	<p><?php echo _('Pour diffuser ce slider dans un ou plusieurs sites cochez la ou les cases correspondantes')?>.</p>
							</div> 		
							<?php 
							foreach($websitesSession['liste'] as $websiteId => $websiteName) {
								
								echo $helpers['Form']->input('SlidersWebsite.'.$websiteId.'.display', _('Diffuser dans le site').' '.$websiteName, array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce slider dans le site".' '.$websiteName)));
							}
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