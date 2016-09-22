<?php
class DateComponent extends Component {

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
		
		$textComponent = $this->load_component('Text', null, null, true);
		
		if($mode == 'fr2Sql') {
			
			//Transformation de la date FR en date SQL
			if(!empty($field)) {
			
				$date = Set::classicExtract($datas, $field);
				if(!empty($date) && $date != 'dd.mm.yy') {
					
					$dateArray = $textComponent->date_human_to_array($date);
					$datas = Set::insert($datas, $field, $dateArray['a'].'-'.$dateArray['m'].'-'.$dateArray['j']);
					
				} else {
					
					$datas = Set::insert($datas, $field, '');
				}
			}
		} else if($mode == 'sql2Fr') {
			
			//Transformation de la date SQL en date FR
			if(!empty($field)) {
			
				$date = Set::classicExtract($datas, $field);
				if($date != '') {
					
					$dateArray = $textComponent->date_human_to_array($date, '-', 'i');
					$datas = Set::insert($datas, $field, $dateArray[2].'.'.$dateArray[1].'.'.$dateArray[0]);
					
				} else {
					
					$datas = Set::insert($datas, $field, 'dd.mm.yy');
					
				}
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