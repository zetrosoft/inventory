// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
		_generate_nomor('SPP','#frm1 input#no_transaksi');
	//lock('#no_transaksi');
	tglNow('#tgl_transaksi');
	// Pengaturan tab panel yang aktif
	var prs=$('#prs').val();
	$('table#panel tr td.flt').hide()
    $('#rencanabelanja').removeClass('tab_button');
	$('#rencanabelanja').addClass('tab_select');
	
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
	//tabField();
    $('#frm2 #1__nm_barang')
        .focus().select()
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
	$('#1__harga_jual')
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
    $('#no_transaksi').keypress(function(e){
        if(e.which==13){
            _show_list();
        }
    })
    $('#prnslip').click(function(){
        $.post('printSPP',$('#frm1').serialize(),function(result){})
    })
})
function _generate_nomor(tipe,field){
		$.post('nomor_transaksi2',{'tipe':tipe,'level':$('#aktif_user').val()},
		function(result){
			$(field).val(result);
			$('#trans_new').val('add');
			//_generate_faktur('#frm1 input#faktur_transaksi');
		})
}
function jumlah(){
	var jml=$('#1__jml_transaksi').val()
	var hgb=$('#1__harga_jual').val()
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
	$.post('get_total_spp',{
		'no_trans'	:$('#no_transaksi').val(),
		'tanggal'	:$('#tgl_transaksi').val()},
		function(result){
			$('#kasir').html(format_number(result));
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

function images_click(id,cl){
    switch(cl){
        case "simpan":
            _simpan_spp();
        break;
    }
}
function _simpan_spp(){
    $.post('simpan_spp',$('#frm1').serialize()+'&'+$('#frm2').serialize(),function(result){
        _kosongkan_field();
        _show_list();
    })
}
function _show_list(){
	unlock('#no_transaksi');
		$.post('show_list_spp',{
			'no_transaksi'	: $('#no_transaksi').val(),
			'jtran'			:'SPP',
			'tanggal'		:$('#tgl_transaksi').val()},			
				function(result){
                    $('#frm2 table#ListTable tbody').html(result);
					_get_total_belanja();
				})
	
	
}
function _kosongkan_field(){
	$('#frm2 input:text').val('');
	$('#id_brg').val('');$('#id_sat').val('');
	$('#frm2 select#1__nm_satuan').html('');	
}