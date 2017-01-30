// JavaScript Document
$(document).ready(function(e) {
    $('#alirankas').removeClass('tab_button');
    $('#alirankas').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
	})
	//tglNow('#dari_tgl')
	$('#dari_tgl').dynDateTime();
	$('#sampai_tgl').dynDateTime();
	
	$('#okelah').click(function(){
		show_indicator('xx',1);
		$('#frm1').attr('action','get_cash_flow');
		document.frm1.submit();
	})
})