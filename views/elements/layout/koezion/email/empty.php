<html>
	<head>
		<?php if(isset($cssHack) && !empty($cssHack)) { ?>
			<style type="text/css">
				<?php echo $cssHack; ?>
			</style>
		<?php } ?>
	</head>
	<body>
		<?php echo $messageContent; ?>
	</body>
</html>