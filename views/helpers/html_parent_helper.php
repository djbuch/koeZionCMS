<?php
/**
 * @todo mutualiser la generation de lid pour ckeditor avec le helper form
 */
class HtmlParentHelper extends Helper {

//////////////////////////////////////////////////////////////////////////////////////////
//										VARIABLES										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Variable contenant les différents doctypes possibles
 *
 * @var 	array
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */	
	private $docTypes = array(
		'html4-strict'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
		'html4-trans'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
		'html4-frame'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
		'xhtml-strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		'xhtml-trans' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
		'xhtml-frame' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
		'xhtml11' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
	);	
	
/**
 * Variable contenant les différents fichiers CSS supplémentaires à charger
 *
 * @var 	array
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */	
	private $css = array();
	
/**
 * Variable contenant les différents fichiers JS supplémentaires à charger
 *
 * @var 	array
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */	
	private $js = array();
	
/**
 * Variable contenant les différentes variables a envoyer aux fichiers
 *
 * @var 	array
 * @access 	private
 * @author 	koéZionCMS
 * @version 0.1 - 07/03/2014 by FI
 */	
	private $jsParams = array();
	
//////////////////////////////////////////////////////////////////////////////////////////
//								FONCTIONS PUBLIQUES										//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Cette fonction permet de générer le doctype d'une page HTML 
 * Par défaut le doctype sera xhtml-strict
 *
 * @param varchar $type Type de document
 * @return varchar Code HTML du doctype
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 */	
	public function docType($type = 'xhtml-strict') {
		
		if(isset($this->docTypes[$type])) { return $this->docTypes[$type]; }
		return null;
	}
	
/**
 * Cette fonction permet le chargement des fichiers CSS utilisés dans le CMS (Front et Back)
 *
 * @param varchar $css		Chemin du fichier CSS à charger
 * @param boolean $inline 	Permet d'indiquer si il faut charger directement le css ou pas
 * @param boolean $merge 	Si vrai le tableau en paramètre de la fonction et celui de la classe seront "mergés"
 * @param boolean $minified	Permet d'indiquer à la fonction si il faut ou non compresser les CSS
 * @return varchar Balises html permettant l'insertion des CSS
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 03/06/2013 by TB : dossier "/templates/" par défaut ou "/css" si la chaîne commence par "/"
 * @version 0.3 - 16/10/2013 by FI : gestion des css pour les plugins
 * @version 0.4 - 11/12/2013 by FI : Suppression de la variable $plugin
 * @version 0.5 - 27/12/2013 by FI : Correction d'un bug lors du chargement en inline
 * @version 0.6 - 27/12/2013 by FI : Mise en place la possibilité de charger directement un fichier
 * @version 0.7 - 08/07/2014 by FI : Mise en place la possibilité de charger des fichiers libremement via le code F
 * @version 0.8 - 17/09/2014 by FI : Rajout de la possibilité de modifier les attributs rel, type et media
 * @version 0.9 - 06/08/2015 by SS : Correction dédoublonnage du tableau $css et si la valeur $css n'est pas vide
 * @version 1.0 - 14/08/2015 by FI : Mise en place de la gestion du protocole
 * @version 1.1 - 07/09/2015 by FI : Gestion des fichers externes via https
 */	
	public function css($css, $inline = false, $merge = true, $minified = false) {	
		
		if($inline) { $this->css = am($this->css, $css); } //Si on ne doit pas charger directement le fichier on le stocke dans la variable de classe
		else {
		
			if($merge) {
				
				$css = am($css, $this->css); //On récupère les les éventuels CSS qui sont dans la variable de classe
				$this->css = array(); //On vide le tableau dans le cas ou d'autres insertions seraient prévues
			}
			
			$css = array_unique($css);
			
			$html = ''; //Code HTML qui sera retourné
			foreach($css as $k => $v) { //Parcours de l'ensemble des fichiers
				
				if(!empty($v)) {
				
					$cssRel = 'stylesheet';
					$cssType = 'text/css';
					$cssMedia = 'all';
					if(is_array($v)) {
						
						$cssHref = $v['href'];
						if(isset($v['rel'])) { $cssRel = $v['rel']; } 
						if(isset($v['type'])) { $cssType = $v['type']; } 
						if(isset($v['media'])) { $cssMedia = $v['media']; }					
					} else {
						
						$cssHref = $v;
					}
					
					
					//On va vérifier si le css n'est pas externe
					if(substr_count($cssHref, 'http://') || substr_count($cssHref, 'https://')) { $cssPath = $cssHref; }
					else {
					
						$firstChar = $cssHref{0};
						switch($firstChar) {
											
							case 'P': //Plugin
								$cssFile = '/plugins/'.str_replace('P/', '', $cssHref);
							break;	
											
							case 'F': //Free, chargement libre
								$cssFile = str_replace('F/', '', $cssHref);
							break;				
							
							case '/': //Normal						
								$cssFile = '/css/'.$cssHref;						
							break;
							
							default: //Front						
								$cssFile = '/templates/'.$cssHref;						
							break;
						}						
						
						//if(!substr_count($cssHref, '.css')) { $cssFile.='.css'; }	//On teste si l'extension n'est pas déjà renseignée sinon on la rajoute
						$cssPath = Router::webroot($cssFile); //On génère le chemin vers le fichier
						
						//On teste si la navigation courante est en HTTPS
						if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https'; } 
						else { $protocol = 'http'; }

						//Génération du lien vers le fichier CSS
						if(!substr_count($cssPath, '.css')) { $cssPath = Router::url($cssPath, 'css', true, $protocol); }
						else { $cssPath = Router::url($cssPath, '', true, $protocol); }
					}
					$html .= "\t\t".'<link href="'.$cssPath.'" rel="'.$cssRel.'" type="'.$cssType.'" media="'.$cssMedia.'" />'."\n"; //On génère la balise de chargement
				}						
			}
			
			$html .= "\n"; //Rajout d'un saut de ligne			
			return $html; //On retourne le code HTML
		}
	}
	
/**
 * Cette fonction permet le chargement des fichiers javascript utilisés dans le CMS (Front et Back)
 * 
 * --> pour envoyer des variables depuis PHP aux fichier JS procéder comme suit
 * 
 * 1/ Charger les variables
 * 
 * $helpers['Html']->set_js_params(
 * 		array(
 * 			'INDEX1' => array(
 * 				'varName' => 'value',
 * 				'varName' => 'value',
 * 				...
 * 			),
 * 			'INDEX2' => array(
 * 				'varName' => 'value',
 * 				'varName' => 'value',
 * 				...
 * 			),
 * 			...
 * 		)
 * );
 * 
 * 2/ Charger les fichiers
 * 
 * $js = array(
 * 		'INDEX1' => 'PATH_TO_FILE',
 * 		'INDEX2' => 'PATH_TO_FILE',
 * 		...
 * );
 * echo $helpers['Html']->js($js, true);
 *
 * @param varchar $js		Chemin du fichier JS à charger
 * @param boolean $inline 	Permet d'indiquer si il faut charger directement le js ou pas
 * @param boolean $merge 	Si vrai le tableau en paramètre de la fonction et celui de la classe seront "mergés"
 * @param boolean $minified	Permet d'indiquer à la fonction si il faut ou non compresser les JS
 * @return varchar Balises html permettant l'insertion des JS
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 03/06/2013 by TB : dossier "/templates" par défaut ou "/js" si la chaîne commence par "/"
 * @version 0.3 - 16/10/2013 by FI : gestion des js pour les plugins
 * @version 0.4 - 11/12/2013 by FI : Suppression de la variable $plugin
 * @version 0.5 - 27/12/2013 by FI : Correction d'un bug lors du chargement en inline
 * @version 0.6 - 27/12/2013 by FI : Mise en place la possibilité de charger directement un fichier
 * @version 0.7 - 07/03/2014 by FI : Mise en place la possibilité de charger des variables pour les fichiers
 * @version 0.8 - 08/07/2014 by FI : Mise en place la possibilité de charger des fichiers libremement via le code F
 * @version 0.9 - 06/08/2015 by SS : Correction dédoublonnage du tableau $js et si la valeur $js n'est pas vide
 * @version 1.0 - 14/08/2015 by FI : Mise en place de la gestion du protocole
 * @version 1.1 - 07/09/2015 by FI : Gestion des fichers externes via https
 */	
	public function js($js, $inline = false, $merge = true, $minified = false) {
		
		if($inline) { $this->js = am($this->js, $js); } //Si on ne doit pas charger directement le fichier on le stocke dans la variable de classe
		else {
		
			if($merge) {
				
				$js = am($js, $this->js); //On récupère les les éventuels JS qui sont dans la variable de classe
				$this->js = array(); //On vide le tableau dans le cas ou d'autres insertions seraient prévues
			}
			
			$js = array_unique($js);
			
			$html = ''; //Code HTML qui sera retourné
			foreach($js as $k => $v) { //Parcours de l'ensemble des fichiers
				
				if(!empty($v)) {
					
					//On va vérifier si le js n'est pas externe
					if(substr_count($v, 'http://') || substr_count($v, 'https://')) { $jsPath = $v; }
					else {
					
						$firstChar = $v{0};
						switch($firstChar) {
											
							case 'P': //Plugin
								$jsFile = '/plugins/'.str_replace('P/', '', $v);
							break;	
											
							case 'F': //Free, chargement libre
								$jsFile = str_replace('F/', '', $v);
							break;				
							
							case '/': //Normal						
								$jsFile = '/js/'.$v;						
							break;
							
							default: //Front						
								$jsFile = '/templates/'.$v;						
							break;
						}
						
						//if(!substr_count($v, '.js')) { $jsFile.='.js'; } //On teste si l'extension n'est pas déjà renseignée sinon on la rajoute
						$jsPath = Router::webroot($jsFile); //On génère le chemin vers le fichier
																		
						//On teste si la navigation courante est en HTTPS
						if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { $protocol = 'https'; } 
						else { $protocol = 'http'; }

						//Génération du lien vers le fichier JS
						if(!substr_count($jsPath, '.js')) { $jsPath = Router::url($jsPath, 'js', true, $protocol); }
						else { $jsPath = Router::url($jsPath, '', true, $protocol); }
					}				
					
					//On contrôle si il faut charger d'éventuels paramètres au fichier courant
					//On charge les données avant le fichier
					if(!empty($this->jsParams) && isset($this->jsParams[$k])) {
						
						$html .= "\t\t".'<script type="text/javascript">';
						foreach($this->jsParams[$k] as $paramName => $paramValue) { $html .= 'var '.$paramName.' = '.$paramValue.';'."\n"; }
						$html .= '</script>'."\n"; //On génère la balise de chargement
					}
					$html .= "\t\t".'<script src="'.$jsPath.'" type="text/javascript"></script>'."\n"; //On génère la balise de chargement
				}			
			}			
			
			$html .= "\n"; //Rajout d'un saut de ligne
			return $html; //On retourne le code HTML
		}
	}
	
/**
 * Cette fonction permet la mise à jour du tableau de paramètres a passer aux fichiers JS
 * @param array $jsParams Tableaux des paramètres
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 07/03/2014 by FI
 */	
	public function set_js_params($jsParams) {
		
		$this->jsParams = am($this->jsParams, $jsParams);		
	}
	
/**
 * Cette fonction permet l'insertion de fichier CSS ou JS supplémentaire directement depuis le dossier upload
 * 
 * Appel : 
 * 	echo $helpers['Html']->upload_additional_files('CSS');
 * 	echo $helpers['Html']->upload_additional_files('JS');
 * 
 * @param	varchar $type 	CSS ou JS
 * @param	array 	$except Tableau contenant la liste des fichiers à ignorer
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 19/03/2014 by FI
 * @deprecated - Since 31/10/2014 by FI - Suppression de cette fonctionnalité car trop lourde à gérer
 */	
	public function upload_additional_files($type, $except = null) {
				
		$html = '';
		if($type == 'CSS') {
			
			$moreCSSPath = UPLOAD.DS.'files'.DS.'css';
			if(is_dir($moreCSSPath)) {
			
				foreach(FileAndDir::directoryContent($moreCSSPath) as $moreCSS) {
					
					if(!isset($except) || (isset($except) && !in_array($moreCSS, $except))) { 

						//On génère la balise de chargement
						$html .= "\t\t".'<link href="'.Router::webroot('/upload/files/css/'.$moreCSS).'" rel="stylesheet" type="text/css" />'."\n";
					} 
				}
			}			
			
		} else if($type == 'JS') {
			
			$moreJSPath = UPLOAD.DS.'files'.DS.'js';
			if(is_dir($moreJSPath)) {
			
				foreach(FileAndDir::directoryContent($moreJSPath) as $moreJS) {
					
					if(!isset($except[$moreJS])) {
						
						//On génère la balise de chargement
						$html .= "\t\t".'<script src="'.Router::webroot('/upload/files/js/'.$moreJS).'" type="text/javascript"></script>'."\n";
					}
				}
			}			
		}	

		return $html;
	}
	
/**
 * Cette fonction est utilisée pour afficher des images
 *
 * @param varchar 	$image		Chemin de l'image
 * @param array 	$options	Options possibles pour l'image (alt, etc...)
 * @return varchar Balises html permettant l'insertion de l'image
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 03/06/2013 by TB : dossier "/templates/" par défaut ou "/img" si la chaîne commence par "/"
 */	
	public function img($image, $options = null) {

		$attr = '';
		if(isset($options)) { foreach($options as $k => $v) { $attr .= ' '.$k.'="'.$v.'"'; } }	
		$chemin = $image{0} == "/" ? '/img' : '/templates/';
		$html = '<img src="'.BASE_URL.$chemin.$image.'" '.$attr.' />';			
		return $html;
	}
	
/**
 * Cette fonction est utilisée pour afficher le code Google Analytics
 * Le code n'est retourné que si l'on est pas en local afin d'éviter les logs inutiles par Google
 *
 * @param 	varchar 	$code		Code communiqué par Google
 * @return 	varchar 	Code Analytics
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 23/05/2013 by TB ajout de l'adresse 127.0.0.1 dans les adresses à ne pas traiter
 */	
	public function analytics($code) {


        if(!empty($code) && !in_array($_SERVER["HTTP_HOST"], array('localhost', '127.0.0.1'))) { return $code; }
        else { return ''; }
    }
}