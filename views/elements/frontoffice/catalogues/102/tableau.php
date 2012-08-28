<table cellpadding="0" cellspacing="0" id="ethernatable" class="catalogues">
	<tbody>
		<tr class="title">			
			<th colspan="7"><h6>Tous nos produits</h6></th>
		</tr>
		<tr class="fields">			
			<th class="image">&nbsp;</th>
			<th class="reference"><h6>Ref.</h6></th>
			<th><h6>Désignation</h6></th>
			<th class="color"><h6>Couleur</h6></th>
			<th class="containing"><h6>Contenant</h6></th>
			<th class="origin"><h6>Origine</h6></th>
			<th class="doc">&nbsp</th>
		</tr>
		<?php		 
		$nbLignes = count($catalogues);
		$numLigne = 0;
		foreach($catalogues as $k => $v) {

			if($numLigne == 0) { $cssTr = 'even'; $numLigne = 1; } else { $cssTr = 'odd'; $numLigne = 0; }			
			?>
			<tr class="<?php echo $cssTr; ?>">
				<td class="center"><img src="<?php echo BASE_URL."/upload/images/Fiches-techniques/".$v['reference'].".jpg"; ?>" style="width:48px;height:48px" /></td>
				<td class="center"><?php echo $v['reference']; ?></td>
				<td><?php echo $v['name']; ?></td>
				<td class="center"><?php echo $v['color']; ?></td>
				<td class="center"><?php echo $v['containing']; ?></td>
				<td class="center"><?php echo $v['origin']; ?></td>
				<td class="center">
					<?php if(file_exists(UPLOAD.DS."files".DS."Fiches-techniques".DS.$v['reference'].".pdf")) { ?>
						<a href="<?php echo BASE_URL."/upload/files/Fiches-techniques/".$v['reference'].".pdf"; ?>" class="blank" title="Télécharger la fiche technique"><img src="<?php echo BASE_URL; ?>/img/frontoffice/pdf.png" alt="Télécharger la fiche technique" /></a>
					<?php } ?>
				</td>
			</tr>
			<?php 
		} 
		?>
	</tbody>
</table>

