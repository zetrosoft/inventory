<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

panel_begin('Todo');
panel_multi('todolist','block');
?>
<table width="100%" border="0">
  <tr bgcolor="#CCCCCC">
    <td colspan="3" class='b_line'><strong>TODO LIST</strong><span id='mark'></span></td>
  </tr>
  <tr>
    <td width="6%" height="300">&nbsp;</td>
    <td width="89%"><form name="form1" method="post" action="">
      <label for="Todolist"></label>
      <textarea name="Todolist" id="Todolist" cols="100" rows="25">
      <? echo empty($content)?'':
	  		  "\n".base64_decode(trim($content));
			  ?>
      </textarea>
    </form></td>
    <td width="5%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type='button' id='simpan' value='Simpan'>
    <input type='button' id='batal' value='Canel'></td>
    <td>&nbsp;</td>
  </tr>
</table>
<? panel_multi_end();
panel_end();
?>
<script language="javascript">
$(document).ready(function(e) {
    $('#simpan').click(function(){
		$.post('simpan_todo',{'todo':$('#Todolist').val()},
			function(result){
				$('span#mark').html('');
			})
	})
	$('#Todolist')
		.focus()
		.keyup(function(){
			$('span#mark')
				.css({'font-weight':'bold','color':'#F00'})
				.html('**');
		
		})
		
});


</script>