<?php 
if(isset($sliders) && count($sliders) > 0) { 
	
	$css = array(
		$websiteParams['tpl_layout'].'/css/slider/slicebox/slicebox',
		$websiteParams['tpl_layout'].'/css/slider/slicebox/custom'
	);		
	echo $helpers['Html']->css($css, true);
	
	$js = array(
			$websiteParams['tpl_layout'].'/js/slider/slicebox/modernizr.custom.46884',
			$websiteParams['tpl_layout'].'/js/slider/slicebox/jquery.slicebox',
			$websiteParams['tpl_layout'].'/js/slider/slicebox/slicebox'
	);
	echo $helpers['Html']->js($js, true);
	?>
	<div class="container_alpha slicebox">			
		<ul id="sb-slider" class="sb-slider">
			<?php 
			
			$nav = '<div id="nav-dots" class="nav-dots">';
			$cpt = 0;
			foreach($sliders as $k => $v) {	

				if(!empty($v['image'])) {
					
					require_once(LIBS.DS.'simple_html_dom.php');					
					$sliderImg = str_get_html($v['image']);
					$sliderImg->find('img', 0)->style = 'width: 918px;';					
					?>
					<li>
						<?php 
						echo $sliderImg;
						if(isset($v['content']) && !empty($v['content'])) { 
							?><div class="sb-description"><?php echo $v['content']; ?></div><?php 
						} 
						?>						
					</li>				
					<?php 
					if($cpt == 0) { $nav .= '<span class="nav-dot-current"></span>'; }
					else { $nav .= '<span></span>'; } 
					$cpt++;
				}
			}
			$nav .= '</div>';
			?>
		</ul>		
		<?php echo $nav; ?>
	</div>
<?php } ?>		