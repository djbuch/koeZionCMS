<?php 
if(isset($sliders) && count($sliders) > 0) {
	
	$nbSlides = count($sliders);
	?>
	<div id="slide_element">
		<div id="carousel" class="carousel slide" data-ride="carousel">
		
			<?php if($nbSlides) { ?>
				<ol class="carousel-indicators">
					<?php for($slideNum=0;$slideNum<$nbSlides;$slideNum++) { ?>
						<li data-target="#koezion_slide" data-slide-to="<?php echo $slideNum; ?>" class="active"></li>
					<?php } ?>
				</ol>
			<?php } ?>
			
			<div class="carousel-inner" role="listbox">
				<?php
				$isActiv = 'active'; 
				foreach($sliders as $slide) { 
					
					?>
					<div class="item <?php echo $isActiv; ?>">
						<?php 
						echo $slide['image']; 
						if(!empty($slide['content'])) { 
							
							?><div class="carousel-caption"><?php echo $slide['content']; ?></div><?php 
						}
						?>
					</div>
					<?php 
					if(!empty($isActiv)) { $isActiv = ''; }
				} 
				?>
			</div>
			
			<?php if($nbSlides) { ?>
				<a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only"><?php echo _("Slide précédent"); ?></span>
				</a>
				<a class="right carousel-control" href="#carousel" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only"><?php echo _("Slide suivant"); ?></span>
				</a>
			<?php } ?>
		</div>
	</div>
	<?php 
}