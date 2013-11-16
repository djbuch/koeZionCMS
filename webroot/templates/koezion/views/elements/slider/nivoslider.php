<?php 

if(isset($sliders) && count($sliders) > 0) { 
	
	$css = array(
		$websiteParams['tpl_layout'].'/css/slider/nivoslider/nivoslider_default_theme',
		$websiteParams['tpl_layout'].'/css/slider/nivoslider/nivo_slider'
	);
	echo $helpers['Html']->css($css, true);
	
	$js = array(
		$websiteParams['tpl_layout'].'/js/slider/nivoslider/jquery.nivo.slider.pack',
		$websiteParams['tpl_layout'].'/js/slider/nivoslider/nivoslider'
	);
	echo $helpers['Html']->js($js, true);
	?>
	<div class="container_alpha slider theme-default">
		<div id="slider" class="nivoSlider">
			<?php
			$captions = ''; 
			foreach($sliders as $k => $v) {
								
				require_once(LIBS.DS.'simple_html_dom.php');
				
				if(!empty($v['image'])) {
					
					$sliderImg = str_get_html($v['image']);
					$sliderImg->find('img', 0)->style = 'width: 918px;';
					
				} else { $sliderImg = ''; }
				
				if(isset($v['content']) && !empty($v['content'])) {
					
					if(!empty($v['image'])) { $sliderImg->find('img', 0)->title = '#htmlcaption-'.$v['id']; }
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