var path = $('#path').val();
$(document).ready(function (e) {
    var prs = $('#prs').val();
    $('#v_satuan img.edit').hide();
    lock('#frm4 #saved-konv');
    $('#listbarang').removeClass('tab_button');
    $('#listbarang').addClass('tab_select');
    $('table#panel tr td').click(function () {
        var id = $(this).attr('id');
        if (id != '') {
            $('#' + id).removeClass('tab_button');
            $('#' + id).addClass('tab_select');
            $('table#panel tr td:not(#' + id + ',.flt)').removeClass('tab_select');
            $('table#panel tr td:not(#' + id + ',#kosong,.flt)').addClass('tab_button');
            $('span#v_' + id).show();
            $('span:not(#v_' + id + ')').hide();
            $('#prs').val(id);
            if (id == 'unitkonversi') {
                $('#frm4 td#c24').append("<span id='sat'>-</span>").next('input');
                $('table#panel tr td.flt').hide();
            } else if (id != 'listbarang') {
                $('table#panel tr td.flt').hide();
            } else {
                $('table#panel tr td.flt').show();
            };
            if (id == 'satuan') {};
        }
    });
    $('#frm1 #status_barang').attr('readonly', 'readonly').click(function () {
        auto_suggest3('get_status', $(this).val(), $(this).attr('id') + '-frm1');
        pos_div('#frm1 input#status_barang');
    });
    $('#frm9 :reset').click(function () {
        keluar();
    });
    $(':button').click(function () {
        var id = $(this).attr('id');
        var idr = id.split('-');
        var pos = $(this).offset();
        var l = pos.left + 5;
        var t = pos.top + 24;
        var induk = $(this).parent().parent().parent().parent().parent().attr('id');
        switch (id) {
            case 'add-' + idr[1]:
                $('#pp-' + idr[1]).css({
                    'left': l,
                    'top': t
                });
                $('#nama').val(idr[1]);
                $('#lock').show();
                $('#pp-' + idr[1]).show('slow');
                break;
            case 'saved-add':
                $.post('simpan_barang', {
                    'nm_jenis': $('#frm1 select#nm_jenis').val(),
                    'nm_kategori': $('#frm1 select#nm_kategori').val(),
                    'id_barang': $('#frm1 input#id_barang').val(),
                    'nm_barang': $('#frm1 input#nm_barang').val(),
                    'status_barang': $('#frm1 input#status_barang').val(),
                    'nm_satuan': $('#frm1 select#nm_satuan').val(),
                    'expired': $('#frm1 input#expired').val(),
                    'stokmin': $('#frm1 input#stokmin').val(),
                    'harga_3': $('#frm9 input#harga_3').val(),
                    'harga_1': $('#frm9 input#harga_1').val(),
                    'harga_2': $('#frm9 input#harga_2').val(),
					'id_barcode' :$('#frm9 input#id_barcode').val(),
                    'linked': ''
                }, function (result) {
                    $('#frm1 input:not(:button,:reset)').val('');
                    $('#frm1 select').val('').select();
                    $('#frm1 input#margin_jual').val('10');
                    $('#v_listbarang table#ListTable tbody').html(result);
                });
                break;
            case 'saved-jenis':
                $.post('simpan_jenis', {
                    'nm_jenis': $('#frm5 input#JenisBarang').val(),
                    'induk': '',
                    'linked': ''
                }, function (result) {
                    $('#frm1 select#nm_jenis').html(result);
                    $('#frm1 select#nm_jenis option:selected').text($('#frm5 input#JenisBarang').val().toUpperCase());
                    $('#frm5 input#JenisBarang').val('');
                    keluar();
                });
                break;
            case 'saved-kat':
                $.post('simpan_kategori', {
                    'nm_kategori': $('#frm6 input#Kategori').val(),
                    'induk': ''
                }, function (result) {
                    $('#frm1 select#nm_kategori').html(result);
                    $('#frm1 select#nm_kategori option:selected').text($('#frm6 input#Kategori').val().toUpperCase());
                    $('#frm6 input#Kategori').val('');
                    keluar();
                });
                break;
            case 'saved-subkat':
                $.post('simpan_golongan', {
                    'nm_golongan': $('#frm7 input#nm_golongan').val(),
                    'induk': '',
                    'linked': ''
                }, function (result) {
                    $('#frm1 select#nm_golongan').html(result);
                    $('#frm7 input#nm_golongan').val('');
                    keluar();
                });
                break;
            case 'saved-sat':
                $.post(path + 'inventory/simpan_satuan', {
                    'nm_satuan': $('#frm3 input#Satuan').val(),
                    'induk': '',
                    'linked': ''
                }, function (result) {
                    $('table#ListTable tbody').html(result);
                    $('#frm3 input#Satuan').val('');
                });
                break;
            case 'saved-satuan':
                $.post(path + 'inventory/simpan_satuan', {
                    'nm_satuan': $('#frm8 input#Satuan').val(),
                    'induk': 'frm3',
                    'linked': ''
                }, function (result) {
                    $('#v_satuan table#ListTable tbody').html(result);
                    $.post('dropdown', {
                        'tbl': 'inv_barang_satuan',
                        'field': 'Satuan'
                    }, function (result) {
                        $('#frm1 select#nm_satuan').html(result);
                        $('#frm1 select#nm_satuan option:selected').text($('#frm8 input#Satuan').val().toUpperCase());
                        $('#frm8 input#Satuan').val('');
                        keluar();
                    })
                });
                break;
            case 'saved-edit_mat':
               /**/ $.post(path + 'inventory/simpan_barang', {
                    'id_jenis': $('#id_jenis').val(),
                    'id_kategori': $('#id_kategori').val(),
                    'id_satuan': $('#id_satuan').val(),
                    'id_barang': $('#frm9 input#id_barang').val(),
                    'nm_barang': $('#frm9 input#nm_barang').val(),
                    'status_barang': $('#frm9 input#status_barang').val(),
                    'stokmin': $('#frm9 input#stokmin').val(),
                    'harga_3': $('#frm9 input#harga_3').val(),
                    'harga_1': $('#frm9 input#harga_1').val(),
                    'harga_2': $('#frm9 input#harga_2').val(),
                    'minstok': $('#frm9 input#stoklimit').val(),
                    'nm_jenis': $('#frm9 input#nm_jenis').val(),
                    'nm_kategori': $('#frm9 input#nm_kategori').val(),
                    'nm_satuan': $('#frm9 input#nm_satuan').val(),
					'id_item'	:$('#frm9 input#id_item').val(),
					'id_barcode' :$('#frm9 input#id_barcode').val(),
                    'linked': ''
                }, function (result) {
                    _show_data();
					keluar_edit_barang();
                });
                break;
            case 'saved-hgb':
                $.post('simpan_hgb', {
                    'nm_barang': $('#frm2 input#nm_barang').val(),
                    'nm_produsen': $('#frm2 input#nm_produsen').val(),
                    'hg_beli': $('#frm2 input#hg_beli').val(),
                    'sat_beli': $('#frm2 select#sat_beli').val()
                }, function (result) {
                    $('#v_hargabeli table#ListTable tbody').html(result);
                    $('#frm2 input#nm_barang').val('');
                    $('#frm2 input#nm_produsen').val('');
                    $('#frm2 input#hg_beli').val('');
                    $('#frm2 select#sat_beli').val('').select();
                    $('div#trbl').html('');
                });
                break;
            case 'saved-konv':
                unlock('#frm4 select#nm_satuan');
                $.post(path + 'inventory/simpan_konversi', {
                    'nm_barang': $('#frm4 input#nm_barang').val(),
                    'nm_satuan': $('#frm4 select#nm_satuan').val(),
                    'isi_konversi': $('#frm4 input#isi_konversi').val(),
                    'sat_beli': $('#frm4 select#sat_beli').val()
                }, function (result) {
                    $('table#konversi tbody').html(result);
                    $('#frm4 input#nm_barang').val('');
                    $('#frm4 select#nm_satuan').val('');
                    $('#frm4 input#isi_konversi').val('');
                    $('#frm4 select#sat_beli').val('').select();
                    $('sapn#sat').html('-');
                    lock('#frm4 #saved-konv');
                });
                break;
            case 'saved-edit_mat_det':
                simpan_detail();
                break;
        }
    });
    $('img.close').click(function () {
        var id = $(this).attr('id');
        keluar();
    });
    $('#frm1 input#nm_barang').coolautosuggest({
        url: 'data_material?fld=Nama_Barang&limit=10&str=',
        width: 350,
        showDescription: false,
        onSelected: function (result) {
            $('#frm1 #nm_jenis').val(result.jenis).select();
            $('#frm1 #nm_kategori').val(result.kategori).select();
            $('#frm1 #id_barang').val(result.kode);
            $('#frm1 #nm_satuan').val(result.satuan).select();
            $('#frm1 #status_barang').val(result.status);
        }
    });
    $('#frm1 input#id_barang').coolautosuggest({
        url: 'data_material?fld=Kode&limit=10&str=',
        width: 350,
        showDescription: true,
        onSelected: function (result) {
            $('#frm1 #nm_jenis').val(result.jenis).select();
            $('#frm1 #nm_kategori').val(result.kategori).select();
            $('#frm1 #nm_barang').val(result.description);
            $('#frm1 #nm_satuan').val(result.satuan).select();
            $('#frm1 #status_barang').val(result.status);
        }
    });
    $('#frm2 input#nm_barang').coolautosuggest({
        url: 'data_material?fld=Nama_Barang&limit=10&str=',
        width: 350,
        showDescription: false,
        onSelected: function (result) {
            $.post('get_unit_konv', {
                'nm_barang': result.description
            }, function (data) {
                $('#frm2 #sat_beli').html(data);
            });
            $('#frm2 #nm_produsen').val(result.pemasok);
            $('#frm2 #hg_beli').val(result.hargabeli);
            $('#frm2 #sat_beli').val(result.satuan).select();
        }
    });
    $('#frm4 input#nm_barang').coolautosuggest({
        url: path + 'inventory/data_material?fld=Nama_Barang&limit=10&str=',
        width: 350,
        showDescription: false,
        onSelected: function (result) {
            $('#frm4 #nm_satuan').val(result.satuan).select().attr('disabled', 'disabled');
            $.post(path + 'inventory/show_konversi', {
                'nm_barang': result.description
            }, function (result) {
                $('table#konversi tbody').html(result);
            });
        }
    });
    $('#frm2 input#hg_beli').focus(function () {
        $(this).val('0').select();
        $('div.autosuggest').hide();
        $('#frm2 input#nm_produsen').removeClass('loading');
    }).keyup(function () {
        $('#frm2 td#c23').append("<div id='trbl'></div>");
        $(this).terbilang({
            'style': "3",
            'output_div': "trbl",
            'output_type': "text"
        })
    });
    $('#frm5 input#nm_jenis');
    $('#frm6 input#nm_kategori');
    $('#frm7 input#nm_golongan');
    $('#frm3 #nm_satuan').click(function () {});
    $('#frm4 #isi_konversi').keyup(function () {
        $('#saved-konv').removeAttr('disabled');
    });
    $('#frm8 input#nm_satuan');
    $('#frm1 input#expired').keyup(function () {
        tanggal(this);
    }).keypress(function (e) {
        if (e.which == 13) {
            $('#stokmin').val('0');
            $('#stokmax').val('0');
            $('#stokmin').focus().select();
        }
    }).focus(function () {
        var today = $('#today').val();
        var expr = getNextDate(today, 180, '/');
        $(this).val(expr).select();
    });
    $('#frm1 input#stokmin').keyup(function () {
        $('#frm1 input#stokmax').val($(this).val());
    }).focus(function () {
        $(this).val('0').select();
        $('#frm1 input#stokmax').val('0');
    });
    $('#frm4 select#sat_beli').change(function () {
        $('#frm4 input#isi_konversi').val('1').focus().select();
        $('span#sat').html($('#frm4 select#nm_satuan option:selected').text());
    });
    $('#plh').change(function () {
       // _show_data(); update on 06-11-2013 
	   _show_jenis($(this).val());
    });
    $('#plh_jenis').change(function () {     
		_show_data();
    });
    $('#plh_stat').change(function () {
        _show_data();
    });
    $('#plh_cari').keyup(function () {
        if ($(this).val().length > 2) {
            _show_data();
        }
    });
    $('#frm9 #nm_kategori').coolautosuggest({
        url: 'get_kategori?limit=8&str=',
        width: 250,
        showDescription: false,
        onSelected: function (result) {
            $('#id_kategori').val(result.ID)
        }
    }).focusout(function () {
        if ($(this).val() == '') {
            alert('Kategori tidak boleh kosong');
        }
    });
    $('#frm9 #nm_jenis').focus(function () {}).coolautosuggest({
        url: 'get_jenis?limit=8&str=',
        width: 250,
        showDescription: false,
        onSelected: function (result) {
            $('#id_jenis').val(result.ID)
        }
    }).focusout(function () {
        ($(this).val() == '') ? $('#id_jenis').val('') : '';
    });
    $('#frm9 #nm_satuan').coolautosuggest({
        url: 'get_satuan?limit=8&str=',
        width: 250,
        showDescription: false,
        onSelected: function (result) {
            $('#id_satuan').val(result.ID)
        }
    });
    $('#frm9 #stokmin').focus(function () {
        $(this).select()
    }).keyup(function () {
        kekata(this);
    }).focusout(function () {
        kekata_hide();
        $('#frm9 #stokmax').focus().select()
    }).keypress(function (e) {
        if (e.which == 13) {
            $(this).focusout();
        }
    });
    $('#frm9 #stokmax').focus(function () {
        $(this).select()
    }).keyup(function () {
        kekata(this);
    }).focusout(function () {
        kekata_hide();
        $('#frm9 #stoklimit').focus().select()
    }).keypress(function (e) {
        if (e.which == 13) {
            $(this).focusout();
        }
    });
    $('#frm9 #stoklimit').focusout(function () {
        $(':button').focus()
    }).keypress(function (e) {
        if (e.which == 13) {
            $(this).focusout();
        }
    });
    $('#frm11 #hpp').focus(function () {
        $(this).select();
        kekata(this)
    }).keyup(function () {
        kekata(this);
    }).focusout(function () {
        kekata_hide();
    }).keypress(function (e) {
        if (e.which == 13) {
            $(this).focusout();
            $('#frm11 #harga_toko').focus().select()
        }
    });
    $('#frm11 #harga_toko').focus(function () {
        $(this).select();
        kekata(this)
    }).keyup(function () {
        kekata(this);
    }).focusout(function () {
        kekata_hide();
    }).keypress(function (e) {
        if (e.which == 13) {
            $(this).focusout();
            $('#frm11 #harga_partai').focus().select()
        }
    });
    $('#frm11 #harga_partai').focus(function () {
        $(this).select();
        kekata(this)
    }).keyup(function () {
        kekata(this);
    }).focusout(function () {
        kekata_hide();
    }).keypress(function (e) {
        if (e.which == 13) {
            $('#frm11 #harga_cabang').focus().select();
            $(this).focusout();
        }
    });
    $('#frm11 #harga_cabang').focus(function () {
        $(this).select();
        kekata(this)
    }).keyup(function () {
        kekata(this);
    }).focusout(function () {
        kekata_hide();
    }).keypress(function (e) {
        if (e.which == 13) {
            $('#frm11 #garansi').focus().select();
            $(this).focusout();
        }
    });
    $('#frm11 :reset').click(function () {
        keluar_edit_detail_barang();
    });
});
function _show_jenis(id)
{
/** 
 added on 06-11-2013
 Split jenis barang based on kategori
 */
	$.post(path +'inventory/getjenisbarang',{'id':id},function(result){
		$('#plh_jenis').html(result);
	})
}

function upd_det_barang(id) {
    $('#pp-edit_detail_barang').css({
        'left': '20%',
        'top': '15%'
    });
    $('#nama').val('edit_detail_barang');
    $('#frm11 input#nm_barang').attr('readonly', 'readonly').val(id);
    $.post(path + 'inventory/detail_material', {
        'ID': id
    }, function (data) {
        var result = $.parseJSON(data);
        $('#frm11 #sn').val(result.sn);
        $('#frm11 #hpp').val(to_number(result.hpp));
        $('#frm11 #harga_toko').val(to_number(result.harga_toko));
        $('#frm11 #harga_partai').val(to_number(result.harga_partai));
        $('#frm11 #harga_cabang').val(to_number(result.harga_cabang));
        $('#frm11 #mata_uang').val(result.mata_uang).select();
        $('#frm11 #garansi').val(result.garansi);
        $('#frm11 #panjang').val(result.ukuran);
        $('#frm11 #berat').val(result.berat);
        $('#frm11 #warna').val(result.warna);
		$('#frm11 #id_barcode').val(result.ID_Pemasok);
    });
    $('#lock').show();
    $('#pp-edit_detail_barang').show('slow');
}
function upd_barang(id) {
    var path = $('#path').val();
    $('#pp-edit_barang').css({
        'left': '20%',
        'top': '20%'
    });
    $('#nama').val('edit_barang');
    lock('#frm9 input#nm_barang');
    lock('#frm9 input#id_barang');
    $.post(path + 'inventory/edit_material', {
        'nm_barang': id
    }, function (result) {
        var obj = $.parseJSON(result);
        $('#frm9 input#nm_jenis').val(obj.JenisBarang);
        $('#frm9 input#nm_kategori').val(obj.Kategori);
        $('#frm9 input#id_barang').val(obj.Kode);
        $('#frm9 input#nm_barang').val(obj.Nama_Barang);
        $('#frm9 input#nm_satuan').val(obj.Satuan);
        $('#frm9 input#stokmin').val(obj.Harga_Beli);
        $('#frm9 input#harga_1').val(obj.Harga_Jual);
        $('#frm9 input#harga_2').val(obj.Harga_Cabang);
        $('#frm9 input#harga_3').val(obj.Harga_Partai);
        $('#frm9 input#stoklimit').val(obj.minstok);
        $('#id_kategori').val(obj.ID_Kategori);
        $('#id_jenis').val(obj.ID_Jenis);
        $('#id_satuan').val(obj.ID_Satuan);
		$('#frm9 input#id_item').val(obj.ID);
		$('#frm9 input#id_barcode').val(obj.ID_Pemasok).focus().select();
    });
    $('#lock').show();
    $('#pp-edit_barang').show('slow');
	//$('#frm9 input#id_barcode').focus().select();
};

function delet_barang(id, bat) {
    var path = $('#path').val();
    if (confirm('Yakin data ini  akan di hapus?')) {
        $.post(path + 'inventory/hapus_inv', {
            'tbl': 'inv_barang',
            'id': id,
            'fld': 'ID',
            'batch': bat
        }, function (result) {
            $('#v_listbarang table#ListTable tbody tr#nm-' + id).remove();
        })
    }
};
function lockStock(id,stat)
{
    $.post('UpdateLockStatus',{'id':id,'stat':stat},function(result){
       _show_data(); 
    });
}
function image_click(id, cl) {
    var path = $('#path').val();
    var induk = $('#' + id).parent().parent().parent().parent().parent().attr('id');
    var id = id.split('-');
    switch (cl) {
        case 'close':
            keluar();
            break;
        case 'edit':
            switch (induk) {
                case 'v_listbarang':
                    break;
                case 'v_satuan':
                    $('#pp-nm_satuan').css({
                        'left': '30%',
                        'top': '25%'
                    });
                    $('#frm8 input#nm_satuan').val(id[1]);
                    $('#nama').val('nm_satuan');
                    $('#lock').show();
                    $('#pp-nm_satuan').show('slow');
                    break;
                case 'v_hargabeli':
                    $('#frm2 input#nm_barang').val($('#v_hargabeli table#ListTable tbody tr#nm-' + id[0] + ' td:nth-child(2)').html());
                    break;
            }
            break;
        case 'del':
            switch (induk) {
                case 'v_listbarang':
                    ;
                    break;
                case 'v_satuan':
                    if (confirm('Yakin data ' + id[1] + '  akan di hapus?')) {
                        $.post('hapus_inv', {
                            'tbl': 'inv_barang_satuan',
                            'id': id[1],
                            'fld': 'Satuan'
                        }, function (result) {
                            $('#v_satuan table#ListTable tbody tr#nm-' + id[1]).remove();
                            $.post('dropdown', {
                                'tbl': 'inv_barang_satuan',
                                'field': 'Satuan'
                            }, function (result) {
                                $('#frm1 select#nm_satuan').html(result);
                            })
                        })
                    };
                    break;
                case 'v_unitkonversi':
                    break;
            }
    }
};

function keluar() {
    var nm = $('#nama').val();
    $('.autosuggest').hide();
    $('#pp-' + nm).hide('slow');
    $('#lock').hide();
};

function hapus_konv(id, aksi) {
    var path = $('#path').val();
    if (confirm('Yakin data akan di hapus?')) {
        $.post(path + 'inventory/hapus_konversi', {
            'tbl': aksi,
            'fld': id
        }, function (result) {
            $.post(path + 'inventory/show_konversi', {
                'nm_barang': $('#frm4 #nm_barang').val()
            }, function (result) {
                $('table#konversi tbody').html(result);
            });
        })
    }
};

function on_clicked(id, fld, frm) {
    switch (frm) {
        case 'frm2':
            if (fld == 'nm_barang') {
                $.post('data_hgb', {
                    'nm_barang': id
                }, function (result) {
                    var rst = $.parseJSON(result);
                    $('#frm2 input#nm_produsen').val(rst.nm_produsen);
                    $('#frm2 input#hg_beli').val(rst.hg_beli);
                    $('#frm2 select#sat_beli').val(rst.sat_beli).select();
                    $.post('list_hgb', {
                        'nm_barang': id
                    }, function (result) {
                        $('#v_hargabeli table#ListTable tbody').html(result);
                    });
                })
            };
            break;
        case 'frm4':
            if (fld == 'nm_barang') {
                $.post('data_konversi', {
                    'nm_barang': id
                }, function (result) {
                    var rst = $.parseJSON(result);
                    $('#frm4 select#nm_satuan').val(rst.nm_satuan).select();
                    lock('#frm4 select#nm_satuan');
                    unlock('#frm4 #saved-konv');
                    $('span#sat').html(rst.nm_satuan);
                    $.post('list_konversi', {
                        'nm_barang': id
                    }, function (result) {
                        $('#v_unitkonversi table#ListTable tbody').html(result);
                    });
                })
            };
            break;
    }
};

function _show_data() {
    var path = $('#path').val();
    show_indicator('ListTable', '12');
    $('plh_cari').val('');
    $.post(path + 'inventory/show_list', {
        'id': $('#plh').val(),
        'id_jenis': $('#plh_jenis').val(),
        'stat': $('#plh_stat').val(),
        'cari': $('#plh_cari').val(),
    }, function (result) {
        $('#v_listbarang table#ListTable tbody').html(result);
        $('#bawahan').html("<b>&bull;&bull;&bull; Total record :" + $('#v_listbarang table#ListTable tbody tr').length + "");
        $('#v_listbarang table#ListTable').fixedHeader({
            width: (screen.width - 30),
            height: (screen.height - 350)
        });
        $('#bawahan').show();
    })
};

function simpan_detail() {
    $.post(path + 'inventory/simpan_barang_detail', {
        'ID': $('#id_barang').val(),
        'nama': $('#frm11 #nm_barang').val(),
        'sn': $('#frm11 #sn').val(),
        'hpp': $('#frm11 #hpp').val(),
        'htk': $('#frm11 #harga_toko').val(),
        'htp': $('#frm11 #harga_partai').val(),
        'htc': $('#frm11 #harga_cabang').val(),
        'idr': $('#frm11 #mata_uang').val(),
        'garansi': $('#frm11 #garansi').val(),
        'ukuran': $('#frm11 #panjang').val(),
        'warna': $('#frm11 #warna').val(),
        'berat': $('#frm11 #berat').val()
    }, function (result) {
        $('div#result').html(result);
        $('div#result').fadeOut(3000);
        $('#frm11 :reset').click();
        keluar_edit_detail_barang();
    })
};