// JavaScript Document
// jquery support material_jual_resep

$(document).ready(function(e) {
	$('table#fmrTable tr#1').hide();
	$('table#ListTable tr th:nth-child(7)').hide();
	$('table#ListTable tr td:nth-child(7)').hide();
	if($('#trans_new').val()==''){
		_generate_nomor('GI','#frm5 input#no_transaksi');
		_generate_faktur('#frm5 input#faktur_transaksi');
		$('#no_resep').blur();
	}
	lock('#no_transaksi');
	tglNow('#tgl_resep');
	// Pengaturan tab panel yang aktif
	var prs=$('#prs').val();
	$('table#panel tr td.flt').hide()
    $('#komposisiresep').removeClass('tab_button');
	$('#komposisiresep').addClass('tab_select');
	//validasi no resep [no resep harus diisi] akan di gunakan pada transaksi penjualan
	//sebagai nama obat dengan nama Resep-[nomor resep]
	//generate automatis ke daftar material
	$('#no_resep')
		.click(function(){
			$(this).focus().select()
		})
		.focus().select()
		.blur(function(){
			var today =Math.random()*1000;
			 var x=today
			 //var xx=parseInt(x).slice(3)
			if($('#trans_new').val()==''){
			$(this).val('RSP-'+x.toFixed(0))
			$('#trans_new').val('add');
			}
			
		})
		.focusout(function(){
			if($(this).val().length <=2){
				$('#no_resep').focus().select()
				alert('Nomor resep harus diisi min 3 karakter/huruf');
			}
		})
			
	
	//auto sugget nama dokter
	$('#frm5 #nm_dokter')
		.keyup(function(){
			pos_div('#frm5 input#nm_dokter');
			auto_suggest('get_dokter',$(this).val(),$(this).attr('id')+'-frm5');
		})
		
	$('#frm5 #nm_nasabah')
		.keyup(function(){
			pos_div('#frm5 input#nm_nasabah');
			auto_suggest2('data_konsumen',$(this).val(),$(this).attr('id')+'-frm5');
		})
		
	//transaksi di grid input
	$('#frm10 input:text')
		.click(function(){
			var id=$(this).attr('id').split('__');
			if(id[1]=='nm_satuan'){
				pos_div(this);
				auto_suggest2('satuan_material',$('#frm10 #'+id[0]+'__nm_barang').val(),$(this).attr('id')+'-frm10');
			}else if(id[1]=='harga_beli'){
				$(this).attr('title','Harga beli');	
			}
			//alert(id);
		})
		.keyup(function(){
			var id=$(this).attr('id').split('__');
			switch(id[1]){
				case 'nm_barang':
				pos_div(this);
				auto_suggest2('data_material',$(this).val(),id[0]+'__nm_barang-frm10');
				break;
				case 'expired':
					tanggal(this);
				break;	
				case 'harga_jual' :
					$(this).terbilang({
						'output_div':'terbilang'})//menampikan data terbilang 
					pos_info(this,'terbilang');
				break;
				case 'jml_transaksi':
					var hj=$('#'+id[0]+'__harga_jual').val();
					var subtotal=(parseFloat($(this).val())* parseFloat(hj))
					$('#ttharga').val($('input.subtt').sumValues())
					$('#'+id[0]+'__harga_total').val(subtotal.toFixed(2));
				break;
				case 'nm_produsen':
				break;
			}
		})
		.focus(function(){
			$(this).select();
			focusID=$(this).attr('id');
		})
		.keypress(function(e){
			var id=$(this).attr('id').split('__');
			var nx=parseInt(id[0])+1;
				if(e.which==13){
				switch(id[1]){
				 case 'jml_transaksi':
				 	if(nx < 8){
						//_total_belanja();
						$('#'+nx+'__nm_barang').focus();
					}else{
						tabField();
					}
				 $('#ttharga').val($('input.subtt').sumValues())
				 //image_click(id[0],'simpan','GI');
				 //$('span#s-'+id[0]).hide();
				 //$('span#e-'+id[0]).show();
				 break;	
				}
					$('#terbilang').hide();//hide data terbilang jika di tekan enter
				}
		})
		.focusout(function(){
			$('#terbilang').hide();
			var id=$(this).attr('id').split('__');
			if($('#'+id[0]+'__jml_transaksi').val().length >0){
				image_click(id[0],'simpan','GI');
			}
			if(id[1]=='jml_transaksi') $('#ttharga').val($('input.subtt').sumValues())
		})
		
	// simpan data resep
	$('input:button').click(function(){
		var path=$('#path').val();
		var id=$(this).attr('id')
		switch(id){
			case 'rsp-simpan':
			var faktur		=$('#no_resep').val();
			var nm_nasabah	=$('#nm_nasabah').val();
			//buat material di table material master
			$.post(path+'inventory/simpan_barang',{
			'nm_jenis'		:'RESEP',
			'nm_golongan'	:'',
			'nm_kategori'	:'RACIKAN',
			'nm_barang'		:'RESEP-'+faktur+'-'+nm_nasabah,
			'nm_satuan'		:'RSP',
			'margin_jual'	:'10',
			'stokmin'		:'0',
			'stokmax'		:'0',
			'linked'		:''
			},function(result){
				//masukan ke table stok
				$.post('stock_resep',{
					'nm_barang'	:'RESEP-'+faktur+'-'+nm_nasabah,
					'batch'		:getNextDate($('#tgl_resep').val(),7,'/'),
					'expired'	:getNextDate($('#tgl_resep').val(),7,'/'),
					'stock'		:'1',
					'blokstok'	:'0',
					'nm_satuan'	:'RSP',
					'harga_beli':$('#ttharga').val()
				},function(result){
					document.location.href=path+'penjualan/resep_std';
				})
			})

			break;
			case 'cancel-rsp':
				
			break;	
		}
	})
});

//auto suggest di klik
function on_clicked(id,fld,frm){
	 var kolom=fld.split('__');
	 //alert(kolom);
		 switch(kolom[1]){
			 case 'nm_barang':
			 $.post('get_satuan',{'nm_barang':id,'rsp':'y'},
					function(result){
						//alert(result)
						var hsl=$.parseJSON(result);
						$('#frm10 #'+kolom[0]+'__nm_satuan').val(hsl.satuan)
						$('#frm10 #'+kolom[0]+'__harga_jual').val(hsl.harga_jual)
						$('#frm10 #'+kolom[0]+'__expired').val(hsl.expired)
					})
				$('#frm10 #'+kolom[0]+'__jml_transaksi').focus().select();
			 break;
		 }
}

	//membuat nomor transaksi otomatis
	function _generate_nomor(tipe,field){
		$.post('nomor_transaksi',{'tipe':tipe},
		function(result){
			$(field).val(result);
			//$('#trans_new').val('add');
			
		})
	}
	//membuat nomor faktur otomatis khusus untuk penjualan
	// format nomor faktur adalah yyyymmdd- [nomor random 1000-9999]
	function _generate_faktur(field){
		$.post('nomor_faktur',{'tipe':'rnd'},
		function(result){
			$(field).val(result);
		})
	}

//fungsi jika tombol simpan di tekan akan mengirim data ke fungsi
//simpan_transaksi di pembelian controller
//dengan feedback / result tbody table#ListTable akan terisi data yang tersimpan
//image_click([id field],[jenis aksi])
function image_click(id,cl,jtran){
	//unlock('input');
	switch(cl){
		case 'simpan':
			var path=$('#path').val();
			var id_trans	=$('#no_transaksi').val();
			var tgl_trans	=$('#tgl_resep').val();
			var faktur		=$('#no_resep').val();
			var vendor_name	=$('#nm_dokter').val();
			var cara_bayar	=$('#cara_bayar').val();
			var nm_barang 	=$('#'+id+'__nm_barang').val();
			var nm_satuan 	=$('#'+id+'__nm_satuan').val();
			var jml_trans 	=$('#'+id+'__jml_transaksi').val();
			var harga_beli	=$('#'+id+'__harga_jual').val();
			var ket_trans	=$('#'+id+'__harga_total').val();
			var expired		=$('#'+id+'__expired').val();
			var nm_nasabah	=$('#nm_nasabah').val();
			var akun		='KAS TOKO';
			if(jml_trans.match(/[0-9]/)){
			$.post(path+'pembelian/simpan_transaksi',
				{
				'no_transaksi'		:id_trans,
				'tgl_transaksi'		:tgl_trans,
				'faktur_transaksi'	:'RESEP-'+faktur+'-'+nm_nasabah,
				'nm_produsen'		:vendor_name,
				'cara_bayar'		:'Cash',
				'nm_barang'			:nm_barang,
				'nm_satuan'			:nm_satuan,
				'jml_transaksi'		:jml_trans,
				'harga_beli'		:harga_beli,
				'ket_transaksi'		:ket_trans,
				'expired'			:expired,
				'jtran'				:jtran,
				'akun'				:akun
				},
				function(result){
					//$('#tbl-canceltrans table#ListTable tbody').html(result);
					 lock('#no_transaksi');
					 $('table#ListTable tr#r-'+id[0]+' input').attr('disabled','disabled');
				})
			}else{
				lock('#no_transaksi');
				alert('Jumlah harus harus di isi angka');
			}
		break;
		case 'edit':
			
		break;
		case 'del':
			var id=id.split('-');
			$.post('hapus_transaksi',{'id_trans':id[1],'no_tran':$('#no_transaksi').val()},
			function(result){
				$('#tbl-canceltrans table#ListTable tbody').html(result);
			})
		break;
	}
}