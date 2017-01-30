<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/akuntansi/kas';
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js,jquery_terbilang.js','asset/js,asset/js');
link_js('jquery.fixedheader.js,operasional.js','asset/js,'.$path.'/js');
panel_begin('Operasional Harian');
panel_multi('operasionalharian','block',false);
if($all_operasionalharian!=''){
	echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Lokasi'.nbs(2)),array("<select id='id_lok' name='id_lok'></select>"));
	addText(array('Periode Tanggal : ',' s/d ','',''),
			array("<input type='text' id='dari_tgl' name='dari_tgl' value=''/>",
				  "<input type='text' id='sampai_tgl' name='sampai_tgl' value=''/>",
				  "<input type='button' id='okedech' value='Process'/>",
				  "<input type='checkbox' id='pajak' name='pajak' value='ok' style='display:none'>"),true,'frm1');
	echo "</form>";
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
	$('#id_lok').html("<option value='' selected>Semua</option><? dropdown('user_lokasi','ID','Lokasi',"where ID in(".$lokasi.") order by ID",'');?>");

});
</script>