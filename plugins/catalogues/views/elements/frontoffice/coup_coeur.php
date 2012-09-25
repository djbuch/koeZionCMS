<?php if(!empty($coupCoeur)) { ?>
	<table cellpadding="0" cellspacing="0" id="ethernatable" class="catalogues coup_coeur">
		<tbody>
			<tr class="title">			
				<th colspan="2"><h6>Coup de coeur</h6></th>
			</tr>
			<tr>			
				<td class="img">
					<?php if(file_exists(UPLOAD.DS."images".DS."Fiches-techniques".DS.$coupCoeur['reference'].".jpg")) { ?>
						<img src="<?php echo BASE_URL."/upload/images/Fiches-techniques/".$coupCoeur['reference'].".jpg"; ?>" style="width:120px;height:120px" />
					<?php } else { ?>
						<?php echo $helpers['Html']->img('frontoffice/no_image_120_120.png'); ?>
					<?php } ?>										
				</td>
				<td>
					<h6><?php echo $coupCoeur['name']; ?></h6>
					<span class="reference">ref. <?php echo $coupCoeur['reference']; ?></span>
					<?php if(file_exists(UPLOAD.DS."files".DS."Fiches-techniques".DS.$coupCoeur['reference'].".pdf")) { ?>
						<a href="<?php echo BASE_URL."/upload/files/Fiches-techniques/".$coupCoeur['reference'].".pdf"; ?>" class="blank" title="Télécharger la fiche technique"><img src="<?php echo BASE_URL; ?>/img/frontoffice/pdf.png" alt="Télécharger la fiche technique" /></a>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
<?php } ?>