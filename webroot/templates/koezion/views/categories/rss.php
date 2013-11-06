<?php foreach($posts as $k => $v) { ?>
	<item>
		<title><?php echo $v['name']; ?></title>
        <link><?php echo Router::url('posts/view/id:'.$v['id'].'/slug:'.$v['slug'].'/prefix:'.$v['prefix'], 'html', true); ?></link>
        <description><?php  echo $v['page_description']; ?></description>
        <guid isPermaLink="true"><?php echo $v['id']; ?></guid>        
        <pubDate><?php echo date(DATE_RFC2822, strtotime($v['modified'])); ?></pubDate>
		<source url="<?php echo $rss_for_layout['link']; ?>"><?php echo $rss_for_layout['title']." - ".$this->vars['websiteParams']['name']; ?></source>        	
	</item>	
<?php } ?>