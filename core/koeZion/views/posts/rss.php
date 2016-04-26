<?php
foreach($posts as $post) { 

	$url = Router::url('posts/view/id:'.$post['id'].'/slug:'.$post['slug'].'/prefix:'.$post['prefix']);
	if(!empty($post['redirect_to'])) { $url = $post['redirect_to']; }
	?>
	<item>
		<title><?php echo $post['name']; ?></title>
        <link><?php echo $url; ?></link>
        <description><?php  echo $post['page_description']; ?></description>
        <guid isPermaLink="true"><?php echo $post['id']; ?></guid>        
        <pubDate><?php echo date(DATE_RFC2822, strtotime($post['modified'])); ?></pubDate>
		<source url="<?php echo $rss_for_layout['link']; ?>"><?php echo $rss_for_layout['title']." - ".$this->vars['websiteParams']['name']; ?></source>        	
	</item>	
	<?php 
} 
?>