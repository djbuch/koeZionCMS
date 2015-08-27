<div class="content nopadding sortable">	
	<div class="row" style="overflow:hidden;">
		<label>
			<?php 
			$helpers['Html']->img('bo_godzilla/img/move.png', array('alt' => _('Déplacer'), 'title' => _('Déplacer'), 'style' => 'float:left;margin-right:5px;margin-top:-3px;cursor:move'));
			echo $rightButtonName; 
			?>
		</label>
		<div class="rowright">
			<span class="checkbox" style="float: left; display: block; width: 240px; line-height: 15px;"><?php
			echo $helpers['Form']->input('right_button_id.'.$rightButtonId.'.top', _("Positionner en haut de la colonne"), array('type' => 'checkbox', 'div' => false, 'value' => 1));
			?></span>	
			<span class="checkbox" style="float: left; display: block; width: 240px; line-height: 15px;"><?php
			echo $helpers['Form']->input('right_button_id.'.$rightButtonId.'.activate', _("Activer ce bouton"), array('type' => 'checkbox', 'div' => false, 'value' => 1));
			?></span>		
		</div>
	</div>					
</div>