<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$section='Barang';
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/controlpanel';
calender();
link_js('jquery.fixedheader.js,karyawan_kas.js','asset/js,'.$path.'/js');
tab_select('');
panel_begin('Kasbon Karyawan');
panel_multi('kasbonkaryawan','block',false);
if($all_kasbonkaryawan!=''){
	   //($c_kasbonkaryawan!='')?
	   addText(array("<input type='button' id='addkasbon' value='+ Karyawan kasbon'/>"),array(''));//:'';
	   addText(array('Lokasi','Bulan','Tahun','',),
	   		   array("<select id='userlok' name='userlok'></select>",
			   		 "<select id='Bulan' name='Bulan'>".dropdownBln(date('m'))."</select>",
					 "<select id='Tahun' name='Tahun'></select>",
			   		 "<input type='button' id='ok' value='OK'>"),true,'frm1');
		$zlb->section('Kasbon');
		$zlb->aksi(true);
		$zlb->icon();
		$zlb->Header('100%');
		echo _tabel(true);
}else{
	no_auth();
}
panel_multi_end();
popup_start('addnew','Tambah Karyawan Kasbon');
	$zfm->Addinput("<input type='hidden' id='ID' name='ID' value=''/>");
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm3');
	$zfm->BuildForm('Kasbon',true,'100%');
	$zfm->BuildFormButton('Simpan','person');
popup_end();
panel_end();
?>
<script language="javascript">
	$(document).ready(function(e) {
    $('#userlok')
		.html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().") order by id",$this->session->userdata('gudang'));?>")
  		.val($('#lok').val()).select()
    $('#Tahun')
		.html("<? dropdownThn('karyawan_kasview','Tahun','Tahun'," order by Tahun",date('Y'));?>")
      $('#Nama')
		.html("<? dropdown('karyawan','ID','Nama'," order by Nama");?>")
		
	//disabled jika satu lokasi
	 if($('#jml_area').val()=='1')
	 { 
	 	lock('#userlok');
		lock('#Lokasi')
	 }else{
		 unlock('#userlok');
		 unlock('#Lokasi')
	 }
	  $('#ok').click();
    });

</script>
