<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/master';
link_js('master_vendor.js',$path.'/js');
link_js('jquery.fixedheader.js','asset/js');
panel_begin('List Vendor');
panel_multi('addvendor','none',false);
if($all_master__vendor_n!=''){
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('produsen',true,'50%');
	$zfm->BuildFormButton('Simpan','vendor');
}else{
	no_auth();
}

panel_multi_end();
panel_multi('listvendor','block',false);
if($all_listvendor!=''){
addText(array('Nama Vendor :',''),
	   array('<input type="text" id="finde" class="cari w100">',
	   '<input type="button" id="oke" value="Cari">'));
		$zlb->section('produsen');
		$zlb->aksi(true);
		$zlb->icon('deleted');
		$zlb->Header('100%');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
popup_start('v_detail','Transaksi Vendor :<span id="nmp"></span>',750,550);
		$zlb->section('detailtransvendor');
		$zlb->aksi(false);
		$zlb->icon('deleted');
		$zlb->Header('100%','vend_detail');
		echo "</tbody></table>";
popup_end();

?>
