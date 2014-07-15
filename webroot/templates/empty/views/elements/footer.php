<footer>
	<?php
	if(isset($websiteParams['footer_social']) && !empty($websiteParams['footer_social'])) { 
		echo $websiteParams['footer_gauche']; 
		echo $websiteParams['footer_social']; 
			
	} else { 
					
		echo $websiteParams['footer_gauche'];
		echo $websiteParams['footer_droite']; 
		?>
		<div class="newsletter">
			<h4><?php echo _("Newsletter"); ?></h4>
			<p><?php echo _("Souscrivrez à notre newsletter pour être informé de notre actualité."); ?></p>
			<?php 
			$formOptions = array('id' => 'FormNewsletterFooter', 'action' => Router::url('contacts/newsletter'), 'method' => 'post');
			echo $helpers['Form']->create($formOptions);		
			$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
			?>		
				<p>
					&nbsp;
					<?php echo $helpers['Form']->input('email', _('Email'), am($commonOptions, array("value" => _('Indiquez votre email'), "title" => _('Indiquez votre email'))));  ?>
					<?php echo $helpers['Form']->input('envoyer', _('Envoyer'), am($commonOptions, array('type' => 'submit', "class" => "superbutton black", 'value' => _('Valider'))));  ?>
				</p>
			<?php echo $helpers['Form']->end(); ?>
			
			<?php if(isset($websiteParams['footer_addthis'])) { ?><div class="social"><?php echo $websiteParams['footer_addthis']; ?></div><?php } ?>
		</div>	
		<?php 
		
	} 
	echo $websiteParams['footer_bottom']; 
	echo GENERATOR_LINK; ?><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE VARIABLE ?>
	<?php $this->element('logout'); ?>
</footer>