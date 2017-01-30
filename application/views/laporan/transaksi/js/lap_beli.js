// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
    $('#daftarpembelian').removeClass('tab_button');
	$('#daftarpembelian').addClass('tab_select');
	$('#v_rekappembelian table#ListTable').hide();
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			if(id!=''){
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
			};
	});
	//tglNow('#dari_tgl');
	$('#frm1 #dari_tgl').dynDateTime();
	$('#frm1 #sampai_tgl').dynDateTime();
	$('#frm1 #jtran').val("GR' or jenis_transaksi='GRR'");
	$('#frm1 #optional').val(" order by p.NoUrut");
	$('#frm1 #dari_tgl')
		.click(function(){
			$(this).focus().select();
		})
		.keyup(function(){
			tanggal(this)
		})
		.keypress(function(e){
			if(e.keyCode==13){
				$('#frm1 #sampai_tgl').focus().select();
			}
		});
	$('#frm1 #sampai_tgl')
		.click(function(){
			$(this).focus().select();
			tglNow('#frm1 #sampai_tgl');
		})
		.keyup(function(){
			tanggal(this)
		})
		.keypress(function(e){
			if(e.keyCode==13){
				$('#frm1 #nm_produsen').focus().select();
			}
		});
	$('#frm1 #nm_produsen')
		.coolautosuggest({
			url		:path+'pembelian/get_pemasok?limit=10&str=',
			width	:350,
			showDescription	:true,
			onSelected		:function(result){
					$('#ID_Pemasok').val(result.id_pemasok);
				}
		})
	$("#okelah").click(function(){
		show_indicator('xx',1);
		
		$('#frm1').attr('action','lap_pembelian');
		document.frm1.submit();
	})
	$("#okedech").click(function(){
		show_indicator('xx',1);
		$('#frm1').attr('action','pembelian_per_vendor');
		document.frm1.submit();
	})
	$('#nm_vendor')
		.coolautosuggest({
			url		:path+'pembelian/get_pemasok?limit=10&str=',
			width	:350,
			showDescription	:true,
			onSelected		:function(result){
				$('#ID_Pemasok').val(result.id_pemasok);
			}
		})

	$(':button')
		.click(function(){
			var id=$(this).attr('id');
			switch(id){
				case 'saved-filter':
					$('#frm1').attr('action','print_laporan_beli');
					document.frm1.submit();

				/*
				var dari_tgl	=$('#frm1 #dari_tgl').val();
				var sampai_tgl	=$('#frm1 #sampai_tgl').val();	
				var nm_golongan	=$('#frm1 #nm_golongan').val();	
				var nm_produsen	=$('#frm1 #nm_produsen').val();
					$.post('list_filtered',{
						'jtran'			:'GR\' or jenis_transaksi=\'GRR\'',
						'dari_tgl'		:dari_tgl,
						'sampai_tgl'	:sampai_tgl,
						'nm_golongan'	:nm_golongan,
						'nm_produsen'	:nm_produsen,
						'section'		:'lapbelilist'
					},function(result){
						$('#v_tranpembelian table#ListTable tbody').html(result);	
						$('#v_tranpembelian table#ListTable').show();
						$('#v_tranpembelian table#ListTable').fixedHeader({'width':900,'height':300});

					})*/
			}
		})
	$('img').click(function(){
		//alert($(this).attr('id'))
	})
})


function on_clicked(id,fld,frm){
	
}