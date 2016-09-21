<section class="content-header">
	<h1><?php echo _("Importer des utilisateurs"); ?></h1>
</section>	
<section class="content">
	<div class="row">
    	<div class="add_edit_page col-md-12">    		     	
	    	<?php 
	    	$this->element('flash_messages');	    	
	    		    		    	
	    	$formOptions = array('id' => $params['modelName'].'Import', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/import'), 'method' => 'post', 'enctype' => 'multipart/form-data');
	    	echo $helpers['Form']->create($formOptions);
			?>
			<div class="row">
				<div class="nav-tabs-custom">
					<div class="col-md-3 left">
						<div class="box box-primary">
				    		<div class="box-body">
								<ul class="nav nav-tabs nav-stacked col-md-12">
							    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
							    	<li><a href="#websites_users" data-toggle="tab"><i class="fa fa-globe"></i> <?php echo _("Sites accessibles"); ?></a></li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="col-md-9 right">
						<div class="box box-primary">
				    		<div class="box-body">		
							    <div class="tab-content col-md-12">
							    	<div class="tab-pane active" id="general">	
							    		<div class="box-header bg-light-blue">
											<h4><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></h4>                  
			                			</div>	
										<?php
										echo $helpers['Form']->input('users_group_id', _("Groupe d'utilisateurs"), array('compulsory' => true, 'type' => 'select', 'datas' => $usersGroupList, 'firstElementList' => _("Sélectionnez un groupe"), 'tooltip' => _("Indiquez le groupe de cet utilisateur")));
										echo $helpers['Form']->upload_files('users_file', array('label' => _("Fichier utilisateurs")));
										?>
										<a href="<?php echo Router::url('files/users', 'csv'); ?>"><?php echo _("Téléchargez un fichier d'exemple"); ?></a>
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
			                	<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div> 
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