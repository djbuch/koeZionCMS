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
		echo $helpers['Form']->input('category_id', 'Catégorie parente', array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => "Indiquez la catégorie parente de cet article, c'est à partir de cette catégorie que cet article sera accessible", 'firstElementList' => "Sélectionnez une catégorie"));
		echo $helpers['Form']->input('name', "<i>(*)</i> Titre de l'article", array('tooltip' => "Indiquez le titre de l'article. Ce champ sera utilisé comme titre de page dans les moteurs de recherche, 70 caractères maximum recommandé"));
		echo $helpers['Form']->input('online', 'En ligne', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour diffuser cet article"));
		?>		
		</div>
		<div class="content nopadding">
			<?php echo $helpers['Form']->input('publication_date', 'Date de publication', array("class" => "datepicker", "placeholder" => "dd.mm.yy", 'tooltip' => "Indiquez la date à laquelle cet article sera publié")); ?>
			<p style="padding:0 20px 0 20px;margin-bottom:5px">Cette option vous permet de définir la date à laquelle sera publié l'article en utilisant une tâche <a href="http://fr.wikipedia.org/wiki/Cron" target="_blank">CRON</a></p>
			<p style="padding:0 20px 0 20px;margin-bottom:5px">Vous pouvez utiliser des services CRON gratuits comme par exemple <a href="http://www.cronoo.com/" target="_blank">Cronoo</a></p>			
			<?php 
			$websiteUrl = Session::read('Backoffice.Websites.details.'.CURRENT_WEBSITE_ID.'.url');
			
			require_once(LIBS.DS.'config_magik.php');
			$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'security_code.ini', true, false);
			$updateCode = $cfg->keys_values();
			
			if(empty($updateCode['security_code'])) {
				
				?><p style="padding:0 20px 0 20px;margin-bottom:5px">Pour utiliser cette fonctionnalité vous devez en premier lieu <a href="<?php echo Router::url('backoffice/configs/security_code_liste'); ?>">paramétrer le code de sécurité</a> utilisé pour pouvoir lancer cette procédure</p><?php 
			
			} else { 
			
				?>
				<p style="padding:0 20px 0 20px;margin-bottom:5px">Pour mettre en place la diffusion automatique vous pouvez utiliser l'url suivante <?php echo $websiteUrl; ?>/posts/update_publication_date.xml?update_code=<?php echo $updateCode['security_code']; ?></p>
				<p style="padding:0 20px 0 20px;margin-bottom:5px">Le type du format de retour est l'XML</p>
				<p style="padding:0 20px 0 20px;margin-bottom:5px">
				&lt;export&gt;<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&lt;result&gt;MISE A JOUR EFFECTUEE&lt;/result&gt;<br />
				&nbsp;&nbsp;&nbsp;&nbsp;&lt;message&gt;La mise à jour des dates de publications à été effectuée&lt;/message&gt;<br />
				&lt;/export&gt;
				</p>
				<div class="system warning" style="margin:0 20px 10px 20px">ATTENTION : si vous utilisez une date de publication vous ne devez pas cocher le champ En ligne, la tâche automatisée qui sera effectuée fera automatiquement la mise à jour de ce champ.</div>
				<?php 
			} 
			?>					
		</div>		
	</div>
	<div id="textes">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('short_content', 'Descriptif court', array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Saisissez ici le descriptif court de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider"));
			echo $helpers['Form']->input('redirect_to', 'Url de redirection', array('tooltip' => "Remplissez ce champ si souhaitez, à partir de cet article, faire une redirection vers une url de votre choix, il ne vous sera alors pas nécessaire de saisir le descriptif long"));			
			echo $helpers['Form']->input('content', 'Descriptif long', array('type' => 'textarea', 'wysiswyg' => true, 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge', 'tooltip' => "Saisissez ici le descriptif long de votre article, n'hésitez pas à utiliser les modèles de pages pour vous aider"));
			?>
		</div>
	</div>
	<div id="types">
		<div class="content nopadding">
			<?php echo $helpers['Form']->input('display_posts_types', "Afficher les types d'articles dans la colonne de droite", array('type' => 'checkbox', 'tooltip' => "En cliquant sur cette case les types d'articles seront affichés dans la colonne de droite")); ?>
			<div class="row" style="overflow:hidden;">
				<label>
					<?php echo $helpers['Html']->img('/backoffice/tooltip.png', array("original-title" => "Cochez les types d'article (Plusieurs choix possibles)", "class" => "tip-w", "style" => "float: left; margin-right: 5px; cursor: pointer;", "alt" => "tooltip")); ?>
					Type d'article
				</label>
				<div class="rowright">
					<?php 
					foreach($postsTypes as $k => $v) {
						?><span class="checkbox" style="float: left; display: block; margin: 0 20px 20px 0; width: 30%; line-height: 15px;"><?php
						echo $helpers['Form']->input('posts_type_id.'.$v['id'], $v['name'].' (<i>dans '.$v['column_title'].'</i>)', array('type' => 'checkbox', 'div' => false));
						?></span><?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div id="right_column">
		<div class="content nopadding">
			<?php
			echo $helpers['Form']->input('title_colonne_droite', 'Titre colonne de droite', array('tooltip' => "Indiquez le titre qui sera affiché dans la colonne de droite"));			
			echo $helpers['Form']->input('display_children', 'Afficher les pages enfants dans la colonne de droite', array('type' => 'checkbox', 'tooltip' => "En cliquant sur cette case les enfants de la page seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite"));
			echo $helpers['Form']->input('display_brothers', 'Afficher les pages du même niveau dans la colonne de droite', array('type' => 'checkbox', 'tooltip' => "En cliquant sur cette case les pages du même niveau seront affichés dans la colonne de droite, ATTENTION pensez à saisir le champ titre de la colonne de droite"));
			?>
		</div>
	</div>
	<div id="buttons">
		<div class="content nopadding">
			<div class="content">
			<p>Précisez ici le ou les boutons à rajouter à cet article.</p>
			<?php echo $helpers['Form']->input('rightButtonsListId', '', array('type' => 'select', 'datas' => $rightButton, 'label' => false, 'div' => false, 'displayError' => false, 'firstElementList' => "Sélectionnez un bouton")); ?>
			<a id="addRightButton" class="btn blue" style="cursor:pointer;margin-bottom:3px;"><span><?php echo _("Rajouter ce bouton"); ?></span></a>
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
			echo $helpers['Form']->input('prefix', "<i>(*)</i> Préfixe d'url", array('value' => POST_PREFIX, 'tooltip' => "Indiquez le préfixe à utiliser pour accéder à cet article (Le préfixe est situé juste avant l'url et vous permet de rajouter un ou deux mots clés dans l'url) <br />ATTENTION n'utiliser que des caractères autorisés pour le préfixe (Que des lettres, des chiffres et -)")); //Voir dans le fichier routes.php pour l'initialisation		
			echo $helpers['Form']->input('slug', 'Url', array('tooltip' => "Indiquez l'url que vous souhaitez mettre en place. ATTENTION n'utiliser que des caractères autorisés pour l'url (Que des lettres, des chiffres et -)"));
			echo $helpers['Form']->input('page_title', 'Meta title', array('tooltip' => "Titre de la page (70 caractères maximum recommandé, par défaut ce champ aura pour valeur le champ Titre)"));
			echo $helpers['Form']->input('page_description', 'Meta description', array('tooltip' => "Résumé de la page html, 160 caractères maximum recommandé"));
			echo $helpers['Form']->input('page_keywords', 'Meta keywords', array('tooltip' => "Liste des mots-clés de la page html séparés par une virgule, 10-20 mots-clés maximum (optionnel)"));
			?>
		</div>
	</div>
	<div id="options">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('display_link', "Afficher un lien sous forme de bouton à la suite de l'article", array('type' => 'checkbox', 'tooltip' => "En cochant cette case vous afficherez automatiquement le lien pour se rendre sur le détail de l'article, par défaut le titre de l'article sera également cliquable"));						
			echo $helpers['Form']->input('display_home_page', "Afficher cet article sur la la page d'accueil", array('type' => 'checkbox', 'tooltip' => "En cochant cette case vous afficherez cet article sur la page d'accueil du site"));

			if(!isset($formulaires)) { $formulaires = array (2 => 'Formulaire commentaire article'); } 
			echo $helpers['Form']->input('display_form', 'Formulaire', array('type' => 'select', 'datas' => $formulaires, 'tooltip' => "Indiquez le formulaire que vous souhaitez afficher sur la page", 'firstElementList' => "Sélectionnez un formulaire"));					
			?>
		</div>		
	</div>
	<?php if($isSecure) { ?>			
		<div id="emailing">
			<div class="content nopadding">
				<?php 
				echo $helpers['Form']->input('send_mail', "Envoyer un email pour informer les utilisateurs de l'ajout (ou de la modification)", array('type' => 'checkbox', 'tooltip' => "En cochant cette case un email sera automatiquement envoyer à l'ensemble des utilisateurs référencés dans le système"));
				echo $helpers['Form']->input('message_mail', 'Contenu email newsletter', array('type' => 'textarea', 'rows' => 5, 'cols' => 10, 'wysiswyg' => true,  'class' => 'xxlarge', 'tooltip' => "Indiquez le texte qui sera envoyé par email"));
				?>
			</div>
		</div>
	<?php } ?>
</div>
<script type="text/javascript">			
	$(document).ready(function() {			

		var addedButton = [];
		
		<?php 
		if(isset($this->controller->request->data['right_button_id'])) {

			foreach($this->controller->request->data['right_button_id'] as $rightButtonId => $isActiv) {
				
				?>addedButton.push("<?php echo $rightButtonId; ?>");<?php echo "\n\t";
			}
		}		
		?>		
		//Rajout d'une nouvelle option de question 
		$("#addRightButton").click(function() {
		
			var rightButton = $('#InputRightButtonsListId').val();
			
			if(rightButton.length) {
				
				if(jQuery.inArray(rightButton, addedButton) < 0) {
				
					var host = $.get_host(); //Récupération du host
					
					var action = host + 'posts/ajax_add_right_button/' + rightButton + '.html'; //On génère l'url			
					
					//On récupère les données en GET et on rajoute une nouvelle ligne
					$.get(action, function(datas) { $(datas).appendTo("#buttons"); });
					addedButton.push(rightButton);
				} else { jAlert("Ce bouton est déjà associé à cet article"); }
			} else { jAlert("Vous devez sélectionner un bouton"); }		
		});		
		
		$("#buttons").livequery(function() { 	

			$(this).sortable({
				items: '.sortable', 
				revert: true, 
				axis: 'y', 
				cursor: 'move'
			});
		});
	});
</script>