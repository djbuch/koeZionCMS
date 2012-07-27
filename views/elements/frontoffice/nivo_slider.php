<?php if(isset($sliders) && count($sliders) > 0) { ?>
	<div class="container_alpha slider">
		<div id="slider" class="nivoSlider">
			<?php
			$captions = ''; 
			foreach($sliders as $k => $v) {
								
				require_once(LIBS.DS.'simple_html_dom.php');				
				/*$html = str_get_html($v['content']);
				$img = $html->find('#slider_img img', 0)->src;
				$txt = $html->find('#slider_txt', 0)->innertext;*/
				
				$html = str_get_html($v['image']);
				if(!empty($html)) {  $img = $html->find('img', 0)->src; } else { $img = ''; }			
				$title = '';
				
				//if(isset($txt) && !empty($txt)) {
				if(isset($v['content']) && !empty($v['content'])) {
					
					$title = 'title="#htmlcaption'.$v['id'].'" ';
					//$captions .= '<div id="htmlcaption'.$v['id'].'" class="nivo-html-caption">'.$txt.'</div>'."\n";
					$captions .= '<div id="htmlcaption'.$v['id'].'" class="nivo-html-caption">'.$v['content'].'</div>'."\n";
				}
				
				//On force la taille de l'image pour éviter les débordements
				if(!empty($img)) {
					
					$httpHost = $_SERVER["HTTP_HOST"];
					if($httpHost == 'localhost' || $httpHost == '127.0.0.1') { $img = '/www.koezion.com'.$img; }
					?><img src="<?php echo $img; ?>" alt="" <?php echo $title; ?> style="width: 918px;" /><?php
				}
				echo "\n";			
			}
			?>
		</div>		
		<?php echo $captions; ?>
		<div class="loader"></div>
	</div>
<?php } ?>