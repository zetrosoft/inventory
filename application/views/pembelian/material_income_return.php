<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_beli.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_beli.frm');
$path='application/views/pembelian';
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('jquery.fixedheader.js','asset/js');
link_js('material_income_return.js,auto_sugest.js,jquery_terbilang.js,jquery.sumfield.js',$path.'/js,asset/js,asset/js,asset/js');
panel_begin('Return Konsinyasi');
panel_multi('returnpembelian','block',false);
if($c_returnpembelian!=''){
	$zfm->AddBarisKosong(false);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('pembelian',false,'60%');
	echo "<hr><form id='frm2' name='frm2' method='post' action=''>";
		$zlb->section('pembelianlist');
		$zlb->aksi(true);
		$zlb->Header('97%');
		echo "<tr><td class='kotak' colspan='8'>&nbsp;</td>";
		echo "</tbody><tfoot>";
		$zfm->section('pembelianlist');
		$zfm->rowCount();
		$zfm->button('simpan');
		$zfm->BuildGrid();
		echo "</tfoot></table></form>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
terbilang();
?>
<div id='kekata' style="display:none;padding:8px; background:#003; border:5px solid #F60;width:80%; height:50px; font-size:x-large;left:9%;top: 82%;color:#FFF; position:fixed; z-index:9997">
	<!--terbilang jumlah yang harus di bayar-->
</div>
<input type="text" id='id_sat' value='' />
<input type="hidden" id='id_brg' value='' />
<input type="hidden" id='total_beli' value='0' />
<input type="hidden" id='trans_new' value='' />
<input type="hidden" id='id_pemasoke' value='' />
<input type='hidden' id='aktif_user' value='<?=$this->session->userdata('idlevel');?>'/>
<script language="javascript">
	$(document).ready(function(e) {
        $('#cara_bayar').html("<? dropdown('inv_penjualan_jenis','ID','Jenis_Jual',"where ID='3'",'3');?>");
    });

</script>



