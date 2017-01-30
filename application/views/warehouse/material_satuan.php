<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/inventory';
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js,jquery_terbilang.js','asset/js,asset/js');
link_js('jquery.fixedheader.js,material_list.js','asset/js,'.$path.'/js');
panel_begin('Satuan Barang');
panel_multi('satuanbarang','block',false);
if($all_satuanbarang!=''){
	$attr=($c_satuanbarang!='')?'':'disabled';
	$zfm->AddBarisKosong();
	$zfm->Start_form(true,'frm3');
	$zfm->BuildForm('Satuan',true,'70%');
	$zfm->AttrButton($attr);
	$zfm->BuildFormButton('Simpan','sat','button');
	echo "<hr>";
	$sql2="select * from inv_barang_satuan order by Satuan";
		$zlb->section('Satuan');
		$zlb->aksi(($e_satuanbarang!='')?true:false);
		$zlb->icon('deleted');
		$zlb->Header('100%');
		$zlb->query($sql2);
		$zlb->list_data('Satuan');
		$zlb->BuildListData('Satuan');
		echo "</tbody></table>";
inline_edit('satuan');
}else{
	no_auth();
}
panel_multi_end();
panel_end();
?>
<script language="javascript">
$(document).ready(function(e) {
    	$('#satuanbarang').removeClass('tab_button');
		$('#satuanbarang').addClass('tab_select');
		$('table#ListTable').fixedHeader({width:(screen.width-400),height:(screen.height-357)})});

</script>