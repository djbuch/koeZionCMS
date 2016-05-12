<div class="form-group <?php if(isset($formerrors['name']) && !empty($formerrors['name'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Nom du site"); ?></label>
	<input type="text" value="<?php echo isset($datas['name']) ? $datas['name'] : '' ?>" name="name" class="form-control" />
	<?php if(isset($formerrors['name']) && !empty($formerrors['name'])) { ?><label class="error"><?php echo $formerrors['name'].'<br />'; ?></label><?php } ?>
</div>
<div class="form-group <?php if(isset($formerrors['url']) && !empty($formerrors['url'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Url du site (Avec HTTP)"); ?></label>
	<input type="text" value="<?php echo isset($datas['url']) ? $datas['url'] : '' ?>" name="url" class="form-control" />
	<?php if(isset($formerrors['url']) && !empty($formerrors['url'])) { ?><label class="error"><?php echo $formerrors['url'].'<br />'; ?></label><?php } ?>
</div>
<?php /* ?>
<div class="form-group <?php if(isset($formerrors['template_id']) && !empty($formerrors['template_id'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Template"); ?></label>
	<select name="template_id" class="form-control">
		<option value="">Sélectionnez un template</option>
		<?php 				
		//Parcours de l'ensemble des données du select
		foreach($templatesList as $k => $v) {
		
			if($datas['template_id'] == $k) { $selected=' selected="selected"'; } else { $selected = ''; }
			?><option value="<?php echo $v['id']; ?>"<?php echo $selected; ?>><?php echo $v['name']; ?></option><?php
		} 
		?>
	</select>
	<?php if(isset($formerrors['template_id']) && !empty($formerrors['template_id'])) { ?><label class="error"><?php echo $formerrors['template_id'].'<br />'; ?></label><?php } ?>
<?php */ ?>
</div>