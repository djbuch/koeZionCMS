<div class="row">
	<div class="nav-tabs-custom">
		<div class="col-md-2 left">
			<div class="box box-primary">
	    		<div class="box-body">
					<ul class="nav nav-tabs nav-stacked col-md-12">
				    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
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
							echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du widget colonne de droite.")));
							echo $helpers['Form']->input('content',	_('Contenu'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le contenu de votre widget, Pour les images ne pas dépasser 249px de largeur.")));
							echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce widget.")));
							?>
				    	</div>
				         <div class="tab-pane" id="options">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-plug"></i> <?php echo _("Options"); ?></h4>                  
                			</div>
				        	<?php
							echo $helpers['Form']->input('display_home_page', _("Afficher sur la page d'accueil"), 	array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour afficher ce widget sur la page d'accueil.")));
							echo $helpers['Form']->input('display_all_pages', _("Afficher sur toutes les pages (sauf accueil)"), 	array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour afficher ce widget sur toutes les pages.")));
							echo $helpers['Form']->input('display_all_pages_top', _("Positionner en haut de la colonne"), 	array('type' => 'checkbox'));
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