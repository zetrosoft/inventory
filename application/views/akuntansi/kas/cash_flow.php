<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/akuntansi/kas';
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js,jquery_terbilang.js','asset/js,asset/js');
link_js('jquery.fixedheader.js,cash_flow.js','asset/js,'.$path.'/js');
panel_begin('Cash Flow');
panel_multi('alirankas','block',false);
if($all_alirankas!=''){
	echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Lokasi  '.nbs(2)),array("<select id='id_lokasi' name='id_lokasi'></select>"));
	addText(array('Periode Dari  '.nbs(1),' s/d '),
			array("<input type='text' id='dari_tgl' name='dari_tgl' value=''/>",
				  "<input type='text' id='sampai_tgl' name='sampai_tgl' value=''/>"));
	addText(array('  '.nbs(3)),array("<input type='button' id='okelah' value='Process'/>"),true,'frm1');
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
    });

</script>