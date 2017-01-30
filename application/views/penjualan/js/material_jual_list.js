// JavaScript Document
$(document).ready(function(e) {
	var prs=$('#prs').val();
		$('#listtransaksipenjualan').removeClass('tab_button');
		$('#listtransaksipenjualan').addClass('tab_select');
		$('table#panel tr td').click(function(){
			var id=$(this).attr('id');
					$('#'+id).removeClass('tab_button');
					$('#'+id).addClass('tab_select');
					$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
					$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
					$('span#v_'+id).show();
					$('span:not(#v_'+id+')').hide();
					$('#prs').val(id);
		})
	$('#frm_tgl').dynDateTime();
	$('#to_tgl').dynDateTime();
	tglNow('#frm_tgl');
	$('#ok').click(function(){_show_data();})
	$('#go').click(function(){_show_data();})
	$('#saved-rubah').click(function(){_update_data();})
	$('#tgl_trans').dynDateTime();
	$('#nm_barang').live('change',function(){var kd=$(this).val().split('-:');$('#id_j').val(kd[1]);})
	$('#frm5 #batal').click(function(){ keluar_edittrans();})
})

function _show_data()
{
	show_indicator('ListTable',8);
	$.post('get_list_trans',$('#frm1').serialize(),
	function(result)
	{
		$('#ListTable tbody').html(result);
	})
}

function images_click(id,aksi)
{
	switch(aksi)
	{
		case 'edit':
			_show_popup(id);
		break;
		case 'del':
			jConfirm("Yakin data ini akan dihapus?"," Confirm",function(r){
				if(r)
				{
					$.post('delete_trans',{'id':id},
					function(result){
						_show_data();
					})
				}
			})
		break;
	    case 'delete':
			jConfirm("Yakin data ini akan dihapus?"," Confirm",function(r){
				if(r)
				{
					$.post('delete_trans_head',{'id':id},
					function(result){
						_show_data();
					})
				}
			})
		break;
		case 'edited':
		var rs=id.split(':');
			jPrompt("Edit Tanggal Transaksi\n [format tanggal dd/mm/yyyy]",rs[1],"Edit Tanggal Penjualan",function(r){
				if(r){
				$.post('updatetgltrans',{'tgl':r,'id':rs[0]},
				function(result){
					_show_data();
				})
				}
			})
		break;
	}
}

function _show_popup(id)
{
	$.post('get_detail_trans',{'id':id},function(result){
		var r=$.parseJSON(result);
		$('#id_j').val(r.ID);
		$('#id_br').val(r.ID_Barang);
		$('#nm_barang').val(r.Nama_Barang);
		$('#id_trans').val(r.Keterangan).attr('readony','readonly');
		$('#id_satuan').val(r.Satuan);
		$('#tgl_trans').val(tglFromSql(r.Tanggal));
		$('#jumlah').val(r.Jumlah);
		$('#harga').val(r.Harga);	
	})
	$('#tbl-edittrans').css({'height':'400px'});
	$('#pp-edittrans')
		.css({'left':'25%','top':'20%'})
		.show();
	$('#lock').show();
}

function _update_data()
{
	$.post('set_trans',$('#frm5').serialize(),function(result){ _show_data();keluar_edittrans();})	
}
