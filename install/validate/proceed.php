<?php 
require_once(KOEZION.DS.'validation.php'); //Inclusion de la librairie de validation
$validation = new Validation();

foreach($validate as $k => $v) { //On va parcourir tous les champs à valider

	//Par défaut si le champ est présent dans les données à valider on le traite
	if(isset($datas[$k])) {

		$isValid = false; //Par défaut on renverra toujours faux

		//On va tester si il y à plusieurs règles de validation
		//Si on a pas directement accès à la clée rule cela signifie qu'il y à plusieurs règles
		if(!isset($v['rule'])) {

			//On va donc les parcourir
			foreach($v as $kRule => $vRule) {

				$isValid = $validation->check($datas[$k], $vRule['rule']);
				if(!$isValid) {
					$formerrors[$k][$kRule] = $vRule['message'];
				} //On injecte le message
			}
		} else {

			$isValid = $validation->check($datas[$k], $v['rule']);
			if(!$isValid) {
				$formerrors[$k] = $v['message'];
			} //On injecte le message
		}
	}
}