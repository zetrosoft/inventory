<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_member.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_member.frm');
$path='application/views/member';
calender();
link_css('jquery.alerts.css','asset/css');
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.alerts.js','asset/js');
link_js('jquery.coolautosuggest.js,auto_sugest.js','asset/js,asset/js');
link_js('jquery.fixedheader.js,jquery_terbilang.js,member_pinjaman.js','asset/js,asset/js,'.$path.'/js');
panel_begin('Pembayaran Tagihan');
panel_multi('pinjaman','none',false);
if($all_pembayarantagihan!=''){
$fld="<input type='hidden' value='' id='ID_Perkiraan'>";
	$zfm->Addinput($fld);
	$zfm->AddBarisKosong();
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('pinjaman',true,'60%');
	$zfm->BuildFormButton('Simpan','pinjaman');
}else{
	no_auth();
}
panel_multi_end();
panel_multi('pembayarantagihan','block');
if($all_pembayarantagihan!=''){
$fld="<input type='hidden' value='' id='ID_Perkiraane'>";
	$zfm->Addinput($fld);
	$zfm->AddBarisKosong();
	$zfm->Start_form(true,'frm2');
	$zfm->BuildForm('setoranpinjaman',true,'60%');
	$zfm->BuildFormButton('Preview','nota');
	echo "$fld<br><hr/><div id='dat_pinjm' style='width:70%;display:none;padding:5px;'>";
	echo "<table id='dat_simp' width='100%' style='border-collapse:collapse'>";
		echo "<thead>
			<tr class='headere' align='center'>
				<th class='kotak' width='5%'>No.</th>
				<th class='kotak' width='15%'>Tanggal </th>
				<th class='kotak' width='18%'>Tagihan</th>
				<th class='kotak' width='18%'>Pembayaran</th>
				<th class='kotak' width='20%'>Saldo</th>
				<th class='kotak' width='30%'>Keterangan</th>
				</tr>
		</thead><tbody>";

	echo "</tbody></table></div>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
echo "<div id='kekata' class='infox'></div>
	<input type='hidden' id='baris' value=''>
	<input type='hidden' id='Tahun' value=''>";
inline_edit('frm2');
terbilang();
?>
<script language='javascript'>
$(document).ready(function(e){
   $('#frm2 input#ID_Agt').attr({'data-source':'[<?=$nasabah;?>]'}); 
});
</script>