<?php 
//On récupère la section de la page précédente
if(isset($_POST['section']) && !empty($_POST['section'])) { 
	
	$section = $_POST['section'];
	unset($_POST['section']);
} else {
	
	$httpHost = $_SERVER["HTTP_HOST"];
	if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $section = 'localhost';	} else { $section = 'online'; }
}

require_once(LIBS.DS.'config_magik.php'); //Import de la librairie de gestion des fichiers de configuration
$cfg = new ConfigMagik(CONFIGS_FILES.DS.'database.ini', true, true); //Création d'une instance, si le fichier database.ini n'existe pas il sera créé
$conf = $cfg->keys_values($section);

//On va procéder à l'import
require_once(INSTALL_FUNCTIONS.DS.'database.php'); //Inclusion des fonctions de paramétrage de la base de données
$start = 1;
$foffset = 0;
$totalqueries = 0;
$init_db_tables = init_db($conf['host'], $conf['database'], $conf['login'], $conf['password'], "database_datas", $start, $foffset, $totalqueries);
?>
<div id="right">		
	<div id="main">				
		
		<div class="box">			
			<div class="title">
				<h2>IMPORT DES DONNEES DANS LES TABLES</h2>
			</div>
			<div class="content nopadding">				
				<?php									
				if($init_db_tables['result']) {
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
							<td class="odd"><?php echo $init_db_tables['datas']['lines_this']; ?></td>
							<td class="even"><?php echo $init_db_tables['datas']['lines_done']; ?></td>
							<td class="odd"><?php echo $init_db_tables['datas']['lines_tota']; ?></td>
						</tr>
						<tr>
							<td class="tableid first">Requêtes</td>
							<td class="odd"><?php echo $init_db_tables['datas']['queries_this']; ?></td>
							<td class="even"><?php echo $init_db_tables['datas']['queries_done']; ?></td>
							<td class="odd"><?php echo $init_db_tables['datas']['queries_tota']; ?></td>
						</tr>
						<tr>
							<td class="tableid first">Volume en Bytes</td>
							<td class="odd"><?php echo $init_db_tables['datas']['bytes_this']; ?></td>
							<td class="even"><?php echo $init_db_tables['datas']['bytes_done']; ?></td>
							<td class="odd"><?php echo $init_db_tables['datas']['bytes_tota']; ?></td>
						</tr>
						<tr>
							<td class="tableid first">Volume en KB</td>
							<td class="odd"><?php echo $init_db_tables['datas']['kbytes_this']; ?></td>
							<td class="even"><?php echo $init_db_tables['datas']['kbytes_done']; ?></td>
							<td class="odd"><?php echo $init_db_tables['datas']['kbytes_tota']; ?></td>
						</tr>
						<tr>
							<td class="tableid first">Volume en MB</td>
							<td class="odd"><?php echo $init_db_tables['datas']['mbytes_this']; ?></td>
							<td class="even"><?php echo $init_db_tables['datas']['mbytes_done']; ?></td>
							<td class="odd"><?php echo $init_db_tables['datas']['mbytes_tota']; ?></td>
						</tr>
						<tr>
							<td class="tableid first">%</td>
							<td class="odd"><?php echo $init_db_tables['datas']['pct_this']; ?></td>
							<td class="even"><?php echo $init_db_tables['datas']['pct_done']; ?></td>
							<td class="odd"><?php echo $init_db_tables['datas']['pct_tota']; ?></td>
						</tr>
						<tr>
							<td class="tableid first">% progression</td>
							<td class="odd" colspan="4"><?php echo $init_db_tables['datas']['pct_bar']; ?></td>
						</tr>
					</table>
					<?php /* ?><form action="index.php?step=database_datas_default" method="post">
						<input type="hidden" name="section" value="<?php echo $section; ?>" />
						<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Importer des données de démo dans les tables</span></button></div>
					</form><?php */ ?>
					<form action="index.php?step=website" method="post">
						<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Configuration du site Internet</span></button></div>
					</form>
					<?php 
				} else {
					
					foreach($init_db_tables['datas'] as $k => $error) { ?><div class="system error"><?php echo $error['message']; ?></div><?php }						
				}
				?>
			</div>			
		</div>	
	</div>
</div>