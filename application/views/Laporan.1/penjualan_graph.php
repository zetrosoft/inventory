<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$path='application/views/laporan';
link_js('FusionCharts.js','asset/js');
link_js('penjualan_graph.js',$path.'/js');
panel_begin('Grafik Penjualan');
panel_multi('grafikpenjualan','block',false);
if($all_grafikpenjualan!=''){
addText(array('Bulan','Tahun','','','Model Grafik'),
		array("<select id='bln' name='bln'></select>",
			   "<select id='thn' name='thn'></select>",
			  "<input type='button' id='okelah' value='OK'>",
			  '',"<select id='j_graph' name='j_graph'>
			  	  <option value='FCF_MSLine' selected>Grafik Garis</option>
			  	  <option value='FCF_MSColumn3D'>Grafik Bar 3D</option>
				  <option value='FCF_MSArea2D'>Grafik Area</option>
				  </select>"
				  ));
			  
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
function show_graph(id,jenis){
	var height=(screen.height-330);
	var width=(screen.width-50);
		   var chart = new FusionCharts("<?=base_url();?>chart/"+jenis+".swf", "ChartId", width, height);
		   chart.setDataURL("<?=base_url().$this->session->userdata('userid');?>_graph_j.xml");		
		   chart.render(id);
}

</script>
