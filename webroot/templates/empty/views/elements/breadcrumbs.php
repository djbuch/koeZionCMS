<div class="breadcrumbs_element">
	<p>
		<a href="<?php echo Router::url('home/index'); ?>"><?php echo _("Accueil"); ?></a>
		<?php 
		if(isset($breadcrumbs)) {
			foreach($breadcrumbs as $breadcrumbsKey => $breadcrumbsValue) { 
			
				?><span>&raquo;</span><a href="<?php echo Router::url('categories/view/id:'.$breadcrumbsValue['id'].'/slug:'.$breadcrumbsValue['slug']); ?>"><?php echo $breadcrumbsValue['name']; ?></a><?php 
			} 
		}
		
		//Affichage d'un article
		if(isset($breadcrumbsPost)) {
			foreach($breadcrumbsPost as $breadcrumbsKey => $breadcrumbsValue) { 
			
				?><span>&raquo;</span><a href="<?php echo Router::url('posts/view/id:'.$breadcrumbsValue['id'].'/slug:'.$breadcrumbsValue['slug'].'/prefix:'.$breadcrumbsValue['prefix']); ?>"><?php echo $breadcrumbsValue['name']; ?></a><?php 
			} 
		}
			
		//Autres liens
		if(isset($otherBreadcrumbs)) {
			foreach($otherBreadcrumbs as $otherBreadcrumbsKey => $otherBreadcrumbsValue) { 
			
				?><span>&raquo;</span><a href="<?php echo $otherBreadcrumbsValue['url']; ?>"><?php echo $otherBreadcrumbsValue['name']; ?></a><?php 
			} 
		}
		?>
	</p>
</div>