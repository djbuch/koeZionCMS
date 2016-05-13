<div class="row">
	<div class="col-md-12 form contact_form">
		<?php
		$this->element('forms/message');
		$formOptions = array('id' => 'websiteForm', 'action' => Router::url($this->controller->request->url, '').'#formsmessage', 'method' => 'post');
		echo $helpers['Form']->create($formOptions);
		
			echo $helpers['Form']->input('type_formulaire', '', array('type' => 'hidden', 'value' => 'contact')); 
			?>
        	<div class="row">
            	<div class="col-xs-12 col-md-6">
					<?php echo $helpers['Form']->input('name', _('Nom'), array('compulsory' => true, 'label' => false, 'placeholder' => _('Nom'))); ?>
				</div> 
				<div class="col-xs-12 col-md-6">
					<?php echo $helpers['Form']->input('phone', _('Téléphone'), array('compulsory' => true, 'label' => false, 'placeholder' => _('Téléphone'))); ?>
				</div>
				<div class="col-xs-12 col-md-6">
					<?php echo $helpers['Form']->input('email', _('Email'), array('compulsory' => true, 'label' => false, 'placeholder' => _('Email'))); ?>
				</div>
				<div class="col-xs-12 col-md-6">
					<?php echo $helpers['Form']->input('cpostal', _('Code postal'), array('compulsory' => true, 'label' => false, 'placeholder' => _('Code postal'))); ?>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<?php echo $helpers['Form']->input('message', _('Message'), array('compulsory' => true, 'label' => false, 'placeholder' => _('Message'), 'type' => 'textarea', 'rows' => '10', 'cols' => '10')); ?>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<button type="submit" class="btn btn-default"><?php echo _("Envoyer"); ?></button>
				</div>
			</div>
			<div class="row">		
				<div class="col-xs-12 col-sm-12 col-md-12 form_tip legacy"><?php echo $websiteParams['txt_after_form_contact']; ?></div>
			</div>
			<?php 
		echo $helpers['Form']->end();
		?>
	</div>
</div>