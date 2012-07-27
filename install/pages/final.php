<div id="right">		
	<div id="main">		
		<div class="box">
			<div class="title">
				<h2>FIN DE L'INSTALLATION</h2>
			</div>			
			<div class="content nopadding">	
				<div class="system succes">
					<b>Bravo!!!</b><br />
					Vous venez de terminer l'installation du CMS koéZion<br />					
				</div>
				<div class="system warning">
					<b>Accès administrateur</b><br />
					Pour vous connecter au backoffice utilisez l'adresse www.votrenomdedomaine.com/adm <br />
					Le login par défaut est <b>admin</b><br />
					Le mot de passe par défaut est <b>admin</b><br />
					<i>Pensez à le changer par un mot de passe que vous seul pourrez retrouver</i>
					
				</div>
				<div class="system error">
					<b>ATTENTION</b><br />
					Veillez à supprimer le dossier install qui se trouve à la racine de votre serveur d'hébergement
				</div>
				<?php require_once(ROOT.DS.'core'.DS.'Koezion'.DS.'router.php'); ?>						
				<div class="row" style="overflow: hidden;">
					<a href="<?php echo Router::url('/'); ?>" target="_blank"><button class="medium grey" type="submit" style="float: right;"><span>Accéder à la page d'accueil de votre site koéZionCMS</span></button></a>
					<a href="<?php echo Router::url('/adm'); ?>" target="_blank"><button class="medium grey" type="submit" style="float: right;"><span>Accéder à l'espace d'administration de votre site koéZionCMS</span></button></a>						
				</div>				
			</div>
		</div>	
	</div>
</div>