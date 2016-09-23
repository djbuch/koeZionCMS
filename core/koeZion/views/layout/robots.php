<?php
header("Content-Type: text; charset=UTF-8");
if(isset($websiteParams['robots_file']) && !empty($websiteParams['robots_file'])) { echo $websiteParams['robots_file']; }
else {
	
	echo 'User-agent: *'."\n";
	echo 'Disallow: /upload/'."\n";
	echo 'Disallow: /adm/'."\n";
	echo 'Disallow: /connexion.html'."\n";
	echo 'Sitemap : http://'.$_SERVER["HTTP_HOST"].BASE_URL.'/sitemaps.xml'."\n";
}