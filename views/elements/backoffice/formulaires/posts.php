<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#textes"><?php echo _("Descriptifs court et long"); ?></a></li>
		<li><a href="#types"><?php echo _("Types d'article"); ?></a></li>
		<li><a href="#right_column"><?php echo _("Colonne de droite"); ?></a></li>
		<li><a href="#buttons"><?php echo _("Boutons"); ?></a></li>
		<li><a href="#seo"><?php echo _("SEO"); ?></a></li>
		<li><a href="#options"><?php echo _("Options"); ?></a></li>
		
		<?php 
		//On ne va afficher ce menu que si le site courant est sécurisé
		$websitesSession = Session::read('Backoffice.Websites'); //Récupération de la variable de session
		$currentWebsite = $websitesSession['current']; //Récupération du site courant
		$websiteDetails = $websitesSession['details'][$currentWebsite]; //Récupération du détail du site courant
		$isSecure = $websiteDetails['secure_activ']; //On va vérifier si celui-ci est sécurisé
		if($isSecure) {
			
			?><li><a href="#emailing"><?php echo _("Emailing"); ?></a></li><?php 
		}
		?>
	</ul>
	<div id="general">
		<div class="content nopadding">
		<?php 
		echo $helpers['Form']->input('category_id', _('Catégorie parente'), array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => "Indiquez la catégorie parente de cet article, c'est à partir de cette catégorie que cet article sera accessible", 'firstElementList' => "Sélectionnez une catégorie"));
		echo $helpers['Form']->input('name', _("Titre de l'article"), array('compulsory' => true, 'tooltip' => "Indiquez le titre de l'article. Ce champ sera utilisé comme titre de page dans les moteurs de recherche, 70 caractères maximum recommandé"));
		echo $helpers['Form']->input('dont_change_modified_date', _('Ne pas changer la date de modification'), array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour ne pas changer automatiquement la date de modification de l'article"));
		echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser cet article"));
		?>		
		</div>
		<div class="content nopadding">
			<?php echo $helpers['Form']->input('publication_date', _('Date de publication'), array("class" => "datepicker", "placeholder" => "dd.mm.yy", 'tooltip' => "Indiquez la date à laquelle cet article sera publié")); ?>
			<p style="padding:0 20px 0 20px;margin-bottom:5px"><?php echo _("Cette option vous permet de définir la date à laquelle sera publié l'article en utilisant une tâche"); ?> <a href="http://fr.wikipedia.org/wiki/Cron" target="_blank">CRON</a></p>
			<p style="padding:0 20px 0 20px;margin-bottom:5px"><?php echo _("Vous pouvez utiliser des services CRON gratuits comme par exemple"); ?> <a href="http://www.cronoo.com/" target="_blank">Cronoo</a></p>			
			<?php 
			$websiteUrl = Session::read('Backoffice.Websites.details.'.CURRENT_WEBSITE_ID.'.url');
			
			require_once(LIBS.DS.'config_magik.php');
			$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'security_code.ini', true, false);
			$updateCode = $cfg->keys_values();
			
			if(empty($updateCode['security_code'])) {
				
				?><p style="padding:0 20px 0 20px;margin-bottom:5px"><?php echo _("Pour utiliser cette fonctionnalité vous devez en premier lieu"); ?> <a href="<?php echo Router::url('backoffice/configs/security_code_liste'); ?>"><?php echo _("paramétrer le code de sécurité"); ?></a> <?php echo _("utilisé pour pouvoir lancer cette procédure"); ?></p><?php 
			
			} else { 
			
				?>
				<p style="padding:0 20px 0 20px;margin-bottom:5px"><?php echo _("Pour mettre en place la diffusion automatique vous pouvez utiliser l'url suivante"); ?> <?php echo $websiteUrl; ?>/posts/update_publication_date.xml?update_code=<?php echo $updateCode['security_code']; ?></p>
				<p style="padding:0 20px 0 20px;margin-bottom:5px"><?php echo _("Le type du format de retour est l'XML"); ?></p>
				<p style="padding:0 20px 0 20px;margin-bottom:5px">
				&lt;export&gt;<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&lt;result&gt;<?php echo _("MISE A JOUR EFFECTUEE"); ?>&lt;/result&gt;<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&lt;message&gt;<?php echo _("La mise à jour des dates de publications à été effectuée"); ?>&lt;/message&gt;<br />
				&lt;/export&gt;
				</p>
				<div class="system warning" style="margin:0 20px 10px 20px"><?php echo _("ATTENTION : si vous utilisez une date de publication vous ne devez pas cocher le champ En ligne, la tâche automatisée qui sera effectuée fera automatiquement la mise à jour de ce champ."); ?></div>
				<?php 
			} 
			?>					
		</div>		
	</div>
	<div id="textes">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('short_content', _('Descriptif court'), array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => _("Saisissez ici le descriptif court de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
			echo $helpers['Form']->input('redirect_to', _('Url de redirection'), array('tooltip' => _("Remplissez ce champ si souhaitez, à partir de cet article, faire une redirection vers une url de votre choix, il ne vous sera alors pas nécessaire de saisir le descriptif long")));			
			echo $helpers['Form']->input('content', _('Descriptif long'), array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => _("Saisissez ici le descriptif long de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider")));
			?>
		</div>
	</div>
	<div id="types">
		<div class="content nopadding">
			<?php echo $helpers['Form']->input('display_posts_types', _("Afficher les types d'articles dans la colonne de droite"), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les types d'articles seront affichés dans la colonne de droite"))); ?>
			<div class="row" style="overflow:hidden;">
				<label>
					<?php echo $helpers['Html']->img('/backoffice/tooltip.png', array("original-title" => _("Cochez les types d'article (Plusieurs choix possibles)"), "class" => "tip-w", "style" => "float: left; margin-right: 5px; cursor: pointer;", "alt" => "tooltip")); ?>
					Type d'article
				</label>
				<div class="rowright">
					<?php 
					//pr($postsTypes);
					foreach($postsTypes as $rubrique => $v) {
						?><h3><b><?php echo $rubrique; ?></b></h3><?php
						?><p style="overflow: hidden;"><?php 
						foreach($v as $postsType) {
							?><span class="checkbox" style="float: left; display: block; margin: 0 20px 20px 0; width: 30%; line-height: 15px;"><?php
							echo $helpers['Form']->input('posts_type_id.'.$postsType['id'], $postsType['name'], array('type' => 'checkbox', 'div' => false));
							?></span><?php
						}
						?></p><?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div id="right_column">
		<div class="content nopadding">
			<?php
			echo $helpers['Form']->input('title_colonne_droite', _('Titre colonne de droite'), array('tooltip' => _("Indiquez le titre qui sera affiché dans la colonne de droite")));			
			echo $helpers['Form']->input('display_children', _('Afficher les pages enfants dans la colonne de droite'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les enfants de la page seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite")));
			echo $helpers['Form']->input('display_brothers', _('Afficher les pages du même niveau dans la colonne de droite'), array('type' => 'checkbox', 'tooltip' => _("En cliquant sur cette case les pages du même niveau seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite")));
			?>
		</div>
	</div>
	<div id="buttons">
		<div class="content nopadding">
			<div class="content">
			<p><?php echo _("Précisez ici le ou les boutons à rajouter à cet article."); ?></p>
			<?php echo $helpers['Form']->input('rightButtonsListId', '', array('type' => 'select', 'datas' => $rightButton, 'label' => false, 'div' => false, 'displayError' => false, 'firstElementList' => _("Sélectionnez un bouton"))); ?>
			<a id="addRightButton" class="btn blue btnselect"><span><?php echo _("Rajouter ce bouton"); ?></span></a>
		</div>		
		<?php
		if(isset($this->controller->request->data['right_button_id'])) {
		
			foreach($this->controller->request->data['right_button_id'] as $rightButtonId => $isActiv) {
			
				$this->element('backoffice/right_button_line', array('rightButtonId' => $rightButtonId, 'rightButtonName' => $rightButton[$rightButtonId]));
			}
		}
		?>
		</div>
	</div>
	<div id="seo">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('prefix', _("Préfixe d'url"), array('compulsory' => true, 'value' => POST_PREFIX, 'tooltip' => _("Indiquez le préfixe à utiliser pour accéder à cet article (Le préfixe est situé juste avant l'url et vous permet de rajouter un ou deux mots clés dans l'url) <br />ATTENTION n'utiliser que des caractères autorisés pour le préfixe (Que des lettres, des chiffres et -)"))); //Voir dans le fichier routes.php pour l'initialisation		
			echo $helpers['Form']->input('slug', _('Url'), array('tooltip' => _("Indiquez l'url que vous souhaitez mettre en place. ATTENTION n'utiliser que des caractères autorisés pour l'url (Que des lettres, des chiffres et -)")));
			echo $helpers['Form']->input('page_title', _('Meta title'), array('tooltip' => _("Titre de la page (70 caractères maximum recommandé, par défaut ce champ aura pour valeur le champ Titre)")));
			echo $helpers['Form']->input('page_description', _('Meta description'), array('tooltip' => _("Résumé de la page html, 160 caractères maximum recommandé")));
			echo $helpers['Form']->input('page_keywords', _('Meta keywords'), array('tooltip' => _("Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (optionnel)")));
			?>
		</div>
	</div>
	<div id="options">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('display_link', _("Afficher un lien sous forme de bouton à la suite de l'article"), array('type' => 'checkbox', 'tooltip' => _("En cochant cette case vous afficherez automatiquement le lien pour se rendre sur le détail de l'article, par défaut le titre de l'article sera également cliquable")));						
			echo $helpers['Form']->input('display_home_page', _("Afficher cet article sur la la page d'accueil"), array('type' => 'checkbox', 'tooltip' => _("En cochant cette case vous afficherez cet article sur la page d'accueil du site")));

			if(!isset($formulaires)) { $formulaires = array (2 => _('Formulaire commentaire article')); } 
			echo $helpers['Form']->input('display_form', _('Formulaire'), array('type' => 'select', 'datas' => $formulaires, 'tooltip' => _("Indiquez le formulaire que vous souhaitez afficher sur la page"), 'firstElementList' => _("Sélectionnez un formulaire")));					
			echo $helpers['Form']->input('shooting_time', _("Durée de réalisation"), array('tooltip' => _("Indiquez la durée de réalisation de ce qui sera présenté dans cet article")));
			echo $helpers['Form']->input('img', _('image'), array('type' => 'file', 'class' => 'input-file', 'tooltip' => _("Cliquez sur le bouton pour télécharger votre image d'illustration. Attention : Mode RVB, Résolution 72dpi")));
			?>
		</div>		
	</div>
	<?php if($isSecure) { ?>			
		<div id="emailing">
			<div class="content nopadding">
				<?php 
				echo $helpers['Form']->input('send_mail', _("Envoyer un email pour informer les utilisateurs de l'ajout (ou de la modification)"), array('type' => 'checkbox', 'tooltip' => _("En cochant cette case un email sera automatiquement envoyer à l'ensemble des utilisateurs référencés dans le système")));
				echo $helpers['Form']->input('message_mail', _('Contenu email newsletter'), array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => _("Indiquez le texte qui sera envoyé par email")));
				?>
			</div>
		</div>
	<?php } ?>
</div>
<?php include_once(JS.DS.'backoffice'.DS.'buttons_right_column.php'); ?>