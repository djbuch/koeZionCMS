<?php 
$title_for_layout = $websiteParams['seo_page_title'];
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout = $websiteParams['seo_page_keywords'];
 
$this->element('frontoffice/nivo_slider'); 

if(isset($websiteParams['txt_slogan'])) { ?><div class="container_gamma slogan"><?php echo $websiteParams['txt_slogan']; ?></div><?php }

if(count($focus) > 0) { 
	
	?><div class="container_omega focus"><?php
	$cpt = 0; //Compteur total pour éviter d'avoir une div vide à la fin
	$i = 1;
	foreach($focus as $k => $v) {
		
		$separator = false; 
		$class = 'gs_3';		
		if($i == 4) { $separator = true; $class = 'gs_3 omega'; }
		?>		
		<div class="<?php echo $class; ?>">
			<?php /* ?><h3 class="widgettitle"><?php echo $v['name']; ?></h3><?php */ ?>
			<?php echo $v['content']; ?>
		</div>				
		<?php		
		$i++;
		$cpt++;
		if($separator && ($cpt != count($focus))) { 
			
			$i = 1;
			?></div><div class="container_omega"><?php 
		} 
	}
	?></div><?php
}

if(count($posts) > 0) {

	if(isset($websiteParams['txt_posts'])) { ?><div class="container_gamma slogan"><?php echo $websiteParams['txt_posts']; ?></div><?php }	
	?>
	<div class="container_omega">	
		<?php $this->element('frontoffice/posts_list', array('cssZone' => 'gs_12')); ?>
		<?php //$this->element('frontoffice/colonne_droite'); ?>
	</div>
	<?php 
} 
?>