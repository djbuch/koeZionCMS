<?php
require_once(HELPERS.DS.'html_parent_helper.php');

$aclsHelper = PLUGINS.DS.'acls'.DS.'views'.DS.'helpers'.DS.'acls'.DS.'html.php';
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
	 * @return 	varchar Code HTML du bouton
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 */	
		public function backoffice_button_title($controller, $action, $title) {
			
			return '<a class="btn black" href="'.Router::url("backoffice/".$controller."/".$action).'" style="float: right; margin-top: 3px;"><span>'.$title.'</span></a>';
		}				
		
	/**
	 * Cette fonction est utilisée pour générer le lien statut du backoffice
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 11/03/2013 by FI
	 */	
		public function backoffice_statut_link($controller, $id, $online, $action = 'statut') {
			
			$onlineCss = ($online == 1) ? 'success' : 'error';
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'"><span class="label '.$onlineCss.' chgstatut">&nbsp;</span></a>';
		}		
		
	/**
	 * Cette fonction est utilisée pour générer le lien edit du backoffice
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 11/03/2013 by FI
	 */	
		public function backoffice_edit_link($controller, $id, $name, $action = 'edit') {
		
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'" class="edit_link">'.$name.'</a>';
		}			
		
	/**
	 * Cette fonction est utilisée pour générer une action avec un picto donné
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	varchar $action 		Action de l'url
	 * @param 	varchar $pictoOn 		Url du picto actif
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $pictoOff 		Url du picto inactif
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 17/03/2013 by FI
	 */	
		public function backoffice_picto($controller, $action, $pictoOn, $id = null, $pictoOff = null) {
			
			$url = 'backoffice/'.$controller.'/'.$action;
			if(isset($id)) { $url .= '/'.$id; }
			return '<a href="'.Router::url($url).'"><img src="'.BASE_URL.$pictoOn.'" /></a>';
		}			
		
	/**
	 * Cette fonction est utilisée pour générer les pictos edit du backoffice
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 */	
		public function backoffice_edit_picto($controller, $id, $action = 'edit') {
			
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'"><img src="'.BASE_URL.'/img/backoffice/thumb-edit.png" alt="edit" /></a>';
		}		
		
	/**
	 * Cette fonction est utilisée pour générer les pictos delete du backoffice
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 */	
		public function backoffice_delete_picto($controller, $id, $action = 'delete') {
			
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'" class="deleteBox" onclick="return confirm(\'Voulez vous vraiment supprimer?\');"><img src="'.BASE_URL.'/img/backoffice/thumb-delete.png" alt="delete" /></a>';
		}			
		
	/**
	 * Cette fonction est utilisée pour générer les boutons delete du backoffice
	 *
	 * @return 	varchar Code HTML du bouton
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 17/03/2013 by FI
	 */	
		public function backoffice_delete_button($controller) {
			
			return '<a class="btn red deleteFormBox" onclick="return confirm(\''._("Voulez vous vraiment supprimer?").'\');" href="formDelete" style="margin-top: 10px;"><span>'._("SUPPRIMER").'</span></a>
			<img src="'.BASE_URL.'/img/backoffice/arrow_top.png" />';
		}		
		
	/**
	 * Cette fonction est utilisée pour générer les pictos move2prev
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 */	
		public function backoffice_move2prev_picto($controller, $id, $action = "move2prev") {
			
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'"><img src="'.BASE_URL.'/img/backoffice/up.png" alt="up" /></a>';
		}			
		
	/**
	 * Cette fonction est utilisée pour générer les pictos move2next
	 *
	 * @param 	varchar $controller 	Controller de l'url
	 * @param 	integer $id 			Identifiant de l'élément
	 * @param 	varchar $action 		Action de l'url
	 * @return 	varchar Code HTML du picto
	 * @access 	public
	 * @author 	koéZionCMS
	 * @version 0.1 - 10/03/2013 by FI
	 */	
		public function backoffice_move2next_picto($controller, $id, $action = "move2next") {
			
			return '<a href="'.Router::url('backoffice/'.$controller.'/'.$action.'/'.$id).'"><img src="'.BASE_URL.'/img/backoffice/down.png" alt="up" /></a>';
		}
	}
}