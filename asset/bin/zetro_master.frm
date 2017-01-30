;Master data build Form
;Inventory build form and list
;Generate auto from zetro_buildform and zetro_buildlist class

[pelanggan]
1|Nama Pelanggan,input,text n,nm_pelanggan,w70 upper,,20%
2|Alamat,input,text n,alm_pelanggan,w90 upper,,25%
3|No. Telp,input,text n,telp_pelanggan,w50,,10%
4|Hutang,input,text d,hutang_pelanggan,w35 angka,0,10%

[produsen]
1|ID Vendor,input,text n,ID,w50 upper,,10%
2|Nama Vendor,input,text n,Pemasok,w70 upper,,15%
3|Alamat,input,text n,Alamat,w70,,25%
4|Kota,input,text n,Kota,w70,,10%
5|Propinsi,input,text n,Propinsi,w50,,10%
;6|Contact Person,input,text n,cp_nama,w90,,12%
;7|No. Telp,input,text n,Telepon,w50,,10%
6|Hutang,input,text d,saldo_piutang,w35 angka,0,10%

[Kas]
1|ID Akun,input,text n,id_kas,w35 upper,,10%
2|Nama Akun,input,text n,nm_kas,w70 upper,,20%
3|Saldo Awal,input,text d,sa_kas,w35 angka,0,10%
;4|Saldo,input,text d,sl_kas,w35 angka,0,10%

[kasharian]
1|ID Trans,input,text n,no_trans,w50,,10%
2|Tanggal,input,text t,tgl_kas,w35,,10%
3|ID Kas,input,text n,id_kas,w35 upper,,10%
4|Nama Kas,input,text n,nm_kas,w70 upper,,20%
5|Saldo Awal,input,text d,sa_kas,w35 angka,0,10%
6|Lokasi,select,text n,id_lok,S35,,10%

[kaskeluar]
1|ID Trans,input,text n,no_transaksi,w50,,10%
2|Tanggal,input,text t,tgl_transaksi,w35,,10%
3|ID Kas,input,text n,akun_transaksi,w50 upper,,10%
4|Uraian,textarea,text n,ket_transaksi,t90,,25%
5|Jumlah ,input,text d,harga_beli,w35 angka,,10%
6|Saldo Kas,input,text d,harga_beli,w35 angka,,10%
7|Lokasi,select,text n,id_lokas,S35,,10%

[filterlapkas]
1|Periode,,,,,,,
2|&nbsp;&nbsp;&nbsp;Dari Tanggal,input,text t,dari_tgl,w35,,
3|&nbsp;&nbsp;&nbsp;Sampai Tanggal,input,text t,sampai_tgl,w35,,
4|Jenis Laporan,select,text n,jenis_lap,S50,,,RS,JenisLaporan

[lapkas]
1|Tanggal,input,text t,tgl_transaksi,w35,,10%,,,22
2|Uraian,,,uraian,,,,,,78
3|Debet,,,kredit,,,,,,30
4|Kredit,,,debit,,,,,,30


[formfaktur]
1|Tanggal Penjualan,,,,,,
2|&nbsp;&nbsp;&nbsp;&bull; Dari Tanggal,input,text t,dari_tgl,w35,,
3|&nbsp;&nbsp;&nbsp;&bull; Sampai Tanggal,input,text t,sampai_tgl,w35,,
4|Nama Pelanggan,input,text n,id_anggota,w90 upper,,
5|No. Transaksi,select,text n,no_transaksi,S70 upper,,10%

[JenisLaporan]
1|Summary,Rangkuman
2|Detail,Detail

[faktur]
1|Nama Barang,,,nm_barang,,,,,,77
2|Qty,,,jml_transaksi,,,,,,25
3|Unit,,,nm_satuan,,,,,,18
4|Harga,,,harga_beli,,,,,,30
5|Jumlah,,,harga_beli,,,,,,30

[neraca]
1|Head,,,Header2,,,30%
2|Jenis,,,Jenis1,,,60%

[shu]
1|Jenis,,,,,,60%
2|Kalkulasi,,,,,,20%

[subneraca]
1|Sub Jenis,,,,,,40%
2|Kalkulasi,,,,,,10%
3|KBR,,,,,,10%
4|USP,,,,,,10%

[detailtransvendor]
1|Tanggal,,,,,,10%,,,12
2|Nomor Faktur,,,,,,12%,,,15
3|Nama Barang,,,,,,40%,,,40
4|Jumlah,,,,,,10%,,,15
5|Satuan,,,,,,10%,,,12
6|Total,,,,,,12%,,,20

[gudang]
1|Nama Gudang,input,text,lokasi,w70 upper,,25%,
2|Alamat Gudang,textarea,text,alamat,T90,,40%,
3|Sebagai Lokasi Server,select,text,status,s25,,,RS,Tanya

[Tanya]
1|Client,Bukan
2|Server,YA

[Sex]
1|1,Laki-Laki
2|2,Perempuan

[Profile]
1|Company Name,input,text n,nm_company,w90 upper,,
2|Address,textarea,text n,ad_company,T90,,
3|City,input,text n,ct_company,w70 upper,,
4|Province,input,text n,pr_company,w50 upper,,
5|PO BOX,input,text n,po_company,w35,,
6|Phone,input,text n,tl_company,w70,,
7|Fax,input,text n,fx_company,w70,,

[Karyawan]
1|ID Karyawan,input,text n,NIP,w35 upper,,5%
2|Nama Karyawan,input,text n,Nama,w90 upper,,15%,
3|Jenis Kelamin,select,text,ID_Kelamin,s70,,5%,RS,Kel
4|No.HP,input,text,NoHP,w50,,10%,,,
5|Lokasi,select,text,Lokasi,S70,,12%,
6|Status,select,text,StatKaryawan,s50,,8%,RS,stKary
7|Tanggal Masuk,input,text,tglMasuk,w50,,10%,,
8|Gaji,input,text n,Gaji,w35 angka,0,,,,
9|Tunjangan,input,text n,Tunjangan,w35 angka,0,,,,
10|Tanggal Keluar,input,text,tglKeluar,w50,,10%,,,

[Kel]
1|L,Laki - Laki
2|P,Perempuan
[Absensi]
1|Nama Karyawan,,,,,,30%,,,12
2|Full,,,,,,10%,,,12
3|1/2 Hari,,,,,,10%,,,12

[lapabsen]
1|Tanggal,,,,,,,30%,,,12
2|Nama Karyawan,,,,,,30%,,,12
3|Status,,,,,,30%,,,12

[lapabsenrekap]

[stKary]
1|Harian,Harian
2|Bulanan,Bulanan

[Kasbon]
1|Tanggal,input,text n,TglKasbon,w35,,10%,,
2|Nama Karyawan,select,text n,Nama,S90,,20%,,
3|Jumlah,input,text,Jumlah,w35 angka,,10%,,
4|Keterangan,textarea,text,ket,T90,,30%,,


