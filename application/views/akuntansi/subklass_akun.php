<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_akun.frm');
$zlb=new zetro_buildlist();
$section='Barang';
$zlb->config_file('asset/bin/zetro_akun.frm');
$path='application/views/akuntansi';
link_js('jquery.fixedheader.js,subklass_akun.js','asset/js,'.$path.'/js');
panel_begin('Sub Klasifikasi');
panel_multi('subklasifikasi','block');
if($all_subklasifikasi!=''){
$fld="<input type='hidden' id='ID_A' name='ID_A' value=''>";
	$zfm->Addinput($fld);
	$zfm->AddBarisKosong(false);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('subklasifikasi',true,'50%');
	$zfm->BuildFormButton('Simpan','subklasifikasi');
	echo "<hr>";
		$zlb->section('subklasifikasi');
		$zlb->aksi(true);
		$zlb->Header('100%');
		$zlb->icon();
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();


?>
