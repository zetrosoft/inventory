<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$section='Barang';
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/inventory';
$kateg="<select id='plh' name='plh' class='s100'></select>";
$jenis="<select id='plh_jenis' name='plh_jenis' class='s100'></select>";
$stats="<select id='plh_stat' name='plh_stat' class='s100'>
		<option value='all'>Semua</option>
		<option value='Continue'>Continue</option>
		<option value='Discontinue'>Discontinue</option></select>";
$cari="<input id='plh_cari' name='plh_cari' class='w100 cari'>";
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js,jquery_terbilang.js','asset/js,asset/js');
link_js('jquery.fixedheader.js,material_list.js','asset/js,'.$path.'/js');
panel_begin('Daftar Barang');
panel_multi('listbarang','block',false);
if($e_listbarang!='' || $v_listbarang!=''){
addText(array('Filter by Kategori :',' Jenis :','Status',' Cari by nama'),array($kateg,$jenis,$stats,$cari));
/*	$sql="select * from inv_barang order by nama_barang";*/
		$zlb->section('BarangList');
		$zlb->aksi(($e_listbarang!='')?true:false);
		$zlb->Header('100%');
		$zlb->icon();
/*
		$zlb->query($sql);
		$zlb->sub_total(false);
		$zlb->sub_total_field('4,5');
		$zlb->list_data($section);
		$zlb->BuildListData('nama_barang');*/
		echo "</tbody></table>";
		
}else{
	no_auth();
}
echo "<hr><div id='bawahan' style='display:none;width:100%; height:25px; background:#AAA; border:1px outset #CCCC'></div>";
panel_multi_end();
panel_end();
popup_start('nm_jenis','Tambah Jenis',350);
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm5');
	$zfm->BuildForm('Jenis',true,'100%');
	$zfm->BuildFormButton('Simpan','jenis');
popup_end();
popup_start('nm_kategori','Tambah Kategori',350);
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm6');
	$zfm->BuildForm('Kategori',true,'100%');
	$zfm->BuildFormButton('Simpan','kat');
popup_end();
popup_start('nm_golongan','Add Sub Kategori',350);
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm7');
	$zfm->BuildForm('Golongan',true,'100%');
	$zfm->BuildFormButton('Simpan','subkat');
popup_end();
popup_start('nm_satuan','Tambah Satuan',300);
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm8');
	$zfm->BuildForm('Satuan',true,'100%');
	$zfm->BuildFormButton('Simpan','satuan');
popup_end();
popup_start('edit_barang','Edit Data',550);
	$zfm->Addinput("<input type='hidden' id='id_item' name='id_item' value=''>");
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm9');
	$zfm->BuildForm($section,true,'100%');
	$zfm->BuildFormButton('Simpan','edit_mat');
popup_end();
popup_start('edit_detail_barang','Edit Data Detail',550);
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm11');
	$zfm->BuildForm('BarangDetail',true,'100%');
	$zfm->BuildFormButton('Simpan','edit_mat_det');
popup_end();
auto_sugest();
terbilang();
echo "<input type='hidden' value='".date('d/m/Y')."' id='today'>";
?>
<script language="javascript">
	$(document).ready(function(e) {
        $('#plh').html("<? dropdown('inv_barang_kategori','ID','Kategori','order by Kategori','28');?>");
		$('#plh_jenis').html("<? dropdown('inv_barang_jenis','ID','JenisBarang','order by JenisBarang');?>");
		//$('#v_listobat table#ListTable').fixedHeader({width:900,height:400})
	_show_data();
    });
</script>
<input type='hidden' id='id_kategori' value=''>
<input type='hidden' id='id_jenis' value=''>
<input type='hidden' id='id_satuan' value=''>
