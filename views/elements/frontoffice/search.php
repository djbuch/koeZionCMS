<div class="search">
	<?php
	$formOptions = array('id' => 'Search', 'action' => Router::url('searches/index'), 'method' => 'post');
	echo $helpers['Form']->create($formOptions);
	$commonOptions = array('label' => false, 'div' => false, 'displayError' => false);
	echo $helpers['Form']->input('q', _('Rechercher'), am($commonOptions, array("value" => _('Mots clés'), "title" => _('Mots clés'))));
	echo $helpers['Form']->input('submit', _('Rechercher'), array('type' => 'submit', 'label' => false, 'div' => false, 'displayError' => false));		
	echo $helpers['Form']->end();
	?>
</div>