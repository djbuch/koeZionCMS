<?php
/**
 * Cette classe permet la gestion de la base de données
 */
class Model extends Object {   
    
	static $connections = array();
    
	public $conf = 'localhost'; //Paramètres de connexion par défaut
	public $table = false; //Nom de la table
	public $db; //Variable contenant la connexion à la base de données
	public $primaryKey = 'id';  //Valeur par défaut de la clé primaire
	public $id; //Variable qui va contenir la valeur de la clé primaire après isert ou update
	public $errors = array(); //Par défaut pas d'erreurs
	public $trace_sql = false; //Permet d'afficher la requête exécutée cf fonction find
    
/**
 * Constructeur de la classe
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 28/12/2011 by FI
 * @version 0.2 - 02/03/2012 by FI - Modification de la récupération de la configuration de la base  de données, on passe maintenant par un fichier .ini
 * @version 0.3 - 11/04/2012 by FI - Modification de la récupération du .ini seulement deux configurations possibles localhost et online
 */
	public function __construct() {

		//ANCIENNE VERSION DE RECUPERATION DE LA CONFIGURATION DE LA BASE DE DONNEES
		//if($_SERVER["HTTP_HOST"] != 'localhost') { $this->conf = 'online'; } //On va tester si on est en local ou pas		        		
		//$conf = Database::$databases[$this->conf]; //Récupération de la configuration de la base de données

		$httpHost = $_SERVER["HTTP_HOST"];
		if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $section = 'localhost'; } else { $section = 'online'; }
		
		require_once(LIBS.DS.'config_magik.php'); //Import de la librairie de gestion des fichiers de configuration 
		$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'database.ini', true, true); //Création d'une instance
		$conf = $cfg->keys_values($section); //Récupération des configurations en fonction du nom de domaine (Ancienne version : $conf = $cfg->keys_values($_SERVER["HTTP_HOST"], 1);)
		
		//Si le nom de la table n'est pas défini on va l'initialiser automatiquement
		//Par convention le nom de la table sera le nom de la classe en minuscule avec un s à la fin
		if($this->table === false) {
		
			$prefix = $conf['prefix']; //On va récupérer la valeur du préfix se trouvant dans le tableau de configuration de la base de données
			$tableName = Inflector::tableize(get_class($this)); //Mise en variable du nom de la table
			$this->table = $prefix.$tableName; //Affectation de la variable de classe
		}		
		
		//On test qu'une connexion ne soit pas déjà active
		if(isset(Model::$connections[$this->conf])) {
			   
			$this->db = Model::$connections[$this->conf];
			return true;             
		}
        
		//On va tenter de se connecter à la base de données
		try {
            
			//Création d'un objet PDO
			$pdo = new PDO(
				'mysql:host='.$conf['host'].';dbname='.$conf['database'], 
				$conf['login'], 
				$conf['password'], 
				array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
			); 
            
			//Mise en place des erreurs de la classe PDO
			//Utilisation du mode exception pour récupérer les erreurs
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
            
			Model::$connections[$this->conf] = $pdo; //On affecte l'objet à la classe
			$this->db = $pdo;
			$this->database = $conf['database'];
		} catch(PDOException $e) { //Erreur
    
			//Test du mode debug
			if(Configure::read('debug') >= 1) {
				die("La base de données n'est pas disponible merci de rééssayer plus tard ".$e->getMessage());
			} else {
				die("La base de données n'est pas disponible merci de rééssayer plus tard");
			}
		}
	}
    
/**
 * Cette fonction permet l'exécution du requête sans passer par la fonction find
 * Il suffit d'envoyer directement dans le paramètre $sql la requête à effectuer (par exemple un SELECT ou autre)
 *
 * @param 	varchar $sql Requête à effectuer
 * @param 	boolean $return Indique si ou ou non on doit retourner le résultat de la requête
 * @return 	array 	Tableau contenant les éléments récupérés lors de la requête
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 28/12/2011 by FI
 */	
	public function query($sql, $return = false) {
				
		$pre = $this->db->prepare($sql); //On prépare la requête
		$result = $pre->execute(); //On l'exécute
		if($result) { //Si l'exécution s'est correctement déroulée
			
			if($return) { return $pre->fetchAll(PDO::FETCH_ASSOC); } //On retourne le résultat si demandé
			else { return true; } //On retourne vrai sinon
		} else { return false; } //Si la requête ne s'est pas bien déroulée on retourne faux
	}	
	
/**
 * Fonction permettant d'effectuer des recherches dans la base de données
 * 
 * $req peut être composé des index suivants :
 * 	- fields (optionnel) : liste des champs à récupérer. Cet index peut être une chaine de caractères ou un tableau, si il est laissé vide la requête récupèrera l'ensemble des colonnes de la table.
 * 	- conditions (optionnel) : ensemble des conditions de recherche à mettre en place. Cet index peut être une chaine de caractères ou un tableau.
 * 	- moreConditions (optionnel) : cet index est une chaine de caractères et permet lorsqu'il est renseigné de rajouter des conditions de recherche particulières.
 * 	- order (optionnel) : cet index est une chaine de caractères et permet lorsqu'il est renseigné d'effectuer un tri sur les éléments retournés.
 * 	- limit (optionnel) : cet index est un entier et permet lorsqu'il est renseigné de limiter le nombre de résultats retournés.
 *  - allLocales (optionnel) : cet index est un booléen qui permet lors de la récupération d'un élément d'indiquer si il faut ou non récupérer l'ensemble des champs traduits
 * 
 * @param 	array 	$req 	Tableau de conditions et paramétrages de la requete
 * @param 	object 	$type 	Indique le type de retour de PDO dans notre cas un tableau dont les index sont les colonnes de la table
 * @return 	array 	Tableau contenant les éléments récupérés lors de la requête  
 * @version 0.1 - 28/12/2011 by FI
 * @version 0.2 - 02/05/2012 by FI - Mise en place de la conditions de récupérations selon l'identifiant du site
 */    
	public function find($req = array(), $type = PDO::FETCH_ASSOC) {
		
		$shema = $this->shema();
		$sql = 'SELECT '; //Requete sql
        
		///////////////////////
		//   CHAMPS FIELDS   //		
		if(!isset($req['fields'])) {
			
			//Si aucun champ n'est demandé on va récupérer le shéma de la table et récupérer ces champs
			//Dans le cas de table traduite on va également récupérer les champs traduits ainsi que la langue associée
			$req['fields'] = $shema;
		}	
		
		if(is_array($req['fields'])) { $sql .= implode(', ', $req['fields']); } //Si il s'agit d'un tableau		
		else { $sql .= $req['fields']; } //Si il s'agit d'une chaine de caractères 
		
		$sql .= ' FROM '.$this->table.' AS '.get_class($this).' '; //Mise en place du from

		///////////////////////////////////////////////////////////
		//   CONDITIONS DE RECHERCHE SUR L'IDENTIFIANT DU SITE   //
		//Si dans le shema de la table on a une colonne website_id		
		if(in_array('website_id', $shema) && get_class($this) != 'UsersGroupsWebsite') {
		
			//Si on a pas de conditions de recherche particulières
			if(!isset($req['conditions'])) { $req['conditions']['website_id'] = CURRENT_WEBSITE_ID; }
			else {
				
				//Sinon on va tester si il s'agit d'un tableau ou d'une chaine de caractères
				if(is_array($req['conditions'])) { $req['conditions']['website_id'] = CURRENT_WEBSITE_ID; } 
				else { $req['conditions'] .= " AND website_id=".CURRENT_WEBSITE_ID; }
			}			
		}
		///////////////////////////////////////////////////////////
		
		///////////////////////////
		//   CHAMPS CONDITIONS   //
		if(isset($req['conditions'])) { //Si on a des conditions
			
			$conditions = 'WHERE ';	//Mise en variable des conditions	
			
			//On teste si conditions est un tableau
			//Sinon on est dans le cas d'une requête personnalisée
			if(!is_array($req['conditions'])) {
                
				$conditions .= $req['conditions']; //On les ajoute à la requete
			
			//Si c'est un tableau on va rajouter les valeurs
			} else {
				
				$cond = array();
				foreach($req['conditions'] as $k => $v) {
					
					if(!is_numeric($v)) {
						
						$v = $this->db->quote($v); //Equivalement de mysql_real_escape_string
						//$v = '"'.mysql_escape_string($v).'"'; //Equivalement de mysql_real_escape_string
					}
					
					$k = get_class($this).".".$k; //On rajoute le nom de la classe devant le nom de la colonne
					$cond[] = "$k=$v";
				}
				$conditions .= implode(' AND ', $cond);
			}
			
			$sql .= $conditions; //On rajoute les conditions à la requête
		}
		
		////////////////////////////////
		//   CHAMPS MORE CONDITIONS   //
		if(isset($req['moreConditions']) && !empty($req['moreConditions'])) { 
			
			if(isset($req['conditions'])) { $sql .= ' AND '; } else { $sql .= ' WHERE '; }			
			$sql .= $req['moreConditions']; 
		}
		
		//////////////////////
		//   CHAMPS ORDER   //
		if(isset($req['order'])) { $sql .= ' ORDER BY '.$req['order']; }
		
		//////////////////////
		//   CHAMPS LIMIT   //
		if(isset($req['limit'])) { $sql .= ' LIMIT '.$req['limit']; }
		
		if($this->trace_sql) { pr($sql); }		
		
		$pre = $this->db->prepare($sql);
		$pre->execute();
        return $pre->fetchAll($type);        
    }
    
/**
 * Cette fonction permet de retourner le premier élément correspondant aux conditions de recherche
 * 
 * @param 	array $req Tableau de conditions et de paramétrages de la requête
 * @return 	array Tableau contenant les données de l'élément 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/02/2012 by FI
 */    
	public function findFirst($req) { 
		
		$request = $this->find($req); //On lance la requête
		return current($request); //Par défaut on va retourne le premier élément du tableau 
	}
	
	
/**
 * Cette fonction permet de compter le nombre de résultat d'une requeête
 *
 * @param 	array 	$condition		Tableau des conditions de recherche
 * @param 	varchar $moreConditions Conditions supplémentaires éventuelles
 * @return  array 	Résultat de la requête
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 16/02/2012 by FI
 */	
	public function findCount($conditions = null, $moreConditions = null) {
		
		$res = $this->findFirst(
			array(
				'fields' => 'COUNT('.get_class($this).'.'.$this->primaryKey.') AS count',
				'conditions' => $conditions,
				'moreConditions' => $moreConditions
			)
		);
		return $res['count'];
	}
	
/**
 * Cette fonction retourne les éléments d'une requête sous forme de tableau
 * Le retour sera de la forme array(key => value, ...)
 * Utilisée pour initialiser les listes déroulantes par exemple
 *
 * @param 	array $conditions Tableau contenant les conditions de la requête
 * @return  array Tableau formaté
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 16/02/2012 by FI
 */	
	public function findList($conditions = null) {
	
		$queryResult = $this->find($conditions);		
		
		//On formate les résultats
		$result = array();
		foreach($queryResult as $k => $v) { $result[$v['id']] = $v['name']; }
		return $result;
	}
	
/**
 * Cette fonction est chargée de supprimer un élément de la table
 * 
 * @param 	mixed $id Identifiant(s) à supprimer (Ce paramètre peut être un tableau ou un entier)
 * @return  objet Objet PDO
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 16/02/2012 by FI
 * @version 0.2 - 13/04/2012 by FI - Modification de la requête de suppression pour pouvoir passer en paramètre un tableau ou un entier
 */		
	public function delete($id) {
		
		if(is_array($id)) { $idConditions = " IN (".implode(',', $id).')'; } else { $idConditions = " = ".$id; }		
		$sql = "DELETE FROM ".$this->table." WHERE ".$this->primaryKey.$idConditions.";";  //Requête de suppression de l'élément		
			
		$queryResult = $this->db->query($sql);
		if(isset($this->fields_to_index)) { $this->delete_search_index($id); } //Suppression de l'index dans la recherche
		
		return $queryResult;
	}
	
/**
 * Fonction chargée de supprimer un élément de la base de données en fonction du colonne particulière (différente de la clée primaire de la table)
 * 
 * @param 	varchar $name 	Nouvelle clée à utiliser pour la suppression
 * @param 	mixed	$value 	Valeur de comparaison de la clée
 * @return  objet	Objet PDO
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 16/02/2012 by FI
 */	
	public function deleteByName($name, $value) {
	
		$oldPrimaryKey = $this->primaryKey; 
		$this->primaryKey = $name;		
		if(!is_numeric($value)) { $value = $this->db->quote($value); } //Equivalement de mysql_real_escape_string
		$sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = {$value}";
		$this->primaryKey = $oldPrimaryKey;		
		return $this->db->query($sql);
	}	
	
/**
 * Fonction chargée d'éffectuer la sauvegarde des données
 * Selon les cas un INSERT ou un UPDATE sera effectué
 * On va travailler avec les requetes préparées de PDO
 *
 * @param 	array 	$data 			Données à sauvegarder
 * @param 	boolean $forceInsert 	Booléan permettant même si le champ id est présent dans le tableau de forcer l'insert
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 16/02/2012 by FI
 * @version 0.2 - 16/02/2012 by FI - Mise en place de la gestion automatique de created et modified si présents dans la table (format --> date('Y-m-d H:i:s');) 
 * @version 0.3 - 06/03/2012 by FI - Mise en place de la gestion automatique des champs created_by et modified_by permettant de stocker l'identifiant de l'utilisateur ayant créé l'enregistrement
 * @version 0.4 - 18/05/2012 by FI - Mise en place d'un booléen permettant de forcer l'insert même si le champ id est présent (utilisé pour la modification des catégories)
 * @version 0.5 - 14/06/2012 by FI - Modification du test sur created, created_by, etc... car le in_array ne devait pas se faire sur $datas directement mais sur array_keys($datas)
 * @todo mettre en place des try catch pour vérifier que la requete c'est bien exécutée 
 */	
	public function save($datas, $forceInsert = false) {
					
		$shema = $this->shema();
		$datasShema = array_keys($datas);
		
		$key = $this->primaryKey; //Récupération de la clé primaire
		
		$fieldsToSaveToSave = array(); //Tableau des champs à sauvegarder
		$datasToSave = array(); //Tableau utilisé lors de la préparation de la requête
		
		//Permet de connaitre le type de requete a effectuer pour deux choses
		// --> Savoir qu'elle requete lancer UPDATE OU INSERT
		// --> Savoir comment renvoyer l'id
		//Dans ce cas on est sur de l'update				
		if(isset($datas[$key]) && !empty($datas[$key]) && !$forceInsert) { 
			
			$action = 'update'; //Définition de l'action
			$returnid = $datas[$key]; //Récupération de la valeur de la clée
			$datasToSave[":$key"] = $returnid; //On insère dans les données préparées la valeur de la clée lors de l'update
			
		//Dans ce cas on est sur de l'insert			
		} else { 
			
			$action = 'insert'; //Définition de l'action
			if(!in_array('created', $datasShema) && in_array('created', $shema)) { $datas['created'] = date('Y-m-d H:i:s'); } //On procède à la mise à jour du champ created si il existe			
			if(!in_array('created_by', $datasShema) && in_array('created_by', $shema)) { $datas['created_by'] = Session::read('Backoffice.User.id'); } //On procède à la mise à jour du champ created_by si il existe			
		}
		
		if(!in_array('modified', $datasShema) && in_array('modified', $shema)) { $datas['modified'] = date('Y-m-d H:i:s'); } //On procède à la mise à jour du champ modified si il existe
		if(!in_array('modified_by', $datasShema) && in_array('modified_by', $shema)) { $datas['modified_by'] = Session::read('Backoffice.User.id'); } //On procède à la mise à jour du champ modified_by si il existe
		if(in_array('password', $datasShema)) { $datas['password'] = sha1($datas['password']); } //On procède à la mise à jour du champ password si il existe				
		if(!in_array('website_id', $datasShema) && in_array('website_id', $shema) && get_class($this) != 'UsersGroupsWebsite') { $datas['website_id'] = CURRENT_WEBSITE_ID; } //On procède à la mise à jour du champ password si il existe

		if(in_array('slug', $shema) && (!in_array('slug', $datasShema) || empty($datas['slug']))) { $datas['slug'] = strtolower(Inflector::slug($datas['name'], '-')); } //On procède à la mise à jour du champ slug si celui ci n'est pas rempli ou non présent dans le formulaire mais présent dans la table
		if(in_array('page_title', $shema) && (!in_array('page_title', $datasShema) || empty($datas['page_title']))) { $datas['page_title'] = $datas['name']; } //On procède à la mise à jour du champ page_title si celui ci n'est pas rempli ou non présent dans le formulaire mais présent dans la table
				
		if(isset($datas[$key]) && !$forceInsert) unset($datas[$key]); //Il faut supprimer du tableau des données la clé primaire si celle ci est définie
				
		//On fait le parcours des données
		foreach($datas as $k => $v) {

			if(isset($this->files_to_upload) && isset($this->files_to_upload[$k])) continue; //On supprime si il y en a les champs d'upload
			
			//On récupère le shéma de la table pour être sur de n'ajouter à la requête que des champs présent dans la table pour éviter les erreurs
			if(in_array($k, $shema)) {
						
				$fieldsToSave[] = "$k=:$k";
				$datasToSave[":$k"] = $v;
			}
		}
		
		//On va tester l'existence de cette clé dans le tableau des datas
		if($action == 'update') { $sql = 'UPDATE '.$this->table.' SET '.implode(',', $fieldsToSave).' WHERE '.$key.'=:'.$key.';'; } 
		else { $sql = 'INSERT INTO '.$this->table.' SET '.implode(',', $fieldsToSave).';'; }
		
		//Il faut maintenant envoyer à PDO un tableau de valeurs du type array(:INDEX => VALEUR)
		//Il nous manque donc les : par rapport au tableau $datas
				
		$pre = $this->db->prepare($sql);
		$pre->execute($datasToSave);
		
		//Affectation de la valeur de la clé primaire à la variable de classe
		if($action == 'insert') { $this->id = $this->db->lastInsertId(); }
		else { $this->id = $returnid; }
		
		if(isset($this->files_to_upload)) { $this->upload_files($datas, $this->id); } //Sauvegarde éventuelle des images
		if(isset($this->fields_to_index)) { $this->make_search_index($datas, $this->id); } //On génère le fichier d'index de recherche
	}
	
	function saveAll($datas) {
		
		foreach($datas as $k => $v) { $this->save($v); }
	}
	
/**
 * Cette fonction est en charge de la validation des données avant modification de la base de données
 *
 * @param 	array $datas Données à sauvegarder
 * @return 	boolean Retourne vrai si la validation est correcte, faux sinon
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 28/12/2011 
 * @version 0.2 - 06/02/2012 - Ne validera que les champs envoyés dans le formulaire 
 * @version 0.3 - 03/04/2012 - Modification de la gestion des messages d'erreurs suite à l'ajout de gettext
 * @version 0.4 - 20/04/2012 - Mise en place des validations par callback 
 */	
	function validates($datas) {
				
		if(isset($this->validate)) { //Si on a un tableau de validation dans la classe
			
			$errors = array(); //Tableau d'erreurs qui sera retourné
			include_once(CONFIGS.DS.'messages.php'); //Inclusion des éventuels messages d'erreurs
						
			foreach($this->validate as $k => $v) { //On va parcourir tous les champs à valider
				
				//Par défaut si le champ est présent dans les données à valider 
				//mais pas dans les données envoyées par le formulaire on génère une erreur
				if(isset($datas[$k])) { 
					
					$this->datas = $datas; //On va rajouter les données à contrôler dans le model dans le cas ou nous en ayons besoin lors de l'utilisation des callback
					$validation = new Validation($this);					
					
					$isValid = false; //Par défaut on renverra toujours faux
					
					//On va tester si il y à plusieurs règles de validation
					//Si on a pas directement accès à la clée rule cela signifie qu'il y à plusieurs règles
					if(!isset($v['rule'])) {
						
						//On va donc les parcourir
						foreach($v as $kRule => $vRule) { 
													
							$isValid = $validation->check($datas[$k], $vRule['rule']);
							if(!$isValid) { $errors[$k] = Set::classicExtract($Errorsmessages, $vRule['message']); } //On injecte le message						
						}	
					} else { 
						
						$isValid = $validation->check($datas[$k], $v['rule']); 
						if(!$isValid) { $errors[$k] = Set::classicExtract($Errorsmessages, $v['message']); } //On injecte le message
					}
				}
			}
			
			$this->errors = $errors;			
			if(empty($errors)) { return true; } else { return false; }
			
		} else { return true; }
	}
	
/**
 * Cette fonction est en charge de l'upload des fichiers sur le serveur et de mettre à jour les champs de la base de données de la ligne concernée
 *
 * @param 	array 	$datas Données à sauvegarder
 * @param 	integer $id	   Identifiant de l'élément
 * @return 	boolean Retourne vrai si la validation est correcte, faux sinon
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 28/12/2011
 */	
	function upload_files($datas, $id) {
		
		require_once(BEHAVIORS.DS.'upload.php');		
		foreach($this->files_to_upload as $k => $v) {
			
			if(isset($datas[$k])) {
				
				$handle = new Upload($datas[$k]);
				if($handle->uploaded) {
					
					$filePath = WEBROOT.DS."upload".DS.get_class($this);
					$handle->Process($filePath);
					$fileName = $handle->file_dst_name;

					//Sauvegarde en base de données
					if(isset($v['bdd']) && $v['bdd']) {
						
						$primaryKey = $this->primaryKey;
						$fileNameField = $k.'_name';
						$filePathField = $k.'_path';
						
						$update = array();
						$update[$primaryKey] = $id;
						$update[$fileNameField] = $fileName;
						$update[$filePathField] = BASE_URL."/upload/".get_class($this)."/";
						$this->save($update);
					}
					
					$handle->Clean();
				}
			}
		}		
	}
	
/**
 * Cette fonction permet d'afficher le shéma d'une table
 *
 * @return 	array Tableau contenant les colonnes de la table
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 12/03/2012 by FI
 */
	function shema() {
	
		$sql = "SHOW COLUMNS FROM ".$this->table;
		$shema = array();
		$result = $this->query($sql, true);
		foreach($result as $k => $v) { $shema[] = $v['Field']; }
		return $shema;
	}	
	
/////////////////////////////	
//   MOTEUR DE RECHERCHE   //	
/////////////////////////////
			
/**
 * Cette fonction permet la construction des index de recherche
 * Lors de la mise en place de l'internationnalisation il faudra sauvegarder l'ensemble des données traduites
 *
 * @param 	array 	$data 	Tableau contenant les données à indexer
 * @param 	integer $id 	Identifiant de l'élément à indexer
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 06/02/2012 by FI
 * @version 0.2 - 12/03/2012 by FI - Rajout du keyword pkdelete pour éviter les confusions d'id lors de la suppression, optimisation de l'index une fois les données ajoutées
 */	
	function make_search_index($datas, $id) {
		
		require_once(BEHAVIORS.DS.'searchable.php'); //Inclusion de la librairie
		$searchable = new Searchable(); //Création d'un objet Searchable
		
		$searchable->deleteEntries($id, get_class($this)); //On va supprimer les éventuelles entrées dans l'index		
		$this->_make_search_index($datas, $searchable, $id); 
	}
	
/**
 * Cette fonction permet la suppression d'un index dans le moteur de recherche
 *
 * @param 	integer Identifiant de l'élément à supprimer
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 12/03/2012 by FI
 */	
	function delete_search_index($id) {
		
		require_once(BEHAVIORS.DS.'searchable.php'); //Inclusion de la librairie
		$searchable = new Searchable(); //Création d'un objet Searchable		
		$searchable->deleteEntries($id, get_class($this)); //On va supprimer les éventuelles entrées dans l'index
	}
	
/**
 * Cette fonction permet l'optimisation de l'index de recherche
 *
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 12/03/2012 by FI
 */
	function optimize_search_index($id) {
	
		require_once(BEHAVIORS.DS.'searchable.php'); //Inclusion de la librairie
		$searchable = new Searchable(); //Création d'un objet Searchable	
		$searchable->optimize(); //Optimisation de l'index
	}
	
/**
 * Cette fonction permet la sauvegarde des index de recherche
 * 
 * @param 	array	$datasToIndex Données à indexer
 * @param 	object	$searchable Comportement Searchable 
 * @param 	integer	$id Identifiant de l'élément à indexer 
 * @access	private
 * @author	koéZionCMS
 * @version 0.1 - 16/04/2012 by FI
 */	
	function _make_search_index($datasToIndex, $searchable, $id) {

		$fieldsToIndex = $this->fields_to_index['fields']; //Récupération des champs à indexer
		$fieldsToDisplay = $this->fields_to_index['display']; //Récupération des champs à afficher lors de la recherche
		
		$searchable->createDocument(); //Création d'un nouveau document
		
		$searchable->addField('Keyword', 'pk', $id); //Ajout dans l'index du champ contenant la valeur de la clée primaire
		$searchable->addField('Keyword', 'pkdelete', get_class($this).$id); //Ajout dans l'index du champ contenant la valeur de la clée primaire concaténée au nom du model
		$searchable->addField('Keyword', 'model', get_class($this)); //Ajout dans l'index du champ contenant le type de Model
		
		//Ajout des champs à indexer
		foreach($fieldsToIndex as $k => $v) {
		
			//Ajout dans l'index du champ contenant l'identifiant de l'élément
			if(isset($datasToIndex[$v])) { $searchable->addField('UnStored', $v, $datasToIndex[$v]); }
		}
		
		//Ajout des champs à afficher
		foreach($fieldsToDisplay as $k => $v) {
		
			//Ajout dans l'index du champ contenant l'identifiant de l'élément
			if(isset($datasToIndex[$v])) { $searchable->addField('Text', $k, $datasToIndex[$v]); }
		}
		
		$searchable->addDocument(); //Ajout du document
		$searchable->commit(); //Sauvegarde des données		
	}
}