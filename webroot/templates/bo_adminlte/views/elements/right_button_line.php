<section class="right_button_line sortable">
	<div class="row">
        <div class="col-sm-12 col-md-4">
			<?php 
			echo $helpers['Html']->img('bo_adminlte/img/move.png', array('alt' => _('Déplacer'), 'title' => _('Déplacer'), 'class' => 'move_cursor')); 
			echo $rightButtonName; 
			?>
        </div>
        <div class="col-sm-12 col-md-4">
        	<?php echo $helpers['Form']->input('right_button_id.'.$rightButtonId.'.top', _("Positionner en haut de la colonne"), array('type' => 'checkbox', 'value' => 1)); ?>
        </div>
        <div class="col-sm-12 col-md-4">
        	<?php echo $helpers['Form']->input('right_button_id.'.$rightButtonId.'.activate', _("Activer ce widget"), array('type' => 'checkbox', 'value' => 1)); ?>        
        </div>
	</div>
</section>