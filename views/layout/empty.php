<?php
if(isset($type) && !empty($type)) { header("Content-Type: '.$type.'; charset=UTF-8"); }
echo $content_for_layout;