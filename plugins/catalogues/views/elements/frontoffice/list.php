<?php
$this->element(PLUGINS.DS.'catalogues/views/elements/frontoffice/coup_coeur', array('coupCoeur' => $coupCoeur), false);
$this->element(PLUGINS.DS.'catalogues/views/elements/frontoffice/recherche', null, false);
$this->element(PLUGINS.DS.'catalogues/views/elements/frontoffice/tableau', array('products' => $catalogues), false); 
?>