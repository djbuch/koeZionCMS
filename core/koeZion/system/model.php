<?php
/**
 * Cette classe permet la gestion de la base de données
 */
class Model extends Object {   
    
	static $connections = array();
    
	public $conf = 'localhost'; //Paramètres de connexion par défaut
	public $table = false; //Nom de la table	
	public $db; //Variable contenant la connexion à la base de données
	public $primaryKey = 'id';  //Valeur par défaut de la clé primaire (Peut être un chaîne de caractère ou un tableau dans le cas de clés composées par exemple array('key1', 'key2'))
	public $id; //Variable qui va contenir la valeur de la clé primaire après isert ou update
	public $errors = array(); //Par défaut pas d'erreurs
	public $trace_sql = false; //Permet d'afficher la requête exécutée cf fonction find
	public $shema = array(); //Shéma de la table
	public $queryExecutionResult = false; //indique si la requete de save s'est bien passée 
	public $refererUrl = ''; //Cette variable va contenir l'url de la page appelante
	public $manageWebsiteId = true; //Permet d'éviter de prendre en compte la recherche basée sur le champ website_id ainsi que l'insertion automatique de ce champ	
	public $manageActivateField = true; //Permet d'éviter de prendre en compte la recherche basée sur le champ activate ainsi que l'insertion automatique de ce champ	
	public $alias = false; //Alias de la table
	public $validAllFields = false; //Indique si il faut ou non valider l'ensemble des champs de la table ou uniquement ceux envoyés
	
/**
 * Tableau contenant l'ensemble des models à tester lors de la suppression
 * 
 * Un exemple de configuration pourrait être
 * var $checkOnDelete = array(
 *		'FirstModel' => 'first_model_field_id',
 *		'SecondModel' => 'second_model_field_id'		
 *	);
 *
 * @var 	boolean/array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 07/04/2014 by FI
 */
	var $checkOnDelete = false;
	
/**
 * Booléen indiquant s'il faut supprimer automatiquement l'ensemble des données connectées à l'élément en cours de suppression
 * 
 * @var 	boolean/array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 03/11/2014 by FI
 */
	var $deleteConnectedDatas = true;
	
/**
 * Tableau contenant la liste des modèle à éviter lors de la suppression des données connectées
 * 
 * @var 	boolean/array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 03/11/2014 by FI
 */
	var $deleteConnectedDatasEscapeModels = false;
	
/**
 * Tableau contenant l'ensemble des champs à traduire
 * 
 * @var 	boolean/array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/03/2015 by FI
 */
	var $fieldsToTranslate = false;
	
/**
 * Booléen indiquant s'il faut ou non récupérer la traduction de l'élément courant
 * 
 * @var 	boolean/array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/03/2015 by FI
 */
	var $getTranslation = true;
	
/**
 * Booléen indiquant s'il faut ou non récupérer l'ensemble des données traduites associées à l'élément courant
 * 
 * @var 	boolean/array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 18/03/2015 by FI
 */
	var $getTranslatedDatas = false;
    
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
 * @version 0.9 - 03/06/2014 by FI - Rajout de la variable $databaseConfigs pour permettre à un modèle de se connecter à une BDD autre que celle par défaut, le format doit être identique à celui retourné par le fichier configs/files/database.ini
 * @version 1.0 - 08/08/2014 by FI - Modification du premier paramètre passé au constructeur pour y ajouter de nouvelles données (la donnée controller_action)
 * @version 1.1 - 20/01/2015 by FI - Mise en place du code pour l'i18n
 * @version 1.2 - 15/10/2015 by SS - Ajout du parametre MYSQL_ATTR_LOCAL_INFILE dans PDO
 */
	public function __construct($modelParams = null, $databaseConfigs = null) {

		$this->refererUrl 			= $modelParams['url'];
		$this->controller_action 	= $modelParams['controller_action'];
		
		//Récupération de la configuration de connexion à la base de données
		$httpHost = $_SERVER["HTTP_HOST"];
		if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $section = 'localhost'; } else { $section = 'online'; }
		
		if(isset($databaseConfigs) && !empty($databaseConfigs)) {
			
			$conf = $databaseConfigs;
			Model::$connections = array();
			
		} else { 
			
			require_once(LIBS.DS.'config_magik.php'); //Import de la librairie de gestion des fichiers de configuration 
			$cfg = new ConfigMagik(CONFIGS_FILES.DS.'database.ini', true, true); //Création d'une instance
			$conf = $cfg->keys_values($section); //Récupération des configurations en fonction du nom de domaine (Ancienne version : $conf = $cfg->keys_values($_SERVER["HTTP_HOST"], 1);)
		}
		$conf['source'] = (isset($conf['source']) ? $conf['source'] : null);
		$conf['host'] = (isset($conf['host']) ? $conf['host'] : null);
		$conf['database'] = (isset($conf['database']) ? $conf['database'] : null);
		$conf['login'] = (isset($conf['login']) ? $conf['login'] : null);
		$conf['password'] = (isset($conf['password']) ? $conf['password'] : null);
		$conf['prefix'] = (isset($conf['prefix']) ? $conf['prefix'] : null);
		
		//$conf['source'] = "mysql";
		
		//Si le nom de la table n'est pas défini on va l'initialiser automatiquement
		//Par convention le nom de la table sera le nom de la classe en minuscule avec un s à la fin
		if($this->table === false) {
		
			$prefix = $conf['prefix']; //On va récupérer la valeur du préfix se trouvant dans le tableau de configuration de la base de données
			$tableName = Inflector::tableize(get_class($this)); //Mise en variable du nom de la table
			$this->table = $prefix.$tableName; //Affectation de la variable de classe
		}		
		
		$this->alias = "Kz".get_class($this);
			
		/////////////////////////////////////////////
		//    GESTION DE L'INTERNATIONALISATION    //			
		if(
			defined('PLUGIN_LOCALIZATION_ACTIV') && 
			PLUGIN_LOCALIZATION_ACTIV && 
			defined('LOCALIZATION_TRANSLATE_FIELDS_FILE') && 
			file_exists(LOCALIZATION_TRANSLATE_FIELDS_FILE)) 
		{			
			//On va contrôler que pour le model en cours il n'existe pas de champs à traduire
			//Le cas échéant on les rajoute dans la variable de classe $fieldsToTranslate
			require_once(LIBS.DS.'config_magik.php'); //Chargement de ConfigMagik
			$cfg = new ConfigMagik(LOCALIZATION_TRANSLATE_FIELDS_FILE, true, true); //Création d'une instance de ConfigMagik avec le fichier des champs à traduire
			$fieldsToTranslate = $cfg->keys_values($this->table); //Récupération des configurations de la table en cours			
			if($fieldsToTranslate) { $this->fieldsToTranslate = $fieldsToTranslate; }
		}
		
		//On test qu'une connexion ne soit pas déjà active
		if(isset(Model::$connections[$this->conf])) {
			   
			$this->db = Model::$connections[$this->conf];	
			$this->database = $conf['database'];
			$this->shema = $this->shema();
			return true;
		}
        
		//On va tenter de se connecter à la base de données
		try {
            
			$dsn = $conf['source'].':host='.$conf['host'].';dbname='.$conf['database'];
			if(!empty($conf['socket'])) { $dsn = $conf['source'].':unix_socket='.$conf['socket'].';dbname='.$conf['database'].';port='.$conf['port']; }
			
			//Création d'un objet PDO
			$pdo = new PDO(
				$dsn, 
				$conf['login'], 
				$conf['password'], 
				array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::MYSQL_ATTR_LOCAL_INFILE => true)
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
				$message .= _("BASE DE DONNEES NON DISPONIBLE, MERCI DE REESSAYER PLUS TARD").$e->getMessage();
				$message .= '</pre>';				
				die($message);
				
			} else {
								
				$message = '<pre style="background-color: #EBEBEB; border: 1px dashed black; padding: 10px;">';
				$message .= _("BASE DE DONNEES INSDISPONIBLE, MERCI DE REESSAYER PLUS TARD");
				$message .= '</pre>';				
				die($message);
				
			}
		}
	}
    
/**
 * Fonctions qui sera exécutée après la sauvegarde des données
 *
 * @param	array	$datas 			Données à sauvegarder
 * @param	varchar	$saveAction		Type d'action générée par le save
 * @param	boolean	$fromSaveAll 	Indique si l'on arrive de saveAll ou non
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/03/2015 by FI
 * @version 0.2 - 30/03/2015 by FI - Modification gestion de la sauvegarde en passant par un modèle tampon
 * @version 0.3 - 30/03/2015 by FI - Rajout de $fromSaveAll pour indiquer si la clé primaire est sous forme de tableau ou non
 * @version 0.4 - 02/04/2015 by FI - Mise en place du test de la valeur à insérer pour rajouter le cas échéant les données de la langue : if(!is_array($v))
 * @version 0.5 - 20/04/2015 by FI - Correction de la gestion des enregistrements des données traduites pour gérer à la fois le insert et le update
 */     
	public function after_save($datas, $saveAction = 'insert', $fromSaveAll = false) {		
		
		////////////////////////////////////////////////
		//    GESTION DE LA TRADUCTION DE LA TABLE    //
		if(isset($this->fieldsToTranslate) && !empty($this->fieldsToTranslate)) {
			
			//Récupération de l'intersection de clés du tableau de traduction et des données à sauvegarder
			$keysIntersect 	= array_intersect_key($datas, array_flip($this->fieldsToTranslate));

			//On va tester l'action générée par la requête de sauvegarde pour modifier la valeur de la clée en fonction de l'insert ou de l'update
			//if($saveAction == 'update') { $primaryKey = array('language', 'model_id'); } else { $primaryKey = 'id'; }
			
			$datasTraduction = array();
			foreach($keysIntersect as $field => $v) {
				
				///////////////////////////
				//    CAS PARTICULIER    //
				//Si $v n'est pas un tableau mais que la table est traduite il faut réorganier les valeurs pour que l'insertion dans la table traduite se fasse correctement
				//Ce cas ce produit par exemple lors de l'ajout d'un nouveau site Internet on ajoute la catégorie racine mais le tableau des champs traduits 
				//(en partant du principe que la table des catégories soit traduite) ne comporte pas les données de la langue il faut donc les rajouter à la volée
				//A voir si cette modification ne pose pas plus de problèmes par la suite
				if(!is_array($v)) {
					
					$translatedDatas = array();
					foreach(Session::read('Backoffice.Languages') as $sessionLanguage) { $translatedDatas[$sessionLanguage['code']] = $v; }
					
				} else { $translatedDatas = $v; }
				
				foreach($translatedDatas as $language => $languageValue) {

					$datasTraduction[$language][$field] = $languageValue;
					$datasTraduction[$language]['language'] = $language; 
					$datasTraduction[$language]['model_id'] = $fromSaveAll ? end($this->id) : $this->id;  //On utilise end pour récupérer le dernier élément ajouté au tableau
				}				
			}
			
			if($datasTraduction) {
				
				//Pour ne pas perturber le fonctionnement standard du CMS on procède à la création d'un model tampon
				//par lequel on va effectuer nos opération de mise à jour des données traduites
				$i18nModel = new Model();
				$i18nModel->table = $this->table.'_i18n';
				$i18nModel->shema = $this->_get_shema($i18nModel->table);
				
				//On va parcourir l'ensemble des données pour vérifier si la ligne est présente ou non dans la table
				//Si elle est présente on fait un update sinon on fait un insert
				//L'insert se présente lorsqu'on ajoute une langue après que les données de paramétrage de la traduction soient mises en place
				foreach($datasTraduction as $language => $languageDatas) {
					
					//On compte le nombre de ligne
					$nbLines = $i18nModel->findCount(array(
						'language' => $languageDatas['language'],
						'model_id' => $languageDatas['model_id']
					));
					
					//En fonction du nombre de ligne on change la valeur de la clé primaire pour faire
					//- Soit un update
					//- Soit un insert
					if($nbLines) { $primaryKey = array('language', 'model_id'); } 
					else { $primaryKey = 'id'; }
				
					$i18nModel->primaryKey = $primaryKey;
					$i18nModel->save($languageDatas);
				}
				
				/*if(isset($this->searches_params)) {
					
					//$this->make_search_index($datasToSave, $this->id, $saveAction);
					pr($this->searches_params);
				}*/				
			}
			
			/*$this->table = $this->table.'_i18n';
			$this->shema = $this->shema();
			$this->primaryKey = $primaryKey;*/
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
 * 	- group ou groupBy (optionnel) : cet index est une chaine de caractères et permet lorsqu'il est renseigné d'effectuer un groupement sur les éléments retournés.
 * 	- order ou orderBy (optionnel) : cet index est une chaine de caractères et permet lorsqu'il est renseigné d'effectuer un tri sur les éléments retournés.
 * 	- limit (optionnel) : cet index est un entier et permet lorsqu'il est renseigné de limiter le nombre de résultats retournés.
 *  - tables (optionnel) : cet index permet de spécifier les tables à charger dans le FROM
 *  - pr (optionnel) : cet index permet d'indiquer s'il faut ou non imprimer la requête à l'écran  
 *  - innerJoin, leftJoin, rightJoin (optionnel) : cet index permet d'effectuer une instruction "INNER|LEFT|RIGHT JOIN" dans une requête, il contient un tableau qui doit lui-même contenir les index model et pivot. 
 *    L'index model contient le nom du model (et donc de la table) sur lequel on va effectuer la recherche.
 *    L'index pivot contient le point de comparaison entre les deux tables. 
 *    L'utilisation de l'index 'fields' est obligatoire pour déterminer les éléments à récupérer dans les différentes tables.
 * 		
 * 	  Exemple d'utilisation : Une table produit liée à plusieurs tables comme par exemple un fournisseur et une marque.
 * 	  On souhaite récupérer le champ name du fournisseur ainsi que le champ name de la marque.
 * 		
 *    Voici un exemple de code php :
 * 		
 *		$this->load_model("Product");		
 *		$product = $this->Product->findFirst(array(
 *			"conditions" => array(
 * 				'OR' => array('id' => $id, 'product_id' => $id),
 *				online' => 1
 *			),
 *			"fields" => am($this->Product->shema, array("supplier_name" => "KzSupplier.name")),
 *			"leftJoin" => array(
 *				"model" => "Supplier",
 *				"pivot" => "KzProduct.supplier_id = KzSupplier.id"				
 *			)
 *		));
 *
 *	  Cet exemple ne concerne qu'une seule table, mais il est possible de cumuler les joins.
 * 		
 *		$this->load_model("Product");		
 *		$product = $this->Product->findFirst(array(
 *			"conditions" => array("id" => $productId),
 *			"fields" => am(
 *				$this->Product->shema, 
 *				array( 
 *					"supplier_name" => "KzSupplier.name"
 *				), 
 *				array( 
 *					"products_mark_name" => "KzProductsMark.name"
 *				)
 *			),
 *			"leftJoin" => array( 
 *				array(
 *					"model" => "Supplier",
 *					"pivot" => "KzProduct.supplier_id = KzSupplier.id"				
 *				),					
 *				array(
 *					"model" => "ProductsMark",
 *					"pivot" => "KzProduct.products_mark_id = KzProductsMark.id"
 *				)
 *			)
 *		));
 *
 *		Cet exemple permet d'utiliser UNION avec deux requêtes
 *		On peut utiliser autant de requête imbriquée que l'on veut
 *
 *		$sales = $this->Model->find(
 *			array('union' =>
 *				array(
 *					array(
 *						'fields' => array(),
 *						'conditions' => array(),
 *						'leftJoin' => array()
 *					),
 *					array(
 *						'fields' => array(),
 *						'conditions' => array(),
 *						'leftJoin' => array()
 *					)
 *				)
 *			), 
 *			array(
 *				'order' => '',
 *				'limit' => ''
 *			)
 *		);
 *
 *		On peut utiliser UNION pour une seule requête
 *		Ce cas est géré mais il n'a aucun interêt
 *		La requête sera alors lancée comme une requête simple
 *
 *		$sales = $this->Model->find(
 *			array('union' =>
 *				array(
 *					array(
 *						'fields' => array(),
 *						'conditions' => array(),
 *						'leftJoin' => array()
 *					)
 *				)
 *			), 
 *			array(
 *				'order' => '',
 *				'limit' => ''
 *			)
 *		);
 *
 *		$sales = $this->Model->find(
 *			array('union' =>
 *				array(
 *					'fields' => array(),
 *					'conditions' => array(),
 *					'leftJoin' => array()
 *				)
 *			), 
 *			array(
 *				'order' => '',
 *				'limit' => ''
 *			)
 *		);
 *
 *	  Ce principe de fonctionnement est identique pour les INNER et le RIGHT JOIN
 * 
 * @param 	array 	$req 	Tableau de conditions et paramétrages de la requete
 * @param 	object 	$type 	Indique le type de retour de PDO dans notre cas un tableau dont les index sont les colonnes de la table
 * @return 	array 	Tableau contenant les éléments récupérés lors de la requête  
 * @version 0.1 - 28/12/2011 by FI
 * @version 0.2 - 02/05/2012 by FI - Mise en place de la conditions de récupérations selon l'identifiant du site
 * @version 0.3 - 30/05/2012 by FI - Modification de la génération de la condition de recherche pour intégrer l'utilisation de tableau de condition sans index particulier ==> $condition = array('conditions' => array("name LIKE '%...%'"));
 * @version 0.4 - 24/02/2014 by FI - Mise en place de la vérification de la présente ou non de l'alias dans les champs des conditions de recherche
 * @version 0.5 - 27/03/2014 by FI - Gestion du champ activate
 * @version 0.6 - 02/04/2014 by FI - Mise en place de la gestion du OR dans les conditions de recherche
 * @version 0.7 - 17/07/2014 by FI - Modification de la gestion du champ fields 
 * @version 0.8 - 20/01/2015 by FI - Gestion i18n 
 * @version 0.9 - 23/01/2015 by FI - Gestion i18n de l'instruction ORDER BY 
 * @version 1.0 - 23/03/2015 by FI - Rajout de l'index tables 
 * @version 1.1 - 23/03/2015 by FI - Rajout de l'index pr 
 * @version 1.2 - 05/04/2015 by FI - Rajout de order by RAND
 * @version 1.3 - 21/04/2015 by FI - Rajout des index group et orderBy en complément de groupBy et order
 * @version 1.4 - 22/04/2015 by FI - Modification de le gestion de la récupération des paramètres des conditions LEFT, RIGHT et INNER JOIN
 * @version 1.5 - 23/04/2015 by FI - Modification de le gestion du OR
 * @version 1.6 - 07/07/2015 by FI - Correction gestion rightJoin et innerJoin (Thks SS) 
 * @version 1.7 - 21/09/2015 by FI - Rajout d'un test sur $orderBy pour vérifier que ce n'est pas déjà un tableau car le explode sur un tableau génère une erreur 
 * @version 1.8 - 09/10/2015 by SS - Mise en place de la mutualisation du code des INNER, LEFT et RIGHT JOIN 
 * @version 1.9 - 14/10/2015 by SS - Correction gestion de l'alias dans le ORDER BY 
 * @version 2.0 - 07/12/2015 by SS - Ajout de la gestion de UNION
 */    
	public function find($req = array(), $type = PDO::FETCH_ASSOC) {
				
		//Dans le cas du i18n on teste, la majorité du temps, que l'on ne soit pas dans le BO car nous devons récupérer tous les champs y compris ceux traduits
		
		$shema = $this->shema;
		if(isset($req['union']) && !empty($req['union']) && is_array($req['union'])) {
			
			if(count($req['union']) > 1) { $union = $req['union']; }
			elseif(count($req['union']) == 1 && isset($req['union'][0])) { $union[] = $req['union'][0]; }
			else { $union[] = $req['union']; }
		}
		else { $union[] = $req; }
		$sql = ''; //Requete sql
		foreach($union as $key => $r) {
			
			$tableOfAlias = array();
			
			///////////////////////
			//    CHAMPS JOIN    //
			if(isset($r['innerJoin']) && !empty($r['innerJoin'])) 	{ $joins['INNER'] = $r['innerJoin']; 	} //INNER
			if(isset($r['leftJoin']) && !empty($r['leftJoin'])) 	{ $joins['LEFT'] = $r['leftJoin']; 	} //LEFT
			if(isset($r['rightJoin']) && !empty($r['rightJoin'])) 	{ $joins['RIGHT'] = $r['rightJoin']; 	} //RIGHT
			
			if(isset($joins) && !empty($joins)) {
					
				foreach($joins as $joinType => $join) {
			
					if(!empty($join)) {
			
						if (is_array($join)) {
							if(isset($join[0])) { //Si l'on a un tableau à index numérique, on peut avoir plusieurs join à la suite et sur plusieurs tables
			
								foreach ($join as $v) {
										
									$joinDatas = $this->_get_left_right_inner_join_datas($v);
									$tableOfAlias[$this->alias] = $this->alias.$key;
									$tableOfAlias[$joinDatas['joinAlias']] = $joinDatas['joinAlias'].$key;
								}
							} else { //Sinon, on n'a qu'un seul join
			
								$joinDatas = $this->_get_left_right_inner_join_datas($join);
								$tableOfAlias[$this->alias] = $this->alias.$key;
								$tableOfAlias[$joinDatas['joinAlias']] = $joinDatas['joinAlias'].$key;
							}
						}
					}
				}
			}
			
			$sql .= (isset($req['union']) && !empty($req['union']) ? '( ' : '');
			$sql .= 'SELECT ';
			
			//Mise en place d'un booléan pour savoir si la traduction est active sur le modèle courant
			//Il faut que :
			//	- $this->fieldsToTranslate existe
			//	- $this->fieldsToTranslate ne soit pas vide
			if(isset($this->fieldsToTranslate) && !empty($this->fieldsToTranslate)) { $translatedTable = true; }
			else { $translatedTable = false; }
					
			/////////////////////////
			//    CHAMPS FIELDS    //					
			//Si aucun champ n'est demandé on va récupérer le shéma de la table et récupérer ces champs
			//Dans le cas de table traduite on va également récupérer les champs traduits ainsi que la langue associée
				if(!isset($r['fields']) && !empty($shema)) { $fields = $shema; } 
				else if(isset($r['fields'])) { $fields = $r['fields']; } 			
				//else { $fields = '*'; }
				
				$i18nFields = null;
				if(isset($fields) && $translatedTable && $this->getTranslation) {
					
					$i18nShema = $this->fieldsToTranslate; //Récupération des champs à traduire

					//On va calculer la différence entre la table "parente" et la table traduite
					$fields = array_diff($fields, $i18nShema);
					
					//Rajout des données complémentaires de la traduction
					$i18nShema['i18n_id'] 		= 'id'; //On gère un alias pour ce champs pour qu'il ne rentre pas en conflit avec l'id de la table parente
					$i18nShema['i18n_language'] = 'language'; //On uniformise avec le champ id
					$i18nShema['i18n_model_id'] = 'model_id'; //On uniformise avec le champ id
					
					$i18nFields = $this->_get_fields($i18nShema, $this->alias.'I18n', null);
				}
				else if(!isset($fields)) { $fields = '*'; }
				
				
				//if(!(isset($req['union']) && !empty($req['union']))) { $sql .= $this->_get_fields($fields, null, $i18nFields); }
				$sql .= $this->_get_fields($fields, null, $i18nFields);
				
			////////////////
			//    FROM    //	
				$tables = array();
				if(isset($r['tables']) && !empty($r['tables'])) {
					
					foreach($r['tables'] as $table => $alias) {
						
						$tables[] = '`'.$table.'` AS `'.$alias.'`';
						//if(isset($req['union']) && !empty($req['union'])) { $sql .= $this->_get_fields($fields, $alias, $i18nFields); }
					}	
					
				} else { $tables[] = '`'.$this->table.'` AS `'.$this->alias.'`'; }
			
				////////////////
				//    I18N    //
					//VERSION SANS LE INNER JOIN
					//Si il y à une variable contenant des champs à traduire on rajoute la table de le from
					if($translatedTable && $this->getTranslation) { 
						
						array_unshift($tables, '`'.$this->table.'_i18n` AS `'.$this->alias.'I18n`');
						//$tables[] = '`'.$this->table.'_i18n` AS `'.$this->alias.'I18n`'; 
					}
					
					$sql .= "\n"."FROM \n\t".implode(", \n\t", $tables)." ";
			
					//VERSION AVEC INNER JOIN changé par une jointure normale car trop restrictive dans l'ordre des tables à charger dans le FROM
					//Cf posts_type model fonction get_for_front l'ordre des tables dans le from générait une erreur
					//Si il y à une variable contenant des champs à traduire
					//On va rajouter le INNER JOIN
					//Ainsi que le pivot qui sera toujours id dans la table source et model_id dans la table de traduction
						/*if($translatedTable && $this->getTranslation) {
						
							$sql .= 'INNER JOIN '.$this->table.'_i18n AS '.$this->alias.'I18n ';
							$sql .= 'ON '.$this->alias.'.id = '.$this->alias.'I18n.model_id '."AND `".$this->alias."I18n`.`language` = '".DEFAULT_LANGUAGE."'"."\n";
						}*/	
					
			///////////////////////
			//    CHAMPS JOIN    //
				//Nouvelle version proposée par SS rajoutée le le 09/10/2015
				if(isset($joins) && !empty($joins)) {
					
					foreach($joins as $joinType => $join) {
						
						if(!empty($join)) {
						
							if (!is_array($join)) { $sql .= "\n".$join; } //On ajoute à la requête s'il s'agit d'une chaîne
							else {
									
								if(isset($join[0])) { //Si l'on a un tableau à index numérique, on peut avoir plusieurs join à la suite et sur plusieurs tables
						
									foreach ($join as $v) {
											
										$joinDatas = $this->_get_left_right_inner_join_datas($v);
										$sql .= "\n".$joinType.' JOIN '."\n\t".$joinDatas['joinTable'].' AS '.$joinDatas['joinAlias'].' '."\n\t".'ON '.$v['pivot'].' '; //On ajoute à la requête
									}
								} else { //Sinon, on n'a qu'un seul join
						
									$joinDatas = $this->_get_left_right_inner_join_datas($join);
									$sql .= "\n".$joinType.' JOIN '."\n\t".$joinDatas['joinTable'].' AS '.$joinDatas['joinAlias'].' '."\n\t".'ON '.$join['pivot'].' '; //On ajoute à la requête
								}
							}
						}
					}
				}
					
			/*
			 * Version supprimée le 09/10/2015 suite à l'amélioration proposée par SS
			 * 		
			////////////////////////////
			//    CHAMPS LEFT JOIN    //
				if(isset($req['leftJoin']) && !empty($req['leftJoin'])) {
					
					if (!is_array($req['leftJoin'])) { $sql .= "\n".$req['leftJoin']; } //On ajoute à la requête s'il s'agit d'une chaîne 
					else {
						
						if(isset($req['leftJoin'][0])) { //Si l'on a un tableau à index numérique, on peut avoir plusieurs join à la suite et sur plusieurs tables
							
							foreach ($req['leftJoin'] as $v) {
								
								$joinDatas = $this->_get_left_right_inner_join_datas($v);
								$sql .= "\n".'LEFT JOIN '."\n\t".$joinDatas['joinTable'].' AS '.$joinDatas['joinAlias'].' '."\n\t".'ON '.$v['pivot'].' '; //On ajoute à la requête

								//On conserve l'ancienne version 22/04/2015
								//$joinModel = $this->load_model($v['model'], true);
								//$sql .= "\n".'LEFT JOIN '."\n\t".$joinModel->table.' AS '.$joinModel->alias.' '."\n\t".'ON '.$v['pivot'].' '; //On ajoute à la requête
							}					
						} else { //Sinon, on n'a qu'un seul join						
							
							$joinDatas = $this->_get_left_right_inner_join_datas($req['leftJoin']);
							$sql .= "\n".'LEFT JOIN '."\n\t".$joinDatas['joinTable'].' AS '.$joinDatas['joinAlias'].' '."\n\t".'ON '.$req['leftJoin']['pivot'].' '; //On ajoute à la requête
							
							//On conserve l'ancienne version 22/04/2015
							//$joinModel = $this->load_model($req['leftJoin']['model'], true);
							//$sql .= "\n".'LEFT JOIN '."\n\t".$joinModel->table.' AS '.$joinModel->alias.' '."\n\t".'ON '.$req['leftJoin']['pivot'].' '; //On ajoute à la requête					
						}
					}
				}
			
			/////////////////////////////
			//    CHAMPS RIGHT JOIN    //
				if(isset($req['rightJoin']) && !empty($req['rightJoin'])) {
					
					if (!is_array($req['rightJoin'])) { $sql .= "\n".$req['rightJoin']; } //On ajoute à la requête s'il s'agit d'une chaîne 
					else {
						
						if(isset($req['rightJoin'][0])) { //Si l'on a un tableau à index numérique, on peut avoir plusieurs join à la suite et sur plusieurs tables
							
							foreach ($req['rightJoin'] as $v) {
								
								$joinDatas = $this->_get_left_right_inner_join_datas($v);
								$sql .= "\n".'RIGHT JOIN '."\n\t".$joinDatas['joinTable'].' AS '.$joinDatas['joinAlias'].' '."\n\t".'ON '.$v['pivot'].' '; //On ajoute à la requête
								
								//On conserve l'ancienne version 22/04/2015
								//$joinModel = $this->load_model($v['model'], true);
								//$sql .= "\n".'RIGHT JOIN '."\n\t".$joinModel->table.' AS '.$joinModel->alias.' '."\n\t".'ON '.$v['pivot'].' '; //On ajoute à la requête
							}					
						} else { //Sinon, on n'a qu'un seul join
							
							$joinDatas = $this->_get_left_right_inner_join_datas($req['rightJoin']);
							$sql .= "\n".'RIGHT JOIN '."\n\t".$joinDatas['joinTable'].' AS '.$joinDatas['joinAlias'].' '."\n\t".'ON '.$req['rightJoin']['pivot'].' '; //On ajoute à la requête
							
							//On conserve l'ancienne version 22/04/2015
							//$joinModel = $this->load_model($req['rightJoin']['model'], true);
							//$sql .= "\n".'RIGHT JOIN '."\n\t".$joinModel->table.' AS '.$joinModel->alias.' '."\n\t".'ON '.$req['rightJoin']['pivot'].' '; //On ajoute à la requête					
						}
					}
				}
			
			/////////////////////////////
			//    CHAMPS INNER JOIN    //
				if(isset($req['innerJoin']) && !empty($req['innerJoin'])) {
					
					if (!is_array($req['innerJoin'])) { $sql .= "\n".$req['innerJoin']; } //On ajoute à la requête s'il s'agit d'une chaîne 
					else {
						
						if(isset($req['innerJoin'][0])) {//Si l'on a un tableau à index numérique, on peut avoir plusieurs "join" à la suite et sur plusieurs tables
							
							foreach ($req['innerJoin'] as $k => $v) {
		
								$joinDatas = $this->_get_left_right_inner_join_datas($v);
								$sql .= "\n".'INNER JOIN '."\n\t".$joinDatas['joinTable'].' AS '.$joinDatas['joinAlias'].' '."\n\t".'ON '.$v['pivot'].' '; //On ajoute à la requête
								
								//On conserve l'ancienne version 22/04/2015
								//$joinModel = $this->load_model($v['model'], true);
								//$sql .= "\n".'INNER JOIN '."\n\t".$joinModel->table.' AS '.$joinModel->alias.' '."\n\t".'ON '.$v['pivot'].' ';//On ajoute à la requête
							}
							
						} else { //Sinon, on n'a qu'un seul "join"
							
							$joinDatas = $this->_get_left_right_inner_join_datas($req['innerJoin']);
							$sql .= "\n".'INNER JOIN '."\n\t".$joinDatas['joinTable'].' AS '.$joinDatas['joinAlias'].' '."\n\t".'ON '.$req['innerJoin']['pivot'].' '; //On ajoute à la requête
							
							//On conserve l'ancienne version 22/04/2015
							//$joinModel = $this->load_model($req['innerJoin']['model'], true);
							//$sql .= "\n".'INNER JOIN '."\n\t".$joinModel->table.' AS '.$joinModel->alias.' '."\n\t".'ON '.$req['innerJoin']['pivot'].' ';//On ajoute à la requête
						}
					}
				}
			*/

			/////////////////////////////////////////////////////////////
			//    CONDITIONS DE RECHERCHE SUR L'IDENTIFIANT DU SITE    //
			//Si dans le shema de la table on a une colonne website_id		
				if($this->manageWebsiteId && in_array('website_id', $shema) && get_class($this) != 'UsersGroupsWebsite') {
				
					//Si on a pas de conditions de recherche particulières
					if(!isset($r['conditions'])) { $r['conditions']['website_id'] = CURRENT_WEBSITE_ID; }
					else {
						
						//Sinon on va tester si il s'agit d'un tableau ou d'une chaine de caractères
						if(is_array($r['conditions'])) { $r['conditions']['website_id'] = CURRENT_WEBSITE_ID; } 
						else { $r['conditions'] .= " AND website_id=".CURRENT_WEBSITE_ID; }
					}			
				}

			/////////////////////////////////////////////////////////
			//    CONDITIONS DE RECHERCHE SUR LE CHAMP ACTIVATE    //
			//Si dans le shema de la table on a une colonne website_id		
				if($this->manageActivateField && in_array('activate', $shema)) {
				
					//Si on a pas de conditions de recherche particulières
					if(!isset($r['conditions'])) { $r['conditions']['activate'] = 1; }
					else {
						
						//Sinon on va tester si il s'agit d'un tableau ou d'une chaine de caractères
						if(is_array($r['conditions'])) { $r['conditions']['activate'] = 1; } 
						else { $r['conditions'] .= " AND activate=1"; }
					}			
				}
			
			/////////////////////////////
			//    CHAMPS CONDITIONS    //
				if(isset($r['conditions'])) { //Si on a des conditions
					
					$conditions = '';	//Mise en variable des conditions	
					
					//On teste si conditions est un tableau
					//Sinon on est dans le cas d'une requête personnalisée
					if(!is_array($r['conditions']) && !empty($r['conditions'])) {
						
						$conditions .= $r['conditions']; //On les ajoute à la requete
					
					//Si c'est un tableau on va rajouter les valeurs
					} else {
						
						$cond = array();
						foreach($r['conditions'] as $k => $v) {		
							
							//if(!empty($v)) {
								
								//On va ensuite tester si la clé est une chaine de caractère
								//On rajoute le nom de la classe devant le nom de la colonne
								if(is_string($k)) {					
									
									if($k == "OR") {
										
										$orCond = array();
										foreach($v as $orField => $orValue) { 
											
											if(!is_int($orField)) { $orCond = $this->_get_query_conditions($orCond, $orField, $orValue); }
											else { $orCond[] = $orValue; }
										}								
										$cond[] = '('.implode(' OR ', $orCond).')';
									} 
									else { $cond = $this->_get_query_conditions($cond, $k, $v); }
									
								} 
								else { $cond[] = $v; } //Sinon on rajoute directement la condition dans le tableau
							//}
						}
						
						if(!empty($cond)) { $conditions .= implode("\n".'AND ', $cond); }
					}
					
					if(!empty($conditions)) { $sql .= "\n".'WHERE '.$conditions.' '; } //On rajoute les conditions à la requête
				}
			
			////////////////
			//    I18N    //
				if($translatedTable && $this->getTranslation) {
									
					$sql .= "\n";
					
					//Deux cas :
					// - on a déjà des conditions, le WHERE est donc déjà renseigné
					// - on a pas de conditions et on peut utiliser le WHERE
					if(isset($r['conditions'])) { $sql .= "AND `".$this->alias."I18n`.`language` = '".DEFAULT_LANGUAGE."'"."\n"; }
					else { $sql .= "WHERE `".$this->alias."I18n`.`language` = '".DEFAULT_LANGUAGE."'"."\n"; }
					
					//Dans tous les cas on rajoute le pivot
					//Au départ ce test s'effectuait dans INNER JOIN qui a été supprimé par la suite
					$sql .= "AND `".$this->alias.'`.`id` = `'.$this->alias.'I18n`.`model_id` '."\n";
					
				}
			
			//////////////////////////////////
			//    CHAMPS MORE CONDITIONS    //
				if(isset($r['moreConditions']) && !empty($r['moreConditions'])) { 
					
					if(isset($conditions) && !empty($conditions)) { $sql .= "\n".'AND '; } else { $sql .= "\n".'WHERE '; }			
					$sql .= $r['moreConditions'].' '; 
				}
			
			///////////////////////////
			//    CHAMPS GROUP BY    //
				if(isset($r['group']) || isset($r['groupBy'])) { 
									
					if(isset($r['group'])) { $groupBy = $r['group']; }
					else if(isset($r['groupBy'])) { $groupBy = $r['groupBy']; }
					else { $groupBy = ''; }
									
					//On va éclater la chaîne pour récupérer tous les champs order
					$groupBy = explode(',', $groupBy);				
					foreach($groupBy as $groupByK => $groupByV) { //Parcours de tous les champs

						//Nettoyage de la valeur
						//Suppression des espaces en début et fin de chaîne
						//Supression des espaces consécutifs dans la chaîne
						$groupByV 	= trim($groupByV);
						$groupByV 	= preg_replace('/\s{2,}/', ' ', $groupByV);
						$tableAlias = $this->alias; //Récupération de l'alias de la table
						
						//On teste si la table n'est pa traduite et que le champ courant est dans la liste des champs traduits
						if($translatedTable && $this->getTranslation && in_array($groupByV, $this->fieldsToTranslate)) { $tableAlias = $this->alias.'I18n'; }
										
						//On va tester si un alias de table est déjà en place, si ce n'est pas le cas on va le rajouter
						$groupByV = explode('.', $groupByV);									
						if(count($groupByV) == 1) 		{ $groupBy[$groupByK] = '`'.$tableAlias.'`.`'.$groupByV[0].'`'; }
						else if(count($groupByV) == 2) 	{ $groupBy[$groupByK] = '`'.$groupByV[0].'`.`'.$groupByV[1].'`'; }				
					}
					$sql .= "\n".'GROUP BY '.implode(', ', $groupBy).' '; 
					
				}
			$sql .= (isset($req['union']) && !empty($req['union']) ? ') ' : '');
			if(isset($req['union']) && !empty($req['union']) && $key != (count($union) - 1)) { $sql .= "\n".'UNION ALL '."\n"; }
			
			////////////////////////////////
			//   REMPLACEMENT DES ALIAS   //
			if(isset($req['union']) && !empty($req['union']) && !empty($tableOfAlias)) { $sql = str_replace(array_keys($tableOfAlias), $tableOfAlias, $sql); }
		}
		
		///////////////////////////
		//    CHAMPS ORDER BY    //
			if(isset($req['order']) || isset($req['orderBy'])) { 
								
				if(isset($req['order'])) { $orderBy = $req['order']; }
				else if(isset($req['orderBy'])) { $orderBy = $req['orderBy']; }
				else { $orderBy = ''; }
				
				if($orderBy == 'RAND') { $sql .= "\n".'ORDER BY RAND() '; }
				else {
					
					if(!is_array($orderBy)) {

						//On va éclater la chaîne pour récupérer tous les champs order
						$orderBy = explode(',', $orderBy);
					}
					foreach($orderBy as $orderK => $orderV) { //Parcours de tous les champs
						
						//Nettoyage de la valeur
						//Suppression des espaces en début et fin de chaîne
						//Supression des espaces consécutifs dans la chaîne
						$orderV 	= trim($orderV);
						$orderV 	= preg_replace('/\s{2,}/', ' ', $orderV);
						$orderV 	= explode(' ', $orderV); //On éclate la donnée					
						$tableAlias = $this->alias; //Récupération de l'alias de la table
						
						//On teste si la table n'est pa traduite et que le champ courant est dans la liste des champs traduits
						if($translatedTable && $this->getTranslation && in_array($orderV[0], $this->fieldsToTranslate)) { $tableAlias = $this->alias.'I18n'; } 
											
						//On va tester si un alias de table est déjà en place, si ce n'est pas le cas on va le rajouter
						//Dans le cas du order on a au préalable testé si la direction était renseignée via $orderV = explode(' ', $orderV);
						//On à dond dans $orderV[0] le champ sur lequel effectuer le tri nous devons tester si ce champ possède un alias de renseigné
						$orderV[0] = explode('.', $orderV[0]);
						
						//Si le champ ne possède pas d'alias on va le rajouter automatiquement pour éviter les ambiguités
						if(count($orderV[0]) == 1) {
							
							$field 			= $orderV[0][0];
							$direction 		= isset($orderV[1]) ? ' '.$orderV[1] : '';
							$orderBy[$orderK] = '`'.$field.'`'.$direction; 
						}
						
						//Si le champ possède un alias on va utiliser cet alias pour la mise en place des tris
						else if(count($orderV) == 2) {
	
							$alias 			= $orderV[0][0];
							$field 			= $orderV[0][1];
							$direction 		= $orderV[1];
							//$orderBy[$orderK] = '`'.$alias.'`.`'.$field.'` '.$direction; 
							$orderBy[$orderK] = '`'.$field.'` '.$direction;
						}
					}
					$sql .= "\n".'ORDER BY '.implode(', ', $orderBy).' ';
				} 
			}
		
		//////////////////////
		//   CHAMPS LIMIT   //
			if(isset($req['limit'])) { $sql .= "\n".'LIMIT '.$req['limit']; }
				
		if(isset($req['pr']) && $req['pr']) { pr($sql); }
			
		$preparedQuery = $this->db->prepare($sql);
		$preparedQuery->execute();
		
		$this->_trace_sql('function find', $preparedQuery->queryString); //Récupération de la requête
		
		$return = $preparedQuery->fetchAll($type);
		
		/////////////////////////////
		//    CAS DU BACKOFFICE    //
		//On récupère toutes les traductions
		if($translatedTable && $this->getTranslatedDatas) {
			
			$localizationModel 			= $this->load_model('Localization', true);
			$localizationModel->table 	= $this->table.'_i18n';
			
			//Parcours de tous les résultats
			foreach($return as $k => $v) {
				
				$traductions = $localizationModel->find(array('conditions' => array('model_id' => $v['id'])));
				
				//Parcours de toutes les traductions
				foreach($traductions as $traduction) {	
					
					$language 		= $traduction['language']; //Récupération de la langue					
					$keysIntersect 	= array_intersect_key($traduction, array_flip($this->fieldsToTranslate)); //Récupération de l'intersection de clés du tableau de traduction et des champs à traduire
					
					foreach($keysIntersect as $index => $value) {
						
						if(isset($return[$k][$index])) {
							
							if(!is_array($return[$k][$index])) { $return[$k][$index] = array(); }						
							$return[$k][$index][$language] = $value;
						}
					}
				}
			}
		}		
        return $return;        
    }
    
/**
 * Cette fonction permet de retourner le premier élément correspondant aux conditions de recherche
 * 
 * @param 	array $req Tableau de conditions et de paramétrages de la requête
 * @return 	array Tableau contenant les données de l'élément 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/02/2012 by FI
 * @version 0.2 - 19/10/2015 by SS Ajout de la condition limit pour récupérer une ligne de résultats
 */    
	public function findFirst($req = array()) { 
		
		if(!empty($req) && !isset($req['limit'])) { $req = am($req, array('limit' => 1)); }
		
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
 * @version 0.2 - 10/01/2014 by FI - Gestion automatique du champ à compter (Le premier de la table)
 * @version 0.3 - 17/03/2015 by FI - Rajout de $this->fieldsToTranslate = false pour ne pas gérer la traduction des tables lorsque l'on compte les données
 */	
	public function findCount($conditions = null, $moreConditions = null) {
		
		$this->fieldsToTranslate = false; //@version 0.3
		
		$field = current($this->shema);
		$res = $this->findFirst(
			array(
				'fields' => 'COUNT('.$this->alias.'.'.$field.') AS count',
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
 * @version 0.2 - 04/12/2013 by FI - Rajout de la possibilité de passer un tableau pour la variable $field
 * @version 0.3 - 22/09/2014 by FI - Rajout de la possibilité de sélectionner le séparateur
 */	
	public function findList($conditions = null, $field = 'name', $key = 'id', $separator = ' ') {
	
		$queryResult = $this->find($conditions);
		//On formate les résultats
		$result = array();
		foreach($queryResult as $k => $v) { 
			
			if(empty($key)) { $result[$k] = $v[$field]; }
			else { 
				if(is_array($field)) {
					
					$fieldTMP = array();
					foreach($field as $kField => $vField) { $fieldTMP[$kField] =  $v[$vField]; }
					$result[$v[$key]] = implode($separator, $fieldTMP);
				} else { $result[$v[$key]] = $v[$field]; } 
			}			 
		}
		
		return $result;
	}
	
/**
 * Cette fonction est chargée de supprimer un élément de la table
 * 
 * @param 	mixed $id 			Identifiant(s) à supprimer (Ce paramètre peut être un tableau ou un entier)
 * @param 	array $moreControls Permet la mise en place d'un contrôle supplémentaire lors de la suppression
 * @return  objet Objet PDO
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 16/02/2012 by FI
 * @version 0.2 - 13/04/2012 by FI - Modification de la requête de suppression pour pouvoir passer en paramètre un tableau ou un entier
 * @version 0.3 - 24/12/2013 by FI - Mise en place de la gestion multisites lors de la suppression
 * @version 0.4 - 17/01/2014 by FI - Mise en place de la variable $moreControls
 * @version 0.5 - 01/04/2014 by FI - Evolution mise en place de la gestion multisites
 * @version 0.6 - 07/04/2014 by FI - Mise en place de la suppression dans les modèles associés via $this->checkOnDelete
 * @version 0.7 - 03/11/2014 by FI - Mise en place de la suppression des données connectées via la variable $deleteConnectedDatas
 */		
	public function delete($id, $moreControls = null) {
		
		if(is_array($id)) { $idConditions = " IN (".implode(',', $id).')'; } 
		else { $idConditions = " = ".$id; }		
		$sql = "DELETE FROM `".$this->table."` WHERE `".$this->primaryKey."`".$idConditions;  //Requête de suppression de l'élément
		
		//Permet de rajouter une condition supplémentaire lors de la suppression
		if(isset($moreControls)) {
			
			if(!is_numeric($moreControls['value'])) { $moreControls['value'] = $this->db->quote($moreControls['value']); }
			$sql .= " AND `".$moreControls['field']."` = ".$moreControls['value'];
		}
		
		//CAS PARTICULIER : GESTION MULTISITE
		if($this->manageWebsiteId && in_array('website_id', $this->shema)) { $sql .= " AND `website_id` = ".CURRENT_WEBSITE_ID; }
		
		$sql .= ";\n";				
		
		//On contrôle si des modèles associés sont renseignés
		//Cela permet d'effectuer les suppressions croisées plus simplement
		if($this->checkOnDelete) {
			
			foreach($this->checkOnDelete as $model => $pivot) {
				
				$modelObject = $this->load_model($model, true);
				$sql .= "\n"."DELETE FROM `".$modelObject->table."` WHERE `".$pivot."` = ".$id.";";
			}			
		}
		
		//On teste si la suppression automatique des données connectées est activée
		if($this->deleteConnectedDatas) {
			
			//On détermine le pivot
			$className = get_class($this);
			$connectedDatasPivot = strtolower($className)."_id";

			$sql .= $this->delete_connected_datas_sql($connectedDatasPivot, $id);				
		}
		
		$queryResult = $this->query($sql);		
		if(isset($this->searches_params)) { $this->delete_search_index($id); } //Suppression de l'index dans la recherche		
		return $queryResult;
	}
	
/**
 * Cette fonction va permettre la récupération de la requête de suppression des données connectées à la donnée en cours de suppression
 * 
 * @param 	varchar $fieldName 	Champ servant de pivot pour la suppression 
 * @param 	integer $fieldValue	Valeur du champ à supprimer
 * @return	varchar 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 03/11/2014 by FI
 * @version 0.2 - 03/11/2014 by FI - Rajout de la possibilité d'échapper des tables lors de la suppression des données connectées
 */	
	public function delete_connected_datas_sql($fieldName, $fieldValue) {
				
		//Récupération des tables à échapper
		$escapeTables = array();
		if($this->deleteConnectedDatasEscapeModels) {
			
			foreach($this->deleteConnectedDatasEscapeModels as $model) {
				
				$modelObject = $this->load_model($model, true);
				$escapeTables[] = $modelObject->table;
			}
		}
		
		$sqlDelete = '';
		//On fait le parcours de l'ensemble des table de la base de données
		foreach($this->table_list_in_database() as $table) {
		
			//Pour chacune d'elles on va récupérer le shéma et vérifier si la colonne $fieldName est présente
			$tableShema = $this->shema($table); //Récupération du shéma de la table $table
			
			//On sort de la boucle les tables qui commencent par _ (généralement des tables de backup ou de test)
			//On sort de la boucle les tables à échapper
			//On check si $fieldName est présent dans le shéma
			if(substr($table, 0, 1) != '_' && !in_array($table, $escapeTables) && in_array($fieldName, $tableShema)) { 
				
				if(is_array($fieldValue)) { $idConditions = " IN (".implode(',', $fieldValue).')'; }
				else { $idConditions = " = ".$fieldValue; }
				$sqlDelete .= "DELETE FROM `".$table."` WHERE `".$fieldName."`".$idConditions.";\n"; 
			}
		}
		return $sqlDelete;
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
 * @version 0.2 - 24/12/2013 by FI - Mise en place de la gestion multisites lors de la suppression
 * @version 0.3 - 01/04/2014 by FI - Evolution mise en place de la gestion multisites
 */	
	public function deleteByName($name, $value) {
	
		$oldPrimaryKey = $this->primaryKey; 
		$this->primaryKey = $name;		
		if(!is_numeric($value)) { $value = $this->db->quote($value); } //Equivalement de mysql_real_escape_string
		$sql = "DELETE FROM ".$this->table." WHERE ".$this->primaryKey." = ".$value;
		
		//CAS PARTICULIER : GESTION MULTISITE
		if($this->manageWebsiteId && in_array('website_id', $this->shema)) { $sql .= " AND website_id = ".CURRENT_WEBSITE_ID; }
		
		$sql .= ";";
		$this->primaryKey = $oldPrimaryKey;		
		//if(isset($this->searches_params)) { $this->delete_search_index($id); } //Suppression de l'index dans la recherche
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
 * @version 1.1 - 10/01/2014 by FI - Gestion des primary key multiples
 * @version 1.2 - 27/09/2014 by FI - Test sur la variable $datas pour vérifier qu'elle ne soit pas vide (auquel cas cela ne sert à rien de lancer le processus)
 * 
 * @todo mettre en place des try catch pour vérifier que la requete c'est bien exécutée 
 */	
	public function save($datas, $forceInsert = false, $escapeUpload = true) {

		if(!empty($datas)) {
			
			$preparedInfos = $this->_prepare_save_query($datas, $forceInsert, $escapeUpload); //Récupération des données de la préparation de la requête
			$datasToSave = $this->_prepare_save_datas($datas, $preparedInfos['moreDatasToSave'], $forceInsert, $escapeUpload); //Récupération des données à sauvegarder
						
			$queryExecutionResult = $preparedInfos['preparedQuery']->execute($datasToSave); //Exécution de la requête
			
			$this->_trace_sql('function save', $preparedInfos['preparedQuery']->queryString, $datasToSave); //Récupération de la requête
			
			//Affectation de la valeur de la clé primaire à la variable de classe
			if($preparedInfos['action'] == 'insert') { $this->id = $this->db->lastInsertId();}
			else if(!is_array($this->primaryKey)) { $this->id = $datas[$this->primaryKey]; }
			else { $this->id[] = 'multiple'; }
			
			$this->queryExecutionResult = $queryExecutionResult;
			
			//if(isset($this->files_to_upload) && $proceedUpload) { $this->upload_files($datas, $this->id); } //Sauvegarde éventuelle des images
			if(isset($this->files_to_upload)) { $this->upload_files($datas, $this->id); } //Sauvegarde éventuelle des images
			if(isset($this->searches_params) && !$this->fieldsToTranslate) { $this->make_search_index($datasToSave, $this->id, $preparedInfos['action']); } //On génère le fichier d'index de recherche
		
			$this->after_save($datas, $preparedInfos['action']);
		}
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
 * @version 0.3 - 10/01/2014 by FI - Gestion des primary key multiples
 * @version 0.4 - 24/03/2015 by FI - On force l'id en tableau
 * @version 0.5 - 30/03/2015 by FI - Rajout de la fonction after_save
 */	
	public function saveAll($datas, $forceInsert = false, $escapeUpload = true) {
		
		if(!empty($datas)) {
			
			$this->id = array(); //On force le type de l'id en tableau
			$preparedInfos = $this->_prepare_save_query(current($datas), $forceInsert, $escapeUpload);
			foreach($datas as $k => $v) { 
				
				$datasToSave = $this->_prepare_save_datas($v, $preparedInfos['moreDatasToSave'], $forceInsert, $escapeUpload);
				$queryExecutionResult = $preparedInfos['preparedQuery']->execute($datasToSave);
				
				$this->_trace_sql('function saveAll', $preparedInfos['preparedQuery']->queryString, $datasToSave); //Récupération de la requête
								
				if($preparedInfos['action'] == 'insert') { $this->id[] = $this->db->lastInsertId(); }
				else if(!is_array($this->primaryKey)) { $this->id[] = $v['id']; }
				else { $this->id[] = 'multiple'; }
				
				$this->queryExecutionResult = $queryExecutionResult;
				
				if(isset($this->searches_params)) { $this->make_search_index($datasToSave, $this->id, $preparedInfos['action']); } //On génère le fichier d'index de recherche
				
				$this->after_save($v, $preparedInfos['action'], true);
			}
		}
	}
	
/**
 * Cette fonction est en charge de la validation des données avant modification de la base de données
 * $this->validAllFields Permet d'indiquer si il faut effectuer une validation obligatoire sur tous les champs présents dans les règles de validation
 *						 Il est possible dans certains cas que le formulaire posté contienne moins de champs que ceux présents dans les règles de validation
 *
 * @param 	array 	$datas 			Données à sauvegarder
 * @param 	varchar $insertErrorsTo Si renseigne l'index $insertErrorsTo sera utilisé pour l'ajout des erreurs dans le modèle
 * @return 	boolean Retourne vrai si la validation est correcte, faux sinon
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 28/12/2011 
 * @version 0.2 - 06/02/2012 - Ne validera que les champs envoyés dans le formulaire 
 * @version 0.3 - 03/04/2012 - Modification de la gestion des messages d'erreurs suite à l'ajout de gettext
 * @version 0.4 - 20/04/2012 - Mise en place des validations par callback 
 * @version 0.5 - 25/06/2013 - Changement du include_once en include pour le chargement des messages d'erreurs car dans le cas de validations multiples le fichier n'était pas chargé  
 * @version 0.6 - 09/11/2013 - Rajout d'un nouveau paramètre aux règles de validation permettant de paramétrer une règle mais de ne l'appliquer que si le champ n'est pas vide  
 * @version 0.7 - 14/11/2013 - Mise en place de la validation des champs construit avec des tableaux multidimensionnels  
 * @version 0.8 - 09/12/2013 - Rajout de l'index $insertErrorsTo pour la gestion des erreurs  
 * @version 0.9 - 10/01/2014 - Rajout de $validAllFields pour indiquer si tous les champs des règles de validation sont obligatoires
 * @version 1.0 - 20/03/2014 - Modification récupération des erreurs dans le cas ou tous les champs doivent obligatoirement être validés
 * @version 1.1 - 20/03/2014 - Suppression de la variable $validAllFields comme paramètre de la fonction pour la passer en variable de classe
 */	
	public function validates($datas, $insertErrorsTo = null) {
				
		if(isset($this->validate)) { //Si on a un tableau de validation dans la classe
			
			$errors = array(); //Tableau d'erreurs qui sera retourné
			include(CONFIGS.DS.'messages.php'); //Inclusion des éventuels messages d'erreurs

			foreach($this->validate as $k => $v) { //On va parcourir tous les champs à valider
				
				//Par défaut si le champ est présent dans les données à valider 
				//mais pas dans les données envoyées par le formulaire on génère une erreur
				//if(isset($datas[$k])) { 
				if(Set::check($datas, $k)) { 
					
					$this->datas = $datas; //On va rajouter les données à contrôler dans le model dans le cas ou nous en ayons besoin lors de l'utilisation des callback
					$this->Validation = new Validation($this);					
					
					//pr($this->datas);
					
					$isValid = false; //Par défaut on renverra toujours faux
					
					//On va tester si il y à plusieurs règles de validation
					//Si on a pas directement accès à la clée rule cela signifie qu'il y à plusieurs règles
					if(!isset($v['rule'])) {
						
						//On va donc les parcourir
						foreach($v as $kRule => $vRule) { 
							
							$errors = $this->_check_validation_rules($k, $vRule, $datas, $errors);
							
							/*if(!isset($vRule['allowEmpty'])) { $vRule['allowEmpty'] = false; } //Par défaut si l'index allowEmpty n'existe pas on le rajoute
							
							$dataToCheck = Set::classicExtract($datas, $k);
							$isValid = $this->Validation->check($dataToCheck, $vRule['rule']); //Lancement de la règle
							$allowEmpty = $vRule['allowEmpty'] && empty($dataToCheck); //Génération du booléen allowEmpty
							
							//On injecte le message en cas d'erreur
							if(!$isValid && !$allowEmpty) { 
								
								if(Set::check($Errorsmessages, $vRule['message'])) { $errors[$k] = Set::classicExtract($Errorsmessages, $vRule['message']); }
								else { $errors[$k] = $vRule['message']; }								 
							}*/	
						}	
					} else { $errors = $this->_check_validation_rules($k, $v, $datas, $errors); }
					
				} else if($this->validAllFields) { //Par défaut on n'impose pas la validation de tous les champs
					
					if(!isset($v['rule'])) { $v = current($v); } //Dans le cas ou on a plusieurs règles de validation on récupère la première
					if(isset($Errorsmessages) && Set::check($Errorsmessages, $v['message'])) { $errors[$k] = Set::classicExtract($Errorsmessages, $v['message']); }
					else { $errors[$k] = $v['message']; }
				}
			}
			
			//Si un index particulier est renseigné on l'utilise
			if(isset($insertErrorsTo) && !empty($insertErrorsTo)) { $this->errors = Set::insert($this->errors, $insertErrorsTo, $errors); } 
			else { $this->errors = $errors; }			
			
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
 * @version 0.2 - 10/02/2014 - Reprise de la fonction dans le cas ou l'upload de fichiers en front soit nécessaire
 * @version 0.3 - 10/07/2015 - Reprise de la gestion de la génération des données dans le cas if(isset($v['path']) && $v['path'])
 */	
	public function upload_files($datas, $id) {
		
		require_once(BEHAVIORS.DS.'upload.php');		
		foreach($this->files_to_upload as $k => $v) {
			
			if(isset($datas[$k])) {
				
				$handle = new Upload($datas[$k]);
				if($handle->uploaded) {
					
					if(isset($v['path']) && $v['path']) {
												
						$filePath = $v['path'];
						
						//Redéfinition du chemin d'accès qui sera sauvegardé en BDD
						$filePathBdd = str_replace(ROOT, BASE_URL, $v['path']); //On remplace le chemin d'accès depuis la racine du disque par celui depuis la racine du localhost
						$filePathBdd = str_replace(DS, '/', $filePathBdd); //On remplace les DS par des /
						$filePathBdd = str_replace('webroot/', '', $filePathBdd); //On supprime le dossier webroot qui n'est pas utile
						
					} else {
						 
						$filePath = WEBROOT.DS."upload".DS.get_class($this).DS.$id; 
						$filePathBdd = BASE_URL."/upload/".DS.get_class($this).'/'.$id; 
						
					}
					
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
						
						$sql = "
						UPDATE ".$this->table." 
						SET 
							".$k."_name = '".$fileName."', 
							".$k."_path = '".$filePathBdd."' 
							
						WHERE ".$primaryKey." = ".$id;
						$this->query($sql);
					}
					
					$handle->Clean();
				}
			}
		}
	}
	
/**
 * Cette fonction permet d'afficher le shéma d'une table
 * Uniquement une table de la BDD locale
 *
 * @param 	varchar $table Nom de la table dont on souhaite récupérer le shéma (Par défaut $this->table sera utilisé)
 * @return 	array 	Tableau contenant les colonnes de la table
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 12/03/2012 by FI
 * @version 0.2 - 07/09/2012 by FI - Test pour vérifier si la table existe dans le cas de model sans table
 * @version 0.3 - 08/05/2013 by FI - Modification de la fonction pour y intégrer la possibilité de récupérer le shéma d'une table passée en paramètre
 */
	public function shema($table = null) {		
		
		$shema = array();
		if(!isset($table)) { $table = $this->table; }
		if($this->exist_table_in_database($table) == 1) {			
		
			$cacheFolder 	= TMP.DS.'cache'.DS.'models'.DS;
			$cacheFile 		= $table;			
			$shema = Cache::exists_cache_file($cacheFolder, $cacheFile);
			
			if(!$shema) { 
			
				$shema = $this->_get_shema($table);				
				Cache::create_cache_file($cacheFolder, $cacheFile, $shema);
			}
		}		
		return $shema;
	}
	
/**
 * Cette fonction permet d'afficher le shéma d'une table
 * Cette table peut ne pas être forcément locale à la base
 *
 * @param 	varchar $table Nom de la table dont on souhaite récupérer le shéma (Par défaut $this->table sera utilisé)
 * @return 	array 	Tableau contenant les colonnes de la table
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 17/07/2014 by FI
 */	
	public function _get_shema($table = null) {
				
		$shema = array();
		if(!isset($table)) { $table = $this->table; }
		$sql = "SHOW COLUMNS FROM `".$table.'`;';			
		$result = $this->query($sql, true);
		foreach($result as $k => $v) { $shema[] = $v['Field']; }
		return $shema;
	}			
	
/**
 * Cette fonction permet de récuperer les informations liées à la table 
 */	
	public function table_status() {
		
		$sql = "SHOW TABLE STATUS LIKE '".$this->table."';";
		$result = $this->query($sql, true);
		return current($result);
	}
	
/**
 * Cette fonction permet de tester l'existence d'une table dans la base de données
 *
 * @param 	varchar $table 		Table à tester
 * @param 	boolean $forceCache Indique s'il faut ou non force le cache
 * @return 	integer Résultat de la requête
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 07/09/2012 by FI
 * @version 0.2 - 04/03/2013 by FI - Suppression de la variable database et récupération de celle-ci de la variable de classe
 */
	public function exist_table_in_database($table, $forceCache = true) {		
				
		if($forceCache) { $tablesList = $this->table_list_in_database(); }
		else { $tablesList = $this->_table_list_in_database(); }		
		return in_array($table, $tablesList);
	}		
	
/**
 * Cette fonction permet de récupérer la liste des tables de la base de données
 *
 * @return 	array	Liste des tables
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 04/03/2013 by FI
 * @version 0.2 - 29/10/2013 by FI - Rajout d'un test pour supprimer du tableau les tables des plugins désinstallés (déporté dans _table_list_in_database)
 */
	public function table_list_in_database() {		
				
		$cacheFolder 	= TMP.DS.'cache'.DS.'models'.DS;
		$cacheFile 		= "tables_list";	
		
		$tablesList = Cache::exists_cache_file($cacheFolder, $cacheFile);		
		
		if(!$tablesList) {
		
			$tablesList = $this->_table_list_in_database();			
			Cache::create_cache_file($cacheFolder, $cacheFile, $tablesList);
		}
		
		return $tablesList;
	}
	
/**
 * Cette fonction permet de récupérer la liste des tables de la base de données sans gestion du cache
 *
 * @return 	array	Liste des tables
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 26/03/2015 by FI 
 */
	public function _table_list_in_database() {		
		
		$tablesList = array();
		$sql = 'SHOW TABLES FROM `'.$this->database.'`;';
		foreach($this->query($sql, true) as $k => $v) {
			
			$value = array_values($v);
			if($value[0]{0} != '_') { $tablesList[] = $value[0]; } //Si le premier caractère n'est pas un underscore
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
	public function make_search_index($datasToSave, $id, $action) {
		
		//pr($datasToSave);
		
		/**
		 * La variable $searches_params peut être du format suivant : 
		 * escape_url est optionnel, cette données (tableau) permet d'indiquer s'il faut ou non ignorer certaines urls lors de l'enrgistrement des données.
		 * 
		 * var $searches_params = array(
		 * 		'fields' => array('name', 'short_content', 'content', 'slug', 'page_title', 'page_description', 'page_keywords'),
		 * 		'url' => array(
		 * 			'url' => 'controller/action/id::id/slug::slug',
		 * 			'params' => array('slug')
		 * 		),
		 * 		'escape_url' => array('controller/action')
		 * );
		 */
				
		$searchesParams 	= $this->searches_params; //Paramètres des champs à indexer
		$fieldsToIndex 		= $searchesParams['fields']; //Liste des champs à indexes
		$urlParams 			= $searchesParams['url']; //Paramètres de l'url		
		
		if(!isset($searchesParams['escape_url']) || (isset($searchesParams['escape_url']) && !in_array($this->controller_action, $searchesParams['escape_url']))) {
		
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
			if($datasToSave[':online'] == 1) {
				
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
		}
	}	
	
/**
 * Cette fonction permet la suppression d'un index de recherche
 *
 * @param 	integer $id 	Identifiant de l'élément à supprimer
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 26/08/2012 by FI
 * @version 0.2 - 18/09/2014 by FI - Modification gestion de l'id
 */
	public function delete_search_index($id) {	
		
		if(is_array($id)) { $idConditions = " IN (".implode(',', $id).')'; } 
		else { $idConditions = " = ".$id; }
						
		$sql = "DELETE From searches WHERE model = '".get_class($this)."' AND model_id".$idConditions;
		$this->query($sql);		
	}
	
//////////////////////////////////
//   PREPARATION DES REQUETES   //
//////////////////////////////////
	
/**
 * Cette fonction permet la génération de la requête préparée
 * 
 * @param 	array	$datas 			Données à sauvegarder
 * @param 	boolean	$forceInsert 	Indique si il faut forcer l'insert
 * @param 	boolean	$escapeUpload 	Indique si il faut ou non ne pas tenir compte des champs à uploader
 * @param 	array	$shema 			Schéma de la table
 * @param 	mixed	$primaryKey 	Clée(s) primaire(s)
 * @param 	varchar	$table 			Table de la base de données dansa laquelle effectuer la requête
 * @return	array	Tableau contenant les paramètres de la requête préparée
 * @access	protected 
 * @author	koéZionCMS
 * @version 0.1 - 24/08/2012 by FI
 * @version 0.2 - 13/11/2013 by FI - Rajout du code permettant la gestion de l'option de modification de la date de modification
 * @version 0.3 - 10/01/2014 by FI - Gestion des primary key multiples
 * @version 0.4 - 27/03/2014 by FI - Gestion du champ activate
 * @version 0.5 - 06/01/2015 by FI - Correction de la gestion de l'intersection permettant le calcul de la variable $isUpdate
 * @version 0.6 - 20/03/2015 by FI - Rajout de $shema et $primaryKey
 */	
	protected function _prepare_save_query($datas, $forceInsert, $escapeUpload, $shema = null, $primaryKey = null, $table = null) {
					
		$datasShema 		= array_keys($datas);
		$shema 				= isset($shema) ? $shema : $this->shema; //Shema de la table		
		$primaryKey 		= isset($primaryKey) ? $primaryKey : $this->primaryKey; //Récupération de la clé primaire		
		$table 				= isset($table) ? $table : $this->table; //Récupération de la clé primaire		
		$fieldsToSave 		= array(); //Tableau des champs de la table à sauvegarder
		$moreDatasToSave 	= array(); //Tableau des données supplémentaires à sauvegarder (évite de les regénérer à chaque fois)
				
		//Permet de connaitre le type de requete à effectuer
		//Dans ce cas on est sur de l'update
		if(is_array($primaryKey)) {
		
			//Calcul de l'intersection
			$intersect = array_intersect($datasShema, $primaryKey);
		
			//Tri des tableau à comparer
			sort($intersect);
			sort($primaryKey);
		
			//Comparaison
			$isUpdate = ($intersect == $primaryKey) ? true : false;
			
		} else { $isUpdate = in_array($primaryKey, $datasShema); }
					
		if($isUpdate && !$forceInsert) { $action = 'update'; } 
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
		//On ne traite le modified que si le champ dont_change_modified_date n'existe pas ou si il n'existe mais que sa valeur est fausse
		if(
			!isset($this->datas['dont_change_modified_date']) || 
			(isset($this->datas['dont_change_modified_date']) && !$this->datas['dont_change_modified_date'])
		) {
			
			if(!in_array('modified', $datasShema) && in_array('modified', $shema)) { 
				
				$datasShema[] = 'modified'; 
				$moreDatasToSave[':modified'] = date('Y-m-d H:i:s');
			}
			if(!in_array('modified_by', $datasShema) && in_array('modified_by', $shema)) { 
				
				$datasShema[] = 'modified_by';
				$moreDatasToSave[':modified_by'] = Session::read('Backoffice.User.id');
			}	
		}					
		
		if($this->manageWebsiteId && !in_array('website_id', $datasShema) && in_array('website_id', $shema)) { //get_class($this) != 'UsersGroupsWebsite') { 
			
			$datasShema[] = 'website_id';
			$moreDatasToSave[':website_id'] = CURRENT_WEBSITE_ID;
		}				
		
		if(!in_array('activate', $datasShema) && in_array('activate', $shema) && $this->manageActivateField) { 
			
			$datasShema[] = 'activate';
			$moreDatasToSave[':activate'] = 1;
		}
		
		if(in_array('slug', $shema) && !empty($datas['name']) && (!in_array('slug', $datasShema))) { $datasShema[] = 'slug'; } 
		if(in_array('page_title', $shema) && !empty($datas['name'])	&& (!in_array('page_title', $datasShema))) 	{ $datasShema[] = 'page_title'; } 
						
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
		/*if($action == 'update') { $sql = 'UPDATE '.$table.' SET '.implode(',', $fieldsToSave).' WHERE '.$primaryKey.'=:'.$primaryKey.';'; } 
		else { $sql = 'INSERT INTO '.$table.' SET '.implode(',', $fieldsToSave).';'; }*/
		if($action == 'update') {
			$sql = 'UPDATE '.$table.' SET '.implode(',', $fieldsToSave).' WHERE ';
			if(is_array($primaryKey)) {
				
				foreach($primaryKey as $k => $v) { $primaryKey[$k] = $v.'=:'.$v; }
				$sql .= implode(' AND ', $primaryKey);
			}
			else { $sql .= $primaryKey.'=:'.$primaryKey; }
			$sql .= ';';
		} 
		else { $sql = 'INSERT INTO '.$table.' SET '.implode(',', $fieldsToSave).';'; }
						
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
 * @param 	array	$shema 				Schéma de la table
 * @param 	mixed	$primaryKey 		Clée(s) primaire(s)
 * @return	array	Tableau contenant les paramètres des données à sauvegarder
 * @access	protected 
 * @author	koéZionCMS
 * @version 0.1 - 24/08/2012 by FI
 * @version 0.2 - 10/01/2014 by FI - Gestion des primary key multiples
 * @version 0.3 - 27/03/2014 by FI - Gestion du champ activate
 * @version 0.4 - 22/07/2014 by FI - Suppression des caractères HTML lors de la gestion automatique du slug et du page_title
 * @version 0.5 - 20/03/2015 by FI - Rajout de $shema et $primaryKey
 * @version 0.6 - 03/04/2015 by FI - Mise en place de la fonction _manage_additional_fields
 */		
	protected function _prepare_save_datas($datas, $moreDatasToSave, $forceInsert, $escapeUpload, $shema = null, $primaryKey = null) {
		
		$shema 		= isset($shema) ? $shema : $this->shema; //Shema de la table
		$primaryKey = isset($primaryKey) ? $primaryKey : $this->primaryKey; //Récupération de la clé primaire
		$datasShema = array_keys($datas); //Shéma des données à sauvegarder
				
		$datasToSave = array(); //Tableau utilisé lors de la préparation de la requête
		
		if(is_array($primaryKey)) {
			
			foreach($primaryKey as $v) {
				
				if(isset($datas[$v]) && !empty($datas[$v]) && !$forceInsert) { $datasToSave[":$v"] = $datas[$v]; }
			}			
		} else {		
			
			if(isset($datas[$primaryKey]) && !empty($datas[$primaryKey]) && !$forceInsert) { $datasToSave[":$primaryKey"] = $datas[$primaryKey]; }
		}
				
		//require_once(LIBS.DS.'config_magik.php');
		//$cfg = new ConfigMagik(CONFIGS_FILES.DS.'core.ini', true, false);
		//$coreConfs = $cfg->keys_values();
				
		if(in_array('password', $datasShema) && HASH_PASSWORD) { $datas['password'] = sha1($datas['password']); } //On procède à la mise à jour du champ password si il existe
		if(in_array('activate', $shema) && isset($datas['activate']) && !$datas['activate'] && in_array('online', $datasShema)) { $datas['online'] = 0; } //On procède à la mise à jour du champ online si le champ activate est présent et que celui-ci est à 0
		
		///////////////////////////////////////////
		//    GESTION DES TEXTES AUTOMATIQUES    //
		//Il faut prendre en compte une éventuelle traduction de ces champs				
		$datas = $this->_manage_additional_fields('slug', $datas, $shema, $datasShema); //Champ SLUG
		$datas = $this->_manage_additional_fields('page_title', $datas, $shema, $datasShema); //Champ PAGE_TITLE
		
		if(is_array($primaryKey)) {
			
			foreach($primaryKey as $v) {
				
				if(isset($datas[$v]) && !$forceInsert) unset($datas[$v]); //Il faut supprimer du tableau des données la clé primaire si celle ci est définie
			}			
		} else {
				
			if(isset($datas[$primaryKey]) && !$forceInsert) unset($datas[$primaryKey]); //Il faut supprimer du tableau des données la clé primaire si celle ci est définie
		}
				
		//On fait le parcours des données
		foreach($datas as $k => $v) {

			//On récupère le shéma de la table pour être sur de n'ajouter à la requête que des champs présent dans la table pour éviter les erreurs
			if(in_array($k, $shema)) {
						
				if(isset($this->files_to_upload) && isset($this->files_to_upload[$k]) && $escapeUpload) continue; //On supprime si il y en a les champs d'upload				
				
				//HACK SPECIAL POUR RAJOUTE LORS DE LA MISE EN PLACE DE LA TRADUCTION
				//Les données envoyées par les formulaires traduits sont sous la forme d'un tableau indexé par les langues
				//Dans le cas de la table principale on ne récupère que le premier élément
				if(is_array($v)) { $v = current($v); } 
				$datasToSave[":$k"] = $v;
			}
		}
		
		return array_merge($datasToSave, $moreDatasToSave);		
	}	
	
/**
 * Cette fonction permet la génération des champs à récupérer
 * 
 * Le format du champ fiels peut être de la forme suivante : 
 * 
 * 1 - Cas le plus fréquent : 
 * 
 * $fields = array(
 * 	'id',
 * 	'name',
 * 	'online'
 * );
 * 
 * 2 - Définition d'un alias pour le champ
 * 
 * $fields = array(
 * 	'tableId' => 'id',
 * 	'tableName' => 'name',
 * 	'tableOnline' => 'online'
 * );
 * 
 * 3 - Spécification de la table dans laquelle récupérer le champ (dans le cas de jointure par exemple)
 * 
 * $fields = array(
 * 	'KzTable.id',
 * 	'KzTable.name',
 * 	'KzTable.online'
 * );
 * 
 * 
 * @param 	array	$fields 		Liste des champs
 * @param 	varchar	$tableAlias 	Alias de la table
 * @param 	varchar	$moreFields 	Liste de champs complémentaire (En provenance de la fonction _get_fields cf fonction find)
 * @return	varchar	Chaine de caractères contenant les champs à récupérer
 * @access	protected 
 * @author	koéZionCMS
 * @version 0.1 - 02/01/2014 by FI
 * @version 0.2 - 17/03/2015 by FI - Rajout de $tableAlias
 * @version 0.3 - 17/03/2015 by FI - Rajout de $moreFields
 */		
	protected function _get_fields($fields, $tableAlias = null, $moreFields = null) {
		
		$sql = '';
		
		/////////////////////////////////
		//    FIELDS EST UN TABLEAU    //
		if(is_array($fields)) {
			
			if(!isset($tableAlias)) { $tableAlias = $this->alias; }
			
			foreach($fields as $k => $field) {
				
				$field = explode('.', $field);
				if(!is_int($k)) { $fieldAlias = " AS `".$k."`"; } else { $fieldAlias = ''; } //Si la clé n'est pas numérique c'est qu'elle sert d'alias (Cas n°2)
				
				if(count($field) == 1) 		{ $fields[$k] = '`'.$tableAlias.'`.`'.$field[0].'`'.$fieldAlias; } //On ne spécifie pas la table (Cas n°1)
				else if(count($field) == 2) { $fields[$k] = '`'.$field[0].'`.`'.$field[1].'`'.$fieldAlias; } //Cas n°3
			}
			
			if(isset($moreFields)) { $fields = am($fields, array($moreFields)); }
			
			$sql .= "\n\t".implode(", \n\t", $fields); 
		} 		
		
		///////////////////////////////////////////////
		//    FIELDS EST UNE CHAINE DE CARACTERES    //
		else { $sql .= "\n".$fields; } 
		
		return $sql;
	}
    
/**
 * Cette fonction permet l'insertion des conditions de recherche dans le tableau prévu à cet effet
 * 
 * @param array 	$cond 	Tableau de conditions
 * @param varchar 	$field 	Champ sur lequel effectuer la recherche
 * @param mixed 	$value 	Valeur à rechercher
 * @return array
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 02/04/2014 by FI
 * @version 0.2 - 16/12/2014 by FI - Mise en place de la gestion de l'opérateur
 */   
    protected function _get_query_conditions($cond, $field, $value) {
    	
    	//On va échaper les caractères spéciaux
    	//Equivalement de mysql_real_escape_string --> $v = '"'.mysql_escape_string($v).'"';
    	if(!is_numeric($value) && !is_array($value)) { $value = $this->db->quote($value); }
    	
    	//On recherche si un point est présent dans la valeur du champ ce qui voudrait dire que l'alias est déjà renseigné
    	//auquel cas on ne le rajoute pas
    	//$k = $this->alias.".".$k;
    	$fieldExplode = explode('.', $field);
    	if(count($fieldExplode) == 1) { $field = $this->alias.".".$fieldExplode[0]; }
    	
    	if(is_array($value)) { 
    		
    		//Gestion d'un opérateur particulier (par exemple != ou NOT IN)
    		if(isset($value['operator'])) {
    			
    			$fieldOperator = $value['operator'];
    			$fieldValue = $value['value'];
    			if(is_array($fieldValue)) { $fieldValue = "(".implode(',', $fieldValue).")"; } //Cas d'un tableau   	
    			else if(!is_numeric($fieldValue)) { $fieldValue = $this->db->quote($fieldValue); }		
    			$cond[] = $field.' '.$fieldOperator.' '.$fieldValue;    			
    			
    		} else { $cond[] = $field." IN (".implode(',', $value).")"; } 
    	}
    	else { $cond[] = $field." = ".$value; }
    	
    	return $cond;    	
    }
	
/**
 * Cette fonction est en charge de contrôler la validité d'un champ
 *
 * @param 	varchar	$validationField 	Champ à contrôler
 * @param 	varchar	$validationRule 	Règle de validation
 * @param 	array 	$datas 				Données envoyées par le formulaire
 * @param 	array 	$errors 			Erreurs
 * @return 	array 	Tableau d'erreurs
 * @access	protected
 * @author	koéZionCMS
 * @version 0.1 - 20/03/2015
 * @version 0.2 - 05/05/2015 - Correction de la gestion de l'affectation de $validationRule['allowEmpty']  
 */		
	protected function _check_validation_rules($validationField, $validationRule, $datas, $errors) {
		
		if(!isset($validationRule['allowEmpty'])) { $validationRule['allowEmpty'] = false; } //Par défaut si l'index allowEmpty n'existe pas on le rajoute

		$dataToCheck = Set::classicExtract($datas, $validationField); //Récupération de la donnée à contrôler
		
		//Si la donnée à contrôler est un tableau 
		if(is_array($dataToCheck)) {
			
			//On va parcourir l'ensemble du tableau pour récupérer les valeurs
			foreach($dataToCheck as $dataFieldToCheck => $dataValueToCheck) { 
								
				$errors = $this->_is_valid_value($dataValueToCheck, $validationRule, $validationField.'.'.$dataFieldToCheck, $errors); //On lance le contrôle
			}
						
		} else { 
			
			$errors = $this->_is_valid_value($dataToCheck, $validationRule, $validationField, $errors); //On lance le contrôle 
		}
		return $errors;
	}
	
/**
 * Cette fonction est en charge de contrôler la valeur d'un champ est valide
 *
 * @param 	varchar	$valueToCheck 	Valeur du champ à contrôler
 * @param 	varchar	$validationRule Règle de validation
 * @param 	array 	$insertErrorIn 	Indique le chemin pour l'insertion de l'erreur
 * @param 	array 	$errors 		Erreurs
 * @return 	array 	Tableau d'erreurs
 * @access	protected
 * @author	koéZionCMS
 * @version 0.1 - 20/03/2015
 */		
	protected function _is_valid_value($valueToCheck, $validationRule, $insertErrorIn, $errors) {
		
		include(CONFIGS.DS.'messages.php'); //Inclusion des éventuels messages d'erreurs
		
		$isValid 	= $this->Validation->check($valueToCheck, $validationRule['rule']); //Lancement de la règle
		$allowEmpty = $validationRule['allowEmpty'] && empty($valueToCheck); //Génération du booléen allowEmpty
		
		//On injecte le message en cas d'erreur
		if(!$isValid && !$allowEmpty) {
		
			if(isset($Errorsmessages) && Set::check($Errorsmessages, $validationRule['message'])) { $errors[$insertErrorIn] = Set::classicExtract($Errorsmessages, $validationRule['message']); }
			else { $errors[$insertErrorIn] = $validationRule['message']; }
		}
		
		return $errors;
	}
	
/**
 * Cette fonction permet de gérer la génération des champs 
 *
 * @param 	varchar	$field 	Champ à traiter
 * @param 	array	$datas 	Tableau de données
 * @return 	array 	Tableau de données
 * @access	protected
 * @author	koéZionCMS
 * @version 0.1 - 03/04/2015
 * @version 0.2 - 24/04/2015 - Rajout du test de l'existence du champ $datas[$field] dans le cas 2
 */		
	protected function _manage_additional_fields($field, $datas, $shema, $datasShema) {
			
		//CAS 1 : 
		//La table n'est pas traduite et $field n'est pas rempli ou non présent dans le formulaire mais présent dans la table
		if(!$this->fieldsToTranslate && in_array($field, $shema) && !empty($datas['name']) && (!in_array($field, $datasShema) || empty($datas[$field]))) { 
			
			$datas[$field] = $this->_format_additional_fields($field, $datas['name']);
		}
		
		//CAS 2 : 
		//La table est traduite et les champs name et $field sont présents dans les champs traduits
		else if($this->fieldsToTranslate && in_array('name', $this->fieldsToTranslate) && in_array($field, $this->fieldsToTranslate)) {
			
			if(isset($datas[$field])) {
				
				//On va parcourir les données de $field et vérifier si elles sont vides ou non
				foreach($datas[$field] as $slugLanguage => $slugValue) {
					
					//On ne gère automatiquement la valeur que si la valeur courante de $field est vide
					if(empty($slugValue)) { $datas[$field][$slugLanguage] = $this->_format_additional_fields($field, $datas['name'][$slugLanguage]); }
				}
			}
		}
		
		//CAS 3 : 
		//La table est traduite le champ $field est dans le shéma général de la table et seul le champ name est présent dans les champs traduits
		else if($this->fieldsToTranslate && in_array('name', $this->fieldsToTranslate) && !in_array($field, $this->fieldsToTranslate) && in_array($field, $datasShema)) {
			
			//On récupère la valeur du champ name pour la langue par défaut
			$datas[$field] = $this->_format_additional_fields($field, $datas['name'][DEFAULT_LANGUAGE]);
		}
		
		//CAS 4 : 
		//La table est traduite et seul $field est présent dans les champs traduits
		else if($this->fieldsToTranslate && !in_array('name', $this->fieldsToTranslate) && in_array($field, $this->fieldsToTranslate)) {
			
			//On va parcourir les données de $field et vérifier si elles sont vides ou non
			foreach($datas[$field] as $slugLanguage => $slugValue) {
			
				//On ne gère automatiquement la valeur que si la valeur courante de $field est vide
				if(empty($slugValue)) { $datas[$field][$slugLanguage] = $this->_format_additional_fields($field, $datas['name']); }
			}
		}
		
		return $datas;
	}
	
/**
 * Cette fonction est en charge de contrôler la valeur d'un champ est valide
 *
 * @param 	varchar	$field 	Champ à traiter
 * @param 	varchar	$value 	Valeur à traiter
 * @return 	varchar	Valeur formatée
 * @access	protected
 * @author	koéZionCMS
 * @version 0.1 - 03/04/2015
 */		
	protected function _format_additional_fields($field, $value) {
		
		if($field == 'slug') { return strtolower(Inflector::slug(strip_tags($value), '-')); }
		else { return strip_tags($value); }
	}	
    
/**
 * Cette fonction est chargé de retourner les informations nécessaires à la mise en place des conditions LEFT, RIGHT et INNER JOIN dans la requête
 * 
 * @param 	array $datas Tableau contenant les informations sur le type de JOIN à mettre en palce
 * @return 	array Tableau contenant les données du JOIN
 * @access 	protected
 * @author 	koéZionCMS
 * @version 0.1 - 22/04/2015 by FI 
 */    
    protected function _get_left_right_inner_join_datas($datas) {
    	
    	$return = array();
    	
    	//On teste si l'index model est passé
    	//Si ce n'est pas le cas on va trouver directement dans le tableau les informations sur la table et l'alias à utiliser
    	if(!isset($datas['model'])) {
    		
    		$return['joinTable'] = $datas['table'];
    		$return['joinAlias'] = $datas['alias'];
    	}
    	
    	//Si l'index model est présent on le charge pour récupérer le nom de la table ainsi que le nom de l'alias
    	else {
    		
    		$joinModel 				= $this->load_model($datas['model'], true);
    		$return['joinTable'] 	= $joinModel->table;
    		$return['joinAlias'] 	= $joinModel->alias;
    	}
    	
    	return $return;
    }
	
/**
 * 
 * @param unknown_type $function
 * @param unknown_type $query
 * @param unknown_type $datasToSave
 */	
	protected function _trace_sql($function, $query, $datasToSave = null) {
			
		//require_once(LIBS.DS.'config_magik.php');
		//$cfg = new ConfigMagik(CONFIGS_FILES.DS.'core.ini', true, false);
		//$coreConfs = $cfg->keys_values();
		
		if(defined('LOG_SQL') && LOG_SQL) {
			
			$date = date('Y-m-d');
			
			$debug = debug_backtrace();
			
			$traceSql = 
				"================================================================================"."\n".
				"[DATE] : \t\t\t".date('Y-m-d H:i:s').
				"\n".
				"[CLASS] : \t\t\t".get_class($this).
				"\n".
				"[REFERER] : \t\t".$this->refererUrl.
				"\n".
				"[DEBUG BACKTRACE] : "."\n";
			
				foreach($debug as $k => $v) { 
					
					if(isset($v['file']) && !empty($v['file']) && isset($v['line']) && !empty($v['line'])) {
															
						$traceSql .= "\t\t\t\t\t".'[FILE] : '.$v['file'].' --> [LINE] : '.$v['line']."\n";
					} 
				}
			
			$traceSql .=
				"[FUNCTION] : \t\t".$function.
				"\n".
				"[QUERY] : "."\n".$query.
				"\n";
			
			if(isset($datasToSave)) {
				
				$traceSql .=
				"[DATAS] : \n".var_export($datasToSave, true).
				"\n";
			}
			
			FileAndDir::put(TMP.DS.'logs'.DS.'models'.DS.$date.'.log', $traceSql, FILE_APPEND);
		}
	}
	
//////////////////////////////////////////////////////	
//				CALLBACK DE VALIDATION				//	
//////////////////////////////////////////////////////
	
/**
 * Cette fonction permet de contrôler qu'un email n'est pas déjà utilisé
 * 
 * @var 	varchar $val Valeur du champ à tester
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 21/01/2016 by FI
 */	
	public function only_one_email($val) {
		
		$modelDatas = $this->datas; //Données postées
		
		$conditions = array('email' => $val);
		if(isset($modelDatas['id'])) {
			
			$conditions['id'] = array(
				'operator' => '!=',
				'value' => $modelDatas['id']
			);			
		}
		
		return !$this->findCount($conditions);
	}
	
/**
 * Cette fonction permet de contrôler que les mots de passe sont identiques
 * 
 * @var 	varchar $val Valeur du champ à tester
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 22/02/2016 by FI
 */	
	public function check_password($val) {
		
		$modelDatas = $this->datas; //Données postées
		
		if(isset($modelDatas['password_confirm'])) { return $modelDatas['password_confirm'] == $val; }
		else { return true; }		
	}
}