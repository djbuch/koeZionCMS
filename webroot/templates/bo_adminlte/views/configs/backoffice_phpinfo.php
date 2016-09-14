<section class="content">
	<div class="row">
    	<div class="col-md-8 col-md-offset-2">
			<div class="box box-primary">
				<div class="box-header with-border">
			    	<h3 class="box-title"><?php echo _("PHPINFO"); ?></h3>
				</div>
				<div class="box-body">
					<div class="col-md-12">
						<?php 
						ob_start();
						phpinfo();
						$phpinfo = array('phpinfo' => array());
						if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
							foreach($matches as $match)
								if(strlen($match[1])) { 
									$phpinfo[$match[1]] = array();
								} elseif(isset($match[3])) {
									$arr = array_keys($phpinfo); 
									$end = end($arr);
									$phpinfo[$end][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
								} else {
									$arr = array_keys($phpinfo);
									$end = end($arr);
									$phpinfo[$end][] = $match[2];
								}
						
							foreach($phpinfo as $name => $section) {
								echo "<h3>$name</h3>\n<table class=\"table table-bordered\">\n";
								foreach($section as $key => $val) {
									if(is_array($val))
										echo "<tr><td>$key</td><td>$val[0]</td><td class=\"val\">$val[1]</td></tr>\n";
									elseif(is_string($key))
										echo "<tr><td>$key</td><td class=\"val\">$val</td></tr>\n";
									else
										echo "<tr><td>$val</td></tr>\n";
							}
							echo "</table>\n";
						}					
						?>
					</div>
				</div>
			</div>
    	</div>
    </div>
</section>
<style>
table td.val{word-break: break-all;}					
</style>