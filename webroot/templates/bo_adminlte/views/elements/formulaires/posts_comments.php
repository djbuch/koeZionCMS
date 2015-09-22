<div class="box box-primary">
	<div class="box-body">
		<?php
		echo $helpers['Form']->input('post_id', _('Article'), 		array('type' => 'select', 'datas' => $posts, 'tooltip' => _("Indiquez ici l'article que vous souhaitez commenter")));
		echo $helpers['Form']->input('name', 	_('Nom'), 			array('compulsory' => true, 'tooltip' => _("Indiquez le nom de l'Internaute"))); 
		echo $helpers['Form']->input('email', 	_('Email'), 		array('compulsory' => true, 'tooltip' => _("Indiquez l'email de l'Internaute"))); 
		echo $helpers['Form']->input('cpostal', _('Code postal'), 	array('compulsory' => true, 'tooltip' => _("Indiquez le code postal de l'Internaute"))); 
		echo $helpers['Form']->input('message', _('Message'), 		array('compulsory' => true, 'type' => 'textarea', 'wysiswyg' => true, 'toolbar' => 'empty', 'class' => 'xxlarge')); 
		echo $helpers['Form']->input('online', 	_('Valide'), 		array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour valider ce commentaire")));
		?>
	</div>
	<div class="box-footer">
		<?php echo $helpers['Form']->button(); ?>
	</div>
</div>