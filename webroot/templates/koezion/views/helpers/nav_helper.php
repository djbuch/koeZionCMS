<?php
class NavHelper extends Helper {
	
	public function generate_menu($categories, $breadcrumbs, $level = 1, $moreElements = null) {
		
		if(count($categories) > 0) {
			
			if(!empty($breadcrumbs)) { $iParentCategory = $breadcrumbs[0]['id']; } else { $iParentCategory = 0; }
						
			if($level == 1) { ?><ul class="nav navbar-nav"><?php }
			else { ?><ul class="dropdown-menu"><?php }
				
				///////////////////////////////
				//    AFFICHAGE DES MENUS    //
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
				
					?><li><?php
					
					if(isset($v['children'])) { ?><a href="<?php echo $url; ?>"><?php echo $v['name']; ?> <span class="caret"></span></a><?php }
					else { ?><a href="<?php echo $url; ?>"><?php echo $v['name']; ?></a><?php }
					 
						///////////////////////
						//    RECURSIVITE    //
						if(isset($v['children'])) { 
							
							$this->generate_menu($v['children'], $breadcrumbs, $level+1); 
						} 
						?>
					</li>
					<?php
				}
	
				///////////////////////////////////////////////
				//    AFFICHAGE DES MENUS COMPLEMENTAIRES    //
				if(isset($moreElements) && !empty($moreElements)) { 
					
					foreach($moreElements as $k => $v) {
						
						if(isset($v['content'])) { echo $v['content']; }
						else {
							
							?>
							<li<?php if(isset($v['class'])) { ?> class="<?php echo $v['class']; ?>"<?php } ?>><a<?php if(isset($v['link'])) { ?> href="<?php echo $v['link']; ?>"<?php } ?>><?php echo $v['name']; ?></a></li>
							<?php 
						}
					}
				}
			?></ul><?php
		}
	}
}