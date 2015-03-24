<div class="smarttabs nobottom">
	<ul class="anchor">
		<li><a href="#general"><?php echo _("Général"); ?></a></li>
		<li><a href="#websites_users"><?php echo _("Sites accessibles"); ?></a></li>
	</ul>
	<div id="general">
		<div class="content nopadding">
			<?php 
			echo $helpers['Form']->input('name', _("Libellé du groupe"), array('compulsory' => true, 'tooltip' => _("Indiquez le libellé du groupe d'utilisateurs")));
			$rolesId = array(
				1 => _("Administrateur backoffice"), 
				2 => _("Utilisateur backoffice"), 
				3 => _('Utilisateur frontoffice')
			);
			echo $helpers['Form']->input('role_id', _("Type de profil"), array('compulsory' => true, 'type' => 'select', 'datas' => $rolesId, 'firstElementList' => _("Sélectionnez un type de profil"), 'tooltip' => _("Indiquez le type de profil de ce groupe d'utilisateurs")));			
			echo $helpers['Form']->input('default_home', _("Url de la home page"), array('tooltip' => _("Indiquez l'url de la home page (backoffice/controller/action/params), laissez vide pour ne pas modifier l'url par défaut")));
			echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce groupe d'utilisateurs")));			
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