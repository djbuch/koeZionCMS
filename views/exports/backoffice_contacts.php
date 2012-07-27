<?php 
//header("Content-Disposition: attachement; filename=contacts.csv");
foreach($contacts as $k => $v) { 
	echo '"'.utf8_decode($v['name']).'";"'.utf8_decode($v['email']).'"'."\n"; 
}