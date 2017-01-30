// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
    $('#profile').removeClass('tab_button');
	$('#profile').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			$('#'+id).removeClass('tab_button');
			$('#'+id).addClass('tab_select');
			$('table#panel tr td:not(#'+id+')').removeClass('tab_select');
			$('table#panel tr td:not(#'+id+',#kosong)').addClass('tab_button');
			$('span#v_'+id).show();
			$('span:not(#v_'+id+')').hide();

	});
	($('#cek').val()=='')?$('#saved-company').attr('disabled','disabled'):'';
	_show_data();
	$('#saved-company').click(function(){
		_simpan_data();
	})
})

function _show_data(){
	show_indicator('rst',1);
	$.post('get_profile',{'id':'InfoCo'},
	function(result){
		var hsl=$.parseJSON(result);
		$('#nm_company').val(hsl.Name);
		$('#ad_company').val(hsl.Address);
		$('#ct_company').val(hsl.Kota);
		$('#pr_company').val(hsl.Propinsi);
		$('#po_company').val(hsl.Pobox);
		$('#tl_company').val(hsl.Telp);
		$('#fx_company').val(hsl.Fax);
		$('#rst tbody').html('');
	})
}

function _simpan_data(){
	show_indicator('rst',1);
	$.post('set_profile',{
		'Name'		:$('#nm_company').val(),
		'Address'	:$('#ad_company').val(),
		'Kota'		:$('#ct_company').val(),
		'Propinsi'	:$('#pr_company').val(),
		'Pobox'		:$('#po_company').val(),
		'Telp'		:$('#tl_company').val(),
		'Fax'		:$('#fx_company').val()
	},function(result){
		$('#rst tbody tr td').html(result);
		_show_data();
	})
}
