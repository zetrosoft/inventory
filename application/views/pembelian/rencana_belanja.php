<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_beli.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_beli.frm');
$path='application/views/pembelian';
Calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('jquery.fixedheader.js','asset/js');
link_js('rencana_belanja.js',$path.'/js');
link_js('jquery_terbilang.js','asset/js');
panel_begin('Rencana Belanja');
panel_multi('rencanabelanja','block',false);
if($all_rencanabelanja!=''){
   echo "<table id='frame' width='99%'>
		 <tr valign='top'><td width='45%'>";
            $zfm->AddBarisKosong(false);
            $zfm->Start_form(true,'frm1');
            $zfm->BuildForm('belanja',false,'60%');
    echo str_repeat("&nbsp;",140)."<input type='button' id='prnslip' value='Print Slip'/>";
    echo"</td><td class='kotak' colspan='2' width='55%'>
	<div id='kasir' class='harga' align='right'></div>
	<div id='trblkasir' style='font-size:medium; font-weight:bold'>Rp. </div></td></tr></table>";
    //echo "<table id='frame' width='99%'>";
    $txt=tr().
		 td("<input type='hidden' id='1__id_barang' name='1__id_barang' value=''>",'center').
		 td("<input type='text' id='1__nm_barang' class='w100 upper xx n_1' name='1__nm_barang' data-provide='typeahead' data-source=\"[".$datas."]\" value=''/>").
		 td("<select id='1__nm_satuan' class='S100 ' name='1__nm_satuan'></select>").
		 td("<input type='text' id='1__jml_transaksi' class='w100 angka' name='1__jml_transaksi' value=''/>").
		 td("<input type='text' id='1__harga_jual' class='w100 angka' name='1__harga_jual' value=''/>").
		 td("<input type='text' id='1__harga_total' class='w100 angka subtt' name='1__harga_total' value=''/>").
		 td("<input type='hidden' id='1__expired' class='w100 angka' name='1__expired' value=''/>".
            "<img src='".base_url()."asset/images/save.png' title='Hapus transaksi' onclick=\"images_click('1','simpan');\">","center").
		_tr();
	echo "<hr><form id='frm2' name='frm2' method='post' action=''>";
		$zlb->section('pembelianlistNew');
		$zlb->aksi(true);
		$zlb->Header('97%','ListTable',$txt);
		echo "<tr><td class='kotak' colspan='8'>&nbsp;</td>";
		echo "</tbody><tfoot>";
		/*$zfm->section('pembelianlist');
		$zfm->rowCount();
		$zfm->button('simpan');
		$zfm->BuildGrid();*/
    
		echo "</tfoot></table></form>";
}else{
    no_auth();
}
panel_multi_end();
panel_end();

?>