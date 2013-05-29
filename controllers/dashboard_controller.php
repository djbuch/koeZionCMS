<?php
/**
 * Contrôleur permettant la gestion de la home page du backoffice
 * 
 * PHP versions 4 and 5
 *
 * KoéZionCMS : PHP OPENSOURCE CMS (http://www.koezion-cms.com)
 * Copyright KoéZionCMS
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	KoéZionCMS
 * @link        http://www.koezion-cms.com
 */
class DashboardController extends AppController {   

	public $auto_load_model = false;
	
	function backoffice_index() {
		
		$aParams = $this->_check_datas_stats(); //Contrôle des dates

		if(isset($this->request->data['display']) && $this->request->data['display']) {
			
			$iSiteId = Session::read('Backoffice.Websites.current'); //Récupération de l'identifiant du site courant			
			$sLoginGoogleAnalytics = Session::read('Backoffice.Websites.details.'.$iSiteId.'.ga_login'); //Login GA
			$sPasswdGoogleAnalytics = Session::read('Backoffice.Websites.details.'.$iSiteId.'.ga_password'); //Mot de passe GA
			$sIdGoogleAnalytics = Session::read('Backoffice.Websites.details.'.$iSiteId.'.ga_id'); //ID du profil du site dans GA 
			
			//Pour afficher les données de Google Analytics nous devons en premier lieu vérifier un certain nombre de choses
			//- Que l'identifiant du site courant soit non vide
			//- Qu'il y ait bien un login Google Analytics de renseigné
			//- Qu'il y ait bien un mot de passe Google Analytics de renseigné
			//- Que pour le site courant l'identifiant du profil Google Analytics soit bien saisit
			if(
				!empty($iSiteId) && 
				!empty($sLoginGoogleAnalytics) && 
				!empty($sPasswdGoogleAnalytics) &&
				!empty($sIdGoogleAnalytics)
			) {
								
					//require_once(LIBS.DS.'api_ga.php'); //Import de la librairie Google Analytics
					Configure::import(LIBS.DS.'api_ga');
					
					//Déclaration d'un objet de type GoogleAnalyticsAPI 
					set_time_limit(0);
					$oGa = new GoogleAnalyticsAPI($sLoginGoogleAnalytics, $sPasswdGoogleAnalytics, $sIdGoogleAnalytics, $aParams['date_debut'], $aParams['date_fin']);
					$sToken = $oGa->getLoginToken(); // recupere le jeton d'acces pour controler que la connexion est bien faite
					
					if(!empty($sToken)) {
						
						//////////////////////////////////////////////////////////////////////////////
						//   RECUPERATION DES DONNEES STATISTIQUES EN FONCTION DES DATES INDIQUEES  //						
						$navigateurs = $oGa->getDimensionByMetric('pageviews', 'browser');
						$this->set('navigateurs', $navigateurs);
						
						$countries = $oGa->getDimensionByMetric('pageviews', 'country');
						$this->set('countries', $countries);
						
						$keywords = $oGa->getDimensionByMetric('pageviews', 'keyword');
						$this->set('keywords', $keywords);
						
						$source = $oGa->getDimensionByMetric('pageviews', 'source');
						$this->set('source', $source);
						
						$pagePath = $oGa->getDimensionByMetric('pageviews', 'pagePath');
						$this->set('pagePath', $pagePath);
						
						$visits = $oGa->getMetric('visits');
						$this->set('visits', $visits);
						
						$unique_visits = $oGa->getMetric('visitors');
						$this->set('unique_visits', $unique_visits);
						
						$page_views = $oGa->getMetric('pageviews');
						$this->set('page_views', $page_views);						
						
						//$oGa->setSortByDimensions('month'); //!!! Obligatoire sinon les mois ne sont pas dans le bon ordre
						//$aVisitesA = $oGa->getDimensionByMetric('visitors', 'month');  //Nombre de visiteurs uniques absolus par mois										
						
						$aDateDeb = explode('-', $aParams['date_debut']);
						$aDateFin = explode('-', $aParams['date_fin']);
						
						$sStartDateGaUsA = date('Y-m-d', mktime(0, 0, 0, $aDateDeb[1], $aDateDeb[2], $aDateDeb[0]));  //1er janvier
						$sEndDateGaUsA = date('Y-m-d', mktime(0, 0, 0, $aDateFin[1], $aDateFin[2], $aDateFin[0]));  //31 décembre
						$oGa->setDate ($sStartDateGaUsA, $sEndDateGaUsA); //Set les date pour prendre une année complete : dissocié de la recherche
						//$oGa->setSortByDimensions('month'); //!!! Obligatoire sinon les mois ne sont pas dans le bon ordre
	
						$aGraphVisitesUniques = $oGa->getDimensionByMetric('visitors', 'month');  //Nombre de visiteurs uniques absolus par mois
						$aGraphVisites = $oGa->getDimensionByMetric('visits', 'month');  //Nombre de visiteurs uniques absolus par mois						
						$aGraphPagesVues = $oGa->getDimensionByMetric('pageviews', 'month');  //Nombre de visiteurs uniques absolus par mois								
						//////////////////////////////////////////////////////////////////////////////
						
						////////////////////////////////////////////////////////////////////
						//  RECUPERATION DES DONNEES STATISTIQUES POUR L'ANNEE EN COURS   //
						$iNumYear = date('Y'); //En prevision si un jour on veut passer en parametre l'année.
						$sStartDateGaUs = date('Y-m-d', mktime(0, 0, 0, 1, 1, $iNumYear));  //1er janvier
						$sEndDateGaUs = date('Y-m-d', mktime(0, 0, 0, 12, 31, $iNumYear));  //31 décembre
						$oGa->setDate ($sStartDateGaUs, $sEndDateGaUs); //Set les date pour prendre une année complete : dissocié de la recherche
						$oGa->setSortByDimensions('month'); //!!! Obligatoire sinon les mois ne sont pas dans le bon ordre
						$aVisites = $oGa->getDimensionByMetric('visitors', 'month');  //Nombre de visiteurs uniques absolus par mois
						$this->set('aGraphVisitesUniques', $aGraphVisitesUniques);
						$this->set('aGraphVisites', $aGraphVisites);
						$this->set('aGraphPagesVues', $aGraphPagesVues);
						$this->set('sGraphStart', $this->request->data['start']);
						$this->set('sGraphEnd', $this->request->data['end']);						
						$this->set('iNumYear', $iNumYear);
						////////////////////////////////////////////////////////////////////
	
						$this->set('iDisplay', 1); //Pour indiquer au front qu'il faut afficher les données
						
					} else { $this->set('sMessageErreurGa', 'Connexion impossible à Google Analytics'); }
					
					unset($oGa);
					
			} else { $this->set('sMessageErreurGa', 'Il y a un problème dans le paramétrages de vos données Google Analytics'); }
		}		
	}
	
//==================================================================================================================================================//
//   																FONCTIONS PRIVEES   															//
//==================================================================================================================================================//
	
/** 
 * Fonction qui permet de récuperer les données postées dans le formulaire de recherche des statistiques
 * Utilise le $this->request->data
 *
 * @return array tableau contenant les paramètres des recherches statistiques
 * @access protected
 * @author koéZionCMS
 * @version 0.1 - 15/05/2013 by FI
 */
	protected function _check_datas_stats(){
		
		////////////////////////////////////////////////////////
		//   CONTROLE DES DONNEES DU FORMULAIRE STATISTIQUE   //		
		if($this->request->data && !empty($this->request->data)) {			
			
			//On contrôle que les champs soient bien remplis sinon on leur affecte une valeur par défaut
			if(!empty($this->request->data['start']) && $this->request->data['start'] != 'dd.mm.yy') { $dateStartUs = $this->components['Text']->date_human_to_array($this->request->data['start'], '.', 'us'); } 
			else { $dateStartUs = $this->components['Text']->get_first_day_of_month(date('m'), date('Y')); pr('1');}
			
			if(!empty($this->request->data['end']) && $this->request->data['end'] != 'dd.mm.yy') { $dateEndUs = $this->components['Text']->date_human_to_array($this->request->data['end'], '.', 'us'); } 
			else { $dateEndUs = $this->components['Text']->get_last_day_of_month(date('m'), date('Y')); pr('2');}
			
			//On contrôle que la date de fin ne soit pas antérieure à la date de début
			//auquel la date de fin sera égale à la date de début
			if($dateEndUs < $dateStartUs) { 
				
				$dateTMP = $dateEndUs;
				$dateEndUs = $dateStartUs; 
				$dateStartUs = $dateTMP;
				$this->request->data['start'] = $dateStartUs;
				$this->request->data['end'] = $dateEndUs;
			}
			
			//initialisation des paramètres de recherche : les dates y seront toujours
			$datesParams = array(
				'date_debut' => $dateStartUs,
				'date_fin' => $dateEndUs
			);
				
		} else {
			
			//Par défaut on prend le mois en cours
			$dateStartUs = $this->components['Text']->get_first_day_of_month(date('m'), date('Y'));
			$dateEndUs = $this->components['Text']->get_last_day_of_month(date('m'), date('Y'));
			
			//parametres par défaut
			$datesParams = array(
				'date_debut' => $dateStartUs,
				'date_fin' => $dateEndUs
			);
			
			$this->request->data['start'] = $this->components['Text']->date_human_to_array($dateStartUs, '-', 'fr');
			$this->request->data['end'] = $this->components['Text']->date_human_to_array($dateEndUs, '-', 'fr');
		}
		////////////////////////////////////////////////////////
		
		return $datesParams;
	}
}