<?php 
require_once(INSTALL_FUNCTIONS.DS.'website.php'); //Inclusion des fonctions de paramétrage de la base de données
$templatesList = init_templates();

$process_website = 0;
//Si les données sont postées
if(isset($_POST) && !empty($_POST)) {
	
	$datas = $_POST; //Création d'une variable contenant les données postées

	require_once(INSTALL_VALIDATE.DS.'website.php'); //Inclusion des règles de validation des champs
	
	//Si pas d'erreur de validation
	if(!isset($formerrors)) {
			
		$datas = $_POST; //Création d'une variable contenant les données postées		
		$process_website = save_website($datas); //Sauvegarde du site
	}
}
?>
<div id="right">		
	<div id="main">				
		
		<div class="box">			
			<div class="title">
				<h2>CONFIGURATION DU SITE INTERNET</h2>
			</div>
			<div class="content nopadding">				
				<?php				
				if($process_website < 2) {	
					
					//Si le check de connexion à la bdd n'a pas fonctionné 
					require_once(INSTALL_INCLUDE.DS.'website_form.php');					
				} else { 
					?>		
					<div class="system succes">Le site Internet est maintenant paramétré.</div>			
					<?php /* ?><div class="system succes">
						Le site Internet est maintenant paramétré.<br /><br />
						Vous pouvez dès à présent passer à l'étape de paramétrage de votre serveur SMTP ou sauter cette étape pour finaliser l'installation.<br /><br />
						<i>Vous pourrez procéder au paramétrage de votre serveur SMTP depuis l'interface d'administration.</i>	
					</div><?php */ ?>
					<?php /* ?><form action="index.php?step=smtp" method="post">					
						<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Paramétrer mon serveur SMTP</span></button></div>
					</form><?php */ ?>
					<form action="index.php?step=final" method="post">					
						<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Finaliser l'installation</span></button></div>
					</form>	
					<?php 
				} 
				?>
			</div>			
		</div>	
	</div>
</div>