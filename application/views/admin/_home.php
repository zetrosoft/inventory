<div id="" style="display:; padding-top:70px;">
<? 
	$zz= new zetro_manager;
	$file='asset/bin/zetro_menu.dll';
	$z_config='asset/bin/zetro_config.dll';
?>
<table width="100%" border='0'>
<tr valign="bottom" align="" style="padding:20px">
<td width='100%'><img src='<?=base_url();?>asset/img/PoS.png' /></td></tr>
</table>
<hr />
<br />
<table width='70%' border="0">
<tr align="center">
<? $mnu=$zz->Count('Menu Utama',$file);
$x=0;
	for ($i=1;$i<=$mnu;$i++ ){
		$gbr=explode('|',$zz->rContent('Menu Utama',$i,$file));
		if($gbr[3]!=''){
			$x++;
			if($x> 3){
				echo tr().
				td("<img src='".base_url()."asset/img/".$gbr[3]."' onclick=\"kliked('".$gbr[4]."');\">",'center','menux\' height=\'69px\' valign=\'middle').
				_tr();
			}else{
			echo td("<img src='".base_url()."asset/img/".$gbr[3]."' onclick=\"kliked('".$gbr[4]."');\">",'','menux\' height=\'69px\' valign=\'middle');
			}
		}else{
			echo '';
		}
	}
	
?>
</tr>
</table>
<? //echo (no_ser()=='6953b843f6cb0cd37e11d1cc485d2d79')?
	?>
<input type='hidden' id='lcs' value='<?=empty($serial)?'x2cdg':$serial;?>' />
</div>
<script language="javascript">
	$(document).ready(function(e) {
		///($('#lcs').val()!='x2cdg')?
       // $('div.menu').show():
		$('div.menu').hide();
    });
		function kliked(id){
			var path=$('#path').val();
			document.location.href=path+id;	
		}
</script>
