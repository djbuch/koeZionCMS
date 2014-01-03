<?php
require_once(HELPERS.DS.'paginator_helper.php');
class PaginatorHelper extends PaginatorHelper {
	
	function paginate($totalPages, $currentPage, $adjacent = 3) {
		
		$pagination = parent::paginate($totalPages, $currentPage, $adjacent);				
		return implode('', $pagination);
	}
}
?>