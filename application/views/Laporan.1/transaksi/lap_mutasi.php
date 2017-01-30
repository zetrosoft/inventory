<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$path='application/views/laporan/transaksi';
$modul=$this->session->userdata('menus');
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('lap_mutasi.js,jquery.fixedheader.js',$path.'/js,asset/js');
panel_begin('Laporan Mutasi');
panel_multi('listmutasistock','block',false);
if($all_listmutasistock!=''){
	echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Lokasi  '.nbs(2)),array("<select id='id_lok' name='id_lok'></select>"));
	addText(array('Periode Dari  '.nbs(1),' s/d '),
			array("<input type='text' id='dari_tgl' name='dari_tgl' value=''>",
		  		  "<input type='text' id='sampai_tgl' name='sampai_tgl' value=''>
		 			<!--input type='button' value='OK' id='okelah'/-->
		  			<input type='hidden' value='1' id='jenis_beli' name='jenis_beli'/>"));
	addText(array('Order by'.nbs(2)),array("<select id='orderby' name='orderby'>".selectTxt('lapmutasiurutan',false,'asset/bin/zetro_inv.frm')."</select>"));
	addText(array('Sort by '.nbs(2)),array("<select id='urutan' name='urutan'>".selectTxt('Urutan',false)."</select>"));
	addText(array(($modul=='S2FzaXI=')?'Show Detail Transaksi':nbs(3),''),array(($modul=='S2FzaXI=')? "<input type='checkbox' id='show_de' name='show_de' value='detail'>":"",
					"<input type='button' value='Proses' id='okelah'/>"));
/*	addText(array('Cari by Nama Pelangan :',''),
			array("<input type='text' class='cari w100' id='cariya' value=''>",
				  "<input type='button' value='Cari' id='carilah'/>"));	
*/	echo "</form>";
	echo "</tbody></table>";
	echo "<table id='xx' width='100%'><tbody><tr><td>&nbsp;</td></tr></tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
auto_sugest();
panel_end();
$lokasi=$this->zetro_auth->cek_area();
?>
<script language="javascript">
$(document).ready(function(e) {
	$('#id_lok').html("<option value=''>Semua Lokasi</option><? dropdown('user_lokasi','ID','Lokasi',"where ID in(".$lokasi.") order by ID",'');?>");
	$('#id_lok').val($('#lok').val()).select()
	if($('#jml_area').val()=='1'){
		lock('#id_lok');
	}else{
		unlock('#id_lok')
	}
});
</script>