// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
    $('#listkaryawan').removeClass('tab_button');
	$('#listkaryawan').addClass('tab_select');
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
	$('#tglMasuk').dynDateTime();
	//button OK pressed
	$('#ok').click(function(){
		_show_data();
	})
	$('#userlok').change(function(){_show_data();});
	$('#status').change(function(){_show_data();});
	//add new pressed
	$('#addkaryawan').click(function(){
		$('#Nama').removeAttr('readonly');
		$('#ID').val('');
		$('#tglKeluar').attr('disabled','disabled');
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
		$('#Lokasi').val($('#lok').val()).select()
	}
	
}

function _show_data()
{
	show_indicator('ListTable',9)
	$.post('get_list_karyawan',$('#frm1').serialize(),function(result){
		$('table#ListTable tbody').html(result)
		$('table#ListTable').fixedHeader({'width':(screen.width-50),'height':(screen.height-355)})
	})
}

function _simpan_data()
{
	if($('#Nama').val()!=''){
		$.post('set_karyawan',$('#frm3').serialize(),
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
		$.post('get_detail_karyawan',{'id':id},
		function(result){
			var r=$.parseJSON(result);
			$('#frm3 #ID').val(r.ID);
			$('#frm3 #NIP').val(r.NIP);
			$('#frm3 #Nama').val(r.Nama);
			$('#frm3 #Lokasi').val(r.Lokasi).select();
			$('#frm3 #ID_Kelamin').val(r.JenisKelamin).select();
			$('#frm3 #Nama').attr('readonly','readonly');
			$('#frm3 #tglMasuk').val(tglFromSql(r.TglMasuk));
			$('#frm3 #tglKeluar').removeAttr('disabled');
			$('#frm3 #tglKeluar').val(tglFromSql(r.TglKeluar));
			$('#frm3 #tglKeluar').dynDateTime();
			$('#frm3 #Gaji').val(r.Gaji);
			$('#frm3 #NoHP').val(r.NoHP);
			$('#frm3 #StatKaryawan').val(r.StatKaryawan).select();
			
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
		jConfirm('Yakin nama karyawan ini akan dihapus?','Warning',function(r){
			if(r){
				$.post('del_karyawan',{'id':id},function(result){
					_show_data();
				})
			}
		})
	}
}