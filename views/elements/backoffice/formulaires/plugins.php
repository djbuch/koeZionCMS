<?php 	
echo $helpers['Form']->input('name', '<i>(*)</i> Code', array('tooltip' => "Indiquez le code du plugin"));
echo $helpers['Form']->input('description', '<i>(*)</i> Description', array('tooltip' => "Indiquez la description du plugin"));
echo $helpers['Form']->input('online', 'Activer', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour activer ce plugin"));