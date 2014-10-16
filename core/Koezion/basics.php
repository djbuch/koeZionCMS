<?php
/**
 * Print_r convenience function, which prints out <PRE> tags around
 * the output of given array. Similar to debug().
 */
	function pr($var, $start = null, $end = null, $die = 0) {
		
		if (Configure::read('debug') > 0) {			
			
			$debug = debug_backtrace();
			
			echo '<div style="background-color: #EBEBEB; border: 1px dashed black; padding: 10px;">';
			
				echo '<pre style="background-color:#F0F0F0;border:1px solid #E3E3E3;color:#606362;font-size:12px;padding:10px;">';		
					print_r('[FILE] : '.$debug[0]['file']."\n");
					print_r('[LINE] : '.$debug[0]['line']."\n\n");
					print_r('[RESULTAT] : '."\n");
					if(isset($start)) { print_r($start."\n"); }
					print_r($var);
					if(isset($end)) { print_r("\n".$end); }
				echo '</pre>';
				
				//DEBUG BACKTRACE
				echo '<p><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false;"><strong>Debug backtrace</strong></a></p>';			
				echo '<ol style="display:none;margin-left:20px;">';
				foreach($debug as $k => $v) {
				
					echo '<li style="margin-bottom:5px;"><strong>[FILE] : </strong>'.$v['file'].'<strong><br />[LINE] : </strong> '.$v['line'].'</li>';
				}
				echo '</ol>';
			echo '</div>';
			
			if($die) { die(); }
		}
	}

/**
 * Cette fonction permet de récupérer le chemin d'un fichier par rapport au dossier d'origine du serveur (et non du site)
 * Pratique, par exemple, pour charger une image chargée depuis le backoffice dans un pdf
 * 
 * @param varchar $file Chemin 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/10/2014 by FI 
 */	
	function get_path_from_server_origin($file) {

		$file = str_replace(BASE_URL, '', $file);
		$file = str_replace('/', DS, $file);
		return ROOT.$file;
	}	
	
	if(!function_exists('_')) {	
		
		function _($text) { return $text; }
	}
	
/**
 * Convenience method for echo().
 *
 * @param string $text String to echo
 * @link http://book.cakephp.org/view/1129/e
 * @deprecated Will be removed in 2.0
 */
	function e($text) {
		echo $text;
	}
	
	/**
 * Convenience method for strtolower().
 *
 * @param string $str String to lowercase
 * @return string Lowercased string
 * @link http://book.cakephp.org/view/1134/low
 * @deprecated Will be removed in 2.0
 */
	function low($str) {
		return strtolower($str);
	}
		
	/**
 * Convenience method for strtoupper().
 *
 * @param string $str String to uppercase
 * @return string Uppercased string
 * @link http://book.cakephp.org/view/1139/up
 * @deprecated Will be removed in 2.0
 */
	function up($str) {
		return strtoupper($str);
	}

	/**
 * Merge a group of arrays
 *
 * @param array First array
 * @param array Second array
 * @param array Third array
 * @param array Etc...
 * @return array All array parameters merged into one
 * @link http://book.cakephp.org/view/1124/am
 */
	function am() {
		$r = array();
		$args = func_get_args();
		foreach ($args as $a) {
			if (!is_array($a)) {
				$a = array($a);
			}
			$r = array_merge($r, $a);
		}
		return $r;
	}

	/**
 * Recursively strips slashes from all values in an array
 *
 * @param array $values Array of values to strip slashes
 * @return mixed What is returned from calling stripslashes
 * @link http://book.cakephp.org/view/1138/stripslashes_deep
 */
	function stripslashes_deep($values) {
		if (is_array($values)) {
			foreach ($values as $key => $value) {
				$values[$key] = stripslashes_deep($value);
			}
		} else {
			$values = stripslashes($values);
		}
		return $values;
	}	
	
	/*function array_to_object($tab) {
	
		$data = new stdClass ;
		if(is_array($tab) && !empty($tab)) {
			
			foreach($tab as $key => $val) {
				
				if(is_array($val))
					$data->$key = array_to_object($val);
				else
					$data->$key = $val ;
			}
		}
		
		return $data ;
	}*/
	
	function get_plugins_connectors() {

		$pluginsConnectors = array();
		$pluginsConnectorsPath = CONFIGS.DS.'plugins'.DS.'connectors';
		if(is_dir($pluginsConnectorsPath)) {
		
			foreach(FileAndDir::directoryContent($pluginsConnectorsPath) as $pluginsConnectorsFile) { include($pluginsConnectorsPath.DS.$pluginsConnectorsFile); }			
		}
		
		return $pluginsConnectors;
	}
	
/**
 * http://blog.studiovitamine.com/actualite,107,fr/php-ajouter-ou-modifier-un-parametre-get-dans-l-url,304,fr.html?id=214
 * A voir pour améliorer
 * @param unknown_type $url
 * @param unknown_type $paramNom
 * @param unknown_type $paramValeur
 */	
	function ajouter_parametre_get($url, $paramNom, $paramValeur) {
		
		$urlFinal = "";
		if($paramNom=="") { $urlFinal = $url; }
		else { 
			$t_url = explode("?", $url); 
			if(count($t_url) == 1) {
				
				//Pas de queryString
				$urlFinal .= $url;
				if(substr($url, strlen($url)-1, strlen($url)) != "/") {
					
					$t_url2 = explode("/", $url);
					if(preg_match("/./", $t_url2[count($t_url2)-1]) == false){ $urlFinal .= "/"; }
				}
				$urlFinal .= "?".$paramNom."=".$paramValeur;
				
			} else if(count($t_url) == 2) {
				
				//Il y a une queryString
				$paramAAjouterPresentDansQueryString = "non";
				$t_queryString = explode("&", $t_url[1]);
				
				foreach($t_queryString as $cle => $coupleNomValeur) {
					
					$t_param = explode("=", $coupleNomValeur);
					if($t_param[0] == $paramNom) { $paramAAjouterPresentDansQueryString = "oui"; }
				}
				
				if($paramAAjouterPresentDansQueryString == "non") {
					
					//Le parametre à ajouter n'existe pas encore dans la queryString
					$urlFinal = $url."&".$paramNom."=".$paramValeur;
					
				} else if($paramAAjouterPresentDansQueryString == "oui") {
					
					//Le parametre à ajouter existe déjà dans la queryString
					//Donc on va reconstruire l'URL
					$urlFinal = $t_url[0]."?";
					foreach($t_queryString as $cle => $coupleNomValeur) {
						
						if($cle > 0) { $urlFinal .= "&"; }
						$t_coupleNomValeur = explode("=", $coupleNomValeur);
						if($t_coupleNomValeur[0] == $paramNom) { $urlFinal .= $paramNom."=".$paramValeur; }
						else{ $urlFinal .= $t_coupleNomValeur[0]."=".$t_coupleNomValeur[1]; }
					}
				}
			}
		}
		return $urlFinal;
	}
	
/*
	

		$debug = debug_backtrace();
		$txt = '[FILE] : '.$debug[0]['file']."\n";
		$txt .= '[LINE] : '.$debug[0]['line']."\n";
		$txt .= '[CONTROLLER] : '.get_class($this)."\n";
		
		foreach($debug as $k => $v) {
		
			$txt .= '[FILE] : '.$v['file']."\n".'[LINE] : '.$v['line']."\n";
		}
		$txt .= '======================================================================='."\n";
		file_put_contents( TMP.DS.'controllers.log', $txt."\n", FILE_APPEND);	
*/