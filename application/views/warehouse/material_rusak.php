<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$file='asset/bin/zetro_neraca.frm';
$zfm=new zetro_frmBuilder($file);
$zlb=new zetro_buildlist();
$zlb->config_file($file);
$path='application/views/warehouse';
calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('jquery.fixedheader.js','asset/js');
link_js('material_mutasi.js',$path.'/js');
panel_begin('Rusak/Hilang');
panel_multi('addbarangrusak','none',false);
if($all_addbarangrusak!=''){
	$fld="<input type='hidden' id='id_barang' name='id_barang' value=''/>";
	$fld.="<input type='hidden' id='id_satuan' name='id_satuan' value=''/>";
	$zfm->Addinput($fld);
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('pemakaian',true,'50%');
	$zfm->BuildFormButton('Simpan','rusak');
	echo "<hr/>";
}else{
	no_auth();
}
panel_multi_end();
panel_multi('listbarangrusak','block',false);
if($all_listbarangrusak!=''){
	echo "<form id='frm3' name='frm3' method='post' action=''>";
	addText(array('Periode','s/d','',''),
			array("<input type='text' id='dari_tgl' name='dari_tgl' value=''/>",
				  "<input type='text' id='sampai_tgl' name='sampai_tgl' value=''/>",
				  "<input type='button' id='okelah' name='okelah' value='OK'/>",
				  "<input type='button' id='prtr' name='prtr' value='Print'/>"));
	echo "</form>";
		$zlb->section('pemakaian');
		$zlb->aksi(($e_listbarangrusak!='')?true:false);
		$zlb->icon('deleted');
		$zlb->Header('100%');
		echo "</tbody></table>";
}else{
	no_auth();
}

panel_multi_end();
popup_start('pakai','Edit Barang Rusak/hilang',550);
	$fld="<input type='hidden' id='id_barang' name='id_barang' value=''/>";
	$fld.="<input type='hidden' id='id_satuan' name='id_satuan' value=''/>";
	$zfm->Addinput($fld);
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm2');
	$zfm->BuildForm('pemakaian',true,'100%');
	$zfm->BuildFormButton('Simpan','edit_pakai');
popup_end();
echo "<input type='hidden' value='2' id='aksine' />";
panel_end();
?>

