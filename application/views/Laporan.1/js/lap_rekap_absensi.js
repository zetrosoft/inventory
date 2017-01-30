// JavaScript Document

$(document).ready(function(e) {
	var path=$('#path').val();
    $('#rekapabsensi').removeClass('tab_button');
	$('#rekapabsensi').addClass('tab_select');
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
	 var today= new Date()
	 var b=today.getMonth()+1
	 var y=today.getFullYear()
	  $('#bulan').val(b).select()
	_get_tahun(y);
	
	$('#ok').click(function()
	{
		_show_data();
	})
	//lokasi klik
	$('#id_lokasi').change(function(){
		_show_data();
	})
	$('#bulan').change(function(){
		_show_data();
	})
	$('#tahun').change(function(){
		_show_data();
	})
})

function _show_data()
{	
	show_indicator('newTable',5);
	$.post('get_rekap_list',{
		'id_lokasi'	:$('#id_lokasi').val(),
		'bulan'		:$('#bulan').val(),
		'tahun'		:$('#tahun').val(),
		'detail'	:$('#detail').is(':checked')},
	function(result){
		$('table#newTable tbody').html(result);
		$('table#newTable').fixedHeader({'width':(screen.width-300),'height':(screen.height-350)});	
	})
}

function _get_tahun(id)
{
	$.post('get_tahun',{'id':''},
	function(result)
	{
		$('#tahun')
			.html(result)
			.val(id).select()
	})
}
