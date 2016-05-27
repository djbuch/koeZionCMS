<?php if(isset($focus) && count($focus) > 0) { ?>
	<div id="focus_element">
		<div class="container">
			<div class="row">			
				<?php 
				$cpt = 1;
				if(count($focus) == 3) { $focusCss = 'col-lg-4'; $cptLimit = 3; }
				else if(count($focus) == 4) { $focusCss = 'col-lg-3'; $cptLimit = 4; }
				foreach($focus as $v) {
					?>
					<div class="item <?php echo $focusCss; ?>">
						<?php if(!empty($v['details_img'])) { ?><img class="img-rounded" src="<?php echo $v['details_img']; ?>" alt="<?php echo $v['details_title']; ?>" /><?php } ?>
						<?php if(!empty($v['details_title'])) { ?><h2><?php echo $v['details_title']; ?></h2><?php } ?>
						<?php if(!empty($v['details_content'])) { ?><div class="focus_content"><?php echo $v['details_content']; ?></div><?php } ?>
					</div>
					<?php
					if($cpt == $cptLimit) { 
						
						?></div><div class="row mb30"><?php
						$cpt = 0; 
					}
					$cpt++;
				} 
				?>
			</div>
		</div>
	</div>
<?php } ?>