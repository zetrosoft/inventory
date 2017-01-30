// JavaScript Document
//tab button event
$(document).ready(function(e){
	$('#sisahasilusaha').removeClass('tab_button');
	$('#sisahasilusaha').addClass('tab_select');
	
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
		var d=new Date();
		$('#tgl_start').val('01/01/'+d.getFullYear());
		$('#tgl_stop').val('31/12/'+d.getFullYear());
		$('#tgl_start').dynDateTime();
		$('#tgl_stop').dynDateTime();
		$('#tgl_banding').dynDateTime();
		$('input#n-1').attr('checked','checked');
	$('#pembanding').click(function(){
		($(this).is(':checked'))?
		unlock('#tgl_banding'):lock('#tgl_banding');
	})
	$('#oke').click(function(){
		ajax_start();
		$('#frm_j').attr('action','print_shu');
		document.frm_j.submit();
		ajax_stop();
	})
	$('#tgl_start').bind('change',function(){
		var nex=getNextDate($(this).val(),365,'/');
		$('#tgl_stop').val(nex);
	})
})