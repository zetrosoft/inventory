// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
    $('#kasbon').removeClass('tab_button');
	$('#kasbon').addClass('tab_select');
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
	
	$('#ok').click(function(){
		_show_data();
	})
	$('#prn').click(function(){_print_rekap();})
})
function _show_data()
{
	show_indicator('ListTable',9)
	$.post('get_gajian',$('#frm1').serialize(),function(result){
		$('div#lstGaji').html(result)
		$('div#lstGaji').attr({'width':(screen.width-30),'height':(screen.height-355)})
	})
}

function _print_rekap()
{
    var path=$('#path').val().replace('index.php/','');
    $('#imgs').html("Data being process, please wait....<img src='"+path+"asset/img/indicator.gif'>");
	$('#result').html("Data being process, please wait....").show().fadeOut(10000)
	$.post('get_gajian',$('#frm1').serialize()+'&rkp=y',
	function (result){
		$('#prv').attr('src',result);
		$('#tbl-print_prev').css({'height':(screen.availHeight-200),'width':(screen.availWidth-210)});
		$('#pp-print_prev')
			.css({'top':'8%','left':'8%','width':(screen.availWidth-200)})
			.show('slow')
		$('#lock').show();
        $('#imgs').html('');
	})
}
function prints(id)
{
    var path=$('#path').val().replace('index.php/','');
    $('#imgs').html("Data being process, please wait....<img src='"+path+"asset/img/indicator.gif'>");
    $('#result').html("Data being process, please wait....").show().fadeOut(10000)
	//buka_wind(id);
	$.post('gajipersonal',$('#frm1').serialize()+'&id='+id,
	function (result){
		$('#prv').attr('src',result);
		$('#tbl-print_prev').css({'height':(screen.availHeight-200),'width':(screen.availWidth-210)});
		$('#pp-print_prev')
			.css({'top':'8%','left':'8%','width':(screen.availWidth-200)})
			.show('slow')
		$('#lock').show();
        $('#imgs').html('');
	})
}
