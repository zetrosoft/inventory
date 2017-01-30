[registrasi]
1|No. ID,input,text n,No_Agt,w35 upper,,
;2|Departement,select,text n,ID_Dept,S70,,10%,RD,mst_departemen-ID-Departemen-,AB
;3|NIP/NIK,input,text n,NIP,w50,,
2|Nama Lengkap,input,text n,Nama,w90 upper ,,
3|Perusahaan,input,text n,Catatan,w90 upper ,,
;5|Jenis Kelamin,select,text n,ID_Kelamin,s50,,5%,RS,Sex
4|Alamat,textarea,text n,Alamat,t90,,
5|Kota,input,text n,Kota,w70,,
6|Propinsi,input,text n,Propinsi,w50,,
7|Telepon,input,text n,Telepon,w50,,
8|Fax,input,text n,Faksimili,w50,,
9|Limit Kredit,input,text n,Status,w35 angka,,
10|Group Pelanggan,select,text n,NIP,S35,,,RS,GrpNasabah,

[biodata]
1|Tanggal Terdaftar,input,text t,TanggalMasuk,w35,,
2|Pelanggan LPG,select,text n,plpg,S25,,,RS,Lpg
3|Pangkalan,select,text n,pangkalan,S70,,,
4|Max. Tabung,input,text n,maxlpg,w35 angka,1,,
5|Peruntukan LPG,textarea,text n,peruntukan,t90,,,
6|Kode Barcode,input,text n,barcode,w70,,,
7|,input,hidden t,idm,w35,,

[Lpg]
1|N,No
2|Y,Yes

[Sex]
1|1,Laki-Laki
2|2,Perempuan

[upload]
1|Nama Lengkap,input,text n,Nama,w90 upper,,
2|NIP,input,text n,NIP,w35 upper,,
3|Photo,input,file n,PhotoLink,w90,,
4|,input,hidden n,no_agt,,,

[kota]
1|Nama Kota,input,text n,Kota,w70 upper,,

[propinsi]
1|Nama Propinsi, input,text n,Propinsi,w70 upper,,

[listanggota]
1|No.ID,input,text n,no_anggota,w35 upper,,8%,
2|Nama Lengkap,input,text n,nm_anggota,w90 upper,,10%,
;3|Perusahaan,select,text n,Catatan,S70,,15%,
3|Alamat,input,text n,alm_anggota,w90,,25%,
4|Telepon,select,text n,telp_anggota,s50,,10%,
5|LPG,select text n,lgp,s25,,5%,RS,Lpg,
6|Limit ,input,text n,status_anggota,w50,,10%,

[detail]
1|Kode,,,,,,10%,
2|Jenis,,,,,,20%,
3|Saldo Awal,,,,,,10%,
4|Debet,,,,,,10%,
5|Kredit,,,,,,10%,
6|Saldo Akhir,,,,,,15%,

[DetailTrans]
1|Tanggal,,,,,,10%,
2|No. Jurnal,,,,,,10%,
3|Keterangan,,,,,,40%,
4|Debet,,,,,,12%,
5|Kredit,,,,,,12%,

[CaraBayar]
1|Tunai,Tunai
2|Transfer,Transfer Bank
3|Potong,Potong Gaji

[simpanan]
1|Bulan,select,text n,ID_Bulan,S50,,
2|Tipe Transaksi,select,text n,ID_Jenis,S35,,,RD,tipe_transaksi-ID-Tipe-
3|Unit,select,text n,ID_Unit,S35,,,RD,unit_jurnal-ID-unit-
4|Jenis Simpanan,select,text n,ID_Simpanan,S50,,,RD,jenis_simpanan-ID-Jenis-where ID_Klasifikasi='3'
5|Cara Bayar,select,text n,cbayar,S35,,,RS,CaraBayar
6|Departemen,select,text n,ID_Dept,S90,,,RD,mst_departemen-ID-Kode+Departemen-order by Kode
;7|Jurnal Otomatis,input,checkbox n,Auto_J,,,

[potonggaji]
1|Kode,,,,,,20%,
2|Nama Anggota,,,,,,50%, 
3|Jumlah,,,,,,20%,

[balance]
1|Bulan,select,text n,ID_Bulan,S50,,
2|Tipe Transaksi,select,text n,ID_Jenis,S35,,,RD,tipe_transaksi-ID-Tipe-
3|Unit,select,text n,ID_Unit,S35,,,RD,unit_jurnal-ID-unit-
4|Perkiraan,select,text n,ID_Perkiraan,S90,,,
5|Jumlah,input,text n,jumlah,w35 angka,,,

[pinjaman]
1|Bulan,select,text n,ID_Bulan,S50,,
2|Tipe Transaksi,select,text n,ID_Jenis,S35,,,RD,tipe_transaksi-ID-Tipe-
3|Unit,select,text n,ID_Unit,S35,,,RD,unit_jurnal-ID-unit-
4|Jenis Pinjaman,select,text n,ID_Simpanan,S50,,,RD,jenis_simpanan-ID-Jenis-where ID_Klasifikasi ='1'
5|Departemen,select,text n,ID_Dept,S90,,,RD,mst_departemen-ID-Kode+Departemen-order by Kode
6|Nama Anggota,input,text n,ID_Agt,w90 cari,,
7|Total Pinjaman,input,text n,pinjaman,w35 angka,,
8|Lama Angsuran,input,text n,lama_cicilan,w15 angka,,
9|Jumlah Cicilan,input, text n,cicilan,w35 angka,,
10|Jumlah Cicilan terakhir ,input, text n,end_cicilan,w35 angka,,
11|Cara Bayar,select,text n,cbayar,S35,,,RS,CaraBayar
12|Mulai bayar bulan,select,text n,mulai_bayar,S50,,
13|Keterangan Pinjamaan,textarea,text n,keterangan,t90,,

[setoranpinjaman]
1|Tanggal,input,text n,Tanggal,S50,,
2|Tipe Transaksi,select,text n,ID_Jenis,S35,,,RD,tipe_transaksi-ID-Tipe-
;3|Unit,select,text n,ID_Unit,S35,,,RD,unit_jurnal-ID-unit-
;4|Jenis Pinjaman,select,text n,ID_Simpanan,S50,,,RD,jenis_simpanan-ID-Jenis-where ID_Klasifikasi ='1'
;5|Departemen,select,text n,ID_Dept,S90,,,RD,mst_departemen-ID-Kode+Departemen-order by Kode
3|Nama Anggota,input,text n,ID_Agt,w90 cari' data-provide='typeahead,,
4|Cara Pembayaran,select,text n,capem,S50,,,RS,cBayar

[cBayar]
1|all,Semua
2|par,Sebagian

[listpinjaman]
7|Total Pinjaman,input,text n,pinjaman,w35 angka,,
8|Angsuran Ke,input,text n,angsuran_ke,w15 angka,,
9|Jumlah Setoran,input, text n,jml_setoran,w35 angka,,
10|Keterangan,textarea,text n,keterangan,t90,,

[listtransaksi]
1|Tanggal,,,,,,10%,
2|Kode,,,,,,8%,
3|Perkiraan,,,,,,25%,
4|Debet,,,,,,12%,
5|Kredit,,,,,,12%,
6|Keterangan,,,,,,20%,

[upd_tagihan]
1|Nama Pelanggan,input,text n,Nama,w70 upper,,
2|No. Faktur,input,text n,Nomor,w50


[TagihanKredit]
1|Nama Pelanggan,,,,,,40%,,,90
2|Total Tagihan,,,,,,12%,,,25
3|Tanggal,,,,,,12%,,,25
;4|Saldo Tagihan,,,,,,15%,,,25
;5|Jatuh Tempo,,,,,,20%,,,18

[TagihanKreditPdf]
1|Nama Pelanggan,,,,,,40%,,,100
2|Tagihan,,,,,,12%,,,30
;3|Pembayaran,,,,,,18%,,,25
;4|Saldo,,,,,,15%,,,25
;5|J.Tempo,,,,,,15%,,,18


[SusunanKredit]
1|b.Nama_Barang, Nama Barang
2|a.Nama,Nama Pelanggan
3|pb.Saldo,Saldo Tagihan
4|sum(p.jml_pinjaman),Total Tagihan
5|p.mulai_bayar, Tanggal Jatuh Tempo

[SusunanPlg]
1|a.Nama,Nama Pelanggan
2|pb.Saldo,Saldo Tagihan
3|sum(p.jml_pinjaman),Total Tagihan
4|p.mulai_bayar, Tanggal Jatuh Tempo

[susunanjual]
1|a.Nama,Nama Pelanggan
2|p.Tanggal,Tanggal Penjualan
3|sum(Jumlah*Harga),Total Penjualan

[susunanbeli]
1|a.Nama,Nama Vendor
2|p.Tanggal,Tanggal Pembelian
3|Harga_Beli,Total Pembelian

[GrpNasabah]
1|Harga_Jual,Umum
2|Harga_Cabang,Toko
3|Harga_Partai,Grosir