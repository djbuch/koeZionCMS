<?php 
require_once INSTALL_FUNCTIONS.DS.'website.php'; //Inclusion des fonctions de paramétrage de la base de données
//$templatesList = init_templates();

$process_website = 0;
//Si les données sont postées
if(isset($_POST) && !empty($_POST)) {
	
	$datas = $_POST; //Création d'une variable contenant les données postées

	require_once INSTALL_VALIDATE.DS.'website.php'; //Inclusion des règles de validation des champs
	
	//Si pas d'erreur de validation
	if(!isset($formerrors)) {
			
		$datas = $_POST; //Création d'une variable contenant les données postées		
		$process_website = save_website($datas); //Sauvegarde du site
	}
}
?>
<div class="box box-primary">
	<div class="box-header bg-light-blue">
		<h4><i class="fa fa-folder-open"></i> <?php echo _("CONFIGURATION DU SITE INTERNET"); ?></h4>                  
	</div>    		
	<?php if($process_website < 2) { ?>
		<form action="index.php?step=website" method="post">
			<div class="box-body">
				<?php
				//Si le check de connexion à la bdd n'a pas fonctionné 
				require_once(INSTALL_INCLUDE.DS.'website_form.php');
				?>
			</div>
			<div class="box-footer"> 
				<button class="btn btn-primary btn-flat pull-right" type="submit"><?php echo _('Configurer le site Internet'); ?></button>
			</div>
		</form>
	<?php } else { ?>		
		<div class="box-body">
			<div class="callout callout-success"><?php echo _("Le site Internet est maintenant paramétré."); ?></div>
		</div>
		<div class="box-footer"> 	
			<form action="index.php?step=final" method="post">					
				<button class="btn btn-primary btn-flat pull-right" type="submit"><?php echo _("Finaliser l'installation"); ?></button>
			</form>	
		</div>
	<?php } ?>	
</div>