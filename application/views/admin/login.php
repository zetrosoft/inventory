<link href="<?php echo base_url(); ?>asset/css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>asset/css/login.css" rel="stylesheet" type="text/css" />
<div id="login_box">
	
	<div id="H0">&nbsp;</div/>
	
	<?php
		$attributes = array('name' => 'login_form', 'id' => 'login_form');
		echo form_open('admin/process_login', $attributes);
	?>
		
		
		<p>
			<label for="username">User ID:</label>
			<input type="text" name="username" size="20" class="form_field" value="<?php echo set_value('username');?>"/>			
		</p>
		
		<p>
			<label for="password">Password:</label>
			<input type="password" name="password" size="20" class="form_field" value="<?php echo set_value('password');?>"/>			
		</p>
		
		<p>
			<input type="submit" name="submit" id="submit" value="Login" />
		</p>
	</form>
		<?php 
			echo empty($error) ? '' : '<p id="message">' . $error . '</p>';
		?>
		<?php echo form_error('username','<p class="field_error">', '</p>');?>
		<?php echo form_error('password','<p class="field_error">', '</p>');?>

<input type='hidden' id='lcs' value='<?=empty($login)?'x2cdg':$login;?>' />
</div>
<script language="javascript">
	$(document).ready(function(e) {
		$('input[name="username"]').val('');
		$('input[name="password"]').val('');
		($('#lcs').val()!='x2cdg' || $('#lcs').val()==false)?
        $('div.menu').show():$('div.menu').hide();
    });
</script>
