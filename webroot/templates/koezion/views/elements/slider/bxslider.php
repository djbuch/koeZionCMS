<?php 
//THKS TO AA
if(isset($sliders) && count($sliders) > 0) { 
	
	$css = array(
		$websiteParams['tpl_layout'].'/css/slider/bxslider/jquery.bxslider'
	);		
	echo $helpers['Html']->css($css, true);
	
	$js = array(
			$websiteParams['tpl_layout'].'/js/slider/bxslider/jquery.bxslider.min',
			$websiteParams['tpl_layout'].'/js/slider/bxslider/jquery.easing.1.3',
			$websiteParams['tpl_layout'].'/js/slider/bxslider/jquery.fitvids',
			$websiteParams['tpl_layout'].'/js/slider/bxslider/bxslider'
	);
	echo $helpers['Html']->js($js, true);
	?>
	<div class="container_alpha slider">			
		<ul class="bxslider">
			<?php 
			foreach($sliders as $k => $v) {	

				require_once(LIBS.DS.'simple_html_dom.php');
				
				if(!empty($v['image'])) {
				
					$sliderImg = str_get_html($v['image']);
					$sliderImg->find('img', 0)->style = 'width: 918px;';
				
				} else { $sliderImg = ''; }								
				?><li><?php 
					echo $sliderImg;
					if(isset($v['content']) && !empty($v['content'])) { 
						echo '<div class="bx-caption">'.$v['content'].'</div>';
					} 
				?></li><?php 	
			}	
			?>
		</ul>
	</div>
<?php } ?>		