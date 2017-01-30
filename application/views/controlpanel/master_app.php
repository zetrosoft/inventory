<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/controlpanel';
link_js('master_app.js',$path.'/js');
panel_begin('Preference');
panel_multi('profile','block',false);
if($all_profile!=''){
	$zfm->AddBarisKosong(true);
	$zfm->Start_form(true,'frm1');
	$zfm->BuildForm('Profile',true,'50%');
	$zfm->BuildFormButton('Simpan','company');
}else{ 
	no_auth();
}
echo "<table id='rst'><tbody><tr><td></td></tr></tbody></table>";
panel_multi_end();
?>
<input type='hidden' id='cek' value='<?=$c_profile;?>'>