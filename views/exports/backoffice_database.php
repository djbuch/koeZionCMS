<?php 
//On va parcourir l'ensemble des éléments pour générer le fichier de backup
foreach($createTables as $k => $v) { echo $v."\n\n\n"; }
foreach($insertions as $k => $v) { echo $v."\n\n\n"; }