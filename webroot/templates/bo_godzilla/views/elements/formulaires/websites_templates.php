<?php
$selectedTemplate = '';
$othersTemplates = '';
foreach($templatesList as $k => $templateValue) {
	
	if($templateValue['id'] == $currentTemplateId) { $selectedTemplate .= $helpers['Form']->radiobutton_templates('template_id', $templateValue['id'], $templateValue['name'], $templateValue['layout'], $templateValue['code'], $templateValue['color']); } 
	else { $othersTemplates .= $helpers['Form']->radiobutton_templates('template_id', $templateValue['id'], $templateValue['name'], $templateValue['layout'], $templateValue['code'], $templateValue['color']); }				
}			
?>		
<div class="prettyRadiobuttons clearfix">
	<input type="hidden" id="InputTemplateId0" name="template_id" value="0" />
	<?php echo $selectedTemplate; ?>
	<span style="display: block;width: 100%;height: 1px;float: left;">&nbsp;</span>
	<?php echo $othersTemplates; ?>				
</div>