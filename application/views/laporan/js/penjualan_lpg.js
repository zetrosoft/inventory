//JavaScript Document
var path=$('#path').val();
$(document).ready(function(e) {
    $('#listtransaksilpg').removeClass('tab_button');
	$('#listtransaksilpg').addClass('tab_select');
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
   _get_tahun(); 
   _get_pangkalan();
  // _get_pemasok();
    $('#ok').click(function(){
        _ListPemasok();
    });
    $('#prnt').click(function(){
       _viewpdf();
    })
	$('#exp').click(function(){
		_excport('1');
	})
})
function _get_tahun()
{
    $.post('tahunlpg',{'id':''},function(result){
        $('#tahun').html(result);
    });
}
function _get_pangkalan()
{
    $.post('pangkalan',{'id':''},function(result){
        $('#pangkalan').html(result);
    });
}
function _get_pemasok()
{
    $.post('pemasokLpg',{'id':''},function(result){
        $('#pemasok').html(result);
    });
}
function _ListPemasok()
{
    show_indicator('newTable',16);
    $.post('ListFromPemasok',$('#frm1').serialize(),function(result){
        $('#newTable tbody').html(result);
        //$('#newTable tbody').css({'width':(screen.width-40)});
        $('div#xxd').css({'width':(screen.width-40),'height':430,'overflow-x':'hidden'});
        prints('1');
    })
}
function prints(id)
{
    var path=$('#path').val().replace('index.php/','');
    $('#imgs').html("Data being process, please wait....<img src='"+path+"asset/img/indicator.gif'>");
    $('#result').html("Data being process, please wait....").show().fadeOut(10000)
	//buka_wind(id);
	$.post('PrintLaporanLpg',$('#frm1').serialize()+'&id='+id,
	function (result){
		/*$('#prv').attr('src',result);
		$('#tbl-print_prev').css({'height':(screen.availHeight-200),'width':(screen.availWidth-210)});
		$('#pp-print_prev')
			.css({'top':'8%','left':'8%','width':(screen.availWidth-200)})
			.show('slow')
		$('#lock').show();
        $('#imgs').html('');*/
	})
    
}
function _excport(id)
{
	$.post('PrintLaporanLpgExcel',$('#frm1').serialize()+'&id='+id,
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
function _viewpdf()
{
    $.post('view_pdf',$('#frm1').serialize(),
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