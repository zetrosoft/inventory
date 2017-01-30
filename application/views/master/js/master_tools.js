// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
	var prs=$('#prs').val();
		$('#settingneraca').removeClass('tab_button');
		$('#settingneraca').addClass('tab_select');
	$('table#panel tr td:not(.flt,.plt,#kosong)').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				//$('#prs').val(id);
	})
	$('#SubTable').hide()
	$('#SubSHU').hide()
	$('#ListTable').fixedHeader({width:(screen.width-350),height:200/*(screen.height-320)*/})
	$('#HeadSHU').fixedHeader({width:(screen.width-350),height:140/*(screen.height-320)*/})
})

function show_detail(id){
	$('#ListTable_body_container #ListTable tbody tr#c-'+id).addClass('list_genap');
	$('#ListTable_body_container #ListTable tbody tr:not(#c-'+id+')').removeClass('list_genap');
	$.post('get_subneraca',{'ID':id},
		function(result){
			$('#SubTable tbody').html(result);
			$('#SubTable').fixedHeader({width:(screen.width-350),height:(screen.height-500)})
			$('#SubTable').show()
		})
}
function show_detail_shu(id){
	$('#HeadSHU_body_container #HeadSHU tbody tr#c-'+id).addClass('list_genap');
	$('#HeadSHU_body_container #HeadSHU tbody tr:not(#c-'+id+')').removeClass('list_genap');
	$.post('get_subneraca',{'ID':id},
		function(result){
			$('#SubSHU tbody').html(result);
			$('#SubSHU').fixedHeader({width:(screen.width-350),height:(screen.height-450)})
			$('#SubSHU').show()
		})
}