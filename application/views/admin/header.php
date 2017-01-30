<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>asset/images/icone.png" />
<?php
	date_default_timezone_set('Asia/Jakarta');
	$zz= new zetro_manager;
	$file='asset/bin/zetro_menu.dll';
	$z_config='asset/bin/zetro_config.dll';

link_css('portal-style.css,zetro_css.css,jquery.alerts.css','asset/css,asset/css,asset/css');
link_js('jquery-1.8.2.min.js,zetro_number.js,dropdown.js,jquery.alerts.js','asset/js,asset/js,asset/js,asset/js');
?>
<script language="javascript">
	$(document).ready(function(e) {
        $('.sample_attach').css('z-index','10000');
		$('a').click(function(){$('#ajax_display').show();})
	 $(document).keypress(function(e){
		if(e.keyCode==122){
			//return false;
		}else if(e.keyCode==121){
			return false;
		}else if(e.keyCode==114){
			return false;
		}
	 })
	
    });
</script>
<!--background:url(<?=base_url();?>asset/img/logo1.png) no-repeat left;-->
<title><?=$zz->rContent("WebPage","Title",$z_config)."-".$zz->rContent("WebPage","subtitle",$z_config);?></title>
</head>
<body>
<div id="menu-atas" style='z-index:9990'>
	<div class='logo' style=" vertical-align:middle">
    <?=$zz->rContent("InfoCo","Name",$z_config);?>
    </div>
    <div class="menu">
    <? 
	if($this->session->userdata('login')==true){
	 echo "<div class='welcome judul sorot' style='padding:5px;display:none'>User Login 
    		<span class='sorot'>".$this->session->userdata('username')."</span></div>\n";
	}
	$menul=$this->session->userdata('menus');
	$menune=empty($menul)?'':base64_decode($menul);
	$jml=$zz->Count($menune,$file);
		for ($i=1;$i<=$jml;$i++){
			$rst=explode('|',$zz->rContent($menune,$i,$file));
			$jml_sub=$zz->Count($rst[0],$file);
			 if($jml_sub==0){
				echo "<div class='sub-menu'><a href='".base_url()."index.php/".$rst[1]."'>".$rst[0]."</a></div>\n";
				 }else{
					echo ($rst[1]!=Null)?
					      "<div id='parent_$i' class='sample_attach'><a  href='".base_url()."index.php/".$rst[1]."'>".$rst[0]."</a></div>\n":
						  "<div id='parent_$i' class='sample_attach'>".$rst[0]."</div>\n";
			 }
			 if($jml_sub>0){
				 echo "<div id='child_$i'>\n";
				 for ($z=1;$z<=$jml_sub;$z++){
						 $rst1=explode('|',$zz->rContent($rst[0],$z,$file));
					 echo ($rst1[1]=='x')?
						  "<a class='sample_attach2' href='#'>".$rst1[0]."</a>\n": 
 	  					  "<a class='sample_attach' href='".base_url()."index.php/".$rst1[1]."'>".$rst1[0]."</a>\n";
				 }
				 echo "</div>\n";
			 echo "<script type='text/javascript'>at_attach('parent_$i', 'child_$i', 'hover', 'y', 'pointer');</script>\n";
			 }
		 }
		
	?>
    </div>
  <input type='hidden' value='<?=base_url();?>index.php/' id='path' />
    
</div>
<?
    link_js("check_do.js","asset/js");
?>