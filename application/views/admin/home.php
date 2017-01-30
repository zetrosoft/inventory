<div id="" style="display:; padding-top:70px;">
<? 
	$zz= new zetro_manager;
	$file='asset/bin/zetro_menu.dll';
	$z_config='asset/bin/zetro_config.dll';
	//link_css('jquery.alerts.css','asset/css');
	//link_js('jquery.alerts.js','asset/js');
?>
<table width="100%" border='0'>
<tr><td colspan="" align="center"></td><td>&nbsp;</td></tr>
<tr valign="bottom" align="" style="padding:20px">
    <td width='40%' align="center" valign="middle"><img src='<?=base_url();?>asset/img/about2.png' /></td>
    <td width='60%' valign="top">
        <table width='70%' border="0" id='x'>
        <tr align="center" valign="middle">
        <? $mnu=$zz->Count('Menu Utama',$file);
        $x=0;$klik='';
        $ver=$this->session->userdata('version');
            for ($i=1;$i<=$mnu;$i++ ){
                $gbr=explode('|',$zz->rContent('Menu Utama',$i,$file));
                if($gbr[3]!=''){
                    $x++;
                    $klik=(encode_php($ver)>='100')?'':"onclick=\"kliked('".base64_encode($gbr[4])."');\"";
                    if($x> 1){
                        echo tr('').
                        td("<img src='".base_url()."asset/img/".$gbr[3]."' class='menux' $klik>",'left',' \' height=\'69px\' valign=\'middle');
                       // _tr();
                    }else{
                    echo td("<img src='".base_url()."asset/img/".$gbr[3]."' class='menux' $klik>",'left',' \' height=\'69px\' valign=\'middle');
                    }
                }else{
                    echo '</tr>';
                }
            }

        ?>
        </tr>
        <? echo (encode_php($ver)>='100')? 
            tr().td('<font color="#000000"><b>Maaf masa aktif versi demo telah berakhir.<br>
            Untuk mendapatkan versi yang full silahkan hubungi:<br><br>
            <i>email : zetrosoft@yahoo.com </i><br><br>
            Dengan menyertakan kode aktifasi dibawah ini<br>
            Kode Aktifasi :<i>'. no_ser().'<br>
            </b>Atau klik tombol Registrasi jika sudah mempunyai Serial Number Applikasi ini</font><br>
            <input type="button" value="Registrasi" id="reg">','left','')._tr():'';
            ?>
                <tr><td id='result'>&nbsp;</td></tr>
        </table>
    </td>
</tr>
</table>
<hr />
<div id='xxx' align="right">
 <img src='<?=base_url();?>asset/img/logout.png' onclick="logout();" width="50" height="50" style='cursor:pointer' />
</div>
<? $serial=$this->session->userdata('version');
	?>
<input type='hidden' id='lcs' value='<?=empty($serial)?'x2cdg':$serial;?>' />
<input type='hidden' id='vers' value="<?=encode_php();?>" class='w100'/>
</div>
<script language="javascript">$(document).ready(function(e){var path=$('#path').val();$('div.menu').hide();$('#popup_message #popup_prompt').keyup(function(e){var l=$(this).val().length;alert(l);});$('#reg').click(function(){jPrompt('Masukan Serial Number','','Registrasi',function(r){if(r){$.post('validity',{'ns':r},function(result){$('table#x tr td#result').html(result);($.trim(result)!=$.trim('Serial Number yang ada masukan salah Salah'))?$.post('validity_ok',{'id':r},function(res){document.location.href=path+'admin/index';}):"";})}})})});function kliked(id){var path=$('#path').val();document.location.href=path+'admin/masuk?id='+id;};function logout(){var path=$('#path').val();document.location.href=path+'admin/logout';};</script>
