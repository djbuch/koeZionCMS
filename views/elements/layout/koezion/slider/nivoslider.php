<?php 

if(isset($sliders) && count($sliders) > 0) { 
	
	$css = array(
		'layout/'.$websiteParams['tpl_layout'].'/slider/nivoslider/nivoslider_default_theme',
		'layout/'.$websiteParams['tpl_layout'].'/slider/nivoslider/nivo_slider'
	);
	echo $helpers['Html']->css($css, true);
	
	$js = array(
		'layout/'.$websiteParams['tpl_layout'].'/slider/nivoslider/jquery.nivo.slider.pack',
		'layout/'.$websiteParams['tpl_layout'].'/slider/nivoslider/nivoslider'
	);
	echo $helpers['Html']->js($js, true);
	?>
	<div class="container_alpha slider theme-default">
		<div id="slider" class="nivoSlider">
			<?php
			$captions = ''; 
			foreach($sliders as $k => $v) {
								
				require_once(LIBS.DS.'simple_html_dom.php');
				
				$sliderImg = str_get_html($v['image']);
				$sliderImg->find('img', 0)->style = 'width: 918px;';
				
				if(isset($v['content']) && !empty($v['content'])) {
					
					$sliderImg->find('img', 0)->title = '#htmlcaption-'.$v['id'];
					$captions .= '<div id="htmlcaption-'.$v['id'].'" class="nivo-html-caption">'.$v['content'].'</div>'."\n";
				}
				
				echo $sliderImg;
				echo "\n";			
			}
			?>
		</div>		
		<?php echo $captions; ?>
		<div class="loader"></div>
	</div>	
<?php } ?>