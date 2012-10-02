<form action="index.php?step=database_params" method="post">
	<input type="hidden" value="1" name="valid_database_form" />
	<input type="hidden" value="<?php echo isset($datas['section']) ? $datas['section'] : $section ?>" name="section" />
	<div class="row">
		<label>Database Hostname</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['host']) ? $datas['host'] : 'localhost' ?>" name="host" /></div>
		<?php 
		if(isset($formerrors['host']) && !empty($formerrors['host'])) {
			
			?><div class="system error"><?php foreach($formerrors['host'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>Database Username</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['login']) ? $datas['login'] : 'root' ?>" name="login" /></div>
		<?php 
		if(isset($formerrors['login']) && !empty($formerrors['login'])) {
			
			?><div class="system error"><?php foreach($formerrors['login'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>Database Password</label>
		<div class="rowright"><input type="password" value="<?php echo isset($datas['password']) ? $datas['password'] : '' ?>" name="password" /></div>
		<?php 
		if(isset($formerrors['password']) && !empty($formerrors['password'])) {
			
			?><div class="system error"><?php foreach($formerrors['password'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>Database Name</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['database']) ? $datas['database'] : 'koezion' ?>" name="database" /></div>
		<?php 
		if(isset($formerrors['database']) && !empty($formerrors['database'])) {
			
			?><div class="system error"><?php foreach($formerrors['database'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<?php /* ?>
	<div class="row">
		<label>Table prefix</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['prefix']) ? $datas['prefix'] : '' ?>" name="prefix" /></div>
		<?php 
		if(isset($formerrors['prefix']) && !empty($formerrors['prefix'])) {
			
			?><div class="system error"><?php foreach($formerrors['prefix'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<?php */ ?>
	<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Tester la connexion à la base de données</span></button></div>
</form>