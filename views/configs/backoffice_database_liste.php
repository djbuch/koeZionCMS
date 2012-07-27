<div class="section">
	<?php echo $helpers['Form']->create(array('id' => 'ConfigDatabase', 'action' => Router::url('backoffice/configs/database_liste'), 'method' => 'post')); ?>
		<div class="half">
			<div class="box">
				<div class="title"><h2><?php echo _("Configuration de la base de données (locale)"); ?></h2></div>
				<div class="content nopadding">
					<?php 
					echo $helpers['Form']->input('localhost.host', _('Adresse du serveur'), array('tooltip' => _("Indiquez ici l'adresse du serveur mysql (par exemple localhost)")));			 
					echo $helpers['Form']->input('localhost.database', _('Nom de la base de données'), array('tooltip' => _("Indiquez ici le nom de la base de données")));			 
					echo $helpers['Form']->input('localhost.login', _('Identifiant'), array('tooltip' => _("Indiquez ici l'identifiant de connexion à la base de données")));			 
					echo $helpers['Form']->input('localhost.password', _('Mot de passe'), array('tooltip' => _("indiquez ici le mot de passe de connexion à la base de données"), 'type' => 'password'));			 
					echo $helpers['Form']->input('localhost.prefix', _('Préfix des tables'), array('tooltip' => _("Si vos tables sont préfixées indiquez ici le préfixe (par exemple blog_)")));	
					?>
				</div>
			</div>			
		</div>		
		<div class="half">
			<div class="box">
				<div class="title"><h2><?php echo _("Configuration de la base de données (production)"); ?></h2></div>
				<div class="content nopadding">
					<?php 
					echo $helpers['Form']->input('online.host', _('Adresse du serveur'), array('tooltip' => _("Indiquez ici l'adresse du serveur mysql (par exemple localhost)")));			 
					echo $helpers['Form']->input('online.database', _('Nom de la base de données'), array('tooltip' => _("Indiquez ici le nom de la base de données")));			 
					echo $helpers['Form']->input('online.login', _('Identifiant'), array('tooltip' => _("Indiquez ici l'identifiant de connexion à la base de données")));			 
					echo $helpers['Form']->input('online.password', _('Mot de passe'), array('tooltip' => _("indiquez ici le mot de passe de connexion à la base de données"), 'type' => 'password'));			 
					echo $helpers['Form']->input('online.prefix', _('Préfix des tables'), array('tooltip' => _("Si vos tables sont préfixées indiquez ici le préfixe (par exemple blog_)")));	
					?>
				</div>
			</div>	
		</div>
	<?php echo $helpers['Form']->end(true); ?>
</div>