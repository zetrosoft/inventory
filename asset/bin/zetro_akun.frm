;;generate form and list data

[klasifikasi]
1|Kode,input,text n,ID,w10,,5%,
2|Klasifikasi,input,text n,Klasifikasi,w90,,80%,


[subklasifikasi]
1|Klasifikasi,select,text n,ID_Klasifikasi,S70,,20%,RD,klasifikasi-ID-Kode+Klasifikasi-order by ID
2|Kode,input,text n,Kode,w90,,5%
3|Sub Klasifikasi,input,text n,SubKlasifikasi,w90,,65%

[perkiraan]
1|Klasifikasi,select,text n,ID_Klas,S70,,18%,RD,klasifikasi-ID-Kode+Klasifikasi-order by ID
2|Sub Klasifikasi,select,text n,ID_SubKlas,S70,,22%,
3|Unit,select,text n,ID_Unit,S35,,5%,RD,unit_jurnal-ID-Kode+Unit-
4|Laporan,select,text n,ID_Laporan,S50,,,RD,laporan-ID-JenisLaporan-
;5|Lap. Detail,select,text n,ID_LapDetail,S70,,,
5|Sistem Kalkulasi,select,text n,ID_Calc,S50,,,RD,lap_head-ID-header2-
6|Kode,input,text n,Kode,w50,,10%,
7|Nama Perkiraan,input,text n,Perkiraan,w90,,35%,
8|Saldo Awal,input,text d,SaldoAwal,w35 angka,,

;Tanggal,,,,,,,,,22
[rekapsimpanan]
1|Departemen,,,,,,,,,60
2|Simp.Pokok,,,,,,,,,30
3|Simp.Wajib,,,,,,,,,30
4|Simp.Khusus,,,,,,,,,30
5|Total,,,,,,,,,30

[jurnal]
1|Tanggal,,,,,,8%,,,25
2|Unit,,,,,,5%,,,15
3|No. Jurnal,,,,,,8%,,,25
4|Keterangan,,,,,,30%,,,112
5|Debet,,,,,,15%,,,32
6|Kredit,,,,,,15%,,,32
7|Balance,,,,,,15%,,,25

[newjurnal]
1|Tanggal,input,text t,Tanggal,w35,,
2|Unit,select,text n,ID_Unit,S50,,,RD,unit_jurnal-ID-Kode+Unit-
3|No.Urut,input,text d,noUrut,w35,,
4|Keterangan,textarea,text n,Keterangan,T90,,

;array(10,25,90,31,31,90)
[addcontent]
1|Kode,,,,,,8%,,,25
2|Perkiraan,,,,,,25%,,,90
3|Debet,,,,,,10%,,,31
4|Kredit,,,,,,10%,,,31
5|Keterangan,,,,,,20%,,,90

[balance]
1|No. Jurnal,input,text n,ID_Jurnale,w35,,,
2|Tipe Transaksi,select,text n,ID_Jenis,S35,,,RD,tipe_transaksi-ID-Tipe-
3|Perkiraan,select,text n,ID_Perkiraan,S90,,,RD,lap_subjenis-ID-SubJenis-where ID_KBR='1'
4|Jumlah,input,text n,jml_bayar,w35 angka,,,
5|Keterangan,textarea,text n,Kete,T90,,

[bukubesar]
1|Tanggal,,,,,,10%,,,25
2|No.Jurnal,,,,,,10%,,,25
3|Keterangan,,,,,,30%,,,25
4|Debet,,,,,,12%,,,25
5|Kredit,,,,,,12%,,,25
6|Saldo,,,,,,12%,,,25

[bukubesartahunan]
1|Bulan,,,,,,10%,,,25
2|Debet,,,,,,15%,,,25
3|Kredit,,,,,,15%,,,25
4|Saldo,,,,,,15%,,,25


