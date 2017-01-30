// JavaScript Document
//tab button event
$(document).ready(function(e){
	var path=$('#path').val();
	$('#transaksisimpanan').removeClass('tab_button');
	$('#transaksisimpanan').addClass('tab_select');
	var tday=new Date;
	lock('#ID_Dept')
	$('#ID_Bulan').html("<option value='1-"+tday.getFullYear()+"'>Januari-"+tday.getFullYear()+"</option><option value='2-"+tday.getFullYear()+"'>Februari-"+tday.getFullYear()+"</option><option value='3-"+tday.getFullYear()+"'>Maret-"+tday.getFullYear()+"</option><option value='4-"+tday.getFullYear()+"'>April-"+tday.getFullYear()+"</option><option value='5-"+tday.getFullYear()+"'>Mei-"+tday.getFullYear()+"</option><option value='6-"+tday.getFullYear()+"'>Juni-"+tday.getFullYear()+"</option><option value='7-"+tday.getFullYear()+"'>Juli-"+tday.getFullYear()+"</option><option value='8-"+tday.getFullYear()+"'>Agustus-"+tday.getFullYear()+"</option><option value='9-"+tday.getFullYear()+"'>September-"+tday.getFullYear()+"</option><option value='10-"+tday.getFullYear()+"'>Oktober-"+tday.getFullYear()+"</option><option value='11-"+tday.getFullYear()+"'>November-"+tday.getFullYear()+"</option><option value='12-"+tday.getFullYear()+"'>Desember-"+tday.getFullYear()+"</option>")
	$('#ID_Bulan').val((tday.getMonth()+1)+'-'+tday.getFullYear()).select();
	$('table#panel tr td:not(.flt,.plt)').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
	})
	var dept=$('#ID_Dept').val();
	$('#nm_agt')
		.coolautosuggest({
		url				:path+'member/get_anggota?limit=10&dept=1&str=',
		width			:290,
		showThumbnail	:false,
		showDescription	:true,
			onSelected:function(result){
				$('#ID_Perkiraan').val(result.ID);
				$('#jumlah').focus().select();
					
			}
		})
	$('#jumlah')
		.focus(function(){
			$.post('get_jml_simpanan',{'ID':$('#ID_Simpanan').val()},
				function(result){
					$('#jumlah').val(result);
					$('#terbilang').show();
					$('#jumlah').terbilang({'output_div':'terbilang'});
				})
		})
		.keyup(function(){
				$('#terbilang').show();
				$(this).terbilang({'output_div':'terbilang'});
		})
		.focusout(function(){
			//$('#terbilang').hide();
		})
	$('#ID_Bulan').change(function(){
		var ket=$('#ID_Simpanan option:selected').text()+' Bulan : '+$('#ID_Bulan option:selected').text() ;
		$('#keterangan').val(ket);
	})
	$('#ID_Simpanan').change(function(){
		unlock('#ID_Dept')
		if($('#ID_Dept').val()!='' && $(this).val()!=''){
			var path=$('#path').val().replace('index.php/','');
			var bln=$('#ID_Bulan').val().split('-');
			$('#ptgaji').hide();
			$('span#loading').show();
			$.post('set_copy_agt',{'ID_Dept':$('#ID_Dept').val()},
			function (result){
				$.post('get_agt_blmbayar',{
					'ID_Dept':$('#ID_Dept').val(),
					'ID_Simpanan':$('#ID_Simpanan').val(),
					'ID_Bulan'		:bln[0],
					'Tahun'			:bln[1]},
				function(result){
					if($('#cbayar').val()=='Potong'){
						$('table#ListTable tbody').html(result);
						$('span#loading').hide();
						$('#ptgaji').show();
						$('table#ListTable').fixedHeader({width:(screen.width-450),height:265});
					}
				})
			})
		}
	})
	$('#cbayar').change(function(){
		var id=$(this).val();
		var today=new Date();
		var ket=$('#ID_Simpanan option:selected').text()+' Bulan : '+$('#ID_Bulan option:selected').text() ;
		unlock('#ID_Dept')
		switch (id){
			case 'Tunai':
				$('tr#Tun').show();
				$('#nm_agt').val('');
				$('#ID_Perkiraan').val('');
				$('#jumlah').val('');
				$('#keterangan').val(ket);
				$('#ptgaji').hide();
			break;
			case 'Transfer':
				$('tr#Tun').show();
				$('tr#trans').show();
				$('#nm_agt').val('');
				$('#ID_Perkiraan').val('');
				$('#jumlah').val('');
				$('#keterangan').val(ket);
				$('#ptgaji').hide();
			break;
			case 'Potong':
				$('tr#Tun').hide();
				$('tr#trans').hide();
				$('#nm_agt').val('');
				$('#ID_Perkiraan').val('');
				$('#jumlah').val('');
				$('#keterangan').val(ket);
					if($('#ID_Dept').val()!='' && $(this).val()!=''){
						var path=$('#path').val().replace('index.php/','');
						var bln=$('#ID_Bulan').val().split('-');
						$('#ptgaji').hide();
						$('span#loading').show();
						$.post('set_copy_agt',{'ID_Dept':$('#ID_Dept').val()},
						function (result){
							$.post('get_agt_blmbayar',{
								'ID_Dept':$('#ID_Dept').val(),
								'ID_Simpanan':$('#ID_Simpanan').val(),
								'ID_Bulan'		:bln[0],
								'Tahun'			:bln[1]},
							function(result){
								if($('#cbayar').val()=='Potong'){
									$('table#ListTable tbody').html(result);
									$('span#loading').hide();
									$('#ptgaji').show();
									$('table#ListTable').fixedHeader({width:(screen.width-450),height:265});
								}
							})
						})
					}
			break;
		}
	})

	$('#ID_Dept').change(function(){
		var path=$('#path').val().replace('index.php/','');
		var bln=$('#ID_Bulan').val().split('-');
		$('#ptgaji').hide();
		$('span#loading').show();
		//copy data anggota ke table temporary
		$.post('set_copy_agt',{'ID_Dept':$(this).val()},
		function (result){
			$.post('get_agt_blmbayar',{
				'ID_Dept':$('#ID_Dept').val(),
				'ID_Simpanan':$('#ID_Simpanan').val(),
				'ID_Bulan'		:bln[0],
				'Tahun'			:bln[1]},
			function(result){
				if($('#cbayar').val()=='Potong'){
					$('table#ListTable tbody').html(result);
					$('#ptgaji').show();
					$('table#ListTable').fixedHeader({width:(screen.width-450),height:265});
				}else{
					$('#ptgaji').hide();
				}
				$('span#loading').hide();
			})
		})
	})
	//simpan transaksi
	$('#saved-simpanan').click(function(){
		var bln=$('#ID_Bulan').val().split('-');
		$.post('set_simpanan',{
			'ID_Jenis'		:$('#ID_Jenis').val(),
			'ID_Unit'		:$('#ID_Unit').val(),
			'ID_Simpanan'	:$('#ID_Simpanan').val(),
			'ID_Dept'		:$('#ID_Dept').val(),
			'ID_Perkiraan'	:$('#ID_Perkiraan').val(),
			'jumlah'		:$('#jumlah').val(),
			'keterangan'	:$('#keterangan').val(),
			'ID_Bulan'		:bln[0],
			'Tahun'			:bln[1]},
			function(result){
				if(result){
					$(':reset').click();
					$('#terbilang').hide();
				}else{
					alert(result);
				}
			})
	})
})
function simkh(id){
	$('input#n-'+id).removeAttr('disabled');
	pos_info('input#t-'+id,'kekata');
	$('input#t-'+id).terbilang({'output_div':'kekata'})
	//$('input#n-'+id).attr('checked','checked');	
}
function lostfocus(id){
	$('#kekata').hide();	
}
function bayar(id,jml,id_simpanan){
	var bln=$('#ID_Bulan').val().split('-');
	if($('#n-'+id+':checked').attr('checked')){
		$.post('set_potonggaji',{
			'ID_Jenis'		:$('#ID_Jenis').val(),
			'ID_Agt'		:id,
			'jumlah'		:(jml==0)?$('table#ListTable tr td input#t-'+id).val():jml,
			'ID_Simpanan'	:id_simpanan,
			'keterangan'	:$('#keterangan').val(),
			'ID_Bulan'		:bln[0],
			'Tahun'			:bln[1]
			},
		function(result){
			$('table#ListTable tr td input#t-'+id).attr('readonly','readonly');
		})
	}else{
		if(confirm("Yakin data ini akan di hapus/di batalkan?")){
			$('table#ListTable tr td input#t-'+id).val('0');
			$('table#ListTable tr td input#t-'+id).removeAttr('readonly');
		}else{
			$('table#ListTable tr td input#n-'+id).attr('checked',true)
		}
	}
}