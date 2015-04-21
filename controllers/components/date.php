<?php
class DateComponent extends Component {

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
		
		$interval 	= $this->_diff($date1, $date2);		
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
	
		$interval 	= $this->_diff($date1, $date2);
		
		//Calcule le nombre d'heures puis le convertit en minutes
		$hours = (($interval->y * 365 * 24) + ($interval->m * 30 * 24) + ($interval->d * 24) + ($interval->h)) * 60;
		return $hours + $interval->i;
	}

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