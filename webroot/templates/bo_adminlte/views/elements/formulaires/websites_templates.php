<table class="table templates_table">
	<tbody>
		<?php
		$currentTemplate 	= '';
		$othersTemplates 	= '';
		$i 					= 0;
		foreach($templatesList as $k => $templateValue) {

			////////////////////////////////////////////
			//    IMAGE D'ILLUSTRATION DU TEMPLATE    //
			$thumb = '<img src="'.BASE_URL.'/img/templates/noimg.png" />';
			$imgFile = WEBROOT.DS.'img'.DS.'templates'.DS.$templateValue['layout'].DS.$templateValue['code'].DS.'background.png';			
			if(file_exists($imgFile)) { $thumb = '<img src="'.BASE_URL.'/img/templates/'.$templateValue['layout'].'/'.$templateValue['code'].'/background.png" />'; }
			else if(!empty($templateColor)) {
			
				$firstChar = $templateColor{0};
				if($firstChar == "#") { $thumb = '<span style="display:block;width:80px;height:72px;padding:0;margin:5px;background:'.$templateColor.'">&nbsp</span>'; }
				else if(!empty($templateColor)) { $thumb = '<img src="'.$templateColor.'" />'; }
			}
			
			///////////////////////////////////////
			//    GESTION DU TEMPLATE COURANT    //
			if($templateValue['id'] == $currentTemplateId) {	
				
				$currentTemplate .= '<tr>';
				$currentTemplate .= '<td class="text-center" style="width:25%;">';
				$currentTemplate .= $thumb;
				$currentTemplate .= $helpers['Form']->input('template_id', $templateValue['name'], array('type' => 'radio', 'value' => $templateValue['id']));
				$currentTemplate .= '</td>';
				$currentTemplate .= '<td colspan="3">&nbsp;</td>';
				$currentTemplate .= '</tr>';				
			} 
			
			////////////////////////////////////////////////////
			//    GESTION DES AUTRES TEMPLATES DISPONIBLES    //
			else {
				
				if($i==0) { $othersTemplates .= '<tr>'; }												
				$othersTemplates .= '<td class="text-center" style="width:25%;">';
				$othersTemplates .= $thumb; 
				$othersTemplates .= $helpers['Form']->input('template_id', $templateValue['name'], array('type' => 'radio', 'value' => $templateValue['id']));
				$othersTemplates .= '</td>';
				$i++;
				if($i==4) {
					
					$othersTemplates .= '</tr>';
					$i = 0;
				}				
			}
		}
		
		echo '<tr class="table_line_title">';
		echo '<th colspan="4">';
		echo _("Template courant");
		echo '</th>';
		echo '</tr>';
		echo $currentTemplate; 
		echo '<tr class="table_line_title">';
		echo '<th colspan="4">';
		echo _("Autres templates disponibles");
		echo '</th>';
		echo '</tr>';
		echo $othersTemplates;
		
		//Fermeture des td du tableau
		if($i<4) { 
			
			$colspan = 4 - $i;
			echo '<td colspan="'.$colspan.'"></td>'; 
		}									
		?>
	</tbody>
</table>