<?php 
if($flashMessage) { ?>
	<div class="box">
		<div class="content">
			<div class="system <?php echo $flashMessage['type']; ?>"><?php echo $flashMessage['message']; ?></div>
		</div>
	</div>
<?php } ?>