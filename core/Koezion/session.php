<?php
/**
 * Classe statique permettant la gestion des variables de session
 *
 */
class Session {
	
/**
 * Cette fonction permet l'initialisation de la variable de session
 *
 * @access	static
 * @author	koéZionCMS
 * @version 0.1 - 20/04/2012
 * @version 0.2 - 31/07/2012 - Suppression de la récupération des données de la variable de session par un fichier
 * @version 0.3 - 09/11/2012 - Rajout du test pour savoir si les classes Inflector et Set sont chargées
 */
	static function init() {
		
		if(!class_exists('Inflector')) { require_once(CAKEPHP.DS.'inflector.php'); }
		if(!class_exists('Set')) { require_once(CAKEPHP.DS.'set.php'); }
		
		$sessionName = Inflector::variable(Inflector::slug('koeZion '.$_SERVER['HTTP_HOST'])); 	//Récupération du nom de la variable de session		
		
		//Récupération des configuration du coeur de l'application pour détermine le mode de stockage des variables de sessions
		//Soit on utilise le comportement natif de PHP
		//Soit on stocke les sessions en local dans le dossier TMP
		require_once(LIBS.DS.'config_magik.php');
		$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'core.ini', true, false);
		$coreConfs = $cfg->keys_values();		
		if(isset($coreConfs['local_storage_session']) && $coreConfs['local_storage_session']) { ini_set('session.save_path', TMP.DS.'sessions'); }
		
		ini_set('session.use_trans_sid', 0);													//Evite de passe l'id de la session dans l'url
		session_name($sessionName); 															//On affecte le nom
		session_start(); 																		//On démarre la session
	}	
	
/**
 * ACCESSEURS EN LECTURE, ECRITURE, SUPPRESSION ET CONTROLE DE LA VARIABLE DE SESSION 
 */	
	
/**
 * Retourne vrai si la variable passée en paramètre figure dans la variable de session
 *
 * @param varchar $key Variable à tester
 * @return boolean Vrai si la variable est dans la variable de session, faux sinon
 * @access	static
 * @author	koéZionCMS
 * @version 0.1 - 30/12/2011 
 * @see /Koezion/lib/set.php
 */
	static function check($key) {
		
		if(empty($key)) { return false; } //Si la clée est vide
		$result = Set::classicExtract($_SESSION, $key); //On procède à l'extraction de la donnée
		return !empty($result); //On retourne le résultat
	}
	
/**
 * Permet d'écrire une donnée dans la session
 *
 * @param varchar $key Clée de la donnée
 * @param mixed $value Donnée à écrire
 * @return boolean Vrai si la valeur est insérée, faux sinon
 * @access	static
 * @author	koéZionCMS
 * @version 0.1 - 30/12/2011
 * @see /Koezion/lib/set.php
 */	
	static function write($key, $value) {
		
		$session = Set::insert($_SESSION, $key, $value); //On insère les données et on récupère la nouvelle variable de session
		$_SESSION = $session; //On affecte les données à la variable de session
		return (Set::classicExtract($_SESSION, $key) === $value); //On retourne le résultat de la fonction
	}
	
/**
 * Permet de lire une donnée dans la session
 *
 * @param varchar $key Clée de la donnée (Peut être composée de . pour les niveaux)
 * @return mixed Valeur de la donnée, faux si la donnée n'est pas dans la variable de session
 * @access	static
 * @author	koéZionCMS
 * @version 0.1 - 30/12/2011
 * @see /Koezion/lib/set.php
 */		
	static function read($key = null) {
		
		$result = Set::classicExtract($_SESSION, $key);
		if(!is_null($result)) { return $result; }
		else { return false; }
	}
	
/**
 * Permet de supprimer une donnée dans la session
 *
 * @param varchar $key Clée de la donnée (Peut être composée de . pour les niveaux)
 * @return boolean Vrai si la valeur est supprimée, faux sinon
 * @access	static
 * @author	koéZionCMS
 * @version 0.1 - 30/12/2011
 * @see /Koezion/lib/set.php
 */			
	static function delete($key) {
		
		$_SESSION = Set::remove($_SESSION, $key);
		return (Session::check($key) == false);
	}
	
/**
 * Permet de supprimer la variable de session lors de la deconnexion
 *
 * @access	static
 * @author	koéZionCMS
 * @version 0.1 - 20/04/2012
 * @see http://www.php.net/manual/fr/function.session-destroy.php
 */	
	static function destroy() {
		
		session_unset(); // Détruit toutes les données dans la variable de session
		
		// Si vous voulez détruire complètement la session, effacez également le cookie de session.
		// Note : cela détruira la session et pas seulement les données de session
		if (ini_get("session.use_cookies")) {
			
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		
		session_destroy(); // Finalement, on détruit la session		
	}
	
/**
 * GESTION DES MESSAGES FLASH
 */	
	
/**
 * Permet d'initialiser un message dans la variable de session
 *
 * @param varchar $message Message à afficher
 * @param varchar $type Type du message
 * @access	static
 * @author	koéZionCMS
 * @version 0.1 - 30/12/2011
 */	
	static function setFlash($message, $type = 'succes') {
		
		//Initialisation de la variable de session avec les valeurs reçues
		Session::write('Flash.message', $message);
		Session::write('Flash.type', $type);		
	}
	
/**
 * EN ATTENTE DE COMM
 *
 * @return unknown
 */	
	
	static function isLogged() {
		
		$session = Session::read('Backoffice');
		return (isset($session) && !empty($session));
	}
	
/**
 * Cette fonction permet de récupérer la valeur du role de l'utilisateur connecté
 * 
 * @access	static
 * @author	koéZionCMS
 * @version 0.1 - 02/03/2013 by FI 
 * @version 0.1 - 10/10/2014 by FI - Modification de la données retournée on passe de role_id à id 
 */
	static function getRole() {

		//Si session
		if(Session::read('Backoffice.UsersGroup')) {
			
			$role = Session::read('Backoffice.UsersGroup.id'); //Récupération de la valeur
			if($role) { return $role; } //Si la valeur est valide on la retourne
			else { return false; } //On retourne faux sinon
		}
		return false;
	}
	
/**
 * Cette fonction permet de récupérer la valeur de l'identifiant de la variable de session
 * 
 * @access	static
 * @author	koéZionCMS
 * @version 0.1 - 13/06/2014 by FI 
 */
	static function get_id() {

		return session_id();
	}
	
	/*static function user($key) {
		
		//Si session user
		if(Session::read('Backoffice.User')) {			
			
			$userType = Session::read('Backoffice.User.'.$key); //Récupération de la valeur
			
			//Test de la valeur
			if(isset($userType)) { return $userType; } 
			else { return false; } 
		}
		return false;	
	}*/
}