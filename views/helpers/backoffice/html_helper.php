<?php
require_once(HELPERS.DS.'html_parent_helper.php');

$aclsHelper = PLUGINS.DS.'acls'.DS.'views'.DS.'helpers'.DS.'acls'.DS.'html_helper.php';
if(file_exists($aclsHelper)) { require_once($aclsHelper); } 
else { 

	/**
	 * @todo mutualiser la generation de lid pour ckeditor avec le helper form
	 */
	class HtmlHelper extends HtmlParentHelper {
		
	/**
	 * Cette fonction est utilisée pour générer les boutons titres du backoffice
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	varchar $action 		Action de l'url
	 * @param 	varchar $title 			Titre du bouton
	 * @param 	varchar $params 		Paramètres supplémentaires à passer dans l'url
	 * @param 	varchar $extension 		Paramètres supplémentaires pour modifier l'extension de l'url
	 * @param 	varchar $css 			Paramètres supplémentaires pour rajouter une ou plusieurs classes CSS supplémentaires
	 * @param 	varchar $moreAttributes	Paramètres supplémentaires (libre)
	 * @return 	varchar Code HTML du bouton
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 * @version 0.2 - 19/09/2014 by FI - Rajout de la variable $params
	 * @version 0.3 - 19/09/2014 by FI - Rajout de la variable $extension
	 * @version 0.4 - 26/09/2014 by FI - Rajout de la variable $css
	 * @version 0.5 - 26/09/2014 by FI - Rajout de la variable $moreAttributes
	 */	
		public function backoffice_button_title($controller, $action, $title, $params = null, $extension = 'html', $css = '', $moreAttributes = '') {
			
			$url = "backoffice/".$controller."/".$action;
			if(isset($params)) { $url .= $params; }
			return '<a class="btn black '.$css.'" href="'.Router::url($url, $extension).'" style="float: right; margin-top: 3px;" '.$moreAttributes.'><span>'.$title.'</span></a>';
		}				
		
	/**
	 * Cette fonction est utilisée pour générer le lien statut du backoffice
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @param 	varchar $css 			Paramètres supplémentaires pour rajouter une ou plusieurs classes CSS supplémentaires
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 11/03/2013 by FI
	 * @version 0.2 - 12/10/2014 by FI - Rajout de la variable $css
	 */	
		public function backoffice_statut_link($controller, $id, $online, $action = 'statut', $css = '') {
			
			$onlineCss = ($online == 1) ? 'success' : 'error';
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'" class="'.$css.'"><span class="label '.$onlineCss.' chgstatut">&nbsp;</span></a>';
		}		
		
	/**
	 * Cette fonction est utilisée pour générer le lien edit du backoffice
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @param 	varchar $css 			Paramètres supplémentaires pour rajouter une ou plusieurs classes CSS supplémentaires
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 11/03/2013 by FI
	 * @version 0.2 - 12/10/2014 by FI - Rajout de la variable $css
	 */	
		public function backoffice_edit_link($controller, $id, $name, $action = 'edit', $css = '') {
		
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'" class="edit_link '.$css.'">'.$name.'</a>';
		}			
		
	/**
	 * Cette fonction est utilisée pour générer une action avec un picto donné
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	varchar $action 		Action de l'url
	 * @param 	varchar $pictoOn 		Url du picto actif
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $pictoOff 		Url du picto inactif
	 * @param 	varchar $extension 		Paramètres supplémentaires + Paramètres supplémentaires pour modifier l'extension de l'url
	 * @param 	varchar $css 			Paramètres supplémentaires pour rajouter une ou plusieurs classes CSS supplémentaires
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 17/03/2013 by FI
	 * @version 0.2 - 22/09/2014 by FI - Rajout de la variable $extension
	 * @version 0.3 - 12/10/2014 by FI - Rajout de la variable $css
	 */	
		public function backoffice_picto($controller, $action, $pictoOn, $id = null, $pictoOff = null, $params = null, $extension = 'html', $css = '') {
			
			$url = 'backoffice/'.$controller.'/'.$action;
			if(isset($id)) { $url .= '/'.$id; }
			if(isset($params)) { $url .= $params; }
			return '<a href="'.Router::url($url, $extension).'" class="'.$css.'"><img src="'.BASE_URL.$pictoOn.'" /></a>';
		}			
		
	/**
	 * Cette fonction est utilisée pour générer les pictos edit du backoffice
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @param 	varchar $css 			Paramètres supplémentaires pour rajouter une ou plusieurs classes CSS supplémentaires
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 * @version 0.2 - 12/10/2014 by FI - Rajout de la variable $css
	 */	
		public function backoffice_edit_picto($controller, $id, $action = 'edit', $css = '') {
			
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'" class="'.$css.'"><img src="'.BASE_URL.'/img/backoffice/thumb-edit.png" alt="edit" /></a>';
		}		
		
	/**
	 * Cette fonction est utilisée pour générer les pictos delete du backoffice
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @param 	varchar $css 			Paramètres supplémentaires pour rajouter une ou plusieurs classes CSS supplémentaires
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 * @version 0.2 - 12/10/2014 by FI - Rajout de la variable $css
	 */	
		public function backoffice_delete_picto($controller, $id, $action = 'delete', $css = '') {
			
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'" class="deleteBox '.$css.'" onclick="return confirm(\'Voulez vous vraiment supprimer?\');"><img src="'.BASE_URL.'/img/backoffice/thumb-delete.png" alt="delete" /></a>';
		}			
		
	/**
	 * Cette fonction est utilisée pour générer les boutons delete du backoffice
	 *
	 * @param 	varchar $css 			Paramètres supplémentaires pour rajouter une ou plusieurs classes CSS supplémentaires
	 * @return 	varchar Code HTML du bouton
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 17/03/2013 by FI
	 * @version 0.2 - 12/10/2014 by FI - Rajout de la variable $css
	 */	
		public function backoffice_delete_button($controller, $css = '') {
			
			return '<a class="btn red deleteFormBox '.$css.'" onclick="return confirm(\''._("Voulez vous vraiment supprimer?").'\');" href="formDelete" style="margin-top: 10px;"><span>'._("SUPPRIMER").'</span></a>
			<img src="'.BASE_URL.'/img/backoffice/arrow_top.png" />';
		}		
		
	/**
	 * Cette fonction est utilisée pour générer les pictos move2prev
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @param 	varchar $css 			Paramètres supplémentaires pour rajouter une ou plusieurs classes CSS supplémentaires
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 * @version 0.2 - 12/10/2014 by FI - Rajout de la variable $css
	 */	
		public function backoffice_move2prev_picto($controller, $id, $action = "move2prev", $css = '') {
			
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'" class="'.$css.'"><img src="'.BASE_URL.'/img/backoffice/up.png" alt="up" /></a>';
		}			
		
	/**
	 * Cette fonction est utilisée pour générer les pictos move2next
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @param 	varchar $css 			Paramètres supplémentaires pour rajouter une ou plusieurs classes CSS supplémentaires
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 * @version 0.2 - 12/10/2014 by FI - Rajout de la variable $css
	 */	
		public function backoffice_move2next_picto($controller, $id, $action = "move2next", $css = '') {
			
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'" class="'.$css.'"><img src="'.BASE_URL.'/img/backoffice/down.png" alt="up" /></a>';
		}
	}
}