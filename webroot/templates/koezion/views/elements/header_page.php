<?php if(!empty($pageTitle)) { ?>
	<section class="page_header_element">
		<?php if(!empty($pageIllustration)) { ?>
			<div class="illustration">
				<img src="<?php echo $pageIllustration; ?>" alt="<?php echo $pageTitle; ?>" />
			</div>
		<?php } ?>
		<div class="container">
			<div class="title">
				<h1><?php echo $pageTitle; ?></h1>
			</div>
			<?php if(!empty($subtitle1)) { ?><div class="subtitle"><?php echo $subtitle1; ?></div><?php } ?>
		</div>
	</section>
<?php } ?>