$(document).ready(function(e) {
	var prs=$('#prs').val();
	$('table#panel tr td.flt').hide()
    $('#countsheet').removeClass('tab_button');
	$('#countsheet').addClass('tab_select');
	$('#v_countsheet table#ListTable').hide();
	$('#v_recordcount table#ListTable').hide();
	$('table#panel tr td.plt').hide();
	$('table#panel tr td#p-0').addClass('bg_print');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			if(id!='' && id.substr(0,2)!='p-'){
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
				if(id!=='countsheet'){
					$('table#panel tr td.plt').hide();
				}else{
					$('table#panel tr td.plt').hide();
				}
			}
	});
	$('#frm2 #okelah').click(function(){
		$('#p-0').click();
	})
	$('#frm1 input#nm_barang')
		.keyup(function(){
			pos_div('#frm1 input#nm_barang');
			auto_suggest('data_material',$(this).val(),$(this).attr('id')+'-frm1');
		})
		.keypress(function(e){
			if(e.which==13 || e.which==27){
				$('.autosuggest').hide();
			}
		})
	$('#frm2 select#nm_jenis').change(function(){
		$('table#panel tr td.plt').hide();
	})
	$('#frm2 select#nm_kategori').change(function(){
		$('table#panel tr td.plt').hide();
	})
	$('#frm2 select#nm_golongan').change(function(){
		$('table#panel tr td.plt').hide();
	})
	$('#frm2 #saved-filter').click(function(){
		show_indicator('sheet',6);
		$.post('list_filtered',{
			'nm_jenis'		:$('#frm2 select#nm_jenis').val(),
			'nm_kategori'	:$('#frm2 select#nm_kategori').val(),
			'nm_golongan'	:$('#frm2 select#nm_golongan').val(),
			'section'		:'stokopname',
			'edit'			:'n'
		},function(result){
			$('#p-0').click();
			$('#v_countsheet table#sheet').hide();
			//$('#v_countsheet table#sheet tbody').html(result);
    		//$('#v_countsheet table#sheet').fixedHeader({width:(screen.width-100),height:(screen.height-390)})
		})
	})
	$('#frm1 #saved-filter').click(function(){
		show_indicator('ListTable',6);
		$.post('list_filtered',{
			'nm_jenis'		:$('#frm1 select#nm_jenis').val(),
			'nm_kategori'	:$('#frm1 select#nm_kategori').val(),
			'nm_golongan'	:$('#frm1 select#nm_golongan').val(),
			'section'		:'stoklistview',
			'edit'			:'y'
		},function(result){
			$('#v_stockadjustment table#ListTable').show();
			$('#v_stockadjustment table#ListTable tbody').html(result);
			$('#v_stockadjustment table#ListTable').fixedHeader({width:(screen.width-100),height:(screen.height-390)})
		})
	})
	$('#v_stockadjustment table#ListTable tbody').mouseenter(function(){
		$('#ListTable tr td input').click(function(){
			var id=$(this).attr('id');
			$('#'+id).focus().select();
			$('#prs').val($('#'+id).val());
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
				$('#v_countsheet table#ListTable').show();
				$.post('list_stock',{'nm_barang':id},
				function(result){
					$('#v_countsheet table#ListTable tbody').html(result);
				});
			})
		}
		break;
	}
}

function image_click(id,cl){
	switch(cl){
		case 'edit':
			var ids=id.split('-');
			var isi=$('input#'+ids[0]+'-'+ids[2]).val();
			$('input#'+ids[0]+'-'+ids[2]).val(isi.replace(',',''));
			var val=to_number((isi.replace(',','')).replace('.00',''));
			$.post('update_adjust',{'nm_barang':ids[0],'stock':val,'old_stok':$('#prs').val()},
				function(result){
					//alert(result);
				})
		break;
		case 'del':
			var ids=id.split('-');
			var isi=$('#prs').val();
			$('input#'+ids[0]+'-'+ids[2]).val(isi);
		break;
	}
}

function edited(id){
	
}