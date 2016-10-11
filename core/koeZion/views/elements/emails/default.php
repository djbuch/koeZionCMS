<html>
	<head>
		<?php if(isset($cssHack) && !empty($cssHack)) { ?>
			<style type="text/css">
				<?php echo $cssHack; ?>
			</style>
		<?php } ?>
	</head>
	<body>
		<?php 
		echo $messageContent; 
		if(isset($messageComplement) && !empty($messageComplement)) { echo $messageComplement; }
		?>
	</body>
</html>