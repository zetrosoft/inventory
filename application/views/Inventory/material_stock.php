<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/inventory';
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('jquery.fixedheader.js,material_stock.js,auto_sugest.js,jquery_terbilang.js','asset/js,'.$path.'/js,asset/js,asset/js');
panel_begin('Stock');
panel_multi('stockoverview','block',false);
if($v_stock__index!=''){
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('stokoverview',false,'50%');
	echo "<hr>";
		$zlb->section('stokoverlist');
		$zlb->aksi(false);
		$zlb->icon();
		$zlb->Header('60%','stoked');
	echo "</tbody></table>";
}else{ no_auth();}
panel_multi_end();
panel_multi('liststock','none',false);
if($v_liststock!=''){
	echo "<form id='frm2' name='frm2' method='post' action=''>";
	addText(array('Filter by Kategori','Jenus','Status','','Nama Barang/Kode   ',''),
			array("<select id='id_kategori' name='id_kategori' class='s100'></select>",
				  "<select id='plh_jenis' name='plh_jenis' class='s100'></select>",
				  "<select id='stat_barang' name='stat_barang' class='s100'>
					  <option value=''>Semua</option>
					<option value='Continue'>Continue</option>
					<option value='Discontinue'>Discontinue</option></select>",
				  '<input type="button" id="saved-filter" value="Process">',
				  '<input type="text" id="nam_barang" name="nam_barang" value="" class="w100 carix" title="Silahkan ketik nama barang atau kode barang">',
				   '<input type="button" id="cari" value="Cari">'));
	echo "</form>";

		$zlb->section('stoklistview');
		$zlb->aksi(true);
		$zlb->icon();
		$zlb->Header('100%');
		echo "</tbody></table>";
}else{ no_auth();}
panel_multi_end();
	addText(array('Total Record :'),array('<div id="ttr"></div>'),false);
panel_end();
auto_sugest();
?>
<script language="javascript">
	$(document).ready(function(e) {
    //$('#v_liststock table#ListTable').fixedHeader({width:700,height:350})
	$('#id_kategori').html("<? dropdown('inv_barang_kategori','ID','Kategori','order by Kategori','2');?>");
	$('#plh_jenis').html("<? dropdown('inv_barang_jenis','ID','JenisBarang','order by JenisBarang');?>");
    });
</script>