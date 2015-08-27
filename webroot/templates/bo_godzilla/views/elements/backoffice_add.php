<?php
$libellePage = array(
	'Categories' 	=> _("une page"),
	'Posts' 		=> _("un article"),
	'PostsTypes' 	=> _("un type d'article"),
	'PostsComments' => _("un commentaire article"),
	'Sliders' 		=> _("un slider"),
	'Focus' 		=> _("un focus"),
	'Contacts' 		=> _("un formulaire Internaute (Contacts/Newsletter)"),
	'Users' 		=> _("un utilisateur"),
	'UsersGroups' 	=> _("un groupe d'utilisateurs"),	
	'Websites' 		=> _("un site Internet"),
	'RightButtons' 	=> _("un bouton colonne de droite"),
	'Modules' 		=> _("un module"),
	'ModulesTypes' 	=> _("un type de module"),
	'UnwantedCrawlers' 	=> _("un crawler")
);
?>
<div class="section">
	<div class="box">
		<div class="title">
			<h2><?php echo _("Ajouter"); ?> <?php if(isset($libellePage[$this->vars['params']['controllerName']])) { echo $libellePage[$this->vars['params']['controllerName']]; } ?></h2>
			<?php echo $helpers['Html']->backoffice_button_title($params['controllerFileName'], 'index', _("Listing")); ?>
		</div>
		<div class="content nopadding">
			<?php 
			$formOptions = array('id' => $params['modelName'].'Add', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/add'), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions); 
			$this->element('formulaires/'.$params['controllerFileName']);
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>