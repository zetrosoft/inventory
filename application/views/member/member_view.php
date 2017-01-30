<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_member.frm');
$zlb=new zetro_buildlist();
$section='Barang';
$zlb->config_file('asset/bin/zetro_member.frm');
$path='application/views/member';
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js,member.js','asset/js,'.$path.'/js');
link_js('ajaxupload.js','asset/js');
link_js('jquery_terbilang.js','asset/js');
calender();
!empty($panel)?$panel=$panel:$panel='';
tab_select($panel);
panel_begin('Pelanggan Baru');
panel_multi('pelangganbaru','block');
if($all_pelangganbaru!=''){
	//$zfm->AddBarisKosong(true);
	//$zfm->Start_form(true,'frm1');
	//$zfm->BuildForm('registrasi',true,'50%');
	//$zfm->BuildFormButton('Simpan','registrasi');
    echo "<table width='100%' border='0'>
	<tr valign='top'><td width='45%'>";
		$zfm->AddBarisKosong(false);
		$zfm->Start_form(true,'frm1');
		$zfm->BuildForm('registrasi',false,'100%');
echo "</td><td width='5%'>&nbsp;</td>
	 <td width='50%'>";
		$zfm->AddBarisKosong(false);
		$zfm->Start_form(true,'frm2');
		$zfm->BuildForm('biodata',false,'100%');
echo"<!--hr><div id='pic' style='height:350px; border:1px inset #FFF'>
	<img src='' id='photone' width='500px' height='350px'></div>";
echo "</td></tr-->
	 </table>
		<hr>
		<div id='btn' style='width:90%; border:0px outset #CCC;padding:5px; padding-right:20px' align='right'>
		<input type='button' id='saved-registrasi' value='Simpan'>
		<!--input type='button' id='cetak_b' value='Cetak'-->
		<input type='reset' id='batal' value='Cancel'></div>";
}else{
	no_auth();
}
panel_multi_end();
panel_multi('uploadphoto');
if($all_uploadphoto!=''){
!empty($upload_data['file_name'])?$img=$upload_data['file_name']:$img='';
!empty($d_photo)?$dv=$d_photo:$dv='none';
!empty($nourut)?$nourut=$nourut:$nourut='';
!empty($nama)?$nama=$nama:$nama='';
!empty($nip)?$nip=$nip:$nip='';
$fld="<input type='hidden' id='gambar' value='$img'>";
$fld.="<input type='hidden' id='nourut' name='nourut' value='$nourut'>";
$fld.="<input type='hidden' id='namane' value='$nama'>";
$fld.="<input type='hidden' id='nipe'  value='$nip'>";
	echo "<table width='100%'>
		<tr valign='top'><td width='45%'>";
	echo form_open_multipart('member/do_upload',"id='frm2' name='frm2'");
	$zfm->Addinput($fld);
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(false,'frm2');
	$zfm->BuildForm('upload',true,'100%');
	$zfm->BuildFormButton('Upload','upload','submit');
	echo "</form></td><td width='55%'>";
	?><div align='center' id='photo' style='width:98%; height:100%;border:0px solid #F90; display:<?=$dv;?>'>
    	<? echo (empty($error))? "<img id='thumb' src='".base_url()."uploads/member/$img' style='max-width:550px; max-height:400px'/>":$error;
		?>
        <p><input type="button" id='s_photo' value='Simpan' />
        <input type="button" id='c_photo' value='Cancel' /></p><p></p>
        </div>
		<?
        
	echo "</td></tr></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
auto_sugest();
terbilang();
?>
<script language="javascript">
    $(document).ready(function(e){
        $('#frm2 #pangkalan').html("<? dropdown('pangkalan','nama','nama',"where stat='Y'");?>");              
    });
</script>