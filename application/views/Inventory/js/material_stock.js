$(document).ready(function(e) {
	var path=$('#path').val();
	var prs=$('#prs').val();
	$('#frm1 input:not(#nm_barang)').attr('disabled','disabled');
	$('table#panel tr td.flt').hide()
    $('#stockoverview').removeClass('tab_button');
	$('#stockoverview').addClass('tab_select');
	$('#v_stockoverview table#stoked').hide();
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			if(id!=''){
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
					if(id!='liststock'){
						$('table#panel tr td.flt').hide()//else if(id=='listobat'){
							$('table#addtxt').hide();
					}else{
						$('table#panel tr td.flt').show()
							$('table#addtxt').show();

					}///$('table#panel tr td:not(#'+id+')')	
			}
	});
	$('table#addtxt').hide();
	$('#frm2 #cari').attr('disabled','disabled');
	$('#frm1 input#nm_barang')
		.coolautosuggest({
				url:path+'inventory/data_material?fld=Nama_Barang&limit=10&str=',
				width:350,
				showDescription	:false,
				onSelected:function(result){
					$('#status').val(result.status);
					$('#nm_kategori').val(result.nm_kategori);
					$.post('list_stock',{'nm_barang':result.description},
						function(data){
							$('table#stoked tbody').html(data);
							$('#v_stockoverview table#stoked').show();
						})
				}
		})
		.keypress(function(e){
			if(e.which==13 || e.which==27){
				$('.autosuggest').hide();
			}
		})
	$('#frm2 select#nm_jenis').change(function(){
		
	})
	$('#frm2 select#id_kategori').change(function(){
		$('#frm2 input#nam_barang').val('')
		$('#frm2 #cari').attr('disabled','disabled');
	})
	$('#frm2 select#stat_barang').change(function(){
		$('#frm2 input#nam_barang').val('')
		$('#frm2 #cari').attr('disabled','disabled');
	})
	$('#frm2 #cari').click(function(){
		$('#frm2 #saved-filter').click();
	})
	$('#frm2 input#nam_barang')
		.keyup(function(){
			$('#frm2 #cari').removeAttr('disabled');
		})
	
	$('#frm2 #saved-filter').click(function(){
		$('#v_liststock table#ListTable tbody').html('');
		show_indicator('ListTable',7);
		$.post('list_filtered',{
			'nm_kategori'	:$('#frm2 select#id_kategori').val(),
			'stat_barang'	:$('#frm2 select#stat_barang').val(),
			'nam_barang'	:$('#frm2 input#nam_barang').val(),
			'section'		:'stoklistview',
			'edit'			:'n'
		},function(result){
			$('table#addtxt').show();
			$('#v_liststock table#ListTable tbody').html(result);
			$('div#ttr').html($('#v_liststock table#ListTable tbody tr').length)
			$('#v_liststock table#ListTable').fixedHeader({width:(screen.width-100),height:(screen.height-355)});
		})
	})
})

function on_clicked(id,fld,frm){
	switch(frm){
		case 'frm1':
		if(fld=='nm_barang'){
			$.post('data_hgb',{'nm_barang':id},
			function(result){
				var rst=$.parseJSON(result)
				$('#frm1 input#nm_jenis').val(rst.nm_jenis);
				$('#frm1 input#nm_kategori').val(rst.nm_kategori);
				$('#frm1 input#nm_golongan').val(rst.nm_golongan);
				$('#v_stockoverview table#ListTable').show();
				$.post('list_stock',{'nm_barang':id},
				function(result){
					$('#v_stockoverview table#ListTable tbody').html(result);
				});
			})
		}
		break;
	}
}

function edited(id){
	
}