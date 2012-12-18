<?php 
if($websiteParams['slider_type'] == 1) { $this->element('frontoffice/slider/nivo'); }
else if($websiteParams['slider_type'] == 2) { $this->element('frontoffice/slider/slicebox'); }
?>