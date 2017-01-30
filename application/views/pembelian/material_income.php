<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_beli.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_beli.frm');
$path='application/views/pembelian';
Calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('jquery.fixedheader.js','asset/js');
link_js('material_income_list.js',$path.'/js');
link_js('material_income.js,jquery_terbilang.js',$path.'/js,asset/js');
panel_begin('Penerimaan Vendor');
panel_multi('inputpembelian','block',false);
if($all_inputpembelian!=''){
	$zfm->AddBarisKosong(false);
	$zfm->Start_form(true,'frm1');
    //$zfm->Addinput("<input type='text' id='jtempo' name='jtemnpo' value=''/>");
	$zfm->BuildForm('pembelian',false,'60%');
    $txt=tr().
		 td("1<input type='hidden' id='1__id_barang' name='1__id_barang' value=''>",'center').
		 td("<input type='text' id='1__nm_barang' class='w100 upper xx n_1' name='1__nm_barang' data-provide='typeahead' data-source=\"[".$datas."]\" value=''/>").
		 td("<select id='1__nm_satuan' class='S100 ' name='1__nm_satuan'></select>").
		 td("<input type='text' id='1__jml_transaksi' class='w100 angka' name='1__jml_transaksi' value=''/>").
		 td("<input type='text' id='1__harga_jual' class='w100 angka' name='1__harga_jual' value=''/>").
		 td("<input type='text' id='1__harga_total' class='w100 angka subtt' name='1__harga_total' value=''/>").
		 td("<select  id='1__lokasi' class='S100' name='1__lokasi'>
                <option value='1'>Pusat</option>
                <option value='3'>Gudang</option>
                <option value='4'>MGM</option>
            </select>").
           td("<img src='".base_url()."asset/images/save.png' title='Hapus transaksi' onclick=\"image_click('1','simpan');\">","center").
		_tr();
	echo "<hr><form id='frm2' name='frm2' method='post' action=''>";
		$zlb->section('pembelianlistNew');
		$zlb->aksi(true);
		$zlb->Header('97%','ListTable',$txt);
		echo "<tr><td class='kotak' colspan='8'>&nbsp;</td>";
		echo "</tbody><tfoot>";
		/*$zfm->section('pembelianlist');
		$zfm->rowCount();
		$zfm->button('simpan');
		$zfm->BuildGrid();*/
    
		echo "</tfoot></table></form>";
}else{
	no_auth();
}
panel_multi_end();
panel_multi('listpenerimaan','none',false);
if($all_listpenerimaan!=''){
addText(array('Periode Dari','Sampai',''),
		array("<input type='text' id='dari_tanggal' value='' class='w100'>",
			  "<input type='text' id='smp_tanggal' value='' class='w100'>",
			  "<input type='button' id='okelah' value='OK'>"));
		$zlb->section('lappembelian');
		$zlb->aksi(($e_listpenerimaan!='')?true:false);
		$zlb->Header('100%','newTable');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();
auto_sugest();
tab_select('prs');
terbilang();
?>
<input type='hidden' id='idaktif' value='1__jml_transaksi'/>
<input type="hidden" id='id_sat' value='' />
<input type="hidden" id='id_brg' value='' />
<input type="hidden" id='total_beli' value='0' />
<input type="hidden" id='trans_new' value='' />
<input type="hidden" id='id_pemasoke' value='' />
<input type='hidden' id='aktif_user' value='<?=$this->session->userdata('idlevel');?>'/>

