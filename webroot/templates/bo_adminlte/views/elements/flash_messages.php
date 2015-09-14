<?php 
if($flashMessage) { 

	if($flashMessage['type'] == 'succes') {
		?>
		<div class="alert alert-success alert-dismissable">
        	<h4><i class="icon fa fa-check"></i> <?php echo _("Confirmation"); ?>!</h4>
			<?php echo $flashMessage['message']; ?>
		</div>
		<?php 		
	}
	else if($flashMessage['type'] == 'error') {		
		?>
		<div class="alert alert-danger alert-dismissable">
			<h4><i class="icon fa fa-ban"></i> <?php echo _("Attention"); ?>!</h4>
			<?php echo $flashMessage['message']; ?>
		</div>
		<?php		
	}
}
?>