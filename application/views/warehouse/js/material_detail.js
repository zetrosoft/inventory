// JavaScript Document
var path=$('#path').val();
$(document).ready(function(e) {
    	$('#frm2 input#nm_barang')
		.keyup(function(){
			$('input:text(:not(#nm_barang)').val('')
		})
		.coolautosuggest({
				url:path+'inventory/data_material?fld=Nama_Barang&limit=20&dest=detail&str=',
				width:370,
				showDescription	:true,
				onSelected:function(rst){
					$('#id_barang').val(rst.id_barang)
					$.post(path+'inventory/detail_material',{'ID':rst.data},
						function(r){
							var result=$.parseJSON(r);
							$('#frm2 #sn').val(result.sn)
							$('#frm2 #hpp').val(to_number(result.hpp))
							$('#frm2 #harga_toko').val(to_number(result.harga_toko));
							$('#frm2 #harga_partai').val(to_number(result.harga_partai))
							$('#frm2 #harga_cabang').val(to_number(result.harga_cabang));
							$('#frm2 #mata_uang').val(result.mata_uang).select();
							$('#frm2 #garansi').val(result.garansi);
							$('#frm2 #panjang').val(result.ukuran);
							$('#frm2 #warna').val(result.warna);
							$('#frm2 #berat').val(result.berat);
						})
					$('#sn').focus().select();
				}
		})
		$('#frm2 #hpp')
			.focus(function(){$(this).select();kekata(this)})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();})
			.keypress(function(e){if(e.which==13){ $(this).focusout();$('#harga_toko').focus().select()}})
		$('#frm2 #harga_toko')
			.focus(function(){$(this).select();kekata(this)})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();})
			.keypress(function(e){if(e.which==13){ $(this).focusout();$('#harga_partai').focus().select()}})
		$('#frm2 #harga_partai')
			.focus(function(){$(this).select();kekata(this)})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();})
			.keypress(function(e){if(e.which==13){$('#harga_cabang').focus().select(); $(this).focusout();}})
		$('#frm2 #harga_cabang')
			.focus(function(){$(this).select();kekata(this)})
			.keyup(function(){kekata(this);})
			.focusout(function(){kekata_hide();})
			.keypress(function(e){if(e.which==13){$('#frm2 #garansi').focus().select(); $(this).focusout();}})
		//simpan data
		$('#saved-detail').click(function(){
			simpan_detail();
		})
});

function simpan_detail(){
	$.post(path+'inventory/simpan_barang_detail',{
		'ID'	:$('#id_barang').val(),
		'nama'	:$('#frm2 #nm_barang').val(),
		'sn'	:$('#frm2 #sn').val(),
		'hpp'	:$('#frm2 #hpp').val(),
		'htk'	:$('#frm2 #harga_toko').val(),
		'htp'	:$('#frm2 #harga_partai').val(),
		'htc'	:$('#frm2 #harga_cabang').val(),
		'idr'	:$('#frm2 #mata_uang').val(),
		'garansi':$('#frm2 #garansi').val(),
		'ukuran':$('#frm2 #panjang').val(),
		'warna'	:$('#frm2 #warna').val(),
		'berat'	:$('#frm2 #berat').val()
		
	},function(result){
		$('div#result').html(result)
		$('div#result').fadeOut(3000);
		$(':reset').click();
	})
		
}



