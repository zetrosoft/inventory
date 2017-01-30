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
	_get_bulan()
	_get_tahun()
	_generate_data_shu(today.getFullYear(),(today.getMonth()+1),$('#j_graph').val());
	$('#okelah').click(function(){
		_generate_data_shu($('#thn').val(),$('#bln').val(),$('#j_graph').val());
	})
	$('#thn').change(function(){
		_generate_data_shu($(this).val(),$('#bln').val(),$('#j_graph').val());
	})
	$('#bln').change(function(){
		_generate_data_shu($('#thn').val(),$('#bln').val(),$('#j_graph').val());
	})
	$('#j_graph').change(function(){
		_generate_data_shu($('#thn').val(),$('#bln').val(),$('#j_graph').val());
	})
	
})

function _generate_data_shu(thn,bln,jenis){
	$('#newTable').show();
	show_indicator('newTable','6');
	$.post('graph_penjualan_data',{'thn':thn,'bln':bln},
		function(result){
			show_graph('chartdiv',jenis);	
		})
		
		/*show_graph('chartdiv');*/	
}

function _get_tahun(){
	$.post('get_tahun',{'id':''},
	function(result){
		$('#thn').html(result);
	})
}

function _get_bulan(){
	$.post('get_bulan',{'id':''},
	function(result){
		$('#bln').html(result);
	})
}

