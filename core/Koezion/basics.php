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