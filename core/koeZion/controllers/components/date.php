<?php
class DateComponent extends Component {

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
		'long' 	=> array(0 => "Dimanche", 1 => "Lundi", 2 => "Mardi", 3 => "Mercredi", 4 => "Jeudi", 5 => "Vendredi", 6 => "Samedi", 7 => "Dimanche")			
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
		'long' 	=> array(1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril", 5 => "Mai", 6 => "Juin", 7 => "Juillet", 8 => "Août", 9 => "Septembre", 10 => "Octobre", 11 => "Novembre", 12 => "Décembre"),			
	);
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//									FONCTIONS PUBLIQUES 										//
//////////////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Retourne une date formaté en fonction d'un datetime SQL
 *
 * @param 	varchar  	$date 		Datetime SQL à convertir
 * @return 	varchar		Date formatée
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 21/10/2014 by FI - Rajout de explode dans le retour
 * @version 0.3 - 23/09/2016 by FI - Déplacée depuis le composant text
 */
	public function date_sql_to_human($date) {
				
		$splitHeure = explode(' ', $date); //pour enlever les heures dans le cas datetime (sql)			
		$dateTemp 	= explode('-', $splitHeure[0]); //On récupère la date et on génère un tableau
		if(isset($splitHeure[1])) { $heureTemp = explode(':', $splitHeure[1]); } //On récupère l'heure et on génère un tableau
			
		$day 	= $dateTemp[2];
		$month 	= (int)($dateTemp[1]);
		//$year = substr($dateTemp[0], -2, 2);
		$year 	= $dateTemp[0];
		
		$return = array(
			'txt' => $this->months['short'][$month]." ".$year,
			'sql' => $dateTemp[0].'-'.$dateTemp[1],
			'date' => array(
				'fullNumber' 	=> $day.'.'.$dateTemp[1].'.'.$year,
				'fullTxt' 		=> $day.' '.$this->months['short'][$month]." ".$year,
				'explode' => array(
					'd' => $day,
					'm' => array(
						'number' => $dateTemp[1],
						'text' => $this->months['short'][$month]
					),
					'Y' => $year
				)
			),
			'time' => array(
				'h' 	=> '',
				'm' 	=> '',
				's' 	=> '',
				'hm' 	=> '',
				'full' 	=> ''
			),
			'full' => array(
				'd' => $day,	
				'm' => $dateTemp[1],	
				'Y' => $year,	
				'h' => '',
				'i' => '',
				's' => '',
			)
		);
		
		if(isset($heureTemp)) {

			$return['time'] = array(
				'h' 	=> $heureTemp[0],
				'm' 	=> $heureTemp[1],
				's' 	=> $heureTemp[2],
				'hm' 	=> $heureTemp[0].':'.$heureTemp[1],
				'full' 	=> $splitHeure[1]
			);
			
			$return['full']['h'] = $heureTemp[0];
			$return['full']['i'] = $heureTemp[1];
			$return['full']['s'] = $heureTemp[2];
		}
		
		return $return;
	}
	
/**
 * Retourne un tableau contenant les données d'une date passées dans un format 'humain'
 *
 * @param 	varchar  	$date Datetime à convertir
 * @return 	array		Tableau contenant les informations sur la date
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/07/2012 by FI
 * @version 0.1 - 25/10/2012 by FI - Rajout d'une option permettant de choisir si on retourne les valeurs dans un tablau avec des index en chiffres ou en lettres
 * @version 0.2 - 06/11/2015 by FI - Rajout de $date = explode(' ', $date);
 * @version 0.3 - 23/09/2016 by FI - Déplacée depuis le composant text
 */
	public function date_human_to_array($date, $separateur = '.', $retour = 'c') {
		
		$date 		= explode(' ', $date);		
		$dateTmp 	= explode($separateur, $date[0]);
		
		if($retour == 'c') {
			
			return array(
				'j' => $dateTmp[0],	
				'm' => $dateTmp[1],	
				'a' => $dateTmp[2]		
			);
				
		} else if($retour == 'i') {
			
			return array(
				$dateTmp[0],
				$dateTmp[1],
				$dateTmp[2]
			);
			
		} 
		else if($retour == 'us') { return $dateTmp[2].'-'.$dateTmp[1].'-'.$dateTmp[0]; } 
		else if($retour == 'fr') { return $dateTmp[2].'.'.$dateTmp[1].'.'.$dateTmp[0]; }
	}
	
/**
 * Fonction qui permet de recuperer la date du premier jour du mois
 * 
 * @param integer $iMonth	mois en cours
 * @param integer $iYear	année
 * @return date
 * @access public
 * @author Az
 * @version 0.1 - 09/03/2011 by AB
 * @version 0.2 - 23/09/2016 by FI - Déplacée depuis le composant text
 */
	public function get_first_day_of_month($iMonth = '', $iYear = '') {
		
		if(empty($iMonth)) $iMonth 	= date('m');
		if(empty($iYear)) $iYear 	= date('Y');
		
		$tTimeMonth 	= mktime(0, 0, 0, $iMonth, 1, $iYear);
		$dFirstDayMonth = date('Y-m-d', $tTimeMonth);
		
		return $dFirstDayMonth;
		
	}

/**
 * Fonction qui permet de recuperer le dernier jour du mois
 * 
 * @param integer $iMonth	mois en cours
 * @param integer $iYear	année
 * @return date
 * @access public
 * @author Az
 * @version 0.1 - 09/03/2011 by AB
 * @version 0.2 - 23/09/2016 by FI - Déplacée depuis le composant text
 */	
	public function get_last_day_of_month($iMonth = '', $iYear = '') {
		
		if(empty($iMonth)) $iMonth 	= date('m');
		if(empty($iYear)) $iYear 	= date('Y');
		
		//Récuperation du dernier jour du mois
		$tTimeMonth 	= mktime(0, 0, 0, $iMonth, 1, $iYear);
		$dLastDayMonth 	= date('Y-m-t', $tTimeMonth);
		
		return $dLastDayMonth;
	}

/**
 * Fonction permettant de tester si une date est valide
 * 
 * @param 	varchar $date 	Date
 * @param 	varchar $format format 
 * @return 	boolean
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/07/2014 by FI
 * @version 0.2 - 21/07/2014 by FI - Rajout du format
 * @version 0.3 - 23/09/2016 by FI - Déplacée depuis le composant text
 */	
	public function check_date($date, $format = 'ymd') {
		
		//On utilise la règle de validation prévue à cet effet
		require_once(SYSTEM.DS.'validation.php');
		$validation = new Validation();
		return $validation->date($date, $format);
	}
	
/**
 * Cette fonction permet de calculer une nouvelle date
 * 
 * @param 	integer 	$value
 * @param 	varvchar 	$type (days, months, years)
 * @param 	varchar 	$direction (+, -)
 * @param 	varchar 	$currentDate 
 * @return 	varchar
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/12/2014 by FI
 * @version 0.2 - 23/09/2016 by FI - Déplacée depuis le composant text
 */	
	public function calculate_date($value, $type = 'days', $direction = '+', $currentDate = null) {
		
		if(!isset($currentDate)) { $currentDate = date('Y-m-d'); }
		$currentDate = explode('-', $currentDate);
		
		$currentDateTimestamp 	= strtotime($currentDate[0].'-'.$currentDate[1].'-'.$currentDate[2]);
		$retractationEndDate 	= date('Y-m-d', strtotime($direction.$value.' '.$type, $currentDateTimestamp));
		return $retractationEndDate;
	}	

/**
 * Cette fonction permet de transformer une date FR en date SQL et inversement
 * 
 * @param 	varchar $mode 	Mode de transformation FR --> SQL ou SQL --> FR
 * @param 	varchar $field 	Champ à tester
 * @param 	array 	$datas 	Si renseigné le test se fera dans cette variable au lieu de $this->request->data 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 25/10/2012 by FI
 * @version 0.2 - 03/11/2013 by FI - Déplacée du contrôleur posts vers le contrôleur app
 * @version 0.3 - 10/11/2013 by FI - Modification de la fonction pour qu'elle prenne en compte les tableaux avec des index multiples
 * @version 0.4 - 09/12/2013 by FI - Modification du champ et du tableau à tester
 * @version 0.5 - 22/09/2016 by FI - Déplacée depuis AppController
 * @version 0.6 - 22/09/2016 by FI - Modification chargement composant Text
 */		
	public function transform_date($mode, $field, $datas) {
		
		if($mode == 'fr2Sql') {
			
			//Transformation de la date FR en date SQL
			if(!empty($field)) {
			
				$date = Set::classicExtract($datas, $field);
				if(!empty($date) && $date != 'dd.mm.yy') {
					
					$dateArray 	= $this->date_human_to_array($date);
					$datas 		= Set::insert($datas, $field, $dateArray['a'].'-'.$dateArray['m'].'-'.$dateArray['j']);					
				} 
				else { $datas = Set::insert($datas, $field, ''); }
			}
		} else if($mode == 'sql2Fr') {
			
			//Transformation de la date SQL en date FR
			if(!empty($field)) {
			
				$date = Set::classicExtract($datas, $field);
				if($date != '') {
					
					$dateArray 	= $this->date_human_to_array($date, '-', 'i');
					$datas 		= Set::insert($datas, $field, $dateArray[2].'.'.$dateArray[1].'.'.$dateArray[0]);					
				} 
				else { $datas = Set::insert($datas, $field, 'dd.mm.yy'); }
			}
		}
					
		return $datas;
	}

/**
 * Cette fonction retourne le nombre de jours entre deux dates
 *
 * @param 	varchar $date1 Date au format YYYY-MM-DD
 * @param 	varchar $date2 Date au format YYYY-MM-DD
 * @return 	integer Différence en nombre de jours
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/08/2012 by FI
 */		
	public function days_diff($date1, $date2) {
		
		$interval = $this->_diff($date1, $date2);		
		return $interval->days;
	}		
	
/**
 * Cette fonction retourne le nombre de minutes entre deux dates
 *
 * @param 	varchar $date1 Date au format YYYY-MM-DD
 * @param 	varchar $date2 Date au format YYYY-MM-DD
 * @return 	integer Différence en nombre de minutes
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/04/2015 by FI
 */	
	public function minutes_diff($date1, $date2) {
	
		$interval = $this->_diff($date1, $date2);
		
		//Calcule le nombre d'heures puis le convertit en minutes
		$hours = (($interval->y * 365 * 24) + ($interval->m * 30 * 24) + ($interval->d * 24) + ($interval->h)) * 60;
		return $hours + $interval->i;
	}	
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS PRIVEES 										//
//////////////////////////////////////////////////////////////////////////////////////////////////	

/**
 * Cette fonction va effectuer l'opération de calcul entre les deux dates passées en paramètres
 *
 * @param 	varchar $date1 Date au format YYYY-MM-DD
 * @param 	varchar $date2 Date au format YYYY-MM-DD
 * @return 	object  Interval entre deux dates
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 21/04/2015 by FI
 */
	protected function _diff($date1, $date2) {
		
		$datetime1 	= new DateTime($date1);
		$datetime2 	= new DateTime($date2);
		$interval 	= $datetime1->diff($datetime2);		
		return $interval;
	}
}