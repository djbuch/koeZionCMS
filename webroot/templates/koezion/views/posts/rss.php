<?php
foreach($posts as $k => $v) { 

	$url = Router::url('posts/view/id:'.$v['id'].'/slug:'.$v['slug'].'/prefix:'.$v['prefix'], 'html', true);
	if(!empty($v['redirect_to'])) { $url = $v['redirect_to']; }
	?>
	<item>
		<title><?php echo $v['name']; ?></title>
        <link><?php echo $url; ?></link>
        <description><?php  echo $v['page_description']; ?></description>
        <guid isPermaLink="true"><?php echo $v['id']; ?></guid>        
        <pubDate><?php echo date(DATE_RFC2822, strtotime($v['modified'])); ?></pubDate>
		<source url="<?php echo $rss_for_layout['link']; ?>"><?php echo $rss_for_layout['title']." - ".$this->vars['websiteParams']['name']; ?></source>        	
	</item>	
	<?php 
} 
?>