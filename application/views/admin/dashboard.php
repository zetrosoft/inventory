<div id="" style="display:; padding-top:70px;">
<? 
	$zz= new zetro_manager;
	$file='asset/bin/zetro_menu.dll';
	$z_config='asset/bin/zetro_config.dll';
?>
<table width="100%" border='0'>
<tr valign="bottom" align="" style="padding:20px">
<td width='100%' align="center" valign="middle"><img src='<?=base_url();?>asset/img/about.png' /></td>
</tr>
</table>
<hr />
<? //echo (no_ser()=='6953b843f6cb0cd37e11d1cc485d2d79')?
	?>
<input type='hidden' id='lcs' value='<?=empty($menus)?'':base64_decode($menus);?>' />
<div align="right" style="padding:10px; padding-right:25px; font-size:; color:#000000">User Login: <?=$this->session->userdata('username');?>-<?=$this->session->userdata('gudang');?></div>

</div>
<script language="javascript">
	$(document).ready(function(e) {
		($('#lcs').val()!='')?
       	$('div.menu').show():
		$('div.menu').hide();
    });
</script>
