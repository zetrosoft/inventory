<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$path='application/views/laporan';
link_js('FusionCharts.js','asset/js');
link_js('cash_graph.js',$path.'/js');
panel_begin('Grafik Kas');
panel_multi('cashflow','block',false);
if($all_cashflow!=''){
addText(array('Bulan','Tahun','','','Model Grafik'),
		array("<select id='bln' name='bln'></select>",
			   "<select id='thn' name='thn'></select>",
			  "<input type='button' id='okelah' value='OK'>",
			  '',"<select id='j_graph' name='j_graph'>
			  	  <option value='FCF_MSLine' selected>Grafik Garis</option>
			  	  <option value='FCF_MSColumn3D'>Grafik Bar 3D</option>
				  <option value='FCF_MSArea2D'>Grafik Area</option>
				  </select>"
				  ),true,'cashflow');
			  
echo "<div id='chartdiv_cashflow' align='center'>";
		echo tabel()."<thead>";
		echo tr('headere\' align=\'left').th('Kalkulasi Data');
		echo _tr()."</thead><tbody>";/**/
		echo _tabel(true);
echo "</div>";
}else{
	no_auth();
}
panel_multi_end();
panel_multi('labarugi','none',false);
if($all_labarugi!=''){
addText(array('Bulan','Tahun','','','Model Grafik'),
		array("<select id='bln_lb' name='bln_lb'></select>",
			   "<select id='thn_lb' name='thn_lb'></select>",
			  "<input type='button' id='okedech_lb' value='OK'>",
			  '',"<select id='j_graph_lb' name='j_graph_lb'>
			  	  <option value='FCF_MSLine' selected>Grafik Garis</option>
			  	  <option value='FCF_MSColumn3D'>Grafik Bar 3D</option>
				  <option value='FCF_MSArea2D'>Grafik Area</option>
				  </select>"
				  ),true,'labarugi');
			  
echo "<div id='chartdiv_labarugi' align='center'>";
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
<input type='hidden' value='cashflow' id='pos' />
<script language="javascript">
$(document).ready(function(e) {
    
});
function show_graph(id,jenis){
	var nj=id.split('_');
	var height=(screen.height-330);
	var width=(screen.width-50);
		   var chart = new FusionCharts("<?=base_url();?>chart/"+jenis+".swf", "ChartId", width, height);
		   chart.setDataURL("<?=base_url().$this->session->userdata('userid');?>_graph_"+nj[1]+".xml");		
		   chart.render(id);
}

</script>
