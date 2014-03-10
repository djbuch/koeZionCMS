<?php 
/*
Format du tableau utilisé pour la génération du menu sur le clic droit
Reprendre cette partie car le code n'est pas optimisé
Dans un besoin de rapidité il a été monté rapidement pour être vite fonctionnel
Prévoir peut être de la récursivité
$jsContextMenu = array(
	"edit" => array("name" => "Edit", "icon" => "edit", 'url' => 'test'),
    "cut" => array("name" => "Cut", "icon" => "cut"),
    "sep1" => "---------",
    "quit" => array("name" => "Quit", "icon" => "quit"),
    "sep2" => "---------",
    "fold1" => array(
    	"name" => "Sub group", 
        "items" => array(
        	"fold1-key1" => array("name" => "Foo bar"),
            "fold2" => array(
            	"name" => "Sub group 2", 
                "items" => array(
                	"fold2-key1" => array("name" => "alpha"),
                    "fold2-key2" => array("name" => "bravo"),
                    "fold2-key3" => array("name" => "charlie")
				)
			),
            "fold1-key3" => array("name" => "delta")
		)
	),
    "fold1a" => array(
    	"name" => "Other group", 
        "items" => array(
        	"fold1a-key1" => array("html" => '<a href="http://www.google.fr" >TEST LINK</a>', "type" => "html"),
            "fold1a-key2" => array("html" => '<a href="http://www.google.fr" >TEST LINK</a>', "type" => "html"),
            "fold1a-key3" => array("html" => '<a href="http://www.google.fr" >TEST LINK</a>', "type" => "html")
		)
	)
);
*/
$jsContextMenu = array();
?>
<div id="left">
	<span class="menu">Menu</span>
	<ul>
		<?php 
		$cpt = 0;
		foreach($leftMenus as $k => $v) {
			
			if(count($v['menus'])) {
				?>
				<li>
					<?php 
					if(!empty($v['libelle'])) { ?><a><?php echo $v['libelle']; ?></a><?php } 
					$folderIdA = 'fold'.$cpt;
					$cpt++;
					$jsContextMenu[$folderIdA]['name'] = $v['libelle']; 
					?>
					<ul>
						<?php 
						foreach($v['menus'] as $key => $leftMenus) {

							if(!is_int($key)) { 
								?>
								<li>
									<?php 
									if(!empty($key)) { ?><a class="under_section"><?php echo $key; ?></a><?php } 
									$folderIdB = 'fold'.$cpt;
									$cpt++;
									$jsContextMenu[$folderIdA]['items'][$folderIdB]['name'] = $key;
									?>
									<ul>
										<?php 
										foreach($leftMenus as $leftMenu) {

											$folderIdC = 'fold'.$cpt;
											$cpt++;
											
											$action = !empty($leftMenu['action_name']) ? $leftMenu['action_name'] : 'index';
											$link = '<a href="'.Router::url('backoffice/'.$leftMenu['controller_name'].'/'.$action).'">'.$leftMenu['name'].'</a>'; 
											?><li><?php echo $link; ?></li><?php
											$jsContextMenu[$folderIdA]['items'][$folderIdB]['items'][$folderIdC] = array('type' => 'html', 'html' => $link);
										}
										?>
									</ul>
								</li>
								<?php 								
							} else {

								$folderIdD = 'fold'.$cpt;
								$cpt++;
							
								$action = !empty($leftMenus['action_name']) ? $leftMenus['action_name'] : 'index';		
								$link = '<a href="'.Router::url('backoffice/'.$leftMenus['controller_name'].'/'.$action).'">'.$leftMenus['name'].'</a>';				
								?><li><?php echo $link; ?></li><?php								
								$jsContextMenu[$folderIdA]['items'][$folderIdD] = array('type' => 'html', 'html' => $link);
							} 
						} 
						?>
					</ul>
				</li>
				<?php 
			}
		} 
		?>
	</ul>
	
	<script type="text/javascript">	
	$(document).ready(function() { 
	   	   
	   var screenSurface = $.get_surface_ecran();
	   var mainHeight = screenSurface.windowHeight - 52;
	   $('#wrapper #right #main').css('height', mainHeight);
	   
	    /**************************************************
	     * Context-Menu with Sub-Menu
	     **************************************************/
	    $.contextMenu({
	        selector: '#wrapper #right #main',
	        items: <?php echo json_encode($jsContextMenu); ?>
	    });
	});
	</script>		
	
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