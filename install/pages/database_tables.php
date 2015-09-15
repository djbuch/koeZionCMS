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
$init_db_tables = init_db($conf['host'], $conf['database'], $conf['login'], $conf['password'], "database_tables", $start, $foffset, $totalqueries);
?>
<div class="box box-primary">
	<div class="box-header bg-light-blue">
		<h4><i class="fa fa-folder-open"></i> <?php echo _("IMPORT DES TABLES DE LA BASE DE DONNEES"); ?></h4>                  
	</div>    		
	<?php if($init_db_tables['result']) { ?>		
		<div class="box-body">
			<table class="table table-bordered">
				<tr>
					<th>&nbsp;</th>
					<th>Session</th>
					<th>Effectué</th>
					<th>Total</th>
				</tr>
				<tr>
					<td>Lignes</td>
					<td class="text-right"><?php echo $init_db_tables['datas']['lines_this']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['lines_done']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['lines_tota']; ?></td>
				</tr>
				<tr>
					<td>Requêtes</td>
					<td class="text-right"><?php echo $init_db_tables['datas']['queries_this']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['queries_done']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['queries_tota']; ?></td>
				</tr>
				<tr>
					<td>Volume en Bytes</td>
					<td class="text-right"><?php echo $init_db_tables['datas']['bytes_this']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['bytes_done']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['bytes_tota']; ?></td>
				</tr>
				<tr>
					<td>Volume en KB</td>
					<td class="text-right"><?php echo $init_db_tables['datas']['kbytes_this']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['kbytes_done']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['kbytes_tota']; ?></td>
				</tr>
				<tr>
					<td>Volume en MB</td>
					<td class="text-right"><?php echo $init_db_tables['datas']['mbytes_this']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['mbytes_done']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['mbytes_tota']; ?></td>
				</tr>
				<tr>
					<td>%</td>
					<td class="text-right"><?php echo $init_db_tables['datas']['pct_this']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['pct_done']; ?></td>
					<td class="text-right"><?php echo $init_db_tables['datas']['pct_tota']; ?></td>
				</tr>
				<tr>
					<td>% progression</td>
					<td colspan="3"><?php echo $init_db_tables['datas']['pct_bar']; ?></td>
				</tr>
			</table>
		</div>
		<div class="box-footer"> 
			<form action="index.php?step=database_datas" method="post">
				<input type="hidden" name="section" value="<?php echo $section; ?>" />
				<button class="btn btn-primary btn-flat pull-right" type="submit"><?php echo _('Importer les données dans les tables'); ?></button>
			</form>
		</div>
	<?php } else { ?>
		<div class="box-body">
			<?php foreach($init_db_tables['datas'] as $k => $error) { ?><div class="callout callout-warning"><?php echo $error['message']; ?></div><?php } ?>
		</div>
	<?php } ?>	
</div>