<?php 
//On récupère la section de la page précédente
if(isset($_POST['section']) && !empty($_POST['section'])) { 
	
	$section = $_POST['section'];
	unset($_POST['section']);
} else {
	
	$httpHost = $_SERVER["HTTP_HOST"];
	if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $section = 'localhost';	} else { $section = 'online'; }
}

//Si les données sont postées
if(isset($_POST['valid_database_form']) && $_POST['valid_database_form']) {
		
	unset($_POST['valid_database_form']);
	$datas = $_POST; //Création d'une variable contenant les données postées

	require_once(INSTALL_VALIDATE.DS.'database.php'); //Inclusion des règles de validation des champs
	
	//Si pas d'erreur de validation
	if(!isset($formerrors)) {
			
		require_once(INSTALL_FUNCTIONS.DS.'database.php'); //Inclusion des fonctions de paramétrage de la base de données
		$bddcheck = check_connexion($datas['host'], $datas['login'], $datas['password'], $datas['database']); //On check la connexion à la bdd
		
		//Si le chek est ok
		if($bddcheck) {
			
			require_once(LIBS.DS.'config_magik.php'); //Import de la librairie de gestion des fichiers de configuration
			$cfg = new ConfigMagik(CONFIGS_FILES.DS.'database.ini', true, true); //Création d'une instance, si le fichier database.ini n'existe pas il sera créé
			
			//On va parcourir les données postées et mettre à jour le fichier ini
			foreach($datas as $k => $v) { $cfg->set($k, $v, $section); }
			$cfg->save(); //On sauvegarde le fichier de configuration
			
			//On va procéder à l'import
			$start = 1;
			$foffset = 0;
			$totalqueries = 0;
			$init_db = init_db($datas['host'], $datas['database'], $datas['login'], $datas['password'], $start, $foffset, $totalqueries);			
		}
	}
}

?>
<div id="right">		
	<div id="main">				
		
		<div class="box">			
			<div class="title">
				<h2>CONFIGURATION DE LA BASE DE DONNEES</h2>
			</div>
			<div class="content nopadding">				
				<?php
				//Si la bdd n'est pas importée, cas par défaut on arrive sur la page
				if(!isset($init_db)) {
					
					//Si le check de connexion à la bdd n'a pas fonctionné
					if(isset($bddcheck) && !$bddcheck) { ?><div class="system error">Impossible de se connecter à la base de données avec les informations communiquées, veuillez recommencer.</div><?php }					
					require_once(INSTALL_INCLUDE.DS.'database_form.php');					
					
				} else {					
					if($init_db['result']) {
						?>
						<table cellpadding="0" cellspacing="0" id="ethernatable">
							<tr>
								<th id="MatrixItems">&nbsp;</th>
								<th class="tablecol">Session</th>
								<th class="tablecol">Effectué</th>
								<th class="tablecol">Total</th>
							</tr>
							<tr>
								<td class="tableid first">Lignes</td>
								<td class="odd"><?php echo $init_db['datas']['lines_this']; ?></td>
								<td class="even"><?php echo $init_db['datas']['lines_done']; ?></td>
								<td class="odd"><?php echo $init_db['datas']['lines_tota']; ?></td>
							</tr>
							<tr>
								<td class="tableid first">Requêtes</td>
								<td class="odd"><?php echo $init_db['datas']['queries_this']; ?></td>
								<td class="even"><?php echo $init_db['datas']['queries_done']; ?></td>
								<td class="odd"><?php echo $init_db['datas']['queries_tota']; ?></td>
							</tr>
							<tr>
								<td class="tableid first">Volume en Bytes</td>
								<td class="odd"><?php echo $init_db['datas']['bytes_this']; ?></td>
								<td class="even"><?php echo $init_db['datas']['bytes_done']; ?></td>
								<td class="odd"><?php echo $init_db['datas']['bytes_tota']; ?></td>
							</tr>
							<tr>
								<td class="tableid first">Volume en KB</td>
								<td class="odd"><?php echo $init_db['datas']['kbytes_this']; ?></td>
								<td class="even"><?php echo $init_db['datas']['kbytes_done']; ?></td>
								<td class="odd"><?php echo $init_db['datas']['kbytes_tota']; ?></td>
							</tr>
							<tr>
								<td class="tableid first">Volume en MB</td>
								<td class="odd"><?php echo $init_db['datas']['mbytes_this']; ?></td>
								<td class="even"><?php echo $init_db['datas']['mbytes_done']; ?></td>
								<td class="odd"><?php echo $init_db['datas']['mbytes_tota']; ?></td>
							</tr>
							<tr>
								<td class="tableid first">%</td>
								<td class="odd"><?php echo $init_db['datas']['pct_this']; ?></td>
								<td class="even"><?php echo $init_db['datas']['pct_done']; ?></td>
								<td class="odd"><?php echo $init_db['datas']['pct_tota']; ?></td>
							</tr>
							<tr>
								<td class="tableid first">% progression</td>
								<td class="odd" colspan="4"><?php echo $init_db['datas']['pct_bar']; ?></td>
							</tr>
						</table>
						<form action="index.php?step=website" method="post">
							<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Configuration du site Internet</span></button></div>
						</form>
						<?php 
					} else {
						
						foreach($init_db['datas'] as $k => $error) { ?><div class="system error"><?php echo $error['message']; ?></div><?php }						
					}
				}
				?>
			</div>			
		</div>	
	</div>
</div>