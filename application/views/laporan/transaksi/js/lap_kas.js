// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
    $('#laporankas').removeClass('tab_button');
	$('#laporankas').addClass('tab_select');

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
	$('#frm1 #dari_tgl').dynDateTime();
	$('#frm1 #sampai_tgl').dynDateTime();
	
	$('#saved-filter').click(function(){
		$('#frm1').attr('action','print_laporan_kas');
		document.frm1.submit();
	})
})