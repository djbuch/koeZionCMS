<div class="gs_4 omega sidebar">

	<?php if(isset($children) && count($children) > 0) { ?>
	<div class="widget">				
		<?php if(isset($category['title_children'])) { ?><h2 class="widgettitle"><?php echo $category['title_children']; ?></h2><?php } ?>
		<ul class="nobordertop">
			<?php 
			foreach($children as $k => $v) {

				$classLi = '';
				if($category['id'] == $v['id']) { $classLi = ' class="selected"'; }
				?><li<?php echo $classLi; ?>><a href="<?php echo Router::url('categories/view/id:'.$v['id'].'/slug:'.$v['slug']); ?>"><?php echo $v['name']; ?></a></li><?php 
			} 
			?>
		</ul>
	</div>
	<?php } ?>

	<?php if(isset($brothers) && count($brothers) > 0) { ?>
	<div class="widget">		
		<?php foreach($brothers as $columnTitle => $brothersValues) { ?>
						
			<?php if(!empty($columnTitle)) { ?><h2 class="widgettitle"><?php echo $columnTitle; ?></h2><?php } ?>
			<ul class="nobordertop">
				<?php 
				foreach($brothersValues as $k => $v) {
	
					$classLi = '';
					if($category['id'] == $v['id']) { $classLi = ' class="selected"'; }
					?><li<?php echo $classLi; ?>><a href="<?php echo Router::url('categories/view/id:'.$v['id'].'/slug:'.$v['slug']); ?>"><?php echo $v['name']; ?></a></li><?php 
				} 
				?>
			</ul>
		<?php } ?>
	</div>
	<?php } ?>

	<?php 
	if(isset($postsTypes) && count($postsTypes) > 0) { ?>
		<div class="widget widget2">
			<?php foreach($postsTypes as $columnTitle => $postsTypesValues) { ?>
				<h2 class="widgettitle"><?php echo $columnTitle; ?></h2>
				<ul>
					<?php				
					foreach($postsTypesValues as $postsTypesId => $postsTypesName) {

						//On va contrôler si un (ou plusieurs) type(s) d'article(s) est(sont) passé(s) en get
						$typepost = array(); //Variable qui va contenir les types de posts passés dans l'url
						//Si des types de posts sont passés on va les stocker dans un tableau
						if(isset($this->controller->request->data['typepost']) && $this->controller->request->data['typepost']) { $typepost = explode(',', $this->controller->request->data['typepost']); }
																	
						//Allumage de la ligne si l'identifiant du type de post est dans le tableau
						$classLi = '';
						if(in_array($postsTypesId, $typepost)) { $classLi = ' class="selected"'; }
						
						if(!in_array($postsTypesId, $typepost)) { $typepost[] = $postsTypesId; } else { $typepost = array_diff($typepost, array($postsTypesId)); }	

						if(count($typepost) > 0) { $urlParams = "?typepost=".implode(',', $typepost); }
						else { $urlParams = ''; }
						
						//Contrôle de la route à mettre en place
						if($this->params['controllerName'] == 'Categories') { 
							$postsRoute = Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']).$urlParams; 
						} else { 
							$postsRoute = Router::url('posts/listing').$urlParams; 
						}
						?>
						<li<?php echo $classLi; ?>><a href="<?php echo $postsRoute; ?>"><?php echo $postsTypesName; ?></a></li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
	<?php } ?>
</div>