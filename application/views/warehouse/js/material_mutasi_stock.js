// JavaScript Document
var path=$('#path').val();
$(document).ready(function(e) {
    var path=$('#path').val();
    $('#mutasistock').removeClass('tab_button');
	$('#mutasistock').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			if(id!=''){
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				if(id=='listmutasi'){
					_show_trans();
				}
			}
	})
	tglNow('#dari_tanggal')
	tglNow('#tgl_trans');
	$('#tgl_trans').dynDateTime();
	$('#dari_tanggal').dynDateTime();
	$('#smp_tanggal').dynDateTime()
	//$('#no_trans').attr('readonly','readonly');
	$('#1__batch').attr('readonly','readonly');
	$('#1__stok').attr('readonly','readonly');
	_generate_nomor('GI','#no_trans');
	$('#dari_lokasi')
		.val($('#lok').val()).select();
	($('#jml_area').val()=='1')? lock('#dari_lokasi') :unlock('#dari_lokasi')
	$('#no_trans')
		.keypress(function(e){
			if(e.keyCode==13){
				$.post('get_header_mutasi',{
					'notrans'	:$(this).val(),
					'tanggal'	:$('#tgl_trans').val()
				},function(result){
					var hsl=$.parseJSON(result)
					$('#tgl_trans').val(tglFromSql(hsl.Tanggal));
					$('#dari_lokasi').val(hsl.ID_Lokasi_asal).select();
					$('#ke_lokasi').val(hsl.ID_Lokasi_kirim).select();
					$('#ket_trans').val(hsl.Keterangan);
					_show_list()
					$('#ket_trans').focus().select()
				})
			};
		})
		.focusout(function(){
			_show_list();
		})
	$('#frm2 img.edit').hide();
	//event untuk input textbox di table#listTable
	//kode barang
	$('#frm2 #1__id_barang')
		.bind('change',function(){
			//if($(this).val().length==13){
				$('#frm2 #1__jml_transaksi').focus().select();
			//}
		})
		.focusout(function(){
			if($(this).val().length!=0){
				$.post(path+'pembelian/get_material_kode',{
					'kode'	:$(this).val()
				},
				function(result){
					if(result){ //cek content output json agar tidak error di parsing
						var rs=$.parseJSON(result);
						$('#frm2 #1__nm_barang').val(rs.Nama_Barang)
						$.post(path+'pembelian/get_satuan_konversi',{
							'nm_barang'	:rs.Nama_Barang
						},
						function(data){
							$('#frm2 #1__nm_satuan').html(data)
						})
						$.post('get_stock',{'ID':rs.ID},
						function(rst){
							var hsl=$.parseJSON(rst);
							$('#frm2 #1__nm_satuan').val(rs.ID_Satuan).select()
							$('#frm2 #1__jml_transaksi').focus().select();
							$('#frm2 #1__batch').val(hsl.batch);
							$('#frm2 #1__stok').val(hsl.stock);
						})
					}
				})
			}else{
				$('#1__nm_barang').focus().select();
			}
		})
	//autosuggest nama material
		$('#frm2 input#1__nm_barang')
			.coolautosuggest({
				url:path+'inventory/data_material?fld=Nama_Barang&limit=8&str=',
				width:350,
				showDescription	:true,
				onSelected:function(result)
				{
					$('#frm2 #1__nm_barang').val(result.data)
					$('#frm2 #1__id_barang').val(result.kode)
					$.post(path+'pembelian/get_satuan_konversi',{
						'nm_barang'	:result.data
					},
					function(data){
						$('#frm2 #1__nm_satuan').html(data)
					})
						$.post('get_stock',{'ID':result.id_barang},
						function(rst){
							var hsl=$.parseJSON(rst);
							$('#frm2 #1__nm_satuan').val(result.ID_Satuan).select()
							$('#frm2 #1__jml_transaksi').focus().select();
							$('#frm2 #1__batch').val(hsl.batch);
							$('#frm2 #1__stok').val(hsl.stock);
						})
				}
			})
			
	$('#1__jml_transaksi')
		.keyup(function(){
			terbilang(this)
			//jumlah();
			if(parseInt(this.value) > parseInt($('#frm2 #1__stok').val())){
				alert('Jumlah yng diinput melebihi stock\nSisanya silahkan gunakan Batch lain')
				$(this).val($('#1__stok').val())
				$('#frm2 img.edit').hide();
			}else{
			$('#frm2 img.edit').show();
			}
		})
		.focusout(function(){
			$('#terbilang').hide();
		})
		.keypress(function(e){
			if(e.keyCode==13){
				 $(this).focusout();
				 $('#frm2 img.edit').trigger('click');
			}
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
	//proses mutasi
	$('#okelah').click(function(){
		_show_trans();
	})
})

function _generate_nomor(tipe,field){
	$.post(path+'pembelian/nomor_transaksi',{'tipe':tipe},
	function(result){
		$(field).val(result);
		$('#trans_new').val('add');	
		_show_list();
	})
}

function jumlah(){
	var jml=$('#1__jml_transaksi').val()
	var hgb=$('#1__harga_beli').val()
	var jml_t=(parseFloat(jml)*parseFloat(hgb))
		
		//$('#1__ket_transaksi').val(jml_t)
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
image/button simpan dan delete di klick
*/
function images_click(id,aksi){
	image_click(id,aksi);	
}
function image_click(id,cl){
	unlock('#no_transaksi,#dari_lokasi');
	switch(cl){
		case 'simpan':
		_simpan_header_pembelian();
		_simpan_detail_pembelian();
		//_update_stock('');
		//_kosongkan_field();
		//$('#1__nm_barang').focus().select();
		break;
		case 'del':
		jConfirm("Yakin data ini akan di hapus?",'Confirm',function(e){
		 if(e){
			 $.post('del_mutasi',{
				'ID':id},
				function(result){
					//_update_stock('2');
					_show_list();
					_show_trans();
				})
		 }
		})
		break;
		case 'pros':
		var idd=id.split('-');
		jConfirm("Yakin data ini akan dikirim sekarang??","Confirm",function(e){
			if(e){
				print_mutasi(idd[0],idd[1]);
				_show_trans();
			}
		})
	}
}
function _show_trans(){
	show_indicator('newTable',7)
	$.post('lap_mutasi',{
		'dari'		:$('#dari_tanggal').val(),
		'tanggal'	:$('#smp_tanggal').val(),
		'status' 	:$('#statuse').val()
	},function(result){
		$('table#newTable tbody').html(result)
		$('table#newTable').fixedHeader({width:(screen.width-30),height:(screen.height-335)});
	})
}
function _show_list(){
	unlock('#no_transaksi');
		$.post('get_mutasi',{
			'no_transaksi'	: $('#no_trans').val(),
			'jtran'			:'GR',
			'tanggal'		:$('#tgl_trans').val()},			
				function(result){
					$('#frm2 table#ListTable tbody').html(result);
					//lock tanggal jika user bukan level adminstrator /superuser
					//lock('#no_transaksi,#tgl_transaksi');
					if($('#aktif_user').val()>2){
						lock('#tgl_trans');
					}else{
						unlock('#tgl_trans')
					}
					//_get_total_belanja();
				})
	
	
}
function _kosongkan_field(){
	$('#frm2 input:text').val('');
	$('#id_brg').val('');$('#id_sat').val('');
	$('#frm2 select#1__nm_satuan').html('');	
}
function _simpan_header_pembelian(){
	//simpan mutasi ke table inv_mutasi_stock
	if($('#frm1 #ke_lokasi').val()!=''){
		$.post('set_mutasi',{
			'no_trans'	:$('#frm1 #no_trans').val(),
			'tanggal'	:$('#frm1 #tgl_trans').val(),
			'asal'		:$('#frm1 #dari_lokasi').val(),
			'tujuan'	:$('#frm1 #ke_lokasi').val(),
			'kode'		:$('#frm2 #1__id_barang').val(),
			'nm_barang'	:$('#frm2 #1__nm_barang').val(),
			'id_satuan'	:$('#frm2 #1__nm_satuan').val(),
			'jumlah'	:$('#frm2 #1__jml_transaksi').val(),
			'batch'		:$("#frm2 #1__batch").val(),
			'keterangan':$('#frm1 #ket_trans').val()
		},function(result){
			_update_stock('');
			_show_list();
			_kosongkan_field();
		})
	}else{
		alert('Tujuan kirim belum di pilih');
		return false
	}
}
/*
uddate stock material
*/
function _update_stock(jn){
	$.post('update_stock',{
		'nm_barang'	:$('#frm2 #1__nm_barang').val(),
		'id_satuan'	:(jn=='del')?$('#id_sat').val():$('#frm2 #1__nm_satuan').val(),
		'jumlah'	:$('#frm2 #1__jml_transaksi').val(),
		'harga_beli':$('#frm2 #1__harga_beli').val(),
		'batch'		:$('#1__batch').val(),
		'aksi'		:jn
	},function(result){
		//alert(result);
		//_show_list();
	})
}

function _simpan_detail_pembelian(){
	
}

