// JavaScript Document
$(document).ready(function(e) {
	$('#v_listjurnalumum #ListTable').hide();
	$(':radio#new').attr('checked','checked');
	$('div#addnew').show();
	$('div#addcontent').hide();
	var prs=$('#prs').val();
		$('#listjurnalumum').removeClass('tab_button');
		$('#listjurnalumum').addClass('tab_select');
		$('.plt').hide();
		lock('#process');
	$('table#panel tr td:not(.flt,.plt,#kosong)').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+',#bybln,#bytgl,#fltby)').hide();
				if(id=='addjurnal'){
					$('table#panel tr td.plt').hide()
				}else{
					$('table#panel tr td.plt').show()
				}
	})
	$('#fby').val('').select()
	$('#fby').change(function(){
		unlock('#process')
		$('span#fltby').html($('#fby option:selected').text()+' :');
		switch($(this).val()){
			case 'tgl':
				$('#Bln').val('').select();
				$('#Thn').val('').select();
				$('#daritgl').val('');$('#smptgl').val('');
				$('span#bytgl').show();	$('span#bybln').hide();
			break;
			case 'bln':
				$.post('get_bulan',{'id':''},function(result){
						$('#Bln').html(result)
				})
				$.post('get_tahun',{'id':''},function(result){
						$('#Thn').html(result)
				})
				$('span#bybln').show();	$('span#bytgl').hide();
			break;
			case 'all':
				$('#Bln').val('').select();
				$('#Thn').val('').select();
				$('span#bybln').hide();	$('span#bytgl').hide();
			break;
			case '':
				$('#Bln').val('').select();
				$('#Thn').val('').select();
				$('span#bybln').hide();	$('span#bytgl').hide();
				lock('#process');
			break;
		}
	})
	//OK button click
	$('#process').click(function(){
		var path=$('#path').val().replace('index.php/','');
		var ajax="<tr><td class='kotak' colspan='8'>Data being processed, please wait...<img src='"+path+"asset/img/indicator.gif'></td></tr>";
		$('#v_listjurnalumum #ListTable').show();
		$('#v_listjurnalumum #ListTable tbody').html(ajax)
		$.post('get_list_jurnal',{
				'filter'	:$('#fby').val(),
				'daritgl'	:$('#daritgl').val(),
				'smptgl'	:$('#smptgl').val(),
				'Bln'		:$('#Bln').val(),
				'Thn'		:$('#Thn').val(),
				'ID_Unit'	:$('#ID_Unit').val()
			},function(result){
			$('#v_listjurnalumum #ListTable tbody').html(result)
			$('span#ttd').html('Total data :'+$('#v_listjurnalumum #ListTable tbody tr').length);
			$('.plt').show();
			$('table#ListTable').fixedHeader({width:(screen.width-30),height:(screen.height-335)})
			})
	})
	$('#daritgl').dynDateTime();
	$('#smptgl').dynDateTime();
// addjurnal
	$('#v_addjurnal table#addtxt tr td#c1-2').hide();
	$('#v_addjurnal table#addtxt tr td#c2-2').hide();
	$('#noUrut').attr('readonly','readonly')
	$('#Tanggal').dynDateTime();
	$('input:radio[name="pilih"]').click(function(){
		var id=$(this).attr('id');
			if (id=='new'){
				$('#v_addjurnal table#addtxt tr td#c1-2').hide();
				$('#v_addjurnal table#addtxt tr td#c2-2').hide();
				$('#NoJurnal').val('');
				$('div#addnew').show();
				$('div#addcontent').hide();
				$(':reset').click();
					tglNow('#Tanggal');
			}else{
				$('#v_addjurnal table#addtxt tr td#c1-2').show();
				$('#v_addjurnal table#addtxt tr td#c2-2').show();
				$('div#addnew').hide();
				$('div#addcontent').hide();
			}
	})
	$('#frm1 #ID_Unit').change(function(){
		$.post('get_last_jurnal',{'ID_Unit':$(this).val()},
			function(result){
					$('#noUrut').val('GJ-'+result);
					$('#Keterangan').focus();
			})
	})
	$('#NoJurnal')
		.click(function(){
			$('div#addcontent').hide();	
		})
		.coolautosuggest({
				url:'get_no_jurnal?limit=30&str=',
				width:350,
				showDescription	:true,
				onSelected:function(result){
					$.post('get_total_KD',{
						'ID_jurnal'	:result.ID,
						'Tanggal'	:result.Tanggal,
						'NoJurnal'	:result.Nomor,
						'Keterangan':result.description,
						'ID_Unit'	:result.ID_Unit},
					function (data){
						$('div#jdet').html(data);
						$('table#j_det').fixedHeader({width:(screen.width-125),height:60})
					})
					$.post('get_detail_jurnal',{'ID':result.ID,'Tahun':result.Tahun},
					function(hasil){
						$('table#j_content tbody').html(hasil);
						$('div#addcontent').show();	
						$('table#j_content').fixedHeader({width:(screen.width-125),height:(screen.height-450)})
						$('table#bwh').fixedHeader({width:(screen.width-125),height:30})
					});
					if(result.Tahun!=$('#thn').val()){
						lock('#addtrans');
					}else{
						unlock('#addtrans');
					}
				}//
		})
	//add new jurnal
	$('#saved-newjurnal')
		.click(function(){
			$.post('set_jurnal',{
				'Tgl'		:$('#frm1 #Tanggal').val(),
				'ID_Unit'	:$('#frm1 #ID_Unit').val(),
				'nomor'		:$('#frm1 #noUrut').val(),
				'Keterangan':$('#frm1 #Keterangan').val()
			},function(result){
				$('#frm1 :reset').click();
				tglNow('#Tanggal')
			})
		})
	//print to pdf
	$('#cetak').click(function(){
			$('#frm_j').attr('action','print_list_jurnal');
			document.frm_j.submit();
	})
	$('#pp-j_detail #batal').click(function(){
		$('#pp-j_detail').hide('slow');
		$('#lock').hide();
	})
	$('#pp-j_detail #pdf').click(function(){
		$('#frm22').attr('action','print_detail_jurnal');
		document.frm22.submit();
	})
	$('table#bwh #pdf').click(function(){
		$('#frm23').attr('action','print_detail_jurnal');
		document.frm23.submit();
	})
	//add jurnal content
	$('#addtrans').click(function(){
		ajax_start();
		$.post('header_perkiraan',{'id':''},
			function(result){
				$('table#pilihan').html(result)
				process('ID_KBR');
			})
		$.post('add_jurnal_content',{'ID':$('#nj').val(),'Tahun':'','ID_Akun':''},
		function(hasil){
			$('#pp-ad_content').css({'top':'10%','left':'5%','width':(screen.width-150),'height':(screen.height-50)});
			$('table#add_trans tbody').html(hasil);	
			$('#pp-ad_content').show();
			$('table#bwht').fixedHeader({width:(screen.width-200),height:30})
			ajax_stop();
			$('#lock').show();
		})
	})
	//
	$('#jml_bayar')
		.click(function(){
			$.post('total_perjurnal',{'ID_jurnal':$('#ID_Jurnal').val()},
			function(result){
				var hsl=$.parseJSON(result);
				/*($('#ID_Jenis').val()==1)?
				$('#jml_bayar').val(hsl.kredit):*/
				$('#jml_bayar').val(hsl.balance);
				$('#Kete').val('Setoran '+hsl.ket);
			})
		})
		.keyup(function(){
			$('#jml_bayar').terbilang({'output_div':'terbilang'})
			pos_info('#jml_bayar','terbilang');
		})
		.focusout(function(e) {
           $('#terbilang').hide();
        });
})

function show_jurnal_detail(id){
	ajax_start()
	$.post('header_popup',{
	'ID_jurnal'	:id,
	},function (data){
		$('div#jdete').html(data);
	})
	$.post('get_detail_jurnal',{'ID':id,'Tahun':''},
	function(hasil){
		var w=$('#pp-j_detail').width();
		var h=$('#pp-j_detail').height();
		$('#pp-j_detail').css({'top':'15%','left':'8%','width':(screen.width-150),'height':(screen.height-50)});
		$('table#sj_content tbody').html(hasil);	
		$('#pp-j_detail').show();
		$('table#j_dete').fixedHeader({width:(screen.width-186),height:60})
		$('table#sj_content').fixedHeader({width:(screen.width-186),height:(screen.height-450)})
		$('table#bwhe').fixedHeader({width:(screen.width-186),height:30})
			ajax_stop();
			$('#lock').show();
	})
}
function addtojurnal(id,nj,ids){
	var today = new Date();
	$.post('add_to_jurnal',{'id':id,'ID_Jurnal':nj},
	function(result){
		$.post('add_jurnal_content',{'ID':$('#nj').val(),'Tahun':'','ID_Akun':ids},
		function(hasil){
		$('#simp table#add_trans tbody').html(hasil);
		//update tampilan detail jurnal
		total_KD(nj);
			$.post('get_detail_jurnal',{'ID':nj,'Tahun':today.getFullYear()},
					function(result){
						$('table#j_content tbody').html(result);
						//$('div#addcontent').show();	
						$('table#j_content').fixedHeader({width:(screen.width-125),height:(screen.height-450)})
					})
		
		})
	})
}
function hapus_content(id){
	var thn=$('#Tgl').val().split('/');
	$.post('hapus_transaksi',{'ID':id},
		function(result){
			total_KD($('#nj').val());
			$.post('get_detail_jurnal',{'ID':$('#nj').val(),'Tahun':thn[2]},
					function(hasil){
						$('table#j_content tbody').html(hasil);
						$('div#addcontent').show();	
						$('table#j_content').fixedHeader({width:(screen.width-125),height:(screen.height-450)})
						$('table#bwh').fixedHeader({width:(screen.width-125),height:30})
			});
		})
}

function balance_show(){ //show popup balance
	$('#pp-ad_balance').css({'left':'10%','top':'15%','width':(screen.width-250),'height':(screen.height-50)});
	$('#pp-ad_balance').show();
	$('#lock').show();
}
//process popup addcontent jurna
function process(id){
	switch(id){
		case 'ID_KBR':
			$('#frm3 input:reset').click();
			$('#ID_Jurnale')
				.val($('#NoJ').val())
				.attr('readonly','readonly')
			$.post('get_SubJenis',{'ID':'1'},
				function(result){
					$('#ID_Perkiraan').html(result);
				})
			$('div#unt').show();
			$('div#simp').hide();
			$('input[name="simpan_x"]').show();
		break;	
		case 'ID_USP':
			$('#frm3 input:reset').click();
			$('#ID_Jurnale')
				.val($('#NoJ').val())
				.attr('readonly','readonly')
			$.post('get_SubJenis',{'ID':'2'},
				function(result){
					$('#ID_Perkiraan').html(result);
				})
			$('div#unt').show();
			$('div#simp').hide();
			$('input[name="simpan_x"]').show();
		break;	
		case '1':
			$('div#unt').hide();
			$('div#simp').show();
			$.post('add_jurnal_content',{'ID':$('#nj').val(),'ID_Akun':id},
				function(result){
				 $('#simp table#add_trans tbody').html(result);	
				})
			$('input[name="simpan_x"]').hide();
		break;	
		case '2':
			$('div#unt').hide();
			$('div#simp').show();
			$.post('add_jurnal_content',{'ID':$('#nj').val(),'ID_Akun':'2'},
				function(result){
				 $('#simp table#add_trans tbody').html(result);	
				 $('#simp table#add_trans').fixedHeader({width:(screen.width-200),height:(screen.height-435)})
				})
			$('input[name="simpan_x"]').hide();
		break;	
		case '3':
			$('div#unt').hide();
			$('div#simp').show();
			$.post('add_jurnal_content',{'ID':$('#nj').val(),'ID_Akun':'3'},
				function(result){
				 $('#simp table#add_trans tbody').html(result);	
				})
			$('input[name="simpan_x"]').hide();
		break;	
		case '4':
			$('div#unt').hide();
			$('div#simp').show();
			$.post('add_jurnal_content',{'ID':$('#nj').val(),'ID_Akun':'4'},
				function(result){
				 $('#simp table#add_trans tbody').html(result);	
				})
			$('input[name="simpan_x"]').hide();
		break;	
		case '5':
			$('div#unt').hide();
			$('div#simp').show();
			$.post('add_jurnal_content',{'ID':$('#nj').val(),'ID_Akun':'5'},
				function(result){
				 $('#simp table#add_trans tbody').html(result);	
				})
			$('input[name="simpan_x"]').hide();
		break;	
	}
}
function simpan_ad_content(){
	$.post('add_balance_jurnal',{
		'ID_Jurnal'		:$('#ID_Jurnal').val(),
		'ID_Perkiraan'	:$('#ID_Perkiraan').val(),
		'Jml'			:to_number($('#jml_bayar').val()),
		'ID_Jenis'		:$('#ID_Jenis').val(),
		'Keterangan'	:$('#Kete').val()
	},function(result){
		var today = new Date();
		alert($.trim(result));//tampilkan status process
		$(':reset').click();//kosongkan field
		//update list detail jurnal parent windows
		total_KD($('#ID_Jurnal').val());
			$.post('get_detail_jurnal',{'ID':$('#ID_Jurnal').val(),'Tahun':today.getFullYear()},
					function(hasil){
						$('table#j_content tbody').html(hasil);
						//$('div#addcontent').show();	
						$('table#j_content').fixedHeader({width:(screen.width-125),height:(screen.height-450)})
					})
	})
	
}
	
function total_KD(id){
	$.post('get_total_KD',{
		'ID_jurnal'	:id,
		'Tanggal'	:$('#Tgl').val(),
		'NoJurnal'	:$('#NoJ').val(),
		'Keterangan':$('#Ket').val(),
		'ID_Unit'	:$('#unit').val()},
		function (data){
			$('div#jdet').html(data);
			$('table#j_det').fixedHeader({width:(screen.width-125),height:60})
		})
}
	