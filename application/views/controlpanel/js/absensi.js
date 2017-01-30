// JavaScript Document

$(document).ready(function(e) {
	var path=$('#path').val();
    $('#absensiharian').removeClass('tab_button');
	$('#absensiharian').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			$('#'+id).removeClass('tab_button');
			$('#'+id).addClass('tab_select');
			$('table#panel tr td:not(#'+id+')').removeClass('tab_select');
			$('table#panel tr td:not(#'+id+',#kosong)').addClass('tab_button');
			$('span#v_'+id).show();
			$('span:not(#v_'+id+')').hide();
			$('#prs').val(id);
	})
	//button OK pressed
	$('#ok').click(function(){
		_show_data();
	})
	$('#tgl_absen').dynDateTime();
	$('#tgl_absen').live('change',function(){_show_data();});
	$('input:radio')
		.live('click',function(){
			var id=$(this).attr('id');
			var st=($('input:radio#'+id).is(':checked'))?'Y':'N';
			var fl=($('input:radio#'+id).is(':checked'))?'C':'N';
			var nik=id.split('-')
			$.post('set_absensi',{
				'id_karyawan'	:nik[1],
				'tgl_absen'		:$('#tgl_absen').val(),
				'on_absen'		:(id.substr(0,1)=='r')?st:fl,
				'id_lokasi'		:$('#userlok').val()
			},function(result){
				$('#result')
					.html(result)
					.show('slow')
					.fadeOut(5000)
			})
		})
})


function _show_data()
{
	show_indicator('ListTable',5)
	$.post('get_daily_absen',$('#frm1').serialize(),function(result){
		$('table#ListTable tbody').html(result)
		//$('table#ListTable').fixedHeader({'width':(screen.width-200),'height':(screen.height-325)})
	})
}
