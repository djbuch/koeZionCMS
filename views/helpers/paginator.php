<?php
class Paginator {
		
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
	function paginate($totalPages, $currentPage, $adjacent = 3) {
		
		//Déclaration des variables
		$prev = $currentPage - 1; 	//numéro de la page précédente
		$next = $currentPage + 1; 	//numéro de la page suivante
		$n2l = $totalPages - 1; 	//numéro de l'avant-dernière page (n2l = next to last)
	
		//Initialisation : s'il n'y a pas au moins deux pages, l'affichage reste vide
		$pagination = '';
		
		//Gestion des éventuels paramètres supplémentaires passés en GET
		$moreParams = $this->get_more_params(); //Par défaut pas de paramètres supplémentaires
				
		//Sinon
		if($totalPages > 1) {
			
			$pagination .= '<a href="?page=1'.$moreParams.'" title="'._("Première page (1)").'" class="first"><<</a>';
	
			/////////////////////////////////////////////
			//   Début affichage du bouton précédent   //			
			//la page courante est > 2, le bouton renvoit donc sur la page précédente
			if($currentPage > 2) { $pagination .= '<a href="?page='.$prev.$moreParams.'" title="'._("Page précédente").' ('.$prev.')" class="prec"><</a>'; } 
			//dans tous les autres, cas la page est 1 : désactivation du bouton [précédent]
			else { $pagination .= '<a href="?page=1'.$moreParams.'" title="'._("Page précédente").' (1)" class="prec"><</a>'; }
			/////////////////////////////////////////////
						
			///////////////////////////////////
			//   Début affichage des pages   //
			//On reprend le cas de 3 numéros de pages adjacents (par défaut) de chaque côté du numéro courant
			//-> CAS 1 : il y a au plus 12 pages, insuffisant pour faire une troncature
			//-> CAS 2 : il y a au moins 13 pages, on effectue la troncature pour afficher 11 numéros de pages au total
	
			//CAS 1
			if($totalPages < 7 + ($adjacent * 2)) {
				
				//Ajout de la page 1
				if($currentPage == 1) { $pagination .= '<a href="?page=1'.$moreParams.'" title="Page 1" class="superbutton select">1</a>'; } 
				else { $pagination .= '<a href="?page=1'.$moreParams.'" title="Page 1">1</a>'; }
				
				//Pour les pages restantes on utilise une boucle for
				for($i=2; $i<=$totalPages; $i++) {
										
					if($i == $currentPage) { $pagination .= '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'" class="superbutton select">'.$i.'</a>'; } 
					else { $pagination .= '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'">'.$i.'</a>'; }
				}
			}
	
			//CAS 2 : au moins 13 pages, troncature
			else {

				//Troncature 1 : on se situe dans la partie proche des premières pages, on tronque donc la fin de la pagination.
				//l'affichage sera de neuf numéros de pages à gauche ... deux à droite
				if($currentPage < 2 + ($adjacent * 2)) {
					
					//Ajout de la page 1
					if($currentPage == 1) { $pagination .= '<a href="?page=1'.$moreParams.'" title="Première page (1)" class="superbutton select">1</a>'; } 
					else { $pagination .= '<a href="?page=1'.$moreParams.'" title="'._("Première page (1)").'">1</a>'; }
	
					//puis des huit autres suivants
					for($i = 2; $i < 4 + ($adjacent * 2); $i++) {
						
						if($i == $currentPage) { $pagination .= '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'" class="superbutton select">'.$i.'</a>'; } 
						else { $pagination .= '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'" class="first">'.$i.'</a>'; }
					}
	
					$pagination .= '<span class="no_action">...</span>';	
					
					//et enfin les deux derniers numéros
					$pagination .= '<a href="?page='.$n2l.$moreParams.'" title="Page '.$n2l.'">'.$n2l.'</a>';					
					$pagination .= '<a href="?page='.$totalPages.$moreParams.'" title="Page '.$totalPages.'">'.$totalPages.'</a>';
				}
	
				//Troncature 2 : on se situe dans la partie centrale de notre pagination, on tronque donc le début et la fin de la pagination.
				//l'affichage sera deux numéros de pages à gauche ... sept au centre ... deux à droite
				elseif( (($adjacent * 2) + 1 < $currentPage) && ($currentPage < $totalPages - ($adjacent * 2)) ) {
					
					//Affichage des numéros 1 et 2
					$pagination .= '<a href="?page=1'.$moreParams.'" title="'._("Première page (1)").'">1</a>';
					$pagination .= '<a href="?page=2'.$moreParams.'" title="Page 2">2</a>';
	
					$pagination .= '<span class="no_action">...</span>';
	
					//les septs du milieu : les trois précédents la page courante, la page courante, puis les trois lui succédant
					for($i = $currentPage - $adjacent; $i <= $currentPage + $adjacent; $i++) {
						
						if($i == $currentPage) { $pagination .= '<span class="superbutton select">'.$i.'</span>'; } 
						else { $pagination .= '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'">'.$i.'</a>'; }
					}
	
					$pagination .= '<span class="no_action">...</span>';
	
					//et les deux derniers numéros
					$pagination .= '<a href="?page='.$n2l.$moreParams.'" title="Page '.$n2l.'">'.$n2l.'</a>';
					$pagination .= '<a href="?page='.$totalPages.$moreParams.'" title="Page '.$totalPages.'" class="first">'.$totalPages.'</a>';
				}
	
				//Troncature 3 : on se situe dans la partie de droite, on tronque donc le début de la pagination.
				//l'affichage sera deux numéros de pages à gauche ... neuf à droite
				else {
					
					//Affichage des numéros 1 et 2
					$pagination .= '<a href="?page=1'.$moreParams.'" title="'._("Première page (1)").'">1</a>';
					$pagination .= '<a href="?page=2'.$moreParams.'" title="Page 2">2</a>';
	
					$pagination .= '<span class="no_action">...</span>';
	
					//puis des neufs dernières
					for($i = $totalPages - (2 + ($adjacent * 2)); $i <= $totalPages; $i++) {
						
						if($i == $currentPage) { $pagination .= '<span class="superbutton select">'.$i.'</span>'; }
						else { $pagination .= '<a href="?page='.$i.$moreParams.'" title="Page '.$i.'">'.$i.'</a>'; }
					}
				}				
			}
			///////////////////////////////////
	
			///////////////////////////////////////////
			//   Début affichage du bouton suivant   //
			if($currentPage == $totalPages) { $pagination .= '<span>></span>'; } 
			else { $pagination .= '<a href="?page='.$next.$moreParams.'" title="'._("Page suivante").' ('.$next.')" class="suiv">></a>'; }
			///////////////////////////////////////////
			
			$pagination .= '<a href="?page='.$totalPages.$moreParams.'" title="'._("Dernière page").' ('.$totalPages.')" class="last">>></a>';
		}
		
		return ($pagination);
	}
	
	function get_more_params($excepts = array()) {
				
		$datas = $_GET;
		
		//On va parcourir les exemptions
		foreach($excepts as $except) {
			
			if(Set::check($_GET, $except)) { 
				
				$datas = Set::remove($datas, $except);
				$exceptPath = explode('.', $except);
				if(count(Set::classicExtract($datas, $exceptPath[0])) == 0) { $datas = Set::remove($datas, $exceptPath[0]); }
			}	
		}
		
		$moreParams = '';
		if(count($datas) > 0) {
		
			//Parcours des paramètres passés en GET
			foreach($datas as $k => $v) { 
				
				if(!is_array($v)) { $moreParams .= '&'.$k.'='.$v; }
				else {
					
					foreach($v as $k1 => $v1) { $moreParams .= '&'.$k.'['.$k1.']='.$v1; }
				}
			}
		}
		return $moreParams;
	}
}
?>