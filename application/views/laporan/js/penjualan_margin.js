var path=$('#path').val();
$(document).ready(function(e) {
    $('#marginpenjualan').removeClass('tab_button');
	$('#marginpenjualan').addClass('tab_select');
	$('table#panel tr td:not(.flt,.plt)').click(function(){
		var id=$(this).attr('id');
        
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
	});
    $('#ok').click(function(){ $('#cari').val('');  _show_data();});
    $('#go').click(function(){ if($('#cari').val()!=''){  _show_data();}});
    $('#frm_tgl').dynDateTime();
    $('#to_tgl').dynDateTime();
    tglNow('#frm_tgl');tglNow('#to_tgl');
    $('#toEx').click(function(){
        $.post('exportToExcel',$('#frm1').serialize(),
        function(result)
        {

        });
    });
})

function _show_data()
{
	show_indicator('ListTable',12);
	$.post('get_margin_jual',$('#frm1').serialize(),
	function(result)
	{
		$('#ListTable tbody').html(result);
        $('table#ListTable').fixedHeader({'width':(screen.width-40),'height':420});
	})
}
function _get_kategory()
{
    
}