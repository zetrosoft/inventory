<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_beli.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_beli.frm');
calender();
$path='application/views/penjualan';
$printer="<img src='".base_url()."asset/images/print.png' id='printsheet' title='Print count sheet'>";
link_css('autosuggest.css','asset/css');
link_css('jquery.alerts.css','asset/css');
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.alerts.js','asset/js');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('jquery.fixedheader.js','asset/js');
link_js('material_jual.js,jquery_terbilang.js,jquery.sumfield.js',$path.'/js,asset/js,asset/js');
panel_begin('Penjualan');
panel_multi('penjualanumum','block',false);
?>
<form id='frm100' name='frm100' method="post" action="">
<input type="hidden" id='id_member' value='' />
<input type="hidden" id='jmlbayar' value='' />
<input type="hidden" id='nama' value='' />
<input type="hidden" id='trans_new' value='' />
<input type="hidden" id='huruf' value='' />
<input type='hidden' id='aktif' value='1'/>
<input type='hidden' id='idaktif' value='1__jml_transaksi'/>
<input type='hidden' id='hrgaktif' value='1__harga_jual'/>
<input type='hidden' id='stat_sim' value=''/>
<input type='hidden' id='aktif_user' name='aktif_user' value='<?=$this->session->userdata('idlevel');?>'/>
<input type='hidden' id='brssimpan' value=''/>
<input type='hidden' id='maxlpg' value=''/>
<input type='hidden' id='isLpg' value=''/>
<input type='hidden' id='lpgMember' value=''/>
</form>
<?
if($c_penjualan__index!=''){
	echo "<table id='frame' width='99%'>
		 <tr valign='top'><td width='45%'>";
		$zfm->AddBarisKosong(false);
		$zfm->Start_form(true,'frm1');
		$zfm->BuildForm('jualan',false,'100%');
		//$zfm->BuildFormButton('Process','filter','button',2);
	echo"</td><td class='kotak' colspan='2' width='55%'>
	<div id='kasir' class='harga' align='right'></div>
	<div id='trblkasir' style='font-size:medium; font-weight:bold'>Rp. </div></td></tr>
	<!--tr><td>Nama Pelanggan </td><td><input id='nas' name='nas' data-provide='typeahead' data-source=\"[".$nasabah."]\" value=''/></td></tr-->
	</table>
	<hr>
	<table id='frame2' width='99%'>
		<tr valign='top'><td rowspan='1' width='75%' style='overflow:auto; max-height:100px'>";
	echo "<div  style='height:320px;overflow:auto'>
		<form id='frm2' name='frm2' method='post' action=''>";
			$zlb->section('penjualanlist');
			$zlb->aksi(false);
			$zlb->Header('100%');
			$zfm->section('penjualanlist');
			/*$zfm->rowCount(30);
			$zfm->button('simpan');
			$zfm->BuildGrid(false);*/
	for($i=1;$i<=15;$i++){
	echo tr().
		 td((strlen($i)==1)?"0".$i:$i,'center').
		 td("<input type='text' id='".$i."__nm_barang' class='w100 upper xx n_".$i."' name='".$i."__nm_barang' data-provide='typeahead' data-source=\"[".$datas."]\" value=''/>").
		 td("<input type='text' id='".$i."__nm_satuan' class='w100 upper' name='".$i."__nm_satuan' value=''/>").
		 td("<input type='text' id='".$i."__jml_transaksi' class='w100 angka' name='".$i."__jml_transaksi' value=''/>").
		 td("<input type='text' id='".$i."__harga_jual' class='w100 angka' name='".$i."__harga_jual' value=''/>").
		 td("<input type='text' id='".$i."__harga_total' class='w100 angka subtt' name='".$i."__harga_total' value=''/>").
		 td("<input type='text' id='".$i."__expired' class='w100 angka' name='".$i."__expired' value=''/>").
		_tr();
	}
	echo "</tbody></table></form>";
	echo "</div></td>
	<td class='kotak' width='25%' valign='top' height='70px' rowspan='2'>
		  <table id='inform' style='border-collapse:collapse' width='100%'>
		  	<tr class='header'><td colspan='2' class='kotak'>Information</td></tr>
		  	<tr><td width='40%' class='kotak'>In line Stock</td>
		  	  	<td class='kotak' id='ist'></td>
			</tr>
		  	<tr style='display:none'><td width='40%' class='kotak'>Modal</td>
		  	  	<td class='kotak' id='mdl'></td>
			</tr>
		  	<tr><td class='kotak'><!--PPN 10%--></td><td class='kotak' id='xst'>
		  		<!--input type='checkbox' id='ppne' disabled>Aktif--></td>
			</tr>
			<tr><td colspan='2'>&nbsp;</td></tr>
			<tr><td colspan='2'><input type='hidden' id='v_ist' value=''></td></tr>
		  </table>
	<!--/td></tr>
	<tr><td class='kotak' valign='top' align=''-->
		<table width='100%' id='b'>
		<tr><td class='kotak' width='35%'>Cara Bayar </td>
		<td class='kotak' width='65%'> <select id='cbayare' class='S100'></select></td>
		</tr>
		<!--<tr id='nontunai'><td class='kotak' width='35%'>Nomor </td>
		<td class='kotak' width='65%'><input type='text' id='nogiro' class='w100 upper'></td>
		</tr>
		<tr id='nontunai'><td class='kotak' width='35%'>Bank </td>
		<td class='kotak' width='65%'><input type='text' id='n_bank' class='w100 upper'></td>
		</tr>
		<tr id='nontunai'><td class='kotak' width='35%'>Tanggal </td>
		<td class='kotak' width='65%'><input type='text' id='tgl_giro' class='w100 upper'></td>
		</tr>-->
		<tr><td colspan='2'>&nbsp;</td></tr>
		<tr><td colspan='2' align='center'>
		  <input type='button' id='bayar' value='Bayar'>
		  <!--input type='button' id='kredit' value='Bayar Kredit'-->
		  <input type='button' id='batal' value='Batal'>
		  </td>
		 </tr></table>
	</tr>
	 <tr><td colspan='1' class=''>
		  <table style='border-collapse:collpse'>
			  <tr align='center'>
				<td class='kotak' width='80px'>F1<br> Cek Stock</td>
				<td class='kotak' width='80px'>F2<br> Re Print Slip</td>
				<td class='kotak' width='80px'>F4<br> Edit</td>
			  </tr>
		  </table>
	 </td></tr>
	</table>";
}else{
	no_auth();
}
panel_multi_end();
panel_multi('penjualangas','non','false');

panel_multi_end();
panel_end();
popup_start('pembayaran','Pembayaran',400);
		$zfm->AddBarisKosong(true);
		$zfm->Start_form(true,'frm3');
		$zfm->BuildForm('bayaran',true,'100%');
		$zfm->BuildFormButton('Process','dibayar','button',1);
popup_end ();
popup_start('kredited','Pembayaran Kredit',400);
		$zfm->AddBarisKosong(true);
		$zfm->Start_form(true,'frm5');
		$zfm->BuildForm('kredite',true,'100%');
		$zfm->BuildFormButton('Process','dikredit','button',1);
popup_end ();
popup_start('viewstock','Stock Overview',500,600);
		$zfm->frm_filename('asset/bin/zetro_inv.frm');
		$zfm->AddBarisKosong(true);
		$zfm->Start_form(true,'frm4');
		$zfm->BuildForm('stokoverview',false,'100%');
		//$zfm->BuildFormButton('Process','dibayar','button',1);
	echo "<hr>";
		$zlb->config_file('asset/bin/zetro_inv.frm');
		$zlb->section('stokoverlist');
		$zlb->aksi(false);
		$zlb->Header('100%');
	echo "</tbody></table>";
popup_end();
popup_start('editline','Login for edit');
popup_end();
popup_start('canceltrans','Edit Transaksi',850);
		$zlb->config_file('asset/bin/zetro_beli.frm');
		$zlb->section('penjualanlist');
		$zlb->aksi(true);
		$zlb->Header('100%');
	echo "</tbody></table>";
popup_end();
popup_start('tranresep','Re Print Slip',500,600);
		$zfm->frm_filename('asset/bin/zetro_beli.frm');
		$zfm->AddBarisKosong(true);
		$zfm->Start_form(true,'frm9');
		$zfm->BuildForm('printslip',true,'100%');
		$zfm->BuildFormButton('Print','prtslip');
popup_end();
auto_sugest();
tab_select('prs');
terbilang();
//echo $nasabah;
?>
<div id='kekata' style="display:none;padding:8px; background:#003; border:5px solid #F60;width:80%; height:50px; font-size:x-large;left:9%;top: 82%;color:#FFF; position:fixed; z-index:9997">
	<!--terbilang jumlah yang harus di bayar-->
</div>
<script language="javascript">
$(document).ready(function(e) {
    $('#cbayare').html("<? dropdown('inv_penjualan_jenis','ID','Jenis_Jual',"where ID in ('1') order by ID",'1');?>");
	//$('#frm2 table#ListTable').fixedHeader({width:(screen.width-300),height:(screen.height-450)})
	$('#frm1 input#nm_nasabah').attr({'data-source':'[<?=$nasabah;?>]'});
});
</script>