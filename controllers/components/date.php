<?php
class DateComponent extends Component {

/**
 * Cette fonction va retourner le texte en remplaçant certains caractères par d'autres
 *
 * @param 	varchar $date1 Date au format YYYY-MM-DD
 * @param 	varchar $date2 Date au format YYYY-MM-DD
 * @return 	integer Différence en nombre de jour
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/08/2012 by FI
 */		
	public function days_diff($date1, $date2) {
		
		$datetime1 	= new DateTime($date1);
		$datetime2 	= new DateTime($date2);
		$interval 	= $datetime1->diff($datetime2);		
		return $interval->days;
	}	
}