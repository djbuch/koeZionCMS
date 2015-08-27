<?php
require_once(HELPERS.DS.'paginator_parent_helper.php');
class PaginatorHelper extends PaginatorParentHelper {
	
	function paginate($totalPages, $currentPage, $adjacent = 3, $options = array()) {
		
		$pagination = parent::paginate($totalPages, $currentPage, $adjacent);				
		return implode('', $pagination);
	}
}
?>