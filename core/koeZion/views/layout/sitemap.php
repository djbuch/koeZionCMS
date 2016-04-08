<?php
header("Content-Type: text/xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";
foreach($sitemaps as $type => $values) {	
	
	switch($type) {
		
		//Urls catégories
		case "categories":
			
			foreach($values as $k => $categorie) {
				
				if(!empty($categorie['redirect_to'])) { $url = Router::url($categorie['redirect_to']); } 
				else { $url = Router::url('categories/view/id:'.$categorie['id'].'/slug:'.$categorie['slug']); }
				
				echo '<url>'."\n";	
				echo "\t".'<loc>http://'.$_SERVER["HTTP_HOST"].$url.'</loc>'."\n";
				//<lastmod>date de mise en place de la page au format année-mois-jour</lastmod>
				//<changefreq>fréquence de mise à jour</changefreq>
				//<priority>importance de l'URL</priority>
				echo '</url>'."\n";
			}
			
		break;
		
		//Urls articles
		case "posts":
			
			foreach($values as $k => $post) {
			
				echo '<url>'."\n";
				echo "\t".'<loc>http://'.$_SERVER["HTTP_HOST"].Router::url('posts/view/id:'.$post['id'].'/slug:'.$post['slug'].'/prefix:'.$post['prefix']).'</loc>'."\n";
				//<lastmod>date de mise en place de la page au format année-mois-jour</lastmod>
				//<changefreq>fréquence de mise à jour</changefreq>
				//<priority>importance de l'URL</priority>
				echo '</url>'."\n";
			}
			
		break;
		
		//Urls types d'articles
		case "postsTypes":
			
			foreach($values as $k => $postsType) {
			
				echo '<url>'."\n";
				echo "\t".'<loc>http://'.$_SERVER["HTTP_HOST"].Router::url('posts/listing').'?typepost='.$postsType['id'].'</loc>'."\n";
				//<lastmod>date de mise en place de la page au format année-mois-jour</lastmod>
				//<changefreq>fréquence de mise à jour</changefreq>
				//<priority>importance de l'URL</priority>
				echo '</url>'."\n";
			}
			
		break;
		
		//Urls rédacteurs
		case "writers":
			
			foreach($values as $k => $writer) {
			
				echo '<url>'."\n";
				echo "\t".'<loc>http://'.$_SERVER["HTTP_HOST"].Router::url('posts/listing').'?writer='.$writer['id'].'</loc>'."\n";
				//<lastmod>date de mise en place de la page au format année-mois-jour</lastmod>
				//<changefreq>fréquence de mise à jour</changefreq>
				//<priority>importance de l'URL</priority>
				echo '</url>'."\n";
			}
			
		break;
		
		//Urls dates de publication
		case "publicationDates":
			
			foreach($values as $k => $publicationDate) {
			
				$postDate = $this->vars['components']['Text']->date_sql_to_human($publicationDate['publication_date']);				
				echo '<url>'."\n";
				echo "\t".'<loc>http://'.$_SERVER["HTTP_HOST"].Router::url('posts/listing').'?date='.$postDate['sql'].'</loc>'."\n";
				//<lastmod>date de mise en place de la page au format année-mois-jour</lastmod>
				//<changefreq>fréquence de mise à jour</changefreq>
				//<priority>importance de l'URL</priority>
				echo '</url>'."\n";
			}
			
		break;	
		
		//More
		case "moreLinks":
			
			foreach($values as $k => $moreLink) {
				
				echo '<url>'."\n";	
				echo "\t".'<loc>http://'.$_SERVER["HTTP_HOST"].$moreLink['url'].'</loc>'."\n";
				//<lastmod>date de mise en place de la page au format année-mois-jour</lastmod>
				//<changefreq>fréquence de mise à jour</changefreq>
				//<priority>importance de l'URL</priority>
				echo '</url>'."\n";
			}
			
		break;	
	}
}
echo '</urlset>';