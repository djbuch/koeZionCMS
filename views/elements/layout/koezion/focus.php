<?php
if(count($focus) > 0) { 
	
	?><div class="container_omega focus"><div class="focus_line"><?php
	$cpt = 0; //Compteur total pour éviter d'avoir une div vide à la fin
	$i = 1;
	foreach($focus as $k => $v) {
		
		$separator = false; 
		$class = 'gs_3';		
		if($i == 4) { $separator = true; $class = 'gs_3 omega'; }
		?>		
		<div class="<?php echo $class; ?>"><?php echo $v['content']; ?></div>				
		<?php		
		$i++;
		$cpt++;
		if($separator && ($cpt != count($focus))) { 
			
			$i = 1;
			?></div><div class="focus_line"><?php 
		} 
	}
	?></div></div><?php
}
?>