<script type="text/javascript">	
$(document).ready(function() {			

	var addedButton = [];
	
	<?php 
	if(isset($this->controller->request->data['right_button_id'])) {

		foreach($this->controller->request->data['right_button_id'] as $rightButtonId => $isActiv) {
			
			?>addedButton.push("<?php echo $rightButtonId; ?>");<?php echo "\n\t";
		}
	}		
	?>		
	//Rajout d'une nouvelle option de question 
	$("#addRightButton").click(function() {
	
		var rightButton = $('#InputRightButtonsListId').val();
		
		if(rightButton.length) {
			
			if(jQuery.inArray(rightButton, addedButton) < 0) {
			
				var host = $.get_host(); //Récupération du host
				var action = host + 'home/ajax_add_right_button/' + rightButton + '.html'; //On génère l'url			
				
				//On récupère les données en GET et on rajoute une nouvelle ligne
				$.get(action, function(datas) { $(datas).appendTo("#buttons"); });
				addedButton.push(rightButton);
			} else { alert("Ce bouton est déjà inséré"); }
		} else { alert("Vous devez sélectionner un bouton"); }		
	});		
	
	$("#buttons").livequery(function() { 	

		$(this).sortable({
			items: '.sortable', 
			revert: true, 
			axis: 'y', 
			cursor: 'move'
			//stop: function() { jAlert('Opération terminée, élément déplacé', 'Message'); }
		});
	});
});
</script>