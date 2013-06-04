<?php
class Nav {
	
/**
 * Cette fonction est utilisée pour générer le menu frontoffice
 * Cette fonction est récursive
 * Elle ne retourne aucune données
 *
 * @param array $categories		Liste des catégories qui composent le menu
 * @param array $breadcrumbs	Fil d'ariane utilisé pour sélectionner la catégorie courante
 * @param array $moreElements	Permet de rajouter des menus qui ne sont pas dans la table des catégories
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 28/05/2013 by TB	ajout de l'id "mainMenu" pour la liste du menu
 */	
	public function generateMenu($categories, $breadcrumbs, $moreElements = null) {
		
		if(count($categories) > 0) {		
			
			if(!empty($breadcrumbs)) { $iParentCategory = $breadcrumbs[0]['id']; } else { $iParentCategory = 0; }
			?><ul id="mainMenu"><?php			
			foreach($categories as $k => $v) {
				
				?>
				<li>
					<?php if($v['level'] == 1 && $iParentCategory == $v['id']) { $sCssMenu = ' class="menu_selected_bg"'; } else { $sCssMenu = ''; } ?>
					<a href="<?php echo Router::url('categories/view/id:'.$v['id'].'/slug:'.$v['slug']); ?>"<?php echo $sCssMenu; ?>><?php echo $v['name']; ?></a>
					<?php if(isset($v['children'])) { $this->generateMenu($v['children'], $breadcrumbs); }; ?>
				</li>
				<?php
			}

			if(isset($moreElements) && !empty($moreElements)) { 
				
				foreach($moreElements as $k => $v) {
					?>
					<li<?php if(isset($v['class'])) { ?> class="<?php echo $v['class']; ?>"<?php } ?>><a href="<?php echo $v['link']; ?>"><?php echo $v['name']; ?></a></li>
					<?php 
				}
			}
			?></ul><?php
		}		
	}
}