<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_member.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_member.frm');
$path='application/views/member';
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js,auto_sugest.js','asset/js,asset/js');
link_js('jquery.fixedheader.js,jquery_terbilang.js,member_simpanan.js','asset/js,asset/js,'.$path.'/js');
panel_begin('Simpanan');
panel_multi('transaksisimpanan','block');
if($all_pembayarantagihan!=''){
	$zfm->AddBarisKosong();
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('simpanan',true,'50%');
	echo "<tr id='Tun' style='display:none'>
		  <td class='border_b'>&nbsp;&nbsp; Nama Anggota</td>
		  <td><input type='text' id='nm_agt' name='nm_agt' class='w90 cari'></td>
		  <td>&nbsp;</td>
		  </tr>\n
		  <tr id='Tune' style='display:none'>
		  <td class='border_b'>&nbsp;&nbsp; Nomor Perkiraan</td>
		  <td><input type='text' id='ID_Perkiraan' name='ID_Perkiraan' class='w50'></td>
		  <td>&nbsp;</td>
		  </tr>\n
		  <tr id='Tun' style='display:none'>
		  <td class='border_b'>&nbsp;&nbsp; Jumlah</td>
		  <td><input type='text' id='jumlah' name='jumlah' class='w35 angka'><div id='terbilang' class='infox'></div></td>
		  <td></td>
		  </tr>\n
		  <tr id='Tun' style='display:none'>
		  <td class='border_b'>&nbsp;&nbsp; Keterangan</td>
		  <td><textarea id='keterangan' name='keterangan' class='t90'></textarea></td>
		  <td>&nbsp;</td>
		  </tr>\n
		  <tr id='Tran' style='display:none'>
		  <td class='border_b'>&nbsp;&nbsp; No. Referensi</td>
		  <td><input type='text' id='noreff' name='noreff' class='w70'></td>
		  <td>&nbsp;</td>
		  </tr>\n
		  <tr><tc colspan='3'>&nbsp;</td></tr>\n
		  <tr id='Tun' style='display:none'>
			<td>&nbsp;</td>
			<td><input type='button' id='saved-simpanan' title='Simpan' value='Simpan'><input type='reset' id='batal' value='Cancel' Title='Cancel'></td>
			<td></td>
			</tr></table>\n";
	echo "<span id='loading' style='display:none'>
	<font style='color:#000'><b>Data being processed, please wait...</b><img src='".base_url()."asset/img/indicator.gif'></span>
	<span id='ptgaji' style='display:none; width:60%'>";
		$zlb->section('potonggaji');
		$zlb->aksi(true);
		$zlb->Header('100%');
		$zlb->icon();
		echo "</tbody></table>";
	echo "</span>";
	//$zfm->BuildFormButton('Simpan','simpanan');
}else{
	no_auth();
}
panel_multi_end();
panel_end();
echo "<div id='kekata' class='infox'></div>";
?>