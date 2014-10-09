<div class="search">
	<?php
	$formOptions = array('id' => 'Search', 'action' => Router::url('searchs/rechercher'), 'method' => 'post');
	echo $helpers['Form']->create($formOptions);
	?>
	<div class="input-group">
		<?php 
		$commonOptions = array('onlyInput' => true);
		echo $helpers['Form']->input('q', '', am($commonOptions, array("class" => 'form-control search', "placeholder" => _('Rechercher'))));
		?>
		<span class="input-group-btn">
			<?php echo $helpers['Form']->input('submit', '<i class="fa fa-search"></i>', array('type' => 'button', 'buttonType' => 'submit', 'onlyInput' => true, 'class' => 'btn btn-default')); ?>
		</span>
	</div>
	<?php echo $helpers['Form']->end(); ?>
</div>