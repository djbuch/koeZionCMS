<?php 
$title_for_layout 		= $websiteParams['seo_page_title'];
$description_for_layout = $websiteParams['seo_page_description'];
$keywords_for_layout 	= $websiteParams['seo_page_keywords'];
?>
<section id="newsletter" class="page_content">	
	<?php
	$this->element('breadcrumbs');
	
	//////////////////////////////
	//    CONTENU DE LA PAGE    //
	?>
	<div class="content container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 form newsletter_form">
				<?php 
				echo $websiteParams['txt_newsletter'];
				 
				$this->element('forms/message');				
				$formOptions = array('id' => 'websiteForm', 'action' => Router::url('contacts/newsletter').'#formsmessage', 'method' => 'post');
				echo $helpers['Form']->create($formOptions);
				
					if(isset($newsletter_id)) { echo $helpers['Form']->input('id', '', array('type' => 'hidden', 'value' => $newsletter_id)); } 					
					?>
					<div class="col-xs-12 col-sm-12 col-md-12">
						<?php echo $helpers['Form']->input('name', _('Nom'), array('compulsory' => true, 'label' => false, 'placeholder' => _('Nom'))); ?>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12">
						<?php echo $helpers['Form']->input('email', _('Email'), array('compulsory' => true, 'label' => false, 'placeholder' => _('Email'))); ?>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12">
						<button type="submit" class="btn btn-default"><?php echo _("Envoyer"); ?></button>
					</div>
					<?php					
				echo $helpers['Form']->end(); 
				?>
				<div class="row">		
					<div class="col-xs-12 col-sm-12 col-md-12 form_tip legacy"><?php echo $websiteParams['txt_after_newsletter']; ?></div>
				</div>
			</div>			
		</div>
	</div>
</section>