<?php if(!empty($pageIllustrationBis)) { ?>
	<section class="page_footer_element">
		<?php if(!empty($pageIllustrationBis)) { ?>
			<div class="illustration">
				<img src="<?php echo $pageIllustrationBis; ?>" alt="<?php echo $pageTitle; ?>" />
			</div>
		<?php } ?>
		<?php if(!empty($subtitle2)) { ?>
			<div class="container">
				<div class="subtitle"><?php echo $subtitle2; ?></div>
			</div>
		<?php } ?>
	</section>
<?php } ?>