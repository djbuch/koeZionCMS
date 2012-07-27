<div class="section">
	<div class="box">
		
		<div class="title">
			<h2><?php echo _("Importer des utilisateurs"); ?></h2>
			<a class="btn black" href="<?php echo Router::url('backoffice/'.$params['controllerFileName'].'/index'); ?>" style="float: right; margin-top: 3px;"><span><?php echo _("Listing des utilisateurs"); ?></span></a>		
		</div>		
		<div class="content nopadding">
			<?php			
			$formOptions = array('id' => $params['modelName'].'Import', 'action' => Router::url('backoffice/'.$params['controllerFileName'].'/import'), 'method' => 'post', 'enctype' => 'multipart/form-data');
			echo $helpers['Form']->create($formOptions); 
			echo $helpers['Form']->upload_files('file'); 
			$typeList = array('admin' => "Administrateur", 'website_admin' => "Administrateur de site", 'user' => 'Utilisateur');
			echo $helpers['Form']->input('role', "Type d'utilisateur", array('type' => 'select', 'datas' => $typeList, 'firstElementList' => _("Sélectionnez un type"), 'tooltip' => "Indiquez le type de cet utilisateur"));
			echo $helpers['Form']->input('users_group_id', "Groupe d'utilisateurs", array('type' => 'select', 'datas' => $usersGroupList, 'firstElementList' => _("Sélectionnez un groupe"), 'tooltip' => "Indiquez le groupe de cet utilisateur"));
			
			echo $helpers['Form']->end(true); 
			?>
			<!--  <input id="xFilePath" name="FilePath" type="text" size="60" />
			<input type="button" value="Browse Server" onclick="BrowseServer();" /> -->
			
		</div>
	</div>
</div>