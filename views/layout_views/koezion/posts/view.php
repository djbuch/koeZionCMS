<?php 
$this->element($websiteParams['tpl_layout'].'/breadcrumbs');
$title_for_layout = $post['page_title']; 
$description_for_layout = $post['page_description']; 
$keywords_for_layout = $post['page_keywords'];

$contentPage = $this->vars['components']['Text']->format_content_text($post['content']);
?>
<div id="post<?php echo $post['id']; ?>" class="container_omega">
	
	<?php echo $contentPage; ?>	
	<div class="clearfix"></div>
	<?php
	if($post['display_form']) { 
		
		//$this->element($websiteParams['tpl_layout'].'/formulaire', array('formulaire' => $formulaire, 'formInfos' => $formInfos));
		if(isset($formPlugin)) { $this->element(PLUGINS.DS.'formulaires/views/formulaires/elements/frontoffice/formulaire', null, false); } 
		else { $this->element($websiteParams['tpl_layout'].'/formulaires/formulaire_commentaires'); } 
	}
 		
	if($postsComments) {
		
		?><div class="clearfix"></div><?php
		foreach($postsComments as $k => $v) {
			
			?>
			<div class="posts_comments">					
				<p class="post_message"><?php echo $v['message']; ?></p>
				<p class="post_name">par <?php echo $v['name']; ?></p>
			</div>
			<?php 
		}		
	}
	?>
</div>
<?php 
//On contrôle la nécessité de l'utilisation de la coloration syntaxique
if(substr_count($contentPage, '<pre class="brush')) {

	$css = array(
		$websiteParams['tpl_layout'].'/css/syntaxhighlighter/shCore',
		$websiteParams['tpl_layout'].'/css/syntaxhighlighter/shCoreDefault'
	);
	echo $helpers['Html']->css($css, true);

	$js = array(
		$websiteParams['tpl_layout'].'/js/syntaxhighlighter/shCore',
		$websiteParams['tpl_layout'].'/js/syntaxhighlighter/shBrushCss',
		$websiteParams['tpl_layout'].'/js/syntaxhighlighter/shBrushJScript',
		$websiteParams['tpl_layout'].'/js/syntaxhighlighter/shBrushPhp',
		$websiteParams['tpl_layout'].'/js/syntaxhighlighter/shBrushPlain',
		$websiteParams['tpl_layout'].'/js/syntaxhighlighter/shBrushSql',
		$websiteParams['tpl_layout'].'/js/syntaxhighlighter/shBrushXml'
	);
	echo $helpers['Html']->js($js);
	?><script type="text/javascript">SyntaxHighlighter.all()</script><?php
}
?>