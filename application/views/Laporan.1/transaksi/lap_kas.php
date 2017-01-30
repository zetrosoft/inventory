<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/laporan/transaksi';
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('lap_kas.js',$path.'/js');
panel_begin('Laporan Kas');
panel_multi('laporankas','block',false);
if($all_laporankas!=''){
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('filterlapkas',true,'60%');
	$zfm->BuildFormButton('Process','filter','button',2);

}else{
	no_auth();
}
panel_multi_end();
panel_end()
?>
