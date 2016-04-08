<?php 
$websitesSession 		= Session::read('Backoffice.Websites'); //Récupération de la variable de session
$currentWebsiteId 		= $websitesSession['current']; //Récupération du site courant
$currentWebsiteDatas 	= $websitesSession['details'][$currentWebsiteId]; //Récupération du site courant
?>
<div class="row">
	<div class="nav-tabs-custom">
		<div class="col-md-2 left">
			<div class="box box-primary">
	    		<div class="box-body">
					<ul class="nav nav-tabs nav-stacked col-md-12">
				    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
				    	<li><a href="#valid" data-toggle="tab"><i class="fa fa-envelope"></i> <?php echo _("Validation inscription"); ?></a></li>
				    	<li><a href="#refused" data-toggle="tab"><i class="fa fa-envelope"></i> <?php echo _("Refus inscription"); ?></a></li>
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
							echo $helpers['Form']->input('users_group_id', _("Groupe d'utilisateurs"), array('compulsory' => true, 'type' => 'select', 'datas' => $usersGroupList, 'firstElementList' => _("Sélectionnez un groupe"), 'tooltip' => _("Indiquez le groupe de cet utilisateur")));			
							echo $helpers['Form']->input('name', _('Nom'), array('compulsory' => true, 'tooltip' => _("Indiquez le nom de l'utilisateur")));
							echo $helpers['Form']->input('second_name', _('Complément nom'), array('tooltip' => _("Indiquez un complément pour le nom de l'utilisateur")));
							echo $helpers['Form']->input('login', _('Identifiant'), array('compulsory' => true, 'tooltip' => _("Indiquez l'identifiant de l'utilisateur (Généralement un email)")));
							echo $helpers['Form']->input('password', _('Mot de passe'), array('compulsory' => true, 'tooltip' => _("Indiquez le mot de passe de l'utilisateur"), 'type' => 'password'));
							echo $helpers['Form']->input('email', _('Email de contact'), array('compulsory' => true, 'tooltip' => _("Indiquez l'email de contact de cet utilisateur (peut être identique au login si celui-ci est un email)")));
							echo $helpers['Form']->input('online', _('Actif'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour valider cet utilisateur")));
							?>	             			
                		</div>
				    	<div class="tab-pane" id="valid">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-envelope"></i> <?php echo _("Validation inscription"); ?></h4>                  
                			</div>						
							<?php 
							echo $helpers['Form']->input('is_validated_user', _("Cocher cette case pour valider l'inscription de cet utilisateur"), array('type' => 'checkbox'));
							echo $helpers['Form']->input('subject_mail_validated_user', _("Sujet de l'email"), array('value' => $currentWebsiteDatas['subject_mail_validated_user']));
							echo $helpers['Form']->input('txt_mail_validated_user', _("Contenu de l'email"), array('type' => 'textarea', 'wysiswyg' => true, 'value' => $currentWebsiteDatas['txt_mail_validated_user']));
							?>							
                		</div>
				    	<div class="tab-pane" id="refused">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-envelope"></i> <?php echo _("Refus inscription"); ?></h4>                  
                			</div>
							<?php 
							echo $helpers['Form']->input('is_refused_user', _("Cocher cette case pour refuser l'inscription de cet utilisateur"), array('type' => 'checkbox'));
							echo $helpers['Form']->input('subject_mail_refused_user', _("Sujet de l'email lorsque l'inscription refusée"), array('value' => $currentWebsiteDatas['subject_mail_refused_user']));
							echo $helpers['Form']->input('txt_mail_refused_user', _("Contenu de l'email lorsque l'inscription refusée"), array('type' => 'textarea', 'wysiswyg' => true, 'value' => $currentWebsiteDatas['txt_mail_refused_user']));
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
                	<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>