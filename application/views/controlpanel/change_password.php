<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_user.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_user.frm');
$path='application/views/controlpanel';
link_css('autosuggest.css','asset/css');
link_js('change_password.js',$path.'/js');
tab_select('');
panel_begin('Ganti Password');
panel_multi('changepassword','bock');
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('changepwd',true,'70%');
	$zfm->BuildFormButton('Simpan','changepwd');
panel_multi_end();
panel_end();

?>