// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
    $('#listmutasistock').removeClass('tab_button');
	$('#listmutasistock').addClass('tab_select');

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
	//laporan penjualan obat
	tglNow('#dari_tgl')
	$('#frm1 #dari_tgl').dynDateTime();
	$('#frm1 #sampai_tgl').dynDateTime();
	
	$('#okelah').click(function(){
		show_indicator('xx',1);
		$('#frm1').attr('action','print_laporan_mutasi');
		document.frm1.submit();
	})
})