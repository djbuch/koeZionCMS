<?php 
$formOptions = array('id' => 'websiteForm', 'action' => Router::url($this->controller->request->url, '').'#formulaire', 'method' => 'post');
echo $helpers['Form']->create($formOptions);
$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
	?>
	<div id="form_container">				
		<div id="formulaire">
			<h3 class="widgettitle"><?php echo _("Laissez un commentaire"); ?></h3>
			<?php 	
			if(isset($message)) { echo $message; }			 
			echo $helpers['Form']->input('type_formulaire', '', array('type' => 'hidden', 'value' => 'comment')); 
			//echo $helpers['Form']->input('post_id', '', array('type' => 'hidden', 'value' => $post['id']));
			echo $helpers['Form']->input('name', _('Nom'), am($commonOptions, array("value" => _('Indiquez votre nom'), "title" => _('Indiquez votre nom'), 'locale' => false)));
			echo $helpers['Form']->input('email', _('Email'), am($commonOptions, array("value" => _('Indiquez votre email'), "title" => _('Indiquez votre email'))));
			echo $helpers['Form']->input('cpostal', _('Code postal'), am($commonOptions, array("value" => _('Indiquez votre code postal'), "title" => _('Indiquez votre code postal'))));
			echo $helpers['Form']->input('message', _('Message'), am($commonOptions, array("value" => _('Indiquez votre message'), "title" => _('Indiquez votre message'), 'type' => 'textarea', 'rows' => '5', 'cols' => '10')));
			?>
			<p style="position:relative;min-height:28px;"><?php echo $helpers['Form']->input('envoyer', _('Envoyer'), am($commonOptions, array('type' => 'submit', "class" => "btn btnPrimary", 'value' => _('Envoyer'))));  ?></p>
		</div>
	</div>
	<?php 
echo $helpers['Form']->end();
 		
/*if($postsComments) {
	
	?><div class="clearfix"></div><?php
	foreach($postsComments as $k => $v) {
		?>
		<div class="posts_comments">					
			<p class="post_message"><?php echo $v['message']; ?></p>
			<p class="post_name">par <?php echo $v['name']; ?></p>
		</div>
		<?php 
	}		
}*/
?>