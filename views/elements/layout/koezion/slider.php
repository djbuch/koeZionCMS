<?php 
if($websiteParams['slider_type'] == 1) { $this->element($websiteParams['tpl_layout'].'/slider/nivo'); }
else if($websiteParams['slider_type'] == 2) { $this->element($websiteParams['tpl_layout'].'/slider/slicebox'); }
?>