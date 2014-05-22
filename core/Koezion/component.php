<?php
/**
 * Classe mère des composants
 * 
 * PHP versions 4 and 5
 *
 * KoéZionCMS : PHP OPENSOURCE CMS (http://www.koezion-cms.com)
 * Copyright KoéZionCMS
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	KoéZionCMS
 * @link        http://www.koezion-cms.com
 */
class Component extends Object {

	function __construct($controller = null) { 
		
		if(isset($controller)) { $this->controller = $controller; } 
	}
}