// JavaScript Document
var path=$('#path').val();
$(document).ready(function(e) {
	if($('#aksine').val()=='2'){
		$('#listbarangrusak').removeClass('tab_button');
		$('#listbarangrusak').addClass('tab_select');
	}else{
		$('#listpemakaian').removeClass('tab_button');
		$('#listpemakaian').addClass('tab_select');
	}
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
	
	if($('#aksine').val()==2){
		_show_data('2')
	}else if($('#aksine').val()==1){
		_show_data('1');
	}
	tglNow('#tanggal');
	tglNow('#dari_tgl');
	$('#tanggal').dynDateTime();
	$('#dari_tgl').dynDateTime();
	$('#sampai_tgl').dynDateTime();
		$('#frm1 input#Nama_Barang')
		.coolautosuggest({
				url:path+'inventory/data_material?fld=Nama_Barang&limit=10&str=',
				width:350,
				showDescription	:true,
				onSelected:function(result){
					$('#frm1 #Kode').val(result.kode);
					$('#frm1 #Satuan').val(result.nm_satuan)
					$('#frm1 #Harga_Beli').val(result.hargajual);
					$('#frm1 #id_barang').val(result.id_barang);
					$('#frm1 #id_satuan').val(result.satuan)
					$('#frm1 #Jumlah').focus().select();
				}
		})
		$('#frm1 input#Kode')
		.coolautosuggest({
				url:path+'inventory/data_material?fld=Kode&limit=10&str=',
				width:250,
				showDescription	:true,
				onSelected:function(result){
					$('#frm1 #Nama_Barang').val(result.description);
					$('#frm1 #Satuan').val(result.nm_satuan)
					$('#frm1 #Harga_Beli').val(result.hargajual);
					$('#frm1 #id_barang').val(result.id_barang);
					$('#frm1 #id_satuan').val(result.satuan)
					$('#frm1 #Jumlah').focus().select();
				}
		})
	
	$('#frm1 #Jumlah')
		.keyup(function(){
			var j=$(this).val()
			var h=$('#frm1 #Harga_Beli').val()
			$('#Total').val(parseFloat(j)*parseFloat(h))
		})
	//simpan pemakaian
	$('#frm1 #saved-pemakaian').click(function(){
		_simpan_data('1');
		$('#frm1 #batal').click()
	})
	$('#frm1 #saved-rusak').click(function(){
		_simpan_data('2');
		$('#frm1 #batal').click();
	})
	$('#okelah').click(function(){
		_show_data($('#aksine').val());
	})
	$('#frm1 #batal').click(function(){
		//document.location.reload();
	})
	$('#frm2 #saved-edit_pakai').click(function(){
		$.post('update_pemakaian',{
			'id'		:$('#frm2 #id_satuan').val(),
			'jumlah'	:$('#frm2 #Jumlah').val(),
			'harga'		:$('#frm2 #Harga_Beli').val(),
			'keterangan':$('#frm2 #Keterangan').val()
		},function(result){
			if(parseInt(result)>0){
				_update_stock('2',parseInt(result),'1')
			}else{
				_update_stock('2',parseInt(result),'2')
			}
/*			$('#frm2 #batal').click();
			_show_data('1');
			keluar_pakai()
*/		})
	})
	//print report
	$('#prt').click(function(){
		$('#frm3').attr('action','print_pemakaian');
		document.frm3.submit();
	})
	$('#prtr').click(function(){
		$('#frm3').attr('action','print_rusak');
		document.frm3.submit();
	})
})

function _simpan_data(id){
		$.post('set_pemakaian',{
			'tanggal'	:$('#frm1 #tanggal').val(),
			'id_barang'	:$('#frm1 #id_barang').val(),
			'id_satuan'	:$('#frm1 #id_satuan').val(),
			'jumlah'	:$('#frm1 #Jumlah').val(),
			'harga'		:$('#frm1 #Harga_Beli').val(),
			'keterangan':$('#frm1 #Keterangan').val(),
			'id_jenis'	:id
		},function(result){
			_update_stock('1',0,'1')
		})
}
function _show_data(id){
	show_indicator('ListTable',10);
		$.post('get_pemakaian',{
			'dari_tgl'	:$('#dari_tgl').val(),
			'sampai_tgl':$('#sampai_tgl').val(),
			'id_jenis'	:id
		},function(result){
			$('table#ListTable tbody').html(result);
			$('table#ListTable').fixedHeader({width:(screen.width-30),height:(screen.height-335)})
		})
}

function images_click(id,aksi){
	switch(aksi){
		case 'edit':
		$('#pp-pakai').css({'left':'22%','top':'20%'});
		lock('#frm2 :not(#Jumlah,#Harga_Beli,#Keterangan,#id_barang,:button,:reset)')
		unlock('#frm2 #id_satuan');
			$.post('edit_pemakaian',{'id':id},
			 function(result){
				 var rst=$.parseJSON(result)
				$('#frm2 #tanggal').val(rst.Tanggal); 
				$('#frm2 #Kode').val(rst.Kode); 
				$('#frm2 #Nama_Barang').val(rst.Nama_Barang); 
				$('#frm2 #Satuan').val(rst.Satuan); 
				$('#frm2 #Jumlah').val(rst.Jumlah); 
				$('#frm2 #Harga_Beli').val(rst.Harga); 
				$('#frm2 #Total').val((parseInt(rst.Jumlah)*parseInt(rst.Harga)));
				$('#frm2 #Keterangan').val(rst.Keterangan);
				$('#frm2 #id_barang').val(rst.ID_Barang);
				$('#frm2 #id_satuan').val(rst.ID);
				$('#pp-pakai').show('slow');
			 })
		$('#lock').show();
		break;
		case 'del':
		if(confirm('Yakin data akan dihapus??')){
			$.post('edit_pemakaian',{'id':id},
			 function(result){
				 var rst=$.parseJSON(result)
				 $('#frm1 #id_barang').val(rst.ID_Barang);
				 $('#frm1 #Jumlah').val(rst.Jumlah)
				 
				$.post('update_stock',{
						'id_barang'	:$('#frm1 #id_barang').val(),
						'jumlah'	:$('#frm1 #Jumlah').val(),
						'ret'		:'2'
					},function(result){
						$.post('delete_pemakaian',{'id':id},
						function(result){
							$('#frm1 #batal').click();
							_show_data($('#aksine').val());
						})
				})
			 })
		}
		break;
	}
}
function _update_stock(id,jml,ret){
		$.post('update_stock',{
				'id_barang'	:$('#frm'+id+' #id_barang').val(),
				'jumlah'	:(jml==0)?$('#frm'+id+' #Jumlah').val():jml,
				'ret'		:ret
			},function(result){
				if(parseInt(result)<0){
					_update_stock(id,result,ret);
				}else{
					//$('#frm1 #batal').click();
					_show_data($('#aksine').val());
					keluar_pakai();
				}
			})
}



