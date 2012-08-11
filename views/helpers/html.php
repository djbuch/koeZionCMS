<?php
/**
 * @todo mutualiser la generation de lid pour ckeditor avec le helper form
 */
class Html {

//////////////////////////////////////////////////////////////////////////////////////////
//										VARIABLES										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Variable contenant les différents doctypes possibles
 *
 * @var 	array
 * @access 	public
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
	
//////////////////////////////////////////////////////////////////////////////////////////
//								FONCTIONS PUBLIQUES										//
//////////////////////////////////////////////////////////////////////////////////////////
	
/**
 * Enter description here...
 *
 * @param unknown_type $type
 * @return unknown
 */	
	function docType($type = 'xhtml-strict') {
		
		if (isset($this->docTypes[$type])) { return $this->docTypes[$type]; }
		return null;
	}
	
/**
 * Enter description here...
 *
 * @param unknown_type $css
 * @return unknown
 */	
	function css($css, $minified = false) {
		
		/*if(!$minified) {*/
			
			$html = '';
			foreach($css as $v) {
				
				$cssFile = '/css/'.$v.'.css';
				$cssPath = Router::webroot($cssFile);
				$html .= '<link href="'.$cssPath.'" rel="stylesheet" type="text/css" />'."\n";
				
			}
			
			return $html;
		/*} else {
			
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
 * Enter description here...
 *
 * @param unknown_type $js
 * @return unknown
 */	
	function js($js) {
		
		$html = '';
		foreach($js as $v) {
			
			$jsFile = 'js/'.$v.'.js';
			$jsPath = Router::webroot($jsFile);
			$html .= '<script src="'.$jsPath.'" type="text/javascript"></script>'."\n";			
		}
		
		return $html;
	}
	
/**
 * Enter description here...
 *
 * @param unknown_type $categories
 */	
	function generateMenu($categories, $breadcrumbs) {
		
		//pr($breadcrumbs);
		if(count($categories) > 0) {
		
			
			if(!empty($breadcrumbs)) { $iParentCategory = $breadcrumbs[0]['id']; } else { $iParentCategory = 0; }
			?><ul><?php			
			/*if($link_home_in_menu) { ?><li><a href="<?php echo Router::url('/'); ?>">Accueil</a></li><?php }*/			
			foreach($categories as $k => $v) {
				
				?>
				<li>
					<?php if($v['level'] == 1 && $iParentCategory == $v['id']) { $sCssMenu = 'class="menu_selected_bg"'; } else { $sCssMenu = ''; } ?>
					<a href="<?php echo Router::url('categories/view/id:'.$v['id'].'/slug:'.$v['slug']); ?>"<?php echo $sCssMenu; ?>><?php echo $v['name']; ?></a>
					<?php if(isset($v['children'])) { $this->generateMenu($v['children'], $breadcrumbs); }; ?>
				</li>
				<?php
			}			
			/*if($contactInMenu) { ?><li><a href="<?php echo Router::url('contacts/contact'); ?>">Contact</a></li><?php }*/
			?></ul><?php
		}		
	}
	
/**
 * 
 * @param unknown_type $image
 * @param unknown_type $options
 */	
	function img($image, $options = null) {
		
		//Gestion des éventuels attributs
		$attr = '';
		if(isset($options)) {

			foreach($options as $k => $v) { $attr .= ' '.$k.'="'.$v.'"'; }
		}
		
		$html = '<img src="'.BASE_URL.'/img/'.$image.'" '.$attr.' />';			
		return $html;
	}
	
/**
 * 
 * @param unknown_type $code
 */	
	function analytics($code) {
		
		if(!empty($code) && $_SERVER["HTTP_HOST"] != 'localhost') { return $code; }
	}	
//////////////////////////////////////////////////////////////////////////////////////////
//								FONCTIONS PRIVEES										//
//////////////////////////////////////////////////////////////////////////////////////////	
}