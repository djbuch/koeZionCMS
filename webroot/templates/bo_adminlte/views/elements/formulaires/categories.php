<div class="row">
	<div class="nav-tabs-custom">
		<div class="col-md-2 left">
			<div class="box box-primary">
	    		<div class="box-body">
					<ul class="nav nav-tabs nav-stacked col-md-12">
				    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
				        <li><a href="#right_column" data-toggle="tab"><i class="fa fa-navicon"></i> <?php echo _("Colonne page"); ?></a></li>
				        <li><a href="#buttons" data-toggle="tab"><i class="fa fa-hand-o-right"></i> <?php echo _("Boutons page"); ?></a></li>
				        <li><a href="#seo" data-toggle="tab"><i class="fa fa-search"></i> <?php echo _("SEO"); ?></a></li>
				        <li><a href="#options" data-toggle="tab"><i class="fa fa-plug"></i> <?php echo _("Options"); ?></a></li>
				        <li><a href="#secure" data-toggle="tab"><i class="fa fa-lock"></i> <?php echo _("Sécuriser la page"); ?></a></li>
						<?php 
						//On ne va afficher ce menu que si le site courant est sécurisé
						$websitesSession = Session::read('Backoffice.Websites'); //Récupération de la variable de session
						$currentWebsite = $websitesSession['current']; //Récupération du site courant
						$websiteDetails = $websitesSession['details'][$currentWebsite]; //Récupération du détail du site courant
						$isSecure = $websiteDetails['secure_activ']; //On va vérifier si celui-ci est sécurisé
						if($isSecure) { ?><li><a href="#emailing" data-toggle="tab"><i class="fa fa-envelope"></i> <?php echo _("Emailing"); ?></a></li><?php }
						?>
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
							echo $helpers['Form']->input('parent_id', _('Page parente'), array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => _("Indiquez la page parente de cette page (Racine du site par défaut)")));
							echo $helpers['Form']->input('type', _('Type de page'), array('type' => 'select', 'datas' => $typesOfPage, 'tooltip' => _("Une page évènement n'est pas intégrée dans le menu général, vous pourrez faire des liens vers celle-ci via l'éditeur WYSIWYG")));
							echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre de la page. Ce champ sera utilisé (par défaut) comme titre de page dans les moteurs de recherche, 70 caractères maximum recommandé")));
							echo $helpers['Form']->input('content', _('Contenu'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Saisissez ici le contenu de votre page, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
							echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser cette page")));
							?>
				    	</div>
				        <div class="tab-pane" id="right_column">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-navicon"></i> <?php echo _("Colonne page"); ?></h4>                  
                			</div>	
				        	<?php
							//echo $helpers['Form']->input('title_colonne_droite', 'Titre colonne de droite', array('tooltip' => "Indiquez le titre qui sera affiché dans la colonne de droite"));			
							echo $helpers['Form']->input('display_children', _('Afficher les pages enfants dans la colonne de droite'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les enfants de la page seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite")));
							echo $helpers['Form']->input('title_children', _('Titre colonne enfants'), array('tooltip' => _("Indiquez le titre qui sera affiché dans la colonne de droite pour les enfants (Activé que si vous avez cliqué sur la case précédente)")));			
							echo $helpers['Form']->input('display_brothers', _('Afficher les pages du même niveau dans la colonne de droite'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les pages du même niveau seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite")));
							echo $helpers['Form']->input('title_brothers', _('Titre colonne même niveau'), array('tooltip' => _("Indiquez le titre qui sera affiché dans la colonne de droite pour les menus du même niveau (Activé que si vous avez cliqué sur la case précédente)")));			
							?>
				        </div>
				        <div class="tab-pane" id="buttons">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-hand-o-right"></i> <?php echo _("Boutons page"); ?></h4>                  
                			</div>	
					        <p><?php echo _("Précisez ici le ou les boutons à rajouter à cette page"); ?>.</p>
							<div class="input-group">
								<?php echo $helpers['Form']->input('rightButtonsListId', '', array('type' => 'select', 'datas' => $rightButton, 'onlyInput' => true, 'firstElementList' => _("Sélectionnez un bouton"))); ?>
								<span class="input-group-btn"> 
									<a id="addRightButton" class="btn btn-default btn-flat btnselect"><span><?php echo _("Rajouter ce bouton"); ?></span></a>
								</span>
							</div>
							<?php
							if(isset($this->controller->request->data['right_button_id'])) {
							
								foreach($this->controller->request->data['right_button_id'] as $rightButtonId => $isActiv) {
								
									$this->element('right_button_line', array('rightButtonId' => $rightButtonId, 'rightButtonName' => $rightButton[$rightButtonId]));
								}
							}
							?>							
				        </div>
				        <div class="tab-pane" id="seo">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-search"></i> <?php echo _("SEO"); ?></h4>                  
                			</div>	
				       	 <?php 
							echo $helpers['Form']->input('slug', _('Url'), array('tooltip' => _("Indiquez l'url que vous souhaitez mettre en place. ATTENTION n'utiliser que des caractères autorisés pour l'url (Que des lettres, des chiffres et -)")));
							echo $helpers['Form']->input('page_title', _('Meta title'), array('tooltip' => _("Titre de la page (70 caractères maximum recommandé, par défaut ce champ aura pour valeur le champ Titre)")));
							echo $helpers['Form']->input('page_description', _('Meta description'), array('tooltip' => _("Résumé de la page html, 160 caractères maximum recommandé")));
							echo $helpers['Form']->input('page_keywords', _('Meta keywords'), array('tooltip' => _("Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (Optionnel)")));		
							?>
				        </div>
				        <div class="tab-pane" id="options">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-plug"></i> <?php echo _("Options"); ?></h4>                  
                			</div>	
				        	<?php 
							//On va supprimer la catégorie racine
							$racine = each($categoriesList);
							unset($categoriesList[$racine['key']]);			
							$categoriesList[-1] = "[&nbsp;&nbsp;&nbsp;"._("Redirection vers la page d'accueil")."&nbsp;&nbsp;&nbsp;]";
							echo $helpers['Form']->input('redirect_category_id', _('Rediriger vers'), array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => _("Vous permet de rediriger cette page vers une autre de la liste"), 'firstElementList' => _("Pas de redirection")));			
							echo $helpers['Form']->input('redirect_to', _('Redirection personnalisée'), array('tooltip' => _("Saisissez l'url de redirection")));
							
							echo $helpers['Form']->input('title_posts_list', _('Titre bloc article'), array('tooltip' => _("Indiquez le titre qui sera affiché au dessus de la liste des articles")));			
							if(!isset($formulaires)) { $formulaires = array (1 => _('Formulaire de contact')); } 
							echo $helpers['Form']->input('display_form', _('Formulaire'), array('type' => 'select', 'datas' => $formulaires, 'tooltip' => _("Indiquez le formulaire que vous souhaitez afficher sur la page"), 'firstElementList' => _("Sélectionnez un formulaire")));
							
							echo $helpers['Form']->upload_files('css_file', array('label' => _("Fichier css"), 'button_value' => _('Sélectionner un fichier CSS'), 'tooltip' => _("Vous pouvez uploader un fichier CSS supplémentaire si besoin. Attention ce fichier ne sera pris en compte que lors de l'affichage de cette page.")));
							echo $helpers['Form']->upload_files('js_file', array('label' => _("Fichier javascript"), 'button_value' => _('Sélectionner un fichier JS'), 'tooltip' => _("Vous pouvez uploader un fichier JS supplémentaire si besoin. Attention ce fichier ne sera pris en compte que lors de l'affichage de cette page.")));
							?>
				        </div>
				        <div class="tab-pane" id="secure">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-lock"></i> <?php echo _("Sécuriser la page"); ?></h4>                  
                			</div>	
				       		<?php 
							echo $helpers['Form']->input('is_secure', _('Activer la protection de la page'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer la protection de la page")));
							echo $helpers['Form']->input('txt_secure', _('Texte page sécurisée'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Saisissez ici le texte qui sera affiché si la page est sécurisée")));	
							?>
				        </div>
				        <?php if($isSecure) { ?>
					        <div class="tab-pane" id="emailing">	
					    		<div class="box-header bg-light-blue">
									<h4><i class="fa fa-envelope"></i> <?php echo _("Emailing"); ?></h4>                  
	                			</div>	
					        	<?php 
								echo $helpers['Form']->input('send_mail', _("Envoyer un email pour informer les utilisateurs de l'ajout (ou de la modification)"), array('type' => 'checkbox', 'tooltip' => _("En cochant cette case un email sera automatiquement envoyer à l'ensemble des utilisateurs référencés dans le système")));
								echo $helpers['Form']->input('message_mail', _('Contenu email newsletter'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
								?>
					        </div>
				        <?php } ?>
					</div>
				</div>
				<div class="box-footer">
                	<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>    
				</div>
			</div>
		</div>
	</div>
</div>