<?php
$libellePage = array(
	'Categories' => _("une page"),
	'Posts' => _("un article"),
	'PostsTypes' => _("un type d'article"),
	'PostsComments' => _("un commentaire article"),
	'Sliders' => _("un slider"),
	'Focus' => _("un focus"),
	'Contacts' => _("un formulaire Internaute (Contacts/Newsletter)"),
	'Users' => _("un utilisateur"),
	'UsersGroups' => _("un groupe d'utilisateurs"),	
	'Websites' => _("un site Internet")
);
?>
<div class="section">
	<div class="box">
		<div class="title">
			<h2><?php echo _("Editer"); ?> <?php if(isset($libellePage[$this->vars['params']['controllerName']])) { echo $libellePage[$this->vars['params']['controllerName']]; } ?></h2>
			<a class="btn black" href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/index'); ?>" style="float: right; margin-top: 3px;"><span><?php echo _("Listing"); ?></span></a>
		</div>
		<div class="content nopadding">
			<?php 
			$formOptions = array('id' => $params['modelName'].'Edit', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/edit/'.$id), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions);
			echo $helpers['Form']->input('id', '', array('type' => 'hidden'));
			$this->element('backoffice/formulaires/'.$params['controllerFileName']);
			echo $helpers['Form']->end(true); 
			?>
		</div>
	</div>
</div>