// JavaScript Document
$(document).ready(function(e) {
    var path=$('#path').val();
    $('#stocklimit').removeClass('tab_button');
	$('#stocklimit').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			if(id!=''){
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
			}
	})
	$('#frm1 #prt').click(function(){
		$('#frm1').attr('action','get_stock_limit');
		document.frm1.submit();
	})
})