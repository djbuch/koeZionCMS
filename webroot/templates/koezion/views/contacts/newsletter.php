<?php 
$this->element('breadcrumbs'); 
$title_for_layout = $websiteParams['seo_page_title'];
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout = $websiteParams['seo_page_keywords'];
?>
<div class="container_omega">
	<?php 
	if(isset($message)) { echo $message; } 
	echo $websiteParams['txt_newsletter']; 
	
	$formOptions = array('id' => 'FormNewsletterPage', 'action' => Router::url('contacts/newsletter'), 'method' => 'post');
	echo $helpers['Form']->create($formOptions);
	
	$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
	?>
		<div id="form_container">		
			<div id="form_contact">
				<?php if(isset($newsletter_id)) { echo $helpers['Form']->input('id', '', array('type' => 'hidden', 'value' => $newsletter_id)); } ?>
				<?php echo $helpers['Form']->input('name', _('Nom'), am($commonOptions, array("value" => _('Indiquez votre nom'), "title" => _('Indiquez votre nom'))));  ?>
				<?php echo $helpers['Form']->input('email', _('Email'), am($commonOptions, array("value" => _('Indiquez votre email'), "title" => _('Indiquez votre email'))));  ?>
				<p><?php echo $helpers['Form']->input('envoyer', _('Envoyer'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Envoyer'))));  ?></p>
			</div>
			<?php echo $websiteParams['txt_after_newsletter']; ?>
		</div>
	<?php echo $helpers['Form']->end(); ?>
</div>