<?php 
/**
 * 
 * OPTIMISER LES TRAITEMENTS QUAND LE TEMPS SE PRESENTERA
 * IL EST ENVIRON 3H DU MATIN LES YEUX PIQUENT UN PEU :)
 * MERCI DE VOTRE COMPREHENSION!!!
 * 
 */
$css = array(
	'/backoffice/visualize/basic',
	'/backoffice/visualize/visualize',
	'/backoffice/visualize/visualize-light'
);		
echo $helpers['Html']->css($css, true);

$js = array(
	'/backoffice/visualize/excanvas',
	'/backoffice/visualize/visualize.jQuery',
	'/backoffice/visualize/start',
);
echo $helpers['Html']->js($js, true);
?>
<div class="section">
	<div class="box">
		<div class="title">
			<h2>Statistiques de visites</h2>			
		</div>
		<div class="content nopadding">
			<?php 
			$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName'].'/index'), 'method' => 'post');
			echo $helpers['Form']->create($formOptions);
			echo $helpers['Form']->input('display', '', array('type' => 'hidden', 'value' => 1)); 
			?>
			<div class="row">
				<label for="InputParentId-button"><b>Sélectionnez une période &gt;</b></label>
				<div class="rowright">
					début : 					 
					<?php echo $helpers['Form']->input('start', '', array("class" => "datepicker", "placeholder" => "dd.mm.yy", 'div' => false, 'label' => false, 'style' => 'margin-right:20px;')); ?> 
					fin : 
					<?php echo $helpers['Form']->input('end', '', array("class" => "datepicker", "placeholder" => "dd.mm.yy", 'div' => false, 'label' => false)); ?>
					<button class="medium grey" type="submit" style="opacity: 1;margin:-1px 0 0 20px;position:absolute;"><span>Envoyer</span></button>
				</div>
			</div>
			<?php echo $helpers['Form']->end(false); ?>
		</div>		
	</div>					
	<?php 
	if(isset($iDisplay) && $iDisplay) { 
				
		$aMonths = array(
			'01' => 'Jan',
			'02' => 'Fev',
			'03' => 'Mars',
			'04' => 'Avr',
			'05' => 'Mai',
			'06' => 'Jun',
			'07' => 'Jul',
			'08' => 'Aou',
			'09' => 'Sep',
			'10' => 'Oct',
			'11' => 'Nov',
			'12' => 'Dec'
		);
		
		$aGraphDatas = array();
		$aGraphDatas['Graph']['VU'] = array();
		$aGraphDatas['Months'] = array();
		foreach($aGraphVisitesUniques as $k => $v) {
			if($k == 'labels') {
				asort($v);
				foreach($v as $k1 => $v1) {
				//foreach(array_reverse($v) as $k1 => $v1) {
					
					$v1tmp = explode(' ', $v1);
					$aGraphDatas['Graph']['VU'][$aMonths[$v1tmp[0]]] = trim($v1tmp[1], '()');
					$aGraphDatas['Months'][] = $aMonths[$v1tmp[0]];
				}
			}
		}		
		$aGraphDatas['Graph']['VT'] = array();
		foreach($aGraphVisites as $k => $v) {
			if($k == 'labels') {
				asort($v);
				foreach($v as $k1 => $v1) {
				//foreach(array_reverse($v) as $k1 => $v1) {
		
					$v1tmp = explode(' ', $v1);
					$aGraphDatas['Graph']['VT'][$aMonths[$v1tmp[0]]] = trim($v1tmp[1], '()');
				}
			}
		}		
		$aGraphDatas['Graph']['PV'] = array();
		foreach($aGraphPagesVues as $k => $v) {
			if($k == 'labels') {
				asort($v);
				foreach($v as $k1 => $v1) {
				//foreach(array_reverse($v) as $k1 => $v1) {
		
					$v1tmp = explode(' ', $v1);
					$aGraphDatas['Graph']['PV'][$aMonths[$v1tmp[0]]] = trim($v1tmp[1], '()');
				}
			}
		}		
		
		//pr("GRAPH DATAS");
		//pr($aGraphDatas);		
		//pr("Visiteurs Uniques");
		//pr($aGraphVisitesUniques);			
		//pr("Visites Totales");
		//pr($aGraphVisites);
		//pr("Pages Vues");
		//pr($aGraphPagesVues);
		?>
		<div class="box">
			<div class="title">
				<h2>Statistiques du <?php echo $sGraphStart; ?> au <?php echo $sGraphEnd; ?></h2>
			</div>
			<div class="content">
				<table class="toGraph">
					<caption>Statistiques du <?php echo $sGraphStart; ?> au <?php echo $sGraphEnd; ?></caption>
					<thead>
						<tr>
							<td></td>
							<?php foreach($aGraphDatas['Months'] as $month) { ?>
								<th scope="col"><?php echo $month; ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">Visiteurs uniques</th>
							<?php foreach($aGraphDatas['Graph']['VU'] as $nb) { ?>
								<td><?php echo $nb; ?></td>
							<?php } ?>
						</tr>
						<tr>
							<th scope="row">Visites totales</th>
							<?php foreach($aGraphDatas['Graph']['VT'] as $nb) { ?>
								<td><?php echo $nb; ?></td>
							<?php } ?>
						</tr>
						<tr>
							<th scope="row">Pages vues</th>
							<?php foreach($aGraphDatas['Graph']['PV'] as $nb) { ?>
								<td><?php echo $nb; ?></td>
							<?php } ?>
						</tr>		
					</tbody>
				</table>
				<table style="width:100%;margin-top:20px;">
					<thead>
						<tr>
							<td style="width:15%"></td>
							<?php foreach($aGraphDatas['Months'] as $month) { ?>
								<th scope="col"><?php echo $month; ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">Visiteurs uniques</th>
							<?php foreach($aGraphDatas['Graph']['VU'] as $nb) { ?>
								<td><?php echo $nb; ?></td>
							<?php } ?>
						</tr>
						<tr>
							<th scope="row">Visites totales</th>
							<?php foreach($aGraphDatas['Graph']['VT'] as $nb) { ?>
								<td><?php echo $nb; ?></td>
							<?php } ?>
						</tr>
						<tr>
							<th scope="row">Pages vues</th>
							<?php foreach($aGraphDatas['Graph']['PV'] as $nb) { ?>
								<td><?php echo $nb; ?></td>
							<?php } ?>
						</tr>		
					</tbody>
				</table>
			</div>
		</div>			
		<div class="section">	
			<div class="half">
				<div class="box">
					<div class="title">
						<h2>Visites</h2>
					</div>
					<div class="content nopadding">
						<table style="width : 100%;"> 
							<tbody> 
								<tr> 
									<td>Nombre de visite(s) :</td>
									<td><?php echo $visits; ?></td> 
								</tr> 
								<tr> 
									<td>Nombre de visite(s) unique(s)</td>
									<td><?php echo $unique_visits; ?></td> 
								</tr> 
								<tr> 
									<td>Page(s) vue(s)</td>
									<td><?php echo $page_views; ?></td> 
								</tr>
							</tbody> 
						</table>
					</div>
				</div>
				
				<div class="box">
					<div class="title">
						<h2>Pages vues par navigateurs</h2>
					</div>
					<div class="content nopadding">
						<table style="width : 100%;"> 
							<tbody> 
								<?php foreach($navigateurs['labels'] as $label) { ?>	
									<tr> 
										<td><?php echo $label; ?></td> 
									</tr> 
								<?php } ?>
							</tbody> 
						</table>				
					</div>
				</div>
				
				<div class="box">
					<div class="title">
						<h2>Pages vues par pays</h2>
					</div>
					<div class="content nopadding">
						<table style="width : 100%;"> 
							<tbody> 
								<?php foreach($countries['labels'] as $label) { ?>	
									<tr> 
										<td><?php echo $label; ?></td> 
									</tr> 
								<?php } ?>
							</tbody> 
						</table>					
					</div>
				</div>
				
				<div class="box">
					<div class="title">
						<h2>Pages vues par sources</h2>
					</div>
					<div class="content nopadding">
						<table style="width : 100%;"> 
							<tbody> 
								<?php foreach($source['labels'] as $label) { ?>	
									<tr> 
										<td><?php echo $label; ?></td> 
									</tr> 
								<?php } ?>
							</tbody> 
						</table>					
					</div>
				</div>
			</div>
				
			<div class="half">
				<div class="box">
					<div class="title">
						<h2>Pages vues par mots clefs</h2>
					</div>
					<div class="content nopadding">
						<table style="width : 100%;"> 
							<tbody> 
								<?php foreach($keywords['labels'] as $label) { ?>	
									<tr> 
										<td><?php echo $label; ?></td> 
									</tr> 
								<?php } ?>
							</tbody> 
						</table>
					</div>
				</div>
			</div>
		</div>			
		<div class="section">	
			<div class="half">
				
				<div class="box">
					<div class="title">
						<h2>Pages les + vues (Top 20)</h2>
					</div>
					<div class="content nopadding">
						<table style="width : 100%;"> 
							<tbody> 
								<?php
								for($i=0;$i<20;$i++) {
									if(isset($pagePath['labels'][$i])) { 
										?><tr> 
											<td><?php echo $pagePath['labels'][$i]; ?></td> 
										</tr><?php 
									}
								}
								?>	
							</tbody> 
						</table>						
					</div>
				</div>
			</div>
				
			<div class="half">				
				<div class="box">
					<div class="title">
						<h2>Pages les - vues (Top 20)</h2>
					</div>
					<div class="content nopadding">	
						<table style="width : 100%;"> 
							<tbody> 
								<?php										
								$iNbPagesVues = count($pagePath['labels']);
								$iNbPagesVuesStart = $iNbPagesVues - 20;
								
								for($i=$iNbPagesVuesStart;$i<$iNbPagesVues;$i++) {
									if(isset($pagePath['labels'][$i])) { 
										?><tr> 
											<td><?php echo $pagePath['labels'][$i]; ?></td> 
										</tr><?php 
									}
								}
								?>	
							</tbody> 
						</table>				
					</div>
				</div>
			</div>
		</div>
		<?php 
	} else if(isset($sMessageErreurGa)) { 
		
		?>
		<div class="box">
			<div class="title">
				<h2>Erreur</h2>
			</div>
			<div class="content"><?php echo $sMessageErreurGa; ?></div>
		</div>		
		<?php		 
	} 
?>	
</div>