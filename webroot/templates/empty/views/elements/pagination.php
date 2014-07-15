<?php if($pager['totalPages'] > 1) { ?>
	<div class="pagination_element">
		Page <?php echo $pager['currentPage']; ?> sur <?php echo $pager['totalPages']; ?>		 
		<?php 
		if($pager['totalElements'] > 1) { $plural = 's'; }
		else { $plural = ''; }
		echo ' - <i>('.$pager['totalElements'].' au total)</i>';	
		echo $helpers['Paginator']->paginate($pager['totalPages'], $pager['currentPage']); 
		?>
	</div>
<?php } ?>