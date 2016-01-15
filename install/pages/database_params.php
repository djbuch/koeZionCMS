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

	require_once INSTALL_VALIDATE.DS.'database.php'; //Inclusion des règles de validation des champs
	
	//Si pas d'erreur de validation
	if(!isset($formerrors)) {
			
		require_once INSTALL_FUNCTIONS.DS.'database.php'; //Inclusion des fonctions de paramétrage de la base de données
		$bddcheck = check_connexion($datas['host'], $datas['login'], $datas['password'], $datas['database']); //On check la connexion à la bdd
		if($bddcheck) {
			
			require_once LIBS.DS.'config_magik.php'; //Import de la librairie de gestion des fichiers de configuration
			$cfg = new ConfigMagik(CONFIGS_FILES.DS.'database.ini', true, true); //Création d'une instance, si le fichier database.ini n'existe pas il sera créé
			
			
			$datas['prefix'] = ""; //Par défaut à vide
			//On va parcourir les données postées et mettre à jour le fichier ini
			foreach($datas as $k => $v) { $cfg->set($k, $v, $section); }
			$cfg->save(); //On sauvegarde le fichier de configuration
		}
	}
}
?>
<div class="box box-primary">
	<div class="box-header bg-light-blue">
		<h4><i class="fa fa-database"></i> <?php echo _("CONFIGURATION DE LA BASE DE DONNEES"); ?></h4>                  
	</div>    							
	<?php
	//Si la bdd n'est pas importée, cas par défaut on arrive sur la page
	if(!isset($bddcheck)) {
		
		?>
		<form action="index.php?step=database_params" method="post">
			<div class="box-body">
				<div class="callout callout-warning"><?php echo ("ATTENTION : La base de données doit être crée avant de procéder aux paramétrages."); ?></div>
				<?php require_once INSTALL_INCLUDE.DS.'database_form.php'; ?>
			</div>	
			<div class="box-footer">
				<button class="btn btn-primary btn-flat pull-right" type="submit"><?php echo _('Tester la connexion à la base de données'); ?></button>
			</div>
		</form>
		<?php				
		
	} else {					
	
		//Si le check de la BDD n'est pas concluant
		if(!$bddcheck) {

			?>
			<form action="index.php?step=database_params" method="post">
				<div class="box-body">
					<div class="callout callout-warning"><?php echo ("ATTENTION : La base de données doit être crée avant de procéder aux paramétrages."); ?></div>
					<div class="callout callout-danger"><?php echo ("Impossible de se connecter à la base de données avec les informations communiquées, veuillez recommencer."); ?></div>
					<?php require_once INSTALL_INCLUDE.DS.'database_form.php'; ?>
				</div>
				<div class="box-footer">
					<button class="btn btn-primary btn-flat pull-right" type="submit"><?php echo _('Tester la connexion à la base de données'); ?></button>
				</div>
			</form>
			<?php 
			
		} else {
		
			?>
			<div class="box-body">
				<div class="callout callout-success"><?php echo ("La base de données est correctement paramétrée."); ?></div>
			</div>
			<div class="box-footer">
				<form action="index.php?step=database_tables" method="post">
					<input type="hidden" name="section" value="<?php echo $section; ?>" />
					<button class="btn btn-primary btn-flat pull-right" type="submit"><?php echo _('Importer les tables de la base de données'); ?></button>
				</form> 
			</div>
			<?php
		}
	}
	?>
</div>