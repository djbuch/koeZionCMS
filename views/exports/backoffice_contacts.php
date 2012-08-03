<?php 
//header("Content-Disposition: attachement; filename=contacts.csv");
foreach($contacts as $k => $v) { 	
	
	echo 
		'"'.utf8_decode($v['id']).'";'.
		'"'.utf8_decode($v['created']).'";'.
		'"'.utf8_decode($v['name']).'";'.
		'"'.utf8_decode($v['phone']).'";'.
		'"'.utf8_decode($v['email']).'"'.
		"\n"; 
}