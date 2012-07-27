<?php
echo $helpers['Form']->input('post_id', 'Article', array('type' => 'select', 'datas' => $posts, 'tooltip' => "Indiquez ici l'article que vous souhaitez commenter"));
echo $helpers['Form']->input('name', 'Nom', array('tooltip' => "Indiquez le nom de l'Internaute")); 
echo $helpers['Form']->input('email', '<i>(*)</i> Email', array('tooltip' => "Indiquez l'email de l'Internaute")); 
echo $helpers['Form']->input('message', 'Message', array('type' => 'textarea', 'tooltip' => "Saisissez le message de l'Internaute")); 
echo $helpers['Form']->input('online', 'Valide', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour valider ce commentaire"));