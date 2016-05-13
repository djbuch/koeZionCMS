<?php 
$controllerName = $this->controller->params['controllerName'];
$actionName 	= $this->controller->params['action'];

if($controllerName == 'Contacts' && $actionName == 'newsletter') { $this->element('slogan'); }
else {
	?>
	<section class="newsletter_element">
		<div class="container">
	    	<div class="row">
				<?php 
				$formOptions = array('id' => 'FormNewsletterHome', 'action' => Router::url('contacts/newsletter'), 'method' => 'post');
		        echo $helpers['Form']->create($formOptions);
		       		?>
		        	<div class="col-md-8 col-md-offset-2 form newsletter_form">
		            	<div class="newsletter_label"><?php echo _("Gardons le contact"); ?></div>
		                <div class="newsletter_input">
							<?php echo $helpers['Form']->input('email', '', array('onlyInput' => true, 'placeholder' => _('Indiquez votre email'), 'class' => 'form-control')); ?>
		                    <button type="submit" class="btn btn-default"><?php echo _("S'inscrire"); ?></button>
						</div>
		                <div class="form_tip">
		                   	<i class="glyphicon glyphicon-info-sign"></i> <?php echo _("Inscrivez-vous à notre newsletter pour recevoir toutes nos actualités"); ?>
						</div>
					</div>
					<?php 
				echo $helpers['Form']->end();
				?>	    		
	    	</div>	    	
		</div>
	</section>
	<?php 
}	
?>