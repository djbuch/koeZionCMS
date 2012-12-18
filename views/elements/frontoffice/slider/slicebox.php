<?php 
if(isset($sliders) && count($sliders) > 0) { 
	
	$css = array(
		$websiteParams['tpl_layout'].'/slider/slicebox',
		$websiteParams['tpl_layout'].'/slider/custom'
	);		
	echo $helpers['Html']->css($css, true);
	
	$js = array(
			$websiteParams['tpl_layout'].'/slider/modernizr.custom.46884',
			$websiteParams['tpl_layout'].'/slider/jquery.slicebox'
	);
	echo $helpers['Html']->js($js);
	?>
	<div class="container_alpha slicebox">
			
		<ul id="sb-slider" class="sb-slider">
			<?php 
			//require_once(LIBS.DS.'simple_html_dom.php');
			
			$nav = '<div id="nav-dots" class="nav-dots">';
			$cpt = 0;
			foreach($sliders as $k => $v) {					
				?>
				<li>
					<?php 
					echo $v['image'];
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
			$nav .= '</div>';
			?>
		</ul>
		
		<?php echo $nav; ?>
	</div>

	<script type="text/javascript">
		$(function() {
	
			var Page = (function() {
	
				var $navArrows = $('#nav-arrows').hide(),
					$navDots = $('#nav-dots').hide(),
					$nav = $navDots.children( 'span' ),
					$shadow = $('#shadow').hide(),
					slicebox = $('#sb-slider').slicebox( {
						onReady : function() {
	
							$navArrows.show();
							$navDots.show();
							$shadow.show();
	
						},
						onBeforeChange : function( pos ) {
	
							$nav.removeClass('nav-dot-current');
							$nav.eq(pos).addClass('nav-dot-current');	
						},
					orientation : 'r',
					cuboidsRandom : true,
					disperseFactor : 30,
					autoplay : true,
					interval : 4500
					} ),
					
					init = function() { initEvents(); },
					initEvents = function() {
	
						// add navigation events
						$navArrows.children(':first').on('click', function() {
	
							slicebox.next();
							return false;	
						} );
	
						$navArrows.children(':last').on('click', function() {
							
							slicebox.previous();
							return false;	
						} );
	
						$nav.each(function(i) {
						
							$(this).on( 'click', function( event ) {
								
								var $dot = $( this );
								
								if(!slicebox.isActive()) {
	
									$nav.removeClass('nav-dot-current');
									$dot.addClass('nav-dot-current');								
								}
								
								slicebox.jump(i + 1);
								return false;							
							});
							
						} );					
						
					
						$('.slicebox').hover(
							function() { slicebox.pause(); }, 
							function() { slicebox.play(); }
						);
	
					};
	
					return { init : init };
	
			})();
	
			Page.init();
	
		});
	</script>	
<?php } ?>		