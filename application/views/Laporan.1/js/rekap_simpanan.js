// JavaScript Document
//tab button event
$(document).ready(function(e){
	$('#rekapsimpanan').removeClass('tab_button');
	$('#rekapsimpanan').addClass('tab_select');
	
	$('table#panel tr td:not(.flt,.plt)').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
	})
	$('#nm_agt')
		.coolautosuggest({
		url				:'get_anggota?limit=8&str=',
		width			:290,
		showThumbnail	:false,
		showDescription	:true,
			onSelected:function(result){
				$('table#panel tr td#kosong').html(result.description);
				$.post('laporan/simpanan',{'ID_Agt':result.ID_Agt},
				function(result){
					
				})
			}
		})
	//initialize data
	_show_data();
   
  // $('table#ListTable').fixedHeader({width:(screen.width-100),height:(screen.height-283)})
	$('#cetak').click(function(){
		$('#frm1').attr('action','print_lap_pdf');
		document.frm1.submit();
	})
})

function _show_data(){
	var path=$('#path').val().replace('index.php/','');
	$('table#ListTable tbody')
		.html("<tr><td colspan='6' class='kotak'><img src='"+path+"asset/img/indicator.gif'>&nbsp;Data being proccessed,please wait...!</td></tr>")
		$.post('get_data_simpanan',{'dept':'2'},
		function(result){
			$('table#ListTable tbody').html(result);	
			$('table#ListTable').fixedHeader({width:(screen.width-100),height:(screen.height-283)})		
			//ajax_stop();
		})
}
	