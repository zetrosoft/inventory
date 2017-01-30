// JavaScript Document
//tab button event
$(document).ready(function(e){
	$('#grafikpembelian').removeClass('tab_button');
	$('#grafikpembelian').addClass('tab_select');
	
	$('table#panel tr td:not(.flt,.plt)').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
	})
	$('#newTable').hide();
	var today=new Date()
	_get_tahun()
	_generate_data_shu(today.getFullYear());
	$('#okelah').click(function(){
		_generate_data_shu($('#thn').val());
	})
	$('#thn').change(function(){
		_generate_data_shu($(this).val());
	})
	
})

function _generate_data_shu(thn){
	$('#newTable').show();
	show_indicator('newTable','6');
	$.post('graph_pembelian_data',{'thn':thn},
		function(result){
			show_graph('chartdiv');	
		})
		
		/*show_graph('chartdiv');*/	
}

function _get_tahun(){
	$.post('get_tahune',{'id':''},
	function(result){
		$('#thn').html(result);
	})
}

function _get_bln(){
	$.post('get_bulan',{'id':''},
	function(result){
		$('#bln').html(result);
	})
}
