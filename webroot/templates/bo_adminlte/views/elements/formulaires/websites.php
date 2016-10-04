<div class="row">
	<div class="nav-tabs-custom">
		<div class="col-md-3 left">
			<div class="box box-primary">
	    		<div class="box-body">
					<ul class="nav nav-tabs nav-stacked col-md-12">
				    	<li class="active"><a href="#general" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Général"); ?></a></li>
				    	<li><a href="#tpl" data-toggle="tab"><i class="fa fa-image"></i> <?php echo _("Template"); ?></a></li>
				    	<li><a href="#header" data-toggle="tab"><i class="fa fa-arrow-up"></i> <?php echo _("Header"); ?></a></li>
				    	<li><a href="#foot" data-toggle="tab"><i class="fa fa-arrow-down"></i> <?php echo _("Footer"); ?></a></li>
				    	<li><a href="#txt" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php echo _("Textes"); ?></a></li>
				    	<li><a href="#txtemailscontact" data-toggle="tab"><i class="fa fa-envelope"></i> <?php echo _("Textes/emails formulaire de contact"); ?></a></li>
				    	<li><a href="#txtemailscomments" data-toggle="tab"><i class="fa fa-envelope"></i> <?php echo _("Textes/emails formulaire commentaires"); ?></a></li>
				    	<li><a href="#txtemailsnewsletter" data-toggle="tab"><i class="fa fa-envelope"></i> <?php echo _("Textes/emails formulaire newsletter"); ?></a></li>
				    	<li><a href="#txtemailsuser" data-toggle="tab"><i class="fa fa-envelope"></i> <?php echo _("Textes/emails nouvel utilisateur"); ?></a></li>
				    	<li><a href="#contactpage" data-toggle="tab"><i class="fa fa-map-marker"></i> <?php echo _("Page contact"); ?></a></li>
				    	<li><a href="#postsconfigs" data-toggle="tab"><i class="fa fa-comments"></i> <?php echo _("Configurations articles"); ?></a></li>
				    	<li><a href="#slidesconfigs" data-toggle="tab"><i class="fa fa-exchange"></i> <?php echo _("Configurations slides"); ?></a></li>
				    	<li><a href="#focusconfigs" data-toggle="tab"><i class="fa fa-plus-square-o"></i> <?php echo _("Configurations focus"); ?></a></li>
				    	<li><a href="#seo" data-toggle="tab"><i class="fa fa-search"></i> <?php echo _("SEO"); ?></a></li>
				    	<li><a href="#options" data-toggle="tab"><i class="fa fa-plug"></i> <?php echo _("Options"); ?></a></li>
				    	<li><a href="#secure" data-toggle="tab"><i class="fa fa-minus-circle"></i> <?php echo _("Sécurité"); ?></a></li>
				    	<li><a href="#googleanalytics" data-toggle="tab"><i class="fa fa-line-chart"></i> <?php echo _("Google Analytics"); ?></a></li>
				    	<li><a href="#connect" data-toggle="tab"><i class="fa fa-lock"></i> <?php echo _("Page de connexion"); ?></a></li>
				    	<li><a href="#cssjs" data-toggle="tab"><i class="fa fa-code"></i> <?php echo _("CSS & JS"); ?></a></li>
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
							echo $helpers['Form']->input('name', _('Titre'), array('compulsory' => true, 'tooltip' => _("Indiquez le titre du site Internet")));
							echo $helpers['Form']->input('url', _('Url'), array('compulsory' => true, 'tooltip' => _("Indiquez l'url complète du site Internet (avec http:// et sans le / à la fin), sautez une ligne entre chaque domaine.")));
							echo $helpers['Form']->input('url_alias', _('Alias du domaine'), array('type' => 'textarea', 'tooltip' => _("Indiquez ici le ou les alias de ce domaine (url complète avec http:// ou non sans le / à la fin), sautez une ligne entre chaque domaine.")));
							echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce site Internet")));
							?>            			
                		</div>
				    	<div class="tab-pane" id="tpl">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-image"></i> <?php echo _("Template"); ?></h4>                  
                			</div>  
							<?php 
							if(count($templatesFilter) == 1) { $templatesFilter = current($templatesFilter); } //On à qu'un seul template on ne va afficher que les informations de ce template
							echo $helpers['Form']->input('filter_template', _('Filtrer les templates'), array('type' => 'select', 'datas' => $templatesFilter, 'firstElementList' => _("Sélectionnez une version de template pour filtrer les résultats"))); 
							
							$this->element('formulaires/websites_templates');
							?>
                		</div>
				    	<div class="tab-pane" id="header">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-arrow-up"></i> <?php echo _("Header"); ?></h4>                  
                			</div>  
							<?php 
							echo $helpers['Form']->input('tpl_logo', _('Logo'), array('type' => 'textarea', 'toolbar' => 'image', 'wysiswyg' => true,  'tooltip' => _("Sélectionnez votre logo à l'aide de l'explorateur de fichier")));
							echo $helpers['Form']->input('header_txt', _('Texte header (Optionnel)'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Texte affiché dans le header (Optionnel selon le template sélectionné)")));
							//echo $helpers['Form']->input('tpl_header', _('Header'), array('type' => 'textarea', 'toolbar' => 'image', 'wysiswyg' => true,  'tooltip' => _("Sélectionnez l'image du header à l'aide de l'explorateur de fichier")));
							?>			
                		</div>
				    	<div class="tab-pane" id="foot">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-arrow-down"></i> <?php echo _("Footer"); ?></h4>                  
                			</div>  		
							<?php 
							echo $helpers['Form']->input('footer_gauche', _('Colonne de gauche'), array('type' => 'textarea', 'wysiswyg' => true));			
							echo $helpers['Form']->input('footer_centre', _('Colonne centrale'), array('type' => 'textarea', 'wysiswyg' => true));			
							echo $helpers['Form']->input('footer_droite', _('Colonne de droite'), array('type' => 'textarea', 'wysiswyg' => true));
							echo $helpers['Form']->input('footer_bottom', _('Baseline'), array('type' => 'textarea', 'wysiswyg' => true));
							echo $helpers['Form']->input('footer_social', _('Texte social'), array('type' => 'textarea', 'tooltip' => _("Indiquez ici, par exemple, le texte du module social de Facebook. Attention si vous activez cette zone la zone newsletter sera supprimée.")));
							echo $helpers['Form']->input('footer_addthis', _('Module AddThis'), array('type' => 'textarea', 'tooltip' => _("Indiquez ici le code pour le module AddThis.")));
							?>
                		</div>
				    	<div class="tab-pane" id="txt">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-file-text-o"></i> <?php echo _("Textes"); ?></h4>                  
                			</div>  	
							<?php 
							echo $helpers['Form']->input('txt_slogan', _('Slogan (accueil)'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le slogan du site")));
							echo $helpers['Form']->input('txt_posts', _('Articles (accueil)'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte de présentation des articles sur la page d'accueil")));
							
							//echo $helpers['Form']->input('txt_social', _('Texte social'), array('type' => 'textarea', 'tooltip' => _("")));
							?>
                		</div>
				    	<div class="tab-pane" id="txtemailscontact">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-envelope"></i> <?php echo _("Textes/emails du formulaire de contact"); ?></h4>                  
                			</div>  	
							<?php 
							echo $helpers['Form']->input('txt_after_form_contact', _('Mentions après le formulaire'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte qui sera affiché après le formulaire")));
							?><h5 class="form-title"><?php echo _("Email de confirmation"); ?></h5><?php
							echo $helpers['Form']->input('subject_mail_contact', _("Sujet de l'email"), array('tooltip' => _("Indiquez le sujet de l'email")));							
							echo $helpers['Form']->input('txt_mail_contact', _("Contenu de l'email"), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
							?>
                		</div>
				    	<div class="tab-pane" id="txtemailscomments">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-envelope"></i> <?php echo _("Textes/emails du formulaire de commentaires article"); ?></h4>                  
                			</div>  	
							<?php 
							echo $helpers['Form']->input('txt_after_form_comments', _('Mentions après le formulaire'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte qui sera affiché après le formulaire")));
							?><h5 class="form-title"><?php echo _("Email de confirmation"); ?></h5><?php
							echo $helpers['Form']->input('subject_mail_comments', _("Sujet de l'email"), array('tooltip' => _("Indiquez le sujet de l'email")));							
							echo $helpers['Form']->input('txt_mail_comments', _("Contenu de l'email"), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
							?>
                		</div>
				    	<div class="tab-pane" id="txtemailsnewsletter">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-envelope"></i> <?php echo _("Textes/emails du formulaire d'inscription à la newsletter"); ?></h4>                  
                			</div>  	
							<?php
							echo $helpers['Form']->input('txt_newsletter', _('Texte page newsletter'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte de la page newsletter")));
							echo $helpers['Form']->input('txt_after_newsletter', _('Mentions après le formulaire'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte qui sera affiché après le formulaire")));
							?><h5 class="form-title"><?php echo _("Email de confirmation"); ?></h5><?php
							echo $helpers['Form']->input('subject_mail_newsletter', _("Sujet de l'email"), array('tooltip' => _("Indiquez le sujet de l'email")));							
							echo $helpers['Form']->input('txt_mail_newsletter', _("Contenu de l'email"), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
							?>
                		</div>
				    	<div class="tab-pane" id="txtemailsuser">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-envelope"></i> <?php echo _("Textes/emails du formulaire d'inscription d'un nouvel utilisateur"); ?></h4>                  
                			</div>
							<?php
							echo $helpers['Form']->input('txt_after_form_user', _('Mentions après le formulaire'), array('type' => 'textarea', 'wysiswyg' => true,  'tooltip' => _("Indiquez le texte qui sera affiché après le formulaire")));
							?><h5 class="form-title"><?php echo _("Inscription utilisateur"); ?></h5><?php
							echo $helpers['Form']->input('subject_mail_inscription_user', _("Sujet de l'email après l'inscription"), array('tooltip' => _("Indiquez le sujet de l'email")));
							echo $helpers['Form']->input('txt_mail_inscription_user', _("Contenu de l'email après l'inscription"), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
							echo $helpers['Form']->input('txt_confirm_inscription_user', _("Texte affiché après l'inscription"), array('tooltip' => _("Indiquez le sujet de l'email")));
							?><h5 class="form-title"><?php echo _("Validation utilisateur"); ?></h5><?php
							echo $helpers['Form']->input('subject_mail_validated_user', _("Sujet de l'email lorsque l'inscription est validée"), array('tooltip' => _("Saisissez le message")));
							echo $helpers['Form']->input('txt_mail_validated_user', _("Contenu de l'email lorsque l'inscription est validée"), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
							echo $helpers['Form']->input('txt_confirm_validated_user', _("Texte affiché après l'inscription (Validation automatique)"), array('tooltip' => _("Saisissez le message")));
							?><h5 class="form-title"><?php echo _("Refus utilisateur"); ?></h5><?php
							echo $helpers['Form']->input('subject_mail_refused_user', _("Sujet de l'email lorsque l'inscription refusée"), array('tooltip' => _("Indiquez le sujet de l'email")));
							echo $helpers['Form']->input('txt_mail_refused_user', _("Contenu de l'email lorsque l'inscription refusée"), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
							?><h5 class="form-title"><?php echo _("Récupération du mot de passe"); ?></h5><?php
							echo $helpers['Form']->input('subject_mail_lost_password_user', _("Sujet de l'email de récupération du mot de passe"), array('tooltip' => _("Indiquez le sujet de l'email")));
							echo $helpers['Form']->input('txt_mail_lost_password_user', _("Contenu de l'email de récupération du mot de passe"), array('type' => 'textarea', 'wysiswyg' => true, 'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
							echo $helpers['Form']->input('txt_confirm_lost_password_user', _("Texte affiché après la demande"), array('tooltip' => _("Saisissez le message")));
							?>
                		</div>
				    	<div class="tab-pane" id="contactpage">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-map-marker"></i> <?php echo _("Page contact"); ?></h4>                  
                			</div>  	
							<?php 
							echo $helpers['Form']->input('contact_map_activ', _('Activer la carte dans la page contact'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer la carte")));
							if(isset($categoriesList)) { echo $helpers['Form']->input('contact_map_page', _('Page contact'), array('type' => 'select', 'datas' => $categoriesList, 'firstElementList' => _('Sélectionnez la page contact'), 'tooltip' => _("Indiquez la page contact"))); } 
							else { echo _("Vous pourrez sélectionner la page contact une fois que l'arborescence aura été créée.")."<br /><br />"; }
							echo $helpers['Form']->input('contact_map_address', _('Adresse'), array('tooltip' => _("Indiquez l'adresse à afficher sur la carte")));
							echo $helpers['Form']->input('contact_map_lat', _('Latitude'), array('tooltip' => _("Indiquez la latitude")));
							echo $helpers['Form']->input('contact_map_lng', _('Longitude'), array('tooltip' => _("Indiquez la longitude")));							
							?>
                		</div>
				    	<div class="tab-pane" id="postsconfigs">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-comments"></i> <?php echo _("Configurations articles"); ?></h4>                  
                			</div>  	
							<?php
							echo $helpers['Form']->input('posts_display_by_default', _('Afficher par défaut dans ce site'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour indiquer si il faut (ou non) afficher, par défaut, les articles dans ce site")));
							if(isset($categoriesList)) { 
								$datas = $categoriesList;
								$firstElementList = _('Sélectionnez la page articles par défaut');
							} else {
								$datas = array();
								$firstElementList = _("Vous pourrez sélectionner la page articles par défaut une fois que l'arborescence du site sera créée");
							}
							echo $helpers['Form']->input('posts_default_page', _('Page articles par défaut'), array('type' => 'select', 'datas' => $datas, 'firstElementList' => $firstElementList, 'tooltip' => _("Indiquez la page articles par défaut")));
							
							$typeSearch = array(
								'large' => _("Recherche large (Affiche les articles ayant au moins une correspondance avec un type d'article sélectionné)"),
								'stricte' => _("Recherche stricte (Affiche les articles ayant toutes les correspondances avec les types d'articles sélectionnés)")
							);
							echo $helpers['Form']->input('posts_search', _('Type de recherche'), array('type' => 'select', 'datas' => $typeSearch, 'tooltip' => _("Sélectionnez le type de recherche associée aux types d'articles pour l'affichage des articles")));			
							$orderType = array(						
								'created' => _("La date de création"),
								'modified' => _("La date de modification"),
								'order_by' => _("Le rang")
							);
							echo $helpers['Form']->input('posts_order', _('Trier par'), array('type' => 'select', 'datas' => $orderType, 'tooltip' => _("Indiquer le tri à mettre en place pour l'affichage des articles dans les pages")));
							echo $helpers['Form']->input('posts_home_page_default', _('Diffuser par défaut sur la home page'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser cet article par défaut sur la home page")));
							echo $helpers['Form']->input('posts_home_page_limit', _('Limite articles home page'), array('tooltip' => _("Indiquez ici le nombre d'articles maximum à récupérer sur la page d'accueil")));														
							?>
                		</div>
				    	<div class="tab-pane" id="slidesconfigs">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-exchange"></i> <?php echo _("Configurations slides"); ?></h4>                  
                			</div>  	
							<?php
							echo $helpers['Form']->input('slide_display_by_default', _('Afficher par défaut dans ce site'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour indiquer si il faut (ou non) afficher, par défaut, les slides dans ce site")));
							echo $helpers['Form']->input('slide_home_page_default', _('Diffuser par défaut sur la home page'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce slide par défaut sur la home page")));
							?>
                		</div>
				    	<div class="tab-pane" id="focusconfigs">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-plus-square-o"></i> <?php echo _("Configurations focus"); ?></h4>                  
                			</div>  	
							<?php
							echo $helpers['Form']->input('focus_display_by_default', _('Afficher par défaut dans ce site'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour indiquer si il faut (ou non) afficher, par défaut, les focus dans ce site")));
							echo $helpers['Form']->input('focus_home_page_default', _('Diffuser par défaut sur la home page'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce focus par défaut sur la home page")));
							?>
                		</div>
				    	<div class="tab-pane" id="seo">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-search"></i> <?php echo _("SEO"); ?></h4>                  
                			</div>  	
							<?php 
							echo $helpers['Form']->input('seo_page_title', _('Meta title'), array('tooltip' => _("Résumé de la page html, 160 caractères maximum recommandé")));
							echo $helpers['Form']->input('seo_page_description', _('Meta description'), array('tooltip' => _("Résumé de la page html, 160 caractères maximum recommandé")));
							echo $helpers['Form']->input('seo_page_keywords', _('Meta keywords'), array('tooltip' => _("Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (Optionnel)")));		
							?>
                		</div>
				    	<div class="tab-pane" id="options">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-plug"></i> <?php echo _("Options"); ?></h4>                  
                			</div>  	
							<?php
							echo $helpers['Form']->upload_files('favicon', array('label' => _("Icône du site")));
							echo $helpers['Form']->input('hook_filename', _('Nom du fichier hooks'), array('tooltip' => _("Indiquez ici le nom du du fichier hooks, ce nom sera recherché dans les dossiers /configs/hooks/* (Si plusieurs fichiers les séparer par un ;)")));
							echo $helpers['Form']->input('robots_file', _('Texte fichier robots.txt'), array('type' => 'textarea', 'tooltip' => _("Indiquez ici le texte à afficher dans le fichier robots.txt.")));
							?>                			
                		</div>
				    	<div class="tab-pane" id="secure">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-minus-circle"></i> <?php echo _("Sécurité"); ?></h4>                  
                			</div>  	
							<?php 
							$txtSecure = _('Sécuriser le site').' <i>'._('Seuls les utilisateurs enregistrés pourront se connecter.').' <a href="'.Router::url('backoffice/users/index').'">'._("Ajouter un utilisateur").'</a></i>';
							echo $helpers['Form']->input('secure_activ', $txtSecure, array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer la sécurité sur le site")));			
							$txtLog = _("Logger les utilisateurs").'. <i>'._("Attention cette option ne fonctionne que dans le cas de sites sécurisés").".</i>";
							echo $helpers['Form']->input('log_users_activ', $txtLog, array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer le log des utilisateurs. La mise en place de cette option peut ralentir l'affichage des pages")));
							echo $helpers['Form']->input('moderate_new_users', _("Modérer les nouveaux utilisateurs frontoffice"), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour activer la modération sur les nouveaux utilisateurs frontoffice")));
							?>                			
                		</div>
				    	<div class="tab-pane" id="googleanalytics">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-line-chart"></i> <?php echo _("Google Analytics"); ?></h4>                  
                			</div>  	
							<?php 
							//echo $helpers['Form']->input('ga_login', _('Google analytics Login'), array('tooltip' => _("Indiquez ici votre identifiant de connexion à Google Analytics")));
							//echo $helpers['Form']->input('ga_password', _('Google analytics Password'), array('tooltip' => _("Indiquez ici votre mot de passe de connexion à Google Analytics")));
							//echo $helpers['Form']->input('ga_id', _('Google analytics ID'), array('tooltip' => _("Indiquez ici l'ID du profil Google Analytics (Disponible les paramètres du profil)")));
							echo $helpers['Form']->input('ga_code', _('Code Google Analytics'), array('type' => 'textarea', 'tooltip' => _("Indiquez ici le code de suivi Google Analytics"), 'rows' => 20, 'cols' => 30));			
							?>
                		</div>
				    	<div class="tab-pane" id="connect">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-lock"></i> <?php echo _("Page de connexion"); ?></h4>                  
                			</div>  
							<?php 
							echo $helpers['Form']->upload_files('connect_background', array('label' => _("Image de fond")));
							echo $helpers['Form']->input('connect_logo', _('Logo'), array('type' => 'textarea', 'toolbar' => 'image', 'wysiswyg' => true,  'tooltip' => _("Sélectionnez votre logo à l'aide de l'explorateur de fichier")));
							echo $helpers['Form']->input('connect_text', _('Texte'), array('type' => 'textarea', 'wysiswyg' => true));	
							echo $helpers['Form']->upload_files('connect_css_file', array('label' => _("Ficher CSS complémentaire")));		
							echo $helpers['Form']->upload_files('connect_js_file', array('label' => _("Ficher JS complémentaire")));		
							?>
                		</div>
				    	<div class="tab-pane" id="cssjs">	
				    		<div class="box-header bg-light-blue">
								<h4><i class="fa fa-code"></i> <?php echo _("CSS & JS"); ?></h4>                  
                			</div>  	
							<?php	
							echo $helpers['Form']->upload_files('css_hack_file', array('label' => _("Ficher CSS complémentaire")));		
							echo $helpers['Form']->input('scan_css_hack_file_folder', _('Scanner le dossier pour charger les fichiers CSS'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour charger l'ensemble des fichiers contenu dans le dossier")));
							
							echo $helpers['Form']->upload_files('js_hack_file', array('label' => _("Ficher JS complémentaire")));
							echo $helpers['Form']->input('scan_js_hack_file_folder', _('Scanner le dossier pour charger les fichiers JS'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour charger l'ensemble des fichiers contenu dans le dossier")));
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