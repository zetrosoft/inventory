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
	$('#cetak').hide()
	tglNow('#tgl_start');
	$('#newTable').hide();
		$('#tgl_start').dynDateTime();
		$('input[name="periode"]').attr('checked','checked');
		$('#oke').click(function(){
			var tgl=$('#tgl_start').val();
		_show_data(tgl);
			
		})
		
		$('#cetak').click(function(){
		$('#frm_j').attr('action','print_lap_pdf');
		document.frm_j.submit();
	})

})

function _show_data(periode){
	$('#newTable').show();
	show_indicator('newTable','6');
	$.post('neraca_lajur_data',{'periode':periode},
	function(result){
		$('#frm_j').attr('action','print_lap_pdf');
		document.frm_j.submit();
		/*
		$('table#newTable tbody').html(result);
		$('table#newTable').fixedHeader({width:(screen.width-30),height:(screen.height-335)})
		$('#cetak').show();*/
	})
}