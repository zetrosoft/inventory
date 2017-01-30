// JavaScript Document
$(document).ready(function(e) {
    $('#operasionalharian').removeClass('tab_button');
    $('#operasionalharian').addClass('tab_select');
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
	
	$('#okedech').click(function(){
		$('#frm1').attr('action','get_operasional');
		document.frm1.submit();
	})
	if($('#pajak').is(':checked')){
		$('#okedech').val('OK !')
	}else{
		$('#okedech').val('OK');
	}
	$(document).keypress(function(e){
		return false
		if(e.keyCode==115){
			if($('#pajak').is(':checked')){
			$('#pajak').removeAttr('checked');
			$('#okedech').val('OK')
			}else{
			$('#pajak').attr('checked','checked');
			$('#okedech').val('OK !');
			}			
		}
	})

})