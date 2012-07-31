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
		if($bddcheck) {
			
			require_once(LIBS.DS.'config_magik.php'); //Import de la librairie de gestion des fichiers de configuration
			$cfg = new ConfigMagik(CONFIGS_FILES.DS.'database.ini', true, true); //Création d'une instance, si le fichier database.ini n'existe pas il sera créé
			
			
			$datas['prefix'] = ""; //Par défaut à vide
			//On va parcourir les données postées et mettre à jour le fichier ini
			foreach($datas as $k => $v) { $cfg->set($k, $v, $section); }
			$cfg->save(); //On sauvegarde le fichier de configuration
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
				if(!isset($bddcheck)) {
					
					?><div class="system warning">ATTENTION : La base de données doit être crée avant de procéder aux paramétrages.</div><?php 
					require_once(INSTALL_INCLUDE.DS.'database_form.php');					
					
				} else {					
				
					if(!$bddcheck) {

						?>
						<div class="system warning">ATTENTION : La base de données doit être crée avant de procéder aux paramétrages.</div>
						<div class="system error">Impossible de se connecter à la base de données avec les informations communiquées, veuillez recommencer.</div>
						<?php 
						require_once(INSTALL_INCLUDE.DS.'database_form.php');
					}
					else { 
					
						?>
						<div class="system succes">La base de données est correctement paramétrée.</div>
						<form action="index.php?step=database_tables" method="post">
							<input type="hidden" name="section" value="<?php echo $section; ?>" />
							<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Importer les tables de la base de données</span></button></div>
						</form>
						<?php 
					}
				}
				?>
			</div>			
		</div>	
	</div>
</div>