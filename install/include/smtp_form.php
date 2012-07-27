<form action="index.php?step=smtp" method="post">
	<div class="row">
		<label>SMTP Host</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['smtp_host']) ? $datas['smtp_host'] : '' ?>" name="smtp_host" /></div>
		<?php 
		if(isset($formerrors['smtp_host']) && !empty($formerrors['smtp_host'])) {
			
			?><div class="system error"><?php foreach($formerrors['smtp_host'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>SMTP Port</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['smtp_port']) ? $datas['smtp_port'] : '' ?>" name="smtp_port" /></div>
		<?php 
		if(isset($formerrors['smtp_port']) && !empty($formerrors['smtp_port'])) {
			
			?><div class="system error"><?php foreach($formerrors['smtp_port'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>SMTP Username</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['smtp_user_name']) ? $datas['smtp_user_name'] : '' ?>" name="smtp_user_name" /></div>
		<?php 
		if(isset($formerrors['smtp_user_name']) && !empty($formerrors['smtp_user_name'])) {
			
			?><div class="system error"><?php foreach($formerrors['smtp_user_name'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>SMTP Password</label>
		<div class="rowright"><input type="password" value="<?php echo isset($datas['smtp_password']) ? $datas['smtp_password'] : '' ?>" name="smtp_password" /></div>
		<?php 
		if(isset($formerrors['smtp_password']) && !empty($formerrors['smtp_password'])) {
			
			?><div class="system error"><?php foreach($formerrors['smtp_password'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>Mail From (email)</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['mail_set_from_email']) ? $datas['mail_set_from_email'] : '' ?>" name="mail_set_from_email" /></div>
		<?php 
		if(isset($formerrors['mail_set_from_email']) && !empty($formerrors['mail_set_from_email'])) {
			
			?><div class="system error"><?php foreach($formerrors['mail_set_from_email'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>Mail From (name)</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['mail_set_from_name']) ? $datas['mail_set_from_name'] : '' ?>" name="mail_set_from_name" /></div>
		<?php 
		if(isset($formerrors['mail_set_from_name']) && !empty($formerrors['mail_set_from_name'])) {
			
			?><div class="system error"><?php foreach($formerrors['mail_set_from_name'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>
	<div class="row">
		<label>Copie cachée</label>
		<div class="rowright"><input type="text" value="<?php echo isset($datas['bcc_email']) ? $datas['bcc_email'] : '' ?>" name="bcc_email" /></div>
		<?php 
		if(isset($formerrors['bcc_email']) && !empty($formerrors['bcc_email'])) {
			
			?><div class="system error"><?php foreach($formerrors['bcc_email'] as $rule => $error) { echo $error.'<br />'; } ?></div><?php
		}
		?>
	</div>					
	<div class="row" style="text-align: right;"><button class="medium grey" type="submit"><span>Configurer le serveur SMTP</span></button></div>
</form>