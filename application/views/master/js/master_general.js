// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
	var prs=$('#prs').val();
	($('#otor').val()=='disabled')? $('#nm_group').attr('disabled','disabled'):	$('#nm_group').removeAttr('disabled');  
   	$('#frm2 select#nm_group').val($('#uea').val()).select();
    $('#dataakun').removeClass('tab_button');
	$('#dataakun').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			$('#'+id).removeClass('tab_button');
			$('#'+id).addClass('tab_select');
			$('table#panel tr td:not(#'+id+')').removeClass('tab_select');
			$('table#panel tr td:not(#'+id+',#kosong)').addClass('tab_button');
			$('span#v_'+id).show();
			$('span:not(#v_'+id+')').hide();
			$('#prs').val(id);
			if(id=='datakas'){
				$('#frm3 #id_kas').focus().select();
			}

	})
	_show_data();
	$('#frm3 #nm_kas')
		.focusout(function(){
			if($('#frm3 #id_kas').val().length==0){
				alert('ID KAS Harus di isi');
				$(this).focus().select()
			}else{
				tabField();
			}
		})
	$('#frm3 #sa_kas')
		.click(function(){
			$(this).focus().select()
		})
		.keyup(function(){
			$('#frm3 #sa_kas').terbilang({'output_div':'terbilang'});
			pos_terbilang(this);
		})
		.focusout(function(){
			$('#terbilang').hide();
			
		})
		.keypress(function(e){
			if(e.keyCode==13){
				$('#frm3 #sl-kas').focus();
			}
		})

	$('#frm3 #sl_kas')
		.click(function(){
			$(this).focus().select()
		})
		.keyup(function(){
			$(this).terbilang({'output_div':'terbilang'});
			pos_terbilang(this);
		})
		.focusout(function(){
			$('#terbilang').hide();
		})
		.keypress(function(e){
			if(e.keyCode==13){
				$('#frm3 #saved-kas').focus();
			}
		})
		
	$(':button')
		.click(function(){
			var id=$(this).attr('id');
			//alert(id);
			switch(id){
				case 'saved-pelanggan':
					var nm_pelanggan	=$('#frm1 #nm_pelanggan').val();
					var alm_pelanggan	=$('#frm1 #alm_pelanggan').val();
					var telp_pelanggan	=$('#frm1 #telp_pelanggan').val();
					var hutang_pelanggan=$('#frm1 #hutang_pelanggan').val();
					$.post('simpan_pelangan',{
						'table'				:'mst_pelanggan',
						'nm_pelanggan'		:nm_pelanggan,
						'alm_pelanggan'		:alm_pelanggan,
						'telp_pelanggan'	:telp_pelanggan,
						'hutang_pelanggan'	:hutang_pelanggan
					},function(result){
						$('#frm1 :reset').click();
						$('input.angka').val('0');
						$('#v_datapelanggan table#ListTable tbody').html(result);
					})
				break;
				case 'saved-dokter':
					var nm_dokter=$('#frm2 #nm_dokter').val();
					var sp_dokter=$('#frm2 #sp_dokter').val();
					$.post('simpan_dokter',{
						'table'			:'mst_dokter',
						'nm_dokter'		:nm_dokter,
						'sp_dokter'		:sp_dokter
					},function(result){
						$('#frm2 :reset').click();
						$('#v_datadokter table#ListTable tbody').html(result);
					})
				break;
				case 'saved-kas':
					var id_kas=$('#frm3 #id_kas').val();
					var nm_kas=$("#frm3 #nm_kas").val();
					var sa_kas=$('#frm3 #sa_kas').val();
					var sl_kas=$('#frm3 #sl_kas').val();
						$.post('simpan_kas',{
							'id_kas'	:id_kas,
							'nm_kas'	:nm_kas,
							'sa_kas'	:sa_kas,
							'sl_kas'	:sl_kas
						},function(result){
						$('#frm3 :reset').click();
						$('#v_datakas table#ListTable tbody').html(result);
						})
				break;
					
			}
		})
	
})

function images_click(id,cl){
	var id=id.split('-');
	switch(cl){
	 case 'edit':
		 switch(id[0]){
			 case 'pelanggan':
				 var nm_pelanggan		=$('#v_datapelanggan table#ListTable tr#nm-'+id[1]+' td:nth-child(2)').html();
				 var alm_pelanggan		=$('#v_datapelanggan table#ListTable tr#nm-'+id[1]+' td:nth-child(3)').html();
				 var telp_pelanggan		=$('#v_datapelanggan table#ListTable tr#nm-'+id[1]+' td:nth-child(4)').html();
				 var hutang_pelanggan	=$('#v_datapelanggan table#ListTable tr#nm-'+id[1]+' td:nth-child(5)').html();
					$('#frm1 #nm_pelanggan').val(nm_pelanggan);
					$('#frm1 #alm_pelanggan').val(alm_pelanggan);
					$('#frm1 #telp_pelanggan').val(telp_pelanggan);
					$('#frm1 #hutang_pelanggan').val(hutang_pelanggan);
			 break;	
			 case 'dokter':
				 var nm_dokter		=$('#v_datadokter table#ListTable tr#nm-'+id[1]+' td:nth-child(2)').html();
				 var sp_dokter		=$('#v_datadokter table#ListTable tr#nm-'+id[1]+' td:nth-child(3)').html();
					$('#frm2 #nm_dokter').val(nm_dokter);
					$('#frm2 #sp_dokter').val(sp_dokter);
			 break;
			 case 'Kas':
			 $.post('get_akun_kas',{'id':id[1]},
			 function(res){
				 var hsl=$.parseJSON(res);
					$('#frm3 #id_kas').val(hsl.id_kas);
					$('#frm3 #nm_kas').val(hsl.nm_kas);
					$('#frm3 #sa_kas').val(hsl.sa_kas);
					$('#frm3 #sl_kas').val(hsl.sl_kas);
			 })
			 break;
		 }
	  break;
	  case 'del':
	  var path=$('#path').val();
	  	if (confirm('Yakin data '+id[0] +' ' +id[1]+' akan dihapus')){
						$.post(path+'inventory/hapus_inv',{'tbl':'mst_'+id[0],'id':id[1],'fld':'nm_'+id[0]},
						function(result){
							_show_data();
						})
		}	
	  break;
	}
}

function pos_terbilang(id){
	var pos=$(id).offset();
	var l=pos.left;
	var t=pos.top+25
	$('#terbilang').css({'top':t,'left':l,'position':'fixed'});
	$('#terbilang').show();	
}

function _show_data(){
	show_indicator('ListTable',5);
	$.post('list_data_akun'	,{'id':''},
		function(result){
			$('table#ListTable tbody').html(result);
		})
}