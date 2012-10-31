<div class="stripe"></div>
<div id="footer_top" class="png_bg"></div>
<div id="footer" class="png_bg">	
	<?php 
	if(isset($websiteParams['footer_social']) && !empty($websiteParams['footer_social'])) { 
		
		?><div class="gs_5 widget" style="width:570px;min-height:258px;"><?php echo $websiteParams['footer_gauche']; ?></div><?php 
		echo $websiteParams['footer_social']; 
	} else { 
		
		?>
		<div class="gs_5 widget"><?php echo $websiteParams['footer_gauche']; ?></div>	
		<div class="gs_4 widget">
			<h4 class="widgettitle"><?php echo _("Newsletter"); ?></h4>
			<p><?php echo _("Souscrivre à notre newsletter pour être informé de notre actualité."); ?></p>
			<?php 
			$formOptions = array('id' => 'FormNewsletterFooter', 'action' => Router::url('contacts/newsletter'), 'method' => 'post');
			echo $helpers['Form']->create($formOptions);		
			$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
			?>		
				<p>
					&nbsp;
					<?php echo $helpers['Form']->input('email', _('Email'), am($commonOptions, array("value" => _('Indiquez votre email'), "title" => _('Indiquez votre email'))));  ?>
					<?php echo $helpers['Form']->input('envoyer', _('Envoyer'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Valider'))));  ?>
				</p>
			<?php echo $helpers['Form']->end(); ?>
			
			<?php /* ?><div class="social"><?php echo $websiteParams['footer_social']; ?></div><?php */ ?>
		</div>	
		<div class="gs_3 omega widget"><?php echo $websiteParams['footer_droite']; ?></div>
		<?php 
	} 
	?>		
	<?php echo GENERATOR_LINK; ?><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE VARIABLE ?>	
</div>
<div id="footer_bottom" class="png_bg"><?php echo $websiteParams['footer_bottom']; ?></div>