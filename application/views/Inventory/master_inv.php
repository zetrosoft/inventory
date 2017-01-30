<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$section='Barang';
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/inventory';
link_js('material_kat.js',$path.'/js');
link_js('jquery.fixedheader.js','asset/js');
tab_select('');
panel_begin('Kategori Barang');
panel_multi('kategoribarang','block',false);
if($all_kategoribarang!=''){
	$attr=($c_kategoribarang!='')?'':'disabled';
	$zfm->AddBarisKosong(false);
	$zfm->Start_form(true,'frm2');
	$zfm->BuildForm('Kategori',true,'50%');
	$zfm->AttrButton($attr);
	$zfm->BuildFormButton('Simpan','kat');
	echo "<hr/>";
		$zlb->section('Kategori');
		$zlb->aksi(true);
		$zlb->icon('deleted');
		$zlb->Header('100%','Jenis');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();

?>
<input type='hidden' id='page' value='kategoribarang' />