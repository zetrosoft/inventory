// JavaScript Document
$(document).ready(function(e) {
    var path=$('#path').val();
    $('#tambahbarang').removeClass('tab_button');
	$('#tambahbarang').addClass('tab_select');
	$('table#panel tr td').click(function(){
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
		$('#frm1 input#nm_barang')
		.coolautosuggest({
				url:path+'inventory/data_material?fld=Nama_Barang&limit=20&str=',
				width:370,
				showDescription	:true,
				onSelected:function(result){
					$('#frm1 #nm_jenis').val(result.nm_jenis)
					$('#frm1 #nm_kategori').val(result.nm_kategori)
					$('#frm1 #id_barang').val(result.kode);
					$('#frm1 #nm_satuan').val(result.nm_satuan)
					$('#frm1 #status_barang').val(result.status);
					$('#id_kategori').val(result.jenis);
					$('#id_jenis').val(result.kategori);
					$('#id_satuan').val(result.satuan);
					$('#stokmin').val(result.hpp);
					$('#harga_1').val(result.h_jualToko)
					$('#harga_2').val(result.h_jualToko)
					$('#harga_3').val(result.h_jualToko);
					$('#stoklimit').val(result.stoklimit);
				}
		})
		$('#frm1 input#id_barang')
		.coolautosuggest({
				url:path+'inventory/data_material?fld=Kode&limit=20&str=',
				width:250,
				showDescription	:true,
				onSelected:function(result){
					$('#frm1 #nm_jenis').val(result.nm_jenis)
					$('#frm1 #nm_kategori').val(result.nm_kategori)
					$('#frm1 #id_barang').val(result.kode);
					$('#frm1 #nm_satuan').val(result.nm_satuan)
					$('#frm1 #status_barang').val(result.status);
					$('#id_kategori').val(result.jenis);
					$('#id_jenis').val(result.kategori);
					$('#id_satuan').val(result.satuan);
					
				}
		})
		
		//auotsuggest kategori
		$('#frm1 #nm_kategori')
		  .coolautosuggest({
				url	:'get_kategori?limit=8&str=',
				width:250,
				showDescription:false,
				onSelected:function(result){
					$('#id_kategori').val(result.ID)
				}
			})
		$('#frm1 #nm_jenis')
			.coolautosuggest({
				url	:'get_jenis?limit=8&str=',
				width:250,
				showDescription:false,
				onSelected:function(result){
					$('#id_jenis').val(result.ID)
				}
			})
		$('#frm1 #nm_satuan')
			.coolautosuggest({
				url	:'get_satuan?limit=8&str=',
				width:250,
				showDescription:false,
				onSelected:function(result){
				$('#id_satuan').val(result.ID)	
				}
			})
		$('#frm1 #stokmin')
			.focus(function(){$(this).select()})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();$('#stokmax').focus().select()})
			.keypress(function(e){if(e.which==13){ $(this).focusout();}})
		
		$('#frm1 #harga_1')
			.focus(function(){$(this).select()})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();$('#harga_2').focus().select()})
			.keypress(function(e){if(e.which==13){$(this).focusout();}})
		$('#frm1 #harga_2')
			.focus(function(){$(this).select()})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();$('#harga_3').focus().select()})
			.keypress(function(e){if(e.which==13){$(this).focusout();}})
		$('#frm1 #harga_3')
			.focus(function(){$(this).select()})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();$('#stoklimit').focus().select()})
			.keypress(function(e){if(e.which==13){$(this).focusout();}})
		
		$('#frm1 #stoklimit')
			.focusout(function(){$(':button').focus()})
			.keypress(function(e){if(e.which==13){$(this).focusout();}})
			
		$('#saved-add').click(function(){
			_simpan_data()
		})
		
});

function _simpan_data(){
	var path=$("#path").val()
	$.post(path+'inventory/simpan_barang',{
		'id_jenis'		 :$('input#id_jenis').val(),
		'id_kategori'	  :$('input#id_kategori').val(),
		'id_barang'		:$('#frm1 input#id_barang').val(),
		'nm_barang'		:$('#frm1 input#nm_barang').val(),
		'status_barang'	:$('#frm1 input#status_barang').val(),
		'id_barcode'	:$('#frm1 input#id_barcode').val(),
	    'id_satuan'		:$('input#id_satuan').val(),
		'expired'		  :$('#frm1 input#expired').val(),
		'stokmin'		  :$('#frm1 input#stokmin').val(),
		'stokmax'		  :$('#frm1 input#stokmax').val(),
		'harga_1'		  :$('#frm1 input#harga_1').val(),
		'harga_2'		  :$('#frm1 input#harga_2').val(),
		'harga_3'		  :$('#frm1 input#harga_3').val(),
		'minstok'		  :$('#frm1 input#stoklimit').val(),
		'nm_jenis'		 :$('#frm1 input#nm_jenis').val(),
		'nm_kategori'	  :$('#frm1 input#nm_kategori').val(),
	    'nm_satuan'		:$('#frm1 input#nm_satuan').val()
		},
		function(result){
			$(':reset').click()
			$('#result').html('Data berhasil di simpan , Total data : '+result)
			$('#result').fadeOut(3000);
		})
}