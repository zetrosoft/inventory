// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
    $('#kasbon').removeClass('tab_button');
	$('#kasbon').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			$('#'+id).removeClass('tab_button');
			$('#'+id).addClass('tab_select');
			$('table#panel tr td:not(#'+id+')').removeClass('tab_select');
			$('table#panel tr td:not(#'+id+',#kosong)').addClass('tab_button');
			$('span#v_'+id).show();
			$('span:not(#v_'+id+')').hide();
			$('#prs').val(id);
	})
	
	$('#TglKasbon').dynDateTime();
	_show_data();
	//button OK pressed
	$('#ok').click(function(){
		_show_data();
	})
	$('#addkasbon').click(function(){
		$('#Nama').removeAttr('readonly');
		$('#ID').val('');
		$(':reset').click();
		_show_popup('')
	})
	//simpan karyawan baru
	$('#saved-person').click(function(){
		_simpan_data();
	})
})

function _show_popup(id)
{
	$('#pp-addnew')
		.css({'top':'20%','left':'20%'})
		.show('slow')
	$('#lock').show();

	if(id!=''){
		 _get_detail_karyawan(id);
	}else{
		$('#Nama').removeAttr('readonly');
		tglNow('#TglKasbon');
	}
	
}

function _show_data()
{
	show_indicator('ListTable',9)
	$.post('get_list_kasbon',$('#frm1').serialize(),function(result){
		$('table#ListTable tbody').html(result)
		$('table#ListTable').fixedHeader({'width':(screen.width-50),'height':(screen.height-355)})
	})
}

function _simpan_data()
{
	if($('#Nama').val()!=''){
		$.post('set_kasbon',$('#frm3').serialize(),
		function(result){
			$(':reset').click()
			keluar_addnew()
			_show_data();
		})
	}else{
		jAlert('Field Nama Wajib di isi');
	}
}
function _get_detail_karyawan(id)
{
		$.post('get_detail_kasbon',{'id':id},
		function(result){
			var r=$.parseJSON(result);
			$('#frm3 #ID').val(r.ID);
			$('#frm3 #Nama').val(r.ID_Kar).select();
			$('#frm3 #Jumlah').val(r.Jumlah);
			$('#frm3 #ket').val(r.Keterangan);
			$('#frm3 #TglKasbon').val(tglFromSql(r.Tanggal));
			
		})
}

function images_click(id,aksi)
{
	switch(aksi)
	{
		case 'edit':
		_show_popup(id);
		break;
		case 'del':
		jConfirm('Yakin nama karyawan ini kasbon akan dihapus?','Warning',function(r){
			if(r){
				$.post('del_kkasbon',{'id':id},function(result){
					_show_data();
				})
			}
		})
	}
}