<table cellpadding="0" cellspacing="0" id="ethernatable" class="catalogues coup_coeur">
	<tbody>
		<tr class="title">			
			<th colspan="2"><h6>Notre coup de coeur</h6></th>
		</tr>
		<tr>			
			<td class="img">
				<img src="<?php echo BASE_URL."/upload/images/Fiches-techniques/".$coupCoeur['reference'].".jpg"; ?>" style="width:120px;height:120px" />							
			</td>
			<td>
				<h6><?php echo $coupCoeur['name']; ?></h6>
				<span class="reference">ref. <?php echo $coupCoeur['reference']; ?></span>
				<span class="type">Type : <?php echo $coupCoeur['type']; ?></span>
				<a href="<?php echo BASE_URL."/upload/files/Fiches-techniques/".$coupCoeur['reference'].".pdf"; ?>" class="blank" title="Télécharger la fiche technique"><img src="<?php echo BASE_URL; ?>/img/frontoffice/pdf.png" alt="Télécharger la fiche technique" /></a>
			</td>
		</tr>
	</tbody>
</table>