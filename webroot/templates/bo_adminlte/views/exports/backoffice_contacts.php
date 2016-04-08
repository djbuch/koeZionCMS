<?php
//Si on a des résultats
if(!empty($contacts)) {

	//On va récupérer les entêtes
	echo '"'.implode('";"', array_keys(current($contacts))).'";'."\n";

	//Parcours des valeurs et génération des lignes
	foreach($contacts as $contact) {
		
		foreach($contact as $k => $v) { 
			echo '"'.utf8_decode(utf8_encode(html_entity_decode($v))).'";';		
		}
		echo "\n";
	}
}