<div id="column_page_element" class="col-md-3">
	<?php 
	////////////////////////////////
	//    BOUTONS HAUT DE PAGE    // 
	//$rightButtons[1] va contenir les éléments à positionner en haut de la page
	if(isset($rightButtons[1]) && !empty($rightButtons[1])) {
	
		?>
		<div class="widget buttons top">
			<ul class="list-unstyled">
				<?php foreach($rightButtons[1] as $order => $rightButtonValues) { ?><li><?php echo $rightButtonValues['content']; ?></li><?php } ?>
			</ul>
		</div>	
		<?php 
	}
	
	/////////////////////////
	//    PAGES ENFANTS    // 		
	if(isset($children) && !empty($children)) {
		
		foreach($children as $columnTitle => $childrenValues) { 
			
			?>
			<div class="widget categories children">
				<?php if(!empty($columnTitle)) { ?><h5 class="widget_title"><?php echo $columnTitle; ?></h5><?php } ?>				
				<ul class="list-unstyled">
				<?php 
				foreach($childrenValues as $k => $v) {
					
					$classLi = '';
					if($category['id'] == $v['id']) { $classLi = ' class="active"'; }
					?><li<?php echo $classLi; ?>><a href="<?php echo Router::url('categories/view/id:'.$v['id'].'/slug:'.$v['slug']); ?>"><?php echo $v['name']; ?></a></li><?php 
				} 
				?>
				</ul>
			</div>		
			<?php 
		} 
	} 
	
	///////////////////////////////
	//    PAGE DE MÊME NIVEAU    //
	if(isset($brothers) && !empty($brothers)) {
		
		foreach($brothers as $columnTitle => $brothersValues) { 
			
			?>
			<div class="widget categories brothers">
				<?php if(!empty($columnTitle)) { ?><h5 class="widget_title"><?php echo $columnTitle; ?></h5><?php } ?>
				<ul class="list-unstyled">
				<?php 
				foreach($brothersValues as $k => $v) {
					
					$classLi = '';
					if($category['id'] == $v['id']) { $classLi = ' class="active"'; }
					?><li<?php echo $classLi; ?>><a href="<?php echo Router::url('categories/view/id:'.$v['id'].'/slug:'.$v['slug']); ?>"><?php echo $v['name']; ?></a></li><?php 
				} 
				?>
				</ul>
			</div>		
			<?php 
		} 
	} 
	
	///////////////////////////////
	//    BOUTONS BAS DE PAGE    // 
	//$rightButtons[0] va contenir les éléments à positionner en bas de la page
	if(isset($rightButtons[0]) && !empty($rightButtons[0])) {
	
		?>
		<div class="widget buttons bottom">
			<ul class="list-unstyled">
				<?php foreach($rightButtons[0] as $order => $rightButtonValues) { ?><li><?php echo $rightButtonValues['content']; ?></li><?php } ?>
			</ul>
		</div>	
		<?php 
	}
	 
	///////////////////////////
	//    TYPES D'ARTICLE    // 
	if(isset($postsTypes) && !empty($postsTypes)) {
		
		foreach($postsTypes as $columnTitle => $postsTypesValues) { 
		
			?>
			<div class="widget tags">
				<h5 class="widget_title"><?php echo $columnTitle; ?></h5>
                <div class="widget_content">			
					<?php				
					foreach($postsTypesValues as $postsTypesId => $postsTypesName) {
	
						//On va contrôler si un (ou plusieurs) type(s) d'article(s) est(sont) passé(s) en get
						$typepost = array(); //Variable qui va contenir les types de posts passés dans l'url
						//Si des types de posts sont passés on va les stocker dans un tableau
						if(isset($this->controller->request->data['typepost']) && $this->controller->request->data['typepost']) { $typepost = explode(',', $this->controller->request->data['typepost']); }
																	
						//Allumage de la ligne si l'identifiant du type de post est dans le tableau
						$classLi = '';
						if(in_array($postsTypesId, $typepost)) { $classLi = ' class="active"'; }
						
						if(!in_array($postsTypesId, $typepost)) { $typepost[] = $postsTypesId; } else { $typepost = array_diff($typepost, array($postsTypesId)); }	
	
						if(count($typepost) > 0) { $urlParams = "?typepost=".implode(',', $typepost); }
						else { $urlParams = ''; }
						
						//Contrôle de la route à mettre en place
						//if($this->params['controllerName'] == 'Categories') { 
							$postsRoute = Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']).$urlParams; 
						//} else { 
						//	$postsRoute = Router::url('posts/listing').$urlParams; 
						//}
						?>
						<a href="<?php echo $postsRoute; ?>" class="tag"><?php echo $postsTypesName; ?></a>
					<?php } ?>
				</div>
			</div>
			<?php 
		} 
	} 
	?>
</div>