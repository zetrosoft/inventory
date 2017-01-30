<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_neraca.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_neraca.frm');
$path='application/views/laporan/transaksi';
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('auto_sugest.js,lap_kreditur.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Rekap Penjualan Kredit');
panel_multi('rekappenjualankredit','block',false);
if($all_rekappenjualankredit!=''){
	echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Periode :',' s/d ','Departemen','Cicilan',''),
	array("<input type='text' id='dari_tgl' name='dari_tgl' value=''>",
		  "<input type='text' id='sampai_tgl' name='sampai_tgl' value=''>",
		  "<select id='departemen' name='departemen'></select>",
		  "<select id='cicilan' name='cicilan'></select>",
		  "<input type='button' value='OK' id='okelah'/>
		  <input type='hidden' value='2' id='jenis_beli' name='jenis_beli'/>"));
	echo "</form>";
	echo "</tbody></table>";
	echo "<table id='xx' width='100%'><tbody><tr><td>&nbsp;</td></tr></tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
auto_sugest();
panel_end();
?>

<script language="javascript">
$(document).ready(function(e) {
	$('#departemen').html("<option value='' selected>Semua</option><? dropdown('mst_departemen','ID','Title','order by Title','');?>");
	$('#cicilan').html("<option value='' selected>Semua</option><? dropdown('mst_bulan','ID','ID','order by ID','');?>");
});
</script>