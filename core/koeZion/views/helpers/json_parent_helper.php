<?php
class JsonParentHelper extends Helper {  
	
	function encode($tabJson) {
		
		if(!function_exists('json_encode')) {
		
			//utilisation d'un package PEAR
     		require_once(PEAR.DS.'JSON.php');
     		$JSON = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
     		$textJson = $JSON->encode($tabJson); 
     		unset($JSON);
     		
    	} else {
     	
    		$textJson = json_encode($tabJson);
    	}	
	
		return $textJson;
	}
	
	function decode($textJson) {

		if(!function_exists('json_decode')) {
		
			//utilisation d'un package PEAR
     		require_once(PEAR.DS.'JSON.php');
     		$JSON = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
     		$tabJson = $JSON->decode($textJson); 
     		unset($JSON);
     		
    	} else {
     	
    		$tabJson = json_decode($textJson, true);
    	}
    	
    	return $tabJson;
	}
}