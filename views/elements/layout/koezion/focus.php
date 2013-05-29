<?php
if(count($focus) > 0) { 
	
	?><div class="container_omega focus"><div class="focus_line"><?php
	$i = 1;
	foreach($focus as $k => $v) {
		$class = 'gs_3';		
		if($i == 4) { $class = 'gs_3 omega'; }
		$i++;
		?>		
		<div class="<?php echo $class; ?>"><?php echo $v['content']; ?></div>				
		<?php		
	}
	?></div></div><?php
}
?>