<?php if(isset($focus) && count($focus) > 0) { ?>
	<div id="focus_element">
		<div class="container">
			<div class="row">			
				<?php 
				$cpt = 1;
				foreach($focus as $v) { 
				
					?>
					<div class="item col-lg-3">
						<img class="img-rounded" src="<?php echo $v['details_img']; ?>" alt="<?php echo $v['details_title']; ?>" />
						<h2><?php echo $v['details_title']; ?></h2>
						<div class="focus_content"><?php echo $v['details_content']; ?></div>						
					</div>
					<?php
					if($cpt == 4) { ?></div><div class="row mb30"><?php }
					$cpt++;
				} 
				?>
			</div>
		</div>
	</div>
<?php } ?>