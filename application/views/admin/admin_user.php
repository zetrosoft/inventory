<?php
link_css('login.css,reset.css','asset/css,asset/css');
?>
<div id="login_box">
	<script language="javascript">
	  $(document).ready(function(e) {
        $('input[name="password"]').focus().select();
    });
	</script>
	<div id='H0'>&nbsp;Create first user as Super User</div>
	<?php
		$attributes = array('name' => 'login_form', 'id' => 'login_form');
		echo form_open('admin/process_userfirst', $attributes);
	?>
		
		<p>
			<label for="username">User ID:</label>
			<input type="text" name="username" size="20" class="form_field" value="superuser" readonly="readonly">			
		</p>
		
		<p>
			<label for="password">Password:</label>
			<input type="password" name="password" size="20" class="form_field" value="" required="required"/>			
		</p>
		
		<p>
			<input type="submit" name="submit" id="submit" style="width:auto" value="Create Super User" />
		</p>
	</form>
		<?php 
			$message = $this->session->flashdata('message');
			echo $message == '' ? '' : '<p id="message">' . $message . '</p>';
		?>
		<?php echo form_error('username','<p class="field_error">', '</p>');?>
		<?php echo form_error('password','<p class="field_error">', '</p>');?>
</div>
