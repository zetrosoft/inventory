<div class='contents'>
<div class="pn_content" align="center">
<hr><br><br><br><br>
<img src="<?=base_url()."asset/images/warning.png";?>">
<font style="font-family:'20th Century Font', Arial; color:#DD0000; font-size:xx-large">
<? $zn= new zetro_manager();
	echo $zn->rContent("Message","NoAuth","asset/bin/form.cfg");
	echo !empty($msg)? $msg:'';
	?>
	</font>
 </div>
 </div>