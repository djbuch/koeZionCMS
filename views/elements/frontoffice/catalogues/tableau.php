<table cellpadding="0" cellspacing="0" id="ethernatable" class="catalogues">
	<tbody>
		<tr class="title">			
			<th colspan="7"><h6>Catalogue</h6></th>
		</tr>
		<tr class="fields">			
			<th class="image">&nbsp;</th>
			<th class="reference">
				<h6>Ref.</h6>
				<?php $moreParamsUrl = $helpers['Paginator']->gest_more_params(array('orderref')); ?>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?orderref=asc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_down.gif', array("class" => "order_table_by")); ?></a>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?orderref=desc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_up.gif', array("class" => "order_table_by")); ?></a>
			</th>
			<th>
				<h6>Désignation</h6>
				<?php $moreParamsUrl = $helpers['Paginator']->gest_more_params(array('ordername')); ?>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?ordername=asc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_down.gif', array("class" => "order_table_by")); ?></a>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?ordername=desc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_up.gif', array("class" => "order_table_by")); ?></a>
			</th>
			<th class="doc">&nbsp</th>
		</tr>
		<?php		 
		$nbLignes = count($catalogues);
		$numLigne = 0;
		foreach($catalogues as $k => $v) {

			if($numLigne == 0) { $cssTr = 'even'; $numLigne = 1; } else { $cssTr = 'odd'; $numLigne = 0; }			
			?>
			<tr class="<?php echo $cssTr; ?>">
				<td class="center">
					<?php if(file_exists(UPLOAD.DS."images".DS."Fiches-techniques".DS.$v['reference'].".jpg")) { ?>
						<img src="<?php echo BASE_URL."/upload/images/Fiches-techniques/".$v['reference'].".jpg"; ?>" style="width:48px;height:48px" />
					<?php } else { ?>
						<?php echo $helpers['Html']->img('frontoffice/no_image_48_48.png'); ?>
					<?php } ?>
				</td>
				<td class="center"><?php echo $v['reference']; ?></td>
				<td><?php echo $v['name']; ?></td>
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

