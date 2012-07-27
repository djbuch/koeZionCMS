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
		
		if(is_int($val)) return ($val < $min || $val > $max) ? false : true;
		else if(is_string($val)) return (strlen($val) < $min || strlen($val) > $max) ? false : true;
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
	function url($val, $strict = false) {
		
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
				asort($callback); //On réorganise le tableau en triant les index
				
				return call_user_func_array(array($this->model, $action), $callback); //On fait appel à la fonction
								
			} else { return false; }
		} else { return false; }
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