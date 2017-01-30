// JavaScript Document
$(document).ready(function(e) {
    var path=$('#path').val();
    $('#liststock').removeClass('tab_button');
	$('#liststock').addClass('tab_select');
/*	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			if(id!=''){
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
			}
	})
*/
	$('table#stoked').hide()
	$('#dari_tgl').dynDateTime();
	$('#okelah').click(function(){
		$('table#stoked').show()
			_show_data();
	})
	$('#Kategori').change(function(){
		$('#okelah').click();
	})
	$('#saved-edit_mat').click(function(){
		$.post('update_adjust',{
			'id'	:$('#id_barang').val(),
			'batch'	:$('#batch').val(),
			'stock'	:$('#stock').val(),
			'harga'	:$('#Harga_Beli').val(),
			'lokasi':$('#lokasi').val()
		},function(result){
			_show_data();
			$('#batal').click();
			$('#lokasi').val('');
			keluar_stocklist();	
		})
	})
	$('#frm1 #prt').click(function(){
		unlock('#frm1 #id_lokasi');
		$('#frm1').attr('action','print_stock');
		document.frm1.submit();
	})
	$('#id_lokasi').change(function(){
		_show_data();
	})
})

function _show_data(){
	show_indicator('stoked',11);
	$.post('get_stock',{
		'kategori'	:$('#Kategori').val(),
		'stat'		:$('#Stat').val(),
		'lokasi'	:$('#id_lokasi').val(),
		'orderby'	:$('#orderby').val(),
		'urutan'	:$('#urutan').val(),
		'edited'	:$('#edited').val(),
        'cari'      :$('#cari').val()},
	function(result){
		$('table#stoked tbody').html(result);
		$('table#stoked').fixedHeader({width:(screen.width-50),height:(screen.height-335)})
	})
}

function images_click(id,action){
	$('#pp-stocklist').css({'left':'20%','top':'20%'});
	lock('#frm2 :not(#stock,:button,:reset)')
	$.post('edit_stock',{'ID':id},
	function(result){
		var hsl=$.parseJSON(result);
		$('#ID_Kategori').val(hsl.Kategori);
		$('#Nama_Barang').val(hsl.Nama_Barang);
		$('#Kode').val(hsl.Kode);
		$('#stock').val(hsl.stock);
		$('#Harga_Beli').val(hsl.harga_beli);
		$('#Satuan').val(hsl.Satuan);
		$('#Status').val(hsl.Status);
		$('#stock').focus().select();
		$('#batch').val(hsl.batch);
		$('#id_barang').val(hsl.ID);
		$('#lokasi').val(hsl.id_lokasi);
		if($('#Harga_Beli').val()==''){
			unlock('#Harga_Beli');
			$('#Harga_Beli').val('0');
		}
	})
	$('#lock').show();
	$('#pp-stocklist').show('slow');
	
}