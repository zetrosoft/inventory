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
	lock('#kredit,#bayar');
	$('#no_transaksi').attr('readonly','readonly');
	//lock tanggal jika user bukan level adminstrator /superuser
	if($('#aktif_user').val()>2){
		$('#tgl_transaksi').attr('readonly','readonly');
	}else{
		$('#tgl_transaksi').removeAttr('readonly')
		$('#tgl_transaksi').dynDateTime();
	}
	$('#tgl_giro').dynDateTime();
	tglNow('#tgl_transaksi');
	$('#kasir').html('0');//kosongkan nilai pada total transaksi
	$('#aktif').val('')//kosongkan cell yang aktif
	//hide nontunai 
	$('#frm2').click(function(){
		//for debug only
		//alert($(this).html());
	})
	var lnk='"div#ListTable_table_container div#ListTable_header_container table#ListTable_header'
	$('table#b tr#nontunai').hide();
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
						_simpan_detail((parseInt($('#aktif').val())-1));
						}
					
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
										$('#frm2 input#'+id[0]+'__harga_jual').val(result.hargajual)
										$('#'+id[0]+'__harga_total').val(result.hargajual);
										total_harga();
										$('#max_input').val('0')
										//dapatkan total stock
									   if(result.kategori!='106'){
										$.post(path+'stock/get_material_stock',{
											'id_material'	:result.id_barang,
											'lokasi'		:$('#id_lok').val()},
											function(dat){
												var data=$.parseJSON(dat);
												if($.trim(data.stock)=='0'||
													data.stock==null  ){
/*													if(confirm('Stock '+result.data+' = 0 (Kosong)\nTransaksi akan dilanjutkan?')){
														$('table#inform tr td#ist').html((jm.satuan==null)?'0 '+result.nm_satuan:'0 '+jm.satuan)
													}else{
														_kosongkan_field(id[0]);
													}
*/												jAlert('Stock '+result.data+' kosong (nol). tidak bisa dilakukan trasaksi\nSilahkan update dulu stocknya','Alert Stock Null');
												_kosongkan_field(id[0]);
												}else{
												$('#frm2 input#'+id[0]+'__jml_transaksi')
													.val('1')
													.focus().select()
												$('#max_input').val(data.stock);
												$('table#inform tr td#ist').html(data.stock+'  '+data.satuan)
												}
												$.post(path+'stock/get_bacth',{
													'id_barang':result.id_barang,
													'lokasi'	:$('#id_lok').val()},
													function(res){
														//alert(res);
														var bt=$.parseJSON(res)
														$('#'+id[0]+'__expired').val(bt.batch);
														$('table#inform tr td#mdl').html(format_number(bt.harga_beli))
													})
											})
									   }else{
										$('#frm2 input#'+id[0]+'__jml_transaksi')
											.val('1')
											.focus().select()	
										$('#max_input').val('10')								   }//end if jenis
									}//end if result
								}//end if autosuggest
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
						.focusout(function(){
						})
						.focus(function(){
							$('#aktif').val(id[0]);
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
			$('#frm3 input').val('')
			$('#frm5 input').val('')
			document.location.reload();
		})
	})
	$('#bayar').click(function(){
		//simpan/update input terakhir untuk mencegah inkonsistensi data
		_simpan_detail($('#aktif').val())
		//popup bayar show
		var cb=$('#cbayare').val();
		var tb=$('#prs').val();
		var pakai_ppn=($('#ppne').is(':checked'));
		var ppn=(pakai_ppn==true)?(parseInt(tb)*10)/100:0;
		var frm=(cb==1)?'#frm3':'#frm5';
		var prs=(cb==1)?'pembayaran':'kredited';
		var t_pos=(cb==1)?'25%':'17%';
		(ppn==0)?unlock('#ppn'):lock('#ppn');
		(cb==2 || cb==3)? lock('#frm5 #dibayar'): unlock('#frm5 #dibayar')
				$('#stat_sim').val(frm);
				$('#nama').val(prs);
				$('#pp-'+prs).css({'left':'28%','top':t_pos});
				$(frm+' #total_belanja').val(format_number($('#prs').val()))
				$(frm+' #total_bayar').val(format_number(parseInt(tb)+parseInt(ppn)))
				$('#jmlbayar').val(parseInt(tb)+parseInt(ppn))
				$('#jmlbayar').terbilang({'awalan':'Total Bayar :','output_div':'kekata','akhiran':'rupiah'})
				$(frm+' #ppn').val(format_number(ppn.toFixed(0)));
				$(frm+' #dibayar').val(parseInt(tb)+parseInt(ppn));
				//$('#lock').attr('align','center').html('Please wait.....in process').show();
				//$('#pp-'+prs).show('slow');
				if(cb==2||cb==3){
					$('#frm5 #dibayar').val(format_number(parseInt(tb)+parseInt(ppn)))
					$('#frm5 #kembalian').val(0);
				}else{
					$('#frm5 #kembalian').val(parseInt(tb)+parseInt(ppn));
				}
				//$('#kekata').show('slow');
				$(frm+' input#dibayar').focus().select();
				$('#result').html('<b>Data being process... please wait.').show().fadeOut(10000);
				(frm=='#frm3')?
				$(frm+' #saved-dibayar').trigger('click'):
				$(frm+' #saved-dikredit').trigger('click');
	})
	
	//button bayar di tekan
	//keyup pada input pembayaran
	//=================untuk pembayaran tunai
	$('#frm3 #dibayar')
		.focus().select()
		.keyup(function(){
			$(this).terbilang({'awalan':'Di Bayar :','output_div':'kekata','akhiran':'rupiah'})
			var ttb=to_number($('#frm3 #total_bayar').val())
			var sisa=parseInt($(this).val())-parseInt(ttb)
			$('#frm3 #kembalian').val(format_number(sisa))
			$('#jmlbayar').val(sisa);
		})
		.focusout(function(){
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
		})
		.keypress(function(e){
			if(e.which==13){
				$(this).focusout();
				$('#frm3 #saved-dibayar').focus()
			 $('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
			}
		})
	//jika ppn tidak ada dengan klik field ppn otomatis berubah nol dan 
	// dengan double klik akan kembali ke semula
	$('#frm3 #ppn')
	  .focus(function(){
		$('#frm3 #total_bayar').val($('#frm3 #total_belanja').val())
		$('#jmlbayar').val(to_number($('#frm3 #total_belanja').val()));
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
		$(this).val('0');
	 	//$(this).attr('title','Double click for add ppn 10%');
		$('#frm3 #dibayar').val(0);$('#frm3 #kembalian').val(0);
		$(this).select()
	 })
	 .keyup(function(){
		var tb=$('#prs').val();
		var pakai_ppn=($('#ppne').is(':checked'));
		var ppn=(pakai_ppn==true)?(parseInt(tb)*10)/100:$(this).val();
		var total=(pakai_ppn==true)?parseInt(tb)+parseInt(ppn):parseInt(tb)-parseInt(ppn);
		// $(this).val(format_number(ppn.toFixed(0)));
		$('#frm3 #total_bayar').val(format_number(total))
		$('#jmlbayar').val(total);
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
	 })
	//=========================untuk kredit=============================
	  $('#frm5 #dibayar')
		.keyup(function(){
			$(this).terbilang({'awalan':'Di Bayar :','output_div':'kekata','akhiran':'rupiah'})
			var ttb=to_number($('#frm5 #total_bayar').val())
			var sisa=parseInt($(this).val())-parseInt(ttb)
			$('#frm5 #kembalian').val(format_number(sisa))
			$('#jmlbayar').val(sisa);
		})
		.focusout(function(){
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
		})
		.keypress(function(e){
			if(e.which==13){
				$(this).focusout();
				$('#frm5 #saved-dibayar').focus()
			 $('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
			}
		})
	//jika ppn tidak ada dengan klik field ppn otomatis berubah nol dan 
	// dengan double klik akan kembali ke semula
	$('#frm5 #ppn')
	  .focus(function(){
		var cb=$('#cbayare').val();
		$('#frm5 #total_bayar').val($('#frm5 #total_belanja').val())
		$('#jmlbayar').val(to_number($('#frm5 #total_belanja').val()));
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
		$(this).val('0');
	 	//$(this).attr('title','Double click for add ppn 10%');
		(cb==3 || cb==2)?$('#frm5 #dibayar').val(to_number($('#frm5 #total_belanja').val())):$('#frm5 #dibayar').val('0');
		//$('#frm5 #kembalian').val(0);
		$(this).select()
	 })
	 .keyup(function(){
		var cb=$('#cbayare').val();
		var tb=$('#prs').val();
		var pakai_ppn=($('#ppne').is(':checked'));
		var ppn=(pakai_ppn==true)?(parseInt(tb)*10)/100:$(this).val();
		var total=(pakai_ppn==true)?parseInt(tb)+parseInt(ppn):parseInt(tb)-parseInt(ppn);
		// $(this).val(format_number(ppn.toFixed(0)));
		$('#frm5 #total_bayar').val(format_number(total))
		if(cb!=4){
		$('#frm5 #dibayar').val((pakai_ppn==true)?'0':format_number(total));
		$('#frm5 #kembalian').val(0);
		}else{
		$('#frm5 #dibayar').val(0);$('#frm5 #kembalian').val(format_number(total))
		}
		$('#jmlbayar').val(total);
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
	 })
    //=======================================end ==========================
	
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
			//_simpan_detail($('#aktif').val()); 
			//sudah dilakukan pada saat tekan tombol bayar $('#bayar').click();
		}
		
		_simpan_pembayaran();//simpan data pembayaran
		})
		
	$('#frm5 #saved-dikredit')
		.click(function(){
		unlock('#ppn,#no_transaksi,#tgl_transaksi');//enable field yang disabled
		_update_jenis_pembayaran();//update jenis pebayaran sesuai dengan cara bayar
		//jika focus out langsung ke button simpan atau ke field lain
		//makan lakukan penyimpanan data di baris terakhir
		if($('#'+$('#aktif').val()+'__nm_barang').val()!=''){
			//_simpan_detail($('#aktif').val()); 
			//sudah dilakukan pada saat tekan tombol bayar $('#bayar').click();
		}
		
		_simpan_pembayaran();//simpan data pembayaran
		})
	//pilih cara pembayaran yang akan dilakukan	
	$('#cbayare').change(function(){
		if($(this).val()!=1 && $('#nm_nasabah').val()==''){
			alert('Nama Pelanggan harus isi terlebih dahulu \nJika pembayaran secara kredit');
			$('#nm_nasabah').focus().select();
			$(this).val('0').select();
		}
		//if($(this).val()!=1&& $('#nm_nasabah').val()!=''){
			_update_jenis_pembayaran();
		//}
		($(this).val()!=1)?	$('table#b tr#nontunai').show():$('table#b tr#nontunai').hide();
	})
	 //autosuggest nama bank
	 $('#n_bank')
		.coolautosuggest({
				url:'get_bank?fld='+$('#id_member').val()+'&limit=10&str=',
				width:100,
				showDescription	:false,
				onSelected:function(result){
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
		}else if(e.keyCode==115){ //tombol F4 ppn activate and deactivate
/*			($('#ppne').is(':checked'))?
				$('#ppne').removeAttr('checked'):
				$('#ppne').attr('checked','checked');
			return false
*/
			$.post('re_print',{'id':''},
			function(result){
				$('#result').show().html(result).fadeOut(10000);
				document.location.href=path+'penjualan/index';
			})
 			//tidak di fungsikan
/*			var id=focusID.split('__')
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
*/			
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
			'total'			:to_number($(frm+' #total_bayar').val()),
			'cicilan'		:$(frm+' #cicilan').val(),
			'cbayar'		:$('#cbayare').val(),
			'lokasi'	:$('#id_lok').val()			
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
			'no_id'			:id,
			'batch'			:$('#'+id+'__expired').val(),
			'id_post'		:($('#ppne').is(':checked'))?'1':'0'
		},function(result){
			lock('#no_transaksi,#tgl_transaksi')
			if($('#aktif_user').val()<=2) unlock('#tgl_transaksi');
			//lakukan update stock pada barang yang dimaksud
			//_update_stock($('#no_transaksi').val(),$('#tgl_transaksi').val());//print struk pembayaran
			
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
			'id_anggota':$('#id_member').val(),
			'nogiro'	:$('#nogiro').val(),
			'n_bank'	:$('#n_bank').val(),
			'tgl_giro'	:$('#tgl_giro').val(),
			'lokasi'	:$('#id_lok').val()
		},function(result){
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
			_update_stock($('#no_transaksi').val(),$('#tgl_transaksi').val());//print struk pembayaran
					keluar();//

		})
	}
	function _update_stock(id,tgl,jml){
		$.post('update_material_stock',{
			'no_trans'	:id,
			'tanggal'	:tgl,
			'jumlah'	:jml,
			'lokasi'	:$('#id_lok').val()
		},function(result){
			if(parseInt(result)>0){
				_update_stock($('#no_transaksi').val(),$('#tgl_transaksi').val(),result);
			}else{
				_print_struk(id,tgl);//print struk pembayaran
			}
		})
	}
	function printPage (sURL) {
      var oHiddFrame = document.createElement("iframe");
      oHiddFrame.src = sURL;
      oHiddFrame.style.visibility = "hidden";
      oHiddFrame.style.position = "fixed";
      oHiddFrame.style.right = "0";
      oHiddFrame.style.bottom = "0";
      document.body.appendChild(oHiddFrame);
      oHiddFrame.contentWindow.onload = oHiddFrame.contentWindow.print;
      oHiddFrame.contentWindow.onafterprint = function () { document.body.removeChild(oHiddFrame); };
    }
	//print struk
	//setelah proses print selesai lakukan refresh halaman
	function _print_struk(id,tgl){
		var path=$('#path').val();
		$.post('print_slip',{
			'no_transaksi':id,
			'tanggal'	  :tgl,
			'lokasi'	  :$('#id_lok').val()},
			function(result){
				//buka_wind($.trim(result));
				jConfirm('Print Struk Pembelian','Alert',function(r){
					if(r){
						$.post('re_print',{'id':''},
						function(result){
							$('#result').show().html(result).fadeOut(10000);
							document.location.href=path+'penjualan/index';
						})
					}else{
					document.location.href=path+'penjualan/index';
					}
				})
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