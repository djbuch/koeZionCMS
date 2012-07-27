<div id="header">
	<?php /*if(count($locales) > 1) { ?>
		<div class="locales">
			<?php foreach($locales as $locale) { ?>
				<a href="<?php echo Router::url('/').'?lang='.$locale['code']; ?>"><?php echo $locale['name']; ?></a>					
			<?php } ?>
		</div>		
	<?php }*/ ?>
	<div id="logo"><?php echo $websiteParams['tpl_logo']; ?></div>
	<?php if(isset($websiteParams['search_engine_position']) && $websiteParams['search_engine_position'] == 'header') { $this->element('frontoffice/search'); } ?>
</div>