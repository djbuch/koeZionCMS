<div class="row">
	<div class="nav-tabs-custom">
		<div class="col-md-2 left">
			<div class="box box-primary">
	    		<div class="box-body">
					<ul class="nav nav-tabs nav-stacked col-md-12">
				    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
				    	<li><a href="#textes" data-toggle="tab"><i class="fa fa-file-word-o"></i> <?php echo _("Illustrations et descriptifs"); ?></a></li>
				    	<li><a href="#types" data-toggle="tab"><i class="fa fa-tags"></i> <?php echo _("Tags"); ?></a></li>
				    	<li><a href="#seo" data-toggle="tab"><i class="fa fa-search"></i> <?php echo _("SEO"); ?></a></li>
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
							echo $helpers['Form']->input('category_id', _('Page'), array('type' => 'select', 'datas' => $categoriesList, 'compulsory' => true, 'tooltip' => _("Indiquez la page dans laquelle sera affiché le portfolio"), 'firstElementList' => _("Sélectionnez une catégorie")));
							echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du portfolio")));
							echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce portfolio")));
							?>	             			
                		</div>
				    	<div class="tab-pane" id="textes">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-file-word-o"></i> <?php echo _("Illustrations et descriptifs"); ?></h4>                  
                			</div>                	
							<?php 
							echo $helpers['Form']->upload_files('short_content_illustration', array('label' => _("Image d'illustration descriptif court")));
							echo $helpers['Form']->input('short_content', _('Descriptif court'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le descriptif court de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
							echo $helpers['Form']->upload_files('content_illustration', array('label' => _("Image d'illustration descriptif long")));
							echo $helpers['Form']->input('content', _('Descriptif long'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le descriptif long de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
							?>		
                		</div>
				    	<div class="tab-pane" id="types">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-tags"></i> <?php echo _("Tags"); ?></h4>                  
                			</div>                
                			<?php if($portfoliosTypes) { ?>
								<table class="table">
									<tbody>
										<?php
										$i=0;
										foreach($portfoliosTypes as $k => $v) {
											
											if($i==0) { echo '<tr>'; }												
											echo '<td>'.$helpers['Form']->input('portfolios_type_id.'.$k, $v, array('type' => 'checkbox')).'</td>';
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
							<?php } ?>	
                		</div>
				    	<div class="tab-pane" id="seo">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-search"></i> <?php echo _("SEO"); ?></h4>                  
                			</div>                	
							<?php 
							echo $helpers['Form']->input('prefix', _("Préfixe d'url"), array('compulsory' => true, 'value' => PORTFOLIO_PREFIX, 'tooltip' => _("Indiquez le préfixe à utiliser pour accéder à cet article (Le préfixe est situé juste avant l'url et vous permet de rajouter un ou deux mots clés dans l'url) <br />ATTENTION n'utiliser que des caractères autorisés pour le préfixe (Que des lettres, des chiffres et -)"))); //Voir dans le fichier routes.php pour l'initialisation		
							echo $helpers['Form']->input('slug', _('Url'), array('tooltip' => _("Indiquez l'url que vous souhaitez mettre en place. ATTENTION n'utiliser que des caractères autorisés pour l'url (Que des lettres, des chiffres et -)")));
							echo $helpers['Form']->input('page_title', _('Meta title'), array('tooltip' => _("Titre de la page (70 caractères maximum recommandé, par défaut ce champ aura pour valeur le champ Titre)")));
							echo $helpers['Form']->input('page_description', _('Meta description'), array('tooltip' => _("Résumé de la page html, 160 caractères maximum recommandé")));
							echo $helpers['Form']->input('page_keywords', _('Meta keywords'), array('tooltip' => _("Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (optionnel)")));
							?>		
                		</div>
				    	<div class="tab-pane" id="options">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-plug"></i> <?php echo _("Options"); ?></h4>                  
                			</div>           
							<?php 
							echo $helpers['Form']->input('automatic_scan', _('Scanner automatiquement le dossier source'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour effectuer un scan automatique du dossier source")));
							$portfolioParams = array(
								'label' => _("Dossier source"),
								'tooltip' => _("Sélectionnez une image dans le dossier source"),
								'button_value' => _("Sélectionner l'image")		
							);
							echo $helpers['Form']->upload_files('source_folder', $portfolioParams);
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