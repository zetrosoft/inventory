// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
		_generate_nomor('GR','#frm1 input#no_transaksi');
	//lock('#no_transaksi');
	tglNow('#tgl_transaksi');
	// Pengaturan tab panel yang aktif
	var prs=$('#prs').val();
	$('table#panel tr td.flt').hide()
    $('#returnpembelian').removeClass('tab_button');
	$('#returnpembelian').addClass('tab_select');
	
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			if(id!='' && id.substr(0,2)!='p-'){
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
			}
	});
	tabField(); //fungsi ganti ke focus ke field berikutnya jika tombol enter di tekan
	
	//pemasok auto suggest
	$('#nm_produsen')
		.coolautosuggest({
			url		:'get_pemasok?limit=10&str=',
			width	:350,
			showDescription	:true,
			onSelected		:function(result){
				$('#id_pemasoke').val(result.id_pemasok);
				$('#po_pembelian').focus().select();
				_show_list();
				$('#frm2').show();
			}
		})
		.focusout(function(){
				_show_list();
				$('#frm2').show();
				$('#po_pembelian').focus().select();
		})
		.keypress(function(e){
			if(e.which==13){
				$(this).focusout();
			}
		})
		
	//event untuk input textbox di table#listTable
	//kode barang
	$('#1__id_barang')
		.bind('change',function(){
			//if($(this).val().length==13){
				$('#1__jml_transaksi').focus().select();
			//}
		})
		.focusout(function(){
			if($(this).val().length!=0){
				$.post('get_material_kode',{
					'kode'	:$(this).val()
				},
				function(result){
					if(result){ //cek content output json agar tidak error di parse
						var rs=$.parseJSON(result);
						$('#1__nm_barang').val(rs.Nama_Barang)
						$.post('get_satuan_konversi',{
							'nm_barang'	:rs.Nama_Barang
						},
						function(data){
							$('#1__nm_satuan').html(data)
						})
						//$('#id_sat').val(rs.ID_Satuan)
						$('#1__nm_satuan').val(rs.ID_Satuan).select()
						$('#1__jml_transaksi').focus().select();
					}
				})
			}else{
				$('#1__nm_barang').focus().select();
			}
		})
	//autosuggest nama material
		$('#frm2 input#1__nm_barang')
			.coolautosuggest({
				url:path+'inventory/data_material?fld=Nama_Barang&key='+$('#id_pemasok').val()+'&limit=8&str=',
				width:350,
				showDescription	:true,
				onSelected:function(result)
				{
					$('#1__nm_barang').val(result.data)
					$('#1__id_barang').val(result.kode)
					$.post('get_satuan_konversi',{
						'nm_barang'	:result.data
					},
					function(data){
						$('#1__nm_satuan').html(data)
					})
					$('#id_sat').val(result.satuan)
					$('#1__nm_satuan').val(result.satuan).select()
					$('#1__jml_transaksi').focus().select();
				}
			})
			
	$('#1__jml_transaksi')
		.keyup(function(){
			terbilang(this)
			jumlah();
		})
		.focusout(function(){
			$('#terbilang').hide();
		})
	$('#1__harga_beli')
		.keyup(function(){
			terbilang(this)
			jumlah();
			$('#dt-1').show()
		})
		.focusout(function(){
			$('#terbilang').hide();
		})
	$('#1__nm_satuan').change(function(){
		$('#id_sat').val($(this).val());
	})
	$('img').click(function(){
		var id=$(this).attr('id');
		var cl=$(this).attr('class');
		switch(id){
			case 'del':
			
			break;	
		}
	})
})
/* jumlah total harga beli
*/
function jumlah(){
	var jml=$('#1__jml_transaksi').val()
	var hgb=$('#1__harga_beli').val()
	var jml_t=(parseFloat(jml)*parseFloat(hgb))
		
		$('#1__ket_transaksi').val(jml_t)
}
/*menampilkan terbilang ketika ditulis angka
 pada field jumlah dan harga beli
*/

function terbilang(field){
	$(field).terbilang({'output_div':'terbilang'})//menampikan data terbilang 
	pos_info(field,'terbilang');
}
/*
 get total belanja berdasarkan no transaksi
 dan tanggal
*/
function _get_total_belanja(){
	unlock('#no_transaksi,#tgl_transaksi');//remove attribute disabled
	$.post('get_total_belanja',{
		'no_trans'	:$('#no_transaksi').val(),
		'tanggal'	:$('#tgl_transaksi').val()},
		function(result){
			$('#total_beli').val(result);
		})
}
/*
simpan data header pembelian
  db 		: inv_pembelian
  controler	: pembelian/set_header_pembelian
*/
function _simpan_header_pembelian(){
	unlock('#no_transaksi,#tgl_transaksi');//remove attribute disabled
	$.post('set_header_pembelian',{
		'no_trans'	:$('#frm1 #no_transaksi').val(),
		'tanggal'	:$('#frm1 #tgl_transaksi').val(),
		'faktur'	:$('#frm1 #faktur_transaksi').val(),
		'pemasok'	:$('#frm1 #nm_produsen').val(),
		'cbayar'	:$('#frm1 #cara_bayar').val(),
		'id_pemasok':$('#id_pemasoke').val(),
		'total'		:$('#total_beli').val()
	},function(result){
		
	})
}
/*
 Simpan detail pembelian
 db			:inv_pembelian_detail
 controller	:pembelian/set_detail_pembelian
*/
function _simpan_detail_pembelian(){
	unlock('#no_transaksi,#tgl_transaksi');//remove attribute disabled
	$.post('set_detail_pembelian',{
		'no_trans'	:$('#frm1 #no_transaksi').val(),
		'tanggal'	:$('#frm1 #tgl_transaksi').val(),
		'kode'		:$('#frm2 #1__id_barang').val(),
		'nm_barang'	:$('#frm2 #1__nm_barang').val(),
		'id_satuan'	:$('#id_sat').val(),//$('#frm2 #1__nm_satuan').val(),
		'jumlah'	:$('#frm2 #1__jml_transaksi').val(),
		'harga_beli':$('#frm2 #1__harga_beli').val(),
		'keterangan':$('#frm2 #1__ket_transaksi').val()
	},function(result){
		$.post('get_detail_trans',{'id':$.trim(result)},
			function(data){
				var hsl=$.parseJSON(data);
				$('#id_brg').val(hsl.batch);
				_update_stock('del');
				_kosongkan_field();
			})
	})
}
/*
uddate stock material
*/
function _update_stock(jn){
	$.post('update_stock',{
		'nm_barang'	:$('#frm2 #1__nm_barang').val(),
		'id_satuan'	:$('#id_sat').val(),
		'jumlah'	:$('#frm2 #1__jml_transaksi').val(),
		'harga_beli':$('#frm2 #1__harga_beli').val(),
		'batch'		:$('#id_brg').val(),
		'aksi'		:jn
	},function(result){
				_kosongkan_field();
				_show_list();
	})
}

/*
image/button simpan dan delete di klick
*/
function image_click(id,cl){
	unlock('#no_transaksi');
	switch(cl){
		case 'simpan':
		var pmsk=$('#nm_produsen').val();
		if(pmsk==''){
			alert('Nama pemasok harus di isi')
		}else{
			_simpan_header_pembelian();
			_simpan_detail_pembelian();
		}
		//_update_stock('');
		//_kosongkan_field();
		
		break;
		case 'del':
		if(confirm("Yakin data ini akan di hapus?")){
			$.post('get_detail_trans',{'id':id},
			function(data){
				var hsl=$.parseJSON(data);
/*				$.post('get_satuan_konversi',{
						'nm_barang'	:hsl.Nama_Barang
					},
					function(datax){
						$('#frm2 #1__nm_satuan').html(datax)
					})
*/
				$('#frm2 #1__nm_satuan').val(hsl.ID).select()
				$('#frm2 #1__nm_barang').val(hsl.Nama_Barang)
				$('#frm2 #1__jml_transaksi').val((parseInt(hsl.Jumlah)))
				$('#frm2 #1__harga_beli').val(hsl.Harga_Beli)
				$('#id_brg').val(hsl.batch)
				$('#id_sat').val(hsl.ID_Satuan);
				
					_update_stock('');//update stock
					//hapus data
					$.post('hapus_transaksi',{
						'ID'	:id
						},function(result){
							//_kosongkan_field();
							_show_list()
						})/**/
			})
			
		}
		break;
	}
}
function _show_list(){
	unlock('#no_transaksi');
		$.post('show_list',{
			'no_transaksi'	: $('#no_transaksi').val(),
			'jtran'			:'GR',
			'tanggal'		:$('#tgl_transaksi').val()},			
				function(result){
					$('#frm2 table#ListTable tbody').html(result);
					//lock tanggal jika user bukan level adminstrator /superuser
					//lock('#no_transaksi,#tgl_transaksi');
					if($('#aktif_user').val()>2){
						lock('#tgl_transaksi');
					}else{
						unlock('#tgl_transaksi')
					}
					//_get_total_belanja();
				})
	
	
}
function _kosongkan_field(){
	$('#frm2 input:text').val('');
	$('#id_brg').val('');$('#id_sat').val('');
	$('#frm2 select#1__nm_satuan').html('');	
}
function _generate_nomor(tipe,field){
	$.post('nomor_transaksi',{'tipe':tipe},
	function(result){
		$(field).val(result);
		$('#trans_new').val('add');	
		//_show_list();
	})
}
