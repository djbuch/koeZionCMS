<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<rss version="2.0">
	<channel>
        <title><?php echo $rss_for_layout['title']." - ".$this->vars['websiteParams']['name']; ?></title>
        <link><?php echo $this->vars['websiteParams']['url']; ?></link>
    	<description><?php echo $category['page_description']; ?></description>    	
    	<?php echo $content_for_layout; ?>    	
	</channel>
</rss>