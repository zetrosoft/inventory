<?php
//koneksi ke database access menggunakan ODBC
//odbc_connect("nama data source", "user name", "password");
//odbc_exec(koneksiHandler, query sql);
$koneksi = odbc_connect("mahasiswa", "" , "");
$hasil = odbc_exec($koneksi, "SELECT * FROM mhs");
while ($data = odbc_fetch_array($hasil))
{
  echo $data['nim']." ".$data['namamhs']."<br>";
}
?>