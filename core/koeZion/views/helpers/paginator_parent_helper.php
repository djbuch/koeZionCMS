<?php
class PaginatorParentHelper extends Helper {
		
	var $excepts = array();
	
/**
 * function paginate
 *
 * @param 	integer $totalPages	 Nombre total de pages
 * @param 	integer $currentPage Numero de la page courrante
 * @param 	integer $adjacent	 Nombre d'element autour de l'élément courant
 * @access	public
 * @author	koéZionCMS 			
 * @return 	varchar Chaine de caractère contenant la pagination
 * @version 0.1 - by FI
 */
	function paginate($totalPages, $currentPage, $adjacent = 3, $options = array()) {
		
		//Déclaration des variables
		$prev = $currentPage - 1; 	//numéro de la page précédente
		$next = $currentPage + 1; 	//numéro de la page suivante
		$n2l = $totalPages - 1; 	//numéro de l'avant-dernière page (n2l = next to last)
	
		//Initialisation : s'il n'y a pas au moins deux pages, l'affichage reste vide
		$pagination = array();
		
		//Gestion des éventuels paramètres supplémentaires passés en GET
		$moreParams = $this->get_more_params(); //Par défaut pas de paramètres supplémentaires

		//Liste des options par défaut
		$defaultOptions = array(
			'firstElementClass' => 'first',
			'precElementClass' => 'prec',
			'lastElementClass' => 'last',
			'suivElementClass' => 'suiv',
			'linkElementClass' => '',
			'linkSelectElementClass' => 'active'
		);
		$options = array_merge($defaultOptions, $options); //Génération du tableau d'options utilisé dans la fonction
				
		//Sinon
		if($totalPages > 1) {
			
			$pagination[] = '<a href="?page=1'.$moreParams.'" title="'._("Première page (1)").'" class="'.$options['firstElementClass'].'"><<</a>';
	
			/////////////////////////////////////////////
			//   Début affichage du bouton précédent   //			
			//la page courante est > 2, le bouton renvoit donc sur la page précédente
			if($currentPage > 2) { $pagination[] = '<a href="?page='.$prev.$moreParams.'" title="'._("Page précédente").' ('.$prev.')" class="'.$options['precElementClass'].'"><</a>'; } 
			//dans tous les autres, cas la page est 1 : désactivation du bouton [précédent]
			else { $pagination[] = '<a href="?page=1'.$moreParams.'" title="'._("Page précédente").' (1)" class="'.$options['precElementClass'].'"><</a>'; }
			/////////////////////////////////////////////
						
			///////////////////////////////////
			//   Début affichage des pages   //
			//On reprend le cas de 3 numéros de pages adjacents (par défaut) de chaque côté du numéro courant
			//-> CAS 1 : il y a au plus 12 pages, insuffisant pour faire une troncature
			//-> CAS 2 : il y a au moins 13 pages, on effectue la troncature pour afficher 11 numéros de pages au total
	
			//CAS 1
			if($totalPages < 7 + ($adjacent * 2)) {
				
				//Ajout de la page 1
				if($currentPage == 1) { $pagination[] = '<a href="?page=1'.$moreParams.'" title="Page 1" class="'.$options['linkSelectElementClass'].'">1</a>'; } 
				else { $pagination[] = '<a href="?page=1'.$moreParams.'" title="Page 1" class="'.$options['linkElementClass'].'">1</a>'; }
				
				//Pour les pages restantes on utilise une boucle for
				for($i=2; $i<=$totalPages; $i++) {
										
					if($i == $currentPage) { $pagination[] = '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'" class="'.$options['linkSelectElementClass'].'">'.$i.'</a>'; } 
					else { $pagination[] = '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'" class="'.$options['linkElementClass'].'">'.$i.'</a>'; }
				}
			}
	
			//CAS 2 : au moins 13 pages, troncature
			else {

				//Troncature 1 : on se situe dans la partie proche des premières pages, on tronque donc la fin de la pagination.
				//l'affichage sera de neuf numéros de pages à gauche ... deux à droite
				if($currentPage < 2 + ($adjacent * 2)) {
					
					//Ajout de la page 1
					if($currentPage == 1) { $pagination[] = '<a href="?page=1'.$moreParams.'" title="Première page (1)" class="'.$options['linkSelectElementClass'].'">1</a>'; } 
					else { $pagination[] = '<a href="?page=1'.$moreParams.'" title="'._("Première page (1)").'" class="'.$options['linkElementClass'].'">1</a>'; }
	
					//puis des huit autres suivants
					for($i = 2; $i < 4 + ($adjacent * 2); $i++) {
						
						if($i == $currentPage) { $pagination[] = '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'" class="'.$options['linkSelectElementClass'].'">'.$i.'</a>'; } 
						else { $pagination[] = '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'" class="'.$options['linkElementClass'].'">'.$i.'</a>'; }
					}
	
					$pagination[] = '<span class="no_action">...</span>';	
					
					//et enfin les deux derniers numéros
					$pagination[] = '<a href="?page='.$n2l.$moreParams.'" title="Page '.$n2l.'" class="'.$options['linkElementClass'].'">'.$n2l.'</a>';					
					$pagination[] = '<a href="?page='.$totalPages.$moreParams.'" title="Page '.$totalPages.'" class="'.$options['linkElementClass'].'">'.$totalPages.'</a>';
				}
	
				//Troncature 2 : on se situe dans la partie centrale de notre pagination, on tronque donc le début et la fin de la pagination.
				//l'affichage sera deux numéros de pages à gauche ... sept au centre ... deux à droite
				elseif( (($adjacent * 2) + 1 < $currentPage) && ($currentPage < $totalPages - ($adjacent * 2)) ) {
					
					//Affichage des numéros 1 et 2
					$pagination[] = '<a href="?page=1'.$moreParams.'" title="'._("Première page (1)").'" class="'.$options['linkElementClass'].'">1</a>';
					$pagination[] = '<a href="?page=2'.$moreParams.'" title="Page 2" class="'.$options['linkElementClass'].'">2</a>';
	
					$pagination[] = '<span class="no_action">...</span>';
	
					//les septs du milieu : les trois précédents la page courante, la page courante, puis les trois lui succédant
					for($i = $currentPage - $adjacent; $i <= $currentPage + $adjacent; $i++) {
						
						if($i == $currentPage) { $pagination[] = '<a class="'.$options['linkSelectElementClass'].'">'.$i.'</a>'; } 
						else { $pagination[] = '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'" class="'.$options['linkElementClass'].'">'.$i.'</a>'; }
					}
	
					$pagination[] = '<span class="no_action">...</span>';
	
					//et les deux derniers numéros
					$pagination[] = '<a href="?page='.$n2l.$moreParams.'" title="Page '.$n2l.'" class="'.$options['linkElementClass'].'">'.$n2l.'</a>';
					$pagination[] = '<a href="?page='.$totalPages.$moreParams.'" title="Page '.$totalPages.'" class="'.$options['linkElementClass'].'">'.$totalPages.'</a>';
				}
	
				//Troncature 3 : on se situe dans la partie de droite, on tronque donc le début de la pagination.
				//l'affichage sera deux numéros de pages à gauche ... neuf à droite
				else {
					
					//Affichage des numéros 1 et 2
					$pagination[] = '<a href="?page=1'.$moreParams.'" title="'._("Première page (1)").'" class="'.$options['linkElementClass'].'">1</a>';
					$pagination[] = '<a href="?page=2'.$moreParams.'" title="Page 2" class="'.$options['linkElementClass'].'">2</a>';
	
					$pagination[] = '<span class="no_action">...</span>';
	
					//puis des neufs dernières
					for($i = $totalPages - (2 + ($adjacent * 2)); $i <= $totalPages; $i++) {
						
						if($i == $currentPage) { $pagination[] = '<a class="'.$options['linkSelectElementClass'].'">'.$i.'</a>'; }
						else { $pagination[] = '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'" class="'.$options['linkElementClass'].'">'.$i.'</a>'; }
					}
				}				
			}
			///////////////////////////////////
	
			///////////////////////////////////////////
			//   Début affichage du bouton suivant   //
			if($currentPage == $totalPages) { $next = $currentPage; }
			$pagination[] = '<a href="?page='.$next.$moreParams.'" title="'._("Page suivante").' ('.$next.')" class="'.$options['suivElementClass'].'">></a>';
			
			///////////////////////////////////////////
			
			$pagination[] = '<a href="?page='.$totalPages.$moreParams.'" title="'._("Dernière page").' ('.$totalPages.')" class="'.$options['lastElementClass'].'">>></a>';
		}
		
		return $pagination;
	}
	
	function get_more_params($excepts = array()) {
				
		$datas = $_GET;
		$excepts = am($this->excepts, array('page'));
		
		//On va parcourir les exemptions
		foreach($excepts as $except) {
			
			if(Set::check($_GET, $except)) { 
				
				$datas = Set::remove($datas, $except);
				$exceptPath = explode('.', $except);
				if(count(Set::classicExtract($datas, $exceptPath[0])) == 0) { $datas = Set::remove($datas, $exceptPath[0]); }
			}	
		}
		
		$moreParams = '';
		if(count($datas) > 0) { $moreParams = $this->_recursive_params($datas); }
		return $moreParams;
	}

/**
 * function _recursive_params
 *
 * @param 	array 	$datas		Données passées en GET
 * @param 	varchar $parent		Valeur du parent
 * @param 	integer $loop	 	Niveau d'imbrication
 * @access	protected
 * @author	koéZionCMS
 * @return 	varchar Chaine de caractère contenant les paramètres à passer au lien de la pagination
 * @version 0.1 - 25/11/2013 by FI
 */	
	
	protected function _recursive_params($datas, $parent = '', $loop = 1) {
				
		//Parcours des paramètres passés en GET
		$moreParams = '';
		//ANCIENNE VERSION BIEN CRADE AVEC 3 FOREACH IMBRIQUES
		foreach($datas as $k => $v) {
		
			if(!is_array($v)) {
				$moreParams .= '&'.$k.'='.$v;
			}
			else {
				foreach($v as $k1 => $v1) {
		
					if(!is_array($v1)) {
						$moreParams .= '&'.$k.'['.$k1.']='.$v1;
					}
					else {
		
						foreach($v1 as $k2 => $v2) {
							$moreParams .= '&'.$k.'['.$k1.']['.$k2.']='.$v2;
						}
		
					}
				}
			}
		}
		
		/*
		//25/11/2013
		//NOUVELLE VERSION RECURSIVE, A VOIR SI POSSIBILITE DE L'AMELIORER
		//JE SUIS PAS SUPER CONCENTRE AJD CF PAPA DE LESLIE
		//07/07/2014 Supprimée car trop aléatoire
		$moreParams = '';
		foreach($datas as $k => $v) {
			
			if(!is_array($v)) { 
				
				if($loop > 2) { $moreParams .= '&'.$parent.'['.$k.']='.$v; }
				else { $moreParams .= '&'.$parent.$k.'='.$v; }  
			
			} else { 
				
				if($loop < 2) { $moreParams .= $this->_recursive_params($v, $k, $loop+1); }
				else { $moreParams .= $this->_recursive_params($v, $parent.'['.$k.']', $loop+1); }
			}
		}*/
		
		return $moreParams;
	}
}
?>