<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_neraca.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_neraca.frm');
$path='application/views/laporan/transaksi';
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('auto_sugest.js,lap_beli.js,jquery.fixedheader.js','asset/js,'.$path.'/js,asset/js');
panel_begin('Rekap Pembelian');
panel_multi('daftarpembelian','block',false);
if($all_daftarpembelian!=''){
	echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Periode  :'.nbs(1),' s/d '),array("<input type='text' id='dari_tgl' name='dari_tgl' value=''>","<input type='text' id='sampai_tgl' name='sampai_tgl' value=''>"));
	addText(array('Jenis Pembelian ',''),array("<select id='jenis_beli' name='jenis_beli'></select>",
		  "<!--input type='button' value='OK' id='okelah'-->"));
	addText(array('Order by :'.nbs(1),'Sort by','',''),
			array("<select id='orderby' name='orderby'>".selectTxt('susunanbeli',false,'asset/bin/zetro_member.frm')."</select>",
				  "<select id='urutan' name='urutan'>".selectTxt('Urutan',true)."</select>",
				  "<input type='checkbox' id='show_de' name='show_de' checked='checked' value='detail' style='display:none'>",
				  "<input type='button' value='OK' id='okelah'/>",''));
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
<input type='hidden' value="<?=$this->session->userdata('menus');?>" id='aktif'/>
<script language="javascript">
$(document).ready(function(e) {
	$('#jenis_beli').html("<option value=''>Semua</option><? dropdown('inv_pembelian_jenis','ID','Jenis_Beli','','');?>");
/*	if($('#aktif').val()=='SW52ZW50b3J5=='){
		$('table#addtxt tr td#c1-2').show();
		$('table#addtxt tr td#c2-2').show();
	}else if($("#aktif").val()=='TGFwb3Jhbg=='){
		$('table#addtxt tr td#c1-2').hide();
		$('table#addtxt tr td#c2-2').hide();
	}
*/});
</script>