<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_neraca.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_neraca.frm');
$path='application/views/laporan/transaksi';
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('auto_sugest.js,lap_jual.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Rekap Barang Kredit');
panel_multi('rekapbarangkredit','block',false);
if($all_rekapbarangkredit!=''){
	echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Periode :',' s/d ','Jenis',''),
	array("<input type='text' id='dari_tgl' name='dari_tgl' value=''>",
		  "<input type='text' id='sampai_tgl' name='sampai_tgl' value=''>",
		  "<select id='id_jenis' name='id_jenis'></select>",
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
    $('#rekapbarangkredit').removeClass('tab_button');
	$('#rekapbarangkredit').addClass('tab_select');
	$('#kategori').html("<option value='' selected>Semua</option><? dropdown('inv_barang_kategori','ID','Kategori','','');?>");
	$('#id_jenis').html("<option value='' selected>Semua</option><? dropdown('inv_barang_jenis','ID','JenisBarang','','');?>");
});
</script>