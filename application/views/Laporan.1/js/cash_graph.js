// JavaScript Document
//tab button event
$(document).ready(function(e){
	$('#cashflow').removeClass('tab_button');
	$('#cashflow').addClass('tab_select');
	
	$('table#panel tr td:not(.flt,.plt)').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#pos').val(id);
				if(id=='labarugi'){
					var today=new Date()
					_get_bulan('_lb')
					_get_tahun('_lb')
					_generate_data_shu(today.getFullYear(),(today.getMonth()+1),$('#j_graph_lb').val());
				}else{
					var today=new Date()
					_get_bulan('')
					_get_tahun('')
					_generate_data_shu(today.getFullYear(),(today.getMonth()+1),$('#j_graph').val());
				}
	})
	$('#newTable').hide();
	var today=new Date()
	var pos=$('#pos').val()
	_get_bulan('')
	_get_tahun('')
/*	_generate_data_shu(today.getFullYear(),(today.getMonth()+1),$('#j_graph').val());
*/	
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
		//==proces grafik labarugi
	$('#okedech_lb').click(function(){
		_generate_data_shu($('#thn_lb').val(),$('#bln_lb').val(),$('#j_graph_lb').val());
	})
	$('#thn_lb').change(function(){
		_generate_data_shu($('#thn_lb').val(),$('#bln_lb').val(),$('#j_graph_lb').val());
	})
	$('#bln_lb').change(function(){
		_generate_data_shu($('#thn_lb').val(),$('#bln_lb').val(),$('#j_graph_lb').val());
	})
	$('#j_graph_lb').change(function(){
		_generate_data_shu($('#thn_lb').val(),$('#bln_lb').val(),$('#j_graph_lb').val());
	})
	
})

function _generate_data_shu(thn,bln,jenis){
	$('#newTable').show();
	show_indicator('newTable','6');
	$.post('graph_cash_data',{'thn':thn,'bln':bln,'pos':$('#pos').val()},
		function(result){
			show_graph('chartdiv_'+$('#pos').val(),jenis);	
		})
		
		/*show_graph('chartdiv');*/	
}

function _get_tahun(id){
	$.post('get_tahun',{'id':''},
	function(result){
		$('#thn'+id).html(result);
	})
}

function _get_bulan(id){
	$.post('get_bulan',{'id':''},
	function(result){
		$('#bln'+id).html(result);
		$('#okelah').click();
	})
}

