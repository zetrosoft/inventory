<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_beli.frm');
$zlb=new zetro_buildlist();
$zn=new zetro_manager();
$section='rptbeli';
$zlb->config_file('asset/bin/zetro_beli.frm');
$path='application/views/member';
link_css('style.css','asset/js');
link_js('jquery.fixedheader.js,member_titipbarang.js','asset/js,'.$path.'/js');
panel_begin('Titip Barang');
panel_multi('titipbarang','block',false);

if($all_titipbarang!=''){
    echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Order by ','Urutan','','','Cari by Nama Pelangan',''),
			array("<select id='orderby' name='orderby'>
				   <option value='Deskripsi'>Nama Pelanggan</option>
				   <option value='Tanggal' selected>Tanggal</option></select>",
				  "<select id='urutan' name='urutan'>".selectTxt('Urutan',true)."</select>",
				  "<input type='button' value='OK' id='okelah'/>",
				  "<input type='hidden' value='Print' id='prt' visible='false'/>",
                 "<input type='text' class='cari w100' id='cariya' name='cariya' data-provide='typeahead' data-source='[".$nasabah."]' value=''>",
				  "<input type='button' value='Cari' id='carilah'/>"));
	//addText(array('Cari by Nama Pelangan',''),
	//		array("<input type='text' class='cari w100' id='cariya' data-provide='typeahead' value=''>",
	//			  "<input type='button' value='Cari' id='carilah'/>"),true,'',"width='400px'");
	echo "</form>";
		$zlb->section('rptbeli2');
		$zlb->aksi(true);
		$zlb->icon();
		$zlb->Header('100%');
	echo "</tbody></table>";
}else{
    no_auth();
}

panel_multi_end();
panel_end();
popup_start('DO','Delivery',850); 
    $zfm->frm_filename('asset/bin/zetro_beli.frm');
		$zfm->AddBarisKosong(true);
		$zfm->Start_form(true,'frm11');
		$zfm->BuildForm('FormDO',false,'60%');
		echo "<hr><form id='frm21' method='post' action=''>";
        $zlb->config_file('asset/bin/zetro_beli.frm');
		$zlb->section('ListDO');
		$zlb->aksi(false);
		$zlb->Header('100%','listdo');
echo "</tbody></table>";
echo "<hr>";
echo "<input type='hidden' id='simpanDO' name='simpanDO' value='Simpan' disabled='false'/>
      <input type='button' id='printDO' name='printDO' value='Cetak DO' disabled='true'><p></p></form>";
popup_end();
popup_start('DOM','Delivery Order',850); 
    $zfm->frm_filename('asset/bin/zetro_beli.frm');
		$zfm->AddBarisKosong(true);
		$zfm->Start_form(true,'frm115');
		$zfm->BuildForm('FormDOMobil',false,'60%');
		echo "<hr><form id='frm215' method='post' action=''>";
        $zlb->config_file('asset/bin/zetro_beli.frm');
		$zlb->section('ListDOMobil');
		$zlb->aksi(false);
		$zlb->Header('100%','listdoMobil');
echo "</tbody></table>";
echo "<hr>";
echo "<input type='button' id='addDO' name='addDO' value='Add DO' disabled='false' onclick='keluar_DOM()'/>
      <input type='button' id='printDOM' name='printDOM' value='Cetak DO' disabled='true'><p></p></form>";
popup_end();
?>
<script language='javascript'>
$(document).ready(function(e){
   $('#frm1 #urutan').val('desc').select();
   $('#frm1 #orderby').val('nm_pelanggan').select();
});
</script>