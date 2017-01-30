<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/laporan';
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('lap_faktur.js,jquery_terbilang.js',$path.'/js,asset/js');
panel_begin('Print Faktur');
panel_multi('printfakturpenjualan','block');
if($all_laporan__faktur!=''){
$fld3="<input type='hidden' id='section' name='section' value='faktur'>";
$fld3.="<input type='hidden' id='lap' name='lap' value='faktur'>";
$fld3.="<input type='hidden' id='optional' name='optional' value=''>";
	$zfm->Addinput($fld3);
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('formfaktur',true,'60%');
	$zfm->BuildFormButton('Process','faktur','button',2);
}else{
	no_auth();
}
panel_multi_end();

panel_end();
auto_sugest();
?>
<input type='hidden' id='tgl_trans' value='' />