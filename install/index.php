<?php 
require_once('bootstrap.php'); //Fichier chargé de loader les librairies et initialiser les constantes 

//Si on récupère la page à afficher dans l'url, par défaut on charge la page de configuration des dossiers
if(!isset($_GET['step'])) { $step = 'folders'; }
else { $step = $_GET['step']; }

$stepTitle = array(
	'folders' => '- Configuration des dossiers ',	
	'database_params' => '- Configuration de la base de données ',	
	'database_tables' => '- Import des tables ',	
	'database_datas' => '- Import des données ',	
	'website' => '- Configuration du site Internet ',	
	'smtp' => '- Configuration du serveur SMTP ',	
	'final' => '- Récapitulatif '		
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>.:: Installation <?php echo $stepTitle[$step];?>::.</title>
		<link href="../webroot/css/backoffice/style.css" rel="stylesheet" type="text/css" />
		<link href="../webroot/css/backoffice/style_text.css" rel="stylesheet" type="text/css" />
		<link href="../webroot/css/backoffice/login.css" rel="stylesheet" type="text/css" />
		<link href="../webroot/css/backoffice/forms.css" rel="stylesheet" type="text/css" />
		<link href="../webroot/css/backoffice/form-buttons.css" rel="stylesheet" type="text/css" />
		<link href="../webroot/css/backoffice/system-messages.css" rel="stylesheet" type="text/css" />
		<link href="../webroot/css/install/hook.css" rel="stylesheet" type="text/css" />
	</head>

	<body>
	
		<div id="wrapper">
			<div id="left">
				<ul>
					<li <?php echo $step == 'folders' ? 'class="active"' : ''; ?>><a>DOSSIERS</a></li>
					<li <?php echo in_array($step, array('database_params', 'database_tables', 'database_datas')) ? 'class="active"' : ''; ?>><a>BASE DE DONNEES</a></li>
					<li <?php echo $step == 'website' ? 'class="active"' : ''; ?>><a>SITE INTERNET</a></li>
					<li <?php echo $step == 'smtp' ? 'class="active"' : ''; ?>><a>SERVEUR SMTP</a></li>
					<li <?php echo $step == 'final' ? 'class="active"' : ''; ?>><a>RECAP</a></li>
				</ul>
			</div>
			<?php include_once('pages/'.$step.'.php'); //Chargement de la page ?>
		</div>	
	</body>
</html>