<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/akuntansi/kas';
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js,jquery_terbilang.js','asset/js,asset/js');
link_js('jquery.fixedheader.js,kas_masuk.js','asset/js,'.$path.'/js');
panel_begin('Penerimaan Harian');
panel_multi('laporankasmasuk','block',false);
if($all_laporankasmasuk!=''){
	echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Lokasi  '.nbs(2)),array("<select id='id_lokasi' name='id_lokasi'></select>"));
	addText(array('Periode Dari  '.nbs(1),' s/d '),
			array("<input type='text' id='dari_tgl' name='dari_tgl' value='".date('01/m/Y')."'/>",
				  "<input type='text' id='sampai_tgl' name='sampai_tgl' value='".date('d/m/Y')."'/>"));
	addText(array('Jenis Pembelian     ','',''),array(
				  "<select id='id_jenis' name='id_jenis'></select>",
				  "<input type='button' id='okedech' value='OK'/>",
				  "<input type='checkbox' id='pajak' name='pajak' value='ok' style='display:none'>"),true,'frm1');
	echo "</form>";
	echo "</tbody></table>";
	echo "<table id='xx' width='100%'><tbody><tr><td>&nbsp;</td></tr></tbody></table>";
}else{
	no_auth();	
}
panel_multi_end();
panel_end();
terbilang();
$lokasi=$this->zetro_auth->cek_area();
?>

<script language="javascript">
	$(document).ready(function(e) {
 	$('#id_lokasi').html("<option value='' selected>Semua</option><? dropdown('user_lokasi','ID','Lokasi',"where ID in(".$lokasi.") order by ID",'');?>");
       $('#id_jenis').html("<option value=''>Semua</option><? dropdown('inv_penjualan_jenis','ID','Jenis_Jual','order by id');?>");
    });

</script>