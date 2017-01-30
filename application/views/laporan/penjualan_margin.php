<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_beli.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_beli.frm');
$path='application/views/laporan';
calender();
link_css('jquery.alerts.css','asset/css');
link_js('jquery.alerts.js','asset/js');
link_js('jquery.fixedheader.js','asset/js');
panel_begin('Margin Penjualan');
panel_multi('marginpenjualan','block');
if($all_marginpenjualan!=''){
    addText(array('Periode','s/d','Kategori','Lokasi','Order By','','','Search',''),
			array("<input type='text' id='frm_tgl' name='frm_tgl' value=''/>",
				  "<input type='text' id='to_tgl' name='to_tgl' value=''/>",
                  "<select id='kat' name='kat'></select>","<select id='id_lokasi' name='id_lokasi'></select>",
				  "<select id='orderby' name='orderby'>
                    <option value='Tanggal,NamaBarang,Modal'>Tanggal</option>
                    <option value='NamaBarang,Modal'>Nama Barang</option>
                    <option value='Margin,NamaBarang,Modal'>Margin Penjualan</option>
                  </select>",
				  "<input type='button' value='OK' id='ok'/>",
                  "<!--input type='button' id='toEx' name='toEx' value='Export to Excel'/-->",
				  "<input type='text' id='cari' name='cari' class='cari' style='width:200px' placeholder='Find by nama barang' value=''/>",
				  "<input type='button' value='GO' id='go'/>"
                  ),true,'frm1');
	//echo "</frm>";
		$zlb->section('MarginJual');
		$zlb->aksi(false);
		$zlb->icon();
		$zlb->Header('100%');
	echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
link_js('penjualan_margin.js',$path.'/js');
$lokasi=$this->zetro_auth->cek_area();
?>
<script language="javascript">
	$(document).ready(function(e) {
 	  $('#id_lokasi').html("<option value='' selected>Semua</option><? dropdown('user_lokasi','ID','Lokasi',"where ID in(".$lokasi.") order by ID",'');?>");
      $('#kat').html("<? dropdown('inv_barang_kategori','ID','Kategori','order by Kategori','0');?>");
    });

</script>