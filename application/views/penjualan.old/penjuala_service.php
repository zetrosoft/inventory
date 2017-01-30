<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$file='asset/bin/zetro_beli.frm';
$zfm=new zetro_frmBuilder($file);
$zlb=new zetro_buildlist();
$zlb->config_file($file);
$path='application/views/penjualan';
calender();
AutoCompleted();
link_js('jquery.sumfield.js','asset/js');
link_js('jquery.fixedheader.js,penjualan_service.js','asset/js,'.$path.'/js');
link_js('penjualan_service_bayar.js',$path.'/js');
panel_begin('Service');
panel_multi('listservice','block',false);
if($all_listservice!=''){
	   ($c_listservice!='')?addText(array('',''),
	   		array("<input type='button' id='addservice' value='Service Baru'/>",
				  "<input type='button' id='ambilservice' value='Pengambilan'/>")):'';
	  echo "<form id='frm1' name='frm1' action='' method='post'>";
	   addText(array('Lokasi','Status',''),
	   		   array("<select id='userlok' name='userlok'></select>",
			   		 "<select id='stat_service' name='stat_service'>
					 <option value=''>Semua</option>
					 <option value='N' selected >Belum diambil</option>
					 <option value='Y'>Sudah diambil</option>
					 </select>",
			   		 "<input type='button' id='ok' value='OK'>"));
	  echo "</form>";
		$zlb->section('service');
		$zlb->aksi(true);
		$zlb->icon();
		$zlb->Header('100%');
		echo _tabel(true);
}else{
	no_auth();
}
panel_multi_end();
popup_start('addnew','Penerimaan Service Baru',500,700);
	$zfm->Addinput("<input type='hidden' id='ID' name='ID' value=''/>");
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm3');
	$zfm->BuildForm('service',true,'100%');
	$zfm->BuildFormButton('Simpan','service');
popup_end();
popup_start('bayar','Pengambilan Service',800,800);
echo "<form id='frm2' name='frm2' method='post' action=''>\n
	<table width='100%' style='border-collapse:collapse'>
	<tr valign='top'><td width='45%'>\n";
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(false);
	$zfm->BuildForm('BayarService',false,'100%');
	//$zfm->BuildFormButton('Simpan','service');
echo "\n</td><td width='5%'>&nbsp;</td><td width='50%'>\n";
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(false);
	$zfm->BuildForm('BayarService2',false,'100%');
	//$zfm->BuildFormButton('Simpan','service');
echo "</td></tr>\n<tr><td colspan='3' class='b_line'>Jasa service dan pemakaian spare part</td></tr>\n<tr><td colspan='3'>\n";
	echo "<!--div  style='height:320px;overflow:auto'-->";
			$zlb->section('penjualanlist');
			$zlb->aksi(false);
			$zlb->Header('100%','newTable');
			$zfm->section('penjualanlist');
			$zfm->rowCount(6);
			$zfm->button('simpan','Service');
			$zfm->BuildGrid(false);
			echo "</tbody></table>";
	echo "<!--/div-->";
$lokasi=$this->session->userdata('gudang');
echo "</td></tr>
<tr><td id='resultant'></td>
<td colspan='2' align='center'>
<input type='hidden' id='id_member' name='id_member' value=''/>
<input type='hidden' id='max_input' value='0' />
<input type='hidden' id='aktif' value='' />
<input type='hidden' id='deskirpsi' name='deskripsi' value='Pendapatan jasa service'>
<input type='hidden' id='total' name='total' value=''>
<input type='hidden' id='lokasi' name='lokasi' value='".$lokasi."'>
<input type='hidden' id='jml_baris' name='jml_baris' value=''></td></tr></table>";
addText(array('',''),array("<input type='button' id='simpanbayar' value='Bayar'>",
		"<input type='reset' id='batal' value='Cancel'>"),false);
echo "<hr></form>";
popup_end();
panel_end();
?>
<script language="javascript">

$(document).ready(function(e){$('#userlok').html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().")order by id",$this->session->userdata('gudang'));?>").val($('#lok').val()).select();$('#ID_Dept').html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().")order by id",$this->session->userdata('gudang'));?>");if($('#jml_area').val()=='1'){lock('#userlok');lock('#ID_Dept');}else{unlock('#userlok');unlock('#ID_Dept');};$('#ok').click();});
function buka_wind(id)
{
	 window.open("<?=base_url();?>penjualan_slipt.php?userid="+id,
				  "mediumWindow",
				  "width=550,height=225,left="+((screen.width/2)-(550/2))+", top=150" +
				  "menubar=No,scrollbars=No,addressbar=No,status=No,toolbar=No");
};

</script>
<input type='hidden' id='stat' value='1' />
<input type='hidden' id='id_member' value='' />