<div class="row">
	<div class="col-md-12">
		<ul class="breadcrumb">
	        <li><a href="<?php echo Router::url('home/index'); ?>"><?php echo _("Accueil"); ?></a></li>
	        <?php 
	      	if(isset($breadcrumbs)) {
				
				$currentElement = 1;
				$nbElements = count($breadcrumbs);
				foreach($breadcrumbs as $breadcrumbsKey => $breadcrumbsValue) { 
						
					$last = false;
					if($currentElement == $nbElements) { $last = true; }
					
					?><li<?php if($last) { ?> class="active"<?php } ?>><?php						
						if(!$last) { ?><a href="<?php echo Router::url('categories/view/id:'.$breadcrumbsValue['id'].'/slug:'.$breadcrumbsValue['slug']); ?>"><?php } 
							echo strip_tags($breadcrumbsValue['name']); 
						if(!$last) { ?></a><?php } 
					?></li><?php
					$currentElement++;
				} 
			}
			
			//Affichage d'un article
			if(isset($breadcrumbsPost)) {
				
				$currentElement = 1;
				$nbElements = count($breadcrumbsPost);
				foreach($breadcrumbsPost as $breadcrumbsKey => $breadcrumbsValue) { 
						
					$last = false;
					if($currentElement == $nbElements) { $last = true; }
					
					?><li<?php if($last) { ?> class="active"<?php } ?>><?php						
						if(!$last) { ?><a href="<?php echo Router::url('posts/view/id:'.$breadcrumbsValue['id'].'/slug:'.$breadcrumbsValue['slug'].'/prefix:'.$breadcrumbsValue['prefix']); ?>"><?php } 
							echo strip_tags($breadcrumbsValue['name']); 
						if(!$last) { ?></a><?php } 
					?></li><?php
					$currentElement++;
				} 
			}
			?>
		</ul>
	</div>
</div>