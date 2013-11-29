<div id="left">
	<ul>
		<?php 
		foreach($leftMenus as $k => $v) {

			if(count($v['menus'])) {		
					
				?>
				<li>
					<?php if(!empty($v['libelle'])) { ?><a><?php echo $v['libelle']; ?></a><?php } ?>
					<ul>
						<?php 
						foreach($v['menus'] as $leftMenus) {

							$action = !empty($leftMenus['action_name']) ? $leftMenus['action_name'] : 'index';						
							?><li><a href="<?php echo Router::url('backoffice/'.$leftMenus['controller_name'].'/'.$action); ?>"><?php echo $leftMenus['name']; ?></a></li><?php 
						} 
						?>
					</ul>
				</li>
				<?php 
			}
		} 
		?>
	</ul>
	
	<?php /* ?>
	<div id="social">
		<?php //PARTAGE FACEBOOK ?>
		<div class="facebook">
			<div id="fb-root"></div>
			<script>
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
			</script>	
			<div class="fb-like" data-href="https://www.facebook.com/koezioncms" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
		</div>
		<div class="twitter">
			<?php //PARTAGE TWITTER ?>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://twitter.com/KoeZionCMS" data-text="KoéZionCMS - Solution de Gestion de Contenu - CMS Open Source PHP" data-lang="fr" data-dnt="true">Tweeter</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>
	</div>
	<?php */ ?>
	<div id="credits">&#169; Copyright 2010 - <?php echo date('Y'); ?> koéZionCMS</div>
</div>