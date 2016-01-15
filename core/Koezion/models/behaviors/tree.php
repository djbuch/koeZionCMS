<?php
/**
 * Cette classe permet la gestion d'une représentation intervallaire
 * 
 * PHP versions 4 and 5
 *
 * KoéZionCMS : PHP OPENSOURCE CMS (http://www.koezion-cms.com)
 * Copyright KoéZionCMS
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	KoéZionCMS
 * @link        http://www.koezion-cms.com  
 * @link 		http://www.siteduzero.com/tuto-3-20017-1-la-representation-intervallaire.html Tutoriel sur la Représentation Intervallaire d'arbre en SQL
 * @link 		http://sqlpro.developpez.com/cours/arborescence/ Cours sur la gestion d'arborescence en SQL (par Frederic Brouard)
 * @todo 		Déplacement haut / bas de noeuds, au sein d'un même parent
 */
class Tree extends Model {

/**
 * Tableau contenant l'arbre à afficher
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	var $displayTree = array();

/**
 * Tableau contenant la correspondance entre les anciens id et les nouveaux lors du déplacement d'un noeud
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 20/02/2012 by FI
 */
	var $oldNewId = array();	
	

/**
 * Fonction chargé de l'ajout ou de l'édition d'un élément
 *
 * @param 	object $datas Données à sauvegarder ou à éditer
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 28/12/2011 by FI
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */	
	function save($datas, $forceInsert = false, $escapeUpload = true) {
		
		//Si la clé id est présente dans les données à sauvegarder il s'agit d'un update
		if(isset($datas['id'])) { $this->edit($datas); } 
		//Sinon il s'agit d'un ajout
		else { $this->add($datas); }
	}
	
/**
 * Fonction chargée de l'ajout d'une nouvelle feuille dans l'arbre
 *
 * @param 	array 	$datas 			Données à sauvegarder
 * @param 	boolean $forceInsert 	Booléan permettant même si le champ id est présent dans le tableau de forcer l'insert
 * @return 	mixed Un booléen si faux, un entier si vrai : la valeur de l'élément créé si vrai
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 27/12/2011 by FI
 * @version 0.2 - 13/04/2012 by FI - Appel de la fonction parente pour la sauvegarde
 * @version 0.3 - 18/05/2012 by FI - Mise en place d'un booléen permettant de forcer l'insert même si le champ id est présent (utilisé pour la modification des catégories)
 */    
    function add($datas, $forceInsert = false) {
    	
    	if(!is_array($datas)) { return false; } //Si les données à sauvegarder ne sont pas sous forme de tableau on retourne faux
  	
    	if(!isset($datas['parent_id'])) { $datas['parent_id'] = 0; } //Si la valeur du parent_id n'est pas définie on va lui attribuer 0 par défaut 	
    	$parent_id = $datas['parent_id']; //On stocke cette valeur dans une variable pour plus de facilité
    	
    	//   HACK SPECIAL POUR LE DEPLACEMENT D'UN NOEUD   //
    	if(isset($this->oldNewId[$parent_id])) { $parent_id = $this->oldNewId[$parent_id]; }
    	
        $tree = $this->getTree(); //Récupération de l'arbre
        
        //Si le parent existe dans l'arbre on va sélectionner ses bornes gauche et droite
        if($parent_id != 0 && isset($tree[$parent_id])) {
        	
        	$parent_data = array(
				'rgt' => $tree[$parent_id]['rgt'],
        		'level' => $tree[$parent_id]['level']
        	);
        	
		//Sinon on va sélectionner la borne la plus à droite et rejouter 1 pour l'insérer        	
        } else {

        	$sql = 'SELECT (COALESCE(MAX(rgt), 0) + 1) as rgt, -1 as level FROM '.$this->table;
        	$parent_data = current($this->query($sql, true));
        }
        
        //Décalage des bornes droite
        $sql = 'UPDATE '.$this->table.' SET rgt = rgt + 2 WHERE rgt >= '.$parent_data['rgt'];        
        $this->query($sql);
        
        //Décalage des bornes gauche
        $sql = 'UPDATE '.$this->table.' SET lft = lft + 2 WHERE lft >= '.$parent_data['rgt'];
        $this->query($sql);

        //On ajoute les données manquantes aux données à sauvegarder
        $datas['lft'] = $parent_data['rgt'];
		$datas['rgt'] = $parent_data['rgt'] + 1;
		$datas['level'] = $parent_data['level'] + 1;
        
		parent::save($datas, $forceInsert); //Sauvegarde des données en faisant appel à la fonction parente
    }
    
/**
 * Fonction chargée de l'édition d'une feuille de l'arbre
 *
 * @param 	array $datas Données à mettre à jour
 * @return  mixed Un booléen si faux, un entier si vrai : la valeur de l'élément créé si vrai
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 28/12/2011 by FI
 * @todo 	28/12/2011 - A voir si au lieu de retourner faux lors du test sur l'id et la présence du noeud dans l'arbre on ne fait pas appel à la fonction add
 */
	function edit($datas) {
		
		if(!is_array($datas)) { return false; } //Si les données à sauvegarder ne sont pas sous forme de tableau on retourne faux
    	    	
		$key = $this->primaryKey; //Récupération de la clé primaire
        $id = $datas['id']; //Récupération de la valeur de l'identifiant
        $fields = array(); //Tableau qui va contenir la liste des champs à sauvegarder

        $tree = $this->getTree(); //Récupération de l'arbre

		if(!$id || !isset($tree[$id])) { return false; } //Si l'identifiant n'existe par ou que le noeud n'est pas dans l'arbre on retourne faux
        
        $current = $tree[$id]; //Récupération du noeud courant
        
        parent::save($datas); //Sauvegarde des données en faisant appel à la fonction parente      
        
        //Si le champ parent_id est présent dans les données à mettre à jour
        //ET que la valeur courante du champ parent_id et différente de celle postée
        if(isset($datas['parent_id']) && $current['parent_id'] != $datas['parent_id']) {

        	//On va extraire l'arbre, le supprimer puis l'insérer à la bonne place
            //On récupère également le noeud parent          
            $curTree = $this->getChildren($id, true, false);
            
            //Pour chacun des éléments de l'arbre on va supprimer les valeurs lft, rgt et level car elles vont être recalculée lors de l'ajout
            foreach($curTree as $key => $node) { unset($curTree[$key]['lft'], $curTree[$key]['rgt'], $curTree[$key]['level']); }
                        
            $curTree[0]['parent_id'] = $datas['parent_id']; //On affecte au noeud parent la nouvelle valeur du champ parent
            $this->delete($id); //On supprime le noeud
                        
            //On va parcourir le tableau d'éléments du noeud courant
            foreach($curTree as $key => $node) { $this->add($node, true); } //Ajouter le noeud dans l'arbre
        }
    }    
    
/**
 * Fonction chargée de la suppression d'un élément de l'arbre
 *
 * @param 	integer $id Identifiant du noeud à supprimer
 * @access	public
 * @author	koéZionCMS
 * @return 	boolean Vrai si la suppression s'est correctement déroulée, faux sinon
 * @version 0.1 - 28/12/2011 by FI
 * @version 0.2 - 03/10/2014 by FI - Correction erreur surcharge de la fonction, rajout de tous les paramètres
 */
	function delete($id, $moreControls = null) {
    	
        $id = (int)$id; //On fait un cast sur l'id
        $tree = $this->getTree(); //Récupération de l'arbre        
        if(!isset($tree[$id])) { return false; } //Si l'identifiant passé en paramètre ne fait pas parti de l'arbre
        
        //On récupère la valeur lft et rgt du noeud courant
        $curLft = $tree[$id]['lft'];
        $curRgt = $tree[$id]['rgt'];
        
        //Sélection des identifiants des catégories à supprimer        
        //On formate le tout sous forme de tableau puis on fait appel à la fonction parente pour la suppression
        $sql = 'SELECT id FROM '.$this->table.' WHERE lft >= '.$curLft.' AND rgt <= '.$curRgt;
        $idToDeleteTMP = $this->query($sql, true);
        $idToDelete = array();
        foreach($idToDeleteTMP as $v) { $idToDelete[] = $v['id']; }
        
        parent::delete($idToDelete);
                
        $diff = $tree[$id]['rgt'] - $tree[$id]['lft'] + 1; //On calcule la différence des deux bornes supprimées
        
        //Rebouchage des trous
        $sql = 'UPDATE '.$this->table.' SET lft = lft - :diff WHERE lft >= :lft'; //Préparation de la requête
        $d[":lft"] = $curLft;
        $d[":diff"] = $diff;
        $pre = $this->db->prepare($sql);
		$pre->execute($d);
		$d = array();
		$pre = null;
                
        $sql = 'UPDATE '.$this->table.' SET rgt = rgt - :diff WHERE rgt >= :rgt';
        $d[":rgt"] = $curRgt;
        $d[":diff"] = $diff;
        $pre = $this->db->prepare($sql);
		$pre->execute($d);
		$d = array();
		$pre = null;
		        
        return true;
    }
    
/**
 * Fonction chargée de monter d'un niveau un noeud de l'arbre
 *
 * @param 	integer $id Identifiant du noeud à traiter
 * @access	public
 * @author	koéZionCMS
 * @return 	boolean Vrai si le noeud à été déplacé, faux sinon
 * @version 0.1 - 21/02/2012 by FI
 */    
    
    function move2prev($id) {
    	    	
    	$currentNode = $this->getTree(array('conditions' => array('id' => intval($id)))); //Récupération du noeud concerné
    	
    	//Si le noeud est vide cela veut dire qu'il n'existe pas de catégorie
    	if(empty($currentNode)) {
    	
    		Session::setFlash("Cette catégorie n'existe pas", 'error'); //Message d'erreur
    		return false;
    	}
    	
    	//////////////////////////////////////////////////////
    	//   RECUPERATION DES INFORMATIONS DU NOEUD VOULU   //
    	$currentNode = current($currentNode);
    	$currentLft = $currentNode['lft']; //Récupération de la borne gauche
    	$currentRgt = $currentNode['rgt']; //Récupération de la borne droite
    	$currentLevel = $currentNode['level']; //Récupération du level
    	$currentDelta = $currentRgt - $currentLft + 1; //Récupération du delta
    	//////////////////////////////////////////////////////
    	
    	$prevRgt = $currentLft - 1; //On récupère la valeur de la borne droite supposée de l'élément qui précède
    	    	
    	//Si cette borne est positive le noeud existe
    	if($prevRgt > 0) {
    	
    		//Récupération de l'élément frère qui précède
    		$prevNode = current($this->getTree(array('conditions' => array('rgt' => $prevRgt, 'level' => $currentLevel)))); //Récupération du noeud concerné    		
    	
    		//Si le noeud n'est pas vide
    		if(!empty($prevNode)) {
    	
    			////////////////////////////////////////////////////////
    			//   RECUPERATION DES BORNES DE L'ELEMENT PRECEDENT   //
    			$prevLft = $prevNode['lft'];
    			$prevRgt = $prevNode['rgt'];
    			////////////////////////////////////////////////////////
    			
    			/////////////////////////////////////////////////
    			//   RECUPERATION DE LA BORNE DROITE MAXIMUM   //
    			$sql = 'SELECT MAX(rgt) AS max FROM '.$this->table;
    			$maxRgtBorne = current($this->query($sql, true));
    			$maxRgtBorne = $maxRgtBorne['max'];
    			/////////////////////////////////////////////////
    			
    			$deltaEndTree = $maxRgtBorne - $currentLft + 1; //On va calculer la valeur du décallage à appliquer pour placer le noeud à la fin de l'arbre
    			
    			//////////////////////////////////////////////////////
    			//						ETAPE 1						//
    			//Déplacement de l'élément et de sa descendance en fin de liste. Le niveau sera réactualisé ultérieurement
    			$sql = '
    				UPDATE '.$this->table.' 
    				SET 
    					lft = lft + '.$deltaEndTree.', 
    					rgt = rgt + '.$deltaEndTree.', 
    					level = level - '.$currentLevel.' 
    				WHERE lft >= '.$currentLft.' 
    				AND rgt <= '.$currentRgt.';';    			
    			$this->query($sql);
    			//////////////////////////////////////////////////////

    			//////////////////////////////////////////////////////
    			//						ETAPE 2						//
    			//Mise à jour des valeurs des bornes droites de l'arbre
    			$sql = '
	    			UPDATE '.$this->table.'
	    			SET rgt = rgt - '.$currentDelta.'
	    			WHERE rgt > '.$currentRgt.'
    				AND lft <= '.$maxRgtBorne.';';
    			$this->query($sql);
    			//////////////////////////////////////////////////////
    			
    			//////////////////////////////////////////////////////
    			//						ETAPE 3						//
    			//Mise à jour des valeurs des bornes gauches de l'arbre
    			$sql = '
	    			UPDATE '.$this->table.'
	    			SET lft = lft - '.$currentDelta.'
	    			WHERE lft > '.$currentRgt.'
	    			AND lft < '.$maxRgtBorne.';';
    			$this->query($sql);
    			//////////////////////////////////////////////////////
    			
    			//////////////////////////////////////////////////////
    			//						ETAPE 4						//
    			//Mise à jour des valeurs des bornes droites de l'élément qui précède
    			$sql = '
	    			UPDATE '.$this->table.'
	    			SET rgt = rgt + '.$currentDelta.'
	    			WHERE rgt > '.$prevLft.'
	    			AND rgt <= '.$maxRgtBorne.';';    			
    			$this->query($sql);
    			//////////////////////////////////////////////////////
    			
    			//////////////////////////////////////////////////////
    			//						ETAPE 5						//
    			//Mise à jour des valeurs des bornes gauches de l'élément qui précède
    			$sql = '
	    			UPDATE '.$this->table.'
	    			SET lft = lft + '.$currentDelta.'
	    			WHERE lft >= '.$prevLft.'
	    			AND lft < '.$maxRgtBorne.';';
    			$this->query($sql);
    			//////////////////////////////////////////////////////
    			
    			//////////////////////////////////////////////////////
    			//						ETAPE 6						//
    			//On recadre l'élément à déplacer
    			//Le décallage global final a effectuer sera : 
    			//la borne droite max moins l'ancienne valeur de la borne gauche de l'élément précédent auquel on rajoute 1
    			$deltaFinal = $maxRgtBorne - $prevLft + 1; 
    			$sql = '
	    			UPDATE '.$this->table.'
	    			SET 
	    				lft = lft - '.$deltaFinal.',
	    				rgt = rgt - '.$deltaFinal.',
	    				level = level + '.$currentLevel.'
	    			WHERE lft > '.$maxRgtBorne.';';
    			$this->query($sql);    			
    			//////////////////////////////////////////////////////
    			
    			Session::setFlash("La catégorie à bien été déplacée"); //Message d'erreur
    			return true; //On retourne vrai
    			
    			//Sinon cela veut dire que l'on est sur le noeud le plus haut
    			//On retourne donc faux avec un message d'erreur
    		} else {
    	
    			Session::setFlash("Impossible de déplacer cette catégorie, car il n'y a pas de catégorie supérieure", 'error'); //Message d'erreur
    			return false; //On retourne faux
    		}
    	
    		//Sinon cela veut dire que l'on se trouve sur le premier noeud de l'arbre et on ne peut pas aller plus haut
    		//On retourne donc faux avec un message d'erreur
    	} else {
    	
    		Session::setFlash("Impossible de déplacer cette catégorie, car il s'agit de la première", 'error'); //Message d'erreur
    		return false; //On retourne faux
    	}
    }
    
/**
 * Fonction chargée de descendre d'un niveau un noeud de l'arbre
 * La subtilité de cette fonction réside dans le fait que l'on va utiliser move2prev avec comme id celui de l'élément qui suit celui passé en paramètre
 *
 * @param 	integer $id Identifiant du noeud à traiter
 * @access	public
 * @author	koéZionCMS
 * @return 	boolean Vrai si le noeud à été déplacé, faux sinon
 * @version 0.1 - 20/02/2012 by FI
 */
    function move2next($id) {
    
    	$node = $this->getTree(array('conditions' => array('id' => intval($id)))); //Récupération du noeud concerné
    
    	//Si le noeud est vide cela veut dire qu'il n'existe pas de catégorie
    	if(empty($node)) {
    
    		Session::setFlash("Cette catégorie n'existe pas", 'error'); //Message d'erreur
    		return false;
    	}
    
    	//////////////////////////////////////////////////////
    	//   RECUPERATION DES INFORMATIONS DU NOEUD VOULU   //
    	$node = current($node);
    	$rgt = $node['rgt']; //Récupération de la borne droite
    	//////////////////////////////////////////////////////
    
    	$lgtNext = $rgt + 1; //On récupère la valeur de la borne gauche supposée de l'élément qui suit
    
    	$sql = 'SELECT MAX(rgt) AS max FROM '.$this->table; //On va récupérer la borne droite maximum (dernier élément de l'arbre)
    	$result = current($this->query($sql, true)); //Récupération des résultats
    	$maxRgt = $result['max']; //Mise en variable du max
    
    	//Si la valeur de la borne gauche supposée de l'élément suivant est inférieure à la borne maximum de l'arbre
    	if($lgtNext < $maxRgt) {
    
    		//On va maintenant sélectionner l'élément qui suit l'élément courant pour le faire monter d'un niveau
    		//Ce qui aura pour effet de faire descendre l'élément courant
    		$sql = 'SELECT id FROM '.$this->table.' WHERE lft = '.$lgtNext; //Sélection de l'élément suivant
    		$result = current($this->query($sql, true)); //Récupération des résultats
    		$idNext = $result['id']; 
    		$this->move2prev($idNext);
    		
    		//Si ce n'est pas le cas on va générer une erreur
    	} else {
    
    		Session::setFlash("Impossible de déplacer cette catégorie, car il s'agit de la dernière", 'error'); //Message d'erreur
    		return false; //On retourne faux
    	}
    }
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//										FONCTIONS DE RECUPERATION										//
//////////////////////////////////////////////////////////////////////////////////////////////////////////	
    
/**
 * Cette fonction permet de récupérer les données sous forme de tableau
 *
 * @param 	array $req Tableau des paramètres de recherche
 * @return 	array Liste de tous les noeuds de l'arbre
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 27/12/2011 by FI
 * @version 0.2 - 13/04/2012 by FI - Modification de la structure des paramètres
 */    
    function getTree($req = array()) {    	
    	
    	$defaultReq = array('order' => 'lft ASC');    	
    	$req = array_merge($defaultReq, $req); //Génération du tableau utilisé pour les conditions de recherche
    	
    	$tree = $this->find($req);
    	
        $return = array(); //Tableau de retour 
		foreach ($tree as &$node) { $return[$node['id']] = $node; } //Mise en forme du tableau        
        return $return; //On le retourne
    }
    
/**
 * Cette fonction permet de récupérer les données sous forme de tableau pouvant être utilisé pour une liste déroulante
 *
 * @param 	boolean $withRacine 	Indique si il faut ou non renvoyer le noeud racine
 * @param 	varchar $req	 		Paramètres de recherche
 * @access	public
 * @author	koéZionCMS
 * @return 	array Liste de tous les noeuds de l'arbre
 * @version 0.1 - 27/12/2011 by FI
 * @version 0.2 - 23/02/2012 by FI - Rajout du passage de conditions pour la récupération de l'arbre
 * @version 0.3 - 16/05/2012 by FI - Modification de la récupération des catégories suite à la mise en place de la gestion des sites - Récupération en premier de la catégorie parente du site et suppression de celle-ci du résultat
 */    
    function getTreeList($withRacine = true, $req = array()) {
    	
       	$arbre = $this->getTree($req); //Récupération de l'arbre       	
       	$racine = each($arbre); //Récupération de la racine de l'arbre
       	if($racine['value']['type'] == 3) { unset($arbre[$racine['key']]); } //On supprime maintenant l'élément correspondant à la racine du site
       	
       	if($withRacine) { $retour = array($racine['key'] => 'Racine'); }
       	else { $retour = array(); }
       	
       	foreach($arbre as $v) { $retour[$v['id']] = str_repeat('__', $v['level']).' '.$v['name']; }       	
       	return $retour;
    }  

/**
 * Cette fonction permet de récupérer l'arbre sous forme de parents/enfants
 *
 * @param 	array 	$req 		Liste des paramètres à prendre en compte pour la recherche
 * @param 	integer $parentId 	Identifiant de l'élément parent
 * @return 	array Arbre
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 01/01/2012 by FI
 * @version 0.2 - 23/02/2012 by FI - Modification de la structure de la fonction pour gérer d'un coté l'identifiant du parent et de l'autre les conditions supplémentaires
 * @version 0.2 - 13/04/2012 by FI - Modification de l'ordre et de la structure des paramètres
 */    
	function getTreeRecursive($req = array(), $parentId = null) {

    	$this->_recursive($req, $parentId);
    	return $this->displayTree;    	
    }  
	
/**
 * Cette fonction permet de récupérer les enfants de l'élément dont l'identifiant est passé en paramètre
 *
 * @param 	integer $id 		Indentifiant de l'élément parent
 * @param 	boolean $withParent Indique si il faut également retourner le parent dans la liste
 * @param 	boolean $reverse 	Indique si il faut renvoyer le tableau en ordre inverse
 * @param 	varchar $level 		Indique le niveau des enfants à retourner
 * @param 	array 	$conditions Conditions de recherches supplémentaires
 * @return 	array Liste de tous les enfants du noeud parent
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 01/01/2012 by FI
 * @version 0.2 - 28/02/2012 by FI - Rajout du niveau à retourner
 * @version 0.3 - 20/03/2014 by FI - Correction pour retourner également le parent lors du test sur le niveau
 */    
    function getChildren($id, $withParent = false, $reverse = true, $level = null, $conditions = array()){
		
		$id = (int)$id; //On force le type de l'identifiant
        $tree = $this->getTree($conditions); //Récupération de l'arbre
        
		if(!isset($tree[$id])) { return array(); } //Si l'identifiant n'existe pas on retourne une valeur vide
        
		$parent = $tree[$id];
		$childs = array(); //Liste des enfants à retourner
        
		if(!$withParent && isset($tree[$id])) unset($tree[$id]);
					
		//On va parcourir l'arbre
        foreach($tree as $node) {
        	
            if($node['lft'] < $parent['lft']) continue;            
            if($node['rgt'] > $parent['rgt']) break;
            $childs[] = $node;
        }   
        
        if(isset($level)) {
        	
        	foreach($childs as $k => $node) { if($node['id'] != $parent['id'] && $node['level'] != $level) unset($childs[$k]); }
        }        
        
        if($reverse) { return array_reverse($childs); }
        else { return $childs; }
    }
    
/**
 * Cette fonction permet de récupérer le chemin d'un noeud
 * Fonction pratique pour la mise en place du fil d'ariane
 *
 * @param 	integer 	$childrenId Identifiant du noeud 
 * @param 	varchar 	$type Type de retour souhaité (array dans son fonctionnement normal, varchar si on l'utilise dans la fonction récursive)
 * @return	mixed	Soit un tableau, soit une chaine de caractères
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 01/01/2012 by FI
 * @version 0.2 - 13/04/2012 by FI - Modification des conditions de recherche
 * @version 0.3 - 16/05/2012 by FI - Modification de la récupération des catégories suite à la mise en place de la gestion des sites - On ne récupère pas les catégories de type 3
 * @version 0.4 - 21/05/2014 by FI - Mise en place d'un contrôle pour vérifier que $children ne soit pas vide auquel cas rien ne sert de continuer
 */    
    function getPath($childrenId, $type = 'array') {
    	
    	$children = $this->getTree(array('conditions' => array('id' => $childrenId))); //Récupération de l'enfant    	
    	
    	if(!empty($children)) {
			
    		$children = $children[$childrenId]; //Récupération de l'enfant
	
			$req = array('moreConditions' => "lft<".$children['lft']." AND rgt>".$children['rgt']." AND type != 3"); //Conditions de recherche
	    	$node = $this->getTree($req); //On va récupérer les menus
	    	
	    	if($type == 'array') {
	    		
	    		if(!empty($node)) return am($node, array($childrenId => $children));
	    		else return array(0 => $children);
	    		
			//Utilisé pour la génération de la fonction récursive pour la mise en place de l'arbre    		
	    	} else if($type == 'varchar') {
	
	    		if(!empty($node)) {
	    			
	    			$path = am($node, array($childrenId => $children)); //Path du noeud
	    			$return = ''; //Variable de retour
	    			foreach($path as $k => $v) { //Parcours des noeuds
	
	    				if($v['level'] > 1) { $return .= 'children.'; } //Si le level est > 0 on est sur un noeud enfant
	    				$return .= $v['id'].'.'; 
	    			}
	    			
	    			$return = substr($return, 0, strlen($return) - 1); //Suppression du dernier .
	    			return $return; //On retourne les données
	    		} else {
	    			
	    			return $childrenId; //On retourne l'identifiant du noeud par défaut
	    		} 
	    	}
    	} else { return array(); }
    }
    
/**
 * Cette fonction est utilisée pour mettre en place l'arbre avec toutes les dépendances parents/enfants
 *
 * @param 	array 	$req 		paramètres de recherche
 * @param 	integer $parentId 	Identifiants de l'élement parent
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 01/01/2012 by FI
 * @version 0.2 - 23/02/2012 by FI - Modification de la structure de la fonction pour gérer d'un coté l'identifiant du parent et de l'autre les conditions supplémentaires 
 * @version 0.3 - 16/05/2012 by FI - Modification de la récupération des catégories suite à la mise en place de la gestion des sites - Récupération en premier de la catégorie parente du site 
 */    
    function _recursive($req = array(), $parentId = null) {
    	    	   	
    	//Récupération de l'indentifiant de la catégorie racine du site
    	$racineConditions = array('conditions' => array('type' => 3, 'online' => 1));
    	$racine = $this->findFirst($racineConditions);
    	$racineId = $racine['id'];
    	
    	if(empty($parentId)) { $req['conditions']['parent_id'] = $racineId; }
    	else { $req['conditions']['parent_id'] = $parentId; }
    	    	
    	$nodes = $this->getTree($req); //On va récupérer les menus
    	foreach($nodes as $k => $v) { //On parcours l'ensemble de ces menus
    		
    		$path = $this->getPath($v['id'], 'varchar'); //On récupère le path    		
    		$this->displayTree = Set::insert($this->displayTree, $path, $v); //On insère l'élément courant dans l'arbre
    		
    		//Pour savoir si le noeud courant à des enfants il suffit de faire 
    		//la différence entre la borne doite et la borne gauche si cette
    		//valeur est > 1 il y à des enfants    		
    		$diff = $v['rgt'] - $v['lft'];    		
    		if($diff > 1) { $this->_recursive($req, $v['id']); } //On relance la fonction
    	}
    }
}