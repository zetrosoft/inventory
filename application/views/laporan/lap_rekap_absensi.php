<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$path='application/views/laporan';
calender();
link_js('jquery.fixedheader.js','asset/js');
link_js('lap_rekap_absensi.js',$path.'/js');
panel_begin('Lap.Absensi');
panel_multi('rekapabsensi','block',false);
if($all_laporan__rekapabsensi!='')
{	echo "<form name='frm1' id='frm1' method='post' action=''>";
	$bulan='\n';
    for($i=1;$i<=12;$i++)
	{
		$bulan.="<option value='".$i."'>".nBulan($i)."</option>\n";
	}
	//addText(array('Lokasi '.nbs()),array("<select id='id_lokasi' name='id_lokasi'></select>"),false);
	addText(array('Periode Bulan','Tahun','',"<input type='checkbox' id='detail' name='detail'>"),
			array("<select id='bulan' name='bulan'>".$bulan."</select>",
				  "<select id='tahun' name='tahun'></select>",
				  "<input type='button' id='ok' value='OK'>",'&nbsp;Detail View'),true);
	echo "</form>";
	echo tabel().
		 _thead().
		 tr().th('No.','10%','headere',"rowspan='2'").
		 th('Lokasi Karyawan','40%','headere',"rowspan='2'").
		 th('Total Hari','10%','headere',"rowspan='2'").
		 th('Keterangan','16%','headere',"colspan='2'").
		 _tr().
		 tr().th('Hadir','8%','headere').th('Absen','8%','headere')._tr().
		 _thead(false,true).
		 _tabel(true);
}
else
{
	no_auth();	
}
panel_multi_end();
panel_end();
?>
<script language="javascript">
	$(document).ready(function(e) {
    $('#id_lokasi')
		.html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().") order by id",$this->session->userdata('gudang'));?>")
  		.val($('#lok').val()).select()
    $('#ID_Dept')
		.html("<? dropdown('user_lokasi','ID','lokasi',"where id in(".$this->zetro_auth->cek_area().") order by id",$this->session->userdata('gudang'));?>")
  		
	//disabled jika satu lokasi
	 if($('#jml_area').val()=='1')
	 { 
	 	lock('#id_lokasi');
		lock('#ID_Dept')
	 }else{
		 unlock('#id_lokasi');
		 unlock('#ID_Dept')
	 }
	  $('#ok').click();
    });
</script>
