// JavaScript Document

$(document).ready(function(e) {
	var path=$('#path').val();
    $('#lokasigudang').removeClass('tab_button');
	$('#lokasigudang').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			$('#'+id).removeClass('tab_button');
			$('#'+id).addClass('tab_select');
			$('table#panel tr td:not(#'+id+')').removeClass('tab_select');
			$('table#panel tr td:not(#'+id+',#kosong)').addClass('tab_button');
			$('span#v_'+id).show();
			$('span:not(#v_'+id+')').hide();
			$('#prs').val(id);
			if(id=='datakas'){
				$('#frm3 #lokasi').focus().select();
			}

	})
	//alert($('#otox').val().length);
	if($('#otox').val().length>0)
	{
		unlock('#saved-lokasi');
	}else{
		lock('#saved-lokasi');
	}
	_show_data();
	$('#saved-lokasi').click(function(){
		($('#lokasi').val().length==0)?
		alert('Nama Lokasi tidak boleh kosong'):
		_simpan_data();
	})

})
function _simpan_data(){
	$.post('set_lokasi',{
		'id'	:$('#id_lokasi').val(),
		'lokasi':$('#lokasi').val(),
		'alamat':$('#alamat').val(),
		'stat'	:$('#status').val()
	},function(result){
		///_show_data();
		$('input :not(:button)').val('');
		$(':reset').click();
		$('#lok_server').val($.trim(result));
		document.location.reload();
		
	})
}
function _show_data(){
	show_indicator('ListTable',5);
	$.post('list_lokasi',{'id':''},
		function(result){
			cek_lok_server();
			cekAsServer();
			$('table#ListTable tbody').html(result);
			$('table#ListTable').fixedHeader({'width':(screen.width-300),'height':270});
		})
}

function images_click(id,aksi){
	switch(aksi){
		case 'edit':
			$.post('get_lokasi',{'id':id},
			function(result){
				var hsl=$.parseJSON(result);
				$('#lokasi').val(hsl.lokasi);
				$('#alamat').val(hsl.alamat);
				$('#id_lokasi').val(hsl.ID);
				$('#status').val(hsl.status).select();
				$('#status').removeAttr('disabled')
			})
			
		break;
		case 'del':
			$.post('del_lokasi',{'id':id},
			function(result){
				_show_data();
			})
		break;	
	}
}
function cek_lok_server(){
	$.post('get_as_server',{'id':''},
	function(result){
		$('#lok_server').val($.trim(result));
	})
}
function cekAsServer(){
	if($('#lok_server').val().length==0){
		$('#status').removeAttr('disabled');
	}else{
		$('#status').attr('disabled','disabled');
	}
}

