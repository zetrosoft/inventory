<?php
$zfm=new zetro_frmBuilder('asset/bin/zetro_master.frm');
$zlb=new zetro_buildlist();
$zlb->config_file('asset/bin/zetro_master.frm');
$path='application/views/admin';
link_css('jquery.alerts.css','asset/css');
link_js('jquery.alerts.js','asset/js');
panel_begin('User Admin');
panel_multi('activation','block',false);

echo '<img src="'.base_url().'asset/img/zetropos.png"><hr><h3><font color="#0000FF">';
echo ($serial!='Demo Version')?
	"Your Application has Full Version <br><br> Serial Number :".$serial."<p> Thanks you and enjoy" :
	"Your Are In Demo Version.<br></h3>
	 please contact <a href='mailto: contact@zetrosoft.com'>contact@zetrosoft.com</a><br>
	 for get full version and please write the activation kode below in your email.<br>
	    Activation Code : ".no_ser()."<br><h4><i> 
		or Click Activation button below if you have Serial Number (xxxx-xxxx-xxxx-xxxx-xxxx-xxxx) <br><br>
		<input type='button' value='Activation' id='reg'>";
	
echo "</font></h4><table id='rst'><tbody><tr><td></td></tr></tbody></table>";
panel_multi_end();
panel_end();
?>
<script language="javascript">
var path=$('#path').val();
$(document).ready(function(e) {
		$('#popup_message #popup_prompt').keyup(function(e){
			var l=$(this).val().length;
			alert(l)
		})
		$('#reg').click(function(){
			jPrompt('Masukan Serial Number','','Registrasi',function(r){
				if(r){
					//alert(r);
					$.post('validity',{'ns':r},
					function(result){
						$('table#x tr td#result').html(result);
						($.trim(result)!=$.trim('Serial Number yang ada masukan salah Salah'))?
						 $.post('validity_ok',{'id':r},
						 function(res){
							document.location.href=path+'admin/index'
						 }):""
						//document.location.reload();
					})
/**/				}
			})
		})
});

</script>