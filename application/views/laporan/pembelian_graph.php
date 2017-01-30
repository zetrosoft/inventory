<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$path='application/views/laporan';
link_js('FusionCharts.js','asset/js');
link_js('pembelian_graph.js',$path.'/js');
panel_begin('Grafik Pembelian');
panel_multi('grafikpembelian','block');
if($all_grafikpembelian!=''){
addText(array('Tahun',''),
		array("<select id='thn' name='thn'></select>",
			  "<input type='button' id='okelah' value='OK'>"));
			  
echo "<div id='chartdiv' align='center'>";
		echo tabel()."<thead>";
		echo tr('headere\' align=\'left').th('Kalkulasi Data');
		echo _tr()."</thead><tbody>";/**/
		echo _tabel(true);
echo "</div>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
loading_ajax();
?>

<script language="javascript">
$(document).ready(function(e) {
    
});
function show_graph(id){
	var height=(screen.height-330);
	var width=(screen.width-50);
		   var chart = new FusionCharts("<?=base_url();?>chart/FCF_Column3D.swf", "ChartId", width, height);
		   chart.setDataURL("<?=base_url().$this->session->userdata('userid');?>_graph_p.xml");		
		   chart.render(id);
}

</script>
