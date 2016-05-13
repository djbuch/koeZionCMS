<?php
//Si la page contact est activée et que l'on est sur la bonne page
if($websiteParams['contact_map_activ'] && $websiteParams['contact_map_page'] == $category['id']) {
	
	$contactMapAddress 	= $websiteParams['contact_map_address'];
	$contactMapLat 		= $websiteParams['contact_map_lat'];
	$contactMapLng 		= $websiteParams['contact_map_lng'];	
	?>
	<div id="contact_map_element">
    	<div data-address="<?php echo $contactMapAddress; ?>" data-lat="<?php echo $contactMapLat; ?>" data-lng="<?php echo $contactMapLng; ?>" class="map"></div>
	</div>
	<?php 
}