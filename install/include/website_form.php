<form action="index.php?step=website" method="post">
	<div class="row">
		<label>Nom du site</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['name']) ? $datas['name'] : '' ?>" name="name" /></div>
		<?php 
		if(isset($formerrors['name']) && !empty($formerrors['name'])) {
			
			?><div class="system error"><?php echo $formerrors['name']; ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>Url du site (Avec HTTP)</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['url']) ? $datas['url'] : '' ?>" name="url" /></div>
		<?php 
		if(isset($formerrors['url']) && !empty($formerrors['url'])) {
			
			?><div class="system error"><?php echo $formerrors['url']; ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>Template</label>
		<div class="rowright">
		
			<div class="styled-select">
				<select name="template_id">
					<option value="">Sélectionnez un template</option>
					<?php 				
					//Parcours de l'ensemble des données du select
					foreach($templatesList as $k => $v) {
					
						if($datas['template_id'] == $k) { $selected=' selected="selected"'; } else { $selected = ''; }
						?><option value="<?php echo $v['id']; ?>"<?php echo $selected; ?>><?php echo $v['name']; ?></option><?php
					} 
					?>
				</select>
			</div>		
		</div>
		<?php 
		if(isset($formerrors['template_id']) && !empty($formerrors['template_id'])) {
			
			?><div class="system error"><?php echo $formerrors['template_id']; ?></div><?php
		}
		?>
	</div>					
	<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Configurer le site Internet</span></button></div>
</form>