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
				        <li><a href="#details_content" data-toggle="tab"><i class="fa fa-indent"></i> <?php echo _("Contenu"); ?></a></li>
				        <?php /* ?><li><a href="#brut_content" data-toggle="tab"><i class="fa fa-align-justify"></i> <?php echo _("Contenu brut"); ?></a></li><?php */ ?>				        
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
				    	<div class="tab-pane" id="diffusion">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-copy"></i> <?php echo _("Diffusion"); ?></h4>                  
                			</div>               
                			<div class="callout callout-info">
			                	<p><?php echo _('Pour diffuser ce focus dans un ou plusieurs sites cochez la ou les cases correspondantes')?>.</p>
							</div> 		
							<?php 
							foreach($websitesSession['liste'] as $websiteId => $websiteName) {
								
								$websiteCategories = $this->request('Categories', 'request_tree_list', array('websiteId' => $websiteId));
								?><h5 class="form-title"><?php echo _("Site")." ".$websiteName; ?></h5><?php
								echo $helpers['Form']->input('CategoriesFocusWebsite.'.$websiteId.'.display', _('Diffuser dans le site').' '.$websiteName, array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce focus dans le site".' '.$websiteName)));							
								echo $helpers['Form']->input('CategoriesFocusWebsite.'.$websiteId.'.display_home_page', _("Afficher ce focus sur la page d'accueil du site").' '.$websiteName, array('type' => 'checkbox', 'tooltip' => _("En cochant cette case vous afficherez ce focus sur la page d'accueil du site".' '.$websiteName)));
								
								if($websiteCategories) {
									?>
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th colspan="2"><?php echo _("Afficher ce focus dans une (ou plusieurs) page(s) du site"); ?> <?php echo $websiteName; ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($websiteCategories as $k => $v) { ?>												
												<tr>
													<td class="txtcenter xxs"><?php echo $helpers['Form']->input('CategoriesFocusWebsite.'.$websiteId.'.category_id.'.$k, '', array('type' => 'checkbox', 'onlyInput' => true)); ?></td>
													<td><?php echo $v; ?></td>
												</tr>	
											<?php } ?>
										</tbody>
									</table>								
									<?php 
								}
							}
							?>	
                		</div>
				        <div class="tab-pane" id="details_content">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-indent"></i> <?php echo _("Contenu"); ?></h4>                  
                			</div>	
				       		<?php 
							echo $helpers['Form']->input('details_title', _('Titre'), array('tooltip' => _("Indiquez le titre qui sera affiché")));
							echo $helpers['Form']->upload_files('details_img', array('label' => _("Image ou icône")));
							echo $helpers['Form']->input('details_content', _('Contenu'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le contenu de votre focus")));
							?>
				        </div>
				        <?php /* ?>
				        <div class="tab-pane" id="brut_content">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-align-justify"></i> <?php echo _("Contenu brut"); ?></h4>                  
                			</div>	
				       		<?php echo $helpers['Form']->input('content', _('Contenu brut'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le contenu brut de votre focus, n'hésitez pas à utiliser le modèle de focus pour vous aider"))); ?>
				        </div>
				        <?php */ ?>
				    </div>
				</div>
				<div class="box-footer">
                	<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>    
				</div>
			</div>
		</div>
	</div>
</div>