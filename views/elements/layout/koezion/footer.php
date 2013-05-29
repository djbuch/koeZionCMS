<div class="stripe"></div>
<div id="footer_top" class="png_bg">
	<div class="footer_top_center_left"></div>		
	<div class="footer_top_center_right"></div>		
	<div class="footer_top_right"></div>	
</div>
<div id="footer" class="png_bg">	
	<?php 
	if(isset($websiteParams['footer_social']) && !empty($websiteParams['footer_social'])) { 
		
		?><div class="gs_5 widget" style="width:570px;min-height:258px;"><?php echo $websiteParams['footer_gauche']; ?></div><?php 
		echo $websiteParams['footer_social']; 
	} else { 
		
		?>
		<div class="gs_5 widget"><?php echo $websiteParams['footer_gauche']; ?></div>	
		<!-- Changement de place du widget gs_3 pour le responsive -->
		<div class="gs_3 omega widget"><?php echo $websiteParams['footer_droite']; ?></div>
		<div class="gs_4 widget">
			<h4 class="widgettitle"><?php echo _("Newsletter"); ?></h4>
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
	?>		
	<?php echo GENERATOR_LINK; ?><?php //ATTENTION VOUS NE POUVEZ PAS SUPPRIMER CETTE VARIABLE ?>	
	
	<div class="footer_left_top"></div>		
	<div class="footer_right_top"></div>
	<div class="footer_left_bottom"></div>		
	<div class="footer_right_bottom"></div>		
</div>
<div id="footer_bottom" class="png_bg">
	<?php echo $websiteParams['footer_bottom']; ?>
	<div class="footer_bottom_center_left"></div>		
	<div class="footer_bottom_center_right"></div>		
	<div class="footer_bottom_right"></div>
</div>