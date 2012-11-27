<?php
function debug($var, $die = 0) {
	
	if(Configure::read('debug') > 0) {
	
		//Affichage de la ligne ayant fait appel au debug
		$debug = debug_backtrace();
		echo '<p style="background-color: #EBEBEB; border: 1px dashed black; padding: 10px;"><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false;"><strong>'.$debug[0]['file'].'</strong> ligne '.$debug[0]['line'].'</a></p>';
		
		echo '<ol style="display:none; background-color: #EBEBEB; border: 1px dashed black; padding: 10px;">';
		foreach($debug as $k => $v) {
			if($k > 0) {
				
				echo '<li><strong>'.$v['file'].'</strong> ligne '.$v['line'].'</li>';
			}		
		}
		echo '</ol>';
		
		
		//Affichage du debug
		pr($var);
		
		if($die) { die(); }
	}
}

/**
 * Print_r convenience function, which prints out <PRE> tags around
 * the output of given array. Similar to debug().
 */
	function pr($var) {
		
		if (Configure::read('debug') > 0) {
			
			$debug = debug_backtrace();
			echo '<pre style="background-color: #EBEBEB; border: 1px dashed black; padding: 10px;">';			
			print_r('[FILE] : '.$debug[0]['file']."\n");
			print_r('[LINE] : '.$debug[0]['line']."\n\n");
			print_r('[RESULTAT] : '."\n");
			print_r($var);
			echo '</pre>';
		}
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