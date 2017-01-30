// JavaScript Document
$(document).ready(function(e) {
	$(this).keypress(function(e){
		e.which==122;
		});
	var path=$('#path').val();
	$('table#frame2 tr th:nth-child(7)').hide();
	$('table#frame2 tr td:nth-child(7)').hide();
	// Pengaturan tab panel yang aktif
	var prs=$('#prs').val();
	$('table#panel tr td.flt').hide()
    $('#penjualan').removeClass('tab_button');
	$('#penjualan').addClass('tab_select');
	//$('#v_inputpembelian table#ListTable').hide();
	//$('#v_inputpembelian table#ListTable').hide();
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
	});
	//generate header transaksi
	if($('#trans_new').val()==''){
		_generate_nomor('GI','#frm1 input#no_transaksi');
		
	}
	lock('#no_transaksi,#kredit,#bayar');
	//lock tanggal jika user bukan level adminstrator /superuser
	if($('#aktif_user').val()>2){
		lock('#tgl_transaksi');
	}else{
		unlock('#tgl_transaksi')
	}
	tglNow('#tgl_transaksi');
	$('#kasir').html('0');//kosongkan nilai pada total transaksi
	$('#aktif').val('')//kosongkan cell yang aktif
	
	//event untuk input textbox di grid table#listTable
		$('#frm2 input:text')
				.focus(function(){
					var id=$(this).attr('id').split('__');
					$('#aktif').val(id[0]);
					if(parseInt(id[0])==1){
						_simpan_header();
					}
					if(parseInt(id[0])>1){
						unlock('#bayar');
						if($('#frm2 #'+(parseInt(id[0])-1)+'__nm_barang').val()!=''){
						_simpan_detail((parseInt(id[0])-1));
						}
					//alert(parseInt(id[0])-1)
					}
				//autosuggest nama barang
					$('#frm2 input#'+id[0]+'__nm_barang')
							.coolautosuggest({
									url:path+'inventory/data_material?fld=Nama_Barang&limit=8&str=',
									width:350,
									showDescription	:true,
									onSelected:function(result)
									{
										if(result){
										$('#frm2 input#'+id[0]+'__nm_satuan').val(result.nm_satuan)
										$('#frm2 input#'+id[0]+'__jml_transaksi')
											.val('1')
											.focus().select()
										$('#frm2 input#'+id[0]+'__harga_jual').val(result.hargajual)
										$('#'+id[0]+'__harga_total').val(result.hargajual);
										total_harga();
										//dapatkan total stock
										$.post(path+'stock/get_material_stock',{
											'id_material'	:result.id_barang},
											function(data){
												var jm=$.parseJSON(data);
												if($.trim(jm.stock)=='0'||jm.stock==null){
													if(confirm('Stock '+result.data+' = 0 (Kosong)\nTransaksi akan dilanjutkan?')){
														$('table#inform tr td#ist').html((jm.satuan==null)?'0 '+result.nm_satuan:'0 '+jm.satuan)
													}else{
														_kosongkan_field(id[0]);
													}
												}else{
												$('table#inform tr td#ist').html(jm.stock+'  '+jm.satuan)
												}
											})
										$.post(path+'stock/get_bacth',{
											'id_barang':result.id_barang},
											function(res){
												//alert(res);
												var bt=$.parseJSON(res)
												$('#'+id[0]+'__expired').val(bt.batch);
											})
										}
									}
							})
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
			})
			
	//auto sugest nama nasabah
	$('#frm1 input#nm_nasabah')
		.coolautosuggest({
				url		:path+'member/get_anggota?limit=15&str=',
				width	:350,
				showDescription	:true,
				onSelected		:function(result){
					//tombol bayar kredit aktif
					unlock('#kredit')
					$('#id_member').val(result.ID);
				}
		})
		
	$('img').click(function(){
		var id=$(this).attr('id').split('-');
		var cl=$(this).attr('class');
		//alert(id+cl)
		switch(cl){
			case 'close':
			keluar(); //popup hide
			break;
			case 'del':
			//alert(id);
			$('table#frame2 tr#r-'+id[1]+' input').removeAttr('disabled');
			image_click(id[1],'simpan','GIR');
			$('table#frame2 tr#r-'+id[1]+' input').addClass('coret');
			$('table#frame2 tr#r-'+id[1]+' input.subtt').val('0');
			$('#kasir').html( format_number($('input.subtt').sumValues()))
			$('#prs').val($('input.subtt').sumValues())
			$('table#frame2 tr#r-'+id[1]+' input').attr('disabled','disabled');
			break;
		}
	})
	
	$('#batal').click(function(){
		$('#frm2 input').val('');
		$('#frm2 input').removeAttr('disabled')
		unlock('#no_transaksi,#tgl_transaksi');
		$.post('batal_transaksi',{
			'no_trans'	:$('#no_transaksi').val(),
			'tanggal'	:$('#tgl_transaksi').val()},
		function(result){
			lock('#no_transaksi');
			document.location.reload();
			
		})
	})
	$('#bayar').click(function(){
		var cb=$('#cbayare').val();
		var tb=$('#prs').val();
		var pakai_ppn=($('#ppne').is(':checked'));
		var ppn=(pakai_ppn==true)?(parseInt(tb)*10)/100:0;
		var frm=(cb==1)?'#frm3':'#frm5';
		var prs=(cb==1)?'pembayaran':'kredited';
		var t_pos=(cb==1)?'25%':'17%';
		(ppn==0)?lock('#ppn'):lock('#ppn');
				$('#stat_sim').val(frm);
				$('#nama').val(prs);
				$('#pp-'+prs).css({'left':'28%','top':t_pos});
				$(frm+' #total_belanja').val(format_number($('#prs').val()))
				$(frm+' #total_bayar').val(format_number(parseInt(tb)+parseInt(ppn)))
				$('#jmlbayar').val(parseInt(tb)+parseInt(ppn))
				$('#jmlbayar').terbilang({'awalan':'Total Bayar :','output_div':'kekata','akhiran':'rupiah'})
				$(frm+' #ppn').val(format_number(ppn.toFixed(0)));
				$('#lock').show();
				$('#pp-'+prs).show('slow');
				$('#frm5 #kembalian').val(parseInt(tb)+parseInt(ppn));
				$('#kekata').show('slow');
				$(frm+'  input#dibayar').focus().select();
	})
	
	//button bayar di tekan
	var frm=$('#stat_sim').val();
	$(frm+' #dibayar')
		.keyup(function(){
			$(this).terbilang({'awalan':'Di Bayar :','output_div':'kekata','akhiran':'rupiah'})
			var ttb=to_number($(frm+' #total_bayar').val())
			var sisa=parseInt($(this).val())-parseInt(ttb)
			$(frm+' #kembalian').val(format_number(sisa))
			$('#jmlbayar').val(sisa);
		})
		.focusout(function(){
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
		})
		.keypress(function(e){
			if(e.which==13){
				$(this).focusout();
				$(frm+' #saved-dibayar').focus()
			 $('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
			}
		})
	//jika ppn tidak ada dengan klik field ppn otomatis berubah nol dan 
	// dengan double klik akan kembali ke semula
	$(frm+'#ppn')
	  .focus(function(){
		$(frm+'#total_bayar').val($(frm+'#total_belanja').val())
		$('#jmlbayar').val(to_number($(frm+'#total_belanja').val()));
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
		$(this).val('0');
	 	$(this).attr('title','Double click for add ppn 10%');
		$(frm+'#dibayar').val(0);$(frm+'#kembalian').val(0);
	 })
	 .dblclick(function(){
		var tb=$('#prs').val();
		var ppn=(parseInt(tb)*10)/100;
		 $(this).val(format_number(ppn.toFixed(0)));
		$(frm+'#total_bayar').val(format_number(parseInt(tb)+parseInt(ppn)))
		$('#jmlbayar').val(parseInt(tb)+parseInt(ppn));
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
	 })
	//jika F1 di tekan muncul popup stock overview
	//autosugest nama_barang akan muncul data stock
	$('#frm4 input#nm_barang')
		.coolautosuggest({
				url:path+'inventory/data_material?fld=Nama_Barang&limit=10&str=',
				width:350,
				showDescription	:false,
				onSelected:function(result){
					$('#status').val(result.status);
					$('#nm_kategori').val(result.nm_kategori);
					$.post(path+'stock/list_stock',{'nm_barang':result.description},
						function(data){
							$('#tbl-viewstock table#ListTable tbody').html(data);
						})
				}
		})

	//simpan data penjualan + print struk penjualan
	$('#frm3 #saved-dibayar')
		.click(function(){
		unlock('#ppn,#no_transaksi,#tgl_transaksi');//enable field yang disabled
		_update_jenis_pembayaran();//update jenis pebayaran sesuai dengan cara bayar
		//jika focus out langsung ke button simpan atau ke field lain
		//makan lakukan penyimpanan data di baris terakhir
		if($('#'+$('#aktif').val()+'__nm_barang').val()!=''){
			_simpan_detail($('#aktif').val()); 
		}
		
		//_simpan_pembayaran();//simpan data pembayaran
		//_print_struk($('#no_transaksi').val(),$('#tgl_transaksi').val());//print struk pembayaran
		})
		
	$('#frm5 #saved-dikredit')
		.click(function(){
		unlock('#ppn,#no_transaksi,#tgl_transaksi');//enable field yang disabled
		_update_jenis_pembayaran();//update jenis pebayaran sesuai dengan cara bayar
		//jika focus out langsung ke button simpan atau ke field lain
		//makan lakukan penyimpanan data di baris terakhir
		if($('#'+$('#aktif').val()+'__nm_barang').val()!=''){
			_simpan_detail($('#aktif').val()); 
		}
		
/*		_simpan_pembayaran();//simpan data pembayaran
		_print_struk($('#no_transaksi').val(),$('#tgl_transaksi').val());//print struk pembayaran
*/		})
	//pilih cara pembayaran yang akan dilakukan	
	$('#cbayare').change(function(){
		if($(this).val()==2 && $('#nm_nasabah').val()==''){
			alert('Nama Anggota harus isi terlebih dahulu \nJika pembayaran secara kredit');
			$('#nm_nasabah').focus().select();
			$(this).val('0').select();
		}
	})
	 
	//handling button keypress
	$(document).keypress(function(e){
		if(e.keyCode==112){
			//button F1 popup stock overview
			$('#nama').val('viewstock');
			$('#pp-viewstock').css({'left':'28%','top':'25%','height':'auto','overflow':'visible'});
			$('#lock').show();
			$('#pp-viewstock').show('slow');
			$('#frm4 input').val('');
			$('#frm4 input#nm_barang').focus().select();
			
		}else if(e.keyCode==113){
			//button F2
			$.post('get_total_trans',{
				'no_trans'	:$('#no_transaksi').val(),
				'table'		:'detail_transaksi',
				'where'		: new Array('no_transaksi','jenis_transaksi'),
				'field'		: new Array($('#no_transaksi').val(),'GI')},
				function(result){
					for (i=1;i<=result;i++){
						$('span#s-'+i).hide();
						$('span#e-'+i).show();	
					}
				})
		}else if(e.keyCode==114){// button F3
			return false
		}else if(e.keyCode==115){ //tombol F4 popup komposisi resep show
			($('#ppne').is(':checked'))?
				$('#ppne').removeAttr('checked'):
				$('#ppne').attr('checked','checked');
			return false
			//tidak di fungsikan
			var id=focusID.split('__')
			if($('#frm2 input#'+id[0]+'__nm_barang').val()=='RESEP DOKTER'){;
				$('#nama').val('tranresep');
				$('#pp-tranresep').css({'left':'10%','top':'8%'});
				$('#lock').show();
				$('#pp-tranresep').show('slow');
				$.post('resep',{'id':''},
					function(result){
						$('#tbl-tranresep').html(result)
						$('#frm5 #no_resep').focus().select();
						$('#frm10 #no_trans').val($('#frm1 #no_transaksi').val());
						tglNow('#frm5 #tgl_resep');
					})
			}
			
		}else if(e.keyCode==116){ //mematikan tombol f5
			return false
		}else if(e.keyCode==122){//mematikan tombol f11
		   return false
		}else if(e.keyCode==121){//mematikan tombol f10
		   return false
		}else if(e.keyCode==27){
			//button Esc
			switch($('#nama').val()){
				case 'viewstock':
					$('#frm4 input').val('');
					$('#tbl-viewstock table#ListTable tbody').html('');
					keluar();
				break;
				case 'pembayaran':
					$('#frm3 input:not(:button)').val('');
					$('#kekata').html('');
					keluar();
				break;
				case 'editline':
				keluar();
				break;
				case 'canceltrans':
					//$('#tbl-canceltrans table#ListTable tbody').html('');
					keluar();
				break;
				case '':
					$('#frm5 input:not(:button)').val('');
					$('#kekata').html('');
					keluar();
				break;
			}
				
		}
	})
//end document ready
})

//fungsi jika tombol simpan di tekan akan mengirim data ke fungsi
//simpan_transaksi di pembelian controller
//dengan feedback / result tbody table#ListTable akan terisi data yang tersimpan
//image_click([id field],[jenis aksi])
//simpan header transaksi
	function _simpan_header(){
		unlock('#no_transaksi,#tgl_transaksi')
		var jenis_bayar		=$('#cbayare').val();
		var frm=(jenis_bayar==1)?'#frm3':'#frm5';
		$.post('set_header_trans',{
			'no_trans'		:$('#no_transaksi').val(),
			'tanggal'		:$('#tgl_transaksi').val(),
			'faktur'		:$('#faktur_transaksi').val(),
			'member'		:$('#id_member').val(),
			'total'			:$(frm+' #total_bayar').val(),
			'cicilan'		:$(frm+' #cicilan').val(),
			'cbayar'		:$('#cbayare').val()			
		},function(result){
		lock('#no_transaksi,#tgl_transaksi')
			if($('#aktif_user').val()<=2) unlock('#tgl_transaksi');
		})
	}
	//simpan detail transaksi dalam satu baris
	function _simpan_detail(id){
		unlock('#no_transaksi,#tgl_transaksi')
		$.post('set_detail_trans',{
			'no_trans'		:$('#no_transaksi').val(),
			'tanggal'		:$('#tgl_transaksi').val(),
			'cbayar'		:$('#cbayare').val(),			
			'nm_barang' 	:$('#'+id+'__nm_barang').val(),
			'nm_satuan' 	:$('#'+id+'__nm_satuan').val(),
			'jml_trans' 	:$('#'+id+'__jml_transaksi').val(),
			'harga_jual'	:$('#'+id+'__harga_jual').val(),
			'ket_trans'		:$('#'+id+'__harga_total').val(),
			'expired'		:$('#'+id+'__expired').val(),
			'no_id'			:$('#aktif').val(),
			'batch'			:$('#'+id+'__expired').val()
		},function(result){
			lock('#no_transaksi,#tgl_transaksi')
			if($('#aktif_user').val()<=2) unlock('#tgl_transaksi');
			//$('#stat_sim').val(id+'-'+result);
		})
	}
	function _update_jenis_pembayaran(){
		unlock('#no_transaksi,#tgl_transaksi')
		var jenis_bayar		=$('#cbayare').val();
		var frm=(jenis_bayar==1)?'#frm3':'#frm5';
		$.post('update_header_trans',{
			'no_trans'	:$('#no_transaksi').val(),
			'tanggal'	:$('#tgl_transaksi').val(),
			'id_jenis'	:$('#cbayare').val(),
			'total'		:to_number($(frm+' #total_bayar').val()),
			'cicilan'	:$(frm+' #cicilan').val(),
			'id_anggota':$('#id_member').val()
		},function(result){
		_simpan_pembayaran();//simpan data pembayaran

		})
	}
	
	function _simpan_pembayaran(){
		unlock('#no_transaksi,#ppn,#tgl_transaksi');
		var jenis_bayar		=$('#cbayare').val();
		var frm=(jenis_bayar==1)?'#frm3':'#frm5';
		var no_transaksi	=$('#no_transaksi').val();
		var total_belanja	=$(frm+' #total_belanja').val();
		var ppn				=$(frm+' #ppn').val();
		var total_bayar		=$(frm+' #total_bayar').val();
		var dibayar			=$(frm+' #dibayar').val();
		var kembalian		=$(frm+' #kembalian').val();
		var terbilang		=$(frm+' #huruf').val();
		$.post('simpan_bayar',{
			'no_transaksi'	:(no_transaksi),
			'total_belanja'	:to_number(total_belanja),
			'ppn'			:to_number(ppn),
			'total_bayar'	:to_number(total_bayar),
			'dibayar'		:to_number(dibayar),
			'kembalian'		:to_number(kembalian),
			'terbilang'		:terbilang,
			'cbayar'		:jenis_bayar,
			'tanggal'		:$('#tgl_transaksi').val()
		},function(result){
				_print_struk($('#no_transaksi').val(),$('#tgl_transaksi').val());//print struk pembayaran
					keluar();

		})
	}
	//print struk
	//setelah proses print selesai lakukan refresh halaman
	function _print_struk(id,tgl){
		var path=$('#path').val();
		$.post('print_slip',{
			'no_transaksi':id,
			'tanggal'	  :tgl},
			function(result){
				//document.location.href=path+'penjualan/index';
			})
	}
	//membuat nomor transaksi otomatis berdasarkan jenis transaksi
	function _generate_nomor(tipe,field){
		$.post('nomor_transaksi',{'tipe':tipe},
		function(result){
			$(field).val(result);
			$('#trans_new').val('add');
			_generate_faktur('#frm1 input#faktur_transaksi');
		})
	}
	//membuat nomor faktur otomatis khusus untuk penjualan
	function _generate_faktur(field){
/*		$.post('nomor_faktur',{'tipe':'rnd'},
		function(result){
			$(field).val(result);
		})
*/		var n_tran=$('#frm1 input#no_transaksi').val().substr(5,5);
		var tahun=new Date()
		$(field).val(n_tran+'-'+tahun.getFullYear())
	}
	
	function _total_belanja(){
		//not available
		//diganti menggunakan plugin jquery.sumfield.js
		//eq: $([id]).val($('input.clasname').sumValues()
	}
	//hide popup window
	function keluar(){
		var nama=$('#nama').val();
		$('.autosuggest').hide();
		$('#pp-'+nama).hide('slow');
		$('#kekata').hide();
		$('#lock').hide();
	}
	
	//simpan transaksi pembayaran ke db
	function _simpan_transaksi(){
		//not available
		//transaksi di simpan pada saat kolom jml transaksi event keypress keycode 13 [enter]	
	}
	
	function total_harga(){
		$('#kasir').html( format_number($('input.subtt').sumValues()) )
		$('#prs').val($('input.subtt').sumValues())
		$('#prs').terbilang({
			'output_div':'trblkasir','akhiran':' rupiah'})//menampikan data terbilang 
			$('#huruf').val($('div#trblkasir').html());
	}
	function _kosongkan_field(id){
		$('#frm2 input#'+id+'__nm_barang')
			.val('')
			.focus().select();
		$('#frm2 input#'+id+'__nm_satuan').val('');
		$('#frm2 input#'+id+'__jml_transaksi').val('')
		$('#frm2 input#'+id+'__harga_jual').val('')
		$('#frm2 input#'+id+'__harga_total').val('')
		total_harga();	
	}