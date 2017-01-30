<? 
	$zz= new zetro_manager;
	$file='asset/bin/zetro_menu.dll';
	$z_config='asset/bin/zetro_config.dll'
?>

<div id="" style="display:block; padding-top:70px;">
<table width="100%"><tr valign="bottom" align="center">
<td width='100%' align="center">
<img src='<?=base_url();?>asset/img/zetropos.png' /><br />
<table width='50%' align="center">
<tr height='50px'><td>&nbsp;</td></tr>
<tr><td width='100%' align="left">License To :</td></tr>
<tr><td><font style="font-family:'Arial Black', Gadget, sans-serif; font-size:large">
<?=$zz->rContent('InfoCo',"Name",$z_config);?></font></td></tr>
<tr><td><?=$zz->rContent('InfoCo',"Address",$z_config);?></td></tr>
<tr><td><?=$zz->rContent('InfoCo',"Kota",$z_config)." ".$zz->rContent('InfoCo',"Propinsi",$z_config);?></td></tr>
<tr><td><?=empty($serial)?"Not Licenced":"Serial Number : ".$serial;?></td></tr>
</table>
<hr />
<!--img src='<?=base_url();?>asset/img/kanz.png' /><?=nbs(5);?><img src='<?=base_url();?>asset/img/zetro_z.png' /--></td></tr>
<tr><td align='center'>Contact Info :</td>
<!--<tr><td align='center'><img src='<?=base_url();?>asset/img/icon_kanz.png' /><?=nbs(2);?><a style='color:#00F;' href='http://www.smarthome-kanz.com'>www.smarthome-kanz.com</a> | Contact : HP: 0817212217 , email :contact@smarthome-kanz.com |</td>
--><tr><td align='center'><img src='<?=base_url();?>asset/img/icon_zetro_z.png' /><?=nbs(2);?><a style='color:#00F;' href='http://zetrosoft.com'>www.zetrosoft.com</a> | Contact : HP: 0812 1998 7377 |</td>
</table>
<? //echo file_get_contents("http://192.168.1.1");?>
</td></tr>
</table>

</div>
