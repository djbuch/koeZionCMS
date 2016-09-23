<?php
/**
 * Print_r convenience function, which prints out <PRE> tags around
 * the output of given array. Similar to debug().
 */
	function pr($var, $start = null, $end = null, $die = 0) {
		
		//if(Configure::read('debug') > 0) {			
			
			$debug = debug_backtrace();
			
			echo '<div id="pr_element" style="background-color: #EBEBEB; border: 1px dashed black; padding: 10px;">';
			
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
		//}
	}

/**
 * Cette fonction permet de faire une redirection de page
 *
 * @param varchar $url Url de redirection
 * @param integer $code Code HTTP
 * @param varchar $params Paramètres supplémentaires à passer à l'url
 * @param boolean $external Indique si l'url est externe au site 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 23/12/2011
 * @version 0.2 - 02/05/2012 - Test sur l'url pour savoir si il y a http:// dedans 
 * @version 0.3 - 06/11/2012 - Rajout de la possibilité de passer des paramètres 
 * @version 0.4 - 29/03/2014 - Déplacement de cette fonction de la classe Controller vers la classe Object
 * @version 0.5 - 24/02/2015 - Rajout de la variable $external
 * @version 0.6 - 13/08/2015 - Test sur l'url pour savoir si il y a https:// dedans 
 */	
	function redirect($url, $code = null, $params = null, $external = false) {
		 
		//Code de redirection possibles
		$http_codes = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Time-out',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Large',
			415 => 'Unsupported Media Type',
			416 => 'Requested range not satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Time-out'
		);
		//Si un code est passé on l'indique dans le header
		if(isset($code)) { header("HTTP/1.0 ".$code." ".$http_codes[$code]); }

		//On contrôle que l'url de redirection ne commence pas par http
		if(!substr_count($url, 'http://') && !substr_count($url, 'https://') && !$external) { $url = Router::url($url); }

		//On rajoute les paramètres éventuels
		if(isset($params)) {$url .= '?'.$params; }
		header("Location: ".$url);
		
		die(); //Pour éviter que l'exécution de la fonction ne continue
	}
	
/**
 * Cette fonction permet la récupération des données du site courant
 *
 * @return 	varchar Url du site à prendre en compte
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 02/05/2012 by FI
 * @version 0.2 - 14/06/2012 by FI - Modification de la récupération du site pour la boucle locale - On récupère le premier site de la liste et plus celui avec l'id 1 pour éviter les éventuelles erreurs
 * @version 0.3 - 04/09/2012 by FI - Mise en place d'un passage de paramètre en GET pour pouvoir changer de site en local
 * @version 0.4 - 02/04/2014 by FI - Mise en place d'un passage de paramètre en GET pour pouvoir changer le host du site en local
 * @version 0.5 - 21/05/2014 by FI - Mise en place d'un passage de paramètre dans la fonction pour pouvoir changer le host du site
 * @version 0.6 - 23/04/2015 by FI - Rajout de la condition OR dans la récupération du site courant afin de traiter également les alias d'url
 * @version 0.7 - 24/04/2015 by FI - Gestion de la traduction
 * @version 0.8 - 18/04/2016 by FI - Déplacement des fichiers de traduction dans le dossier de la langue si celle-ci est définie
 * @version 0.9 - 05/09/2016 by FI - Correction récupération des données du site Internet rajout de http:// dans $websiteConditions car cela posait problème sur des adresses du type a-b.domaine.com et b.domaine.com (l'une étant une sous partie de l'autre)
 * @version 1.0 - 23/09/2016 by FI - Déplacement de cette fonction depuis le composant website
 */
	function get_website_datas($hackWsHost = null) {
				
		//Si un hack du host est passé dans l'url on le stocke dans la variable de session
		if(isset($_GET['hack_ws_host'])) { Session::write('Frontoffice.hack_ws_host', $_GET['hack_ws_host']); }
		
		//On va contrôler que le hack du host n'est pas passé en paramètre de la fonction si c'est le cas il prendra le dessus sur celui dans la variable de session
		$hackWsHost = isset($hackWsHost) ? $hackWsHost : Session::read('Frontoffice.hack_ws_host');
		
		$httpHost = (isset($hackWsHost) && !empty($hackWsHost)) ? $hackWsHost : $_SERVER["HTTP_HOST"]; //Récupération de l'url		
				
		$cacheFolder 	= TMP.DS.'cache'.DS.'variables'.DS.'Websites'.DS;
		$cacheFile 		= $httpHost;
 
		//On contrôle si le modèle est traduit
		//Si c'est le cas on va récupérer les fichiers de cache dans un dossier spécifique à la langue
		if(!class_exists('Website')) { Configure::import(MODELS.DS.'website'); }
		$websiteModel = new Website(null);
		
		//$this->load_model('Website'); //Chargement du modèle
		if($websiteModel->fieldsToTranslate) { $cacheFolder .= DEFAULT_LANGUAGE.DS; }
		
		$website = Cache::exists_cache_file($cacheFolder, $cacheFile);
	
		if(!$website) {
	
			//HACK SPECIAL LOCAL POUR CHANGER DE SITE pour permettre la passage de l'identifiant du site en paramètre
			if(isset($_GET['hack_ws_id'])) { Session::write('Frontoffice.hack_ws_id', $_GET['hack_ws_id']); }
			$hackWsId = Session::read('Frontoffice.hack_ws_id');
	
			if($httpHost == 'localhost' || $httpHost == '127.0.0.1') {
	
				if($hackWsId) { $websiteId = $hackWsId; }
				else {
	
					$websites = $websiteModel->findList(array('order' => 'id ASC'));
					$websiteId = current(array_keys($websites));
				}
	
				$websiteConditions = array('conditions' => array('id' => $websiteId, 'online' => 1));
	
			} else {
	
				if($hackWsId) { $websiteConditions = array('conditions' => array('id' => $hackWsId, 'online' => 1)); }
				else {

					//On récupère les sites dont l'url ou un alias est égal à $httpHost
					$websiteConditions = array('conditions' => array(
						'OR' => array(
							"url LIKE '%http://".$httpHost."%'",
							"url_alias LIKE '%http://".$httpHost."%'",
						),
						'online' => 1
					)); 
				}
			}
			
			$website = $websiteModel->findFirst($websiteConditions);
	
			Cache::create_cache_file($cacheFolder, $cacheFile, $website);
		}
	
		if(!defined('CURRENT_WEBSITE_ID')) { define('CURRENT_WEBSITE_ID', $website['id']); }
		
		return array(
			'layout' => 	$website['tpl_layout'],
			'website' =>	$website 	
		);
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
	
/**
 * Gestion de la fonction _() utilisée par GetText pour la localisation
 * On test si le plugin localization n'est pas en cours d'utilisation car la fonction _() est déjà implémentée dans la librairie GetText
 */	
	if(!in_array('localization', get_plugins_connectors()) && !function_exists('_')) {	
		
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
	
/**
 * Cette fonction permet de récupérer l'ensemble des données de paramétrage des connecteurs de plugin
 * 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 20/01/2015 by FI 
 */	
	function get_plugins_connectors() {

		$pluginsConnectors = array();
		$pluginsConnectorsPath = CONFIGS_PLUGINS.DS.'connectors';
		if(is_dir($pluginsConnectorsPath)) {
		
			foreach(FileAndDir::directoryContent($pluginsConnectorsPath) as $pluginsConnectorsFile) { 
				
				//Si le fichier alternatif existe on le charge
				if(file_exists(TMP.DS.'rewrite'.DS.'connectors'.DS.$pluginsConnectorsFile)) { include(TMP.DS.'rewrite'.DS.'connectors'.DS.$pluginsConnectorsFile); }
				else { include($pluginsConnectorsPath.DS.$pluginsConnectorsFile); } 
			}			
		}
		
		//On formate le tableau des connecteurs
		$pluginsConnectorsFormat = array();
		foreach($pluginsConnectors as $k => $v) {
			
			//Si on ne récupère pas de tableau du connecteur cela veut dire qu'il ne faut pas redéfinir le dossier de stockage on le force donc avec PLUGINS
			if(!is_array($v)) { 
				
				$pluginsConnectorsFormat[$k] = array(
					'plugin_folder' => $v,
					'plugin_path' => PLUGINS
				);
			}
			else { $pluginsConnectorsFormat[$k] = $v; } //Sinon on récupère les données stockées dans le fichier			
		}
		
		return $pluginsConnectorsFormat;
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
	
/**
 * Serialize et unserialize des données
 * http://www.jackreichert.com/2014/02/02/handling-a-php-unserialize-offset-error/
 * BASE64 pose pb après les tests effectués à creuser  
 */	
	function _serialize($datas) { 

		//return base64_encode(serialize($datas)); 
		return serialize($datas); 
	}
	function _unserialize($datas) { 
		
		//return unserialize(base64_decode($datas)); 
		return unserialize($datas); 
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