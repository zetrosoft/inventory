<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_akun.frm');
$zlb=new zetro_buildlist();
$section='Barang';
$zlb->config_file('asset/bin/zetro_akun.frm');
$path='application/views/akuntansi';
link_js('jquery.fixedheader.js,akun.js,jquery_terbilang.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Perkiraan');
panel_multi('addperkiraan');
if($all_addperkiraan!=''){
	$zfm->AddBarisKosong(true,'b');
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('perkiraan',true,'50%');
	$zfm->BuildFormButton('Simpan','perkiraan');
}else{
	no_auth();
}
panel_multi_end();
panel_multi('perkiraan','block');
if($all_perkiraan!=''){
$klas='';
addText(array('Klasifikasi','Sub Klasifikasi','Unit'),
		array("<select id='klas' class='S00'></select>",
			  "<select id='subklas'></select>",
			  "<select id='unit_jurnal'></select>"));
		$zlb->section('perkiraan');
		$zlb->aksi(($e_perkiraan!='')?true:false);
		$zlb->Header('100%');
		$zlb->icon();
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
popup_start('akun','Edit Perkiraan','700');
if($e_perkiraan!=''){
	$zfm->AddBarisKosong(true,'b');
	$zfm->Start_form(true,'frm2');
	$zfm->BuildForm('perkiraan',true,'100%');
	$zfm->BuildFormButton('Update','edit_akun');
}else{
	no_auth();
}
popup_end();
loading_ajax();

?>
