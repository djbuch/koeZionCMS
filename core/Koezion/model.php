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
	public $schema = array(); //Shéma de la table
	public $queryExecutionResult = false; //indique si la requete de save s'est bien passée 
	public $refererUrl = ''; //Cette variable va contenir l'url de la page appelante
	public $manageWebsiteId = true; //Permet d'éviter de prendre en compte la recherche basée sur le champ website_id ainsi que l'insertion automatique de ce champ
	
	protected $alias = false; //Alias de la table
    
/**
 * Constructeur de la classe
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 28/12/2011 by FI
 * @version 0.2 - 02/03/2012 by FI - Modification de la récupération de la configuration de la base  de données, on passe maintenant par un fichier .ini
 * @version 0.3 - 11/04/2012 by FI - Modification de la récupération du .ini seulement deux configurations possibles localhost et online
 * @version 0.4 - 24/08/2012 by FI - Rajout du shéma de la table comme variable de classe
 * @version 0.5 - 07/09/2012 by FI - Rajout de la variable database si la connexion existe déjà
 * @version 0.6 - 14/12/2012 by FI - Rajout de la variable $refererUrl dans le constructeur pour les logs bdd
 * @version 0.7 - 05/07/2013 by FI - Gestion de l'alias
 * @version 0.8 - 11/11/2013 by FI - Rajout de la source en variable en prévision de futures évolutions
 */
	public function __construct($refererUrl = null) {

		//ANCIENNE VERSION DE RECUPERATION DE LA CONFIGURATION DE LA BASE DE DONNEES
		//if($_SERVER["HTTP_HOST"] != 'localhost') { $this->conf = 'online'; } //On va tester si on est en local ou pas		        		
		//$conf = Database::$databases[$this->conf]; //Récupération de la configuration de la base de données

		$this->refererUrl = $refererUrl;
		
		$httpHost = $_SERVER["HTTP_HOST"];
		if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $section = 'localhost'; } else { $section = 'online'; }
		
		require_once(LIBS.DS.'config_magik.php'); //Import de la librairie de gestion des fichiers de configuration 
		$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'database.ini', true, true); //Création d'une instance
		$conf = $cfg->keys_values($section); //Récupération des configurations en fonction du nom de domaine (Ancienne version : $conf = $cfg->keys_values($_SERVER["HTTP_HOST"], 1);)
		$conf['source'] = "mysql";
		
		//Si le nom de la table n'est pas défini on va l'initialiser automatiquement
		//Par convention le nom de la table sera le nom de la classe en minuscule avec un s à la fin
		if($this->table === false) {
		
			$prefix = $conf['prefix']; //On va récupérer la valeur du préfix se trouvant dans le tableau de configuration de la base de données
			$tableName = Inflector::tableize(get_class($this)); //Mise en variable du nom de la table
			$this->table = $prefix.$tableName; //Affectation de la variable de classe
		}		
		
		$this->alias = "Kz".get_class($this);
		
		//On test qu'une connexion ne soit pas déjà active
		if(isset(Model::$connections[$this->conf])) {
			   
			$this->db = Model::$connections[$this->conf];	
			$this->database = $conf['database'];
			$this->shema = $this->shema();
			return true;
		}
        
		//On va tenter de se connecter à la base de données
		try {
            
			//Création d'un objet PDO
			$pdo = new PDO(
				$conf['source'].':host='.$conf['host'].';dbname='.$conf['database'], 
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
			$this->shema = $this->shema();
			
		} catch(PDOException $e) { //Erreur
    
			//Test du mode debug
			if(Configure::read('debug') >= 1) {
				
				$message = '<pre style="background-color: #EBEBEB; border: 1px dashed black; padding: 10px;">';
				$message .= "La base de donn&eacute;es n'est pas disponible merci de r&eacute;&eacute;ssayer plus tard ".$e->getMessage();
				$message .= '</pre>';				
				die($message);
				
			} else {
								
				$message = '<pre style="background-color: #EBEBEB; border: 1px dashed black; padding: 10px;">';
				$message .= "La base de donn&eacute;es n'est pas disponible merci de r&eacute;&eacute;ssayer plus tard ";
				$message .= '</pre>';				
				die($message);
				
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
				
		$preparedQuery = $this->db->prepare($sql); //On prépare la requête
		$result = $preparedQuery->execute(); //On l'exécute
		
		$this->_trace_sql('function query', $preparedQuery->queryString); //Récupération de la requête
		
		if($result) { //Si l'exécution s'est correctement déroulée
			
			if($return) { return $preparedQuery->fetchAll(PDO::FETCH_ASSOC); } //On retourne le résultat si demandé
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
	/*public function find($req = array(), $type = PDO::FETCH_ASSOC) {
		
		$shema = $this->shema;
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
		if(in_array('website_id', $shema) && !in_array(get_class($this), array('UsersWebsite', 'UsersGroupsWebsite'))) {
		
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
		//   CHAMPS GROUP BY   //
		if(isset($req['groupBy'])) { $sql .= ' '.$req['groupBy']; }
		
		//////////////////////
		//   CHAMPS ORDER   //
		if(isset($req['order'])) { $sql .= ' ORDER BY '.$req['order']; }
		
		//////////////////////
		//   CHAMPS LIMIT   //
		if(isset($req['limit'])) { $sql .= ' LIMIT '.$req['limit']; }
		
		//if($this->trace_sql) { pr($sql); }
		
		$preparedQuery = $this->db->prepare($sql);
		$preparedQuery->execute();
		
		$this->_trace_sql('function find', $preparedQuery->queryString); //Récupération de la requête
		
        return $preparedQuery->fetchAll($type);        
    }*/
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
 *  - innerJoin (optionnel) : cet index permet d'effectuer une instruction "INNER JOIN" dans une requête, il contient un tableau qui doit lui-même contenir les index 'table' et 'pivot'. 
 *    L'index 'table' contient le nom de la table sur laquelle on va effectuer la recherche, et l'index 'pivot' contient la comparaison entre les deux tables. 
 *    L'utilisation de l'index 'fields' est obligatoire pour déterminer les éléments à récupérer dans les multiples tables.
 * 		
 * 	  Exemple d'utilisation : On récupère des données à partir d'une table d'association. On souhaite également récupérer l'attribut "name" des éléments entrés dans cette table. 
 *    Mais vu que la table d'association ne contient que l'id, on va effectuer un "INNER JOIN" sur la table d'origine pour récupérer l'attribut "name" dans celle-ci.
 * 		
 *    Voici un exemple de code php :
 *    
 * 			$datas = $this->AssociationModel->find(array(									#On effectue l'action dans le modèle de la table d'association
 * 				'fields' => array(' AssociationModel.* ', ' `original_table`.name '),		#On définit les tables dans lesquelles on va chercher les données, ainsi que les champs (le champ 'name' en l'occurrence)
 * 				'innerJoin' => array(
 * 					'table' => ' `original_table` ',										#Dans le champ "table" on entre le nom de la table d'origine dans laquelle se trouve l'attribut "name" que l'on cherche
 * 					'pivot' => ' AssociationModel.`original_id` = `original_table`.`id` '	#Ceci est notre point de pivot : on compare l'id dans la table d'association avec l'id dans la table originale, afin de récupérer les bons éléments
 * 				)
 * 			));
 *	  Celui-ci ne concerne qu'une seule table et donc un seul "INNER JOIN", mais on peut récupérer également des infos sur plusieurs tables.
 * 	  Exemple concret : Notre table d'association contient l'id d'un livre et l'id d'un catalogue dans lequel ce livre est contenu. Un livre peut donc être dans plusieurs catalogues.
 * 	  Voici un exemple de code php qui va exécuter une requête qui va récupérer le nom du livre et le nom du catalogue, le tout à partir de la table d'association
 * 
 * 			$datas = $this->AssociationCataloguesLivres->find(array(								#On effectue l'action dans le modèle de la table d'association
 * 				'fields' => array(
 * 					' AssociationCataloguesLivres.* ', 
 * 					' table_livres.name as livre_name ', 
 * 					' table_catalogues.name as catalogue_name '
 * 				),																					#On définit les tables dans lesquelles on va chercher les données, ainsi que les champs (le champ 'name' en l'occurrence) et on met un alias pour que PHP trie correctement le tableau
 *				'innerJoin' => array(
 *					array(
 *						'table' => ' table_livres ',												#Nom de la table des livres
 *						'pivot' => ' AssociationCataloguesLivres.livre_id = table_livres.id '		#Point de pivot pour la table des livres
 *					),
 *					array(
 *						'table' => ' table_catalogues ',											#Nom de la table des catalogues
 *						'pivot' => ' AssociationCataloguesLivres.livre_id = table_catalogues.id '	#Point de pivot pour la table des catalogues
 *					),
 *				)
 *			));
 * 
 * @param 	array 	$req 	Tableau de conditions et paramétrages de la requete
 * @param 	object 	$type 	Indique le type de retour de PDO dans notre cas un tableau dont les index sont les colonnes de la table
 * @return 	array 	Tableau contenant les éléments récupérés lors de la requête  
 * @version 0.1 - 28/12/2011 by FI
 * @version 0.2 - 02/05/2012 by FI - Mise en place de la conditions de récupérations selon l'identifiant du site
 * @version 0.3 - 30/05/2012 by FI - Modification de la génération de la condition de recherche pour intégrer l'utilisation de tableau de condition sans index particulier ==> $condition = array('conditions' => array("name LIKE '%...%'"));
 */    
	public function find($req = array(), $type = PDO::FETCH_ASSOC) {
		
		$shema = $this->shema;
		$sql = 'SELECT '; //Requete sql
        
		///////////////////////
		//   CHAMPS FIELDS   //		
		if(!isset($req['fields'])) {
			
			//Si aucun champ n'est demandé on va récupérer le shéma de la table et récupérer ces champs
			//Dans le cas de table traduite on va également récupérer les champs traduits ainsi que la langue associée
			$req['fields'] = $shema;
		}	
		
		if(is_array($req['fields'])) { $sql .= '`'.implode('`, `', $req['fields']).'`'; } //Si il s'agit d'un tableau		
		else { $sql .= $req['fields']; } //Si il s'agit d'une chaine de caractères 
		
		//$sql .= ' FROM '.$this->table.' AS '.get_class($this).' '; //Mise en place du from//Mise en place du from
		$sql .= ' FROM `'.$this->table.'` AS '.$this->alias.' ';		
		//if(get_class($this) != 'Order') { $sql .= 'AS '.get_class($this).' '; } //Hack spécial si on a un model Order

		///////////////////////////////////////////////////////////
		//   CONDITIONS DE RECHERCHE SUR L'IDENTIFIANT DU SITE   //
		//Si dans le shema de la table on a une colonne website_id		
		if($this->manageWebsiteId && in_array('website_id', $shema) && get_class($this) != 'UsersGroupsWebsite') {
		
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
		//   CHAMPS INNER JOIN   //
		if(isset($req['innerJoin']) && !empty($req['innerJoin'])) {
			if (!is_array($req['innerJoin'])) {
				$sql .= $req['innerJoin'].' ';//On ajoute à la requête s'il s'agit d'une chaîne
			} else {
				if (isset($req['innerJoin'][0])) {//Si l'on a un tableau à index numérique, on peut avoir plusieurs "join" à la suite et sur plusieurs tables
					foreach ($req['innerJoin'] as $k => $v) {
						$sql .= ' INNER JOIN '.$v['table'].' ON '.$v['pivot'].' ';//On ajoute à la requête
					}
				} else {//Sinon, on n'a qu'un seul "join"
					$sql .= ' INNER JOIN '.$req['innerJoin']['table'].' ON '.$req['innerJoin']['pivot'].' ';//On ajoute à la requête
				}
			}
		}
		
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
					
					
					
					//On va ensuite tester si la clé est une chaine de caractère
					//On rajoute le nom de la classe devant le nom de la colonne
					if(is_string($k)) { 

						//On va échaper les caractères spéciaux
						//Equivalement de mysql_real_escape_string --> $v = '"'.mysql_escape_string($v).'"';
						if(!is_numeric($v)) { $v = $this->db->quote($v); }
						
						$k = $this->alias.".".$k;
						$cond[] = "$k=$v";
						
					} 
					else { $cond[] = $v; } //Sinon on rajoute directement la condition dans le tableau
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
		//   CHAMPS GROUP BY   //
		if(isset($req['groupBy'])) { $sql .= ' '.$req['groupBy']; }
		
		//////////////////////
		//   CHAMPS ORDER   //
		if(isset($req['order'])) { $sql .= ' ORDER BY '.$req['order']; }
		
		//////////////////////
		//   CHAMPS LIMIT   //
		if(isset($req['limit'])) { $sql .= ' LIMIT '.$req['limit']; }
		
		//if($this->trace_sql) { pr($sql); }
		
		$preparedQuery = $this->db->prepare($sql);
		$preparedQuery->execute();
		
		$this->_trace_sql('function find', $preparedQuery->queryString); //Récupération de la requête
		
        return $preparedQuery->fetchAll($type);        
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
				'fields' => 'COUNT('.$this->alias.'.'.$this->primaryKey.') AS count',
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
	public function findList($conditions = null, $field = 'name', $key = 'id') {
	
		$queryResult = $this->find($conditions);		
		
		//On formate les résultats
		$result = array();
		foreach($queryResult as $k => $v) { 
			
			if(empty($key)) { $result[$k] = $v[$field]; }
			else { $result[$v[$key]] = $v[$field]; }			 
		}
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
		//$queryResult = $this->db->query($sql);
		$queryResult = $this->query($sql);
		
		if(isset($this->searches_params)) { $this->delete_search_index($idConditions); } //Suppression de l'index dans la recherche
		
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
		
		//if(isset($this->searches_params)) { $this->delete_search_index($id); } //Suppression de l'index dans la recherche
		
		//return $this->db->query($sql);
		return $this->query($sql);
	}	
	
/**
 * Cette fonction est chargée de vider une table
 * 
 * @return  objet Objet PDO
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 07/03/2013 by FI
 */		
	public function truncate() {
		
		$sql = "TRUNCATE TABLE ".$this->table.";";  //Requête de suppression
		$queryResult = $this->query($sql);		
		return $queryResult;
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
 * @version 1.0 - 24/08/2012 by FI - Changement radical de la gestion des INSERT ou UPDATE afin d'utiliser pleinement la fonctionnalité des requêtes préparées
 * 									 on a divisé le process en 2 on récupère d'un coté les infos de la requête et de l'autre les données à sauvegarder avec des fonctions privées
 * 
 * @todo mettre en place des try catch pour vérifier que la requete c'est bien exécutée 
 */	
	public function save($datas, $forceInsert = false, $escapeUpload = true) {
					
		$preparedInfos = $this->_prepare_save_query(array_keys($datas), $forceInsert, $escapeUpload); //Récupération des données de la préparation de la requête
		$datasToSave = $this->_prepare_save_datas($datas, $preparedInfos['moreDatasToSave'], $forceInsert, $escapeUpload); //Récupération des données à sauvegarder
		
		$queryExecutionResult = $preparedInfos['preparedQuery']->execute($datasToSave); //Exécution de la requête
		
		$this->_trace_sql('function save', $preparedInfos['preparedQuery']->queryString); //Récupération de la requête
		
		//Affectation de la valeur de la clé primaire à la variable de classe
		if($preparedInfos['action'] == 'insert') { $this->id = $this->db->lastInsertId(); }
		else { $this->id = $datas[$this->primaryKey]; }
		
		$this->queryExecutionResult = $queryExecutionResult;
		
		//if(isset($this->files_to_upload) && $proceedUpload) { $this->upload_files($datas, $this->id); } //Sauvegarde éventuelle des images
		if(isset($this->files_to_upload)) { $this->upload_files($datas, $this->id); } //Sauvegarde éventuelle des images
		if(isset($this->searches_params)) { $this->make_search_index($datasToSave, $this->id, $preparedInfos['action']); } //On génère le fichier d'index de recherche
	}

/**
 * Fonction chargée d'éffectuer la sauvegarde d'une liste de données
 * 
 * @param 	array 	$data 			Données à sauvegarder
 * @param 	boolean $forceInsert 	Booléan permettant même si le champ id est présent dans le tableau de forcer l'insert
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 24/08/2012 by FI
 * @version 0.2 - 03/04/2013 by FI - Correction de l'affectation des ids
 */	
	function saveAll($datas, $forceInsert = false, $escapeUpload = true) {
		
		if(!empty($datas)) {
			
			$preparedInfos = $this->_prepare_save_query(array_keys(current($datas)), $forceInsert, $escapeUpload);
			foreach($datas as $k => $v) { 
				
				$datasToSave = $this->_prepare_save_datas($v, $preparedInfos['moreDatasToSave'], $forceInsert, $escapeUpload);
				$queryExecutionResult = $preparedInfos['preparedQuery']->execute($datasToSave);
				
				$this->_trace_sql('function saveAll', $preparedInfos['preparedQuery']->queryString); //Récupération de la requête
				
				if($preparedInfos['action'] == 'insert') { $this->id[] = $this->db->lastInsertId();}
				else { $this->id[] = $v['id']; }
				
				$this->queryExecutionResult = $queryExecutionResult;
				
				if(isset($this->searches_params)) { $this->make_search_index($datasToSave, $this->id, $preparedInfos['action']); } //On génère le fichier d'index de recherche
			}
		}
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
 * @version 0.5 - 25/06/2013 - Changement du include_once en include pour le chargement des messages d'erreurs car dans le cas de validations multiples le fichier n'était pas chargé  
 * @version 0.6 - 09/11/2013 - Rajout d'un nouveau paramètre aux règles de validation permettant de paramétrer une règle mais de ne l'appliquer que si le champ n'est pas vide  
 */	
	function validates($datas) {
				
		if(isset($this->validate)) { //Si on a un tableau de validation dans la classe
			
			$errors = array(); //Tableau d'erreurs qui sera retourné
			include(CONFIGS.DS.'messages.php'); //Inclusion des éventuels messages d'erreurs
						
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
							
							if(!isset($vRule['allowEmpty'])) { $vRule['allowEmpty'] = false; } //Par défaut si l'index allowEmpty n'existe pas on le rajoute
							
							$isValid = $validation->check($datas[$k], $vRule['rule']); //Lancement de la règle
							$allowEmpty = $vRule['allowEmpty'] && empty($datas[$k]); //Génération du booléen allowEmpty
							
							//On injecte le message en cas d'erreur
							if(!$isValid && !$allowEmpty) { 
								
								if(Set::check($Errorsmessages, $vRule['message'])) { $errors[$k] = Set::classicExtract($Errorsmessages, $vRule['message']); }
								else { $errors[$k] = $vRule['message']; }
								 
							}	
						}	
					} else { 
						
						if(!isset($v['allowEmpty'])) { $v['allowEmpty'] = false; } //Par défaut si l'index allowEmpty n'existe pas on le rajoute
							
						$isValid = $validation->check($datas[$k], $v['rule']); //Lancement de la règle
						$allowEmpty = $v['allowEmpty'] && empty($datas[$k]); //Génération du booléen allowEmpty

						//On injecte le message en cas d'erreur
						if(!$isValid && !$allowEmpty) { 
								
							if(Set::check($Errorsmessages, $v['message'])) { $errors[$k] = Set::classicExtract($Errorsmessages, $v['message']); }
							else { $errors[$k] = $v['message']; }
								 
						} 						
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
 * @deprecated since 24/08/2012 - On passe par ckfinder pour les upload plus simple et plus léger
 */	
	function upload_files($datas, $id) {
		
		require_once(BEHAVIORS.DS.'upload.php');		
		foreach($this->files_to_upload as $k => $v) {
			
			if(isset($datas[$k])) {
				
				$handle = new Upload($datas[$k]);
				if($handle->uploaded) {
					
					if(isset($v['path']) && $v['path']) { $filePath = $v['path']; }
					else { $filePath = WEBROOT.DS."upload".DS.get_class($this); }
					
					$handle->Process($filePath);					
					$fileName = $handle->file_dst_name;
					
					//On va stocker dans le model le nom du fichier téléchargé dans le cas ou celui-ci serait changé
					$this->files_to_upload[$k]['uploaded_name'] = $fileName;

					//Sauvegarde en base de données
					if(isset($v['bdd']) && $v['bdd']) {
												
						$primaryKey = $this->primaryKey;						
						$update = array();
						$update[$primaryKey] = $id;
						$update[$k] = $fileName;						
						
						$sql = "UPDATE ".$this->table." SET ".$k." = '".$fileName."' WHERE ".$primaryKey." = ".$id;
						$this->query($sql);
					}
					
					$handle->Clean();
				}
			}
		}
	}
	
/**
 * Cette fonction permet d'afficher le shéma d'une table
 *
 * @param 	varchar $table Nom de la table dont on souhaite récupérer le shéma (Par défaut $this->table sera utilisé)
 * @return 	array 	Tableau contenant les colonnes de la table
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 12/03/2012 by FI
 * @version 0.2 - 07/09/2012 by FI - Test pour vérifier si la table existe dans le cas de model sans table
 * @version 0.3 - 08/05/2013 by FI - Modification de la fonction pour y intégrer la possibilité de récupérer le shéma d'une table passée en paramètre
 */
	function shema($table = null) {		
		
		$shema = array();
		if(!isset($table)) { $table = $this->table; }
		if($this->exist_table_in_database($table) == 1) {			
		
			$cacheFolder 	= TMP.DS.'cache'.DS.'models'.DS;
			$cacheFile 		= $table;			
			$shema = Cache::exists_cache_file($cacheFolder, $cacheFile);
			
			if(!$shema) { 
			
				$sql = "SHOW COLUMNS FROM `".$table.'`;';			
				$result = $this->query($sql, true);
				foreach($result as $k => $v) { $shema[] = $v['Field']; }
				
				Cache::create_cache_file($cacheFolder, $cacheFile, $shema);
			}
		}		
		return $shema;
	}
	
/**
 * Cette fonction permet de récuperer les informations liées à la table 
 */	
	function table_status() {
		
		$sql = "SHOW TABLE STATUS LIKE '".$this->table."';";
		$result = $this->query($sql, true);
		return current($result);
	}
	
/**
 * Cette fonction permet de tester l'existence d'une table dans la base de données
 *
 * @param 	varchar $table 		Table à tester
 * @return 	integer Résultat de la requête
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 07/09/2012 by FI
 * @version 0.2 - 04/03/2013 by FI - Suppression de la variable database et récupération de celle-ci de la variable de classe
 */
	function exist_table_in_database($table) {		
				
		$tablesList = $this->table_list_in_database();		
		return in_array($table, $tablesList);
	}		
	
/**
 * Cette fonction permet de récupérer la liste des tables de la base de données
 *
 * @return 	array	Liste des tables
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 04/03/2013 by FI
 * @version 0.2 - 29/10/2013 by FI - Rajout d'un test pour supprimer du tableau les tables des plugins désinstallés 
 */
	function table_list_in_database() {		
				
		$cacheFolder 	= TMP.DS.'cache'.DS.'models'.DS;
		$cacheFile 		= "tables_list";	
		
		$tablesList = Cache::exists_cache_file($cacheFolder, $cacheFile);		
		
		if(!$tablesList) {
		
			$tablesList = array();
			$sql = 'SHOW TABLES FROM `'.$this->database.'`;';
			foreach($this->query($sql, true) as $k => $v) {
				
				$value = array_values($v);
				if($value[0]{0} != '_') { $tablesList[] = $value[0]; } //Si le premier caractère n'est pas un underscore
			}
			
			Cache::create_cache_file($cacheFolder, $cacheFile, $tablesList);
		}
		
		return $tablesList;
	}
	
/////////////////////////////
//   MOTEUR DE RECHERCHE   //
/////////////////////////////

/**
 * Cette fonction permet la construction des index de recherche
 *
 * @param 	array 	$data 	Tableau contenant les données à indexer
 * @param 	integer $id 	Identifiant de l'élément à indexer
 * @param 	varchar $action	Type d'action (INSERT ou UPDATE)
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 26/08/2012 by FI
 */
	function make_search_index($datasToSave, $id, $action) {
				
		$searchesParams = $this->searches_params; //Paramètres des champs à indexer
		$fieldsToIndex = $searchesParams['fields']; //Liste des champs à indexes
		$urlParams = $searchesParams['url']; //Paramètres de l'url
		
		$datasToSaveKeys = array_keys($datasToSave); //Liste des clés des champs du model
		$searchesDatas = ''; //Données de recherche
		
		//On parcours les champs à indexer
		foreach($fieldsToIndex as $v) {
			
			//Si la clé à indexer est dans le tableau des données à sauvegarder on concatène à la chaine	
			if(in_array(':'.$v, $datasToSaveKeys)) { $searchesDatas .= ' '.$datasToSave[':'.$v]; }
		}
		
		///////////////////////
		//Génération de l'url//
		$url = $urlParams['url'];
		$url = str_replace(':id', $id, $url);
		foreach($urlParams['params'] as $v) { $url = str_replace(':'.$v, $datasToSave[':'.$v], $url); }
		$url = Router::url($url);
		
		//En cas de mise à jour on supprime l'ancienne valeur
		if($action == "update") { $this->delete_search_index($id); }
		
		///////////////////////////////////////
		//Sauvegarde des données de recherche//
		$searchDatas = array(
			'model' => get_class($this),
			'title' => $datasToSave[':page_title'],
			'description' => $datasToSave[':page_description'],
			'datas' => strip_tags($searchesDatas),
			'url' => $url,
			'model_id' => $id				
		);		
		require_once(MODELS.DS.'search.php'); //Chargement du model
		$search = new Search();		
		$search->save($searchDatas);
		unset($search); //Déchargement du model
	}	
	
/**
 * Cette fonction permet la suppression d'un index de recherche
 *
 * @param 	integer $id 	Identifiant de l'élément à supprimer
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 26/08/2012 by FI
 */
	function delete_search_index($id) {	
						
		$sql = "DELETE From searches WHERE model = '".get_class($this)."' AND model_id = ".$id;
		$this->query($sql);		
	}
	
//////////////////////////////////
//   PREPARATION DES REQUETES   //
//////////////////////////////////
	
/**
 * Cette fonction permet la génération de la requête préparée
 * 
 * @param 	array	$datasShema 	Shéma des champs de la table
 * @param 	boolean	$forceInsert 	Indique si il faut forcer l'insert
 * @param 	boolean	$escapeUpload 	Indique si il faut ou non ne pas tenir compte des champs à uploader
 * @return	array	Tableau contenant les paramètres de la requête préparée
 * @access	private
 * @author	koéZionCMS
 * @version 0.1 - 24/08/2012 by FI
 */	
	function _prepare_save_query($datasShema, $forceInsert, $escapeUpload) {
					
		$shema = $this->shema; //Shema de la table		
		$primaryKey = $this->primaryKey; //Récupération de la clé primaire
		
		$fieldsToSaveToSave = array(); //Tableau des champs de la table à sauvegarder
		$moreDatasToSave = array(); //Tableau des données supplémentaires à sauvegarder (évite de les regénérer à chaque fois)
		
		//Permet de connaitre le type de requete à effectuer
		//Dans ce cas on est sur de l'update				
		if(in_array($primaryKey, $datasShema) && !$forceInsert) { $action = 'update'; } 
		else { 
			
			$action = 'insert'; //Définition de l'action
			
			//Par défaut on va rajouter les champs created et created by lors de l'INSERT
			if(!in_array('created', $datasShema) && in_array('created', $shema)) { 
				
				$datasShema[] = 'created'; 
				$moreDatasToSave[':created'] = date('Y-m-d H:i:s');
			}	
					
			if(!in_array('created_by', $datasShema) && in_array('created_by', $shema)) { 
				
				$datasShema[] = 'created_by'; 
				$moreDatasToSave[':created_by'] = Session::read('Backoffice.User.id');
			}		
					
			/*if(!in_array('send_newsletter', $datasShema) && in_array('send_newsletter', $shema)) { 
				
				$datasShema[] = 'send_newsletter'; 
				$moreDatasToSave[':send_newsletter'] = 1;
			}*/		
		}
		
		//Génération des chammps supplémentaires
		if(!in_array('modified', $datasShema) && in_array('modified', $shema)) { 
			
			$datasShema[] = 'modified'; 
			$moreDatasToSave[':modified'] = date('Y-m-d H:i:s');
		}
		if(!in_array('modified_by', $datasShema) && in_array('modified_by', $shema)) { 
			
			$datasShema[] = 'modified_by';
			$moreDatasToSave[':modified_by'] = Session::read('Backoffice.User.id');
		}						
		
		if(!in_array('website_id', $datasShema) && in_array('website_id', $shema) && $this->manageWebsiteId) { //get_class($this) != 'UsersGroupsWebsite') { 
			
			$datasShema[] = 'website_id';
			$moreDatasToSave[':website_id'] = CURRENT_WEBSITE_ID;
		}
		
		if(in_array('slug', $shema) && (!in_array('slug', $datasShema))) { $datasShema[] = 'slug'; } 
		if(in_array('page_title', $shema) 	&& (!in_array('page_title', $datasShema))) 	{ $datasShema[] = 'page_title'; } 
		
		//if(in_array('password', $datasShema)) { $datasShema[] = 'password'; }
						
		//On contrôle que la clé primaire ne soit pas dans le tableau si on a demandé de forcer l'ajout
		if(in_array($primaryKey, $datasShema) && !$forceInsert) {
			
			$indexKey = array_search($primaryKey, $datasShema);
			unset($datasShema[$indexKey]);
		}
				
		//On fait le parcours des données
		foreach($datasShema as $v) {

			if(isset($this->files_to_upload) && isset($this->files_to_upload[$v]) && $escapeUpload) continue; //On supprime si il y en a les champs d'upload
			if(in_array($v, $shema)) { $fieldsToSave[] = "$v=:$v"; } //On récupère le shéma de la table pour être sur de n'ajouter à la requête que des champs présent dans la table pour éviter les erreurs
		}
		
		//On va tester l'existence de cette clé dans le tableau des datas
		if($action == 'update') { $sql = 'UPDATE '.$this->table.' SET '.implode(',', $fieldsToSave).' WHERE '.$primaryKey.'=:'.$primaryKey.';'; } 
		else { $sql = 'INSERT INTO '.$this->table.' SET '.implode(',', $fieldsToSave).';'; }
				
		return array(
			'preparedQuery' => $this->db->prepare($sql),
			'action' => $action,
			'moreDatasToSave' => $moreDatasToSave,
			'fieldsToSave' => $fieldsToSave
		);
	}
	
/**
 * Cette fonction permet la génération des champs à insérer dans la requête préparée
 * 
 * @param 	array	$datas 				Données à sauvegarder
 * @param 	array	$moreDatasToSave 	Champs supplémentaires à sauvegarder (created par exemple, provient de _prepare_save_query)
 * @param 	boolean	$forceInsert 		Indique si il faut forcer l'insert
 * @param 	boolean	$escapeUpload 		Indique si il faut ou non ne pas tenir compte des champs à uploader
 * @return	array	Tableau contenant les paramètres des données à sauvegarder
 * @access	private
 * @author	koéZionCMS
 * @version 0.1 - 24/08/2012 by FI
 */		
	function _prepare_save_datas($datas, $moreDatasToSave, $forceInsert, $escapeUpload) {
		
		$shema = $this->shema; //Shéma de la table 
		$datasShema = array_keys($datas); //Shéma des données à sauvegarder
		$primaryKey = $this->primaryKey; //Récupération de la clé primaire
				
		$datasToSave = array(); //Tableau utilisé lors de la préparation de la requête
		if(isset($datas[$primaryKey]) && !empty($datas[$primaryKey]) && !$forceInsert) { $datasToSave[":$primaryKey"] = $datas[$primaryKey]; }
		
		//if(in_array('password', $datasShema)) { $datas['password'] = sha1($datas['password']); } //On procède à la mise à jour du champ password si il existe				
		
		if(in_array('slug', $shema) && (!in_array('slug', $datasShema) || empty($datas['slug']))) { $datas['slug'] = strtolower(Inflector::slug($datas['name'], '-')); } //On procède à la mise à jour du champ slug si celui ci n'est pas rempli ou non présent dans le formulaire mais présent dans la table
		if(in_array('page_title', $shema) && (!in_array('page_title', $datasShema) || empty($datas['page_title']))) { $datas['page_title'] = $datas['name']; } //On procède à la mise à jour du champ page_title si celui ci n'est pas rempli ou non présent dans le formulaire mais présent dans la table
				
		if(isset($datas[$primaryKey]) && !$forceInsert) unset($datas[$primaryKey]); //Il faut supprimer du tableau des données la clé primaire si celle ci est définie
				
		//On fait le parcours des données
		foreach($datas as $k => $v) {

			//On récupère le shéma de la table pour être sur de n'ajouter à la requête que des champs présent dans la table pour éviter les erreurs
			if(in_array($k, $shema)) {
						
				if(isset($this->files_to_upload) && isset($this->files_to_upload[$k]) && $escapeUpload) continue; //On supprime si il y en a les champs d'upload				
				$datasToSave[":$k"] = $v;
			}
		}
		
		return array_merge($datasToSave, $moreDatasToSave);		
	}	
	
	protected function _trace_sql($function, $query) {
			
		require_once(LIBS.DS.'config_magik.php');
		$cfg = new ConfigMagik(CONFIGS.DS.'files'.DS.'core.ini', true, false);
		$conf = $cfg->keys_values();
		
		if($conf['log_sql']) {
			
			$date = date('Y-m-d');
			$traceSql = 
				date('Y-m-d H:i:s').
				"|#|".
				get_class($this).
				"|#|".
				$this->refererUrl.
				"|#|".
				$function.
				"|#|".
				$query.
				"\n";
			
			FileAndDir::put(TMP.DS.'logs'.DS.'models'.DS.$date.'.log', $traceSql, FILE_APPEND);
		}
	}
}

/**
 * Model sans base de données
 */
/*class ModelStd extends Object {

	static $connections = array();
	public $conf = 'localhost'; //Paramètres de connexion par défaut
	public $table = false; //Nom de la table
	public $db; //Variable contenant la connexion à la base de données
	public $primaryKey = 'id';  //Valeur par défaut de la clé primaire
	public $id; //Variable qui va contenir la valeur de la clé primaire après isert ou update
	public $errors = array(); //Par défaut pas d'erreurs
	public $trace_sql = false; //Permet d'afficher la requête exécutée cf fonction find
	public $schema = array(); //Shéma de la table
}*/