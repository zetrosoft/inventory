// JavaScript Document
//tab button event
$(document).ready(function(e){
	$('#grafikshu').removeClass('tab_button');
	$('#grafikshu').addClass('tab_select');
	
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
	_generate_data_shu();
})

function _generate_data_shu(){
	$('#newTable').show();
	show_indicator('newTable','6');
	$.post('graph_shu_data',{'thn':''},
		function(result){
			show_graph('chartdiv');	
		})
		
		/*show_graph('chartdiv');*/	
}

