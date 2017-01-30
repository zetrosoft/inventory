<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/inventory';
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js,jquery_terbilang.js','asset/js,asset/js');
link_js('jquery.fixedheader.js,material_list.js','asset/js,'.$path.'/js');
panel_begin('Konversi Satuan');
panel_multi('konversisatuan','block',false);
if($all_konversisatuan!=''){
	$attr=($c_konversisatuan!='')?'':'disabled';
	$zfm->AddBarisKosong();
	$zfm->Start_form(true,'frm4');
	$zfm->BuildForm('Konversi',true,'70%');
	$zfm->AttrButton($attr);
	$zfm->BuildFormButton('Simpan','konv');
	echo "<hr>";
		$zlb->section('Konversi');
		$zlb->aksi(true);
		$zlb->icon('deleted');
		$zlb->Header('70%','konversi');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
?>
<script language="javascript">
$(document).ready(function(e) {
    	$('#konversisatuan').removeClass('tab_button');
		$('#konversisatuan').addClass('tab_select');
		$('#add-nm_satuan').hide();
});

</script>