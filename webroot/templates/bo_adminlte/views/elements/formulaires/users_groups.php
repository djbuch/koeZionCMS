<div class="row">
	<div class="nav-tabs-custom">
		<div class="col-md-2 left">
			<div class="box box-primary">
	    		<div class="box-body">
					<ul class="nav nav-tabs nav-stacked col-md-12">
				    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
				    	<li><a href="#websites_users" data-toggle="tab"><i class="fa fa-globe"></i> <?php echo _("Sites accessibles"); ?></a></li>
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
							echo $helpers['Form']->input('name', _("Libellé du groupe"), array('compulsory' => true, 'tooltip' => _("Indiquez le libellé du groupe d'utilisateurs")));
							$rolesId = array(
								1 => _("Administrateur backoffice"), 
								2 => _("Utilisateur backoffice"), 
								3 => _('Utilisateur frontoffice')
							);
							echo $helpers['Form']->input('role_id', _("Type de profil"), array('compulsory' => true, 'type' => 'select', 'datas' => $rolesId, 'firstElementList' => _("Sélectionnez un type de profil"), 'tooltip' => _("Indiquez le type de profil de ce groupe d'utilisateurs")));			
							echo $helpers['Form']->input('default_home', _("Url de la home page"), array('tooltip' => _("Indiquez l'url de la home page (backoffice/controller/action/params), laissez vide pour ne pas modifier l'url par défaut")));
							echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce groupe d'utilisateurs")));			
							?>           			
                		</div>
				    	<div class="tab-pane" id="websites_users">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-globe"></i> <?php echo _("Sites accessibles"); ?></h4>                  
                			</div>  
							
							<label><?php echo _("Site(s) administrable(s)"); ?></label>
							<p class="help-block"><?php echo ("Cochez le (ou les) site(s) que cet utilisateur peut administrer"); ?></p>
							<table class="table">
								<tbody>
									<?php
									$i=0;
									foreach($websitesList as $id => $name) {
										
										if($i==0) { echo '<tr>'; }												
										echo '<td>'.$helpers['Form']->input('website_id.'.$id, $name, array('type' => 'checkbox')).'</td>';
										$i++;
										if($i==4) {
											
											echo '</tr>';
											$i=0;
										}
									}
									
									if($i<4) { 
										
										$colspan = 4 - $i;
										echo '<td colspan="'.$colspan.'"></td>'; 
									}									
									?>
								</tbody>
							</table>	
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