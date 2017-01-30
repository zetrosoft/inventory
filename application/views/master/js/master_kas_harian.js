// JavaScript Document
$(document).ready(function(e) {
    $('#operasionaltoko').removeClass('tab_button');
	$('#operasionaltoko').addClass('tab_select');
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
			if(id=='operasionaltoko'){
				_generate_nomor('D','#frm2 input#no_transaksi');
				__show_data_trans('2');}
	});
	
	$('#saved-kaskeluar').attr('disabled','disabled');
	_generate_nomor('D','#frm2 input#no_transaksi');
	_generate_nomor('D','#frm1 input#no_trans');
	tglNow('#frm1 #tgl_kas');
	tglNow('#frm2 #tgl_transaksi');
	$('#frm1 #no_trans').attr('readonly','readonly');
	$('#frm1 #nm_kas').attr('readonly','readonly');
	$('#frm2 #no_transaksi').attr('readonly','readonly');
	//setup saldo kas
	__id_kas('1');
	__id_kas('2');
	//tampilkan data kas harian
	__show_data('1');
	__show_data_trans('2')
	$('#frm2 #fmrTable tr#6').hide();
	$('#frm1 #tgl_kas')
		.click(function(){
			$(this).focus().select();
			
		})
		.dblclick(function(){
			tglNow('#frm1 #tgl_kas');
		})
		.keyup(function(){
			tanggal(this)
		})
		.keypress(function(e){
			if(e.keyCode==13){
				$('#frm1 #id_kas').focus().select();
			}
		});
	$('#frm1 #id_kas')
		.click(function(){
			//auto_suggest3('get_datakas',$(this).val(),$(this).attr('id')+'-frm1');
			//pos_div('#frm1 #id_kas');
		});
	$('#frm1 #sa_kas')
		.click(function(){
			$(this).focus().select();
		})
		.keyup(function(){
			pos_info(this,'terbilang');
			$(this).terbilang({'output_div':'terbilang'});
		})
		.keypress(function(e){
			if(e.which==13){
				$('#frm1 #saved-kas').focus();
				$(this).focusout();
			}
		})
		.focusout(function(){
			$('#terbilang').hide();
		});
	$('#id_lok').change(function(){
		__show_data('1');
	});
	
	$('#id_lokas').change(function(){
		__show_data_trans('2');
	});
	//transaksi pengeluaran kas
	$('#frm2 #tgl_transaksi')
		.click(function(){
			$(this).focus().select();
			
		})
		.dblclick(function(){
			tglNow('#frm2 #tgl_transaksi');
		})
		.keyup(function(){
			tanggal(this)
		})
		.keypress(function(e){
			if(e.keyCode==13){
				$('#frm1 #akun_transaksi').focus().select();
			}
		});
	$('#frm2 #akun_transaksi')
		.click(function(){
		});
		
	$('#frm2 #jml_transaksi')
		.click(function(){
			$(this).focus().select();
			
		})
		.keyup(function(){
			pos_info(this,'terbilang');
			$(this).terbilang({'output_div':'terbilang'});
		})
		.keypress(function(e){
			if(e.which==13){
				$('#frm2 #saved-kaskeluar').focus();
				$(this).focusout();
			}
		})
		.focusout(function(){
			$('#terbilang').hide();
			
		});
	$('#frm2 #harga_beli')
		.focus(function(){
		unlock('#saved-kaskeluar');	
		})
		.keyup(function(){
			kekata(this);
		})
		.focusout(function(){
			kekata_hide();
		})
		.keypress(function(e){
			if(e.which==13){
			kekata_hide();
			$(':button').focus();
			}
		});
	
	$(':button').click(function(){
		var id=$(this).attr('id')
		$('#frm1 #nm_kas').removeAttr('disabled');
		switch(id){
			case 'saved-kas':
				_simpan_kas();
			break;
			case 'saved-kaskeluar':
				$.post('simpan_kas_keluar',{
					'tgl_transaksi'	:$('#frm2 #tgl_transaksi').val(),
					'no_transaksi'	:$('#frm2 #no_transaksi').val(),
					'ket_transaksi'	:$('#frm2 #ket_transaksi').val(),
					'harga_beli'	:$('#frm2 #harga_beli').val(),
					'akun_transaksi':$('#frm2 #akun_transaksi').val(),
					'jtran'			:$('#trans_new').val(),
					'tipe'			:$('#trans_new').val(),
					'tanggal'		:$('#frm2 #tgl_transaksi').val(),
					'lokasi'		:$('#id_lokas').val()
				},function(result){
					$('#frm2 :reset').click();
					__id_kas('2');
					_generate_nomor('D','#frm2 input#no_transaksi');
					tglNow('#frm2 #tgl_transaksi');
					$('#id_lokas').val($('#lok').val()).select();
					__show_data_trans('2');
					$('#trans_new').val('D');
					lock('#saved-kaskeluar');
				});
			break;	
		}
	})
});

function _simpan_kas()
{
	$.post('simpan_kas_harian',{
		'no_trans'	:$('#frm1 #no_trans').val(),
		'tgl_kas'	:$('#frm1 #tgl_kas').val(),
		'id_kas'	:$('#frm1 #id_kas').val(),
		'nm_kas'	:$('#frm1 #nm_kas').val(),
		'sa_kas'	:$('#frm1 #sa_kas').val(),
		'lokasi'	:$('#id_lok').val()
		},function(result){
		$('#frm1 :reset').click();
		_generate_nomor('D','#frm1 input#no_trans');
		tglNow('#frm1 #tgl_kas');
		$('#id_lok').val($('#lok').val()).select();
		//__id_kas('1');
        //__show_data('1');
		});
}
function on_clicked(clicked,id,frm){
	switch(id){
		case 'id_kas':
		$.post('get_datailkas',{'id_kas':clicked},
			function(result){
			 var obj=$.parseJSON(result);
				$('#frm1 #nm_kas').val(obj.nm_kas);	
				$('#frm1 #sa_kas').val(obj.sa_kas);	
			});
		break;
		case 'akun_transaksi':
		unlock('#saved-kaskeluar');
		$('#frm2 #ket_transaksi').focus().select();
		break;
	}
};
	function _generate_nomor(tipe,field){
		var path=$('#path').val();
		$.post(path+'penjualan/nomor_transaksi',{'tipe':tipe,'level':$('#aktif_user').val()},
		function(result){
			$(field).val(result);
			$('#trans_new').val('D');
		})
	};

function image_click(id,cl){
	var id=id.split('-');
	switch(cl){
	  case 'del':
	  $('#saved-kaskeluar').click();
	}
};

function __id_kas(id){
	$.post('get_datailkas',{'id':id},
		function(result){
			var rst=$.parseJSON(result)
			$('#frm'+id+' #id_kas').val(rst.id_kas);
			$('#frm'+id+' #nm_kas').val(rst.nm_kas);
			$('#frm'+id+' #akun_transaksi').val(rst.id_kas);
			$('#sa_kas').focus().select();
		})
};
function __show_data(id){
	$.post('list_kas_harian',{
		'tanggal':'',
		'lokasi' :$('#id_lok').val()},
	function(result){
		$('#v_setupsaldokas table#ListTable tbody').html(result);
       	$('#v_setupsaldokas table#ListTable').fixedHeader({width:(screen.width-100), height:200});
	})
};
function __show_data_trans(id){
	_simpan_kas();
	$.post('list_kas_trans',{
		'tanggal':$('#tgl_transaksi').val(),
		'lokasi' :$('#id_lokas').val()},
	function(result){
		//alert($.trim(result).length)
		//if($.trim(result).length!=0){
		$('#v_operasionaltoko table#ListTable tbody').html(result);
		$('#v_operasionaltoko table#ListTable').fixedHeader({width:(screen.width-100), height:200});
/*		}else{
			$('#setupsaldokas').removeClass('tab_button');
			$('#setupsaldokas').addClass('tab_select');
			$('#operasionaltoko').removeClass('tab_select');
			$('#operasionaltoko').addClass('tab_button');
			
			$('#v_setupsaldokas').css({'display':'block'});
			$('#v_operasionaltoko').css({'display':'none'});
		}
*/	})
};