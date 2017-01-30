<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/penjualan';
calender();
link_css('jquery.alerts.css','asset/css');
link_js('jquery.alerts.js','asset/js');
link_js('jquery.fixedheader.js','asset/js');
link_js('material_jual_list.js',$path.'/js');
panel_begin('List Transaksi Penjualan');
panel_multi('listtransaksipenjualan','block',false);
if($all_listtransaksipenjualan!=''){
	//echo "<form name='frm1' id='frm1' method='post' action=''>";
	addText(array('Periode','s/d','','','','Search',''),
			array("<input type='text' id='frm_tgl' name='frm_tgl' value=''/>",
				  "<input type='text' id='to_tgl' name='to_tgl' value=''/>",
				  "<select id='st_trans' name='st_trans'></select>",
				  "<input type='button' value='OK' id='ok'/>",'',
				  "<input type='text' id='cari' name='cari' class='cari' style='width:250px' placeholder='Find by nama anggota' value=''/>",
				  "<input type='button' value='GO' id='go'/>"),true,'frm1');
	//echo "</frm>";
		$zlb->section('listjual');
		$zlb->aksi(true);
		$zlb->icon();
		$zlb->Header('100%');
	echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
popup_start('edittrans',"Edit Transaksi",600,500);
		$zfm->Addinput("<input type='hidden' id='id_j' name='id_j' value=''/><input type='hidden' id='id_br' name='id_br' value=''/>");
 		$zfm->AddBarisKosong(false);
		$zfm->Start_form(true,'frm5');
		$zfm->BuildForm('transdetail',true,'100%');
		$zfm->BuildFormButton('Update','rubah');
popup_end();
?>
<script language='javascript'>
$(document).ready(function(e) {
	$('#st_trans').html("<?=dropdown("inv_penjualan_jenis","ID","Jenis_Jual","order by ID","1",true);?>");	
	$.post('get_barange',{'id':''},function(result){
		$('#nm_barang').typeahead({source:$.parseJSON(result)});
	})
});
</script>