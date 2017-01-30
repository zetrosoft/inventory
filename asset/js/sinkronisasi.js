//javascript document
/**
 * Monitoring do baru
 * Check do di toko lain : 
 * CheckDO ->Check status do di server lain yng statusnya baru di buat
 * CopyDO ->Simpan do hasil dari server lain dengan status harga 0, 
 * getNotrans ->Dapatkan nomor transksi nya untuk siap di print
 * UpdateDoForPrint ->Update inv_penjualan_do dengan nomor transaksi tersebut di field harga=1
 * CheckOpenDOPrint ->Lakukan print do
 * added on jan 2017
 * untuk mengatasi jaringan yng tidak stabil
 */
var xpath=$('#server').val(); //server lain yng akan di baca do nya
var ori=$('#path').val();//client IP
var lokasi=$('#slok').val();//lokasi gudang / print

$(document).ready(function(){
    window.setInterval(function(){
      // hanya pc server yng melakukan sinkronisai data 
	   if(ori=='http://192.168.1.104/Toko/index.php/')
	   {
           CheckDO();
           CheckBelanja();
           CheckHargaJual();
           getNotrans();
	   }
    },20000)
})

function CheckDO()
{
    $.post(xpath+'sinkronized/CheckOpenDO',{'l':lokasi},function(data){
        var d=JSON.stringify(data);
        var arrD=d.replace("[","").replace("]","").split('},')
        //alert(arrD);
        if(d!="[]"){
            
            for(i=0;i<arrD.length;i++)
            {
                copyDO(arrD[i]);
            }
            getNotrans();
        }
    });
   
}
function getNotrans()
{
    $.post(path+'sinkronized/CheckDONotPrint',{l:lokasi},function(data){
        var d=(data!="'")?data:'';
        if(d!=''){
            UpdateDOForPrint($.tirm(d));
        }
       
    })
}
function UpdateDOForPrint(notrans)
{
    $.post(path+"sinkronized/updatedoPrint",{id:notrans},function(data){
        CheckOpenDOPrint();
    })    
}
function CheckOpenDOPrint()
{
    $.post(path+"penjualan/CheckOpenDO",{'id':''},function(result){});
}
function copyDO(data)
{
    $.post(ori+"sinkronized/simpando",{'data':(data)},function(result){
        var d=parseInt($.trim(result));
        //alert(d>0);
        if(d>0){
            $.post(xpath+"sinkronized/updatedo",{'id':d},function(rst){
                
            })
			$.post(ori+"sinkronized/updatestock",{'id':d},function(rst){
                
            })
        }
    });
}
function CheckBelanja()
{
  $.post(xpath+'sinkronized/CheklBelanja',{'l':lokasi},function(data){
        var d=JSON.stringify(data);
        var arrD=d.replace("[","").replace("]","").split('},')
        //alert(arrD);
        if(d!="[]"){
            
            for(i=0;i<arrD.length;i++)
            {
                UpdateStock(arrD[i]);
            }
        }
    });
}
function UpdateStock(data)
{
    $.post(ori+"sinkronized/UpdateInventory",{'data':data},function(result){
        var d=parseInt($.trim(result));
        if(d>0){
            $.post(xpath+'sinkronized/posting',{'id':d},function(rst){
                
            })
        }
    })
}
function CheckHargaJual()
{
   $.post(xpath+'sinkronized/CheklHargaJual',{'l':lokasi},function(data){
        var d=JSON.stringify(data);
        var arrD=d.replace("[","").replace("]","").split('},')
        //alert(arrD);
        if(d!="[]"){
            
            for(i=0;i<arrD.length;i++)
            {
				if(i==(arrD.length-1)){
				UpdateHargaJual(arrD[i]+"}");
				}else{
                UpdateHargaJual(arrD[i]+"}");
				}
            }
        }
    }) 
}
function UpdateHargaJual(data)
{
    $.post(ori+"sinkronized/UpdateHarga",{'data':data},function(result){
        var d=parseInt($.trim(result));
       if(d>0){
            $.post(xpath+'sinkronized/PostingStatus',{'id':d},function(rst){
                
            })
        }
    })
}