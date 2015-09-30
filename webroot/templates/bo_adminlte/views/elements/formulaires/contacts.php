<div class="box box-primary">
	<div class="box-body">
		<div class="col-md-12">
			<?php
			echo $helpers['Form']->input('name', _('Nom'), array('compulsory' => true, 'tooltip' => _("Indiquez le nom du contact"))); 
			echo $helpers['Form']->input('phone', _('Téléphone'), array('compulsory' => true, 'tooltip' => _("Indiquez le téléphone du contact"))); 
			echo $helpers['Form']->input('email', _('Email'), array('compulsory' => true, 'tooltip' => _("Indiquez l'email du contact"))); 
			echo $helpers['Form']->input('cpostal', _('Code postal'), array('compulsory' => true, 'tooltip' => _("Indiquez le code postal du contact"))); 
			echo $helpers['Form']->input('message', _('Message'), array('compulsory' => true, 'type' => 'textarea', 'wysiswyg' => true, 'toolbar' => 'empty', 'class' => 'xxlarge')); 
			echo $helpers['Form']->input('online', _('Valide'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour valider ce contact (Une fois validé le contact sera exporté dans le fichier csv)")));
			?>
		</div>
	</div>	
	<div class="box-footer">
    	<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>    
	</div>
</div>