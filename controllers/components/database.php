<?php
class DatabaseComponent extends Component {
	
/**
 * Cette fonction permet de générer le fichier de sauvegarde de la base de données
 *
 * @param	object 	$model 			Objet de type modèle utilisé pour effecter les requêtes
 * @param	array  	$databaseTables Tableau contenant la liste des tables à traiter, par défaut si cette variable n'est pas renseignée toutes les tables de la base seront traitées
 * @param	boolean $formatDatas 	Booléen qui indique si les données sont retournées brutes ou non
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 24/09/2015 by FI
 */	
	function export_database($model, $databaseTables = null, $formatDatas = false) {
				
		if(!isset($databaseTables)) { 
		
			$database 		= $model->database; //Récupération du nom de la base de données				
			$sql 			= 'SHOW TABLES FROM `'.$database.'`;'; //Requête de récupération de la liste des tables		
			$databaseTables = $model->query($sql, true); //On effectue la requête
		}
		
		foreach($databaseTables as $k1 => $v1) { //On va parcourir l'ensemble des tables
		
			//Récupération du nom de la table
			$value = array_values($v1);
			$table = $value[0];
			
			$fisrtChar = substr($table, 0, 1); //On ne traite pas les tables avec _ devant
		
			if($fisrtChar != '_') {
		
				//On va lancer la requête SQL qui mermet de récupérer les instructions pour effectuer la création de la table
				$sql = 'SHOW CREATE TABLE `'.$table.'`;';
				$createTable = $model->query($sql, true);
				$createTable = array_values($createTable[0]);
				$datas['createTables'][] = 'DROP TABLE IF EXISTS `'.$table.'`;'."\n".$createTable[1].';';	//On stocke la requête dans le tableau qui sera envoyé à la vue
		
				//On va maintenant s'occuper des données des différentes tables
				$sql = 'SELECT * FROM `'.$table.'`;';
				$tableDatas =  $model->query($sql, true);
				$insertions = ''; //Variable qui contiendra les différentes insertions à effectuer pour la table courante
				foreach($tableDatas as $k2 => $v2) { //On va parcourir les données de la table
		
					$insertions .= 'INSERT INTO `'.$table.'` VALUES ('; //On va construire la requête d'insertion
					//foreach($v2 as $k3 => $v3) { $insertions .= '\'' . mysql_real_escape_string($v3) . '\', '; }
					foreach($v2 as $k3 => $v3) {
						$insertions .= $model->db->quote($v3).', ';
					}
					$insertions = substr($insertions, 0, -2); //On va supprimer les deux derniers caractères un espace et une ,
					$insertions .= ");\n";
				}
		
				$datas['insertions'][] = $insertions; //On stocke les données à insérer dans le tableau qui sera envoyé à la vue
			}
		}
		
		//Données brutes
		if(!$formatDatas) { return $datas; }
		
		//Données formatées
		else {
			
			$return = '';
			
			//Création des tables
			if($datas['createTables']) {
				
				foreach($datas['createTables'] as $k => $v) { 
					
					if(!empty($v)) { $return .= $v."\n\n\n"; } 
				}
			}
			
			//Insertion des données
			if($datas['insertions']) {
				
				foreach($datas['insertions'] as $k => $v) { 
					
					if(!empty($v)) { $return .= $v."\n\n\n"; } 
				}
			}
			
			return $return; 
		}		
	}
}