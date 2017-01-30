// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
	var prs=$('#prs').val();
    $('#changepassword').removeClass('tab_button');
	$('#changepassword').addClass('tab_select');
	$('table#panel tr td').click(function(){
		var id=$(this).attr('id');
			$('#'+id).removeClass('tab_button');
			$('#'+id).addClass('tab_select');
			$('table#panel tr td:not(#'+id+')').removeClass('tab_select');
			$('table#panel tr td:not(#'+id+',#kosong)').addClass('tab_button');
			$('span#v_'+id).show();
			$('span:not(#v_'+id+')').hide();
			$('#prs').val(id);
		if(id=='adduser'){
			$('#frm1 #userid').focus().select();
		}

	});
	$('#old_password').focus().select();
	$('#saved-changepwd').click(function(){
		//cek matching new password
		var old_pwd		=$('#old_password').val();
		var new_pwd		=$('#new_password').val();
		var re_newpwd	=$('#re_password').val();
		if(new_pwd!=re_newpwd || new_pwd.length==0){ alert('input Password baru tidak sesuai\n silahkan cek lagi');
		}else{
			$.post('cek_password',{'old_pwd':old_pwd},
			function(result){
				if($.trim(result)=='OK'){
					$.post('update_password',{'new_pwd':new_pwd},
					function(result){
						if(result='1'){
							alert('Password berhasil di rubah\nSilahkan login kembali');
							document.location.href=path+'admin/logout';
						}else{
							alert("Gagal merubah password \nSilahkan coba lagi");
						}
					})
				}else{
					alert("Gagal merubah password \nPassword lama salah");
				}
			})
		}
	})
})