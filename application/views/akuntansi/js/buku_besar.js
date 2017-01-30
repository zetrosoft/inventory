// JavaScript Document 
/*
	Script untuk menangani form pada file buku_besar.php, menggunakan controller akuntansi //buku besar
*/
$(document).ready(function(e) {
	$(':radio#new').attr('checked','checked');
	$('div#addnew').show();
	$('div#addcontent').hide();
	$('#tgl_start').dynDateTime();
	$('#tgl_stop').dynDateTime();
	lock('#tgl_start,#tgl_stop,#tahun')
	lock('#ID_Klas,#ID_SubKlas,#ID_Dept,#ID_Agt')
	_get_tahun();
/* -----------penanganan tab---------------
	tab yang aktif saat pertama halaman di buka
*/
	var prs=$('#prs').val();
		$('#bukubesar').removeClass('tab_button');
		$('#bukubesar').addClass('tab_select');
		$('.plt').hide();
		lock('#process');
// proses ketika tab di klik
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
/* ---end penanganan tab---*/
// filter by periode
	$('input:radio[name="periode"]').click(function(){
		var id=$(this).attr('id');
		$('#filper').val(id)
			switch (id){
				case 'pertgl':
					lock('#tahun');
					tglNow('#tgl_start');
					$('#tgl_stop').val('');
					unlock('#tgl_start,#tgl_stop');
					unlock('#ID_Klas,#ID_SubKlas,#ID_Dept,#ID_Agt')
					$('table#ListTable tbody').html('')
					$('div#thn').hide();
					$('div#tgl').show();
				break;
				case 'pertahun':
					unlock('#tahun');
					lock('#tgl_start,#tgl_stop');
					unlock('#ID_Klas,#ID_SubKlas,#ID_Dept,#ID_Agt')
					$('#tgl_start').val('');
					$('#tgl_stop').val('');
					$('table#bbTahunan tbody').html('')
					$('div#tgl').hide();
					$('div#thn').show();
				break;
			}
	})

//filter by others
	$('#ID_Klas').change(function(){
		//menampilkan sub klas berdasarkan kode klass
		   $.post('dropdown_subklas',{'ID_Klas':$(this).val()},
		   function(result){
			   $('#ID_SubKlas').html(result);
				$('#ID_Dept').val('').select();
				$('#ID_Agt').val('').select();
				$('table#ListTable tbody').html('')
		   })
	})
	$('#ID_SubKlas').change(function(){
		$('#ID_Dept').val('').select();
		$('#ID_Agt').val('').select();
		$('table#ListTable tbody').html('')
	})
	$('#ID_Dept').change(function(){
		//menampilkan data perkiraan berdasarkan id_klas dan id_subklas
		   $.post('dropdown_agt',{
			   'ID_Dept'	:$(this).val(),
			   'ID_SubKlas'	:$('#ID_SubKlas').val(),
			   'ID_Klas'	:$('#ID_Klas').val()},
		   function(result){
			   $('#ID_Agt').html(result);
			   $('table#ListTable tbody').html('')
		   })
	})
	$('#ID_Agt').change(function(){
		if($('#filper').val()=='pertgl'){
			_show_data('tgl');
		}else{
			_show_data('tahun');
		}
		unlock('#ok');
	})
	
	$('#oke').click(function(){
		if($('#ID_Agt').val()!=null){
			if($('#filper').val()=='pertgl'){
				_show_data('tgl');
			}else{
				_show_data('tahun');
			}
		}
	})
	
})
function cek_attr(){
 if($('#ID_Klas').val()		!='' && 
 	$('#ID_SubKlas').val()	!='' && 
	$('#ID_Dept').val		!='' && 
	$('#ID_Agt').val()		!=''){
		return true }else{ return false
		}
		
}
function _show_data(jenis){
	//menampiklan data buku besar berdasarkab kriteria / filter yang di pilih
	var akun=$('#ID_Klas').val()+
 			 $('#ID_SubKlas').val()+
			 $('#ID_Dept').val()+
			 $('#ID_Agt').val()
	if(jenis=='tgl'){		 
		$.post('get_buku_besar',{
			'Akun'	:akun,
			'ID_SubKlas':$('#ID_SubKlas').val(),
			'ID_P'	:$('#ID_Agt').val(),
			'Start'	:$('#tgl_start').val(),
			'Stop'	:$("#tgl_stop").val()},
		function(result){
			$('table#ListTable tbody').html(result);
			$('table#ListTable').fixedHeader({width:(screen.width-30),height:(screen.height-335)})
		})
	}
	if(jenis=='tahun'){
		$.post('get_bukubesar_tahunan',{
			'ID_SubKlas':$('#ID_SubKlas').val(),
			'ID_P'	:$('#ID_Agt').val(),
			'Tahun'	:$('#tahun').val()},
			function(result){
				$('table#bbTahunan tbody').html(result);
				$('table#bbTahunan').fixedHeader({width:(screen.width-30),height:(screen.height-335)})
			})
	}
}
function _get_tahun(){
	$.post('dropdown_tahun',{'ID':''},
	function(result){
		$('#tahun').html(result);
	})
}