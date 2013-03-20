<div class="section">
	<div class="box">
		
		<div class="title">
			<h2><?php echo _("Logs utilisateur")." - ".$userName['name'].' - '.$userName['second_name'].' <i>(IDENTIFIANT : '.$userName['id'].')</i>'; ?></h2>
			<?php echo $helpers['Html']->backoffice_button_title($params['controllerFileName'], 'index', "Listing"); ?>
		</div>	
		<div class="content">
			<?php 
			$formOptions = array('id' => $params['modelName'].'Logs', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/logs/'.$userName['id']), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions); 
				echo $helpers['Form']->input('date', 'Afficher les logs pour la journée du :', array("class" => "datepicker", "placeholder" => "dd.mm.yy"));
			echo $helpers['Form']->end(true); 
			?>	
				
			<?php 
			if(!empty($usersLogs)) { 
				?>
				<table>
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo _("Date"); ?></th>
							<th><?php echo _("Heure"); ?></th>
							<th><?php echo _("Url"); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						foreach($usersLogs as $k => $v): 
						
							$date = $this->vars['components']['Text']->date_sql_to_human($v['date']);						
							?>
							<tr>
								<td class="txtcenter xxs"><?php echo $v['id']; ?></td>
								<td class="txtcenter xs"><?php echo $date['date']['fullNumber']; ?></td>
								<td class="txtcenter xs"><?php echo $date['time']['hm']; ?></td>
								<td><?php echo $v['url']; ?></td>
							</tr>
							<?php 
						endforeach; 
						?>
					</tbody>
				</table>
				<?php 				
			} else { 
				
				?><p>Aucun élément</p><?php 
			}
			?>
		</div>
	</div>
</div>