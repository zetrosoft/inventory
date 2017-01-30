$(document).ready(function(e) {
	var path=$('#path').val();
    $('#listvendor').removeClass('tab_button');
	$('#listvendor').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			$('#'+id).removeClass('tab_button');
			$('#'+id).addClass('tab_select');
			$('table#panel tr td:not(#'+id+')').removeClass('tab_select');
			$('table#panel tr td:not(#'+id+',#kosong)').addClass('tab_button');
			$('span#v_'+id).show();
			$('span:not(#v_'+id+')').hide();
	})
	$('#ID').attr('readonly','readonly');
	_generate_id();
	_show_data();
	
	$('#saved-vendor').click(function(){
		_simpan_data();
	})
	
	$('#oke').click(function(){
		
		($('#finde').val().length > 0)? _show_data():''
	})
	$(':reset').click(function(){
	 _generate_id();
	})
})

function _generate_id(){
	$.post('get_next_id',{'id':''},
	function(result){
		$('#ID').val(result);
		$('table#xx tbody').html('');		
	})
}

function _show_data(){
	show_indicator('ListTable',9);
	$.post('list_vendor',{
		'nama'	:$('#finde').val()
	},function(result){
		$('table#ListTable tbody').html(result);
		$('table#ListTable').fixedHeader({width:(screen.width-30),height:(screen.height-328)})
	})
}

function _simpan_data(){
	show_indicator('xx',1);
	$.post('set_data_vendor',{
		'ID'		:$('#ID').val(),
		'pemasok'	:$('#Pemasok').val(),
		'alamat'	:$('#Alamat').val(),
		'kota'		:$('#Kota').val(),
		'propinsi'	:$('#Propinsi').val(),
		'faksimili'	:$('#Faksimili').val(),
		'telepon'	:$('#Telepon').val()
	},function(result){
		$(':reset').click();
		_generate_id
		_show_data
	})
}

function images_click(id,aksi){
 var idd=id.split(':');
 switch(aksi)
 {
	 case 'edit':
	 _show_detail(idd[0],idd[1])
	 break;
	 case 'del':
		jConfirm('Yakin data ini akan dihapus?','Alert',function(){
			$.post('hapus_vendor',{'ID':idd[0]},
			function(result){
				_show_data();
			})
		})
		break;
 }
}

function _show_detail(id,nama){
	show_indicator('vend_detail',7);
	$('#pp-v_detail').css({'left':'15%','top':'10%'})
	$('#pp-v_detail').show();
	$.post('vendor_detailed',{'id':id},
	function(result){
		$('span#nmp').html('  '+nama);
		$('table#vend_detail tbody').html(result)
		$('#lock').show();
		$('#pp-v_detail').show();
		$('table#vend_detail').fixedHeader({width:730,height:470})
	})
}