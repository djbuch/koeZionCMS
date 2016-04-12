<?php 
$searchFields = array(
	'id' => _('Identifiant'),
	'name' => _('Libellé'),
	$helpers['Form']->input('Search.portfolio_id', _('Portfolio'), array('type' => 'select', 'datas' => $portfoliosList, 'firstElementList' => _("Sélectionnez un portfolio")))
);
$this->element('listing/commun/search', array('searchFields' => $searchFields)); 
?>