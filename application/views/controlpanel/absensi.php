<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$section='Barang';
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/controlpanel';
link_js('jquery.fixedheader.js,absensi.js','asset/js,'.$path.'/js');
tab_select('');
calender();
panel_begin('Kehadiran');
panel_multi('absensiharian','block',false);
if($all_controlpanel__absensi!=''){
	   
	   addText(array('Lokasi ','Tanggal Absen',''),
	   		   array("<select id='lokasi' name='lokasi'></select>",
			   "<input type='text' class='w50' id='tgl_absen' name='tgl_absen' value='".date('d/m/Y')."' >",
			   		 "<input type='button' id='ok' value='OK'>"),true,'frm1');
		$zlb->section('Absensi');
		$zlb->aksi(false);
		$zlb->icon();
		$zlb->Header('75%');
		echo _tabel(true);
}else{
	no_auth();
}
panel_multi_end();
panel_end();

?>
<script language="javascript">
	$(document).ready(function(e) {
    $('#lokasi')
		.html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().") order by id",$this->session->userdata('gudang'));?>")
  		.val($('#lok').val()).select()
  		
	//disabled jika satu lokasi
	 if($('#jml_area').val()=='1')
	 { 
	 	lock('#lokasi');
	 }else{
		 unlock('#lokasi');
	 }
	  $('#ok').click();
    });

</script>
	