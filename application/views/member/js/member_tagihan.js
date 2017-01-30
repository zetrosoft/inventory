// JavaScript Document
$(document).ready(function(e) {
    var path=$('#path').val();
	$('#listtagihan').removeClass('tab_button');
	$('#listtagihan').addClass('tab_select');
	var tday=new Date;
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
	$('table#ListTable').hide();
	$('#okelah').click(function(){
		_show_data(false);
	})
	$('#carilah').click(function(){
		_show_data(true);
	})
	$('#prt').click(function(){
		$('#frm1').attr('action',path+'member/get_member_kredit_print');
		document.frm1.submit();
	})
});


function _show_data(cari){
var path=$('#path').val();
   if(cari==false){
	$.post(path+'member/get_member_kredit',{
		'status'	:$('#stat_tag').val(),
		'orderby'	:$('#orderby').val(),
		'urutan'	:$('#urutan').val()
	},function(result){
		$('table#ListTable').show();
		$('table#ListTable tbody').html(result);
		$('table#ListTable').fixedHeader({width:(screen.width-100),height:(screen.height-372)});
	})
   }else if(cari==true){
	$.post(path+'member/get_member_kredit',{
		'cari'		:$('#cariya').val(),
		'orderby'	:$('#orderby').val(),
		'urutan'	:$('#urutan').val(),
		'status'	:''
	},function(result){
		$('table#ListTable').show();
		$('table#ListTable tbody').html(result);
		$('table#ListTable').fixedHeader({width:(screen.width-100),height:(screen.height-372)});
	})
   }
}

function images_click(id){
var path=$('#path').val();
	//var h=id.split('-')
jConfirm('Yakin data ini akan dihapus','Message',function(r){
	if(r){
		$.post(path+'simpanan/hapus_tagihan',{'id':id,'thn':'2015'},
		 function(result){
			_show_data(false); 
		 })
	}
})
}