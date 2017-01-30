// JavaScript Document
// material_inv.js
// author : Iswan Putera

$(document).ready(function(e) {
	var path=$('#path').val();
	var prs=$('#prs').val();
		$('#jenisbarang').removeClass('tab_button');
		$('#jenisbarang').addClass('tab_select');

	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
	});
	_show_list('Jenis','inv_barang_jenis','JenisBarang','jenisbarang');
	if($('#page').val()=='kategoribarang') _show_list('Kategori','inv_barang_kategori','Kategori','kategoribarang');
	$(':button').click(function(){
		var id=$(this).attr('id');
		var idr=id.split('-');
		var pos=$(this).offset();
		var l=pos.left+5;
		var t=pos.top+24;
		var induk=$(this).parent().parent().parent().parent().parent().attr('id');
		switch(id){
				case 'saved-jenis':
				$.post(path+'inventory/simpan_jenis',{'nm_jenis':$('#frm1 input#JenisBarang').val(),'induk':'frm1'},
				function(result){
					_show_list('Jenis','inv_barang_jenis','JenisBarang','jenisbarang');
					$('#frm1 input#JenisBarang').val('');
				})
			break;
			case 'saved-kat':
				$.post(path+'inventory/simpan_kategori',{'nm_kategori':$('#frm2 input#Kategori').val(),'induk':'frm2'},
				function(result){
					$('#v_kategoribarang table#ListTable tbody').html(result);
					$('#frm2 input#Kategori').val('');
				})
			break;
			case 'saved-subkat':
				$.post(path+'inventory/simpan_golongan',{'nm_golongan':$('#frm3 input#nm_golongan').val(),'induk':'frm3'},
				function(result){
					$('#v_subkategori table#ListTable tbody').html(result);
					$('#frm3 input#nm_golongan').val('');
				})
			break;
		}
	});

});
function images_click(id,aksi){
	var path=$('#path').val();
	var idd=id.split('-');
	switch(aksi){
		case 'del':
					if (confirm('Yakin data ini  akan di hapus?')){
						$.post(path+'inventory/hapus_jenis',{'tbl':idd[1],'id':idd[0],'fld':'JenisBarang'},
						function(result){
							_show_list('Jenis',idd[1],'JenisBarang','jenisbarang');
						})
					}
	}
};
	
function _show_list(tabelName,tabel,field,oto){
	var path=$('#path').val();
	$.post(path+'inventory/jenis_list',{
		'tabel'		:tabel,
		'orderby'	:field,
		'oto'		:oto},
	function(result){
		$('table#'+tabelName+' tbody').html(result);
		$('table#'+tabelName).fixedHeader({'width':(screen.width-350),'height':420});
	})
};