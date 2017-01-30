// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
	var prs=$('#prs').val();
	if(prs==''){
		$('#pelangganbaru').removeClass('tab_button');
		$('#pelangganbaru').addClass('tab_select');
		$('span#v_anggotabaru').show();
	}else{
		$('#uploadphoto').removeClass('tab_button');
		$('#uploadphoto').addClass('tab_select');
		$('span#v_anggotabaru').hide();
		$('span#v_uploadphoto').show();
	}
	_generate_nomor('#No_Agt');
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
	$('#frm1 #No_Agt').attr('readonly','readonly');
	$('#frm1 #ID_Aktif').attr('disabled','disabled');
	$('#frm2 #NIP')
		.attr('readonly','readonly')
		.val($('#nipe').val());
	$('#frm2 #Nama')
		.val($('#namane').val());
	$('#frm2 #TanggalMasuk').dynDateTime();	
    $('#lpg').val('N');
	$(':button').click(function(){
		var id=$(this).attr('id')
		switch(id){
			case 'saved-registrasi':
				/*$('#frm1').attr('action','set_anggota');
				var No_Agt		=$('#frm1 #No_Agt').val();
				var ID_Dept		=$('#frm1 #Catatan').val();//nama perusahaan
				var NIP			=$('#frm1 #NIP').val();
				var Nama		=$('#frm1 #Nama').val();
				var ID_Kelamin	=$('#frm1 #ID_Kelamin').val();
				var Alamat		=$('#frm1 #Alamat').val();
				var Kota		=$('#frm1 #Kota').val();
				var Propinsi	=$('#frm1 #Propinsi').val();
				var Telepon		=$('#frm1 #Telepon').val();
				var Faksimili	=$('#frm1 #Faksimili').val();
				var ID_Aktif	=$('#frm1 #ID_Aktif').val();
				$.post('set_anggota',{
						'No_Agt'	:No_Agt,
						'Catatan'	:ID_Dept,
						'ID_Dept'	:'1',
						'NIP'		:NIP,
						'Nama'		:Nama,
						'ID_Kelamin':ID_Kelamin,
						'Alamat'	:Alamat,
						'Kota'		:Kota,
						'Propinsi'	:Propinsi,
						'Telepon'	:Telepon,
						'Faksimili'	:Faksimili,
						'ID_Aktif'	:ID_Aktif,
						'Status'	:$('#Status').val()
					},*/
                    $.post('set_anggota',$('#frm1').serialize()+'&'+$('#frm2').serialize(),
					function(result){
						$(':reset').click();
						document.location.reload();
					})
			break;
			case 'c_photo':
				$('img#thumb').attr('src',ab+'uploads/member/no_photo.jpg');
			break;
			case 's_photo':
				$.post('simpan_photo',{
					'no_anggota':$('#nourut').val(),
					'photo_anggota':$('#gambar').val()},
					function(result){
						//alert($.trim(result))
						$('#nourut').val('');
						$('#gambar').val('');
						$('#namane').val('');
						$('#nipe').val('');
						$('#c_photo').hide();
						$('#s_photo').hide();
					})
				break;
			
		}
	})
 $('#Status')
 	.keyup(function(){
		kekata(this);
	})
	.focusout(function(){
		kekata_hide();	
	})
	.keypress(function(e){
	  (e.which==13)?$(this).focusout():''
	})
/*	not used in this version
    manage photo member
	$('#frm1 #Nama').coolautosuggest({url:'get_anggota?limit=10&str='})
	$('#frm1 #Kota').coolautosuggest({url:'get_kota?str='})
	$('#frm1 #Propinsi').coolautosuggest({url:'get_propinsi?str='})
	$('#frm2 #Nama')
		.click(function(){
			$('#photo').hide();
			$('#gambar').val('');
		})
		.coolautosuggest({
		url:'get_anggota?limit=10&str=',
		width:270,
		onSelected:function(result){
			if(result!=null){
				$('#photo').show();
				ab=path.replace('index.php/','');
				$('#frm2 #NIP').val(result.NIP);
				$('#frm2 #no_agt').val(result.NoUrut);
				(result.PhotoLink==null || result.PhotoLink=='')?
				photo='no_photo.jpg':photo=result.PhotoLink;
				$('img#thumb')
					.error(function(){
						//alert(photo);
						$(this).attr('src',ab+'uploads/member/no_photo.jpg')
					})
					.attr('src',ab+'uploads/member/'+photo);
				$('#photo').show();
					if($('#namane').val()=='') {
						$('#c_photo').hide();
						$('#s_photo').hide();
					}else{
						$('#c_photo').show();
						$('#s_photo').show();
					}

			}
		}
		})
*/	//tombol reset ditekan akan menghapus isi field
	//dan generate nomor lagi
	$(':reset').click(function(){
		_generate_nomor('#No_Agt');
	})
	
})

function upload_img(){
	
}
function _generate_nomor(fld){
	$.post('get_nomor_anggota',{'id':'id'},
		function(result){
			$(fld).val(result);
		})
}

	