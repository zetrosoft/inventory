// JavaScript Document
//tab button event
$(document).ready(function(e){
	$('#neraca').removeClass('tab_button');
	$('#neraca').addClass('tab_select');
	
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
		lock('#tgl_banding')
		$('#tgl_start').dynDateTime();
		$('#tgl_banding').dynDateTime();
		$('input#n-1').attr('checked','checked');

	$('#pembanding').click(function(){
		if($(this).is(':checked')){
			unlock('#tgl_banding');
			$('#tgl_banding').val('');
		}else{
			$('#tgl_banding').val('');
			lock('#tgl_banding');
		}
		
	})
	$('input:radio[name="unite"]').click(function(){
		$('#lokasi').val($(this).val());
	})
	$('#oke').click(function(){
		//ajax_start();
		if($('#lokasi').val()=='3'){
			$('#frm_j').attr('action','print_neraca_gabungan');	
		}else{
			$('#frm_j').attr('action','print_neraca');
		}
		document.frm_j.submit();/*//*/
		//ajax_stop();
	})
})