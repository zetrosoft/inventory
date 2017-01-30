// JavaScript Document
var path=$('#path').val();
$(document).ready(function(e) {
$('#aktif').val('');
$('#total').val('0');
	//event untuk input textbox di grid table#listTable
	$('#frm2 #1__nm_barang').focus(function(){_simpan_header();	});

		$('#frm2 input:text')
				.focus(function(){
					var id=$(this).attr('id').split('__');
					$('#aktif').val(id[0]);
					if(parseInt(id[0])>1){
						unlock('#simpanbayar');
						if($('#frm2 #'+(parseInt(id[0])-1)+'__nm_barang').val()!=''){
							_simpan_detail((parseInt($('#aktif').val())-1));
						}
					
					};
					var a=$('#jml_baris').val();
					$('#jml_baris').val((a > id[0])?a:id[0]);
				//autosuggest nama barang
					$('#frm2 input#'+id[0]+'__nm_barang')
							.coolautosuggest({
								url:path+'inventory/data_material?fld=Nama_Barang&limit=5&str=',
								width:350,
								showDescription	:true,
								onSelected:function(result)
								{
									if(result){
										$('#frm2 input#'+id[0]+'__nm_satuan').val(result.nm_satuan)
										$('#frm2 input#'+id[0]+'__harga_jual').val(result.hargajual)
										$('#'+id[0]+'__harga_total').val(result.hargajual);
										total_harga();
										$('#max_input').val('0')
										//dapatkan total stock
									   if(result.kategori!='106' || result.status!='JASA'){
										$.post(path+'stock/get_material_stock',{
											'id_material'	:result.id_barang,
											'lokasi'		:$('#lokasi').val()},
											function(dat){
												var data=$.parseJSON(dat);
												if($.trim(data.stock)=='0'||
													data.stock==null  ){
													jAlert('Stock '+result.data+' kosong (nol). tidak bisa dilakukan trasaksi\nSilahkan update dulu stocknya','Alert Stock Null');
												}else{
												$('#frm2 input#'+id[0]+'__jml_transaksi')
													.val('1')
													.focus().select();
												 $('#max_input').val(data.stock);
												 //cari batch material
												}
										$.post(path+'stock/get_bacth',{
											'id_barang':result.id_barang,
											'lokasi'   :$('#lokasi').val()},
											function(res){
											//alert(res);
											var bt=$.parseJSON(res)
											$('#'+id[0]+'__expired').val(bt.batch);
											})
											});
									   }else{
										$('#frm2 input#'+id[0]+'__jml_transaksi')
											.val('1')
											.focus().select()	
										$('#max_input').val('10')								   }//end if jenis
									};//end if result
								}//end if autosuggest
							});
					//jml_transaksi otomatis adalah satu
					//jika di tulis akan merubah subtotal
					$('#'+id[0]+'__jml_transaksi')
						.keyup(function(){
							var hgj=$('#frm2 input#'+id[0]+'__harga_jual').val();
							var jml=$(this).val();
							var tth=(parseFloat(jml)*parseFloat(hgj))
							$('#'+id[0]+'__harga_total').val(tth)
							total_harga();
						})
						.keypress(function(e){
							if(e.which==13){
							$('#frm2 input#'+id[0]+'__harga_jual').focus().select()	
							}
						})
						.focus(function(){
							$('#aktif').val(id[0]);
						})
						.focusout(function(){
							
							//jika jumlah input lebih besar dari stock
							if($(this).val() > $('#max_input').val()){
								jAlert('Stock Tidak mencukupi','Notification',function(e){;
								$('#'+id[0]+'__jml_transaksi').focus().select();
								})
							}
						});
					//harga jual sesuai dengan data dalam databas
					//jika diinput manual akan membuat subtotal	
					$('#frm2 input#'+id[0]+'__harga_jual')
						.keyup(function(){
							var hgj=$('#frm2 input#'+id[0]+'__jml_transaksi').val();
							var jml=$(this).val();
							var tth=(parseFloat(jml)*parseFloat(hgj))
							$('#'+id[0]+'__harga_total').val(tth)
							total_harga();
						})
						.keypress(function(e){
							//jika di tekan enter akan ke field nama barang berikutnya
							//kecuali jika sudah di akhir baris akan focus ke tombol bayar tunai
							var next_fld=parseInt(id[0])+1;
							if(e.which==13){
							(next_fld < 8)?
								$('#frm2 input#'+next_fld+'__nm_barang').focus().select():
								$('#bayar').focus();	
							}
						})
						.focusout(function(){
						})
						.focus(function(){
							$('#aktif').val(id[0]);
						});
		});
		$('#simpanbayar').click(function()
		{
			_simpan_bayar();
		})
	$('#pp-bayar img#bayar').click(function()
	{
		document.frm2.reset();
	})
	$('#frm2 #batal').click(function(){
		//alert('reset')
	});
});

function _simpan_bayar()
{
	$('#resultant')
	 .html("Data being process, please wait ...<img src='"+path.replace('index.php/','')+"asset/img/indicator.gif'>")
	 .show();
	 //.fadeOut(15000);	
	 _simpan_pembayaran();	
	
};

function total_harga()
{
		$('#total').val($('input.subtt').sumValues() );
};

//pembayaran
	function _simpan_header(){
		unlock('#frm2 #no_trans,#tanggal');
		$.post('set_header_trans',{
			'no_trans'		:$('#frm2 #no_trans').val(),
			'tanggal'		:$('#tanggal').val(),
			'faktur'		:'',
			'member'		:$('#id_member').val(),
			'total'			:$('#total').val(),
			'cicilan'		:'0',
			'cbayar'		:'1',
			'lokasi'		:$('#lokasi').val()			
		},
		function(result){
			
		})
	};

	function _simpan_detail(id){
		unlock('#frm2 #no_trans,#tanggal')
		$.post('set_detail_trans',{
			'no_trans'		:$('#frm2 #no_trans').val(),
			'tanggal'		:$('#tanggal').val(),
			'nm_barang' 	:$('#'+id+'__nm_barang').val(),
			'nm_satuan' 	:$('#'+id+'__nm_satuan').val(),
			'jml_trans' 	:$('#'+id+'__jml_transaksi').val(),
			'harga_jual'	:$('#'+id+'__harga_jual').val(),
			'ket_trans'		:$('#'+id+'__harga_total').val(),
			'expired'		:$('#'+id+'__expired').val(),
			'no_id'			:id,
			'batch'			:$('#'+id+'__expired').val(),
			'id_post'		:($('#ppne').is(':checked'))?'1':'0'
		},function(result){
			//lock('#no_transaksi,#tanggal')
			//if($('#aktif_user').val()<=2) unlock('#tgl_transaksi');
			//lakukan update stock pada barang yang dimaksud
			//_update_stock($('#no_transaksi').val(),$('#tgl_transaksi').val());//print struk pembayaran
			
		})
	}

	function _simpan_pembayaran(){
		unlock('#frm2 #no_trans,#ppn,#tanggal');
		$.post('simpan_bayar',{
			'no_transaksi'	:$('#frm2 #no_trans').val(),
			'total_belanja'	:$('#total').val(),
			'ppn'			:'0',
			'total_bayar'	:$('#total').val(),
			'dibayar'		:$('#total').val(),
			'kembalian'		:'0',
			'terbilang'		:'',
			'cbayar'		:'1',
			'tanggal'		:$('#tanggal').val()
		},function(result){
			_update_stock($('#frm2 #no_trans').val(),$('#tanggal').val());//print struk pembayaran
			$.post('update_status_service',{
				'no_trans'	:$('#frm2 #no_trans').val()
			},function(result){
				keluar_bayar();
			})
		})
	};

	function _update_stock(id,tgl,jml){
		$.post('update_material_stock',{
			'no_trans'	:id,
			'tanggal'	:tgl,
			'jumlah'	:jml,
			'lokasi'	:$('#lokasi').val()
		},function(result){
			if(parseInt(result)<0){
				_update_stock($('#frm2 #no_trans').val(),$('#tanggal').val(),result);
			}else{
				_print_struk(id,tgl);//print struk pembayaran
			}
		})
	};
	function _print_struk(id,tgl){
		var path=$('#path').val();
		$.post('print_slip',{
			'no_transaksi':id,
			'tanggal'	  :tgl,
			'lokasi'	  :$('#id_lok').val()},
			function(result){
				//buka_wind($.trim(result));
				jConfirm('Print Slip Service','Alert',function(r){
					if(r)
					{
						_re_print_slip()
						//document.location.href=path+'penjualan/service';
					}else{
						document.location.href=path+'penjualan/service';
					}
				})
			})
	};

function _re_print_slip()
{
			$.post('re_print',{'id':id},
			function(result){
				$('#result').show().html(result).fadeOut(10000);
				document.location.href=path+'penjualan/service';
			})
}