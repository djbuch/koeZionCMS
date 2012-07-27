<?php
class Text {

//////////////////////////////////////////////////////////////////////////////////////////
//										VARIABLES										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Variable contenant la liste des jours de la semaine
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */	
	private $days = array(
		'short' => array (0 => "Dim.", 1 => "Lun.", 2 => "Mar.", 3 => "Mer.", 4 => "Jeu.", 5 => "Ven.", 6 => "Sam.", 7 => "Dim."),
		'long' => array(0 => "Dimanche", 1 => "Lundi", 2 => "Mardi", 3 => "Mercredi", 4 => "Jeudi", 5 => "Vendredi", 6 => "Samedi", 7 => "Dimanche")			
	);
	
/**
 * Variable contenant la liste des mois de l'année
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */	
	private $months = array(
		'short' => array(1 => "Janv.", 2 => "Févr.", 3 => "Mars", 4 => "Avr.", 5 => "Mai", 6 => "Juin", 7 => "Juil.", 8 => "Août", 9 => "Sept.", 10 => "Oct.", 11 => "Nov.", 12 => "Déc."),
		'long' => array(1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril", 5 => "Mai", 6 => "Juin", 7 => "Juillet", 8 => "Août", 9 => "Septembre", 10 => "Octobre", 11 => "Novembre", 12 => "Décembre"),			
	);
//////////////////////////////////////////////////////////////////////////////////////////
//								FONCTIONS PUBLIQUES										//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Retourne une date formaté en fonction d'un datetime SQL
 *
 * @param 	varchar  	$date 		Datetime SQL à convertir
 * @return 	varchar		Date formatée
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */
	function date_sql_to_human($date) {
		
		$splitHeure = explode(' ', $date); //pour enlever les heures dans le cas datetime (sql)	
		$dateTemp = explode('-', $splitHeure[0]); //On récupère la date et on génère un tableau
		$heureTemp = explode(':', $splitHeure[1]); //On récupère l'heure et on génère un tableau
			
		$day = $dateTemp[2];
		$month = (int)($dateTemp[1]);
		//$year = substr($dateTemp[0], -2, 2);
		$year = $dateTemp[0];
		
		return array(
			'txt' => $this->months['short'][$month]." ".$year,
			'sql' => $dateTemp[0].'-'.$dateTemp[1],
			'date' => array(
				'fullNumber' => $day.'.'.$dateTemp[1].'.'.$year,
			),
			'time' => array(
				'h' => $heureTemp[0],	
				'm' => $heureTemp[1],	
				's' => $heureTemp[2],
				'hm' => $heureTemp[0].':'.$heureTemp[1],
				'full' => $splitHeure[1]	
			)			
		);	
	}
	
/**
 * Retourne un tableau contenant les données d'une date passées dans un format 'humain'
 *
 * @param 	varchar  	$date 		Datetime à convertir
 * @return 	varchar		Date formatée
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/07/2012 by FI
 */
	function date_human_to_array($date, $separateur = '.') {
		
		$dateTmp = explode($separateur, $date);
		return array(
			'j' => $dateTmp[0],	
			'm' => $dateTmp[1],	
			'a' => $dateTmp[2]
		);	
	}
	
	
//////////////////////////////////////////////////////////////////////////////////////////
//								FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////	
}