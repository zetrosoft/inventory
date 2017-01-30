// JavaScript Document
var path=$('#path').val();
$(document).ready(function(e) {
	var prs=$('#prs').val();
	$('table#panel tr td.flt').hide()
    $('#printfakturpenjualan').removeClass('tab_button');
	$('#printfakturpenjualan').addClass('tab_select');
	last_notran();
	$('input:text').focus().select();
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
	});
	$('#saved-faktur').click(function(){
		var n=$('#no_transaksi').val()
		var x=n.split(':');
		$('#result').html('Document being process ...please wait').show().fadeOut(5000)
		_print_struk(x[0],x[1]);
	});
	tglNow('#dari_tgl');
	$('#dari_tgl')
		.focus().select()
		.dynDateTime()
		.focusout(function(){
			last_notran();
		});
		
	$('#sampai_tgl')
		.focusout(function(){
			last_notran();
		})
		.dynDateTime();
	$('#id_anggota')
		.keypress(function(e){
			if(e.which==13){
				last_notran();			
			}
		})
		.focusout(function(){
		 last_notran();
		});
});


function last_notran(){
	$.post('last_no_transaksi',{
		'dari_tgl'	:$('#dari_tgl').val(),
		'sampai_tgl':$('#sampai_tgl').val(),
		'id_anggota':$('#id_anggota').val(),
		'lokasi'	:$('#lok').val()},
		function(result){
			$('#no_transaksi').html(result);
		})
};
function _print_struk(id,tgl){
		$.post(path+'penjualan/print_slip',{
			'no_transaksi':id,
			'tanggal'	  :tgl,
			'lokasi'	  :$('#lok').val()},
			function(result){
				buka_wind($.trim(result));
			})
};
function buka_wind(id)
{
	 window.open("http://localhost/putrisvn/penjualan_slipt.php?userid="+id,
				  "mediumWindow",
				  "width=550,height=225,left="+((screen.width/2)-(550/2))+", top=150" +
				  "menubar=No,scrollbars=No,addressbar=No,status=No,toolbar=No");
};
	
