<?php 
$this->element('breadcrumbs');
$title_for_layout = $post['page_title']; 
$description_for_layout = $post['page_description']; 
$keywords_for_layout = $post['page_keywords'];

$contentPage = $this->vars['components']['Text']->format_content_text($post['content']);

if(count($children) == 0 && count($brothers) == 0 && count($postsTypes) == 0 && count($rightButtons) == 0) { $rightColumn = false; } 
else { $rightColumn = true; }
?>
<div id="post<?php echo $post['id']; ?>" class="container_omega post_details">
	<?php 
	if($rightColumn) { 
		
		$this->element('colonne_droite');
		echo '<div class="gs_8"><div class="gs_8 omega">'; 
	}	
	?>
	<h2 class="widgettitle"><?php echo $post['name']; ?></h2>	
	<p class="post_info">
		<?php 					
		$categoryLink = $this->request('Categories', 'get_category_link', array($post['category_id']));
		$categoryName = $categoryLink['name'];
		$postBaseRoute = Router::url('categories/view/id:'.$categoryLink['id'].'/slug:'.$categoryLink['slug']);				
		
		$postDate = $this->vars['components']['Text']->date_sql_to_human($post['modified']);
		echo '<a href="'.$postBaseRoute.'?date='.$postDate['sql'].'">'.$postDate['txt'].'</a>';
			
		$nbComments = $this->request('PostsComments', 'get_nb_comments', array($post['id']));
		if($nbComments == 0) { $nbCommentsTxt = _('aucun'); } else { $nbCommentsTxt = $nbComments; }				
		echo '|'.$nbCommentsTxt.' commentaire(s)';
						
		$writer = $this->request('Users', 'get_user_libelle', array($post['modified_by']));				
		echo '| '._('par').' '.'<a href="'.$postBaseRoute.'?writer='.$writer['id'].'">'.$writer['name'].'</a>';				
		
		$inTypes = $this->request('PostsTypes', 'get_posts_types', array($post['id']));
		if(count($inTypes) > 0) {
			
			echo '| tags ';
			$inTypesDisplay = array();
			foreach($inTypes as $kType => $vType) { $inTypesDisplay[] = '<a href="'.$postBaseRoute.'?typepost='.$vType['id'].'">'.$vType['name'].'</a>'; }					
			
			echo implode(', ', $inTypesDisplay);
		}
		
		echo '| '._('dans').' '.'<a href="'.$postBaseRoute.'">'.$categoryName.'</a>';
		?>
	</p>	
	<div class="hr"><div class="inner_hr">&nbsp;</div></div>
	
	<?php echo $contentPage; ?>
			
	<div class="clearfix"></div>
	<?php
	if($post['display_form']) { 
		
		//$this->element('formulaire', array('formulaire' => $formulaire, 'formInfos' => $formInfos));
		if(isset($formPlugin)) { $this->element(PLUGINS.DS.'formulaires/views/elements/formulaires/frontoffice/formulaire', null, false); } 
		else { $this->element('formulaires/formulaire_commentaires'); } 
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
			
	if($rightColumn) { echo '</div></div>'; }
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