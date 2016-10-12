<?php
$contentPage 	= $this->vars['components']['Text']->format_content_text($post['content']);
$categoryLink 	= $this->request('Categories', 'get_category_link', array($category['id']));
$categoryName 	= $categoryLink['name'];
$postBaseRoute 	= Router::url('categories/view/id:'.$categoryLink['id'].'/slug:'.$categoryLink['slug']);
$postDate 		= $this->vars['components']['Date']->date_sql_to_human($post['modified']);
$writer 		= $this->request('Users', 'get_user_libelle', array($post['created_by']));
$inTypes 		= $this->request('PostsTypes', 'get_posts_types', array($post['id']));
?>
<div class="article_libelle">
	<h2><?php echo $post['name']; ?></h2>
</div>
<div class="article_infos">
	<?php /*DATE*/ ?>
	<a href="<?php echo $postBaseRoute; ?>?date=<?php echo $postDate['sql']; ?>"><i class="glyphicon glyphicon-calendar"></i> <?php echo $postDate['txt']; ?></a>
	<span class="separator">&nbsp;</span>
	
	<?php 
	/////////////////////
	//    REDACTEUR    // 
	?>
	<a href="<?php echo $postBaseRoute; ?>?writer=<?php echo $writer['id']; ?>"><i class="glyphicon glyphicon-user"></i> <?php echo $writer['name']; ?></a>
	<span class="separator">&nbsp;</span>
	
	<?php 	
	///////////////////////////////////
	//    AFFICHAGE DU FORMULAIRE    //
	if($post['display_form']) {
		
		$nbComments = $this->request('PostsComments', 'get_nb_comments', array($post['id']));
		if($nbComments == 0) { $nbCommentsTxt = _('aucun commentaire'); } else { $nbCommentsTxt = $nbComments.' '._('commentaires'); }
		?><i class="glyphicon glyphicon-comment"></i> <?php echo $nbCommentsTxt;	    	
		?><span class="separator">&nbsp;</span><?php 
	}
			
	////////////////////////////////
	//    DUREE DE REALISATION    //
	if(!empty($post['shooting_time'])) { ?><i class="glyphicon glyphicon-time"></i> <?php echo $post['shooting_time']; }
	?>
</div>
<?php       
///////////////////////////
//    TYPES D'ARTICLE    //
if(!empty($inTypes)) {
	?>
    <div class="tags">
    	<?php foreach($inTypes as $kType => $vType) { ?><a href="<?php echo $postBaseRoute; ?>?typepost=<?php echo $vType['id']; ?>" class="tag"><?php echo $vType['name']; ?></a><?php } ?>
	</div>
	<?php 
}
?>
<div class="article_content">
	<?php echo $contentPage; ?>
</div>
<?php if($post['display_form']) { $this->element('forms/post_comments', array('nbComments' => $nbComments, 'nbCommentsTxt' => $nbCommentsTxt)); } ?>