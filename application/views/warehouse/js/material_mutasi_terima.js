// JavaScript Document
var path=$('#path').val();
$(document).ready(function(e) {
    var path=$('#path').val();
    $('#penerimaanmutasi').removeClass('tab_button');
	$('#penerimaanmutasi').addClass('tab_select');
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
	$('#okelah').click(function(){
		_show_trans()
	})
		
})

function _show_trans(){
	show_indicator('newTable',7);
	$.post('proses_terima_mutasi',{
		'no_trans':$('#no_trans').val()},
		function(result){
			$('table#newTable tbody').html(result);
			$('table#newTable').fixedHeader({width:(screen.width-30),height:(screen.height-335)});
		})
}

function images_click(id,aksi){
	switch(aksi){
	 case 'pros':
	 var idd=id.split('-');
	 jConfirm('Yakin data ini akan diproses semua??','Confirm',function(r){
		if(r){
			$.post('update_terima_mutasi',{
				'no_trans':idd[0],
				'tanggal'	:idd[1]},
				function(result){
				_show_trans();
			})
		}
	 })
	 break;
	 case 'check':
	 $.alerts.okButton='Sesuai';$.alerts.cancelButton='&nbsp;Tidak&nbsp;';
	 jConfirm('Material ini akan diposting\nPastikan jumlah sudah sesuai','Confirm',function(e){
		if(!e){
	 $.alerts.okButton='OK';$.alerts.cancelButton='&nbsp;CANCEL&nbsp;';
			jPrompt('Masukan Jumlah sesuai dengan aktual penerimaan','Jumlah','Confirm',function(r){
				
			})
		}else{
	 $.alerts.okButton='OK';
	 $.alerts.cancelButton='&nbsp;CANCEL&nbsp;';
			jConfirm('Yakin data ini akan di posting','Confirm',function(e){
				if(e){
					$.post('update_terima_mutasi',{
						'id_trans':id},
						function(result){
							_show_trans();
						})
				}else{
					jAlert($('#cen-'+id).is(':checked'));
				}
			})
		}
	 })
	 break;	
	}
}