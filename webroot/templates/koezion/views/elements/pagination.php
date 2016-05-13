<?php if($pager['totalPages'] > 1) { ?>
	<div class="pagination_element">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<ul class="pagination pagination-sm">
					<li class="pages pull-left">
						<?php 
						echo _('Page').' '.$pager['currentPage'].' '._('sur').' '.$pager['totalPages'];				
						if($pager['totalElements'] > 1) { $plural = 's'; }
						else { $plural = ''; }
						echo ' - <i>('.$pager['totalElements'].' au total)</i>';
						?>
					</li>	
					<li class="disabled pull-left">&nbsp;</li>
					<?php echo $helpers['Paginator']->paginate($pager['totalPages'], $pager['currentPage']); ?>
				</ul>
			</div>
		</div>
	</div>
<?php } ?>