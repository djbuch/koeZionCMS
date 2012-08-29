<?php 
$category['id'] = 100;
$this->element('frontoffice/catalogues/'.$category['id'].'/coup_coeur', array('coupCoeur' => $coupCoeur)); 
$this->element('frontoffice/catalogues/'.$category['id'].'/recherche'); 
$this->element('frontoffice/catalogues/'.$category['id'].'/tableau', array('products' => $catalogues)); 
?>