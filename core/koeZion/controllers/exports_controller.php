<?php
/**
 * Ce contrôleur permet la gestion des exports
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
 */
class ExportsController extends AppController {	

//////////////////////////////////////////////////////////////////////////////////////////
//										FRONTOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	

/**
 * Cette fonction permet de générer le fichier de sauvegarde de la base de données par CRON
 * 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 10/09/2012 by FI	
 */	
	function database() {
		
		require_once(LIBS.DS.'config_magik.php');
		$cfg = new ConfigMagik(CONFIGS_FILES.DS.'security_code.ini', true, false);
		$cfgValues = $cfg->keys_values();
		
		$this->layout = 'export'; //Définition du layout à utiliser
		$datas['type'] = 'xml';
		$this->set($datas);
		
		if(isset($_GET['export_code']) && !empty($_GET['export_code']) && !empty($cfgValues['security_code']) && ($_GET['export_code'] == $cfgValues['security_code'])) {
		
			$datas = $this->_export_database();
			
			$file = '';
			foreach($datas['createTables'] as $k => $v) { $file .= $v."\n\n\n"; }
			foreach($datas['insertions'] as $k => $v) { $file .= $v."\n\n\n"; }
									
			$fileFolder = TMP.DS.'backup_bdd'.DS;
			$fileName = 'backup_'.date("Ymd_His").'.sql';
			
			if(!is_dir($fileFolder)) { mkdir($fileFolder, 0777); }
			
			$pointeur = fopen($fileFolder.$fileName, 'w');
			fwrite($pointeur, $file);
			fclose($pointeur);
			
			$this->set('export_result', 'EXPORT EFFECTUE');
			$this->set('export_message', "Votre export est disponible à l'endroit suivant ".$fileFolder.$fileName);
			
		} else {
			
			$this->set('export_result', 'EXPORT NON EFFECTUE');
			$this->set('export_message', "Le code d'export n'a pu être vérifié");
		}	
	
		////////////////////////////////////////////
		//    SUPPRESSION DES ANCIENS FICHIERS    //
		$bddBackupPath = TMP.DS.'backup_bdd';
		$bddBackupFiles = FileAndDir::directoryContent($bddBackupPath);
		if(!empty($bddBackupFiles)) {
					
			rsort($bddBackupFiles);
			
			$nbBddBackups = isset($cfgValues['nb_bdd_backups']) ? $cfgValues['nb_bdd_backups'] : 10;
			if(count($bddBackupFiles) > $nbBddBackups) {
				
				$suppr = '';
				for($i=$nbBddBackups;$i<count($bddBackupFiles);$i++) {
					 
					$fileToDelete = $bddBackupPath.DS.$bddBackupFiles[$i]; 
					unlink($fileToDelete); 
				}
			}
		}
		
		$this->render(VIEWS.DS.'exports'.DS.'database', false);	
	}

//////////////////////////////////////////////////////////////////////////////////////////
//										BACKOFFICE										//
//////////////////////////////////////////////////////////////////////////////////////////	
	
/**
 * Cette fonction permet de lister l'ensemble des exports possibles
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 16/07/2012 by FI
 */
	function backoffice_index($return = false, $fields = null, $order = null, $conditions = null) {}
		
/**
 * Cette fonction permet l'export des données
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 06/02/2012 by FI
 */
	function backoffice_contacts($online = null) {
	
		$this->layout = 'export'; //Définition du layout à utiliser	
		$datas['type'] = 'text/csv';
		
		$this->loadModel('Contact');
		$searchConditions = array('order' => 'created DESC, name ASC');		
		if(isset($online)) { $searchConditions['conditions'] = array('online' => $online); }		
		$datas['contacts'] = $this->Contact->find($searchConditions);				
		$this->set($datas);
	}	

/**
 * Cette fonction permet de générer le fichier de sauvegarde de la base de données
 * 
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 23/03/2012 by FI
 * @version 0.2 - 07/05/2012 by FI - Correction de la procédure d'import qui ne fonctionnait pas sous OVH
 * @version 0.3 - 10/09/2012 by FI - Mise en fonction privée de la procédure pour pouvoir mettre en place une sauvegarde par cron 
 * @see http://www.lyxia.org/blog/developpement/script-php-de-sauvegarde-mysql-571
 * @todo Voir si on ne peut pas mettre un autre type d'entête de fichier que text (maybee text/sql)	
 */	
	function backoffice_database() {
		
		$datas = $this->_export_database();			
	
		$this->layout = 'export'; //Définition du layout à utiliser
		$datas['type'] = 'text/plain'; //Voir si pas un autre type d'entête possible	
		
		$this->set($datas);
	}
	
/**
 * Cette fonction permet l'export du fichier pot
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 04/03/2012 by FI
 */
	/*function backoffice_get_pot() {
	
		$this->layout = 'export'; //Définition du layout à utiliser
		$datas['type'] = 'text';
		
		$datas['filecontent'] = file_get_contents(LOCALE.DS.'default.pot');
		$this->set($datas);
	}*/	
	
/**
 * Cette fonction permet de générer le fichier de sauvegarde de la base de données
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 10/09/2012 by FI
 */	
	function _export_database($return = false) {
				
		$database = $this->Export->database; //Récupération du nom de la base de données
				
		$sql = 'SHOW TABLES FROM `'.$database.'`;'; //Requête de récupération de la liste des tables		
		$databaseTables = $this->Export->query($sql, true); //On effectue la requête
		
		foreach($databaseTables as $k1 => $v1) { //On va parcourir l'ensemble des tables
		
			//Récupération du nom de la table
			$value = array_values($v1);
			$table = $value[0];
			
			$fisrtChar = substr($table, 0, 1); //On ne traite pas les tables avec _ devant
		
			if($fisrtChar != '_') {
		
				//On va lancer la requête SQL qui mermet de récupérer les instructions pour effectuer la création de la table
				$sql = 'SHOW CREATE TABLE `'.$table.'`;';
				$createTable = $this->Export->query($sql, true);
				$createTable = array_values($createTable[0]);
				$datas['createTables'][] = 'DROP TABLE IF EXISTS `'.$table.'`;'."\n".$createTable[1].';';	//On stocke la requête dans le tableau qui sera envoyé à la vue
		
				//On va maintenant s'occuper des données des différentes tables
				$sql = 'SELECT * FROM `'.$table.'`;';
				$tableDatas =  $this->Export->query($sql, true);
				$insertions = ''; //Variable qui contiendra les différentes insertions à effectuer pour la table courante
				foreach($tableDatas as $k2 => $v2) { //On va parcourir les données de la table
		
					$insertions .= 'INSERT INTO `'.$table.'` VALUES ('; //On va construire la requête d'insertion
					//foreach($v2 as $k3 => $v3) { $insertions .= '\'' . mysql_real_escape_string($v3) . '\', '; }
					foreach($v2 as $k3 => $v3) {
						$insertions .= $this->Export->db->quote($v3).', ';
					}
					$insertions = substr($insertions, 0, -2); //On va supprimer les deux derniers caractères un espace et une ,
					$insertions .= ");\n";
				}
		
				$datas['insertions'][] = $insertions; //On stocke les données à insérer dans le tableau qui sera envoyé à la vue
			}
		}
		
		return $datas;		
	}
	
/**
 * Cette fonction permet l'export des fichiers BDD générés automatiquement
 *
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 04/12/2014 by FI
 */
	function backoffice_get_bdd_backup() {
	
		$file = $this->request->data['file'];
		$this->layout = 'export'; //Définition du layout à utiliser
		$datas['type'] = 'text/plain'; //Voir si pas un autre type d'entête possible	
		
		$datas['file'] = $file;	
		
		$bddBackupFile = TMP.DS.'backup_bdd'.DS.$file;
		$datas['filecontent'] = file_get_contents($bddBackupFile);
		$this->set($datas);
	}
}