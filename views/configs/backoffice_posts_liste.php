<div class="section">
	<div class="box">
		<div class="title"><h2><?php echo _("Paramètres des articles"); ?></h2></div>
		<div class="content nopadding">
			<?php 			
			echo $helpers['Form']->create(array('id' => 'ConfigPosts', 'action' => Router::url('backoffice/configs/posts_liste'), 'method' => 'post')); 
				$typeSearch = array(
					'large' => _("Recherche large (Affiche les articles ayant au moins une correspondance avec un type d'article sélectionné)"),
					'stricte' => _("Recherche stricte (Affiche les articles ayant toutes les correspondances avec les types d'articles sélectionnés)")
				);
				echo $helpers['Form']->input('search', _('Type de recherche'), array('type' => 'select', 'datas' => $typeSearch, 'tooltip' => _("Sélectionnez le type de recherche associée aux types d'articles pour l'affichage des articles")));

				$orderType = array(						
					'created' => _("La date de création"),
					'modified' => _("La date de modification"),
					'order_by' => _("Le rang")
				);
				echo $helpers['Form']->input('order', _('Trier par'), array('type' => 'select', 'datas' => $orderType, 'tooltip' => _("Indiquer le tri à mettre en place pour l'affichage des articles dans les pages")));
				
				echo $helpers['Form']->input('home_page_limit', _('Limite articles home page'), array('tooltip' => _("Indiquez ici le nombre d'articles maximum à récupérer sur la page d'accueil")));
				
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>