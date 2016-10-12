<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 post_comments">
		<h4 class="page_title"><?php echo $nbCommentsTxt; ?></h4>
		<?php 
		if(!$nbComments) { ?><p><?php echo _("Aucun commentaire pour le moment, soyez le premier à donner votre avis."); ?></p><?php } 
		else {
			
			?>
			<ul class="list-unstyled">
				<?php 
				foreach($postsComments as $k => $v) { 
				
					$commentDate = $this->vars['components']['Date']->date_sql_to_human($v['created']);
					?>
					<li class="comment_item">
			           	<div class="comment_item_data">
							<div class="comment_author"><?php echo $v['name']; ?></div>
			        		<div class="comment_date"><?php echo $commentDate['date']['fullTxt'].' '._('à').' '.$commentDate['time']['hm']; ?></div>
						</div>
						<div class="comment_message"><?php echo $v['message']; ?></div>
					</li>
					<?php 
				}
				?>
			</ul>
			<?php 
		}
		?>
	</div>
	
	<div class="col-xs-12 col-sm-12 col-md-12 form post_form">
		<h4 class="page_title"><?php echo _("Laisser un commentaire"); ?></h4>
		<?php 
		$this->element('forms/message');
		$formOptions = array('id' => 'websiteForm', 'action' => Router::url($this->controller->request->url, '').'#formsmessage', 'method' => 'post');
		echo $helpers['Form']->create($formOptions);
	
			echo $helpers['Form']->input('type_formulaire', '', array('type' => 'hidden', 'value' => 'comment'));
			?>
			<div class="row">
				<div class="col-xs-12 col-md-4">
					<?php echo $helpers['Form']->input('name', _('Nom'), array('compulsory' => true, 'label' => false, 'placeholder' => _('Nom'))); ?>
				</div>
				<div class="col-xs-12 col-md-4">
					<?php echo $helpers['Form']->input('email', _('Email'), array('compulsory' => true, 'label' => false, 'placeholder' => _('Email'))); ?>
				</div>
				<div class="col-xs-12 col-md-4">
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
				<div class="col-xs-12 col-sm-12 col-md-12 form_tip legacy"><?php echo $websiteParams['txt_after_form_comments']; ?></div>
			</div>
			<?php 
		echo $helpers['Form']->end();
		?>		
	</div>
</div>