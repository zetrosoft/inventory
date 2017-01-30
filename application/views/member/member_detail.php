<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_member.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_member.frm');
calender();
link_js('jquery.fixedheader.js,zetro_number.js','asset/js,asset/js');
link_js('jquery_terbilang.js','asset/js');
tab_head('Member Detail',"style='background:#333'");
panel_multi('datapelanggan','block',false);
echo "<table width='100%' border='0'>
	<tr valign='top'><td width='45%'>";
		$zfm->AddBarisKosong(false);
		$zfm->Start_form(true,'frm1');
		$zfm->BuildForm('registrasi',false,'100%');
echo "</td><td width='5%'>&nbsp;</td>
	 <td width='50%'>";
		$zfm->AddBarisKosong(false);
		$zfm->Start_form(true,'frm2');
		$zfm->BuildForm('biodata',false,'100%');
echo"<!--hr><div id='pic' style='height:350px; border:1px inset #FFF'>
	<img src='' id='photone' width='500px' height='350px'></div>";
echo "</td></tr-->
	 </table>
		<hr>
		<div id='btn' style='width:90%; border:0px outset #CCC;padding:5px; padding-right:20px' align='right'>
		<input type='button' id='update_b' value='Update Data'>
		<input type='button' id='delet_b' value='Hapus'>
		<input type='button' id='keluar_b' value='Tutup'></div>";
panel_multi_end();
panel_multi('membertrans','none',false);
addText(array('No. Anggota','Nama Lengkap','Department'),
		array("<input type='text' id='no_anggota' readonly value='$no_anggota'>",
			  "<input type='text' id='nm_anggota' readonly value='$nm_anggota'>",
			  "<input type='text' id='id_department' readonly value='$id_department' size='40'>"));
		$zlb->section('detail');
		$zlb->aksi(false);
		$zlb->Header('90%','detail_tbl');
		$zlb->icon();
		$n=0;
		foreach($transaksi->result() as $trn){
			$n++;
			echo "<tr class='xx' id='t-".$trn->ID."' align='center' onclick=\"get_detail_trans('".$trn->ID."');\">
				  <td class='kotak'>$n</td>
				  <td class='kotak'>".$trn->Kode."</td>
				  <td class='kotak' align='left'>".$trn->Jenis."</td>
				  <td class='kotak' align='right'>".number_format($trn->SaldoAwal,2)."</td>
				  <td class='kotak' align='right'>".number_format($trn->Debet,2)."</td>
				  <td class='kotak' align='right'>".number_format($trn->Kredit,2)."</td>
				  <td class='kotak' align='right'>".number_format($trn->SaldoAkhir,2)."</td>
				  </tr>";
		}
		echo "</tbody></table><hr>";
		echo "<div id='loading' style='display:none'>Loading data in progress ...please wait. &nbsp;<img src='".base_url()."asset/img/indicator.gif'></div>";
		echo "<div id='dtl_trans' style='display:none'>";
		echo "<p style='color:#000'>Detail transaksi <span id='dtl'></span> :</p>";
		$zlb->section('DetailTrans');
		$zlb->aksi(false);
		$zlb->Header('100%','trans_tbl');
		$zlb->icon();
		$n=0;
		echo "</tbody></table></div>
		<hr>
		<div id='btn' style='width:90%; border:0px outset #FFF;padding:5px; padding-right:20px' align='right'>
		<input type='button' id='cetak' value='Cetak'>
		<input type='button' id='keluar' value='Tutup'>
		<input type='hidden' id='kunci' value='$kunci' />
		<input type='hidden' id='kunci_cetak' value='' /></div>";
panel_multi_end();
tab_head_end();
terbilang();
?>
<script language="javascript">
$(document).ready(function(e) {
	get_biodata();
    $('#frm2 #pangkalan').html("<? dropdown('pangkalan','nama','nama',"where stat='Y'");?>");  
	$('#detail_tbl').fixedHeader({width:(screen.width-50),height:150})
    $('#frm2 #TanggalMasuk').dynDateTime();
	$('#cetak').click(function(){
		var id=$('#kunci_cetak').val();
	})
    $('#lpg').val('N');
	$('#keluar').click(function(){
		$('#mm_lock').hide();	
		$('#mm_detail').hide();
	})
	$('#keluar_b').click(function(){
		$('#mm_lock').hide();	
		$('#mm_detail').hide();
	})
	$('#update_b').click(function(){
		update();
	})
    $('#delet_b').click(function(){ hapus();});
	$('#Status')
		.keyup(function(){ kekata(this)})
		.focusout(function(){kekata_hide()})
		.keypress(function(e){ (e.which==13)? kekata_hide():''})
});
    function get_biodata(){
        $.post('member_biodata',{'no_anggota':$('#kunci').val()},
            function(result){
                var obj=$.parseJSON(result);
                    $('#frm1 #No_Agt')
                        .val(obj.No_Agt)
                        .attr('readonly','readonly')
                    $('#frm1 #Catatan').val(obj.Catatan).select();
                                //$('#frm1 #NIP').val(obj.NIP);
                    $('#frm1 #Nama').val(obj.Nama);
                                //$('#frm1 #ID_Kelamin').val(obj.ID_Kelamin).select();
                    $('#frm1 #Alamat').val(obj.Alamat);
                    $('#frm1 #Kota').val(obj.Kota);
                    $('#frm1 #Propinsi').val(obj.Propinsi);
                    $('#frm1 #Telepon').val(obj.Telepon);
                    $('#frm1 #Faksimili').val(obj.Faksimili);
                    $('#frm1 #Status').val(obj.Status);
                    $('#frm2 #TanggalMasuk').val(tglFromSql(obj.TanggalMasuk));
                    $('#frm2 #idm').val($('#kunci').val());
                    $('#frm2 #plpg').val(obj.ID_Check).select();
                    $('#frm2 #pangkalan').val(obj.NamaPangkalan).select();
                    $('#frm2 #maxlpg').val(obj.ID_Kelamin);
                    $('#frm1 #NIP').val(obj.NIP).select();
                    $('#frm2 #barcode').val(obj.No_Perkiraan);
                })

    }

    function get_detail_trans(id){
        $('div#dtl_trans').hide();
        $.post('get_nama_simpanan',{'id':id},
            function(result){
                $('span#dtl').html('<strong>'+result+'</strong>');
            })
        $('#kunci_cetak').val(id);
        $('#loading').show();
        var k=$('#kunci').val();
        $.post('member_detail_trans',{
            'ID_Jenis'	:id,
            'ID_Agt'	:k
        },
    function(result){
                $('table#trans_tbl tbody').html(result);
                $('#trans_tbl').fixedHeader({width:(screen.width-150),height:195})
                $('#loading').hide();
                $('div#dtl_trans').show();
            })
    }
	function update(){
				$.post('set_anggota',$('#frm1').serialize()+'&'+$('#frm2').serialize(),
					function(result){
						get_biodata();
						document.location.reload();
						$('#keluar').click();
					})
	}
    function hapus(){
        $.post('delete_member',{'id_member':$('#kunci').val()},function(res){
           get_biodata();
            document.location.reload();
			$('#keluar').click();
        });
    }
</script>
