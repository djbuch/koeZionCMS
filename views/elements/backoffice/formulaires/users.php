<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#websites_users"><?php echo _("Sites accessibles"); ?></a></li>
	</ul>
	<div id="general">
		<div class="content nopadding">
			<?php 
			//$typeList = array('admin' => "Administrateur", 'website_admin' => "Administrateur de site", 'user' => 'Utilisateur');
			//echo $helpers['Form']->input('role', "<i>(*)</i> Type d'utilisateur", array('type' => 'select', 'datas' => $typeList, 'firstElementList' => _("Sélectionnez un type"), 'tooltip' => "Indiquez le type de cet utilisateur"));			
			echo $helpers['Form']->input('users_group_id', "<i>(*)</i> Groupe d'utilisateurs", array('type' => 'select', 'datas' => $usersGroupList, 'firstElementList' => _("Sélectionnez un groupe"), 'tooltip' => "Indiquez le groupe de cet utilisateur"));			
			echo $helpers['Form']->input('name', '<i>(*)</i> Nom', array('tooltip' => "Indiquez le nom de l'utilisateur"));
			echo $helpers['Form']->input('second_name', 'Complément nom', array('tooltip' => "Indiquez un complément pour le nom de l'utilisateur"));
			echo $helpers['Form']->input('login', '<i>(*)</i> Identifiant', array('tooltip' => "Indiquez l'identifiant de l'utilisateur (Généralement un email)"));
			echo $helpers['Form']->input('password', '<i>(*)</i> Mot de passe', array('tooltip' => "Indiquez le mot de passe de l'utilisateur", 'type' => 'password'));
			echo $helpers['Form']->input('email', '<i>(*)</i> Email de contact', array('tooltip' => "Indiquez l'email de contact de cet utilisateur (peut être identique au login si celui-ci est un email)"));
			echo $helpers['Form']->input('online', 'Actif', array('type' => 'checkbox', 'tooltip' => "Cochez cette case pour valider cet utilisateur"));
			?>
		</div>
	</div>
	<div id="websites_users">
		<div class="content nopadding">
			<div class="row" style="overflow:hidden;">
				<label>
					<?php echo $helpers['Html']->img('/backoffice/tooltip.png', array("original-title" => "Cochez le (ou les) site(s) que cet utilisateur peut administrer", "class" => "tip-w", "style" => "float: left; margin-right: 5px; cursor: pointer;", "alt" => "tooltip")); ?>
					Site(s) administrable(s)
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
			
