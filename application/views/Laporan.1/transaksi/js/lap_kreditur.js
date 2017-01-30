// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
    $('#rekappenjualankredit').removeClass('tab_button');
	$('#rekappenjualankredit').addClass('tab_select');
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
	$('#frm1 #dari_tgl')
		.click(function(){
			$(this).focus().select();
		})
		.keyup(function(){
			tanggal(this)
		})
		.keypress(function(e){
			if(e.keyCode==13){
				$('#frm1 #sampai_tgl').focus().select();
			}
		})
	$('#frm1 #sampai_tgl')
		.click(function(){
			$(this).focus().select();
			tglNow('#frm1 #sampai_tgl');
		})
		.keyup(function(){
			tanggal(this)
		})
		.keypress(function(e){
			if(e.keyCode==13){
				$('#frm1 #nm_produsen').focus().select();
			}
		})
	$('#okelah').click(function(){
		$('#frm1').attr('action','lap_kreditur');
		document.frm1.submit();
	})
})