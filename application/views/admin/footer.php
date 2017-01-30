<div id="menu-bawah" align="center">
<table width='90%' style='border-collapse:collapse'>
<tr valign='middle'>
	<td width='15%'>
        <div id='result'></div>
        <div id='ajax_display' style='display:none'>
        <img src='<?=base_url();?>asset/images/ajax-loader.gif' />
        </div>
    </td>
	<td width='75%' align="right">
    <div class="copyright" style="padding:10px">
    <font color='#fff'><?="Public IP :".get_real_ip();?>&nbsp;&nbsp;<?= "Modul Stat : ".base64_decode($this->session->userdata('menus')).nbs(5);?>
 	&copy; Copyright <a href="http://www.zetrosoft.blogspot.com" target="_blank">zetrosoft</a>.2012 - Powered by :<a href="http://www.zetrosoft.com" target="_blank"> Zetrosoft&trade;</a></font></div></td>
</tr>
</table>
  <input type='hidden' value="<?=set_server();?>index.php/" id='server' />
  <input type='hidden' value="<?=$this->session->userdata('gudang')?>" id='slok'/>
</div>
<?
link_css('bootstrap.css','asset/css');
link_js('bootstrap-typeahead.js','asset/js');
link_js('jquery-ui-1.9.2.custom.min.js','asset/js');
link_js('sinkronisasi.js','asset/js');
?>
</body>
</html>

<?
function get_real_ip()
{
//$externalContent = file_get_contents('http://checkip.dyndns.com/');
//preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $externalContent, $m);
$externalIp = $_SERVER['REMOTE_ADDR'];
return $externalIp;
}
function set_server()
{
    $ipserver="";
    switch(get_real_ip())
    {
        case "192.168.1.104":
        case "192.168.1.105":
            $ipserver="http://192.168.1.101/toko/";
            break;
        case "192.168.1.100":
        case "192.168.1.101":
        case "192.168.1.107":
            $ipserver="http://192.168.1.104/toko/";
        break;    }
    return $ipserver;
}
?>
