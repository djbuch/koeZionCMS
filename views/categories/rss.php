<?php foreach($posts as $k => $v) { ?>
	<item>
		<title><?php echo $v['name']; ?></title>
        <link><?php echo Router::url('posts/view/id:'.$v['id'].'/slug:'.$v['slug'].'/prefix:'.$v['prefix'], 'html', true); ?></link>
        <description>
        	<?php 
        	/*$rssDesc = $this->controller->components['Text']->format_for_mailing(
        		array('description' => $v['short_content']),
        		'http://localhost'
        	);
        	echo $this->controller->components['Text']->convert_lt_gt($rssDesc['description']);*/
        	echo $v['page_description'];
        	?>
        </description>
        <pubDate><?php echo date(DATE_RFC2822, strtotime($v['modified'])); ?></pubDate>
	</item>	
<?php } ?>