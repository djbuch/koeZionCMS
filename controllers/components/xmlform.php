<?php
/**
 * class XmlForm
 * 
 * Cette classe nous permet : 
 * - de récupérer les formulaires XML
 * - de parser l'ensemble des résultats sous forme de tableau
 * - de générer les différentes règles de validation pour le modèle
 * - de générer le code HTML du formulaire qui sera envoyé à la vue 
 * 
 * On utilise simpleXml pour parser le fichier XML
 * 
 * @author koéZionCMS
 *
 */
class Xmlform {
	
/**
 * function get_xml_form
 * 
 * Cette fonction est chargée de récupérer les fichiers XML distant et de les transformer en objet de type SimpleXml
 * 
 * @param 	integer $formName Identifiant du formulaire
 * @return 	simpleXml $xParsedXml Fichier XML parsé avec simpleXml
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 14/09/2012 by FI
 */
	function get_xml_form($formName) {
		
		if(!substr_count($formName, '.xml')) { $formName.'.xml'; }		
		
		//On va vérifier si le fichier xml du formulaire est présent ou pas en local
		$sUrlLocale = CONFIGS.DS.'forms'.DS.$formName;
		if(!file_exists($sUrlLocale)) { exit(); }
				
		$xParsedXml = simplexml_load_file($sUrlLocale); //Création de l'objet SimpleXml avec comme paramètre l'url du formulaire à récupérer
		return $xParsedXml; //On retourne le fichier SimpleXml parsé				
	}
	
/**
 * function get_constraint
 * 
 * Cette fonction est chargée de récupérer les différentes contraintes liées au formulaire pour pouvoir ensuite les transmettre au model
 * 
 * @param 	integer $formName 	Identifiant du formulaire
 * @param 	mixed 	$xmlForm 	Formulaire XML (On passe le formulaire en paramètres lorsque l'on souhaite récupérer les contraintes et le formulaire sous forme de tableau pour ne pas avoir à recharger coups sur coups le fichier) 
 * @return 	array 	Tableau des contraintes
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 14/09/2012 by FI
 */
	function get_constraint($formName, $xmlForm = null) {		
		
		$aValidates = array(); //Tableau des contraintes du formulaire
		
		//Si le formulaire XML n'est pas passé en paramètre il nous faut le récupérer
		if(!isset($xmlForm)) { $xmlForm = $this->get_xml_form($formName); }//Récupération du formulaire sous forme de tableau
		 
		foreach($xmlForm->question as $aQuestion) { //On parcours le noeud question			
			foreach ($aQuestion->value as $aValue) { //On parcous le noeud value (dans lequel se trouve si il y en a les contraintes)				 
				if(isset($aValue->constraint)) { //On teste si on a des contraintes					
					$iCptRule = 1; //On initialise le compteur de contraintes					
					$aValidatesTMP = array(); //On initialise le tableau temporaire
					foreach ($aValue->constraint as $aConstraint) { //On parcours les contraintes
					
						$sRule = (String)$aConstraint;
						$sMessage = (String)$aConstraint['message'];
						$sParams = (String)$aConstraint['params'];
						
						if(!empty($sParams)) {
							
							$aParams = explode('|', $sParams);
							
							//pr($aParams);
							//pr(count($aParams));
							
							if(count($aParams) == 1) {
							
								//Dans le cas de règle de validation complexes à un paramètre
								$aValidatesTMP['rule'.$iCptRule] = array(
									'rule' => array($sRule, $aParams[0]), 
									'message' => $sMessage
								);
							} else if(count($aParams) == 2) {
							
								//Dans le cas de règle de validation complexes à deux paramètre
								$aValidatesTMP['rule'.$iCptRule] = array(
									'rule' => array($sRule, $aParams[0], $aParams[1]), 
									'message' => $sMessage
								);								
							}
							
						} else { $aValidatesTMP['rule'.$iCptRule] = array('rule' => $sRule, 'message' => $sMessage); }
						
						$iCptRule ++;
					}
					
					$aValidates[(String)$aQuestion->name] = $aValidatesTMP;
				}								
			}
		}				
		
		//pr($aValidates);
		return $aValidates;	
	}
	
/**
 * function get_array_form
 * 
 * Cette fonction est chargée de formater un tableau contenant la liste des champs du formulaire
 * Ce tableau sera ensuite envoyé à un helper qui en fera l'affichage
 * 
 * @param 	integer $formName 	Identifiant du formulaire
 * @param 	mixed 	$xmlForm 	Formulaire XML (On passe le formulaire en paramètres lorsque l'on souhaite récupérer les contraintes et le formulaire sous forme de tableau pour ne pas avoir à recharger coups sur coups le fichier)
 * @return 	array 	Tableau des différents champs du formulaire
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 14/09/2012 by FI
 *  
 */
	function get_array_form($formName, $xmlForm = null) {		
		
		$aElementsForm = array(); //Tableau des éléments du formulaire
		
		//Si le formulaire XML n'est pas passé en paramètre il nous faut le récupérer
		if(!isset($xmlForm)) { $xmlForm = $this->get_xml_form($formName); }
		
		$iCptQuestion = 0; //Compteur de question
		$sLastLabelDefault = ''; //Chaîne de caractère contenant le libellé de la dernière question parcourue
		
		$aElementsForm['form_infos']['libelle'] = (String)$xmlForm->name; //Libellé du formulaire	
		$aElementsForm['form_infos']['error_message'] = (String)$xmlForm->error_message; //Message en cas d'erreur
		$aElementsForm['form_infos']['success_message'] = (String)$xmlForm->success_message; //Message en cas de succès	
		$aElementsForm['form_infos']['mail_subject'] = (String)$xmlForm->mail_subject; //Sujet du mail
		
		//Mise en place des tableaux pour la gestion des différentes étapes
		foreach($xmlForm->question as $aQuestion) { //On parcours le noeud question
			
			foreach($aQuestion->value as $aValue) { //On parcous le noeud value (dans lequel se trouve les ddifférentes valeurs des éléments)
				
				$sLabel = (String)$aQuestion->label;
				
				//On check que le libellé de la question courante soit différent de la précédente pour incrémenter le compteur
				if($sLabel != $sLastLabelDefault) { $iCptQuestion++; }
									
				$sInputName = (String)$aQuestion->name;
				$aElementsForm['formulaire'][$iCptQuestion]['label'] = $sLabel;
				$aElementsForm['formulaire'][$iCptQuestion]['name'][] = $sInputName;
				
				$aElementsForm['html'][$sInputName] = $sLabel;
				
				$aElementsForm['formulaire'][$iCptQuestion]['type'] = (String)$aValue['type'];
				$aElementsForm['formulaire'][$iCptQuestion]['default'] = (String)$aValue['default'];
				$aElementsForm['formulaire'][$iCptQuestion]['constraint'] = (String)$aValue->constraint;
				
				if(isset($aValue->option)) { //On teste si on a des options pour le champ courant
					foreach ($aValue->option as $aOption) { //On parcours les options
						
						$aElementsForm['formulaire'][$iCptQuestion]['option'][(String)$aOption['value']] = (String)$aOption;
					}
				}		
				
				$sLastLabelDefault = (String)$aQuestion->label; //Affectation du libellé de la dernière question				
			}			
			
			foreach($aQuestion->input_params as $params) {
								
				$aElementsForm['formulaire'][$iCptQuestion]['input_params']['value'] = (String)$params['value'];
				$aElementsForm['formulaire'][$iCptQuestion]['input_params']['title'] = (String)$params['title'];
				$aElementsForm['formulaire'][$iCptQuestion]['input_params']['label'] = (String)$params['label'];
				$aElementsForm['formulaire'][$iCptQuestion]['input_params']['div'] = (String)$params['div'];				
				$aElementsForm['formulaire'][$iCptQuestion]['input_params']['class'] = (String)$params['class'];				
			}
		}
		
		ksort($aElementsForm['formulaire']);		
		return $aElementsForm;	
	}
	
/**
 * function format_form
 * 
 * Cette fonction est chargée de formater un tableau contenant la liste des contraintes, le nom du formulaire ainsi que la liste des champs du formulaire
 * Ces tableaux seront ensuite envoyé au controller qui dispachera au model et à la vue
 * 
 * @param 	integer $formName Identifiant du formulaire
 * @return 	array 	Tableaux des contraintes et des différents champs du formulaire
 * @access 	public
 * @author 	koéZionCMS
 * @version 0.1 - 14/09/2012 by FI
 */
	function format_form($formName) {
		
		$xmlForm = $this->get_xml_form($formName); //Récupération du formulaire sous forme de tableau
		$aValidates = $this->get_constraint($formName, $xmlForm); //Récupération des contraintes
		$aElementsForm = $this->get_array_form($formName, $xmlForm); //Récupération des champs de formulaire
				
		return array(
			'validate' => $aValidates,
			'formInfos' => $aElementsForm['form_infos'],
			'formulaire' => $aElementsForm['formulaire'],
			'formulaireHtml' => $aElementsForm['html'],
		);
	}
}
?>