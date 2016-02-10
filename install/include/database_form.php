<input type="hidden" value="1" name="valid_database_form" />
<input type="hidden" value="<?php echo isset($datas['section']) ? $datas['section'] : $section ?>" name="section" />

<div class="form-group <?php if(isset($formerrors['host']) && !empty($formerrors['host'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Database Hostname"); ?></label>
	<input type="text" value="<?php echo isset($datas['host']) ? $datas['host'] : 'localhost' ?>" name="host" class="form-control" />
	<?php if(isset($formerrors['host']) && !empty($formerrors['host'])) { ?><label class="error"><?php foreach($formerrors['host'] as $rule => $error) { echo $error.'<br />'; } ?></label><?php } ?>
</div>
<div class="form-group <?php if(isset($formerrors['socket']) && !empty($formerrors['socket'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Connexion via socket"); ?></label>
	<input type="text" value="<?php echo isset($datas['socket']) ? $datas['socket'] : '' ?>" name="socket" class="form-control" />
	<?php if(isset($formerrors['socket']) && !empty($formerrors['socket'])) { ?><label class="error"><?php foreach($formerrors['socket'] as $rule => $error) { echo $error.'<br />'; } ?></label><?php } ?>
</div>
<div class="form-group <?php if(isset($formerrors['login']) && !empty($formerrors['login'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Database Username"); ?></label>
	<input type="text" value="<?php echo isset($datas['login']) ? $datas['login'] : 'root' ?>" name="login" class="form-control" />
	<?php if(isset($formerrors['login']) && !empty($formerrors['login'])) { ?><label class="error"><?php foreach($formerrors['login'] as $rule => $error) { echo $error.'<br />'; } ?></label><?php } ?>
</div>
<div class="form-group <?php if(isset($formerrors['password']) && !empty($formerrors['password'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Database Password"); ?></label>
	<input type="password" value="<?php echo isset($datas['password']) ? $datas['password'] : '' ?>" name="password" class="form-control" />
	<?php if(isset($formerrors['password']) && !empty($formerrors['password'])) { ?><label class="error"><?php foreach($formerrors['password'] as $rule => $error) { echo $error.'<br />'; } ?></label><?php } ?>
</div>
<div class="form-group <?php if(isset($formerrors['database']) && !empty($formerrors['database'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Database Name"); ?></label>
	<input type="text" value="<?php echo isset($datas['database']) ? $datas['database'] : 'koezion' ?>" name="database" class="form-control" />
	<?php if(isset($formerrors['database']) && !empty($formerrors['database'])) { ?><label class="error"><?php foreach($formerrors['database'] as $rule => $error) { echo $error.'<br />'; } ?></label><?php } ?>
</div>
<div class="form-group <?php if(isset($formerrors['port']) && !empty($formerrors['port'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Database Name"); ?></label>
	<input type="text" value="<?php echo isset($datas['port']) ? $datas['port'] : '' ?>" name="port" class="form-control" />
	<?php if(isset($formerrors['port']) && !empty($formerrors['port'])) { ?><label class="error"><?php foreach($formerrors['port'] as $rule => $error) { echo $error.'<br />'; } ?></label><?php } ?>
</div>
<div class="form-group <?php if(isset($formerrors['source']) && !empty($formerrors['source'])) { echo 'has-error'; } ?>">
	<label><?php echo ("Database Name"); ?></label>
	<input type="text" value="<?php echo isset($datas['source']) ? $datas['source'] : 'mysql' ?>" name="source" class="form-control" />
	<?php if(isset($formerrors['source']) && !empty($formerrors['source'])) { ?><label class="error"><?php foreach($formerrors['source'] as $rule => $error) { echo $error.'<br />'; } ?></label><?php } ?>
</div>