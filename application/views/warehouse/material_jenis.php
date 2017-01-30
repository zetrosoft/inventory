<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$section='Barang';
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/inventory';
link_js('material_inv.js',$path.'/js');
link_js('jquery.fixedheader.js','asset/js');
tab_select('');
panel_begin('Jenis Barang');
panel_multi('jenisbarang','block',false);
if($all_jenisbarang!=''){
	$attr=($e_jenisbarang!='')?'':'disabled';
	$zfm->AddBarisKosong(false);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('Jenis',true,'50%');
	$zfm->AttrButton($attr);
	$zfm->BuildFormButton('Simpan','jenis');
	echo "<hr/>";
		$zlb->section('Jenis');
		$zlb->aksi(true);
		$zlb->icon('deleted');
		$zlb->Header('100%','Jenis');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
?>
<script language="javascript">
$(document).ready(function(e) {
    	$('#jenisbarang').removeClass('tab_button');
		$('#jenisbarang').addClass('tab_select');

});

</script>