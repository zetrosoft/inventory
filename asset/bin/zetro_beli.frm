[Carabeli]
1|Cash,Cash
2|Tempo,Tempo
3|Transfer,Transfer
4|Check,Check
5|Return,Return

[GrpNasabah]
1|Harga_Jual,Umum
2|Harga_Cabang,Toko
3|Harga_Partai,Grosir

[pembelian]
1|No. ID,input,text n,no_transaksi,w50 upper,,10%
2|Tanggal,input,text t,tgl_transaksi,w35,,8%
3|No.Faktur Pembelian,input,text n,faktur_transaksi,w70 upper,,12%
4|Nama Pemasok,input,text n,nm_produsen,w90 upper,,15%
5|No. PO,input,text n,po_pembelian,w70 upper,,10%
6|Jenis Pembelian,select,text n,cara_bayar,S50,,5%,RD,inv_pembelian_jenis-ID-Jenis_Beli-,
7|Jatuh Tempo,input,text n,jtempo,w25,,,

[pembelianlist]
1|Kode Barang,input,text t,id_barang,w100,,15%
2|Nama Barang,input,text n,nm_barang,w100 upper,,30%
3|Satuan,select,text n,nm_satuan,S100 upper,,12%
4|Jumlah,input,text d,jml_transaksi,w100 angka,,12%
5|Harga Beli/Satuan,input,text d,harga_beli,100 angka,,12%
6|Total Harga,input,text n,ket_transaksi,w100 angka,,15%


[pembelianlistNew]
1|Nama Barang,input,text n,nm_barang,w100 upper,,35%
2|Satuan,input,text n,nm_satuan,w100 upper,,8%
3|Jumlah,input,text d,jml_transaksi,w100 angka,,10%
4|Harga,input,text d,harga_jual,w100 angka,,10%
5|Total Harga,input,text d,harga_total,w100 angka subtt,,10%
6|Lokasi,select,text,lokasi,S100 upper,,10%

[lappembelian]
1|No. Transaksi,,,,,,15%,,
2|Tanggal,,,,,,10%,,
;3|Kode Barang,input,text t,id_barang,w100,,15%
3|Nama Barang,input,text n,nm_barang,w100 upper,,25%
4|Satuan,select,text n,nm_satuan,S100 upper,,8%
5|Jumlah,input,text d,jml_transaksi,w100 angka,,8%
6|Harga Beli,input,text d,harga_beli,100 angka,,10%
7|Sub Total,input,text n,ket_transaksi,w100,,15%

[jualan]
1|No. ID,input,text n,no_transaksi,w50 upper,,10%
2|Tanggal,input,text t,tgl_transaksi,w35,,8%
;3|No.Faktur,input,text n,faktur_transaksi,w70 upper,,12%
4|Group Pelanggan,select,text n,grp_nasabah,S50,,10%,RS,GrpNasabah,
3|Nama Pelanggan,input,text n,nm_nasabah,w70 upper' data-provide='typeahead' data-items='15,,15%,,,
;5|Cara Bayar,select,text n,cara_bayar,S25,,5%,RS,Carabeli

[penjualanlist]
1|Nama Barang,input,text n,nm_barang,w100 upper,,25%
2|Satuan,input,text n,nm_satuan,w100 upper,,8%
3|Jumlah,input,text d,jml_transaksi,w100 angka,,12%
4|Harga,input,text d,harga_jual,w100 angka,,10%
5|Total Harga,input,text d,harga_total,w100 angka subtt,,10%
6|Expired,input,text t,expired,w100,,15%

[penjualanlist2]
1|Nama Barang,input,text n,nm_barang,w100 upper,,25%
2|Satuan,input,text n,nm_satuan,w100 upper,,8%
3|Jumlah,input,text d,jml_transaksi,w100 angka,,12%
4|Harga,input,text d,harga_beli,w100 angka,,10%
5|Total Harga,input,text d,ket_transaksi,w100 angka subtt,,10%
6|Expired,input,text t,expired,w100,,15%

[bayaran]
1|Sub Total ,input,text d,total_belanja,w90 angka big,,
2|Diskon (Rp.),input,text d,ppn,w90 angka big,0,
4|Total Bayar,input,text d,total_bayar,w90 angka big,,
3|Nota,input,text d,nota,w90 angka big,0,
5|Di Bayar,input,text d,dibayar,w90 angka big,,,,,,<input type='checkbox' id='nbr' name='nbr' title='Simpan Barang'>*
6|Kembali,input,text d,kembalian,w90 angka big,,,,,,<input type='checkbox' id='nitip' name='nitip' title='Simpan Uang'>**
7|Nota dibayar,select,text n,chkNota,s25,,,RS,ok

[resep]
1|No. Transaksi,input,text n,no_transaksi,w50,,
2|No. Resep,input,text n,no_resep,w50 upper,,
3|Tanggal Resep,input,text t,tgl_resep,w35,,
4|Nama Dokter,input,text n,nm_dokter,w70 upper,,
5|Nama Pasien,input,text n,nm_nasabah,w70 upper,,


[return]
1|No. Transaksi,input,text n,NoUrut,w50,,
2|Tanggal,input,text t,Tanggal,w35,,
3|Nama Pelanggan,input,text n,Nama,w90 upper,,
4|Nama Barang,input,text n,Nama_Barang,w70 upper,,
5|Satuan,input,text n,ID_Satuan,w35 upper,,
6|Jumlah,input,text d,Jumlah,w35 angka,,
7|Harga Beli,input,text d,harga_beli,w35 angka,,

[return_beli]
1|,input,hidden n,no_transaksi,w50 upper,,10%
2|Doc.No.,input,text n,no_doc,w50 upper,,10%
3|Tanggal,input,text t,tgl_transaksi,w35,,8%
4|No.Faktur,input,text n,faktur_transaksi,w70 upper,,12%
5|Nama Vendor,input,text n,nm_nasabah,w70 upper,,15%,,,
6|Nama Barang,input,text n,nm_barang,w90 upper,,25%
7|Satuan,input,text n,nm_satuan,w35 upper,,8%
8|Jumlah,input,text d,jml_transaksi,w35 angka,,12%
9|Harga Beli,input,text d,harga_beli,w35 angka,,10%
;10|Total Harga,input,text d,total_harga,w35 angka subtt,,10%
10|Expired,input,text t,expired,w35,,15%

[lapbeli]
1|Tanggal Pembelian,,,,,
2|&nbsp;&nbsp;&nbsp;Dari Tanggal,input,text t,dari_tgl,w35,,
3|&nbsp;&nbsp;&nbsp;Sampai Tanggal,input,text t,sampai_tgl,w35,,
4|Kategori,select,text n,nm_golongan,S70,,,RD,inv_barang_kategori-ID-Kategori-
5|Nama Vendor,input,text n,nm_produsen,w90 upper,,
6|Susun Berdasarkan,select,text n,susunan,S70,,,RS,SusunanBeli
7|&nbsp;&nbsp;&nbsp; Jenis Urutan,select,textn,urutan,S50,,,RS,Urutan

;array(10,22,70,15,25,25,30,40,40)
[lapbelilist]
1|Tanggal,input,text t,tgl_transaksi,w35,,10%,,,25
2|Nama Barang,input,text n,nm_barang,w100 upper,,25%,,,70
3|Satuan,input,text n,nm_satuan,w100 upper,,10%,,,18
4|Jumlah,input,text d,jml_transaksi,w100 angka,,12%,,,25
5|Harga Beli,input,text d,harga_beli,100 angka,,12%,,,30
;6|Vendor,input,text n,nm_produsen,w100,,20%,,,60
6|Total harga,input,text n,faktur_transaksi,,,20%,,,40

[lapjual]
1|Tanggal Penjualan,,,,,
2|&nbsp;&nbsp;&nbsp;Dari Tanggal,input,text t,dari_tgl,w35,,
3|&nbsp;&nbsp;&nbsp;Sampai Tanggal,input,text t,sampai_tgl,w35,,
4|Kategori Barang,select,text n,nm_jenis,S70,,,RD,inv_barang_kategori-ID-Kategori-
5|Nama Pelanggan,input,text n,nm_dokter,w90 upper,,
6|Susun Berdasarkan,select,text n,susunan,S70,,,RS,SusunanJual
7|&nbsp;&nbsp;&nbsp; Jenis Urutan,select,textn,urutan,S50,,,RS,Urutan

[SusunanJual]
1|Nama_Barang,Nama Barang
2|Nama,Nama Pelanggan
3|dt.Tanggal-Nama_Barang,Tanggal Penjualan dan Nama Barang
4|Nama-Nama_Barang, Nama Pelanggan dan Nama Barang
5|Jumlah-Nama_Barang,Jumlah dan Nama Barang

[SusunanBeli]
1|Nama_Barang,Nama Barang
2|Pemasok,Nama Vendor
3|dt.Tanggal-Nama_Barang,Tanggal Pembelian dan Nama Barang
4|Pemasok-Nama_Barang, Nama Vendor dan Nama Barang
5|Jml_Faktur-Nama_Barang,Jumlah dan Nama Barang
6|Jumlah,Harga Beli

[SusunanStock]
1|Nama_Barang,Nama Barang
2|k.Kategori-Nama_Barang,Kategori dan Nama Barang
3|ms.stock-Nama_Barang,Stock dan Nama Barang 
4|sum(ms.harga_beli),Harga Pembelian

[Urutan]
1|asc,Kecil ke Besar (A-Z)
2|desc,Besar ke Kecil (Z-A)

;array(10,22,70,15,25,25,30,40,40)
[lapjuallist]
1|Tanggal,input,text t,tgl_transaksi,w35,,10%,,,22
2|Nama Barang,input,text n,nm_barang,w100 upper,,25%,,,65
3|Satuan,input,text n,nm_satuan,w100 upper,,10%,,,18
4|Jumlah,input,text d,jml_transaksi,w100 angka,,12%,,,25
5|Harga Beli,input,text t,expired,w100,,15%,,,25
6|Harga Jual,input,text d,harga_beli,100 angka,,12%,,,28
7|Consumen,input,text n,nm_produsen,w100,,20%,,,40
8|Keterangan,input,text n,faktur_transaksi,,,20%,,,45

[kredite]
1|Sub Total ,input,text d,total_belanja,w90 angka big,,
2|Diskon,input,text d,ppn,w90 angka big,0,
3|Total Bayar,input,text d,total_bayar,w90 angka big,,
4|Uang Muka,input,text d,dibayar,w90 angka big,0,
5|Sisa,input,text d,kembalian,w90 angka big,,
6|,input,hidden d,cicilan,w50 angka big,1,


[lapjualresep]
1|Tanggal Penjualan,,,,,
2|&nbsp;&nbsp;&nbsp;Dari Tanggal,input,text t,dari_tgl,w35,,
3|&nbsp;&nbsp;&nbsp;Sampai Tanggal,input,text t,sampai_tgl,w35,,
;4|Jenis Barang,select,text n,nm_jenis,S70,,,RD,inv_jenis-nm_jenis-nm_jenis-
;4|Nama Dokter,input,text n,nm_dokter,w90 upper,,

[lapjualtop]
1|Tanggal Penjualan,,,,,
2|&nbsp;&nbsp;&nbsp;Dari Tanggal,input,text t,dari_tgl,w35,,
3|&nbsp;&nbsp;&nbsp;Sampai Tanggal,input,text t,sampai_tgl,w35,,
4|Jenis Barang,select,text n,nm_jenis,S70,,,RD,inv_jenis-nm_jenis-nm_jenis-

[rptbeli]
1|Tanggal,,,,,,,,,25
2|Nama Barang,,,,,,,,,70
3|Satuan,,,, ,,,,,18
4|Jumlah,,,,,,,,,25
5|Harga Beli,,,, ,,,,,30
6|Total Harga,,,,,,,,,30
7|Keterangan,,,,,,,,,70

[ok]
1|Y,Ya
2|N,Tidak

[service]
1|No. Service,input,text n,no_trans,w50 upper,,10%
2|Tanggal,input,text n,tgl_service,w35,,9%
3|Nama Pelanggan,input,text n,nm_pelanggan,w90 upper ,,18%
4|Alamat Pelanggan,textarea,text,alm_pelanggan,t90,,20%
5|Telepon Pelanggan,input,text,tlp_pelanggan,w50,,
6|Identitas Barang Service,,,,,,,,
7|&nbsp;&nbsp;&bull;&nbsp;Nama Barang,input,text,nm_barang,w70 upper,,15%
8|&nbsp;&nbsp;&bull;&nbsp;Type/Merk,input,text,tp_barang,w70 upper,,
9|&nbsp;&nbsp;&bull;&nbsp;No.Seri,input,text,no_seri_barang,w70 upper,,
10|Kelengkapan Barang,textarea,text,ket_barang,t90,,
11|Kerusakan/Masalah,textarea,text,ket_service,t90,,
12|Garansi,select,text,gr_service,s25,,,RS,ok
13|Lokasi,select,text,id_lokasi,s50,,10%,RD,user_lokasi-ID-lokasi-

[printslip]
1|Tanggal Transaksi,input,text n,tgl_jual,w35,,,
2|Nomor Transaksi,select,text n,nomor_slip,S90,,,
;3|Nomor Faktur,input,text n,no_faktur,w50,,,

[BayarService]
1|No. Service,input,text n,no_trans,w70 upper,,10%
2|Tanggal,input,text n,tanggal,w50,,9%
3|Nama Pelanggan,input,text n,nmt_pelanggan,w100 upper ,,18%
4|Alamat Pelanggan,textarea,text,almt_pelanggan,t100,,20%
;5|Telepon Pelanggan,input,text,tlpt_pelanggan,w50,,

[BayarService2]
1|Identitas Barang Service,,,,,,,,
2|&nbsp;&nbsp;&bull;&nbsp;Nama Barang,input,text,nmt_barang,w100 upper,,15%
3|&nbsp;&nbsp;&bull;&nbsp;Type/Merk,input,text,tpt_barang,w100 upper,,
4|Kerusakan/Masalah,textarea,text,kett_service,t90,,

[servicelist]
1|Nama Barang,input,text n,nm_barang,w100 upper,,25%
2|Satuan,input,text n,nm_satuan,w100 upper,,8%
3|Jumlah,input,text d,jml_transaksi,w100 angka,,12%
4|Harga,input,text d,harga_jual,w100 angka,,10%

[MarginJual]
1|Tanggal,,,,,,8%,,
2|Nama Barang,,,,,,30%,,
3|Satuan,,,,,,5%,,
4|Jumlah,,,,,,8%,,
5|Harga Jual,,,,,,8%,,
6|Harga Beli,,,,,,8%,,
7|Total Jual,,,,,,8%,,
8|Total Beli,,,,,,8%,,
9|Margin,,,,,,8%,,
10|(%),,,,,,5%,,

[FormDO]
1|No. Transaksi,input,text n,notransdo,w70' data-provide='typeahead' data-items='10,,
2|Pelanggan,input,text,nm_pelanggane,w100 upper,,


[ListDO]
1|Nama Barang,,,,,,60%,,
2|Satuan,,,,,,5%,,
3|Jumlah,,,,,,10%,,
4|Lokasi,,,,,,10%,,

[belanja]
1|No. ID,input,text n,no_transaksi,w50 upper,,10%
2|Tanggal,input,text t,tgl_transaksi,w35,,8%

[FormDOMobil]
1|No. Transaksi,input,text n,notransdom,w70' data-provide='typeahead' data-items='10,,
2|No Mobil,select,text,no_mobil,S60 upper,,,RS,NoMobil,
3|Driver,input,text,nm_driver,w70 upper,,,
4|Kirim Ke,input,text,add_kirim,w100,,,

[NoMobil]
1|Hijau,Hijau
2|Putih,Putih
3|Merah,Merah
4|Hitam,Hitam
5|Coklat,Coklat
6|Motor,Motor
7|DiAmbil,DiAmbil

[ListDOMobil]
1|Nama Barang,,,,,,80%,,
2|Satuan,,,,,,5%,,
3|Jumlah,,,,,,10%,,

[rptbeli2]
1|Tanggal,,,,,,10%,,,25
2|Nama Barang,,,,,,30%,,,70
3|Satuan,,,, ,,5%,,,18
4|Jumlah,,,,,,7%,,,25
5|Harga Beli,,,, ,,8%,,,30
6|Total Harga,,,,,,10%,,,30
7|Diambil Tanggal,,,,,,10%,,,30