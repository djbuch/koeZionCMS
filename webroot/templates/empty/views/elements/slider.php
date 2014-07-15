<?php if(isset($sliders) && count($sliders) > 0) { ?>
	<ul class="slider_element">
		<?php
		$captions = ''; 
		foreach($sliders as $k => $v) {
								
			if(!empty($v['image'])) {
				
				?><li><?php 
					echo $v['image'];
					if(isset($v['content']) && !empty($v['content'])) { echo '<div class="slider_caption">'.$v['content'].'</div>'; }
				?></li><?php
			}		
		}
		?>
	</ul>
<?php } ?>