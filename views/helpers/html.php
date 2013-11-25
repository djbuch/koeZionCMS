<?php
$aclsHelper = PLUGINS.DS.'acls'.DS.'views'.DS.'helpers'.DS.'acls'.DS.'backoffice_html.php';
if(file_exists($aclsHelper)) { require_once($aclsHelper); } 
else { require_once('backoffice_html.php'); }

/**
 * @todo mutualiser la generation de lid pour ckeditor avec le helper form
 */
class Html extends BackofficeHtml {

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
 * @param boolean $minified	Permet d'indiquer à la fonction si il faut ou non compresser les CSS
 * @param boolean $plugin	Permet d'indiquer si il s'agit de CSS utilisé par un plugin
 * @return varchar Balises html permettant l'insertion des CSS
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 03/06/2013 by TB : dossier "/templates/" par défaut ou "/css" si la chaîne commence par "/"
 * @version 0.3 - 16/10/2013 by FI : gestion des css pour les plugins
 */	
	public function css($css, $inline = false, $minified = false, $plugin = false) {	
		
		if($inline) { $this->css = am($this->css, $css); } //Si on ne doit pas charger directement le fichier on le stocke dans la variable de classe
		else {
		
			$css = am($css, $this->css); //On récupère les les éventuels CSS qui sont dans la variable de classe			
			$html = ''; //Code HTML qui sera retourné
			foreach($css as $v) { //Parcours de l'ensemble des fichiers
								
				$firstChar = $v{0};
				switch($firstChar) {
									
					case 'P': //Plugin
						$cssFile = '/plugins/'.str_replace('P/', '', $v);
					break;					
					
					case '/': //Normal						
						$cssFile = '/css/'.$v;						
					break;
					
					default: //Front						
						$cssFile = '/templates/'.$v;						
					break;
				}
				
				if(!substr_count($v, '.css')) { $cssFile.='.css'; }	//On teste si l'extension n'est pas déjà renseignée sinon on la rajoute			
				$cssPath = Router::webroot($cssFile); //On génère le chemin vers le fichier
				$html .= "\t\t".'<link href="'.$cssPath.'" rel="stylesheet" type="text/css" />'."\n"; //On génère la balise de chargement						
			}
			$html .= "\n"; //Rajout d'un saut de ligne
			return $html; //On retourne le code HTML
		}
			
		/*if(!$minified) {
		} else {
			
			$html = '';
			foreach($css as $v) {
			
				$v = str_replace('/', DS, $v);
				$cssFile = CSS.DS.$v.'.css';
				$cssContent = CssMin::minify(file_get_contents($cssFile));
				$cssContent = str_replace('../', '', $cssContent);
				$html .= $cssContent."\n";
			}			
			
			return '<link href="css.php" rel="stylesheet" type="text/css" />'."\n";
		}*/
	}
	
/**
 * Cette fonction permet le chargement des fichiers javascript utilisés dans le CMS (Front et Back)
 *
 * @param varchar $js		Chemin du fichier JS à charger
 * @param boolean $inline 	Permet d'indiquer si il faut charger directement le js ou pas
 * @param boolean $minified	Permet d'indiquer à la fonction si il faut ou non compresser les JS
 * @param boolean $plugin	Permet d'indiquer si il s'agit de JS utilisé par un plugin
 * @return varchar Balises html permettant l'insertion des JS
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 03/06/2013 by TB : dossier "/templates" par défaut ou "/js" si la chaîne commence par "/"
 * @version 0.3 - 16/10/2013 by FI : gestion des js pour les plugins
 */	
	public function js($js, $inline = false, $minified = false, $plugin = false) {
		
		if($inline) { $this->js = am($this->js, $js); } //Si on ne doit pas charger directement le fichier on le stocke dans la variable de classe
		else {
		
			$js = am($js, $this->js); //On récupère les les éventuels JS qui sont dans la variable de classe
			$html = ''; //Code HTML qui sera retourné
			foreach($js as $v) { //Parcours de l'ensemble des fichiers
				
				$firstChar = $v{0};
				switch($firstChar) {
									
					case 'P': //Plugin
						$jsFile = '/plugins/'.str_replace('P/', '', $v);
					break;					
					
					case '/': //Normal						
						$jsFile = '/js/'.$v;						
					break;
					
					default: //Front						
						$jsFile = '/templates/'.$v;						
					break;
				}
				
				if(!substr_count($v, '.js')) { $jsFile.='.js'; } //On teste si l'extension n'est pas déjà renseignée sinon on la rajoute
				$jsPath = Router::webroot($jsFile); //On génère le chemin vers le fichier
				$html .= "\t\t".'<script src="'.$jsPath.'" type="text/javascript"></script>'."\n"; //On génère la balise de chargement			
			}	
			$html .= "\n"; //Rajout d'un saut de ligne
			return $html; //On retourne le code HTML
		}
	}
	
/**
 * Cette fonction est utilisée pour générer le menu frontoffice
 * Cette fonction est récursive
 * Elle ne retourne aucune données
 *
 * @param array $categories		Liste des catégories qui composent le menu
 * @param array $breadcrumbs	Fil d'ariane utilisé pour sélectionner la catégorie courante
 * @param array $moreElements	Permet de rajouter des menus qui ne sont pas dans la table des catégories
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/03/2012 by FI
 * @version 0.2 - 28/05/2013 by TB	ajout de l'id "mainMenu" pour la liste du menu
 * @deprecated since 05/06/2013 Cette fonction est maintenant dans le helper nav qui se trouve dans le dossier du template
 */	
	/*public function generateMenu($categories, $breadcrumbs, $moreElements = null) {
		
		if(count($categories) > 0) {		
			
			if(!empty($breadcrumbs)) { $iParentCategory = $breadcrumbs[0]['id']; } else { $iParentCategory = 0; }
			?><ul id="mainMenu"><?php			
			foreach($categories as $k => $v) {
				
				?>
				<li>
					<?php if($v['level'] == 1 && $iParentCategory == $v['id']) { $sCssMenu = ' class="menu_selected_bg"'; } else { $sCssMenu = ''; } ?>
					<a href="<?php echo Router::url('categories/view/id:'.$v['id'].'/slug:'.$v['slug']); ?>"<?php echo $sCssMenu; ?>><?php echo $v['name']; ?></a>
					<?php if(isset($v['children'])) { $this->generateMenu($v['children'], $breadcrumbs); }; ?>
				</li>
				<?php
			}

			if(isset($moreElements) && !empty($moreElements)) { 
				
				foreach($moreElements as $k => $v) {
					?>
					<li<?php if(isset($v['class'])) { ?> class="<?php echo $v['class']; ?>"<?php } ?>><a href="<?php echo $v['link']; ?>"><?php echo $v['name']; ?></a></li>
					<?php 
				}
			}
			?></ul><?php
		}		
	}*/
	
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