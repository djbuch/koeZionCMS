<?php
echo $helpers['Form']->input('name', 'Nom', array('tooltip' => "Indiquez le nom du contact")); 
echo $helpers['Form']->input('phone', 'Téléphone', array('tooltip' => "Indiquez le téléphone du contact")); 
echo $helpers['Form']->input('email', '<i>(*)</i> Email', array('tooltip' => "Indiquez l'email du contact")); 
//echo $helpers['Form']->input('message', 'Message', array('type' => 'textarea', 'tooltip' => "Saisissez le message du contact"));
echo $helpers['Form']->input('message', 'Message', array('type' => 'textarea', 'wysiswyg' => true, 'toolbar' => 'empty', 'rows' => 5, 'cols' => 10, 'class' => 'xxlarge')); 
echo $helpers['Form']->input('online', 'Valide', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour valider ce contact (Une fois validé le contact sera exporté dans le fichier csv)"));