// JavaScript Document
var path=$('#path').val();
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
    $('#penjualanumum').removeClass('tab_button');
	$('#penjualanumum').addClass('tab_select');
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
    $('#frm3 #dibayar').addClass('big');
	//generate header transaksi
	if($('#trans_new').val()==''){
		_generate_nomor('GI','#frm1 input#no_transaksi');
		
	}
    $('#frm3 #chkNota').val('Y').select();
    $('#grp_nasabah')
		.val('Harga_Jual').select()
		.removeAttr('disabled');
    
	$('#nm_nasabah')
        .focus(function(){$(this).select();})
        .live("keypress",function(e){
            if(e.which==13){
                $('#1__nm_barang').focus().select();
                _get_detailNasabah($(this).val()+'-');
            }
        })
       /* .live('change',function(e){
        if($(this).val().length==13){
            $('#1__nm_barang').focus().select();
            _get_detailNasabah($(this).val());
        }
        });*/
	
		
	lock('#no_transaksi,#kredit,#bayar');
	//lock tanggal jiunctika user bukan level adminstrator /superuser
	if($('#aktif_user').val()>2){
		lock('#tgl_transaksi');
	}else{
		unlock('#tgl_transaksi')
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
			$('#frm2 input.xx')
				.focus(function()
				{
					var id=$(this).attr('id').split('__');
					$('#aktif').val(id[0]);
					$('#idaktif').val(id[0]+'__jml_transaksi');
					$('#hrgaktif').val(id[0]+'__harga_jual');
					var BarisAktif=id[0];
					if(parseInt(BarisAktif)==1){
						$('#frm2 #1__nm_barang').focus(function(){	
						_simpan_header();})
						//_non_aktifkan(id[0],false);
					}
					if(parseInt(BarisAktif)>1){
						/*unlock('#bayar');*/
						if($('#frm2 #'+(parseInt(BarisAktif)-1)+'__nm_barang').val()!=''){
						var ss=$('#brssimpan').val();
							if(ss != (parseInt(BarisAktif)-1))
							{
								/*_simpan_detail((parseInt(BarisAktif)-1));
								_non_aktifkan((parseInt(BarisAktif)-1),true);*/
							}
						}
					
					}
				}) //end focus
				
			
		  $('#frm2 #1__nm_barang')
			  .live('keypress',function(e){ if(e.which==13){getBarang('1',path);$('#frm2 #'+$('#idaktif').val()).focus().select();} })
			  .live('focus',function(){_simpan_header(); })
		  $('#frm2 input#1__jml_transaksi')
			  .live("keydown",function(){getHarga('1'); })
			  .live("keypress",function(e){if(e.which==13){MaxLPG('1');} });			
		  $('#frm2 input#1__harga_jual')
			  .keyup(function(e){getHarga('1');})
			  //.keypress(function(e){nextFld('1',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('1');_non_aktifkan('1',true);nextFld('1',e)}})
		//=======================================================
		  $('#frm2 #2__nm_barang')
		  .live('keypress',function(e){if(e.which==13){getBarang('2',path);$('#frm2 #'+$('#idaktif').val()).focus().select();} })
		  //.live('focusout',function(){if($(this).val()==''){ _simpan_detail('1');_non_aktifkan('1',true);}})
		  $('#frm2 input#2__jml_transaksi')
			  .live("keydown",function(){getHarga('2');})
			  .live("keypress",function(e){if(e.which==13){MaxLPG('2');}});			
		  $('#frm2 input#2__harga_jual')
			  .keyup(function(e){getHarga('2');})
			  //.keypress(function(e){nextFld('2',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('2');_non_aktifkan('2',true);nextFld('2',e)}})
		//=======================================================
		  $('#frm2 #3__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('3',path);$('#frm2 #'+$('#idaktif').val()).focus().select();} })
		  //.live('focusout',function(){ if($(this).val()==''){_simpan_detail('2');_non_aktifkan('2',true);}});
		  
		  $('#frm2 input#3__jml_transaksi')
			  .live("keydown",function(){getHarga('3');})
			  .live("keypress",function(e){if(e.which==13){MaxLPG('3');}});			
		  $('#frm2 input#3__harga_jual')
			  .keyup(function(e){getHarga('3');})
			  //.keypress(function(e){nextFld('3',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('3');_non_aktifkan('3',true);nextFld('3',e)}})
		//=======================================================
		  $('#frm2 #4__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('4',path);$('#frm2 #'+$('#idaktif').val()).focus().select();} })
		  //.live('focusout',function(){ if($(this).val()==''){_simpan_detail('3');_non_aktifkan('3',true);}});
		  
		  $('#frm2 input#4__jml_transaksi')
			  .live("keydown",function(){getHarga('4');})
			  .live("keypress",function(e){if(e.which==13){MaxLPG('4');}});			
		  $('#frm2 input#4__harga_jual')
			  .keyup(function(e){getHarga('4');})
			  //.keypress(function(e){nextFld('4',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('4');_non_aktifkan('4',true);nextFld('4',e)}})
		//=======================================================
		  $('#frm2 #5__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('5',path);$('#frm2 #'+$('#idaktif').val()).focus().select();} })
		  //.live('focusout',function(){if($(this).val()==''){ _simpan_detail('4');_non_aktifkan('4',true);}});
		  
		  $('#frm2 input#5__jml_transaksi')
			  .live("keydown",function(){getHarga('5');})
			  .live("keypress",function(e){if(e.which==13){MaxLPG('5');}});			
		  $('#frm2 input#5__harga_jual')
			  .keyup(function(e){getHarga('5');})
			  //.keypress(function(e){nextFld('5',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('5');_non_aktifkan('5',true);nextFld('5',e)}})
		//=======================================================
		  $('#frm2 #6__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('6',path);$('#frm2 #'+$('#idaktif').val()).focus().select();} })
		 // .live('focusout',function(){if($(this).val()==''){ _simpan_detail('5');_non_aktifkan('5',true);}});
		  
		  $('#frm2 input#6__jml_transaksi')
			  .live("keydown",function(){getHarga('6');})
			  .live("keypress",function(e){if(e.which==13){MaxLPG('6');}});			
		  $('#frm2 input#6__harga_jual')
			  .keyup(function(e){getHarga('6');})
			  //.keypress(function(e){nextFld('6',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('6');_non_aktifkan('6',true);nextFld('6',e)}})
		//=======================================================
		  $('#frm2 #7__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('7',path);$('#frm2 #'+$('#idaktif').val()).focus().select();}
		  })
		  //.live('focusout',function(){if($(this).val()==''){ _simpan_detail('6');_non_aktifkan('6',true);}});
		  
		  $('#frm2 input#7__jml_transaksi')
			  .live("keydown",function(){getHarga('7');})
			  .live("keypress",function(e){if(e.which==13){MaxLPG('7');}});			
		  $('#frm2 input#7__harga_jual')
			  .keyup(function(e){getHarga('7');})
			  //.keypress(function(e){nextFld('7',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('7');_non_aktifkan('7',true);nextFld('7',e)}})
		//=======================================================
		  $('#frm2 #8__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('8',path);$('#frm2 #'+$('#idaktif').val()).focus().select();}
		  })
		  //.live('focusout',function(){if($(this).val()==''){ _simpan_detail('7');_non_aktifkan('7',true);}});
		  
		  $('#frm2 input#8__jml_transaksi')
			  .live("keydown",function(){getHarga('8');})
			  .live("keypress",function(e){if(e.which==13){MaxLPG('8');}});			
		  $('#frm2 input#8__harga_jual')
			  .keyup(function(e){getHarga('8');})
			  //.keypress(function(e){nextFld('8',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('8');_non_aktifkan('8',true);nextFld('8',e)}})
		//=======================================================
		  $('#frm2 #9__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('9',path);$('#frm2 #'+$('#idaktif').val()).focus().select();}
		  })
		 // .live('focusout',function(){if($(this).val()==''){ _simpan_detail('8');_non_aktifkan('8',true);}});
		  
		  $('#frm2 input#9__jml_transaksi')
			  .live("keydown",function(){getHarga('9');})
			  .live("keypress",function(e){if(e.which==13){MaxLPG('9');}});			
		  $('#frm2 input#9__harga_jual')
			  .keyup(function(e){getHarga('9');})
			  //.keypress(function(e){nextFld('9',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('9');_non_aktifkan('9',true);nextFld('9',e)}})
		//=======================================================
		  $('#frm2 #10__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('10',path);$('#frm2 #'+$('#idaktif').val()).focus().select();}
		  })
		  //.live('focusout',function(){if($(this).val()==''){ _simpan_detail('9');_non_aktifkan('9',true);}});
		  
		  $('#frm2 input#10__jml_transaksi')
			  .live("keydown",function(){getHarga('10');})
			  .live("keypress",function(e){if(e.which==13){MaxLPG('10');}});			
		  $('#frm2 input#10__harga_jual')
			  .keyup(function(e){getHarga('10');})
			  //.keypress(function(e){nextFld('10',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('10');_non_aktifkan('10',true);nextFld('10',e)}})
		//=======================================================
		  $('#frm2 #11__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('11',path);$('#frm2 #'+$('#idaktif').val()).focus().select();}
		  })
		  //.live('focusout',function(){if($(this).val()==''){ _simpan_detail('10');_non_aktifkan('10',true);}});
		  
		  $('#frm2 input#11__jml_transaksi')
			  .live("keydown",function(){getHarga('11');})
			  .live("keypress",function(e){if(e.which==13){$('#11__harga_jual').focus().select()}});			
		  $('#frm2 input#11__harga_jual')
			  .keyup(function(e){getHarga('11');})
			  //.keypress(function(e){nextFld('11',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('11');_non_aktifkan('11',true);nextFld('11',e)}})
		//=======================================================
		  $('#frm2 #12__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('12',path);$('#frm2 #'+$('#idaktif').val()).focus().select();}
		  })
		  //.live('focusout',function(){if($(this).val()==''){ _simpan_detail('11');_non_aktifkan('11',true);}});
		  
		  $('#frm2 input#12__jml_transaksi')
			  .live("keydown",function(){getHarga('12');})
			  .live("keypress",function(e){if(e.which==13){$('#12__harga_jual').focus().select()}});			
		  $('#frm2 input#12__harga_jual')
			  .keyup(function(e){getHarga('12');})
			  //.keypress(function(e){nextFld('12',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('12');_non_aktifkan('12',true);nextFld('12',e)}})
		//=======================================================
		  $('#frm2 #13__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('13',path);$('#frm2 #'+$('#idaktif').val()).focus().select();}
		  })
		  //.live('focusout',function(){ if($(this).val()==''){_simpan_detail('12');_non_aktifkan('12',true);}});
		  
		  $('#frm2 input#13__jml_transaksi')
			  .live("keydown",function(){getHarga('13');})
			  .live("keypress",function(e){if(e.which==13){$('#13__harga_jual').focus().select()}});			
		  $('#frm2 input#13__harga_jual')
			  .keyup(function(e){getHarga('13');})
			  //.keypress(function(e){nextFld('13',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('13');_non_aktifkan('13',true);nextFld('13',e)}})
		//=======================================================
		  $('#frm2 #14__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('14',path);$('#frm2 #'+$('#idaktif').val()).focus().select();}
		  })
		  //.live('focusout',function(){if($(this).val()==''){ _simpan_detail('13');_non_aktifkan('13',true);}});
		  
		  $('#frm2 input#14__jml_transaksi')
			  .live("keydown",function(){getHarga('14');})
			  .live("keypress",function(e){if(e.which==13){$('#14__harga_jual').focus().select()}});			
		  $('#frm2 input#14__harga_jual')
			  .keyup(function(e){getHarga('14');})
			  //.keypress(function(e){nextFld('14',e);})
			  .keypress(function(e){if(e.which==13){_simpan_detail('14');_non_aktifkan('14',true);nextFld('14',e)}})
		//=======================================================
		  $('#frm2 #15__nm_barang').live('keypress',function(e){
			  if(e.which==13){getBarang('15',path);$('#frm2 #'+$('#idaktif').val()).focus().select();}
		  })
		  //.live('focusout',function(){ if($(this).val()==''){_simpan_detail('14');_non_aktifkan('14',true);}});
		  
		  $('#frm2 input#15__jml_transaksi')
			  .live("keydown",function(){getHarga('15');})
			  .live("keypress",function(e){if(e.which==13){$('#15__harga_jual').focus().select()}});			
		  $('#frm2 input#15__harga_jual')
			  .keyup(function(e){getHarga('15');})
			  //.keypress(function(e){if(e.which==13){_simpan_detail('15');nextFld('15',e)};})
			  .keypress(function(e){if(e.which==13){_simpan_detail('15');_non_aktifkan('15',true);nextFld('15',e)}})
		//=======================================================
					
	//auto sugest nama nasabah
	/*$('#frm1 input#nm_nasabah')
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
		.attr('data-source',"["++"]")*/
		
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
			'tanggal'	:$('#tgl_transaksi').val(),
            'dari'  :"Jual"},
		function(result){
			lock('#no_transaksi');
			document.location.reload();
			
		})
	})
	$('#bayar').click(function(){
		if(parseInt($('#frm2 #15__jml_transaksi').val())>0){ _simpan_detail('15');_non_aktifkan('15',true);}
		total_harga();
        // _get_nota($('#nm_nasabah').val());
        $.post('hutangpelanggan',{'nm_nasabah':$('#nm_nasabah').val()},function(result){
            $('#frm3 #nota').val(to_number(result));
        
		var cb=$('#cbayare').val();
		var tb=$('#prs').val();
		var pakai_ppn=($('#ppne').is(':checked'));
		var ppn=(pakai_ppn==true)?(parseInt(tb)*10)/100:0;
		var frm=(cb==1)?'#frm3':'#frm5';
		var frm='#frm3';
		var prs=(cb==1)?'pembayaran':'kredited';
		var t_pos=(cb==1)?'10%':'10%';
        var nt=parseFloat($(frm+' #nota').val());
		//(ppn==0)?lock('#ppn'):lock('#ppn');
       
				$('#stat_sim').val(frm);
				$('#nama').val(prs);
				$('#pp-'+prs).css({'left':'28%','top':t_pos});
				$(frm+' #total_belanja').val(format_number($('#prs').val()))
				$(frm+' #total_bayar').val(format_number(parseInt(tb)+parseInt(ppn)+parseInt(nt)))
				$('#jmlbayar').val(parseInt(tb)+parseInt(ppn)+parseInt(nt))
				$('#jmlbayar').terbilang({'awalan':'Total Bayar :','output_div':'kekata','akhiran':'rupiah'})
				$(frm+' #ppn').val(format_number(ppn.toFixed(0)));
				$('#lock').show();
				$('#pp-'+prs).show('slow');
				$('#frm5 #kembalian').val(parseInt(tb)+parseInt(ppn));
				$('#kekata').show('slow');
				$(frm+' input#dibayar').focus().select();
                $(frm+' #chkNota').val('Y').select();
        });
	})
	
	//button bayar di tekan
	//button bayar di tekan
	//keyup pada input pembayaran
	//=================untuk pembayaran tunai
	$('#frm3 #dibayar')
		.focus().select()
		.keyup(function(){
			$(this).terbilang({'awalan':'Di Bayar :','output_div':'kekata','akhiran':'rupiah'})
			var ttb=to_number($('#frm3 #total_bayar').val())
			var dby=to_number($('#frm3 #dibayar').val())
			var sisa=parseInt(dby)-parseInt(ttb)
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
        var nota=($('#frm3 #chkNota').val()=='Y')?$('#frm3 #nota').val():0;
		var ppn=(pakai_ppn==true)?(parseInt(tb)*10)/100:$(this).val();
		var total=(pakai_ppn==true)?parseInt(tb)+parseInt(ppn)+parseInt(nota):parseInt(tb)-parseInt(ppn)+parseInt(nota);
		// $(this).val(format_number(ppn.toFixed(0)));
		$('#frm3 #total_bayar').val(format_number(total))
		$('#jmlbayar').val(total);
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
	 })
	 .keypress(function(e){
		 if(e.which==13)
		 {
			 $('#frm3 #dibayar').focus().select();
		 }
	 })
    //jika ada hutang dan belum di simpan data nya
    // maka nota ditulis manual terlebih dahulu
    $('#frm3 #nota')
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
		var ppn=(pakai_ppn==true)?(parseInt(tb)*10)/100:$('#frm3 #ppn').val();
        var nota=($('#frm3 #chkNota').val()=='Y')?$(this).val():0;
		var total=(pakai_ppn==true)?parseInt(tb)+parseInt(ppn)+parseInt(nota):parseInt(tb)-parseInt(ppn)+parseInt(nota);
		// $(this).val(format_number(ppn.toFixed(0)));
		$('#frm3 #total_bayar').val(format_number(total))
		$('#jmlbayar').val(total);
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
    })
      .keypress(function(e){
        if(e.which==13)
		 {
			 $('#frm3 #dibayar').focus().select();
		 }
    })
    $('#frm3 #chkNota').live('change',function(){
        var tb=$('#prs').val();
		var pakai_ppn=($('#ppne').is(':checked'));
		var ppn=(pakai_ppn==true)?(parseInt(tb)*10)/100:$('#frm3 #ppn').val();
        var nota=($('#frm3 #chkNota').val()=='Y')?$('#frm3 #ppn').val():0;
		var total=(pakai_ppn==true)?parseInt(tb)+parseInt(ppn)+parseInt(nota):parseInt(tb)-parseInt(ppn)+parseInt(nota);
		// $(this).val(format_number(ppn.toFixed(0)));
		$('#frm3 #total_bayar').val(format_number(total))
		$('#jmlbayar').val(total);
		$('#jmlbayar').terbilang({'awalan':'Kembalian :','output_div':'kekata','akhiran':'rupiah'})
    });
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
		}
		
		_simpan_pembayaran();//simpan data pembayaran
/*		_print_struk($('#no_transaksi').val(),$('#tgl_transaksi').val());//print struk pembayaran
*/		})
		
	$('#frm5 #saved-dikredit')
		.click(function(){
		unlock('#ppn,#no_transaksi,#tgl_transaksi');//enable field yang disabled
		_update_jenis_pembayaran();//update jenis pebayaran sesuai dengan cara bayar
		//jika focus out langsung ke button simpan atau ke field lain
		//makan lakukan penyimpanan data di baris terakhir
		if($('#'+$('#aktif').val()+'__nm_barang').val()!=''){
			_simpan_detail($('#aktif').val()); 
		}
		
		_simpan_pembayaran();//simpan data pembayaran
/*		_print_struk($('#no_transaksi').val(),$('#tgl_transaksi').val());//print struk pembayaran
*/		})
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
	/*else{
			$('table#b tr#nontunai').hide();
		}
*/	})
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
			
		}else if(e.keyCode==113){	//button F2
			/*$.post('get_total_trans',{
				'no_trans'	:$('#no_transaksi').val(),
				'table'		:'detail_transaksi',
				'where'		: new Array('no_transaksi','jenis_transaksi'),
				'field'		: new Array($('#no_transaksi').val(),'GI')},
				function(result){
					for (i=1;i<=result;i++){
						$('span#s-'+i).hide();
						$('span#e-'+i).show();	
					}
				})*/	

			$('#nama').val('tranresep');
			$('#pp-tranresep').css({'left':'28%','top':'25%','height':'auto','z-index':'9998'});
			$('#lock').show();
			$('#frm9 #tgl_jual')
				.focus().select()
				.keyup(function(){
					tanggal(this);
				})
				.keypress(function(e){
					if(e.keyCode==13){
						$(this).focusout();
					}
				})
				.focusout(function(){
					$.post('get_no_transaction',{
						'tanggal' :$(this).val()
					},function(result){
						$('#nomor_slip').html(result);
					})
				})
				//.dynDateTime();
			$('#pp-tranresep').show('slow');
			$('#frm9 input#tgl_jual').focus().select();
			
		}else if(e.keyCode==114){// button F3
			return false
		}else if(e.keyCode==115){ //tombol F4 popup komposisi resep show
			jPrompt('Masukan baris yang akan di edit','','Alert',function(r){
				if(r){
					_non_aktifkan(r,false);
				}
			})
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
					keluar_viewstock();
				break;
				case 'pembayaran':
					$('#frm3 input:not(:button)').val('');
					$('#kekata').html('');
					keluar_pembayaran();
				break;
				case 'editline':
				keluar();
				break;
				case 'canceltrans':
					//$('#tbl-canceltrans table#ListTable tbody').html('');
					keluar_canceltrans();
				break;
				case '':
					$('#frm5 input:not(:button)').val('');
					$('#kekata').html('');
					keluar();
				break;
				case 'tranresep':
				    keluar_tranresep();
				break;
			}
				
		}
	})
 //re print slip
  $('#frm9 #saved-prtslip').click(function(){
	/*$.post('print_slip_kecil',{
		'tanggal'		:$('#frm9 #tgl_jual').val(),
		'no_transaksi'	:$('#nomor_slip').val()
	},function(result){
		//alert(result);
		re_print();
		keluar_tranresep();
	})*/
	_print_struk($('#nomor_slip').val(),$('#frm9 #tgl_jual').val());
	keluar_tranresep();
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
			'no_trans'		:$.trim($('#no_transaksi').val()),
			'tanggal'		:$('#tgl_transaksi').val(),
			'faktur'		:$('#faktur_transaksi').val(),
			'member'		:$('#id_member').val(),
			'total'			:to_number($(frm+' #total_bayar').val()),
			'cicilan'		:$(frm+' #cicilan').val(),
			'cbayar'		:$('#cbayare').val(),
			'lokasi'	:$('#id_lok').val()	,
		    'deskripsi':$('#nm_nasabah').val(),
			'level'		:'GI'+$('#aktif_user').val()
		},function(result){
		lock('#no_transaksi,#tgl_transaksi')
			if($('#aktif_user').val()<=2) unlock('#tgl_transaksi');
		})
	}
	//simpan detail transaksi dalam satu baris
	function _simpan_detail(id){
		unlock('#bayar');
		unlock('#no_transaksi,#tgl_transaksi')
		$.post('CekDetailTrans',{
			'no_trans':$.trim($('#no_transaksi').val()),
			'nm_barang':$('#'+id+'__nm_barang').val(),
			'jml_trans':$('#'+id+'__jml_transaksi').val()
			},function(rst)	{
			 
			 if($.trim(rst)=='0'){
				$.post('set_detail_trans',{
					'no_trans'		:$.trim($('#no_transaksi').val()),
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
					'id_post'		:($('#ppne').is(':checked'))?'1':'0',
					'ID'			: $.trim(rst)
				},function(result){
					lock('#no_transaksi,#tgl_transaksi')
					if($('#aktif_user').val()<=2) unlock('#tgl_transaksi');
					//$('#stat_sim').val(id+'-'+result);
					if($.trim(result)==1)
					$('#brssimpan').val(parseInt($('#aktif').val()-1));
					total_harga();
				})
             }
			});
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
			'lokasi'	:$('#id_lok').val(),
            'deskripsi':$('#nm_nasabah').val(),
			'level'		:'GI'+$('#aktif_user').val()
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
			'kembalian'		:(kembalian.replace(',','')),
			'terbilang'		:terbilang,
			'cbayar'		:jenis_bayar,
			'tanggal'		:$('#tgl_transaksi').val(),
            'hutang'        :$(frm+' #nota').val(),
            'nota'          :($(frm+' #chkNota').val()=='Y')?(kembalian.replace(',','')):$(frm+' #nota').val(),
            'nasabah'       :$('#nm_nasabah').val()
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
			/*if(parseInt(result)<0){
				_update_stock($('#no_transaksi').val(),$('#tgl_transaksi').val(),result);
			}else{*/
				_print_struk(id,tgl);//print struk pembayaran
			//}
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
		$.post('print_slip_kecil',{
			'no_transaksi':id,
			'tanggal'	  :tgl,
			'lokasi'	  :$('#id_lok').val()},
			function(result){
				//buka_wind($.trim(result));
				/*jConfirm('Print Struk Pembelian','Alert',function(r){
					if(r){*/
						//otomatis print
						$.post('re_print',{'id':''},
						function(result){
							$('#result').show().html(result).fadeOut(10000);
							document.location.href=path+'penjualan/index';
						})
					/*}else{
					document.location.href=path+'penjualan/index';
					}
				})*/
			})
	}
	//membuat nomor transaksi otomatis berdasarkan jenis transaksi
	function _generate_nomor(tipe,field){
		$.post('nomor_transaksi',{'tipe':tipe,'level':$('#aktif_user').val()},
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
*/	var n_tran=$('#frm1 input#no_transaksi').val().substr(5,5);
		var tahun=new Date()
		$(field).val(n_tran+'-'+tahun.getFullYear()+tahun.getMonth())
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
		//cek data dulu
		$.post('CekDetailTrans',{
			'no_trans':'',
			'nm_barang':'',
			'jml_trans':''
			},function(result)
			{
			 var r=$.parseJSON(Result);
			 return r.ID;
			 });
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
	
	function _non_aktifkan(id,stat)
	{
		if(stat==true)
		{
			$('#frm2 input#'+id+'__nm_barang').attr('readonly','readonly')
			$('#frm2 input#'+id+'__nm_satuan').attr('readonly','readonly')
			$('#frm2 input#'+id+'__jml_transaksi').attr('readonly','readonly')
			$('#frm2 input#'+id+'__harga_jual').attr('readonly','readonly')
			$('#frm2 input#'+id+'__harga_total').attr('readonly','readonly')
		}else{
			$('#frm2 input#'+id+'__nm_barang').removeAttr('readonly')
			$('#frm2 input#'+id+'__nm_satuan').removeAttr('readonly')
			$('#frm2 input#'+id+'__jml_transaksi').removeAttr('readonly').focus().select();
			$('#frm2 input#'+id+'__harga_jual').removeAttr('readonly')
			$('#frm2 input#'+id+'__harga_total').removeAttr('readonly')
		}
	}
	
	function getBarang(BarisAktif,path)
	{
		lock('#bayar');
		$.post('get_detail_barang',{'id':$('#frm2 input#'+BarisAktif+'__nm_barang').val()},
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
                $('#isLpg').val(r.Jenis);
				$('#brssimpan').val(BarisAktif);
				
				$('#frm2 input#'+BarisAktif+'__nm_barang').val(r.Nama_Barang);
				$('#frm2 input#'+BarisAktif+'__nm_satuan').val(r.Satuan)
				//tentuakn harga base on group nasabah
				if($('#grp_nasabah').val()=='Harga_Jual' ||$('#grp_nasabah').val()=='' )
				{
					$('#frm2 input#'+BarisAktif+'__harga_jual').val(r.Harga_Jual)
					$('#'+BarisAktif+'__harga_total').val(r.Harga_Jual);
				}
				else if($('#grp_nasabah').val()== 'Harga_Cabang')
				{
					$('#frm2 input#'+BarisAktif+'__harga_jual').val(r.Harga_Cabang)
					$('#'+BarisAktif+'__harga_total').val(r.Harga_Cabang);
				}
				else if($('#grp_nasabah').val()=='Harga_Partai')
				{
					$('#frm2 input#'+BarisAktif+'__harga_jual').val(r.Harga_Partai)
					$('#'+BarisAktif+'__harga_total').val(r.Harga_Partai);
					
				}
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
                        if(r.locked=='Y' && jm.stock<=0)
                        {
                            jAlert('Stok barang sudah kosong, tidak bisa di transaksi\nSilahkan Update stock nya dahulu');
                            _kosongkan_field(BarisAktif);
                        }
                                   
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
		total_harga();	
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
    function _get_detailNasabah(id)
    {
     $.post(path+'member/get_member_detail',{'id':id},function(result){
         var r=$.parseJSON(result);
         $('#grp_nasabah').val(r.NIP).select();
         $('#maxlpg').val(r.ID_Kelamin);
         $('#lpgMember').val(r.ID_Check);
         $('#nm_nasabah').val((r.Nama!='')?r.Nama+' - '+r.Alamat:'');
     });
    }
    
    function MaxLPG(BarisAktif){
        var jml=$('#frm2 input#'+BarisAktif+'__jml_transaksi').val();
        var maxlpg=$('#maxlpg').val();
        if($('#isLpg').val()=='LPG' && $('#lpgMember').val()=='Y'){
            if(jml > parseInt(maxlpg)){
                alert('Maximal pembelian hanya '+maxlpg);
                $('#frm2 input#'+BarisAktif+'__jml_transaksi').focus().select();
                return;
            }else{
                $('#frm2 input#'+BarisAktif+'__harga_jual').focus().select();
            }
        }else{
            $('#frm2 input#'+BarisAktif+'__harga_jual').focus().select();
        }
    }
	function _get_nota(nasabah){
        $.post('hutangpelanggan',{'nm_nasabah':nasabah},function(result){
            $('#frm3 #nota').val(to_number(result));
        });
    }
	
	