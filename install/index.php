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
	'final' => '- Récapitulatif '		
);
?>
<!DOCTYPE html>
<html>
	<head>
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<title>.:: Installation <?php echo $stepTitle[$step];?>::.</title>
	  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">    
    	<link href="./css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    	<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    	<link href="./css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    	<link href="./css/skins/skin-blue.min.css" rel="stylesheet" type="text/css" />
    	<link href="./css/hooks/backoffice.css" rel="stylesheet" type="text/css" />
    	<link href="./css/hooks/install.css" rel="stylesheet" type="text/css" />
    	<link href="./css/hooks/alerts.css" rel="stylesheet" type="text/css" />
	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	<body data-baseurl="<?php echo BASE_URL; ?>" class="hold-transition skin-blue sidebar-mini"><?php /*sidebar-collapse */ ?>
    	<div class="wrapper">
      		<div class="content-wrapper">
      			<section class="content-header">
          			<h1 class="text-center"><?php echo _("Processus d'installation de KoéZioN CMS"); ?></h1>
        		</section>
      			<section class="content">
	      			<div class="row">
	      				<div class="col-md-8 col-md-offset-2">
							<div class="nav-tabs-custom">
								<div class="col-md-3 left">
									<div class="box box-primary">
							    		<div class="box-body">
											<ul class="nav nav-tabs nav-stacked col-md-12">
										    	<li <?php echo $step == 'folders' ? 'class="active"' : ''; ?>><a><i class="fa fa-folder-open"></i> <?php echo _("Dossiers"); ?></a></li>
										        <li <?php echo in_array($step, array('database_params', 'database_tables', 'database_datas')) ? 'class="active"' : ''; ?>><a><i class="fa fa-database"></i> <?php echo _("Base de données"); ?></a></li>
										        <li <?php echo $step == 'website' ? 'class="active"' : ''; ?>><a><i class="fa fa-globe"></i> <?php echo _("Site Internet"); ?></a></li>
										        <li <?php echo $step == 'final' ? 'class="active"' : ''; ?>><a><i class="fa fa-check"></i> <?php echo _("Récapitulatif"); ?></a></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-md-9 right">		
									<div class="tab-content col-md-12">
										<div class="tab-pane active">
											<?php include_once('pages/'.$step.'.php'); //Chargement de la page ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>	
			</div>            
    	</div>
	</body>
</html>