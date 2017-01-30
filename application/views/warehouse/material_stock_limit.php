<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_neraca.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_neraca.frm');
$path='application/views/warehouse';
$sesi=$this->session->userdata('menus');
link_js('jquery.fixedheader.js','asset/js');
link_js('material_stock_limit.js',$path.'/js');
calender();
panel_begin('Sugest Pembelian');
panel_multi('stocklimit','block',false);
if($all_stocklimit!=''){
	echo "<form id='frm1' name='frm1' method='post' action=''>";
	addText(array('Kategori','Order By','Urutan',''),
			array("<select id='Kategori' name='Kategori'></select>",
				  "<select id='orderby' name='orderby'>".selectTxt('SusunanStock')."</select>",
				  "<select id='urutan' name='urutan'>".selectTxt('Urutan',true)."</select>",
				  "<input type='button' value='Print' id='prt'/>"));
	echo "</form>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
?>
<script language="javascript">
$(document).ready(function(e) {
    $('#Kategori').html("<option value=''>Semua</option><? dropdown('inv_barang_kategori','ID','Kategori','order by Kategori','');?>")
	$('#okelah').click();

});
</script>
