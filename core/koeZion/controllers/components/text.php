<?php
class TextComponent extends Component {
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//									FONCTIONS PUBLIQUES GENERIQUES										//
//////////////////////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction va retourner le texte en remplaçant certains caractères par d'autres
 *
 * @param 	varchar $content 	Texte source
 * @return 	varchar Texte modifié
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/08/2012 by FI
 */		
	public function format_content_text($content) {
		
		$content = str_replace('&brvbar;', '&#92;', $content);		
		return $content;
	}	
	
/**
 * Cette fonction va convertir les < (inférieur à) en &lt; et les > (supérieur à) en &gt; 
 *
 * @param 	varchar $content 	Texte source
 * @return 	varchar Texte modifié
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/11/2012 by FI
 */
	public function convert_lt_gt($content) {
	
		$content = str_replace('<', '&lt;', $content);
		$content = str_replace('>', '&gt;', $content);
		return $content;
	}		

/**
 * Supprimer les accents
 *
 * @param string $str chaîne de caractères avec caractères accentués
 * @param string $encoding encodage du texte (exemple : utf-8, ISO-8859-1 ...)
 * @see http://www.infowebmaster.fr/tutoriel/php-enlever-accents
 */	
	public function suppr_accents($str, $encoding = 'utf-8', $toLower = false) {
		
		// transformer les caractères accentués en entités HTML
		$str = htmlentities($str, ENT_NOQUOTES, $encoding);
	
		// remplacer les entités HTML pour avoir juste le premier caractères non accentués
		// Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
		$str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	
		// Remplacer les ligatures tel que : Œ, Æ ...
		// Exemple "Å“" => "oe"
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
		// Supprimer tout le reste
		$str = preg_replace('#&[^;]+;#', '', $str);
	
		if($toLower) { $str = strtolower($str); }
		return $str;
	}
	
/**
 * Cette fonction permet de générer un code aléatoire
 * 
 * @param 	interger $length Nombre de caractères qui composeront le code
 * @return 	varchar Code
 * @access 	public 
 * @author 	koéZionCMS
 * @version 0.1 - 02/07/2014 by FI
 * @version 0.2 - 17/09/2014 by FI - Possibilité de déterminer les caractères à prendre en compte
 */		
	public function random_code($length = 10, $characts = null) {		
		
		if(!isset($characts)) { 
			
			$characts   = 'abcdefghijklmnopqrstuvwxyz';
			$characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$characts   .= '1234567890';
		}
		$code 		= '';
		
		for($i=0;$i<$length;$i++) { $code .= substr($characts, rand()%(strlen($characts)), 1); }
		return $code;
	}

/**
 * Cette fonction permet d'effectuer le rempmlace des données contenues dans un text
 * Dans $content si on trouve un texte du style [Customer.name] et que l'index est présent dans $replacement alors le texte sera remplacé par sa veleur dans le tableau 
 * @param 	varchar $content 		Texte dans lequel il faut chercher
 * @param 	array 	$replacement	Données de remplacement
 * @return 	varchar	Contenu modifié
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2014 by FI
 * @version 0.2 - 04/04/2014 by FI - Modification de la récupération de la valeur à remplacer
 */		
	public function replace_content($content, $replacement) {
		
		preg_match_all("/\[(.+?)\]/", $content, $result);
		
		//$result contient 
		// - dans le premier index (0) les chaines trouvés dans le texte avec les crochets
		// - dans le second (1) les chaines trouvés dans le texte sans les crochets
		if(!empty($result[1])) {
			
			foreach($result[1] as $key => $path) {
				
				if(Set::check($replacement, $path)) {
					
					$value = Set::classicExtract($replacement, $path);
					$content = str_replace($result[0][$key], $value, $content);
				}	
			}			
		}
		
		return $content;
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//									FONCTIONS PUBLIQUES SUR LES DATES										//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Toutes déplacées dans le composant date
//Sera prochainement supprimé
	
/**
 * Retourne une date formaté en fonction d'un datetime SQL
 *
 * @param 	varchar  	$date 		Datetime SQL à convertir
 * @return 	varchar		Date formatée
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 21/10/2014 by FI - Rajout de explode dans le retour
 * @deprecated since 23/09/2016 by FI - Déplacée dans le composant date
 */
	public function date_sql_to_human($date) {
		pr('FONCTION DEPLACEE DANS LE COMPOSANT DATE');
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
 * @deprecated since 23/09/2016 by FI - Déplacée dans le composant date
 */
	public function date_human_to_array($date, $separateur = '.', $retour = 'c') {
		pr('FONCTION DEPLACEE DANS LE COMPOSANT DATE');
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
 * @deprecated since 23/09/2016 by FI - Déplacée dans le composant date
 */
	public function get_first_day_of_month($iMonth = '', $iYear = '') {
		pr('FONCTION DEPLACEE DANS LE COMPOSANT DATE');		
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
 * @deprecated since 23/09/2016 by FI - Déplacée dans le composant date
 */	
	public function get_last_day_of_month($iMonth = '', $iYear = '') {
		pr('FONCTION DEPLACEE DANS LE COMPOSANT DATE');		
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
 * @deprecated since 23/09/2016 by FI - Déplacée dans le composant date
 */	
	public function check_date($date, $format = 'ymd') {
		pr('FONCTION DEPLACEE DANS LE COMPOSANT DATE');		
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
 * @deprecated since 23/09/2016 by FI - Déplacée dans le composant date
 */	
	public function calculate_date($value, $type = 'days', $direction = '+', $currentDate = null) {
		pr('FONCTION DEPLACEE DANS LE COMPOSANT DATE');
	}
}