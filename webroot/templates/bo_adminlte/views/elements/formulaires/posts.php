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
				    	<li><a href="#textes" data-toggle="tab"><i class="fa fa-file-word-o"></i> <?php echo _("Descriptifs court et long"); ?></a></li>
				    	<li><a href="#types" data-toggle="tab"><i class="fa fa-tags"></i> <?php echo _("Types d'article"); ?></a></li>
				    	<li><a href="#right_column" data-toggle="tab"><i class="fa fa-navicon"></i> <?php echo _("Colonne page"); ?></a></li>
				    	<li><a href="#buttons" data-toggle="tab"><i class="fa fa-hand-o-right"></i> <?php echo _("Boutons page"); ?></a></li>
				    	<li><a href="#seo" data-toggle="tab"><i class="fa fa-search"></i> <?php echo _("SEO"); ?></a></li>
				    	<li><a href="#options" data-toggle="tab"><i class="fa fa-plug"></i> <?php echo _("Options"); ?></a></li>	
				        <li><a href="#secure" data-toggle="tab"><i class="fa fa-lock"></i> <?php echo _("Sécuriser l'article"); ?></a></li>			
						<?php
						//On ne va afficher ce menu que si le site courant est sécurisé
						$websiteDetails = $websitesSession['details'][$currentWebsite]; //Récupération du détail du site courant
						$isSecure = $websiteDetails['secure_activ']; //On va vérifier si celui-ci est sécurisé
						if($isSecure) {
							
							?><li><a href="#emailing" data-toggle="tab"><i class="fa fa-send"></i> <?php echo _("Emailing"); ?></a></li><?php 
						}	
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
							echo $helpers['Form']->input('name', _("Titre de l'article"), array('compulsory' => true, 'tooltip' => _("Indiquez le titre de l'article. Ce champ sera utilisé comme titre de page dans les moteurs de recherche, 70 caractères maximum recommandé")));
							echo $helpers['Form']->input('dont_change_modified_date', _('Ne pas changer la date de modification'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour ne pas changer automatiquement la date de modification de l'article")));
							echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser cet article")));
							?>	             			
                		</div>                	
				    	<div class="tab-pane" id="diffusion">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-copy"></i> <?php echo _("Diffusion"); ?></h4>                  
                			</div>               
                			<div class="callout callout-info">
			                	<p><?php echo _('Pour diffuser cet article dans un ou plusieurs sites cochez la ou les cases correspondantes')?>.</p>
							</div> 		
							<?php 
							foreach($websitesSession['liste'] as $websiteId => $websiteName) {
								
								$websiteCategories = $this->request('Categories', 'request_tree_list', array('websiteId' => $websiteId));									
								?><h5 class="form-title"><?php echo _("Site")." ".$websiteName; ?></h5><?php 
								echo $helpers['Form']->input('CategoriesPostsWebsite.'.$websiteId.'.display', _('Diffuser dans le site').' '.$websiteName, array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser cet article dans le site".' '.$websiteName)));
								echo $helpers['Form']->input('CategoriesPostsWebsite.'.$websiteId.'.display_home_page', _("Afficher cet article sur la la page d'accueil du site").' '.$websiteName, array('type' => 'checkbox', 'tooltip' => _("En cochant cette case vous afficherez cet article sur la page d'accueil du site".' '.$websiteName)));
								echo $helpers['Form']->input('CategoriesPostsWebsite.'.$websiteId.'.category_id', _("Catégorie parente de l'article dans le site").' '.$websiteName, array('type' => 'select', 'datas' => $websiteCategories, 'tooltip' => _("Indiquez la catégorie parente de cet article, c'est à partir de cette catégorie que cet article sera accessible dans le site").' '.$websiteName, 'firstElementList' => _("Sélectionnez une catégorie")));
							}
							?>	
                		</div>
				    	<div class="tab-pane" id="textes">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-file-word-o"></i> <?php echo _("Descriptifs court et long"); ?></h4>                  
                			</div>                	
							<?php 
							echo $helpers['Form']->input('short_content', _('Descriptif court'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le descriptif court de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
							echo $helpers['Form']->input('content', _('Descriptif long'), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Saisissez ici le descriptif long de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
							?>		
                		</div>
				    	<div class="tab-pane" id="types">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-tags"></i> <?php echo _("Types d'article"); ?></h4>                  
                			</div>                
                			<?php echo $helpers['Form']->input('display_posts_types', _("Afficher les types d'articles dans la colonne de page"), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les types d'articles seront affichés dans la colonne de droite"))); ?>
							<label>Type d'article</label>
							<p class="help-block"><?php echo _("Cochez les types d'article (Plusieurs choix possibles)"); ?></p>
							<?php if($postsTypes) { ?>
								<table class="table">
									<tbody>
										<?php
										foreach($postsTypes as $rubrique => $v) {
											
											?>
											<tr class="table_line_title">
												<th colspan="4"><i class="fa fa-arrow-right"></i> <?php echo _('Rubrique').' : '.$rubrique; ?></th>
											</tr>
											<?php
											$i=0;
											foreach($v as $postsType) {
												
												if($i==0) { echo '<tr>'; }												
												echo '<td>'.$helpers['Form']->input('posts_type_id.'.$postsType['id'], $postsType['name'], array('type' => 'checkbox')).'</td>';
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
										}
										?>
									</tbody>
								</table>
							<?php } ?>	
                		</div>
				    	<div class="tab-pane" id="right_column">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-navicon"></i> <?php echo _("Colonne page"); ?></h4>                  
                			</div>    
							<?php
							echo $helpers['Form']->input('title_colonne_droite', _('Titre colonne'), array('tooltip' => _("Indiquez le titre qui sera affiché dans la colonne")));			
							echo $helpers['Form']->input('display_children', _('Afficher les pages enfants dans la colonne'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les enfants de la page seront affichés dans la colonne, ATTENTION pensez à saisir le champ titre de la colonne")));
							echo $helpers['Form']->input('display_brothers', _('Afficher les pages du même niveau dans la colonne'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les pages du même niveau seront affichés dans la colonne, ATTENTION pensez à saisir le champ titre de la colonne")));
							?>            			
                		</div>
				    	<div class="tab-pane" id="buttons">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-hand-o-right"></i> <?php echo _("Boutons page"); ?></h4>                  
                			</div>                			
							<p><?php echo _("Précisez ici le ou les boutons à rajouter à cet article."); ?></p>
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
							echo $helpers['Form']->input('prefix', _("Préfixe d'url"), array('compulsory' => true, 'value' => POST_PREFIX, 'tooltip' => _("Indiquez le préfixe à utiliser pour accéder à cet article (Le préfixe est situé juste avant l'url et vous permet de rajouter un ou deux mots clés dans l'url) <br />ATTENTION n'utiliser que des caractères autorisés pour le préfixe (Que des lettres, des chiffres et -)"))); //Voir dans le fichier routes.php pour l'initialisation		
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
							echo $helpers['Form']->input('display_link', _("Afficher un lien sous forme de bouton à la suite de l'article"), array('type' => 'checkbox', 'tooltip' => _("En cochant cette case vous afficherez automatiquement le lien pour se rendre sur le détail de l'article, par défaut le titre de l'article sera également cliquable")));						
							echo $helpers['Form']->input('redirect_to', _('Url de redirection'), array('tooltip' => _("Remplissez ce champ si souhaitez, à partir de cet article, faire une redirection vers une url de votre choix, il ne vous sera alors pas nécessaire de saisir le descriptif long")));			
							
							if(!isset($formulaires)) { $formulaires = array (2 => _('Formulaire commentaire article')); } 
							echo $helpers['Form']->input('display_form', _('Formulaire'), array('type' => 'select', 'datas' => $formulaires, 'tooltip' => _("Indiquez le formulaire que vous souhaitez afficher sur la page"), 'firstElementList' => _("Sélectionnez un formulaire")));					
							echo $helpers['Form']->input('shooting_time', _("Durée de réalisation"), array('tooltip' => _("Indiquez la durée de réalisation de ce qui sera présenté dans cet article")));
							echo $helpers['Form']->input('img', _('image'), array('type' => 'file', 'class' => 'input-file', 'tooltip' => _("Cliquez sur le bouton pour télécharger votre image d'illustration. Attention : Mode RVB, Résolution 72dpi")));
							echo $helpers['Form']->input('publication_start_date', _('Date de publication'), array('wrapperDivClass' => 'form-group no_border_bottom', 'class' => 'form-control datepicker', 'placeholder' => 'dd.mm.yy', 'tooltip' => _("Indiquez la date à laquelle cet article sera publié"))); 
							?>
							<div class="row">
								<p class="col-md-12"><?php echo _("Cette option vous permet de définir la date à laquelle sera publié l'article en utilisant une tâche"); ?> <a href="http://fr.wikipedia.org/wiki/Cron" target="_blank">CRON</a></p>
								<p class="col-md-12"><?php echo _("Vous pouvez utiliser des services CRON gratuits comme par exemple"); ?> <a href="http://www.cronoo.com/" target="_blank">Cronoo</a></p>
							</div>			
							<?php 
							$websiteUrl = Session::read('Backoffice.Websites.details.'.CURRENT_WEBSITE_ID.'.url');
							
							require_once(LIBS.DS.'config_magik.php');
							$cfg = new ConfigMagik(CONFIGS_FILES.DS.'security_code.ini', true, false);
							$updateCode = $cfg->keys_values();
							
							if(empty($updateCode['security_code'])) {
								
								?>
								<div class="row">
									<p class="col-md-12"><?php echo _("Pour utiliser cette fonctionnalité vous devez en premier lieu"); ?> <a href="<?php echo Router::url('backoffice/configs/security_code_liste'); ?>"><?php echo _("paramétrer le code de sécurité"); ?></a> <?php echo _("utilisé pour pouvoir lancer cette procédure"); ?></p>
								</div>
								<?php 
							
							} else { 
							
								?>
								<div class="row">
									<p class="col-md-12"><?php echo _("Pour mettre en place la diffusion automatique vous pouvez utiliser l'url suivante"); ?> <?php echo $websiteUrl; ?>/posts/publish.xml?update_code=<?php echo $updateCode['security_code']; ?></p>
									<p class="col-md-12"><?php echo _("Le type du format de retour est l'XML"); ?></p>
									<p class="col-md-12">
									&lt;export&gt;<br />
									&nbsp;&nbsp;&nbsp;&nbsp;&lt;result&gt;<?php echo _("MISE A JOUR EFFECTUEE"); ?>&lt;/result&gt;<br />
									&nbsp;&nbsp;&nbsp;&nbsp;&lt;message&gt;<?php echo _("La mise à jour des dates de publications à été effectuée"); ?>&lt;/message&gt;<br />
									&lt;/export&gt;
									</p>
								</div>
								<div class="col-md-12 callout callout-warning"><?php echo _("ATTENTION : si vous utilisez une date de publication vous ne devez pas cocher le champ En ligne, la tâche automatisée qui sera effectuée fera automatiquement la mise à jour de ce champ."); ?></div>
								<?php 
							} 
							?>
                		</div>
				        <div class="tab-pane" id="secure">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-lock"></i> <?php echo _("Sécuriser l'article"); ?></h4>                  
                			</div>	
				       		<?php 
							echo $helpers['Form']->input('is_secure', _("Activer la protection de l'article"), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer la protection de l'article")));
							echo $helpers['Form']->input('txt_secure', _('Texte article sécurisé'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Saisissez ici le texte qui sera affiché si l'article est sécurisé")));	
							?>
				        </div>
	                	<?php if($isSecure) { ?>
					    	<div class="tab-pane" id="emailing">	
					    		<div class="box-header bg-light-blue">
									<h4><i class="fa "></i> <?php echo _("Emailing"); ?></h4>                  
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