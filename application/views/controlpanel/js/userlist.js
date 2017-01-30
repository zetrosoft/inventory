// JavaScript Document
$(document).ready(function(e) {
	var path=$('#path').val();
	var prs=$('#prs').val();
	($('#otor').val()=='disabled')? $('#nm_group').attr('disabled','disabled'):	$('#nm_group').removeAttr('disabled');  
   	$('#frm2 select#nm_group').val($('#uea').val()).select();
    $('#listuser').removeClass('tab_button');
	$('#listuser').addClass('tab_select');
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
	_get_modul();
	_get_head();
	_get_tab_content();
	$('#nm_modul').change(function(){
		$('#hdd').html('');
		_get_head($(this).val());
		_get_tab_content($(this).val());
	})
	//group select click
	$('#nm_group').change(function(){
		_get_tab_content($('#nm_modul').val());
		$('div#hdd table#tab tr td:first').click()
		//get_data_menu('User Admin',$(this).val());
	})
	$('div#hdd table#tab tr td').live('click',function(e){
		var id=$(this).attr('id');
		$('div#hdd table#tab tr td#'+id).removeClass('tab_button');
		$('div#hdd table#tab tr td#'+id).addClass('tab_select');
		$('div#hdd table#tab tr td:not(#'+id+')').removeClass('tab_select');
		$('div#hdd table#tab tr td:not(#'+id+',#kosong)').addClass('tab_button');
	})
	//tab adduser
	
	$('#frm1 #userid')
		.keyup(function(){
			pos_div(this);
		})
		.focus().select();
	$(':button').click(function(){
		var id=($(this).attr('id'));
		//alert(id)
		switch(id){
			case 'saved-userlist':
			//alert(id)
				var userid		=$('#frm1 #userid').val();
				var username	=$('#frm1 #username').val();
				var userlevel	=$('#frm1 #idlevel').val();
				var userpwd		=$('#frm1 #password').val();
				var lokasi		=$('#frm1 #lokasi').val();
				$.post('simpan_newuser',{
					'userid'	:userid,
					'username'	:username,
					'idlevel'	:userlevel,
					'password'	:userpwd,
					'lokasi'	:lokasi
					},function(result){
						$('#v_listuser table#ListTable tbody').html(result);
						$(':reset').click();
						document.location.reload();
						})
				break;
			case 'add-idlevel':
				var pos=$(this).offset();
				var l=pos.left+5;
				var t=pos.top+24;
				$('#pp-adduserlevel').css({'left':l,'top':t});
				$('#nama').val('adduserlevel');
				$('#lock').show();
				$('#pp-adduserlevel').show('slow');
				$('#frm3 #idlevel').val('0');
				$('#frm3 #idlevel').attr('disabled','disabled');
				$('#frm3 #nmlevel').focus().select();
			break;
			case 'saved-addlevel':
				$.post('simpan_newlevel',{'nmlevel':$('#frm3 #nmlevel').val()},
					function(result){
						$('#frm1 #idlevel').html(result);
						$('#frm3 input:reset').click();
						keluar();
					})
			break;
			case 'saved-edited':
			$('#frm4 #userid').removeAttr('disabled')
				var userid		=$('#frm4 #userid').val();
				var username	=$('#frm4 #username').val();
				var userlevel	=$('#frm4 #idlevel').val();
				var userpwd		=$('#frm4 #password').val();
				var lokasi		=$('#frm4 #lokasi').val();
				$.post('set_userupdate',{
					'userid'	:userid,
					'username'	:username,
					'idlevel'	:userlevel,
					'password'	:userpwd,
					'lokasi'	:lokasi
					},function(result){
						$('#v_listuser table#ListTable tbody').html(result);
						$('#frm4 :reset').click();
						keluar();
						document.location.reload();
						})
			break;
		}
	})
	$('img.close').click(function(){
		keluar()
	})
	//otorisasi area
	get_lokasi();	
	$('#id_user').change(function(){
		get_lokasi($(this).val());
	})
})

//function support
//get daftar lokasi gudang
function get_lokasi(id){
	var path=$('#path').val();
	$.post(path+'settingan/get_lokasi_for_oto',{'id':id},
	function (result){
		$('table#newTable tbody').html(result);
	});
}
//update otorisasi area
function simpan_oto(id){
	var path=$('#path').val();
	var user=$('#frm12 #id_user').val();
	var stat=$('table#newTable tbody tr td input:checkbox#c_'+id).is(':checked')
	var st=(stat)?'Y':'N';
	$.post('user_oto_lokasi',{
		'userid':user,
		'stat'	:st,
		'area'	:id},
		function(result){
			
		})
}
	
//function checkbok on click
function mnu_onClick(tp,id){
	var path=$('#path').val();
	var nm=$('span#v_authorisation div#v_panel table#usrmenu tbody  tr td input:checkbox#'+tp+'-'+id).is(':checked')
		var grp=$('#frm2 #nm_group').val();
		(nm)?st='Y':st='N';
			$.post('useroto_update',{'userid':grp,'stat':st,'idmenu':id,'idfld':tp},
				function(result){
					//alert(result);
				})
}

function get_data_menu(mn,uid){
		$.post('get_data_menu',{'section':mn,'nm_group':uid},
			function(result){
				$('div#v_panel table#usrmenu tbody').html(result);
			})
}

//callback autosuggest
function on_clicked(id,fld,frm){
	alert(id+' sudah ada, Silahkan gunakan user id yang lain');	
}

function image_click(id,cl){
	id=id.split('-')
	switch(cl){
		case 'edit':
				$('#pp-edituser').css({'left':'25%','top':'20%'});
				$('#nama').val('edituser');
				$('#lock').show();
				$('#pp-edituser').show('slow');
				$('#frm4 #userid')
					.val(id[1])
					.attr('disabled','disabled')
				$('#frm4 #add-idlevel').attr('disabled','disabled');
				$.post('get_datauser',{'userid':id[1]},
					function(result){
						var obj=$.parseJSON(result);
						$('#frm4 #username').val(obj.username);
						$('#frm4 #idlevel').val(obj.idlevel).select();
						$('#frm4 #lokasi').val(obj.lokasi).select();
						$('#frm4 #password')
							.val(obj.password)
							.attr('disabled','disabled');
					})
		break;
		case 'del':
		if(confirm("Yakin data ini akan di hapus?")){
			$.post('hapus_user',{'userid':id[1]},
				function(result){
				$('#v_listuser table#ListTable tbody tr#nm-'+id[1]).remove();
				})
		}
		break	
	}
}
//popup handle
	function keluar(){
		var nama=$('#nama').val();
		$('.autosuggest').hide();
		$('#pp-'+nama).hide('slow');
		$('#kekata').hide();
		$('#lock').hide();
	}

function _get_modul(){
	$.post('get_modul',{'ID':''},
	function(result){
		$('#nm_modul').html(result);
	})
}

function _get_head(modul){
	$.post('get_data_head',{'modul':modul},
	function(result){
		$('#hdd').html(result);
	})
}
function tab_click(id,mn){
/*		$('div#hdd table#tab tr td#'+id).click();
		$('div#hdd table#tab tr td#'+id).addClass('tab_select');
		$('div#hdd table#tab tr td:not(#'+id+')').removeClass('tab_select');
		$('div#hdd table#tab tr td:not(#'+id+',#kosong)').addClass('tab_button');
*/		get_data_menu(mn,$('#nm_group').val())
}
function _get_tab_content(id){
	$.post('get_tab_content',{'section':id},
	function(result){
		tab_click(id,$.trim(result));
		//get_data_menu($.trim(result),$('#nm_group').val())

	})
}