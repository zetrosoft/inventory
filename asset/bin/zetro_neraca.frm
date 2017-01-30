[Neraca Lajur]
1|Kode,,,,,,8%,,,20
2|Perkiraan,,,,,,25%,,,60
3|Saldo Awal,,,,,,10%,,,25
4|Debet,,,,,,10%,,,25
5|Kredit,,,,,,10%,,,25
6|Saldo Akhir,,,,,,20%,,,25

[rekapbeli]
1|Tanggal,,,,,,8%,,,20
2|Nomor,,,,,,12%,,,27
3|Pemasok,,,,,,20%,,,60
;4|Deskripsi,,,,,,25%,,,40
4|Total Harga,,,,,,10%,,,30
5|Keterangan,,,,,,10%,,,35

[detailbeli]
1|Tanggal,,,,,,8%,,,20
2|Nomor,,,,,,12%,,,27
3|Nama Barang,,,,,,20%,,,50
4|Jumlah,,,,,,10%,,,19
5|Satuan,,,,,,10%,,,18
6|Harga,,,,,,10%,,,25
7|Total Harga,,,,,,10%,,,27
;8|Keterangan,,,,,,10%,,,30

[detailbelijual]
1|Tanggal,,,,,,8%,,,25
2|Nomor,,,,,,12%,,,22
3|Nama Barang,,,,,,20%,,,90
4|Jumlah,,,,,,10%,,,25
5|Satuan,,,,,,10%,,,20
6|Harga,,,,,,10%,,,25
7|Total Harga,,,,,,10%,,,25
8|Modal,,,,,,10%,,,25
;8|Keterangan,,,,,,10%,,,30

[belibyvendor]
1|Tanggal,,,,,,8%,,,20
2|Nomor,,,,,,12%,,,20
3|Nama Barang,,,,,,20%,,,50
4|Jumlah,,,,,,10%,,,25
5|Satuan,,,,,,10%,,,18
6|Harga,,,,,,10%,,,30
7|Total Harga,,,,,,10%,,,30
8|Keterangan,,,,,,10%,,,69

[rekapjualtunai]
1|Kode Barang,,,,,,8%,,,30
2|Nama Barang,,,,,,8%,,,60
3|Jumlah,,,,,,8%,,,20
4|Satuan,,,,,,8%,,,18
5|Harga,,,,,,8%,,,22
6|Total Harga,,,,,,8%,,,28

[detailjual]
1|Nama Barang,,,,,,8%,,,60
2|Jumlah,,,,,,8%,,,20
3|Satuan,,,,,,8%,,,18
4|Harga,,,,,,8%,,,25
5|Total Harga,,,,,,8%,,,25
6|Pelanggan,,,,,,8%,,,40
7|Keterangan,,,,,,,8%,,,70

[rekapjualkredit]
1|Nama Anggota,,,,,,8%,,,70
2|Departemen,,,,,,8%,,,60
3|Cicilan,,,,,,8%,,,15
4|Total,,,,,,8%,,,30

[stocklist]
1|Kode Barang,input,text n,Kode,w50,,15%,,,32,
2|Nama Barang,input,text n,Nama_Barang,w90 upper,,25%,,,62,
3|Kategori,input,text n,ID_Kategori,S70,,15%,,,25
4|Jumlah,input,text d,stock,w35 angka,,10%,,,17,
5|Unit,input,text n,Satuan,w35,,10%,,,20
6|Total Harga,input,text d,Harga_Beli,w35 angka,,10%,,,20
;7|Status,input,text n,Status,w35,,,,,,

[stockadjust]
1|Kode Barang,input,text n,Kode,w50,,15%,
2|Nama Barang,input,text n,Nama_Barang,w90 upper,,25%,
3|Kategori,input,text n,ID_Kategori,S70,,15%,
4|Jumlah,input,text d,stock,w35 angka,,10%,
5|Satuan,input,text n,Satuan,w35,,10%,
6|Harga/Sat.,input,text d,Harga_Beli,w35 angka,,10%,
7|Status,input,text n,Status,w35,,10%,

[pemakaian]
1|Tanggal,input,text t,tanggal,w35,,8%,,,20
2|Kode Barang,input,text n,Kode,w50,,10%,,,30,
3|Nama Barang,input,text n,Nama_Barang,w90 upper,,19%,,,60,
4|Satuan,input,text n,Satuan,w35,,8%,,,20
5|Jml,input,text d,Jumlah,w35 angka,,5%,,,20,
6|Harga,input,text d,Harga_Beli,w35 angka,,9%,,,25
7|Total Harga,input,text d,Total,w35 angka,,10%,,,30,
8|Keterangan,textarea,text n,Keterangan,T90,,21%,,,60

[pemakaian]
1|Tanggal,input,text t,tanggal,w35,,8%,,,20
2|Kode Barang,input,text n,Kode,w50,,10%,,,30,
3|Nama Barang,input,text n,Nama_Barang,w90 upper,,19%,,,60,
4|Satuan,input,text n,Satuan,w35,,8%,,,20
5|Jml,input,text d,Jumlah,w35 angka,,5%,,,20,
6|Harga,input,text d,Harga_Beli,w35 angka,,9%,,,25
7|Total Harga,input,text d,Total,w35 angka,,10%,,,30,
8|Keterangan,textarea,text n,Keterangan,T90,,21%,,,60

[rekapsimpanan]
1|Bulan,,,,,,12%,,,70
2|Debet,,,,,,8%,,,30
3|Kredit,,,,,,8%,,,30
4|Saldo,,,,,,8%,,,30

[stocklimit]
1|Kode Barang,,,,,,12%,,,30
2|Nama Barang,,,,,,12%,,,60
3|Min.Stock,,,,,,12%,,,25
4|Stock,,,,,,12%,,,25
5|Satuan,,,,,,12%,,,30
;6|Keterangan,,,,,,12%,,,40

[kasmasuk]
1|Faktur,,,,,,12%,,,25
2|Tanggal,,,,,,12%,,,20
3|Total,,,,,,12%,,,30
4|Penerimaan,,,,,,12%,,,30
5|Keterangan,,,,,,12%,,,75

[labarugi]
1|Tanggal,,,,,,12%,,,30
2|Laba Kotor,,,,,,12%,,,30
3|Operasional,,,,,,12%,,,30
4|PPN 10%,,,,,,12%,,,25
5|Laba Bersih,,,,,,12%,,,30

[mutasi]
1|Tanggal,,,,,,12%,,,25
2|Dari,,,,,,12%,,,35
3|Tujuan,,,,,,12%,,,35
4|Nama Barang,,,,,,12%,,,70
5|Unit,,,,,,12%,,,15
6|Jumlah,,,,,,12%,,,30
7|Keterangan,,,,,,12%,,,60

[laporanLPG]
1|Tanggal,,,,,,10%,,,15,
2|Penerimaan dr Agen,,,,,,12%,,,35
3|

