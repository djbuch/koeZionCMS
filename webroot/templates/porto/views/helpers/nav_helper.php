<?php
class NavHelper extends Helper {
	
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
 * @version 0.3 - 07/07/2014 by FI	Gestion des redirections externes dans le champ redirect_to
 */	
	public function generateMenu($categories, $breadcrumbs, $moreElements = null, $level) {
		
		if(count($categories) > 0) {		
			
			if(!empty($breadcrumbs)) { $iParentCategory = $breadcrumbs[0]['id']; } else { $iParentCategory = 0; }
			
			if($level == 1) { ?><ul class="nav nav-pills nav-main" id="mainMenu"><?php }
			else { ?><ul class="dropdown-menu"><?php }
				
			foreach($categories as $k => $v) {
					
				$isActive = '';
				if(!empty($v['redirect_to'])) { 
					
					//On teste s'il s'agit d'une url interne ou externe
					if(substr_count($v['redirect_to'], 'http://', 0, 7)) { $url = $v['redirect_to']; }
					else {
					
						$url = Router::url($v['redirect_to']);
						$shortUrl = str_replace(BASE_URL, '', $url); //On supprime le BASE_URL avant la comparaison
						if($v['level'] == 1 && $shortUrl == $this->view->controller->request->url) { $isActive = ' active'; }
					}
					
				} else { 
					
					$url = Router::url('categories/view/id:'.$v['id'].'/slug:'.$v['slug']); 
					if($v['level'] == 1 && $iParentCategory == $v['id']) { $isActive = ' active'; }
				}	
			
				if($level == 1 && isset($v['children'])) { ?><li class="dropdown <?php echo $isActive; ?>"><?php }
				else if($level == 1 && !isset($v['children'])) { ?><li><?php }
				else if($level == 2 && isset($v['children'])) { ?><li class="dropdown-submenu"><?php }
				else { ?><li><?php }
				
				if($level == 1 && isset($v['children'])) { ?><a class="dropdown-toggle" href="<?php echo $url; ?>"><?php echo $v['name']; ?> <i class="fa fa-angle-down"></i></a><?php }
				else { ?><a href="<?php echo $url; ?>"><?php echo $v['name']; ?></a><?php }
				 
					if(isset($v['children'])) { 
						
						$this->generateMenu($v['children'], $breadcrumbs, null, $level+1); 
					} 
					?>
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