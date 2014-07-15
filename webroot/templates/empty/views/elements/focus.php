<?php
if(count($focus) > 0) { 
	
	?><ul class="focus_element"><?php
		foreach($focus as $k => $v) { ?><li><?php echo $v['content']; ?></li><?php }
	?></ul><?php
}
?>