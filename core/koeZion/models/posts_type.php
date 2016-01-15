<?php
/**
 * Modèle permettant la gestion des types de posts
 */
class PostsType extends Model {   

///////////////////	
//   VARIABLES   //	
///////////////////
		
/**
 * Tableau contenant l'ensemble des champs à valider
 *
 * @var 	array
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 17/01/2012 by FI
 */	
	var $validate = array(
		'column_title' => array(
			'rule' => array('minLength', 2),
			'message' => 'PostsType.column_title'
		),
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'PostsType.name'
		)
	);
	
///////////////////	
//   FONCTIONS   //	
///////////////////
	
/**
 * Cette fonction récupère les types de posts et formate la variable retournée par titre de colonne
 *
 * @varchar	integer $categoryId Identifiant de la catégorie
 * @return	array	Tableau contenant les types de posts formatés par titre de colonne
 * @access	public
 * @author	koéZionCMS
 * @version 0.1 - 12/01/2012 by FI
 * @version 0.2 - 16/05/2012 by FI - Modification de la récupération des données pour n'obtenir que les types de posts de la catégories voulues
 * @version 0.3 - 06/11/2012 by FI - RE -> Modification de la récupération des données pour n'obtenir que les types de posts de la catégories voulues car la requête ne marchait pas
 */	
	function get_for_front($categoryId = null) {
		
		///////////////////////////////////////////
		//   RECUPERATION DES TYPES D'ARTICLES   //
		//Dans le cas ou est indiqué une catégorie
		if(isset($categoryId) && !empty($categoryId)) {
			
			$params = array(
				'fields' => array('id', 'name', 'column_title'),
				'tables' => array(
					'posts_types' => 'KzPostsType',	
					'categories_posts_posts_types' => 'KzCategoriesPostsPostsType',	
					'posts' => 'KzPost',	
				),
				'conditions' => array(
					"KzCategoriesPostsPostsType.category_id = ".$categoryId,
					"KzCategoriesPostsPostsType.posts_type_id = KzPostsType.id",
					"KzPostsType.online = 1",
					"KzCategoriesPostsPostsType.post_id = KzPost.id",
					"KzPost.online = 1"
				)				
			);
			$postsTypes = $this->find($params);
			
			/*
			$sql = "
				SELECT 
					PostsType.id, 
					PostsType.name, 
					PostsType.column_title
					 
				FROM 
					posts_types AS PostsType, 
					posts_posts_types AS PostsPostsType,
					posts AS Post 
					
				WHERE PostsPostsType.category_id = ".$categoryId." 
				AND PostsPostsType.posts_type_id = PostsType.id 
				AND PostsType.online = 1 
				AND PostsPostsType.post_id = Post.id 
				AND Post.category_id =  ".$categoryId." 
				AND Post.online = 1
				
				ORDER BY PostsType.column_title, PostsType.order_by, PostsType.name
			";			
			$postsTypes = $this->query($sql, true);
			*/
			
		//Dans le cas ou aucune catégorie n'est indiquée
		} else {
						
			$params = array(
				'fields' => array('id', 'name', 'column_title'),
				'conditions' => array('online' => 1)
			);
			$postsTypes = $this->find($params);
		}
		
		//Formatage de la variable de retour
		$retour = array();
		foreach($postsTypes as $k => $v) { $retour[$v['column_title']][$v['id']] = $v['name']; }		
		return $retour;
	}
}