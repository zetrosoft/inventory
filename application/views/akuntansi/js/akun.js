// JavaScript Document
$(document).ready(function(e) {
    $('#perkiraan').removeClass('tab_button');
    $('#perkiraan').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
				if(id=='addperkiraan'){
					$('#frm1 :reset').click();
				}
	})
	unlock('select')
	_show_data(''); //generate data klasifikasi
	$('#klas').html("<option value=''>Semua</option>");
	$('#subklas').html("<option value=''>Semua</option>");
	$('#unit_jurnal').html("<option value=''>Semua</option>");
	fill_dropdown('#klas','Klasifikasi','ID','Kode+Klasifikasi','');
	fill_dropdown('#unit_jurnal','unit_jurnal','ID','Kode+Unit','');
	//event select filter dipilih
	$('#klas').change(function(){
		$('#subklas').html("<option>Semua</option>");
		fill_dropdown('#subklas','Sub_Klasifikasi','ID','Kode+SubKlasifikasi',"where ID_Klasifikasi='"+$(this).val()+"' order by Kode");
		var klas	=$(this).val();
		var sklas	=$('#subklas').val()
		var unit_j	=$('#unit_jurnal').val();
		var filter	=klas+','+sklas+','+unit_j
		_show_data(filter)
	})
	$('#subklas').change(function(){
		var klas	=$('#klas').val();
		var sklas	=$('#subklas').val()
		var unit_j	=$('#unit_jurnal').val();
		var filter	=klas+','+sklas+','+unit_j
		_show_data(filter)
	})
	$('#unit_jurnal').change(function(){
		$('#subklas').change();
	})
	$('#frm2 :reset').click(function(){
		keluar();
	})
	$('#frm1 :reset').click(function(){
		$('#frm1 #addData').html('');
	})
	//form add perkiraan
	$('#frm1 #Kode').attr('readonly','readonly');
	$('#frm1 #ID_Klas').change(function(){
		$('#frm1 #ID_SubKlas').html('<option></option>');
		fill_dropdown('#frm1 #ID_SubKlas','Sub_Klasifikasi','ID','Kode+SubKlasifikasi',"where ID_Klasifikasi='"+$(this).val()+"' order by Kode");
		$('#frm1 #Kode').val($(this).val());
		//kosoangkan semua field di bawahnya
		  $('#frm1 select:not(#ID_Klas)').val('').select()
		  $('#frm1 input:not(:button,:reset)').val('');

	})
	$('#frm1 #ID_Laporan').change(function(){
		$('#frm1 #ID_LapDetail').html('');
		var unite=($('#frm1 #ID_Unit').val()=='')?'':
			($('#frm1 #ID_Unit').val()=='1')?
			" and ID_KBR='"+$('#ID_Unit').val()+"'":
			" and ID_USP='"+$('#ID_Unit').val()+"'";
		fill_dropdown('#frm1 #ID_LapDetail','lap_subjenis','ID','NoUrut+SubJenis',"where ID_Lap='"+$(this).val()+"'"+unite+' order by NoUrut');
	})
	$('#frm1 #ID_SubKlas').change(function(){
		var val	=$('#frm1 #ID_SubKlas option:selected').text().split('-')
		var exst=$('#frm1 #Kode').val();
		$('#frm1 #Kode').val(exst.substr(0,1)+$.trim(val[0])+exst.substr(3,(exst.length-3)));
	})
	$('#frm1 #ID_Unit').change(function(){
		var exst=$('#frm1 #Kode').val();
	 	$('#frm1 #Kode').val(exst.substr(0,3)+$(this).val()+'00'+exst.substr(6,(exst.length-6)));
	})
	$('#frm1 #ID_LapDetail').change(function(){
		var ID_Klas		=$('#frm1 #ID_Klas').val();
		var ID_SubKlas	=$('#frm1 #ID_SubKlas').val();
		var ID_Unit		=$('#frm1 #ID_Unit').val();
		var ID_Laporan	=$('#frm1 #ID_Laporan').val();
		var ID_LapDetail=$(this).val();
		$.post('get_nomor_akun',{
			'ID_Klas'	:ID_Klas,
			'ID_SubKlas':ID_SubKlas,
			'ID_Unit'	:ID_Unit,
			'ID_Laporan':ID_Laporan,
			'ID_LapDetail':ID_LapDetail
		},function(result){
			var rst=$.parseJSON(result)
			var exst=$('#frm1 #Kode').val();
			$('#frm1 #ID_Calc').val(rst.ID_Calc);
			$('#frm1 #Kode').val(exst.substr(0,6)+rst.Kode);
			$('#frm1 #Perkiraan').val(rst.Perkiraan)
			$('#frm1 #SaldoAwal').val(rst.SaldoAwal)
		})
	})
	
	$('#frm1 #saved-perkiraan').click(function(){
		var ID_Klas		=$('#frm1 #ID_Klas').val();
		var ID_SubKlas	=$('#frm1 #ID_SubKlas').val();
		var ID_Unit		=$('#frm1 #ID_Unit').val();
		var ID_Laporan	=$('#frm1 #ID_Laporan').val();
		var ID_LapDetail=$('#frm1 #ID_LapDetail').val();
		var ID_Calc		=$('#frm1 #ID_Calc').val();
		var Kode		=$('#frm1 #Kode').val();
		var Perkiraan	=$('#frm1 #Perkiraan').val();
		var SaldoAwal	=$('#frm1 #SaldoAwal').val();
		$.post('set_akun',{
			'ID_Klas'	:ID_Klas,
			'ID_SubKlas':ID_SubKlas,
			'ID_Unit'	:ID_Unit,
			'ID_Laporan':ID_Laporan,
			'ID_LapDetail':ID_LapDetail,
			'ID_Calc'	:ID_Calc,
			'Kode'		:Kode,
			'Perkiraan'	:Perkiraan,
			'SaldoAwal'	:SaldoAwal
		},function(result){
			if($.trim(result)=='1'){
				$('#frm1 :reset').click();
				
			}
		})
	})
	//button update in popup window
	$('#frm2 #saved-edit_akun').click(function(){
		unlock('select');
		var ID_Klas		=$('#frm2 #ID_Klas').val();
		var ID_SubKlas	=$('#frm2 #ID_SubKlas').val();
		var ID_Unit		=$('#frm2 #ID_Unit').val();
		var ID_Laporan	=$('#frm2 #ID_Laporan').val();
		var ID_LapDetail=$('#frm2 #ID_LapDetail').val();
		var ID_Calc		=$('#frm2 #ID_Calc').val();
		var Kode		=$('#frm2 #Kode').val();
		var Perkiraan	=$('#frm2 #Perkiraan').val();
		var SaldoAwal	=$('#frm2 #SaldoAwal').val();
		$.post('set_akun',{
			'ID_Klas'	:ID_Klas,
			'ID_SubKlas':ID_SubKlas,
			'ID_Unit'	:ID_Unit,
			'ID_Laporan':ID_Laporan,
			'ID_LapDetail':ID_LapDetail,
			'ID_Calc'	:ID_Calc,
			'Kode'		:Kode,
			'Perkiraan'	:Perkiraan,
			'SaldoAwal'	:to_number(SaldoAwal)
		},function(result){
			if($.trim(result)=='1'){
				$('#frm2 :reset').click();
			}
		})
	})
	$('#frm2 input#SaldoAwal')
		.click(function(){
			$(this).focus().select();
		})
		.keyup(function(){
			$(this).terbilang({'output_div':'addData'})
		})
	$('#frm1 input#SaldoAwal')
		.click(function(){
			$(this).focus().select();
		})
		.keyup(function(){
			$(this).terbilang({'output_div':'addData'})
		})

});

function _show_data(filter){
	show_indicator('ListTable','7');
	fils=filter.split(',');
	$.post('get_akun',{
		'klas'		:fils[0],
		'subklas'	:(fils[1]=='Semua')?'':fils[1],
		'unit_j'	:(fils[2]=='Semua')?'':fils[2]},
		function(result){
			$('table#ListTable tbody').html(result);
			$('input:text').val('');
			$('table#ListTable').fixedHeader({width:(screen.width-30),height:(screen.height-340)})
		})
}

function img_onClick(id,tipe){
	switch(tipe){
		case 'edit':
		//get data
		$.post('get_akun_edit',{'ID':id},
		function(result){
			var rst=$.parseJSON(result);
			var unite=(rst.ID_Unit=='')?'':(rst.ID_Unit=='1')?	" and ID_KBR='"+rst.ID_Unit+"'":" and ID_USP='"+rst.ID_Unit+"'";
			fill_dropdown2('#frm2 #ID_SubKlas','Sub_Klasifikasi','ID','Kode+SubKlasifikasi',"where ID_Klasifikasi='"+rst.ID_Klas+"' order by Kode",rst.ID_SubKlas)
			fill_dropdown2('#frm2 #ID_LapDetail','lap_subjenis','ID','NoUrut+SubJenis',"where ID_Lap='"+rst.ID_Laporan+"' "+unite+" order by NoUrut",rst.ID_LapDetail);
				$('div#addData').html('');
				$('#frm2 select#ID_Klas').val(rst.ID_Klas).select();
				$('#frm2 select#ID_Unit').val(rst.ID_Unit).select();
				$('#frm2 select#ID_Laporan').val(rst.ID_Laporan).select();
				$('#frm2 select#ID_Calc').val(rst.ID_Calc).select();
				$('#frm2 input#Kode')
					.val(rst.ID_Klas+rst.sKode+rst.ID_Unit+'00'+rst.Kode)
					.attr('readonly','readonly')
				$('#frm2 input#Perkiraan').val(rst.Perkiraan)
				$('#frm2 input#SaldoAwal').val((rst.SaldoAwal==null)?'0':format_number(rst.SaldoAwal))
				
		})
		//show popup window edit
		lock('#frm2 select');
		$('frm2 #Perkiraan').focus();
		$('#frm2 input#SaldoAwal').terbilang({'output_div':'addData'});
		$('#pp-akun').css({'left':'20%','top':'20%'});
		$('#nama').val('akun');
		$('#lock').show();
		$('#pp-akun').show('slow');
		break;
		case 'del':
		$.post('check_status_akun',{'ID':id},
		function(result){
			if($.trim(result)=='dipakai'){
				alert('Data akun ini tidak bisa di hapus\nSudah dipakai untuk transaksi');
			}else if($.trim(result)=='hapus'){
				if(confirm('Yakin Data Perkiraan ini akan dihapus???')){
					$.post('hapus_akun',{'ID':id,'sumber':'perkiraan'},
					function(result){
						_show_data($('#klas').val()+','+$('#subklas').val()+','+$('#unit_jurnal').val())
						$('table#ListTable').fixedHeader({width:(screen.width-30),height:(screen.height-340)})
					})
				}
			}
		})
		break;
	}
}

function fill_dropdown(obj,tbl,id,val,where){
	$.post('fill_dropdown',{
		'table'	:tbl,
		'id'	:id,
		'val'	:val,
		'where'	:where
	},function(result){
		$(obj).append($.trim(result));
	})
}
function fill_dropdown2(obj,tbl,id,val,where,pilih){
	$.post('fill_dropdown',{
		'table'	:tbl,
		'id'	:id,
		'val'	:val,
		'where'	:where,
		'pilih'	:pilih
	},function(result){
		$(obj).append($.trim(result));
	})
}
