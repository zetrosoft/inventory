// JavaScript Document
//tab button event
$(document).ready(function(e){
	
	$('#rekapsimpanan').removeClass('tab_button');
	$('#rekapsimpanan').addClass('tab_select');
	
	$('table#panel tr td:not(.flt,.plt)').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
	})
	_get_tahun();
	$('#cetak').hide()
		$('#tgl_start').dynDateTime();
		//$('input[name="periode"]').attr('checked','checked');
		$('#oke').click(function(){
			var tgl=$('#tgl_start').val();
			_show_datax(tgl);
			
		})
		$('#cetak').click(function(){
		$('#frm_j').attr('action','print_lap_pdf');
		document.frm_j.submit();
	})

})

function _show_datax(periode){
	show_indicator('ListTable','7');
	$.post('get_data_nc_lajur',{
		'tgl_start'	:periode,
		'tgl_stop'	:$('#tgl_stop').val(),
		'id_dept'	:$('#ID_Dept').val(),
		'stat_agt'	:$('#ID_Stat').val(),
		'akun'		:$('#ID_Perkiraan').val()},
	function(result){
		$('table#ListTable tbody').html(result);
		$('table#ListTable').fixedHeader({width:(screen.width-30),height:(screen.height-350)})
		$('#cetak').show();
	})
}
function _get_tahun(){
var path=$('#path').val();
	$.post(path+'akuntansi/dropdown_tahun',{'ID':''},
	function(result){
		$('#tahun').html(result);
	})
}