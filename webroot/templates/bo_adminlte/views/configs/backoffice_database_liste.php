<section class="content-header">
	<h1><?php echo _("Configuration de la base de données"); ?></h1>
</section>	
<section class="content">
	<div class="row">
    	<div class="add_edit_page col-md-12">    		     	
	    	<?php 
	    	$this->element('flash_messages');	    	
			echo $helpers['Form']->create(array('id' => 'ConfigDatabase', 'action' => Router::url('backoffice/configs/database_liste'), 'method' => 'post'));
			?>
			<div class="row">
				<div class="nav-tabs-custom">
					<div class="col-md-2 left">
						<div class="box box-primary">
				    		<div class="box-body">
								<ul class="nav nav-tabs nav-stacked col-md-12">
							    	<li class="active"><a href="#local" data-toggle="tab"><i class="fa fa-cloud-download"></i> <?php echo _("Locale"); ?></a></li>
							        <li><a href="#production" data-toggle="tab"><i class="fa fa-cloud-upload"></i> <?php echo _("Production"); ?></a></li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="col-md-10 right">
						<div class="box box-primary">
				    		<div class="box-body">		
							    <div class="tab-content col-md-12">
							    	<div class="tab-pane active" id="local">	
							    		<div class="box-header bg-light-blue">
											<h4><i class="fa fa-cloud-download"></i> <?php echo _("Base de données locale"); ?></h4>                  
			                			</div>	
										<?php 
										echo $helpers['Form']->input('localhost.host', _('Adresse du serveur'), array('tooltip' => _("Indiquez ici l'adresse du serveur mysql (par exemple localhost)")));		
										echo $helpers['Form']->input('localhost.socket', _('Connexion via socket'), array('tooltip' => _("Indiquez ici le chemin du socket (Utilisé sur certains serveur 1&1 par exemple)")));	 
										echo $helpers['Form']->input('localhost.database', _('Nom de la base de données'), array('tooltip' => _("Indiquez ici le nom de la base de données")));			 
										echo $helpers['Form']->input('localhost.login', _('Identifiant'), array('tooltip' => _("Indiquez ici l'identifiant de connexion à la base de données")));			 
										echo $helpers['Form']->input('localhost.password', _('Mot de passe'), array('tooltip' => _("indiquez ici le mot de passe de connexion à la base de données"), 'type' => 'password'));			 
										echo $helpers['Form']->input('localhost.prefix', _('Préfix des tables'), array('tooltip' => _("Si vos tables sont préfixées indiquez ici le préfixe (par exemple blog_)")));	
										echo $helpers['Form']->input('localhost.port', _('Port'), array('tooltip' => _("Indiquez ici le port de connexion")));	
										echo $helpers['Form']->input('localhost.source', _('Source'), array('tooltip' => _("Indiquez ici la source par exemple mysql"), "value" => "mysql"));	
										?>
							    	</div>
							    	<div class="tab-pane" id="production">	
							    		<div class="box-header bg-light-blue">
											<h4><i class="fa fa-cloud-upload"></i> <?php echo _("Base de données de production"); ?></h4>                  
			                			</div>	
										<?php 
										echo $helpers['Form']->input('online.host', _('Adresse du serveur'), array('tooltip' => _("Indiquez ici l'adresse du serveur mysql (par exemple localhost)")));			 
										echo $helpers['Form']->input('online.socket', _('Connexion via socket'), array('tooltip' => _("Indiquez ici le chemin du socket (Utilisé sur certains serveur 1&1 par exemple)")));			 
										echo $helpers['Form']->input('online.database', _('Nom de la base de données'), array('tooltip' => _("Indiquez ici le nom de la base de données")));			 
										echo $helpers['Form']->input('online.login', _('Identifiant'), array('tooltip' => _("Indiquez ici l'identifiant de connexion à la base de données")));			 
										echo $helpers['Form']->input('online.password', _('Mot de passe'), array('tooltip' => _("indiquez ici le mot de passe de connexion à la base de données"), 'type' => 'password'));			 
										echo $helpers['Form']->input('online.prefix', _('Préfix des tables'), array('tooltip' => _("Si vos tables sont préfixées indiquez ici le préfixe (par exemple blog_)")));	
										echo $helpers['Form']->input('online.port', _('Port'), array('tooltip' => _("Indiquez ici le port de connexion")));		
										echo $helpers['Form']->input('online.source', _('Source'), array('tooltip' => _("Indiquez ici la source par exemple mysql"), "value" => "mysql")); 
										?>
							    	</div>
							    </div>
							</div>
							<div class="box-footer">
			                	<?php echo $helpers['Form']->button(); ?>    
							</div>
						</div>
					</div>
				</div>
			</div>			
			<?php 
			echo $helpers['Form']->end(); 
			?>	
    	</div>
    </div>
</section>