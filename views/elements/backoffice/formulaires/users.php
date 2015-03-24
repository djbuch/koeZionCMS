<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#websites_users"><?php echo _("Sites accessibles"); ?></a></li>
	</ul>
	<div id="general">
		<div class="content nopadding">
			<?php 			
			echo $helpers['Form']->input('users_group_id', _("Groupe d'utilisateurs"), array('compulsory' => true, 'type' => 'select', 'datas' => $usersGroupList, 'firstElementList' => _("Sélectionnez un groupe"), 'tooltip' => _("Indiquez le groupe de cet utilisateur")));			
			echo $helpers['Form']->input('name', _('Nom'), array('compulsory' => true, 'tooltip' => _("Indiquez le nom de l'utilisateur")));
			echo $helpers['Form']->input('second_name', _('Complément nom'), array('tooltip' => _("Indiquez un complément pour le nom de l'utilisateur")));
			echo $helpers['Form']->input('login', _('Identifiant'), array('compulsory' => true, 'tooltip' => _("Indiquez l'identifiant de l'utilisateur (Généralement un email)")));
			echo $helpers['Form']->input('password', _('Mot de passe'), array('compulsory' => true, 'tooltip' => _("Indiquez le mot de passe de l'utilisateur"), 'type' => 'password'));
			echo $helpers['Form']->input('email', _('Email de contact'), array('compulsory' => true, 'tooltip' => _("Indiquez l'email de contact de cet utilisateur (peut être identique au login si celui-ci est un email)")));
			echo $helpers['Form']->input('online', _('Actif'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour valider cet utilisateur")));
			?>
		</div>
	</div>
	<div id="websites_users">
		<div class="content nopadding">
			<div class="row" style="overflow:hidden;">
				<label>
					<?php echo $helpers['Html']->img('/backoffice/tooltip.png', array("original-title" => _("Cochez le (ou les) site(s) que cet utilisateur peut administrer"), "class" => "tip-w", "style" => "float: left; margin-right: 5px; cursor: pointer;", "alt" => "tooltip")); ?>
					<?php echo _("Site(s) administrable(s)"); ?>
				</label>
				<div class="rowright">
					<?php 
					foreach($websitesList as $id => $name) {
						?><span class="checkbox" style="float: left; display: block; margin: 0 20px 20px 0; width: 15%; line-height: 15px;"><?php
						echo $helpers['Form']->input('website_id.'.$id, $name, array('type' => 'checkbox', 'div' => false));
						?></span><?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
			
