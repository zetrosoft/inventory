$(document).ready(function(e) {
    var path=$('#path').val();
	$('#titipbarang').removeClass('tab_button');
	$('#titipbarang').addClass('tab_select');
	var tday=new Date;
	$('table#panel tr td:not(.flt,.plt)').click(function(){
		var id=$(this).attr('id');
				$('#'+id).removeClass('tab_button');
				$('#'+id).addClass('tab_select');
				$('table#panel tr td:not(#'+id+',.flt,.plt)').removeClass('tab_select');
				$('table#panel tr td:not(#'+id+',#kosong,.flt,.plt)').addClass('tab_button');
				$('span#v_'+id).show();
				$('span:not(#v_'+id+')').hide();
				$('#prs').val(id);
	})
	//$('table#ListTable').hide();
	$('#okelah').click(function(){
		_show_data(false);
	})
	$('#carilah').click(function(){
		_show_data(true);
	})
	$('#prt').click(function(){
		
	})
    
    $('#frm21 #printDO').click(function(){
      $.post('printDO',$('#frm11').serialize(),function(data){
        keluar_DO();
      })
  })
 
  $('#frm11 input#nm_pelanggan').focusin(function(){
        if($('#frm11 input#notransdo').val().length==10){
            _getNamaPelanggan($('#frm11 input#notransdo').val());
            
        }
        
    })
});
function _show_data(cari='false'){
    $.post('ListBarangTitipan',$('#frm1').serialize(),
           function(result){
        $('table#ListTable').show();
		$('table#ListTable tbody').html(result);
		$('table#ListTable').fixedHeader({width:(screen.width-100),height:(screen.height-372)});
    });
}
 //Delivery Order
    function _show_do(){
        $('#pp-DO').css({'left':'22%','top':'20%','height':'auto','z-index':'9998'});
		$('#lock').show();
        $('#pp-DO').show('slow');
		$('#frm11 input#notransdo').focus().select();
    }
    function _getDONotrans(notran){
        $.post('GetListTrans',{'notrans':notran},function(rst){
            $('#listdo tbody').html($.trim(rst));
            $.post('dropdownlokasi',{'id':''},function(d){
                //$('#frm21 select.cls').html($.trim(d));
            })
        })
    }
    function _getNamaPelanggan(notran){
        $.post('GetPelangganDO',{'notrans':notran},function(data){
            var r=$.parseJSON(data);
            $('#frm11 input#nm_pelanggan').val(r.Deskripsi);
            _getDONotrans($('#frm11 input#notransdo').val());
            $('#frm21 #simpanDO').removeAttr('disabled');
            $('#frm21 #printDO').removeAttr('disabled');
        });
    }
function chk_click(id){
              $.post('Set_ListDO',{
                'di_jual':id,
                'chk':$('#frm21 #id_'+id).is(':checked'),
                'id_barang':$('#frm21 #nn_'+id).val(),
                'id_satuan':$('#frm21 #ss_'+id).val(),
                'jumlah'    :$('#frm21 #nj_'+id).val(),
                'notrans'   :$('#frm11 #notransdo').val(),
                'nm_pelanggan':$('#frm11 #nm_pelanggan').val(),
                'lokasi':$('#frm21 #ls_'+id).val()
            },function(data){
                
            });

    }
    function do_select(id){
        if($('#frm21 #ls_'+id).val()!=''){
            $('#frm21 #id_'+id).attr('checked','checked');
            $('#frm21 #id_'+id).removeAttr("disabled");
            chk_click(id);
            var jml=$('#frm21 #nj_'+id).val();
            if(jml>1){
                $('#frm21 #nj_'+id).removeAttr('readonly').focus().select();
            }else{
                $('#frm21 #nj_'+id).attr('readonly','readonly')
            }
        }
        
    }
    function do_jml(id){
        //if(KeyboardEvent.bind==13){
            chk_click(id);
        //}      
    }

 /* added on 06-02-2016 */
    function ListDOMon(){
        $.post('GetListDO',{'status':'Y'},function(data){
           $('table#domon tbody').html($.trim(data));
           $('table#domon').fixedHeader({width:320,height:120})
        });
    }
    function images_click(id,aksi){
        switch(aksi){
            case 'pros':
                _show_doMobil(id);
                break;
        }
    }
    function _show_doMobil(notrans){
        $('#pp-DOM').css({'left':'22%','top':'20%','height':'auto','z-index':'9998'});
		$('#lock').show();
        $('#pp-DOM').show('slow');
		$('#frm115 input#notransdom').focus().select().val(notrans);
        $('#frm215 #listdoMobil tbody').html('');
        $('#frm115 #no_mobil').val('').select();
    }
    function _getDOtrans(notran,nomobil){
        $.post('GetListDOTrans',{'notrans':notran,'nomobil':nomobil},function(rst){
            $('#frm215 #listdoMobil tbody').html($.trim(rst));
            $('#printDOM').removeAttr('disabled');
            $('#addDO').removeAttr('disabled');
        })
    }
    function chk_click2(id){
        var st=($('#frm215 #id_'+id).is(':checked'))?'P':'N';
        var nmb=$('#frm115 #no_mobil').val();
        $.post('UpdateDoKirim',{
            'id':id,
            'stat':st,
            'mobil':nmb
        },function(data){
            
        });
    }
    function _cetakDOM(){
       var nmb=$('#frm115 #no_mobil').val();
       $.post('PrintDOM',{'Mobil':nmb},function(data){
           keluar_DOM();
       })
    }