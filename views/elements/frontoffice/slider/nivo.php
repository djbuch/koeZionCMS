<?php if(isset($sliders) && count($sliders) > 0) { ?>
	<div class="container_alpha slider">
		<div id="slider" class="nivoSlider">
			<?php
			$captions = ''; 
			foreach($sliders as $k => $v) {
								
				require_once(LIBS.DS.'simple_html_dom.php');
				
				$html = str_get_html($v['image']);
				if(!empty($html)) {  $img = $html->find('img', 0)->src; } else { $img = ''; }			
				$title = '';
				
				if(isset($v['content']) && !empty($v['content'])) {
					
					$title = 'title="#htmlcaption-'.$v['id'].'" ';
					$captions .= '<div id="htmlcaption-'.$v['id'].'" class="nivo-html-caption">'.$v['content'].'</div>'."\n";
				} else {
					
					$title = 'title="#htmlcaption-'.$v['id'].'" ';
					$captions .= '<div id="htmlcaption-'.$v['id'].'" class="nivo-html-caption empty">&nbsp;</div>'."\n";
				}
				
				//On force la taille de l'image pour éviter les débordements
				if(!empty($img)) {
					
					?><img src="<?php echo $img; ?>" alt="" <?php echo $title; ?> style="width: 918px;height:350px" /><?php
				}
				//echo $v['image'];
				echo "\n";			
			}
			?>
		</div>		
		<?php echo $captions; ?>
		<div class="loader"></div>
	</div>
<?php } ?>