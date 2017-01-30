<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$section='Barang';
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/controlpanel';
link_js('jquery.fixedheader.js,master_lokasi.js','asset/js,'.$path.'/js');
tab_select('');
panel_begin('Lokasi Gudang');
panel_multi('lokasigudang','block',false);
if($all_lokasigudang!=''){
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm3');
	$zfm->BuildForm('gudang',true,'50%');
	$zfm->BuildFormButton('Simpan','lokasi');
	echo "<hr>";
		$zlb->section('gudang');
		$zlb->aksi(true);
		$zlb->icon('delete');
		$zlb->Header('100%');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
?>
<input type="hidden" id='otox' value="<?=$c_lokasigudang;?>">
<input type='hidden' id='id_lokasi' value='' />
<input type='hidden' id='lok_server' value=''  />

