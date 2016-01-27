<?php
class Validation {

	var $model = null;
	
/**
 * Some complex patterns needed in multiple places
 *
 * @var array
 * @access private
 */
	var $__pattern = array(
		'hostname' => '(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)'
	);	
	
	function __construct($model = null) { $this->model = $model; }
	
/**
 * Cette fonction va lancer la règle de validation
 *
 * @param varchar $val Valeur à tester
 * @param mixed $rule Règle de validation à lancer
 * @return boolean
 * @version 0.1 - 28/12/2011
 */	
	function check($val, $rule) {
		
		//Si la règle est un tableau
		if(is_array($rule)) { 
			
			$function = $rule[0]; //On récupère la fonction qui sera toujours dans la première clée du tableau
			unset($rule[0]); //On la supprime la fonction du tableau de règle
			 
		} 
		else { $function = $rule; } //Sinon on affecte directement la fonction
		
		if(method_exists($this, $function)) { //On teste si la méthode existe dans la classe
			
			$params = am(array($val), $rule); //On génère une variable contenant les paramètres de la fonction
			return call_user_func_array(array($this, $function), $params); //Et on retourne le résultat de la fonction
			
		} else { return false; }  //Si la méthode n'existe pas on génère une erreur
	}
	
/**
 * Cette fonction va contrôler que la valeur passée en paramètre n'est pas vide
 *
 * @param varchar $val Valeur à tester
 * @return boolean
 * @version 0.1 - 28/12/2011
 */	
	function notEmpty($val) { return !empty($val); }
	
/**
 * Cette fonction va contrôler que la valeur passée en paramètre n'est pas égale à la valeur de référence
 *
 * @param mixed $val Valeur à tester
 * @param mixed $ref Valeur de référence
 * @return boolean
 * @version 0.1 - 04/02/2011
 */
	function notEqualsTo($val, $ref) { return $val != $ref; }	
	
/**
 * Cette fonction va contrôler que la valeur passée en paramètre est  égale à la valeur de référence
 *
 * @param mixed $val Valeur à tester
 * @param mixed $ref Valeur de référence
 * @return boolean
 * @version 0.1 - 14/05/2014
 */
	function equalsTo($val, $ref) { return $val == $ref; }	

/**
 * Cette fonction va contrôler que la valeur passée en paramètre est bien un email
 *
 * @param varchar $val Valeur à tester
 * @return boolean
 * @version 0.1 - 28/12/2011
 */	
	function email($val) { 

		$regex = '/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@' . $this->__pattern['hostname'] . '$/i';
		return preg_match($regex, $val) ? true : false; 
	}

/**
 * Cette fonction va contrôler que la valeur passée en paramètre ne contient que des lettres
 *
 * @param varchar $val Valeur à tester
 * @return boolean
 * @version 0.1 - 28/12/2011
 */		
	function alphabetic($val) { return preg_match('/^([ÀÂÇÈÉÊËÎÏÔÙÛÜàâçèéêëîïôùûüa-zA-Z -]+)$/', $val) ? true : false; }

/**
 * Cette fonction va contrôler que la valeur passée en paramètre ne contient que des lettres et des chiffres
 *
 * @param varchar $val Valeur à tester
 * @return boolean
 * @version 0.1 - 28/12/2011
 */	
	function alphanumeric($val) { return preg_match('/^([ÀÂÇÈÉÊËÎÏÔÙÛÜàâçèéêëîïôùûüa-zA-Z0-9 -]+)$/', $val) ? true : false; }

/**
 * Cette fonction va contrôler que la valeur passée en paramètre ne contient que des nombres
 *
 * @param varchar $val Valeur à tester
 * @return boolean
 * @version 0.1 - 28/12/2011
 */	
	function numeric($val) { return is_numeric($val); }

/**
 * Cette fonction va contrôler que la valeur passée en paramètre est un entier
 *
 * @param varchar $val Valeur à tester
 * @return boolean
 * @version 0.1 - 04/12/2013
 */	
	function  integer($val) { return(is_numeric($val) ? intval(0+$val) == $val : false); }

/**
 * Cette fonction va contrôler que la valeur passée en paramètre ne contient pas plus de x caractères ou n'est pas supérieur à x
 *
 * @param mixed $val Valeur à tester
 * @param integer $x Longueur maximale possible
 * @return boolean
 * @version 0.1 - 28/12/2011
 */	
	function maxLength($val, $x) {
		
		if(is_int($val)) return ($val > $x) ? false : true; //Si c'est un entier il ne doit pas être supérieur $x
		else if(is_string($val)) return (strlen($val) > $x) ? false : true; //Si c'est une chaine de caractères elle ne doit pas contenir plus de $x caractères
	}

/**
 * Cette fonction va contrôler que la valeur passée en paramètre ne contient pas moins de x caractères ou n'est pas inférieure à x
 *
 * @param mixed $val Valeur à tester
 * @param integer $x Longueur minimale possible
 * @return boolean
 * @version 0.1 - 28/12/2011
 */		
	function minLength($val, $x) {
		
		if(is_int($val)) return ($val < $x) ? false : true; //Si c'est un entier il ne doit pas être inférieur $x
		else if(is_string($val)) return (strlen($val) < $x) ? false : true; //Si c'est une chaine de caractères elle ne doit pas contenir mois de $x caractères
	}
	
/**
 * Cette fonction va contrôler que la valeur passée en paramètre est comprise entre les valeurs de la variable $range
 *
 * @param mixed $val Valeur à tester
 * @param integer $min Valeur minimum
 * @param integer $max Valeur maximum
 * @return boolean
 * @version 0.1 - 28/12/2011
 */	
	function between($val, $min, $max) {
		
		$val = (int)$val;		
		return ($val < $min || $val > $max) ? false : true;
		
		/*if(is_int($val)) return ($val < $min || $val > $max) ? false : true;
		else if(is_string($val)) return (strlen($val) < $min || strlen($val) > $max) ? false : true;*/
	}
	
/**
 * Cette fonction va contrôler que la valeur passée en paramètre est conforme à l'expression régulière
 *
 * @param varchar $val Valeur à tester
 * @param varchar $regex expression régulière
 * @return boolean
 * @version 0.1 - 28/12/2011
 */		
	function custom($val, $regex) { return (!preg_match($regex, $val)) ? false : true; }

/**
 * Checks that a value is a valid URL according to http://www.w3.org/Addressing/URL/url-spec.txt
 *
 * The regex checks for the following component parts:
 *
 * - a valid, optional, scheme
 * - a valid ip address OR
 *   a valid domain name as defined by section 2.3.1 of http://www.ietf.org/rfc/rfc1035.txt
 *   with an optional port number
 * - an optional valid path
 * - an optional query string (get parameters)
 * - an optional fragment (anchor tag)
 *
 * @param string $check Value to check
 * @param boolean $strict Require URL to be prefixed by a valid scheme (one of http(s)/ftp(s)/file/news/gopher)
 * @return boolean Success
 * @access public
 * @version 0.1 - 29/12/2011
 */	
	function url($val, $strict = false, $multiple = false) {
		
		if($multiple) {
			
			$urls = explode("\n", $val);
			foreach($urls as $url) {
				
				$url = trim($url);
				$return = $this->_url($url, $strict);
				if(!$return) {return false;}
			}
		} else { return $this->_url($val, $strict); }
		return true;
	}		
	
	function _url($val, $strict = false) {
		
		$this->__populateIp();
		$validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=~') . '\/0-9a-z\p{L}\p{N}]|(%[0-9a-f]{2}))';
		
		$regex = 
			'/^(?:(?:https?|ftps?|file|news|gopher):\/\/)' . (!empty($strict) ? '' : '?') .
			'(?:' . $this->__pattern['IPv4'] . '|\[' . $this->__pattern['IPv6'] . '\]|' . $this->__pattern['hostname'] . ')' .
			'(?::[1-9][0-9]{0,4})?' .
			'(?:\/?|\/' . $validChars . '*)?' .
			'(?:\?' . $validChars . '*)?' .
			'(?:#' . $validChars . '*)?$/iu';
		
		return preg_match($regex, $val) ? true : false;
	}
	
/**
 * Cette fonction permet de faire appel à une fonction dans un model en respectant la logique de celui-ci
 *
 * @param 	string 	$val Value to check
 * @param 	array 	$callback Tableau contenant les informations de l'action à exécuter
 * @return 	boolean Success
 * @access 	public
 * @version 0.1 - 20/04/2012
 * @version 0.2 - 13/02/2014 - Modification du tri sur le tableau callback pour ordonner selon la clé
 */	
	function callback($val, $callback) {

		//Si la fonction de callback est un tableau
		//Le premier index de ce tableau sera la fonction à lancer
		//Les index suivants seront les éventuels paramètres à passer
		if(is_array($callback)) {
			
			//Récupération et suppression dans le tableau de l'action à exécuter
			$action = $callback[0];
			unset($callback[0]);
			
			if(method_exists($this->model, $action)) {
								
				$callback[0] = $val; //On affecte en premier index la valeur à tester
				//asort($callback); //On réorganise le tableau en triant les index
				ksort($callback); //On réorganise le tableau en triant les index
				
				return call_user_func_array(array($this->model, $action), $callback); //On fait appel à la fonction
								
			} else { return false; }
		} else { return false; }
	}	
	
/**
 * Cette fonction est chargée de contrôler que le fichier est correctement chargé
 *
 * @param 	array 	$val Value to check
 * @return 	boolean Success
 * @access 	public
 * @version 0.1 - 13/11/2012
 * @see http://stackoverflow.com/questions/3185603/validation-on-a-input-file-in-cakephp pour la base de départ
 */		
	function checkUpload($val) {
		
		if($val['error'] > 0) { return false; } //Si on a un code erreur
		if($val['size'] == 0) { return false; } //Si le fichier est vide
		return true;
	}	
	
/**
 * Cette fonction est chargée de contrôler le type du fichier chargé (l'extension)
 *
 * @param 	array 	$val 			Value to check
 * @param 	mixed 	$allowedMime 	Types autorisés (par défaut faux pour aucun contrôle de type)
 * @return 	boolean Success
 * @access 	public
 * @version 0.1 - 13/11/2012
 * @see http://stackoverflow.com/questions/3185603/validation-on-a-input-file-in-cakephp pour la base de départ
 */	
	function checkType($val, $allowedMime = false) {
		
		if(!in_array($val['type'], $allowedMime)) { return false; }		
		return true;
	}	
	
/**
 * Cette fonction est chargée de contrôler la taille maximale du fichier
 *
 * @param 	array 	$val		Value to check
 * @param 	integer $maxSize 	Taille maximale en Mo
 * @return 	boolean Success
 * @access 	public
 * @version 0.1 - 13/11/2012
 * @see http://stackoverflow.com/questions/3185603/validation-on-a-input-file-in-cakephp pour la base de départ
 */		
	function checkSize($val, $maxSize = false) {
				
		if($maxSize && $val['size'] < ($maxSize * 1048576)) { return true; }
		return false;
	}

/**
 * Détermine si la chaîne de caractère est une date valide
 * keys that expect full month, day and year will validate leap years
 *
 * @param string $check a valid date string
 * @param string|array $format Use a string or an array of the keys below. Arrays should be passed as array('dmy', 'mdy', etc)
 *          Keys: dmy 27-12-2006 or 27-12-06 separators can be a space, period, dash, forward slash
 *          mdy 12-27-2006 or 12-27-06 separators can be a space, period, dash, forward slash
 *          ymd 2006-12-27 or 06-12-27 separators can be a space, period, dash, forward slash
 *          dMy 27 December 2006 or 27 Dec 2006
 *          Mdy December 27, 2006 or Dec 27, 2006 comma is optional
 *          My December 2006 or Dec 2006
 *          my 12/2006 separators can be a space, period, dash, forward slash
 *          ym 2006/12 separators can be a space, period, dash, forward slash
 *          y 2006 just the year without any separators
 * @param string $regex If a custom regular expression is used this is the only validation that will occur.
 * @return boolean Success
 * @access 	public
 * @version 0.1 - 04/12/2013
 * @see http://book.cakephp.org/2.0/fr/models/data-validation.html#regles-de-validation-incluses
 */
	function date($val, $format = 'ymd', $regex = null) {
		
		if ($regex !== null) { return $this->custom($val, $regex); }
	
		$regex['dmy'] = '%^(?:(?:31(\\/|-|\\.|\\x20)(?:0?[13578]|1[02]))\\1|(?:(?:29|30)(\\/|-|\\.|\\x20)(?:0?[1,3-9]|1[0-2])\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:29(\\/|-|\\.|\\x20)0?2\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\\d|2[0-8])(\\/|-|\\.|\\x20)(?:(?:0?[1-9])|(?:1[0-2]))\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%';
		$regex['mdy'] = '%^(?:(?:(?:0?[13578]|1[02])(\\/|-|\\.|\\x20)31)\\1|(?:(?:0?[13-9]|1[0-2])(\\/|-|\\.|\\x20)(?:29|30)\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:0?2(\\/|-|\\.|\\x20)29\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\\/|-|\\.|\\x20)(?:0?[1-9]|1\\d|2[0-8])\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%';
		$regex['ymd'] = '%^(?:(?:(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\\/|-|\\.|\\x20)(?:0?2\\1(?:29)))|(?:(?:(?:1[6-9]|[2-9]\\d)?\\d{2})(\\/|-|\\.|\\x20)(?:(?:(?:0?[13578]|1[02])\\2(?:31))|(?:(?:0?[1,3-9]|1[0-2])\\2(29|30))|(?:(?:0?[1-9])|(?:1[0-2]))\\2(?:0?[1-9]|1\\d|2[0-8]))))$%';
		$regex['dMy'] = '/^((31(?!\\ (Feb(ruary)?|Apr(il)?|June?|(Sep(?=\\b|t)t?|Nov)(ember)?)))|((30|29)(?!\\ Feb(ruary)?))|(29(?=\\ Feb(ruary)?\\ (((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)))))|(0?[1-9])|1\\d|2[0-8])\\ (Jan(uary)?|Feb(ruary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep(?=\\b|t)t?|Nov|Dec)(ember)?)\\ ((1[6-9]|[2-9]\\d)\\d{2})$/';
		$regex['Mdy'] = '/^(?:(((Jan(uary)?|Ma(r(ch)?|y)|Jul(y)?|Aug(ust)?|Oct(ober)?|Dec(ember)?)\\ 31)|((Jan(uary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep)(tember)?|(Nov|Dec)(ember)?)\\ (0?[1-9]|([12]\\d)|30))|(Feb(ruary)?\\ (0?[1-9]|1\\d|2[0-8]|(29(?=,?\\ ((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)))))))\\,?\\ ((1[6-9]|[2-9]\\d)\\d{2}))$/';
		$regex['My'] = '%^(Jan(uary)?|Feb(ruary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep(?=\\b|t)t?|Nov|Dec)(ember)?)[ /]((1[6-9]|[2-9]\\d)\\d{2})$%';
		$regex['my'] = '%^((0[123456789]|10|11|12)([- /.])(([1][9][0-9][0-9])|([2][0-9][0-9][0-9])))$%';
		$regex['ym'] = '%^((([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))([- /.])(0[123456789]|10|11|12))$%';
		$regex['y'] = '%^(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))$%';
	
		$format = (is_array($format)) ? array_values($format) : array($format);
		foreach ($format as $key) {
			
			if($this->custom($val, $regex[$key]) === true) { return true; }
		}
		return false;
	}		
	
/*
 * Lazily popualate the IP address patterns used for validations
 *
 * @return void
 * @access private
 */
	function __populateIp() {
		
		if (!isset($this->__pattern['IPv6'])) {
			$pattern  = '((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}';
			$pattern .= '(:|((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})';
			$pattern .= '|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})';
			$pattern .= '(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)';
			$pattern .= '{4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
			$pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}';
			$pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|';
			$pattern .= '((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}';
			$pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
			$pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4})';
			$pattern .= '{0,4}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)';
			$pattern .= '|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4]';
			$pattern .= '\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4})';
			$pattern .= '{1,2})))|(((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})))(%.+)?';

			$this->__pattern['IPv6'] = $pattern;
		}
		if (!isset($this->__pattern['IPv4'])) {
			$pattern = '(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])';
			$this->__pattern['IPv4'] = $pattern;
		}
	}	
}