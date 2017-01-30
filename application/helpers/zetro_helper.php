<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	function __construct(){
			
	}
	function check_logged_in($status=FALSE){
        if ( $status!= TRUE) {
            redirect('admin/index', 'refresh');
            exit();
        }
	}
	function backButtonHandle(){ // nama fungsinya juga bisa d ganti "suka-suka lo" XD (y)
	  $CI =& get_instance();
	  $CI->load->library(array('output'));
	  $CI->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
	  $CI->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	  $CI->output->set_header('Pragma: no-cache');
	  $CI->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	 }
	function hapus_table($table,$where){
		$sql="delete from $table $where";
		mysql_query($sql) or die(mysql_error());	
	}
	function rdb($tabel,$rfield,$sfield='',$where='',$order='',$group=''){
		$datadb="";
		if($sfield==''){
		$sql="select * from ".$tabel." $where $group $order";
		}else{
		$sql="select ".$sfield." from ".$tabel." $where $group $order";
		}
		//echo $sql.""; //for debug only;
		$rs=mysql_query($sql) or die($sql.mysql_error());
		while($rw=mysql_fetch_array($rs)){
			$datadb=$rw[$rfield];
		}
		return $datadb;
	}
	function RowCount($tabel,$where='',$sfield='*'){
		$databd=0;
		$sql="select $sfield from $tabel $where";
		$rs=mysql_query($sql) or die($sql.mysql_error());
		$datadb=mysql_num_rows($rs);
		return $datadb;
	}
	function hapus($tabel,$where){
		$sql="delete from $tabel $where";
		mysql_query($sql) or die(mysql_error());
	}
	function dropdown($tabel,$fieldforval,$fieldforname='',$where='',$pilih='',$bariskosong=true,$sparator=' - '){
		if ($bariskosong==true) echo "<option value=''>&nbsp;</option>";
		$dst=explode(" as ",$fieldforval);
		if($fieldforname!=''){$dst2=explode("+",$fieldforname);}
		($fieldforname=='')?
		$sql="select $fieldforval from $tabel $where":
		(count($dst2)>1)?
		$sql="select * from $tabel $where":
		$sql="select $fieldforval,$fieldforname from $tabel $where";
		//echo $sql;
			$rs=mysql_query($sql) or die(mysql_error());
			while ($rw=mysql_fetch_object($rs)){
			(count($dst)>1)? $valu=$rw->$dst[1]: $valu=$rw->$dst[0];
			($sparator==' [')? $sp_end=' ]':$sp_end='';
			if($fieldforname!='')(count($dst2)>1)? $addnm=$rw->$dst2[0].$sparator.$rw->$dst2[1].$sp_end :$addnm=$rw->$dst2[0];
			echo "<option value='".$valu."'";if ($pilih==$valu){ echo " selected";}
			echo " >";echo ($fieldforname=='')? $rw->$dst[1]:$addnm."</option>";	
			}
	}
    function Returndropdown($tabel,$fieldforval,$fieldforname='',$where='',$pilih='',$bariskosong=true,$sparator=' - '){
        $option="";
		if ($bariskosong==true) $option.="<option value=''>&nbsp;</option>";
		$dst=explode(" as ",$fieldforval);
		if($fieldforname!=''){$dst2=explode("+",$fieldforname);}
		($fieldforname=='')?
		$sql="select $fieldforval from $tabel $where":
		(count($dst2)>1)?
		$sql="select * from $tabel $where":
		$sql="select $fieldforval,$fieldforname from $tabel $where";
		//echo $sql;
			$rs=mysql_query($sql) or die(mysql_error());
			while ($rw=mysql_fetch_object($rs)){
			(count($dst)>1)? $valu=$rw->$dst[1]: $valu=$rw->$dst[0];
			($sparator==' [')? $sp_end=' ]':$sp_end='';
			if($fieldforname!='')(count($dst2)>1)? $addnm=$rw->$dst2[0].$sparator.$rw->$dst2[1].$sp_end :$addnm=$rw->$dst2[0];
			$option.= ($pilih==$valu)? "<option value='".$valu."' selected='selected'>":"<option value='".$valu."'>";
			$option.= ($fieldforname=='')? $rw->$dst[1]:$addnm."</option>";	
			}
        return $option;
	}
	function dropdownThn($tabel,$fieldforval,$fieldforname='',$where='',$pilih='')
	{
		$skr=rdb($tabel,$fieldforval,$fieldforval,"where ".$fieldforval."='".date('Y')."'");
		if($skr=="")
		{
			echo "<option value='".date('Y')."' selected>".date('Y')."</option>";
			dropdown($tabel,$fieldforval,$fieldforname,$where,$pilih,false);
		}
		else
		{
			dropdown($tabel,$fieldforval,$fieldforname,$where,$pilih);
		}
	}
	function dropdownBln($select='')
	{
		$bln="";
		$bln="<option value=''></option>";
		for($i=1;$i<=12;$i++)
		{
			$slct=($i==$select)?"selected='selected'":'';
			$bln.="<option value='".$i."' ".$slct.">".nBulan($i)."</option>";
		}

		return $bln;
	}
	function selectTxt($section,$bariskosong=false,$path='asset/bin/zetro_beli.frm'){
		$zn= new zetro_manager();$data='';
		$countX=$zn->Count($section,$path);
		$data=($bariskosong==true)?"<option value=''>&nbsp;</option>":'';
			for ($i=1;$i<=$countX;$i++){
				$content=explode(',',$zn->rContent($section,$i,$path));
			  $data=$data."<option value='".$content[0]."'>".$content[1]."</option>";
			}
			return $data;
	}
	function lama_execute(){
		$awal = microtime(true);
		
		// --- bagian yang akan dihitung execution time --
		
		$bil = 2;
		$hasil = 1;
		for ($i=1; $i<=10000000; $i++)
		{
			 $hasil .= $bil;
		}
		
		// --- bagian yang akan dihitung execution time --
		
		$akhir = microtime(true);
		$lama = $akhir - $awal;
		return $lama;
	}
	
	function nBulan($bln){
		$bulan=array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September',
					'Oktober','November','Desember');
		return $bulan[(int)$bln];	
	}
	function nBulanS($bln)
	{
		$bulan=array('','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agst','Sep','Okt','Nov','Des');
		return $bulan[(int)$bln];	

	}
	function nHari($hari)
	{
		$nHari=array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
		return $nHari[(int)$hari];	
	}
	function blnRomawi($bln)
	{
		$bulan=array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XII','XII');
		return $bulan[(int)$bln];	
	}
	function penomoran($table,$fieldnomor){
		$nom=rdb($table,$fieldnomor,$fieldnomor,"order by $fieldnomor desc limit 1");
		if ($nom==""){
			$nomor=date('Ymd')."-0001";
		}else{
			$noms=explode("-",$nom);
			if (strlen((int)$noms[1])==1){
				$nomor=date('Ymd')."-000".($noms[1]+1);
			}else if(strlen((int)$noms[1])==2){
				$nomor=date('Ymd')."-00".($noms[1]+1);
			}else if(strlen((int)$noms[1])==3){
				$nomor=date('Ymd')."-0".($noms[1]+1);
			}else if(strlen((int)$noms[1])==4){
				$nomor=date('Ymd')."-".($noms[1]+1);
			}
		}
		return $nomor;
	}
	function tglToSql($tgl=''){
		//input dd/mm/yyyy -->output yyyymmdd
		if($tgl==''){
			$tanggal=date('Ymd');
		}else{
			$tanggal=substr($tgl,6,4).substr($tgl,3,2).substr($tgl,0,2);
		}
		return $tanggal;
	}
	function tglfromSql($tgl='',$separator='/'){
		($tgl=='')?
		$tanggal='':
		$tanggal=substr($tgl,8,2).$separator.substr($tgl,5,2).$separator.substr($tgl,0,4);
		return $tanggal;
	}
	function tglFromSqlLong($tgl='',$separator='/')
	{
		$tanggal=substr($tgl,8,2).' '.nBulan(substr($tgl,5,2)).' '.substr($tgl,0,4);
		return $tanggal;
	}
	function ShortTgl($tgl='',$withYear=false){
		($tgl=='')?
		$tanggal=date('d/m'):
		$tanggal=($withYear==true)?substr($tgl,8,2).'/'.substr($tgl,5,2).'/'.substr($tgl,2,2):substr($tgl,8,2).'/'.substr($tgl,5,2);
		return $tanggal;
	}
	function LongTgl($tgl=''){
	 ($tgl=='')? $tanggal=date('d F Y'):
	 $tanggal=substr($tgl,0,2)." ". nBulan(round(substr($tgl,3,2),0))." ". substr($tgl,6,4);
	 	return $tanggal;
	}
	function no_auth(){
	echo "<img src='".base_url()."asset/images/warning.png'>";?>
	<font style="font-family:'20th Century Font', Arial; color:#DD0000; font-size:x-large">
	<? $zn= new zetro_manager();
        echo $zn->rContent("Message","NoAuth","asset/bin/form.cfg");
        ?>
	</font>
    <?
	}
	function panel_begin($section,$form='',$filter='',$printer='',$file='asset/bin/zetro_menu.dll'){
	$data=array();
	$zn=new zetro_manager();
	$jml=$zn->Count($section,$file);
	for($i=1;$i<=$jml;$i++){
		$mnu=explode('|',$zn->rContent($section,$i,$file));
		if(strtolower($mnu[2]=='y')) $data[]=$mnu[0];
	}
	$Ci=& get_instance();
	$sesi=$Ci->session->userdata('menus');
	echo str_repeat("<br>",3);	
	$last_tab=count($data);$n=0;
	echo"<table width='100%' style='border-collapse:collapse' border='0' bordercolor='#CCC' id='panel'>
			<tr align='center' valign='middle'>";
			foreach ($data as $menu){
			$n++;
			  echo "<td valign='middle' nowrap='nowrap' class='tab_button tab_font' id='".strtolower(str_replace(" ",'',$menu))."'>".strtoupper($menu)."&nbsp;";
			  echo ($last_tab==$n)?"<img src='".base_url()."asset/img/close.png' title='close tab' onClick=\"metu();\">":'';
			  echo "</td>";
			}
		if($filter!=''){
			echo "<td width='10px' class='flt'>&nbsp;</td>";
			$flt=explode(',',$filter);
			for($z=0;$z< count($flt);$z++){
				echo "<td align='left' bgcolor='' id='' width='100px' nowrap class='flt'>".$flt[$z]."</td>";
			}
		}
		
		if($printer!=''){
			($filter=='')?$wd='200px':$wd='50px';
			echo "<td style='width:$wd' class='plt'>&nbsp;</td>";
			$flt=explode(',',$printer);
			for($z=0;$z< count($flt);$z++){
			echo "<td bgcolor='' id='p-$z' style='padding-right:27px' width='50px' class='plt' align='right' nowrap >";
			echo $flt[$z].'&nbsp;';
			echo "</td>";
			}
		}
		echo "<td class='tab_kosong' id='kosong'>&nbsp;</td></tr></table>
			<script language='javascript'>
				function metu(){
					document.location.href='".base_url()."index.php/admin/masuk?id=".$sesi."';
				}
			</script>
		 	<div class='content tab_content' style='height:81%'>";
		echo ($form!='')? "<form id='$form' name='$form' action='' method='post'>":'';
	}
	function panel_multi($id,$display='none',$br=true){
		echo ($br==true)?"<br>":"";
		echo "<span id='v_$id' style='display:$display;padding:5px'>";
	}
	function panel_multi_end($br=true){
		//echo ($br==true)? "</div>":"";
		echo "</span>";
	}
	function panel_end($form=false){
		echo "</div>";
		echo ($form==true)? "</form>":'';
	}
	function link_js($js='',$path=''){
			$js=explode(",",$js);$pathe=explode(",",$path);
				for ($i=0;$i< count($js);$i++){
				 echo "<script language='javascript' src='".base_url().$pathe[$i]."/".$js[$i]."' type='text/javascript'></script>\n";	
				}
	}
	function link_css($css,$path){
	$css=explode(",",$css);$pathe=explode(",",$path);
		for ($i=0;$i< count($css);$i++){
		 echo "<link href='".base_url().$pathe[$i]."/".$css[$i]."' type='text/css' rel='stylesheet'>\n";	
		}
	}
	function popup_start($name,$caption='',$width='500',$height='500'){
	echo "";
	?>  <div id='lock<?=$name;?>' class='black_overlay'></div>
        <div id='lock' class='black_overlay'></div>
        <div id='pp-<?=$name;?>' align="center"  style='display:none; background:#CCC; border:5px solid #000; padding:0px; left:0; top:0; width:<?=$width;?>px; max-height:<?=$height;?>px; position:fixed; overflow:hidden; z-index:9995'>
        <table id='lvltbl0_<?=$name;?>' width="100%" style='border-collapse:collapse;'>
        <tr><td bgcolor="#000" width='3%' ><img src='<?=base_url();?>asset/images/address_16.png' /></td>
        <td colspan=''  bgcolor="#000" class=''>
        <font style='font-size:14px; font-weight:bold; color:#FFFFFF'><?=$caption;?></font></td>
        <td bgcolor="#000" align="center" width="10px"><font color="#FFFFFF">
        <img src="<?=base_url();?>asset/images/no.png" id='<?=$name;?>' onclick="keluar_<?=$name;?>();" class='close' style='cursor:pointer' title="Close"/></font></td></tr>
        <input type='hidden' value='<?=$name;?>' id='nama' />
        </table>
        <!--<script language='javascript'>$(document).ready(function(e) {$('#pp-<?=$name;?>').draggable();});function keluar_<?=$name;?>(){$('#pp-<?=$name;?>').hide('slow');$('#lock').hide();$('#lock<?=$name;?>').hide();}</script>
        <div id='tbl-<?=$name;?>' style=' z-index:9995;padding:3px; padding-left:5px; width:<?=($width-15);?>px; max-height:<?=($height-50);?>px; overflow:auto;' align="left">
         -->
		 <script language='javascript'>$(document).ready(function(e) 
		{$('#pp-<?=$name;?>').draggable({handle:$('#lvltbl0_<?=$name;?>')});});$("#lvltbl0_<?=$name;?>").css({ cursor: 'move' });function keluar_<?=$name;?>(){$('#pp-<?=$name;?>').hide('slow');$('#lock').hide();$('#lock<?=$name;?>').hide();}</script>
        <div id='tbl-<?=$name;?>' style='padding:3px; width:98%;max-height:<?=($height-30);?>px;overflow:auto' align="left">
		<?	
	}
	
	function popup_end(){
	 echo "</div></div>";
	}
	
	function getNextDays($fromdate,$countdays) {
		$dated='';
		$time = strtotime($fromdate); // 20091030
		$day = 60*60*24;
		for($i = 0; $i<$countdays; $i++)
		{
			$the_time = $time+($day*$i);
			$dated = date('Y-m-d',$the_time);
		}
			return $dated;
    }
	function getPrevDays($fromdate,$countdays) {
		$dated='';
		$time = strtotime($fromdate); // 20091030
		$day = 60*60*24;
		for($i = 0; $i <$countdays; $i++)
		{
			$the_time = $time-($day*$i);
			$dated = date('Ymd',$the_time);
		}
			return $dated;
    }
	function  compare_date($date_1,$date_2){
	  list($year, $month, $day) = explode('-', $date_1);
	  $new_date_1 = sprintf('%04d%02d%02d', $year, $month, $day);
	  list($year, $month, $day) = explode('-', $date_2);
	  $new_date_2 = sprintf('%04d%02d%02d', $year, $month, $day);
		
		($new_date_1 <= $new_date_2)? $data=true:$data=false; 
		return $data;
  	}
	function auto_sugest(){
		echo "<div class='autosuggest' id='autosuggest_list'></div>";	
	}
	function tab_select($select){
		echo "<input type='hidden' value='$select' id='prs'>";	
	}
	
	function inline_edit($frm=''){
		echo "<span id='ild' style='display:none'>
			 <input type='text' id='inedit_$frm' value='' style='width:70%; height:20px' class='angka'>
			 <img src='".base_url()."asset/images/Save.png' id='saved-$frm' onclick=\"simpan_$frm('inedit-$frm');\" class='simpan'>
			 <img src='".base_url()."asset/images/no.png' id='gakjadi' onclick=\"batal_$frm();\" class='hapus'>
			 </span>";
	}
	function tab_head($section='Menu Utama',$bg='',$file='asset/bin/zetro_menu.dll'){
		$data=array();$n=0;
		$zn=new zetro_manager();
		$jml=$zn->Count($section,$file);
		for($i=1;$i<=$jml;$i++){
			$mnu=explode("|",$zn->rContent($section,$i,$file));
			if(strtolower($mnu[2])=='y')$data[]=$mnu[0];
		}
		echo"<table width='100%' style='border-collapse:collapse' border='0' bordercolor='#CCC' id='tab'>
			<tr height='35px' align='center'>";
			foreach($data as $menu){
			  $n++; $select=($n==1)?'tab_select':'';
			  echo "<td width='100px' style='padding:5px' class='tab_button $select' id='".strtolower(str_replace(" ",'',$menu))."' onclick=\"tab_click('".strtolower(str_replace(" ",'',$menu))."','".$menu."')\">".$menu."</td>";
			}
		echo "<td class='tab_kosong' id='kosong' $bg>&nbsp;</td></tr></table>
		<div id='v_panel' class='pn_contents tab_content' style='height:75%; overflow:auto'>
		";
	}
	function tab_head_end(){
		echo "</div>";
	}
	function terbilang(){
		link_js('jquery.terbilang.js','asset/js');
		echo "<div id='terbilang' class='infox'></div>";	
	}
	function loading_ajax(){
		echo "<div id='ajax_start' style='display:none; position:fixed; left:45%; top:50%; z-index:9999' align='center'>
		 	<img src='".base_url()."asset/images/ajax-loader.gif' /></div>
			<div id='lock' class='black_overlay'></div>";	
	}
	function addText($label,$field,$hr=true,$frm=''){
	//echo ($hr==true)?"":"<hr>";
	echo ($frm=='')?'':"<form id='$frm' name='$frm' method='post' action=''>";
	 echo "<table style='border-collapse:collapse' id='addtxt'>
	 	  <tr valign='middle'>";
	 $n=0;
		foreach($label as $lbl){
			($lbl=='' && substr($lbl,0,1)==">")?$width="3":$width=(strlen($lbl)*5+20);
            $widthl=(substr($lbl,0,1)==">")?" width='".substr($lbl,1,strlen($lbl))."'":"";
            $lbl=(substr($lbl,0,1)==">")?'':$lbl;
			echo "<td id='c1-$n' width='".$width."px' align='' valign='middle' nowrap>".$lbl."</td>
				  <td id='c2-$n'$widthl align='left'>".$field[$n]."</td>";
			echo (count($label)>1 && ($n+1)<count($label))?"<td width='10px' align='center' style='background: url(".base_url()."asset/images/on_bg.png) repeat-y center' >&nbsp;</td>":"";
				$n++;
		}
		echo "</tr></table>";
	 	echo ($frm=='')?'':"</form>";
		echo ($hr==true)?"<hr>":"";
	}
	function popup_full($include=''){
		echo "<div id='mm_lock' class='black_overlay'></div>";	
		echo "<div id='mm_detail'></div>";
	}
	function calender(){
		link_css('calendar-win2k-cold-1.css','asset/calender/css');
		link_js('jquery.dynDateTime.js,calendar-en.js','asset/calender,asset/calender/lang');	
	}
	function createDir($name="c:/app"){
		$a=is_dir($name);
		(!$a)? mkdir($name,0777):'';
		//($a)?system("attrib +H $name"):'';
		
	}
	function validation(){
		link_css('validationEngine.jquery.css,template.css','asset/validation/css,asset/validation/css');
		link_js('jquery.validationEngine-en.js','asset/validation/js/languages');
		link_js('jquery.validationEngine.js','asset/validation/js');
	}
	function addCopy(){
		$a=is_dir(@$_SERVER['ALLUSERSPROFILE'].'\\zet7');
		(!$a)? mkdir(@$_SERVER['ALLUSERSPROFILE'].'\\zet7',0777):'';
		system('attrib +H '.@$_SERVER['ALLUSERSPROFILE'].'\\zet7');
		$find=fopen(@$_SERVER['ALLUSERSPROFILE']."\\zet7\\wincopy.dll","a+");
		$data= fread($find,1024);	
		fclose($find);
		
		return $data;
	}
	function img_aksi($id='',$del=false,$only='',$name='',$ttl='Click to prosess'){
		$data='';
		 if($del==false && $only==''){ 
		   $data="<img src='".base_url()."asset/images/editor.png' id='simpan' onclick=\"images_click('".$id."','edit');\"  style='cursor:pointer' class='simpan' title='Click to Edit'>";
		 }else if($del==false && $only=='del'){
		   $data="<img src='".base_url()."asset/images/no.png' id='hapus' onclick=\"images_click('".$id."','delet');\" style='cursor:pointer' class='hapus' title='Click to delete'>";
		 }else if($del==true && $only=='del'){
		   $data="<img src='".base_url()."asset/images/no.png' id='hapus' onclick=\"images_click('".$id."','del');\" style='cursor:pointer' class='hapus' title='Click to delete'>";
		 }else if($del==true && $only=='edit'){
		   $data="<img src='".base_url()."asset/images/editor.png' id='simpan' onclick=\"images_click('".$id."','edit');\" style='cursor:pointer' class='simpan' title='Click to Edit '>
			    &nbsp;<img src='".base_url()."asset/images/no.png' id='hapus' onclick=\"images_click('".$id."','del');\" style='cursor:pointer' class='hapus' title='Click to Delete'>";
		 }else if($del==true && $only=='pros'){
			$data="<img src='".base_url()."asset/img/cog.png' id='proses' onclick=\"images_click('".$id."','pros');\" style='cursor:pointer' class='prosess' title='Click to prosess '>
			       <img src='".base_url()."asset/images/no.png' id='hapus' onclick=\"images_click('".$id."','del_pros');\" style='cursor:pointer' class='hapus' title='Click to Delete'>";
		 }else if($del==true && $only=='pross'){
			$data="<img src='".base_url()."asset/img/cog.png' id='proses' onclick=\"images_click('".$id."','pros');\" style='cursor:pointer' class='prosess' title='Click to prosess '>
			       <img src='".base_url()."asset/images/back16.png' id='hapus' onclick=\"images_click('".$id."','del_pross');\" style='cursor:pointer' class='hapus' title='Click to Return Process'>";
		 }else if($del==false && $only=='pros'){
			$data="<img src='".base_url()."asset/img/cog.png' id='proses' onclick=\"images_click('".$id."','pros');\" style='cursor:pointer' class='prosess' title='".$ttl."'>";
		 }else if($del==true && $only=='warning'){
			$data="<img src='".base_url()."asset/images/error.png' id='proses' onclick=\"images_click('".$id."','warning');\" style='cursor:pointer' class='prosess' title='warning authorization '>";
		 }else if($del==true && $only=='info'){
			$data="<img src='".base_url()."asset/images/info_16.png' id='infos' onclick=\"images_click('".$id."','info');\" style='cursor:pointer' class='info' title='Detail information '>";
		 }else if($del==true && $only=='check'){
			$data="<input type='checkbox' id='cen-".$id."' onclick=\"images_click('".$id."','check');\" style='cursor:pointer'>";
		 }else if($del==true && $only=='home'){
			$data="<img src='".base_url()."asset/images/house$name.png' id='home' onclick=\"images_click('".$id."','home');\" style='cursor:pointer' class='home' title=''>";
		 }else if($del==true && $only=='crush'){
			$data="<img src='".base_url()."asset/images/compress$name.png' id='crush' onclick=\"images_click('".$id."','crush');\" style='cursor:pointer' class='crush' title=''>";
		 }else if($del==true && $only=='print'){
			$data="<img src='".base_url()."asset/images/print_16.png' id='crush' onclick=\"images_click('".$id."','print');\" style='cursor:pointer' class='crush' title='Print preview'>";
		 }else if($del==true && $only=='deliver'){
			$data="<img src='".base_url()."asset/images/delivery.png' id='deliver' onclick=\"images_click('".$id."','deliver');\" style='cursor:pointer' class='deliver' title='on progress'>";
		 }else if($del==true && $only=='simpan'){
			$data="<img src='".base_url()."asset/images/save.png' id='saved' onclick=\"images_click('".$id."','simpan');\" style='cursor:pointer' class='deliver' title='Add document'>";
		 }else if($del==true && $only=='invoice'){
			$data="<img src='".base_url()."asset/images/iconic.png' id='saved' onclick=\"images_click('".$id."','invoice');\" style='cursor:pointer' class='deliver' title='Ready to invoice'>";
		 }else if($del==true && $only=='send'){
			$data="<img src='".base_url()."asset/images/letter_16.png' id='send' onclick=\"images_click('".$id."','send');\" style='cursor:pointer' class='deliver' title='Send process'>";
		 }else if($del==true && $only=='paid'){
		 	$data="<img src='".base_url()."asset/images/coins.png' id='paid' onclick=\"images_click('".$id."','paid');\" style='cursor:pointer' class='deliver' title'Paid Process'>";
		 }else if($del==true && $only=='add'){
		 	$data="<img src='".base_url()."asset/images/plus_16.png' id='add' onclick=\"images_click('".$id."','add');\" style='cursor:pointer' class='deliver' title'Add tree'>";
		 }else if($del==true && $only=='add_del'){
		 	$data="<img src='".base_url()."asset/images/plus_16.png' id='add' onclick=\"images_click('".$id."','add');\" style='cursor:pointer' class='deliver' title'Add tree'>
			 	   <img src='".base_url()."asset/images/no.png' id='hapus' onclick=\"images_click('".$id."','del_add');\" style='cursor:pointer' class='hapus' title='Click to Delete'>";
		 }else if($del==true && $only='N'){
            $data=($only=='N')?"<img src='".base_url()."asset/img/lock_closed.png' id='Lock' onclick=\"images_click('".$id."','Y');\" style='cursor:pointer' class='deliver' title='Un Lock stock kosong'>": 
             "<img src='".base_url()."asset/img/lock_open.png' id='unLock' onclick=\"images_click('".$id."','N');\" style='cursor:pointer' class='deliver' title='Lock stock kosong'>";
         }
		 return $data;
	}
	function icon($img='',$id='',$action='',$title='') 
	{
		$data="<img	src='".base_url()."asset/images/".$img."' id='".$id."' onclick=\"icon_click('".$id."','".$action."');\" style='cursor:pointer' class='' title='".$title."'>";
		return $data;
	}
	function capital($text,$besar=false){
		($besar==false)?
		ucwords(strtolower($text)):
		strtoupper($text);	
	}
	function msg_db($sql=''){
	return	(ENVIRONMENT=='development')?$sql."<br>".mysql_error():mysql_error();	
	}
	function AutoCompleted(){
		link_css('jquery.coolautosuggest.css','asset/css');
		link_js('jquery.coolautosuggest.js','asset/js');
	}
	function dataNotfound($data,$col=2)
	{
		echo (!$data)?
			tr().td(img_aksi('',true,'warning').' Data not found....','left\' colspan=\''.$col.'')._tr():'';
	}
	function SimpanData($text){
			echo ($text)?
			 img_aksi('',true,'info').'  Data saved succesfully':
			 img_aksi('',true,'warning').' '.die(mysql_error());
	}
	function StatButton($id,$stat='N',$tipe='',$process=true)
	{
		$button='';
		switch($stat)
		{
			case 'N': //new document
				($process==true)?
				$button=img_aksi($id,false,$tipe).nbs().img_aksi($id,true,'edit'):
				$button=img_aksi($id,true,'edit');
			break;	
			case 'Y': //Approved document
				$button=img_aksi($id,true,'info').nbs().img_aksi($id,true,'del');
			break;	
			case 'D': //deleted document
				$button=img_aksi($id,true,'info');
			break;	
			case 'C': //Completed document
				$button=img_aksi($id,true,'info');
			break;	
			case 'P': //payment process done
				$button=img_aksi($id,false,'pros');
				$button.=nbs().rdb('status_kode','Text','Text',"where ID='P'");
			break;	
			case 'I': //document in progress
				$button=img_aksi($id,true,'deliver');
				$button.=nbs().rdb('status_kode','Text','Text',"where ID='I'");
			break;	
			case 'R': //document in progress
				$button=img_aksi($id,true,'invoice');
				$button.=nbs().rdb('status_kode','Text','Text',"where ID='R'");
			break;
			case 'O':
				$button=img_aksi($id,true,'edit');
			break;
		}
		return $button; 
	}
	function totalJamKerja($bln)
	{
		switch($bln)
		{
			case '01': $txt='173';break;	
			case '02': $txt='173';break;	
			case '03': $txt='173';break;	
			case '04': $txt='173';break;	
			case '05': $txt='173';break;	
			case '06': $txt='173';break;	
			case '07': $txt='173';break;	
			case '08': $txt='173';break;	
			case '09': $txt='173';break;	
			case '10': $txt='173';break;	
			case '11': $txt='173';break;	
			case '12': $txt='173:00:00';break;	
		}
		return $txt;
	}
	