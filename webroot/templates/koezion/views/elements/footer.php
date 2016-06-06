<footer>
	<div class="container">
    	<div class="row">
			<?php 
			if(!empty($websiteParams['footer_gauche'])) { ?><div class="f_left"><?php echo $websiteParams['footer_gauche']; ?></div><?php } 
			if(!empty($websiteParams['footer_centre'])) { ?><div class="f_center"><?php echo $websiteParams['footer_centre']; ?></div><?php } 
			if(!empty($websiteParams['footer_droite'])) { ?><div class="f_right"><?php echo $websiteParams['footer_droite']; ?></div><?php }
			?>    	
    	</div>
	</div>
	<div class="container">
    	<div class="row">
			<?php 			
			if(!empty($websiteParams['footer_social'])) { ?><div class="f_social"><?php echo $websiteParams['footer_social']; ?></div><?php }		
			if(!empty($websiteParams['footer_addthis'])) { ?><div class="f_addthis"><?php echo $websiteParams['footer_addthis']; ?></div><?php }
			?>
		</div>
	</div>
	<?php if(!empty($websiteParams['footer_bottom'])) { ?>
		<div class="f_bottom">
			<div class="container">
    			<div class="row">
					<?php echo $websiteParams['footer_bottom']; ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php echo GENERATOR_LINK; ?><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE VARIABLE ?>
</footer>