<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$section='Barang';
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/master';
link_js('jquery.fixedheader.js,master_general.js,jquery_terbilang.js','asset/js,'.$path.'/js,asset/js');
tab_select('');
panel_begin('Data Akun');
panel_multi('dataakun','block',false);
if($all_dataakun!=''){
	$attr=($c_dataakun!='')?'':'disabled';
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm3');
	$zfm->BuildForm('Kas',true,'50%');
	$zfm->AttrButton($attr);
	$zfm->BuildFormButton('Simpan','kas');
	echo "<hr>";
		$zlb->section('Kas');
		$zlb->aksi(true);
		$zlb->icon('delete');
		$zlb->Header('70%');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
auto_sugest();
tab_select('prs');
panel_end();
terbilang();

?>

<script language="javascript">
	$(document).ready(function(e) {
    });
</script>

