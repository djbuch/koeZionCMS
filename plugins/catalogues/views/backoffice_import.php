<div class="section">
	<div class="box">
		
		<div class="title">
			<h2><?php echo _("Imports"); ?></h2>
			<a class="btn black" href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/index'); ?>" style="float: right; margin-top: 3px;"><span><?php echo _("Listing des produits"); ?></span></a>
		</div>		
		<div class="content nopadding">			
			<?php 
			
			$formOptions = array('id' => $params['modelName'].'Import', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/import'), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions);			 
			echo $helpers['Form']->upload_files('file');
			echo $helpers['Form']->input('category_id', 'Catégorie parente', array('type' => 'select', 'datas' => $categoriesList, 'tooltip' => "Indiquez la catégorie parente de cet article, c'est à partir de cette catégorie que cet article sera accessible"));		
			echo $helpers['Form']->end(true); 
			?>
			<!--  <input id="xFilePath" name="FilePath" type="text" size="60" />
			<input type="button" value="Browse Server" onclick="BrowseServer();" /> -->
			
		</div>
	</div>
</div>