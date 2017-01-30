<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$section='Barang';
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/controlpanel';
calender();
link_js('jquery.fixedheader.js,karyawan_gaji.js','asset/js,'.$path.'/js');
tab_select('');
panel_begin('Laporan Gaji');
panel_multi('rekapgaji','block',false);
if($all_rekapgaji!=''){
	  // ($c_kasbonkaryawan!='')?addText(array("<input type='button' id='addkasbon' value='+ Karyawan kasbon'/>"),array('')):'';
	   addText(array('','Bulan','Tahun','','',"<span id='imgs'></span>"),
	   		   array("<select id='userlok' name='userlok' style='display:none'></select>",
			   		 "<select id='Bulan' name='Bulan'>".dropdownBln(date('m'))."</select>",
					 "<select id='Tahun' name='Tahun'></select>",
			   		 "<input type='button' id='ok' value='OK'>",
					 "<input type='button' id='prn' value='Print Rekap'/>",''),true,'frm1');
		echo "<div id='lstGaji' style='width:100%; height:500px; overflow=auto'></div>";
}else{
	no_auth();
}
panel_multi_end();
popup_start('print_prev','Print Preview ',800,750);
	echo "<iframe id='prv' src='' height='100%' width='100%' frameborder='0' allowtransparency='1' style='z-index:100'></iframe>";
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
	//disabled jika satu lokasi
	 if($('#jml_area').val()=='1')
	 { 
	 	lock('#userlok');
	 }else{
		 unlock('#userlok');
	 }
	  $('#ok').click();
    });

</script>
