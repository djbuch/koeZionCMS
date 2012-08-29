<table cellpadding="0" cellspacing="0" id="ethernatable" class="catalogues recherche">
	<tbody>
		<tr class="title">			
			<th ><h6>Recherche</h6></th>
		</tr>
		<tr class="content">			
			<td>
				<?php 
				$formOptions = array('id' => 'FormRecherche', 'action' => Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug']), 'method' => 'get');
				echo $helpers['Form']->create($formOptions);				
				?>
					<table cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td class="lib">Désignation :</td>
								<td>
									<?php echo $helpers['Form']->input('rechercher', _('ok'), array('label' => false, 'div' => false, 'displayError' => false, 'type' => 'submit', "class" => "superbutton", 'value' => _('ok')));  ?>
									<?php echo $helpers['Form']->input('name', '', array('label' => false, 'div' => false, 'displayError' => false)); ?>
								</td>
							</tr>
						</tbody>
					</table>	
				<?php echo $helpers['Form']->end(); ?>
			</td>
		</tr>
	</tbody>
</table>