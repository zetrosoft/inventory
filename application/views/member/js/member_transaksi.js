// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
	var prs=$('#prs').val();
		$('#listtransaksi').removeClass('tab_button');
		$('#listtransaksi').addClass('tab_select');
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
	_show_data('all','0');
	$('#ndept').html("<b>Semua</b>");
	$('#nstat').html("<b>Belum Terjurnal</b>");
	$('#stat').change(function(){
		var dept =$('#dept').val();
		var stat =$('#stat').val();
		$('#ndept').html("<b>"+$('#dept option:selected').text()+"</b>")
		$('#nstat').html("<b>"+$('#stat option:selected').text()+"</b>")
		_show_data(dept,stat)	
	})
	$('#dept').change(function(){
		var dept =$('#dept').val();
		var stat =$('#stat').val();
		$('#ndept').html("<b>"+$('#dept option:selected').text()+"</b>")
		$('#nstat').html("<b>"+$('#stat option:selected').text()+"</b>")
		_show_data(dept,stat)	
	})
})

function _show_data(dept,stat){
	$.post('get_transaksi',{'ID_Dept':dept,'ID_Aktif':stat},
	function(result){
		$('table#ListTable tbody').html(result);
		$('#ListTable').fixedHeader({width:(screen.width-30),height:(screen.height-320)})
	})
}

function hapus(id){
	if(confirm("Yakin transaksi ini akan di hapus?")){
		$.post('hapus_transaksi',{'ID':id},
		function(result){
			var dept =$('#dept').val();
			var stat =$('#stat').val();
			_show_data(dept,stat)	
		})
	}
	
}
	
	