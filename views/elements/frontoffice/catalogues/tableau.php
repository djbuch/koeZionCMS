<table cellpadding="0" cellspacing="0" id="ethernatable" class="catalogues">
	<tbody>
		<tr class="title">			
			<th colspan="7"><h6>Catalogue</h6></th>
		</tr>
		<tr class="fields">			
			<th class="image">&nbsp;</th>
			<th class="disp">
				<h6>Disp.</h6>
				<?php $moreParamsUrl = $helpers['Paginator']->get_more_params(array('order')); ?>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?order[disponibility]=asc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_down.gif', array("class" => "order_table_by")); ?></a>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?order[disponibility]=desc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_up.gif', array("class" => "order_table_by")); ?></a>
			</th>
			<th class="reference">
				<h6>Ref.</h6>
				<?php $moreParamsUrl = $helpers['Paginator']->get_more_params(array('order.reference')); ?>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?order[reference]=asc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_down.gif', array("class" => "order_table_by")); ?></a>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?order[reference]=desc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_up.gif', array("class" => "order_table_by")); ?></a>
			</th>
			<th>
				<h6>Désignation</h6>
				<?php $moreParamsUrl = $helpers['Paginator']->get_more_params(array('order.name')); ?>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?order[name]=asc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_down.gif', array("class" => "order_table_by")); ?></a>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?order[name]=desc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_up.gif', array("class" => "order_table_by")); ?></a>
			</th>
			<th class="price">
				<h6>Prix</h6>
				<?php $moreParamsUrl = $helpers['Paginator']->get_more_params(array('order')); ?>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?order[price]=asc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_down.gif', array("class" => "order_table_by")); ?></a>
				<a href="<?php echo Router::url('categories/view/id:'.$category['id'].'/slug:'.$category['slug'])."?order[price]=desc".$moreParamsUrl; ?>"><?php echo $helpers['Html']->img('frontoffice/arrow_up.gif', array("class" => "order_table_by")); ?></a>
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
					<?php if(file_exists(UPLOAD.DS."images".DS."Catalogues".DS.$v['reference'].".jpg")) { ?>
						<img src="<?php echo BASE_URL."/upload/images/Catalogues/".$v['reference'].".jpg"; ?>" style="width:48px;height:48px" />
					<?php } else { ?>
						<?php echo $helpers['Html']->img('frontoffice/no_image_48_48.png'); ?>
					<?php } ?>
				</td>
				<td class="center"><span class="label <?php echo ($v['disponibility'] == 1) ? 'success' : 'error'; ?>"><?php echo ($v['disponibility'] == 1) ? '&nbsp;' : '&nbsp;'; ?></span></td>
				<td class="center"><?php echo $v['reference']; ?></td>
				<td><?php echo $v['name']; ?></td>
				<td class="center"><?php echo $v['price']; ?></td>
				<td class="center">
					<?php if(file_exists(UPLOAD.DS."files".DS."Catalogues".DS.$v['reference'].".pdf")) { ?>
						<a href="<?php echo BASE_URL."/upload/files/Catalogues/".$v['reference'].".pdf"; ?>" class="blank" title="Télécharger la fiche technique"><img src="<?php echo BASE_URL; ?>/img/frontoffice/pdf.png" alt="Télécharger la fiche technique" /></a>
					<?php } ?>
				</td>
			</tr>
			<?php 
		} 
		?>
	</tbody>
</table>