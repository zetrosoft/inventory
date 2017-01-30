<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/master';
link_js('master_vendor.js',$path.'/js');
link_js('jquery.fixedheader.js','asset/js');
panel_begin('Vendor');
panel_multi('addvendor','block',false);
if($all_master__vendor_n!=''){
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('produsen',true,'50%');
	$zfm->BuildFormButton('Simpan','vendor');
}else{
	no_auth();
}

panel_multi_end();
panel_multi('listvendor','none',false);
if($all_listvendor!=''){
addText(array('Nama Vendor :',''),
	   array('<input type="text" id="finde" class="cari w100">',
	   '<input type="button" id="oke" value="Cari">'));
		$zlb->section('produsen');
		$zlb->aksi(($e_listvendor!='')?true:false);
		$zlb->icon('deleted');
		$zlb->Header('100%');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
auto_sugest();

?>
<script language="javascript">
	$(document).ready(function(e) {
        $('#addvendor').removeClass('tab_button');
		$('#addvendor').addClass('tab_select');
    });


</script>