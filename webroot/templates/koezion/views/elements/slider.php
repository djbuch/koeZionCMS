<?php 
if($websiteParams['slider_type'] == 1) { $this->element('slider/nivoslider'); }
else if($websiteParams['slider_type'] == 2) { $this->element('slider/slicebox'); }
else if($websiteParams['slider_type'] == 3) { $this->element('slider/bxslider'); }
?>