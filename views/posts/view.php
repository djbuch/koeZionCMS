<?php 
$this->element('frontoffice/breadcrumbs');
$title_for_layout = $post['page_title']; 
$description_for_layout = $post['page_description']; 
$keywords_for_layout = $post['page_keywords'];

?>
<div class="container_omega">
	<?php 
	echo $post['content']; 	
	if(isset($post['code']) && !empty($post['code'])) { ?><p class="information"><?php echo str_replace('[ARTICLE_ID]', $post['id'], $post['code']); ?></p><?php } 
	?>	
	<div class="clearfix"></div>
	<?php 
	//Page catégorie avec formulaire de contact
	if($post['display_comments']) { 

		$formOptions = array('id' => 'FormComment', 'action' => Router::url('posts/view/id:'.$post['id'].'/slug:'.$post['slug'].'/prefix:'.$post['prefix']).'#form_comments', 'method' => 'post');
		echo $helpers['Form']->create($formOptions);
		
		$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
		?>
		<div id="form_container">				
			<div id="form_comments">
				<h3 class="widgettitle"><?php echo _("Laissez un commentaire"); ?></h3>
				<?php 
				if(isset($message)) { echo $message; } 
				echo $helpers['Form']->input('post_id', '', array('type' => 'hidden', 'value' => $post['id']));
				echo $helpers['Form']->input('name', _('Nom'), am($commonOptions, array("value" => _('Indiquez votre nom'), "title" => _('Indiquez votre nom'), 'locale' => false)));
				echo $helpers['Form']->input('email', _('Email'), am($commonOptions, array("value" => _('Indiquez votre email'), "title" => _('Indiquez votre email'))));
				echo $helpers['Form']->input('message', _('Message'), am($commonOptions, array("value" => _('Indiquez votre message'), "title" => _('Indiquez votre message'), 'type' => 'textarea', 'rows' => '5', 'cols' => '10')));
				?>
				<p><?php echo $helpers['Form']->input('envoyer', _('Envoyer'), am($commonOptions, array('type' => 'submit', "class" => "superbutton", 'value' => _('Envoyer'))));  ?></p>
			</div>
		</div>
		<?php 
		echo $helpers['Form']->end();
	} 		
	
	if($postsComments) {
		
		?><div class="clearfix"></div><?php
		foreach($postsComments as $k => $v) {

			?>
			<div class="posts_comments">					
				<p class="post_message"><?php echo $v['message']; ?></p>
				<p class="post_name">par <?php echo $v['name']; ?></p>
			</div>
			<?php 
		}		
	}
	?>
</div>