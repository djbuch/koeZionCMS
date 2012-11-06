<?php
/**
 * Classe statique permettant la gestion des variables de session
 *
 */
class Session {
	
/**
 * Cette fonction permet l'initialisation de la variable de session
 *
 * @version 0.1 - 20/04/2012
 * @version 0.2 - 31/07/2012 - Suppression de la récupération des données de la variable de session par un fichier
 */
	function init() {	
		
		$sessionName = Inflector::variable(Inflector::slug('koeZion '.$_SERVER['HTTP_HOST'])); 	//Récupération du nom de la variable de session																
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
 * @version 0.1 - 30/12/2011 
 * @see /Koezion/lib/set.php
 */
	function check($key) {
		
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
 * @version 0.1 - 30/12/2011
 * @see /Koezion/lib/set.php
 */	
	function write($key, $value) {
		
		$session = Set::insert($_SESSION, $key, $value); //On insère les données et on récupère la nouvelle variable de session
		$_SESSION = $session; //On affecte les données à la variable de session
		return (Set::classicExtract($_SESSION, $key) === $value); //On retourne le résultat de la fonction
	}
	
/**
 * Permet de lire une donnée dans la session
 *
 * @param varchar $key Clée de la donnée (Peut être composée de . pour les niveaux)
 * @return mixed Valeur de la donnée, faux si la donnée n'est pas dans la variable de session
 * @version 0.1 - 30/12/2011
 * @see /Koezion/lib/set.php
 */		
	function read($key = null) {
		
		$result = Set::classicExtract($_SESSION, $key);
		if(!is_null($result)) { return $result; }
		else { return false; }
	}
	
/**
 * Permet de supprimer une donnée dans la session
 *
 * @param varchar $key Clée de la donnée (Peut être composée de . pour les niveaux)
 * @return boolean Vrai si la valeur est supprimée, faux sinon
 * @version 0.1 - 30/12/2011
 * @see /Koezion/lib/set.php
 */			
	function delete($key) {
		
		$_SESSION = Set::remove($_SESSION, $key);
		return (Session::check($key) == false);
	}
	
/**
 * Permet de supprimer la variable de session lors de la deconnexion
 *
 * @version 0.1 - 20/04/2012
 * @see http://www.php.net/manual/fr/function.session-destroy.php
 */	
	function destroy() {
		
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
 * @version 0.1 - 30/12/2011
 */	
	function setFlash($message, $type = 'succes') {
		
		//Initialisation de la variable de session avec les valeurs reçues
		Session::write('Flash.message', $message);
		Session::write('Flash.type', $type);		
	}
	
/**
 * EN ATTENTE DE COMM
 *
 * @return unknown
 */	
	
	function isLogged() {
		
		$role = Session::read('Backoffice.User.role');
		return isset($role);
	}
	
	
	
	function user($key) {
		
		//Si session user
		if(Session::read('Backoffice.User')) {			
			
			$userType = Session::read('Backoffice.User.'.$key); //Récupération de la valeur
			
			//Test de la valeur
			if(isset($userType)) { return $userType; } 
			else { return false; } 
		}
		return false;	
	}
}