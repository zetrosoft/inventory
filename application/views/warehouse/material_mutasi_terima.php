<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$zfm=new zetro_frmBuilder('asset/bin/zetro_inv.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_inv.frm');
$path='application/views/warehouse';
Calender();
link_css('jquery.coolautosuggest.css','asset/css');
link_js('jquery.coolautosuggest.js','asset/js');
link_js('jquery.fixedheader.js','asset/js');
link_js('material_mutasi_terima.js',$path.'/js');
link_js('jquery_terbilang.js','asset/js');
panel_begin('Penerimaan Mutasi');
panel_multi('penerimaanmutasi','block',false);
if($all_penerimaanmutasi!=''){
addText(array('No. Transaksi',''),
		array("<input type='text' id='no_trans' value='' class='w100'>",
			  "<input type='button' id='okelah' value='OK'>"));
		$zlb->section('lapmutasi');
		$zlb->aksi(true);
		$zlb->Header('100%','newTable');
		echo "</tbody></table>";
}else{
	no_auth();
}
panel_multi_end();
panel_end();

?>