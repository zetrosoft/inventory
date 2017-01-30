// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
	//$('#frm2').hide();
	$('#dt-1').hide();
	if($('#trans_new').val()==''){
		_generate_nomor('GR','#frm1 input#no_transaksi');
	}
	//lock('#no_transaksi');
	$('#no_transaksi').focusout(function(){
		_show_list();
	})
	//lock tanggal jika user bukan level adminstrator /superuser
	if($('#aktif_user').val()>2){
		lock('#tgl_transaksi');
	}else{
		unlock('#tgl_transaksi')
	}
	tglNow('#tgl_transaksi');
	$('#cara_bayar').val('1').select();
	// Pengaturan tab panel yang aktif
	var prs=$('#prs').val();
	$('table#panel tr td.flt').hide()
    $('#inputpembelian').removeClass('tab_button');
	$('#inputpembelian').addClass('tab_select');
	$('table#panel tr td.plt').hide();
	$('table#panel tr td#p-0').addClass('bg_print');
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
			if(id=='inputpembelian')
			{
			//alert(id);
				//document.location.reload();
				_generate_nomor('GR','#frm1 input#no_transaksi');
			}
	});
	//tabField(); //fungsi ganti ke focus ke field berikutnya jika tombol enter di tekan
	$('#jtempo').attr('disabled','true').val('0');
	//show data pembelian dengan no_transaksi yang tampil
	$('#cara_bayar').change(function(){
        if($(this).val()=='2'){
            $('#jtempo').removeAttr('disabled').focus().select();
        }else{
            $('#jtempo').attr('disabled','true');
        }
    })
    $('#cara_bayar').removeAttr('disabled');
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
	/*$('#1__id_barang')
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
						$('#1__nm_satuan').val(rs.satuan).select()
						$('#1__harga_beli').val(result.hpp);
						$('#1__jml_transaksi').focus().select();
					}
				})
			}else{
				$('#1__nm_barang').focus().select();
			}
		})*/
	$('#frm2 #1__nm_barang')
        .keypress(function(e){
        //alert(e.which);    
        if(e.which==13){getBarang('1',path);$('#frm2 #'+$('#idaktif').val()).focus().select();} })
			  .live('focus',function(){ })
    $('#frm2 input#1__jml_transaksi')
			  .live("keydown",function(){getHarga('1'); })	
              .keypress(function(e){if(e.which==13){$('#frm2 #1__harga_jual').focus().select();}})
    $('#frm2 input#1__harga_jual')
			  .keyup(function(e){getHarga('1');})
			  //.keypress(function(e){nextFld('1',e);})
			  //.keypress(function(e){if(e.which==13){_simpan_detail('1');_non_aktifkan('1',true);nextFld('1',e)}})
          
			
	$('#1__jml_transaksi')
		.keyup(function(){
			terbilang(this)
			//jumlah();
		})
		.focusout(function(){
			$('#terbilang').hide();
		})
	$('#1__harga_beli')
		.keyup(function(){
			terbilang(this)
			//jumlah();
			$('#dt-1').show()
		})
		.focusout(function(){
			$('#terbilang').hide();
		})
	$('img').click(function(){
		var id=$(this).attr('id');
		var cl=$(this).attr('class');
		switch(id){
			case 'del':
			
			break;	
		}
	})
   /* */
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
		'total'		:$('#total_beli').val(),
        'jtempo'    :($('#frm1 #jtempo').val()=='')?'0':$('#frm1 #jtempo').val()
	},function(result){
		_simpan_detail_pembelian();
        $('#frm1 #cara_bayar').attr('disabled','true');
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
		'id_satuan'	:$('#frm2 #1__nm_satuan').val(),
		'jumlah'	:$('#frm2 #1__jml_transaksi').val(),
		'harga_beli':$('#frm2 #1__harga_jual').val(),
		'keterangan':$('#frm2 #1__harga_total').val(),
        'jtempo'    :($('#frm1 #jtempo').val()=='')?'0':$('#frm1 #jtempo').val(),
        'lokasi'    :$('#frm2 #1__lokasi').val()
	},function(result){
		$.post('get_detail_trans',{'id':$.trim(result)},
			function(data){
				var hsl=$.parseJSON(data);
				$('#id_brg').val(hsl.batch);
				if($('#frm2 #1__lokasi').val()=="1")
				{
					_update_stock('',hsl.IID_Barang);
				}
				
				_kosongkan_field();
				
			})
	})
}
/*
uddate stock material
*/
function _update_stock(jn,id_barang){
	$.post('update_stock',{
        'id_barang' :id_barang,
		'nm_barang'	:$('#frm2 #1__nm_barang').val(),
		'id_satuan'	:(jn=='del')?$('#id_sat').val():$('#frm2 #1__nm_satuan').val(),
		'jumlah'	:$('#frm2 #1__jml_transaksi').val(),
		'harga_beli':$('#frm2 #1__harga_jual').val(),
		'batch'		:$('#no_transaksi').val(),
		'aksi'		:jn
	},function(result){
		//alert(result);
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
        if(parseFloat($('#frm2 #1__jml_transaksi').val())>0)
        {
		_simpan_header_pembelian();
		//_simpan_detail_pembelian();
		//_update_stock('');
		//_kosongkan_field();
        }
		break;
		case 'del':
		if(confirm("Yakin data ini akan di hapus?")){
			$.post('get_detail_trans',{'id':id},
			function(data){
				var hsl=$.parseJSON(data);
				$.post('get_satuan_konversi',{
						'nm_barang'	:hsl.Nama_Barang
					},
					function(datax){
						$('#frm2 #1__nm_satuan').html(datax)
						$('#frm2 #1__nm_satuan').val(hsl.ID).select()
					})
                $('#frm2 input#1__id_barang').val(hsl.ID_Barang);        
				$('#frm2 #1__nm_barang').val(hsl.Nama_Barang);
				$('#frm2 #1__jml_transaksi').val((parseInt(hsl.Jumlah)))
				$('#frm2 #1__harga_jual').val(hsl.Harga_Beli)
				$('#id_brg').val(hsl.batch)
				$('#id_sat').val(hsl.ID);
				
					_update_stock('del',hsl.IID_Barang);//update stock
					//hapus data
					$.post('hapus_transaksi',{
						'ID'	:id
						},function(result){
							_kosongkan_field();
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
function getBarang(BarisAktif,path)
	{
		lock('#bayar');
		$.post(path+'penjualan/get_detail_barang',{'id':$('#frm2 input#'+BarisAktif+'__nm_barang').val()},
		function(result)
		{
			//if(result){
            var r=$.parseJSON(result);
                //Cek max untuk pembelian lpg base on pelanggan lpg
                if(r.Jenis=='LPG')
                {
                    $.post(path+'member/get_member_detail',{'id':$('#nm_nasabah').val()},function(result){
                         var r=$.parseJSON(result);
                           $('#frm2 input#'+BarisAktif+'__jml_transaksi')
                            .val(r.ID_Kelamin)
                            .focus().select()/**/ 
                           
                     });
                }else{
                    $('#frm2 input#'+BarisAktif+'__jml_transaksi')
                        .val('1')
                        .focus().select()/**/
                }
                $('#frm2 input#1__id_barang').val(r.ID);
                $('#isLpg').val(r.Jenis);
				$('#brssimpan').val(BarisAktif);
			     $.post('get_satuan_konversi',{
							'nm_barang'	:r.Nama_Barang
						},
						function(data){
							$('#1__nm_satuan').html(data)
						})	
            
				$('#frm2 input#'+BarisAktif+'__nm_barang').val(r.Nama_Barang);
				$('#frm2 input#'+BarisAktif+'__nm_satuan').val(r.Satuan).select()
				//tentuakn harga base on group nasabah
				$('#frm2 input#'+BarisAktif+'__harga_jual').val(r.Harga_Beli)
				$('#'+BarisAktif+'__harga_total').val(r.Harga_Beli);
					
				
				$('#'+BarisAktif+'__expired').val(r.Harga_Beli);
				$.post(path+'stock/get_material_stock',{'id_material'	:r.ID},
				function(data){
						var jm=$.parseJSON(data);
						$('table#inform tr td#ist').html(jm.stock+'  '+jm.satuan)
						//$.post(path+'stock/get_bacth',{
						//'id_barang':r.ID},
						//function(res){
							//alert(res);
						//	var bt=$.parseJSON(res)
							//$('#'+BarisAktif+'__expired').val(bt.harga_beli);
							
							//$('table#inform tr td#mdl').html(format_number(bt.harga_beli))
						//})//end bacth
                        //alert jika barang di lok karena stok kosong
                        //if(r.locked=='Y' && jm.stock<=0)
                       // {
                       //     jAlert('Stok barang sudah kosong, tidak bisa di transaksi\nSilahkan Update stock nya dahulu');
                       //     _kosongkan_field(BarisAktif);
                       // }
                                   
				})//dpatkan total stock
				//end stock
				
			})//end detailbarang
	}
	
	function getHarga(BarisAktif)
	{
		var hgj=$('#frm2 input#'+BarisAktif+'__harga_jual').val();
		var jml=$('#frm2 input#'+BarisAktif+'__jml_transaksi').val();
        
		var tth=(parseFloat(jml)*parseFloat(hgj))
		$('#'+BarisAktif+'__harga_total').val(tth)
		//total_harga();	
	}
	function nextFld(BarisAktif,e)
	{
	//jika di tekan enter akan ke field nama barang berikutnya
	  //kecuali jika sudah di akhir baris akan focus ke tombol bayar tunai
	  var next_fld=parseInt(BarisAktif)+1;
	  if(e.which==13){
	  (next_fld < 15)?
		  $('#frm2 input#'+next_fld+'__nm_barang').focus().select():
		  $('#bayar').focus();	
	  }	
	}
