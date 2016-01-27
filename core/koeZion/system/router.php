<?php
/**
 * Parse les urls
 */
class Router {
    
	static $routes = array();
	
	static $prefixes = array();
	
	static $extensions = array('.html', '.csv', '.xml', '.txt', '.sql', '.pot');	
	
	
/**
 * Fonction qui permet de déterminer la liste des prefixes
 *
 */	
	static function prefix($url, $prefix) {
		
		self::$prefixes[$url] = $prefix; //On indique la correspondance entre le prefixe de l'url et le prefix des fonctions dans les controllers
	}
	
/**
 * Permet de paser une url
 * 
 * @param 	varchar 	$url url a parser
 * @param 	object 	$request objet de type Request
 * @return 	tableau contenant les paramètres 
 * @version 0.1 - 28/04/2015 by FI - Test de l'existence d'un contrôleur isset($params[0])
 */
	static function parse($url, $request) {
		
		$url = trim($url, '/'); //On enlève les / en début et fin de chaine
		$url = str_replace(Router::$extensions, '', $url); //On enlève l'extension
		
		//Gestion du cas j'arrive sur la racine
		if(empty($url)) { $url = Router::$routes[0]['url']; }
		else {

			$match = false; //par défaut ça ne match pas
			
			//On va analyser les urls et vérifier que l'on ne tombe pas dans le cas d'une dans le catcher
			foreach(Router::$routes as $v) {
				
				if(!$match && preg_match($v['redirreg'], $url, $match)) {
					
					$url = $v['origin'];
					foreach($match as $k => $v) { $url = str_replace(':'.$k.':', $v, $url); }
					$match = true;					
				}			
			}
		}		
		
		$params = explode('/', $url); //On récupère l'url sous forme de tableau
		
		//Le systeme d'admin n'ayant pas besoin d'url réécrite on peut se positionner à ce niveau
		//On va tester si le premier paramètre de l'url figure dans le tableau des prefixes
		if(in_array($params[0], array_keys(self::$prefixes))) {
			
			//Si oui on va initialiser une nouvelle valeur à request
			$request->prefix = self::$prefixes[$params[0]];
			array_shift($params); //Par défaut params commence par la clé du prefixe on va donc décaller d'un valeur pour qu'il commence bien par le controller
		}		
		
		$request->controller 	= isset($params[0]) ? $params[0] : 'Unset'; //Initialisation du controller
		$request->action 		= isset($params[1]) ? $params[1] : 'index'; //On va tester si une action est demandée, sinon on affecte la fonction index par défaut
		
		//HACK pour éviter que les fonctions du back ne puissent etre accessible avec une url du type front
		//ex --> post/admin_edit/19
		foreach(self::$prefixes as $k => $v) { //Récupération des préfixes
			
			if(strpos($request->action, $v.'_') === 0) { //Si il est présent dans la fonction
				
				$request->prefix = $v; //On injecte le prefixe dans le request
				$request->action = str_replace($v.'_', '', $request->action); //Et on remplace l'action
			}
		}
		
		$request->params = array_slice($params, 2); //On fait passer ensuite l'ensemble des éventuels paramètres au tableau
		return true;
	}

/**
 * Cette fonction va initialiser dans la classe une variable contenant la liste des urls possibles
 *
 * @param varchar $redir Url de redirection
 * @param varchar $url Url de départ
 */	
	static function connect($redir, $url) {
		
		$r 				= array();		
		$r['params'] 	= array();
		$r['url'] 		= $url;
		
		//$r['redir'] = $redir;
				
		//On va retravailler cette règle pour avoir un tableau de résultats dans la fonction url qui soit plus cohérent
		//clé avec la valeur du champ et non une valeur numérique				
		//on récupère deux valeurs : 
		// - la premiere récupère tout ce qui est avant les : lettres et chiffres répétés plusieurs fois
		// - la seconde tout ce qui est après et qui se termine par un /
		//On récupère ensuite ces valeurs dans ${1} et ${2}
		//et on va transformet pour avoir ?P<id> --> permet de récupérer dans le résultat de l'expression régulière les valeurs avec comme clé les variables
		//On va travailler en deux fois la premiere fois on va modifier $url puis apres on travaille sur $r['origin'] pour eviter les erreur avec les \
		
		$r['originreg'] = preg_replace('/([a-z0-9]+):([^\/]+)/', '${1}:(?P<${1}>${2})', $url);
		$r['originreg'] = str_replace('/*', '(?P<args>/?.*)', $r['originreg']);
		$r['originreg'] = '/^'.str_replace('/', '\/', $r['originreg']).'$/'; 		
		$r['origin'] 	= preg_replace('/([a-z0-9]+):([^\/]+)/', ':${1}:', $url);
		$r['origin'] 	= str_replace('/*', ':args:', $r['origin']);
		
		//On va analyser l'url passée au Router dans le fichier conf.php 
		$params = explode('/', $url); 
		foreach($params as $k => $v) {
			
			//On recherche les valeurs contenants un :
			if(strpos($v, ':')) {
								
				$p 					= explode(':', $v); //On va refaire un explode sur :
				$r['params'][$p[0]] = $p[1]; //On ajoute les parametres à $r
				
			}
		}
		
		//On va créer une nouvelle variable qui va contenir l'url de base avec les expression reg de remplacement
		$r['redirreg'] = $redir;
		
		//Traitement pour les appels direct d'action
		$r['redirreg'] = str_replace('/*', '(?P<args>/?.*)', $r['redirreg']); 
		
		foreach($r['params'] as $k => $v) { $r['redirreg'] = str_replace(":$k", "(?P<$k>$v)", $r['redirreg']); }
		
		$r['redirreg'] 	= '/^'.str_replace('/', '\/', $r['redirreg']).'$/'; //On va convertir le format de l'url pour qu'il soit compréhensible pour le preg_match		
		$r['redir'] 	= preg_replace('/:([a-z0-9]+)/', ':${1}:', $redir);
		$r['redir'] 	= str_replace('/*', ':args:', $r['redir']); //On va convertir le format de l'url pour qu'il soit compréhensible pour le preg_match		
		self::$routes[] = $r; //On injecte les routes dans le tableau de classe
	}
	
/**
 * Permet de construire une url
 *
 * @param 	varchar 	$url 		Url de la page à atteindre
 * @param 	mixed 	$extension 	Indique si il faut ou non mettre l'extension html (faux si pas d'url)
 * @param 	boolean 	$fullUrl 	Indique si il faut retourner une url complète (avec http et le HOST)
 * @return 	varchar Url formatée
 * @version 0.1 - 28/05/2015 by FI - Rajout de $protocol
 * @version 0.2 - 14/08/2015 by FI - Mise en place de la récupération du protocol via Router::get_url_protocol
 */		
	static function url($url = '', $extension = 'html', $fullUrl = true, $protocol = null) {
				
		trim($url, '/');
		
		//Parcours de toutes les routes
		foreach(self::$routes as $v) {
			
			//Si la route parcourue correspond à une url stockée dans la classe
			if(preg_match($v['originreg'], $url, $match)) {
				
				$url = $v['redir'];
				
				//On va parcourir les résultats
				foreach($match as $k => $w) {
					
					//Et on ne travaille que sur les clés non numérique
					//if(!is_numeric($k)) {
						
						$url = str_replace(":$k:", $w, $url); //On remplace les clés par leurs valeurs
					//}
				}
				//$v['args'] contient la liste des arguments
				//return BASE_URL.str_replace('//', '/', '/'.$v['redir']).$match['args'];
			}
		}
		
		//On va vérifier si un prefixe n'est pas à mettre en place pour une url
		foreach(self::$prefixes as $k => $v) {
			
			//Si il trouve une correspondance de prefixe
			if(strpos($url, $v) === 0) { $url = str_replace($v, $k, $url); } //On le remplace dans l'url ex : Router::prefix('cockpit', 'admin');
		}
		
		$url = str_replace('//', '/', '/'.$url);
		
		//On check 
		//	que l'url ne commence pas par /
		//	que l'extension n'est pas vide
		//	et que l'url ne contient pas déhà l'extension
		if($url != '/' && !empty($extension) && !substr_count($url, '.'.$extension)) { $url .= '.'.$extension; } //Cas ou on est pas sur la racine du site
		
		if(!isset($protocol)) { $protocol = Router::get_url_protocol($url, $protocol); } //Récupération du protocole
				
		$url = str_replace(BASE_URL, '', $url); //On va supprimer la valeur de BASE_URL dans la variable $url 
		
		if($fullUrl) { $url2Return = $protocol.'://'.$_SERVER['HTTP_HOST'].BASE_URL.$url; }
		else { $url2Return = BASE_URL.$url; }
		
		return $url2Return;
	}
	
/**
 * Lien vers le dossier webroot
 *
 * @param 	varchar $url Url du lien
 * @return 	varchar Url formatée
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 14/08/2015 by FI - Suppression de l'ajout de BASER_URL dans le retour suite à la mise en place de l'utilisation de Router::url pour générer les liens des css et des js
 * @todo 	A bien controller
 * 
 */	
	static function webroot($url) {
			
		trim($url, '/');
		//return BASE_URL.str_replace('//', '/', '/'.$url);		
		return str_replace('//', '/', '/'.$url);		
	}
	
/**
 * Fonction chargée de tester si l'HTTPS est activé et qu'elles sont les urls protégées par le certificat
 * 
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 14/08/2015 by FI 
 */	
	static function get_url_protocol($url) {
			
		$protocol = 'http';
		
		//Récupération des configurations du coeur
		//require_once(LIBS.DS.'config_magik.php');
		//$cfg = new ConfigMagik(CONFIGS_FILES.DS.'core.ini', true, false);
		//$coreConfs = $cfg->keys_values();
		
		//On check si la navigation sécurisée est activée (HTTPS)
		if(defined('HTTP_ACTIVATED') && HTTP_ACTIVATED) {
						
			$secureUrls = explode(';', HTTP_SECURE_URLS); //On récupère la liste des urls qui sont sécurisées sous forme de tableau
			
			//Parcours de la liste des urls
			foreach($secureUrls as $secureUrl) {
				
				//Si on retrouve dans l'url courante une portion de l'url à sécuriser alors on active le mode HTTPS
				if(substr_count($url, $secureUrl)) { $protocol = 'https'; }				
			}
		}
		return $protocol;
	}
}