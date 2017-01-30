-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               10.1.16-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for posdb
DROP DATABASE IF EXISTS `posdb`;
CREATE DATABASE IF NOT EXISTS `posdb` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `posdb`;

-- Dumping structure for table posdb.absensi
DROP TABLE IF EXISTS `absensi`;
CREATE TABLE IF NOT EXISTS `absensi` (
  `id_karyawan` int(10) NOT NULL DEFAULT '0',
  `tgl_absen` date NOT NULL DEFAULT '0000-00-00',
  `id_lokasi` varchar(50) DEFAULT NULL,
  `on_absen` enum('Y','N') DEFAULT 'N',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tgl_absen`,`id_karyawan`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='data absensi karyawan';

-- Data exporting was unselected.
-- Dumping structure for table posdb.bank
DROP TABLE IF EXISTS `bank`;
CREATE TABLE IF NOT EXISTS `bank` (
  `kode` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.barang
DROP TABLE IF EXISTS `barang`;
CREATE TABLE IF NOT EXISTS `barang` (
  `kode` varchar(20) NOT NULL DEFAULT '',
  `nama` varchar(150) NOT NULL DEFAULT '',
  `kategori` varchar(15) DEFAULT NULL,
  `golongan` varchar(50) DEFAULT NULL,
  `satuan` varchar(25) DEFAULT NULL,
  `sn` varchar(100) DEFAULT NULL,
  `nol_price` varchar(5) DEFAULT NULL,
  `toko` decimal(15,2) DEFAULT '0.00',
  `gudang` decimal(15,2) DEFAULT '0.00',
  `hpp` decimal(15,2) DEFAULT '0.00',
  `harga_toko` decimal(15,2) DEFAULT '0.00',
  `harga_partai` decimal(15,2) DEFAULT '0.00',
  `harga_cabang` decimal(15,2) DEFAULT '0.00',
  `diskon` decimal(15,2) DEFAULT '0.00',
  `diskon_beli` decimal(15,2) DEFAULT '0.00',
  `point` decimal(15,2) DEFAULT '0.00',
  `jenis` varchar(25) DEFAULT 'BARANG',
  `mata_uang` varchar(15) DEFAULT 'RUPIAH',
  `hpp_rupiah` decimal(15,2) DEFAULT '0.00',
  `tr_saldo` varchar(5) DEFAULT 'False',
  `kode_barcode` varchar(25) DEFAULT NULL,
  `tampil` varchar(5) DEFAULT 'False',
  `supplier` varchar(25) DEFAULT NULL,
  `stokmin` decimal(10,2) DEFAULT '0.00',
  `stokmax` decimal(10,2) DEFAULT '0.00',
  `garansi` varchar(15) DEFAULT NULL,
  `lokasi` varchar(15) DEFAULT NULL,
  `satuanbeli` varchar(25) DEFAULT NULL,
  `isi` decimal(15,2) DEFAULT '1.00',
  `gambar` varchar(200) DEFAULT NULL,
  `gambar2` varchar(200) DEFAULT NULL,
  `warna` varchar(25) DEFAULT NULL,
  `ukuran` varchar(25) DEFAULT NULL,
  `berat` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.bayar_transaksi
DROP TABLE IF EXISTS `bayar_transaksi`;
CREATE TABLE IF NOT EXISTS `bayar_transaksi` (
  `no_transaksi` varchar(50) NOT NULL DEFAULT '',
  `total_belanja` double DEFAULT '0',
  `ppn` double DEFAULT '0',
  `total_bayar` double DEFAULT '0',
  `dibayar` double DEFAULT '0',
  `kembalian` double DEFAULT '0',
  `created_by` varchar(125) DEFAULT NULL,
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `terbilang` text,
  PRIMARY KEY (`no_transaksi`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='table data pembayaran pejualan';

-- Data exporting was unselected.
-- Dumping structure for table posdb.biaya
DROP TABLE IF EXISTS `biaya`;
CREATE TABLE IF NOT EXISTS `biaya` (
  `kode` varchar(15) NOT NULL DEFAULT '',
  `nama` varchar(25) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.buku_besar
DROP TABLE IF EXISTS `buku_besar`;
CREATE TABLE IF NOT EXISTS `buku_besar` (
  `ID` int(11) DEFAULT NULL,
  `Tanggal` datetime DEFAULT NULL,
  `NoJurnal` varchar(10) DEFAULT NULL,
  `Keterangan` varchar(50) DEFAULT NULL,
  `Debet` double DEFAULT NULL,
  `Kredit` double DEFAULT NULL,
  `Saldo` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.detail_transaksi
DROP TABLE IF EXISTS `detail_transaksi`;
CREATE TABLE IF NOT EXISTS `detail_transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) DEFAULT NULL,
  `tgl_transaksi` date DEFAULT NULL,
  `faktur_transaksi` varchar(100) DEFAULT NULL,
  `jenis_transaksi` varchar(50) DEFAULT NULL,
  `akun_transaksi` varchar(100) DEFAULT NULL,
  `cara_bayar` varchar(50) DEFAULT NULL,
  `nm_produsen` varchar(225) DEFAULT NULL,
  `nm_barang` varchar(225) DEFAULT NULL,
  `nm_satuan` varchar(225) DEFAULT NULL,
  `jml_transaksi` double(10,2) DEFAULT '0.00',
  `expired` date DEFAULT NULL,
  `harga_beli` double DEFAULT '0',
  `ket_transaksi` text,
  `created_by` varchar(50) DEFAULT NULL,
  `doc_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaksi`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='table transaksi keluar masuk obat';

-- Data exporting was unselected.
-- Dumping structure for table posdb.dollar
DROP TABLE IF EXISTS `dollar`;
CREATE TABLE IF NOT EXISTS `dollar` (
  `jumlah` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.golongan_jasa
DROP TABLE IF EXISTS `golongan_jasa`;
CREATE TABLE IF NOT EXISTS `golongan_jasa` (
  `kode` varchar(15) NOT NULL DEFAULT '',
  `nama` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_barang
DROP TABLE IF EXISTS `inv_barang`;
CREATE TABLE IF NOT EXISTS `inv_barang` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Kode` varchar(20) DEFAULT NULL,
  `ID_Kategori` varchar(11) DEFAULT NULL,
  `ID_Jenis` varchar(10) DEFAULT NULL,
  `ID_Pemasok` varchar(30) DEFAULT NULL,
  `ID_Pemasok1` varchar(30) DEFAULT '0',
  `Nama_Barang` varchar(130) NOT NULL DEFAULT '',
  `Harga_Beli` float DEFAULT NULL,
  `Harga_Jual` float DEFAULT NULL,
  `Harga_Partai` float DEFAULT NULL,
  `Harga_Cabang` float DEFAULT NULL,
  `ID_Satuan` int(11) DEFAULT NULL,
  `Status` varchar(11) DEFAULT NULL,
  `minstok` double DEFAULT '1',
  `locked` enum('Y','N') DEFAULT 'N',
  `posting` int(11) DEFAULT '0',
  PRIMARY KEY (`Nama_Barang`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=8300 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_barang_copy
DROP TABLE IF EXISTS `inv_barang_copy`;
CREATE TABLE IF NOT EXISTS `inv_barang_copy` (
  `ID` int(11) NOT NULL,
  `Kode` varchar(15) DEFAULT NULL,
  `ID_Kategori` int(11) DEFAULT NULL,
  `ID_Jenis` tinyint(4) DEFAULT NULL,
  `ID_Pemasok` int(11) DEFAULT NULL,
  `Nama_Barang` varchar(100) NOT NULL DEFAULT '',
  `Harga_Beli` float DEFAULT NULL,
  `Harga_Jual` float DEFAULT NULL,
  `ID_Satuan` int(5) DEFAULT '6',
  `Status` varchar(11) DEFAULT NULL,
  `minstok` double DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.
-- Dumping structure for view posdb.inv_barang_data
DROP VIEW IF EXISTS `inv_barang_data`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `inv_barang_data` (
	`ID` INT(11) NOT NULL,
	`Kode` VARCHAR(20) NULL COLLATE 'latin1_swedish_ci',
	`ID_Kategori` VARCHAR(11) NULL COLLATE 'latin1_swedish_ci',
	`ID_Jenis` VARCHAR(10) NULL COLLATE 'latin1_swedish_ci',
	`ID_Pemasok` VARCHAR(30) NULL COLLATE 'latin1_swedish_ci',
	`Nama_Barang` VARCHAR(130) NOT NULL COLLATE 'latin1_swedish_ci',
	`Harga_Beli` FLOAT NULL,
	`Harga_Jual` FLOAT NULL,
	`Harga_Cabang` FLOAT NULL,
	`Harga_Partai` FLOAT NULL,
	`ID_Satuan` INT(11) NULL,
	`Status` VARCHAR(11) NULL COLLATE 'latin1_swedish_ci',
	`minstok` DOUBLE NULL,
	`Satuan` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`Kategori` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`Jenis` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for table posdb.inv_barang_jenis
DROP TABLE IF EXISTS `inv_barang_jenis`;
CREATE TABLE IF NOT EXISTS `inv_barang_jenis` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `JenisBarang` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`JenisBarang`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=861 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for view posdb.inv_barang_jenis_v
DROP VIEW IF EXISTS `inv_barang_jenis_v`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `inv_barang_jenis_v` (
	`ID_Kategori` VARCHAR(11) NULL COLLATE 'latin1_swedish_ci',
	`Kategori` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`ID` VARCHAR(10) NULL COLLATE 'latin1_swedish_ci',
	`JenisBarang` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for table posdb.inv_barang_kategori
DROP TABLE IF EXISTS `inv_barang_kategori`;
CREATE TABLE IF NOT EXISTS `inv_barang_kategori` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Kategori` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`Kategori`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_barang_satuan
DROP TABLE IF EXISTS `inv_barang_satuan`;
CREATE TABLE IF NOT EXISTS `inv_barang_satuan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Satuan` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`Satuan`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=147 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for view posdb.inv_barang_stock
DROP VIEW IF EXISTS `inv_barang_stock`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `inv_barang_stock` (
	`ID_Kategori` VARCHAR(11) NULL COLLATE 'latin1_swedish_ci',
	`ID_Jenis` VARCHAR(10) NULL COLLATE 'latin1_swedish_ci',
	`ID` INT(11) NOT NULL,
	`ID_Satuan` INT(11) NULL,
	`Nama_Barang` VARCHAR(130) NOT NULL COLLATE 'latin1_swedish_ci',
	`Kode` VARCHAR(20) NULL COLLATE 'latin1_swedish_ci',
	`stock` DECIMAL(32,2) NULL,
	`Status` VARCHAR(11) NULL COLLATE 'latin1_swedish_ci',
	`Satuan` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`Kategori` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`harga_beli` DOUBLE NULL,
	`batch` VARCHAR(125) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view posdb.inv_barang_view
DROP VIEW IF EXISTS `inv_barang_view`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `inv_barang_view` (
	`ID` INT(11) NOT NULL,
	`Kode` VARCHAR(20) NULL COLLATE 'latin1_swedish_ci',
	`ID_Kategori` VARCHAR(11) NULL COLLATE 'latin1_swedish_ci',
	`ID_Jenis` VARCHAR(10) NULL COLLATE 'latin1_swedish_ci',
	`ID_Pemasok` VARCHAR(30) NULL COLLATE 'latin1_swedish_ci',
	`Nama_Barang` VARCHAR(130) NOT NULL COLLATE 'latin1_swedish_ci',
	`Harga_Beli` FLOAT NULL,
	`Harga_Jual` FLOAT NULL,
	`Harga_Cabang` FLOAT NULL,
	`Harga_Partai` FLOAT NULL,
	`ID_Satuan` INT(11) NULL,
	`Status` VARCHAR(11) NULL COLLATE 'latin1_swedish_ci',
	`minstok` DOUBLE NULL,
	`Satuan` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`Kategori` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`Jenis` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`locked` ENUM('Y','N') NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for table posdb.inv_belanja
DROP TABLE IF EXISTS `inv_belanja`;
CREATE TABLE IF NOT EXISTS `inv_belanja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notrans` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `nm_barang` varchar(250) DEFAULT NULL,
  `satuan` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `tglharga` date DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `createdtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='catatan rencana belanja';

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_blacklist
DROP TABLE IF EXISTS `inv_blacklist`;
CREATE TABLE IF NOT EXISTS `inv_blacklist` (
  `ID` int(11) DEFAULT NULL,
  `ID_Agt` int(11) NOT NULL DEFAULT '0',
  `Keterangan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Agt`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_golongan
DROP TABLE IF EXISTS `inv_golongan`;
CREATE TABLE IF NOT EXISTS `inv_golongan` (
  `nm_golongan` varchar(225) NOT NULL DEFAULT '',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nm_golongan`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='tabel golongan obat';

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_konversi
DROP TABLE IF EXISTS `inv_konversi`;
CREATE TABLE IF NOT EXISTS `inv_konversi` (
  `id_barang` varchar(50) NOT NULL DEFAULT '',
  `nm_barang` varchar(225) NOT NULL DEFAULT '',
  `nm_satuan` varchar(50) DEFAULT NULL,
  `sat_beli` varchar(50) NOT NULL,
  `isi_konversi` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`sat_beli`,`id_barang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_material_count
DROP TABLE IF EXISTS `inv_material_count`;
CREATE TABLE IF NOT EXISTS `inv_material_count` (
  `nm_barang` varchar(125) NOT NULL DEFAULT '',
  `nm_satuan` varchar(50) DEFAULT NULL,
  `count1` double DEFAULT '0',
  `count2` double DEFAULT '0',
  `datecount` date DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nm_barang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_material_stok
DROP TABLE IF EXISTS `inv_material_stok`;
CREATE TABLE IF NOT EXISTS `inv_material_stok` (
  `id_lokasi` varchar(50) NOT NULL DEFAULT '',
  `id_barang` int(11) NOT NULL DEFAULT '0',
  `nm_barang` varchar(125) NOT NULL DEFAULT '',
  `batch` varchar(125) NOT NULL DEFAULT '',
  `expired` date DEFAULT NULL,
  `stock` decimal(10,2) DEFAULT '0.00',
  `blokstok` decimal(10,2) DEFAULT '0.00',
  `nm_satuan` varchar(50) DEFAULT NULL,
  `harga_beli` double unsigned DEFAULT '0',
  `created_by` varchar(50) DEFAULT NULL,
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `streset` decimal(10,2) DEFAULT NULL,
  `dtreset` datetime DEFAULT NULL,
  `dariFlow` text,
  PRIMARY KEY (`id_lokasi`,`id_barang`,`nm_barang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='data stock material';

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_material_stok_copy
DROP TABLE IF EXISTS `inv_material_stok_copy`;
CREATE TABLE IF NOT EXISTS `inv_material_stok_copy` (
  `id_lokasi` varchar(50) NOT NULL DEFAULT '',
  `id_barang` int(11) NOT NULL DEFAULT '0',
  `nm_barang` varchar(125) NOT NULL DEFAULT '',
  `batch` varchar(125) NOT NULL DEFAULT '',
  `expired` date DEFAULT NULL,
  `stock` double DEFAULT '0',
  `blokstok` double DEFAULT '0',
  `nm_satuan` varchar(50) DEFAULT NULL,
  `harga_beli` int(10) unsigned DEFAULT '0',
  `created_by` varchar(50) DEFAULT NULL,
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='data stock material';

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_mutasi_stock
DROP TABLE IF EXISTS `inv_mutasi_stock`;
CREATE TABLE IF NOT EXISTS `inv_mutasi_stock` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NoTrans` varchar(50) DEFAULT NULL,
  `Tanggal` date DEFAULT NULL,
  `ID_Barang` varchar(50) DEFAULT NULL,
  `Batch` varchar(50) DEFAULT NULL,
  `ID_Satuan` varchar(50) DEFAULT NULL,
  `Jumlah` double DEFAULT '0',
  `ID_Lokasi_asal` varchar(50) DEFAULT NULL,
  `ID_Lokasi_kirim` varchar(50) DEFAULT NULL,
  `Created_by` varchar(100) DEFAULT NULL,
  `Status` enum('Y','T','N') DEFAULT 'N',
  `Keterangan` tinytext,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_pemasok
DROP TABLE IF EXISTS `inv_pemasok`;
CREATE TABLE IF NOT EXISTS `inv_pemasok` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Pemasok` varchar(30) DEFAULT NULL,
  `Alamat` varchar(50) DEFAULT NULL,
  `Kota` varchar(20) DEFAULT NULL,
  `Propinsi` varchar(20) DEFAULT NULL,
  `Telepon` varchar(30) DEFAULT NULL,
  `Faksimili` varchar(15) DEFAULT NULL,
  `Status` varchar(9) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_pembayaran
DROP TABLE IF EXISTS `inv_pembayaran`;
CREATE TABLE IF NOT EXISTS `inv_pembayaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) NOT NULL DEFAULT '0',
  `total_belanja` double DEFAULT '0',
  `ppn` double DEFAULT '0',
  `nota` double DEFAULT '0',
  `total_bayar` double DEFAULT '0',
  `jml_dibayar` double DEFAULT '0',
  `kembalian` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  `ID_Jenis` varchar(50) DEFAULT NULL,
  `dinota` enum('Y','N') DEFAULT 'N',
  `nitipuang` enum('Y','N') DEFAULT 'N',
  `nitipbarang` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`no_transaksi`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=109425 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_pembayaran_service
DROP TABLE IF EXISTS `inv_pembayaran_service`;
CREATE TABLE IF NOT EXISTS `inv_pembayaran_service` (
  `id_service` int(11) NOT NULL AUTO_INCREMENT,
  `no_trans` varchar(50) DEFAULT NULL,
  `tgl_ambil` date DEFAULT NULL,
  `ket_service` text,
  `biaya_service` double DEFAULT NULL,
  `nm_barang_ganti` varchar(50) DEFAULT NULL,
  `qty_barang` double DEFAULT NULL,
  `satuan_barang` varchar(50) DEFAULT NULL,
  `harga_barang` double DEFAULT NULL,
  PRIMARY KEY (`id_service`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_pembelian
DROP TABLE IF EXISTS `inv_pembelian`;
CREATE TABLE IF NOT EXISTS `inv_pembelian` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Jenis` tinyint(4) DEFAULT NULL,
  `Tanggal` date DEFAULT NULL,
  `Bulan` tinyint(4) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `ID_Pemasok` int(11) DEFAULT NULL,
  `NoUrut` varchar(50) DEFAULT NULL,
  `Nomor` varchar(20) DEFAULT NULL,
  `Deskripsi` varchar(225) DEFAULT NULL,
  `ID_Bayar` int(11) DEFAULT NULL,
  `ID_Post` tinyint(4) DEFAULT NULL,
  `JatuhTempo` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1970 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_pembelian_detail
DROP TABLE IF EXISTS `inv_pembelian_detail`;
CREATE TABLE IF NOT EXISTS `inv_pembelian_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tanggal` datetime DEFAULT NULL,
  `Bulan` tinyint(4) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `ID_Beli` int(11) DEFAULT NULL,
  `ID_Barang` int(11) DEFAULT NULL,
  `Jml_Faktur` smallint(6) DEFAULT NULL,
  `stockAwal` double DEFAULT NULL,
  `Jumlah` double DEFAULT NULL,
  `stockAkhir` double DEFAULT NULL,
  `Harga_Beli` double DEFAULT NULL,
  `Keterangan` varchar(50) DEFAULT NULL,
  `ID_Satuan` int(11) DEFAULT NULL,
  `Batch` varchar(50) DEFAULT NULL,
  `tglUpdate` datetime DEFAULT NULL,
  `Lokasi` int(11) DEFAULT NULL,
  `posting` int(11) DEFAULT '0',
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=12831 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_pembelian_jenis
DROP TABLE IF EXISTS `inv_pembelian_jenis`;
CREATE TABLE IF NOT EXISTS `inv_pembelian_jenis` (
  `ID` int(11) DEFAULT NULL,
  `Jenis_Beli` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for view posdb.inv_pembelian_lpg
DROP VIEW IF EXISTS `inv_pembelian_lpg`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `inv_pembelian_lpg` (
	`ID` INT(11) NOT NULL,
	`Tanggal` DATE NULL,
	`Deskripsi` VARCHAR(250) NULL COLLATE 'latin1_swedish_ci',
	`ID_Pemasok` VARCHAR(30) NULL COLLATE 'latin1_swedish_ci',
	`ID_Barang` INT(11) NULL,
	`Nama_Barang` VARCHAR(130) NULL COLLATE 'latin1_swedish_ci',
	`Jumlah` DOUBLE NULL,
	`Harga_Beli` DOUBLE NULL,
	`Batch` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for view posdb.inv_pembelian_pkln
DROP VIEW IF EXISTS `inv_pembelian_pkln`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `inv_pembelian_pkln` (
	`Tanggal` DATE NULL,
	`Deskripsi` VARCHAR(250) NULL COLLATE 'latin1_swedish_ci',
	`ID_Barang` INT(11) NULL,
	`Nama_Barang` VARCHAR(130) NULL COLLATE 'latin1_swedish_ci',
	`Jumlah` DOUBLE NULL,
	`Harga_Beli` DOUBLE NULL,
	`ID_Pemasok` VARCHAR(30) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for table posdb.inv_pembelian_rekap
DROP TABLE IF EXISTS `inv_pembelian_rekap`;
CREATE TABLE IF NOT EXISTS `inv_pembelian_rekap` (
  `ID` int(11) DEFAULT NULL,
  `Tanggal` datetime DEFAULT NULL,
  `Bulan` tinyint(4) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `ID_Beli` int(11) DEFAULT NULL,
  `ID_Barang` int(11) DEFAULT NULL,
  `Jml_Faktur` smallint(6) DEFAULT NULL,
  `Jumlah` double DEFAULT NULL,
  `Harga_Beli` float DEFAULT NULL,
  `Keterangan` varchar(50) DEFAULT NULL,
  `ID_Satuan` int(11) DEFAULT NULL,
  `batch` varchar(50) DEFAULT NULL,
  `stockAwal` double DEFAULT NULL,
  `stockAkhir` double DEFAULT NULL,
  `tglUpdate` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_pembelian_status
DROP TABLE IF EXISTS `inv_pembelian_status`;
CREATE TABLE IF NOT EXISTS `inv_pembelian_status` (
  `ID` int(11) DEFAULT NULL,
  `Status` varchar(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_penjualan
DROP TABLE IF EXISTS `inv_penjualan`;
CREATE TABLE IF NOT EXISTS `inv_penjualan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Jenis` tinyint(4) DEFAULT NULL,
  `Tanggal` date DEFAULT NULL,
  `Bulan` tinyint(4) DEFAULT NULL,
  `Tahun` int(11) DEFAULT NULL,
  `NoUrut` varchar(50) DEFAULT NULL,
  `Nomor` varchar(11) DEFAULT NULL,
  `ID_Anggota` int(11) DEFAULT '0',
  `Deskripsi` varchar(100) DEFAULT NULL,
  `Bank` varchar(100) DEFAULT NULL,
  `Cicilan` tinyint(4) DEFAULT NULL,
  `Total` double DEFAULT NULL,
  `Tgl_Cicilan` datetime DEFAULT NULL,
  `ID_Post` tinyint(4) DEFAULT NULL,
  `ID_Close` int(11) DEFAULT NULL,
  `ID_Lokasi` varchar(50) DEFAULT NULL,
  `createdtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=140401 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_penjualan_bayar
DROP TABLE IF EXISTS `inv_penjualan_bayar`;
CREATE TABLE IF NOT EXISTS `inv_penjualan_bayar` (
  `ID` int(11) DEFAULT NULL,
  `ID_Jual` int(11) DEFAULT NULL,
  `ID_Master` tinyint(4) DEFAULT NULL,
  `Tanggal` varchar(50) DEFAULT NULL,
  `Bayar` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_penjualan_detail
DROP TABLE IF EXISTS `inv_penjualan_detail`;
CREATE TABLE IF NOT EXISTS `inv_penjualan_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Jenis` tinyint(4) DEFAULT NULL,
  `Tanggal` datetime DEFAULT NULL,
  `Bulan` tinyint(4) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `ID_Jual` int(11) DEFAULT NULL,
  `ID_Barang` int(11) DEFAULT NULL,
  `stockAwal` decimal(10,2) DEFAULT NULL,
  `Jumlah` decimal(18,2) DEFAULT NULL,
  `stockAkhir` decimal(10,2) DEFAULT NULL,
  `Harga` double DEFAULT NULL,
  `ID_Post` decimal(10,2) DEFAULT NULL,
  `dummistk` decimal(10,2) DEFAULT NULL,
  `Keterangan` varchar(50) DEFAULT NULL,
  `ID_Satuan` varchar(50) DEFAULT NULL,
  `Batch` varchar(50) DEFAULT NULL,
  `CreatedTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tglUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=237179 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_penjualan_do
DROP TABLE IF EXISTS `inv_penjualan_do`;
CREATE TABLE IF NOT EXISTS `inv_penjualan_do` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_jual` int(11) NOT NULL,
  `notrans` varchar(50) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `nm_pelanggan` varchar(150) DEFAULT NULL,
  `lokasi` varchar(50) DEFAULT NULL,
  `status_kirim` enum('Y','N','P') DEFAULT 'N',
  `nomobil` varchar(50) DEFAULT NULL,
  `kirim_date` datetime DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(50) DEFAULT NULL,
  `printed` enum('Y','N','I') DEFAULT 'I',
  `Driver` varchar(50) DEFAULT NULL,
  `TujuanKirim` tinytext,
  PRIMARY KEY (`id_jual`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='untuk tampung data do';

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_penjualan_jenis
DROP TABLE IF EXISTS `inv_penjualan_jenis`;
CREATE TABLE IF NOT EXISTS `inv_penjualan_jenis` (
  `ID` int(11) NOT NULL DEFAULT '0',
  `Jenis_Jual` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_penjualan_rekap
DROP TABLE IF EXISTS `inv_penjualan_rekap`;
CREATE TABLE IF NOT EXISTS `inv_penjualan_rekap` (
  `ID` int(11) DEFAULT NULL,
  `ID_Jenis` tinyint(4) DEFAULT NULL,
  `Tanggal` datetime DEFAULT NULL,
  `Bulan` tinyint(4) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `ID_Jual` int(11) DEFAULT NULL,
  `ID_Barang` int(11) DEFAULT NULL,
  `Jumlah` smallint(6) DEFAULT NULL,
  `Harga` double DEFAULT NULL,
  `ID_Post` tinyint(4) DEFAULT NULL,
  `Keterangan` varchar(50) DEFAULT NULL,
  `no_transaksi` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_penjualan_service
DROP TABLE IF EXISTS `inv_penjualan_service`;
CREATE TABLE IF NOT EXISTS `inv_penjualan_service` (
  `no_trans` varchar(50) NOT NULL,
  `tgl_service` date DEFAULT NULL,
  `nm_pelanggan` varchar(150) DEFAULT NULL,
  `alm_pelanggan` tinytext,
  `tlp_pelanggan` varchar(50) DEFAULT NULL,
  `nm_barang` varchar(100) DEFAULT NULL,
  `merk_barang` varchar(100) DEFAULT NULL,
  `noser_barang` varchar(100) DEFAULT NULL,
  `ket_service` tinytext,
  `masalah_service` varchar(200) DEFAULT NULL,
  `gr_service` enum('Y','N') DEFAULT 'N',
  `id_lokasi` varchar(50) DEFAULT NULL,
  `stat_service` enum('Y','N','D') DEFAULT 'N',
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_trans`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_penjualan_status
DROP TABLE IF EXISTS `inv_penjualan_status`;
CREATE TABLE IF NOT EXISTS `inv_penjualan_status` (
  `ID` int(11) DEFAULT NULL,
  `Status_Jual` varchar(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for view posdb.inv_penjualan_view
DROP VIEW IF EXISTS `inv_penjualan_view`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `inv_penjualan_view` (
	`ID` INT(11) NOT NULL,
	`ID_Jenis` TINYINT(4) NULL,
	`Tanggal` DATETIME NULL,
	`Bulan` TINYINT(4) NULL,
	`Tahun` SMALLINT(6) NULL,
	`ID_Jual` INT(11) NULL,
	`ID_Barang` INT(11) NULL,
	`stockAwal` DECIMAL(10,2) NULL,
	`Jumlah` DECIMAL(18,2) NULL,
	`stockAkhir` DECIMAL(10,2) NULL,
	`Harga` DOUBLE NULL,
	`ID_Post` DECIMAL(10,2) NULL,
	`dummistk` DECIMAL(10,2) NULL,
	`Keterangan` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`ID_Satuan` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`Batch` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`CreatedTime` TIMESTAMP NULL,
	`tglUpdate` DATETIME NULL,
	`Bln` TINYINT(4) NULL,
	`Thn` INT(11) NULL,
	`NoUrut` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`Nomor` VARCHAR(11) NULL COLLATE 'latin1_swedish_ci',
	`Deskripsi` VARCHAR(100) NULL COLLATE 'latin1_swedish_ci',
	`ID_Lokasi` INT(11) NULL,
	`IDP` TINYINT(4) NULL,
	`ID_Anggota` INT(11) NULL,
	`Nama_Barang` VARCHAR(130) NULL COLLATE 'latin1_swedish_ci',
	`Harga_Beli` FLOAT NULL,
	`Satuan` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for table posdb.inv_posting_date
DROP TABLE IF EXISTS `inv_posting_date`;
CREATE TABLE IF NOT EXISTS `inv_posting_date` (
  `ID` int(11) DEFAULT NULL,
  `PostingDate` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_posting_status
DROP TABLE IF EXISTS `inv_posting_status`;
CREATE TABLE IF NOT EXISTS `inv_posting_status` (
  `ID` int(11) DEFAULT NULL,
  `PostStatus` varchar(8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.inv_tagihan
DROP TABLE IF EXISTS `inv_tagihan`;
CREATE TABLE IF NOT EXISTS `inv_tagihan` (
  `ID` int(11) DEFAULT NULL,
  `ID_Jual` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for view posdb.inv_transaksi_lpg
DROP VIEW IF EXISTS `inv_transaksi_lpg`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `inv_transaksi_lpg` (
	`Num` BIGINT(20) NOT NULL,
	`ID` INT(11) NOT NULL,
	`ID_Barang` INT(11) NULL,
	`Tanggal` DATETIME NULL,
	`Bulan` TINYINT(4) NULL,
	`Tahun` SMALLINT(6) NULL,
	`Beli` DOUBLE NULL,
	`Jual` DECIMAL(18,2) NULL,
	`Harga_Beli` DOUBLE NULL,
	`Proses` VARCHAR(4) NOT NULL COLLATE 'utf8mb4_general_ci',
	`Konsumen` VARCHAR(250) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;

-- Dumping structure for table posdb.jenis_simpanan
DROP TABLE IF EXISTS `jenis_simpanan`;
CREATE TABLE IF NOT EXISTS `jenis_simpanan` (
  `ID` int(11) DEFAULT NULL,
  `Jenis` varchar(12) DEFAULT NULL,
  `ID_Klasifikasi` int(11) DEFAULT NULL,
  `ID_SubKlas` int(11) DEFAULT NULL,
  `ID_Laporan` int(11) DEFAULT NULL,
  `ID_LapDetail` int(11) DEFAULT NULL,
  `ID_Calc` int(11) DEFAULT NULL,
  `ID_Unit` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.jurnal
DROP TABLE IF EXISTS `jurnal`;
CREATE TABLE IF NOT EXISTS `jurnal` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Unit` int(11) DEFAULT NULL,
  `ID_Tipe` int(11) DEFAULT NULL,
  `ID_Dept` int(11) DEFAULT NULL,
  `Tanggal` date DEFAULT NULL,
  `ID_Bulan` smallint(6) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `NoUrut` int(11) DEFAULT NULL,
  `Nomor` varchar(10) DEFAULT NULL,
  `Keterangan` varchar(60) DEFAULT NULL,
  `ID_Mark` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.karyawan
DROP TABLE IF EXISTS `karyawan`;
CREATE TABLE IF NOT EXISTS `karyawan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NIP` varchar(50) DEFAULT NULL,
  `Nama` varchar(150) NOT NULL,
  `JenisKelamin` enum('L','P') DEFAULT NULL,
  `StatKaryawan` varchar(50) DEFAULT NULL,
  `Jabatan` varchar(50) DEFAULT NULL,
  `Lokasi` varchar(50) DEFAULT NULL,
  `Gaji` double DEFAULT NULL,
  `Tunjangan` double DEFAULT NULL,
  `TglMasuk` date DEFAULT NULL,
  `TglKeluar` date DEFAULT '0000-00-00',
  `NoHP` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`,`Nama`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.karyawan_absensi
DROP TABLE IF EXISTS `karyawan_absensi`;
CREATE TABLE IF NOT EXISTS `karyawan_absensi` (
  `id_karyawan` int(10) NOT NULL DEFAULT '0',
  `tgl_absen` date NOT NULL DEFAULT '0000-00-00',
  `id_lokasi` varchar(50) DEFAULT NULL,
  `on_absen` enum('Y','N','C') DEFAULT 'N',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tgl_absen`,`id_karyawan`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='data absensi karyawan';

-- Data exporting was unselected.
-- Dumping structure for table posdb.karyawan_kasbon
DROP TABLE IF EXISTS `karyawan_kasbon`;
CREATE TABLE IF NOT EXISTS `karyawan_kasbon` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Kar` int(11) DEFAULT NULL,
  `Tanggal` date DEFAULT NULL,
  `Jumlah` double DEFAULT NULL,
  `Keterangan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.karyawan_kasview
DROP TABLE IF EXISTS `karyawan_kasview`;
CREATE TABLE IF NOT EXISTS `karyawan_kasview` (
  `Tahun` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.kas
DROP TABLE IF EXISTS `kas`;
CREATE TABLE IF NOT EXISTS `kas` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `Kode` varchar(50) DEFAULT NULL,
  `Nama_Kas` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.kas_sub
DROP TABLE IF EXISTS `kas_sub`;
CREATE TABLE IF NOT EXISTS `kas_sub` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `ID_KAS` int(10) DEFAULT NULL,
  `Nama_SubKas` varchar(150) DEFAULT NULL,
  `ID_Calc` int(10) DEFAULT '0',
  `ID_CC` int(10) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.keaktifan
DROP TABLE IF EXISTS `keaktifan`;
CREATE TABLE IF NOT EXISTS `keaktifan` (
  `ID` int(11) DEFAULT NULL,
  `Keaktifan` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.klasifikasi
DROP TABLE IF EXISTS `klasifikasi`;
CREATE TABLE IF NOT EXISTS `klasifikasi` (
  `ID` int(11) DEFAULT NULL,
  `Kode` varchar(2) DEFAULT NULL,
  `Klasifikasi` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.laba_rugi
DROP TABLE IF EXISTS `laba_rugi`;
CREATE TABLE IF NOT EXISTS `laba_rugi` (
  `ID` int(11) DEFAULT NULL,
  `Jenis` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.laporan
DROP TABLE IF EXISTS `laporan`;
CREATE TABLE IF NOT EXISTS `laporan` (
  `ID` int(11) DEFAULT NULL,
  `JenisLaporan` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.lap_head
DROP TABLE IF EXISTS `lap_head`;
CREATE TABLE IF NOT EXISTS `lap_head` (
  `ID` int(11) DEFAULT NULL,
  `Header1` varchar(11) DEFAULT NULL,
  `Header2` varchar(11) DEFAULT NULL,
  `Number1` double DEFAULT NULL,
  `Number2` double DEFAULT NULL,
  `Number3` double DEFAULT NULL,
  `Number4` double DEFAULT NULL,
  `Number5` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.lap_jenis
DROP TABLE IF EXISTS `lap_jenis`;
CREATE TABLE IF NOT EXISTS `lap_jenis` (
  `ID` int(11) DEFAULT NULL,
  `ID_Head` int(11) DEFAULT NULL,
  `ID_KBR` smallint(6) DEFAULT NULL,
  `ID_USP` smallint(6) DEFAULT NULL,
  `ID_Calc` smallint(6) DEFAULT NULL,
  `Jenis` varchar(30) DEFAULT NULL,
  `Jenis1` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.lap_neraca_detail
DROP TABLE IF EXISTS `lap_neraca_detail`;
CREATE TABLE IF NOT EXISTS `lap_neraca_detail` (
  `ID` int(11) DEFAULT NULL,
  `ID_Head` int(11) DEFAULT NULL,
  `ID_KBR` tinyint(4) DEFAULT NULL,
  `ID_USP` tinyint(4) DEFAULT NULL,
  `Detail` varchar(50) DEFAULT NULL,
  `Number1` double DEFAULT NULL,
  `Number2` double DEFAULT NULL,
  `Number3` double DEFAULT NULL,
  `Number4` double DEFAULT NULL,
  `Number5` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.lap_neraca_head
DROP TABLE IF EXISTS `lap_neraca_head`;
CREATE TABLE IF NOT EXISTS `lap_neraca_head` (
  `ID` int(11) DEFAULT NULL,
  `ID_Ledger` int(11) DEFAULT NULL,
  `ID_Head` int(11) DEFAULT NULL,
  `Head` varchar(30) DEFAULT NULL,
  `Number1` double DEFAULT NULL,
  `Number2` double DEFAULT NULL,
  `Number3` double DEFAULT NULL,
  `Number4` double DEFAULT NULL,
  `Number5` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.lap_neraca_ledger
DROP TABLE IF EXISTS `lap_neraca_ledger`;
CREATE TABLE IF NOT EXISTS `lap_neraca_ledger` (
  `ID` int(11) DEFAULT NULL,
  `ID_Ledger` int(11) DEFAULT NULL,
  `Ledger1` varchar(20) DEFAULT NULL,
  `Ledger2` varchar(20) DEFAULT NULL,
  `Number1` double DEFAULT NULL,
  `Number2` double DEFAULT NULL,
  `Number3` double DEFAULT NULL,
  `Number4` double DEFAULT NULL,
  `Number5` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.lap_shu_detail
DROP TABLE IF EXISTS `lap_shu_detail`;
CREATE TABLE IF NOT EXISTS `lap_shu_detail` (
  `ID` int(11) DEFAULT NULL,
  `ID_Head` int(11) DEFAULT NULL,
  `ID_KBR` tinyint(4) DEFAULT NULL,
  `ID_USP` tinyint(4) DEFAULT NULL,
  `Detail` varchar(50) DEFAULT NULL,
  `Number1` double DEFAULT NULL,
  `Number2` double DEFAULT NULL,
  `Number3` double DEFAULT NULL,
  `Number4` double DEFAULT NULL,
  `Number5` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.lap_shu_head
DROP TABLE IF EXISTS `lap_shu_head`;
CREATE TABLE IF NOT EXISTS `lap_shu_head` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Head` int(11) DEFAULT NULL,
  `Head` varchar(50) DEFAULT NULL,
  `Number1` double DEFAULT NULL,
  `Number2` double DEFAULT NULL,
  `Number3` double DEFAULT NULL,
  `Number4` double DEFAULT NULL,
  `Number5` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.lap_subjenis
DROP TABLE IF EXISTS `lap_subjenis`;
CREATE TABLE IF NOT EXISTS `lap_subjenis` (
  `ID` int(11) DEFAULT NULL,
  `NoUrut` int(11) DEFAULT NULL,
  `ID_Lap` int(11) DEFAULT NULL,
  `ID_Jenis` int(11) DEFAULT NULL,
  `ID_Calc` int(11) DEFAULT NULL,
  `ID_KBR` smallint(6) DEFAULT NULL,
  `ID_USP` smallint(6) DEFAULT NULL,
  `ID_Post` smallint(6) DEFAULT NULL,
  `SubJenis` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_anggota
DROP TABLE IF EXISTS `mst_anggota`;
CREATE TABLE IF NOT EXISTS `mst_anggota` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Jenis` tinyint(4) NOT NULL,
  `NoUrut` varchar(50) DEFAULT '0',
  `ID_Aktif` int(11) DEFAULT '0',
  `ID_Dept` int(11) DEFAULT '0',
  `No_Perkiraan` varchar(20) DEFAULT NULL,
  `NIP` varchar(50) DEFAULT NULL,
  `No_Agt` varchar(10) NOT NULL,
  `Nama` varchar(250) NOT NULL,
  `ID_Check` varchar(50) DEFAULT '0',
  `ID_Kelamin` int(11) DEFAULT '0',
  `TanggalMasuk` datetime DEFAULT NULL,
  `TanggalKeluar` datetime DEFAULT NULL,
  `NamaPangkalan` varchar(255) DEFAULT NULL,
  `Catatan` mediumtext,
  `Alamat` text,
  `Kota` varchar(150) DEFAULT NULL,
  `Propinsi` varchar(50) DEFAULT NULL,
  `Telepon` varchar(50) DEFAULT NULL,
  `Faksimili` varchar(50) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `Keterangan` text,
  `CreatedTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Nama`,`ID_Jenis`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3664 DEFAULT CHARSET=latin1 COMMENT='table anggota koperasi';

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_anggota_copy
DROP TABLE IF EXISTS `mst_anggota_copy`;
CREATE TABLE IF NOT EXISTS `mst_anggota_copy` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Jenis` tinyint(4) NOT NULL,
  `NoUrut` int(11) DEFAULT '0',
  `ID_Aktif` int(11) DEFAULT '0',
  `ID_Dept` int(11) DEFAULT '0',
  `No_Perkiraan` varchar(20) DEFAULT NULL,
  `NIP` varchar(50) DEFAULT NULL,
  `No_Agt` varchar(10) NOT NULL,
  `Nama` varchar(250) NOT NULL,
  `ID_Check` varchar(50) DEFAULT '0',
  `ID_Kelamin` int(11) DEFAULT '0',
  `TanggalMasuk` datetime DEFAULT NULL,
  `TanggalKeluar` datetime DEFAULT NULL,
  `NamaPangkalan` varchar(255) DEFAULT NULL,
  `Catatan` mediumtext,
  `Alamat` text,
  `Kota` varchar(150) DEFAULT NULL,
  `Propinsi` varchar(50) DEFAULT NULL,
  `Telepon` varchar(50) DEFAULT NULL,
  `Faksimili` varchar(50) DEFAULT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `Keterangan` text,
  `CreatedTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Nama`,`No_Agt`,`ID_Jenis`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4982 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='table anggota koperasi';

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_anggota_view
DROP TABLE IF EXISTS `mst_anggota_view`;
CREATE TABLE IF NOT EXISTS `mst_anggota_view` (
  `ID` tinyint(4) NOT NULL,
  `No_Agt` tinyint(4) NOT NULL,
  `Nama` tinyint(4) NOT NULL,
  `NIP` tinyint(4) NOT NULL,
  `Kota` tinyint(4) NOT NULL,
  `Telepon` tinyint(4) NOT NULL,
  `Faksimili` tinyint(4) NOT NULL,
  `ID_Check` tinyint(4) NOT NULL,
  `ID_Kelamin` tinyint(4) NOT NULL,
  `Alamat` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_bulan
DROP TABLE IF EXISTS `mst_bulan`;
CREATE TABLE IF NOT EXISTS `mst_bulan` (
  `ID` int(11) NOT NULL DEFAULT '0',
  `Bulan` varchar(9) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_departemen
DROP TABLE IF EXISTS `mst_departemen`;
CREATE TABLE IF NOT EXISTS `mst_departemen` (
  `ID` int(11) NOT NULL DEFAULT '0',
  `Kode` varchar(2) DEFAULT NULL,
  `Departemen` varchar(40) DEFAULT NULL,
  `Title` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_kas
DROP TABLE IF EXISTS `mst_kas`;
CREATE TABLE IF NOT EXISTS `mst_kas` (
  `id_kas` varchar(100) NOT NULL DEFAULT '',
  `nm_kas` varchar(225) DEFAULT '',
  `sa_kas` double DEFAULT '0',
  `sl_kas` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_kas`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_kas_harian
DROP TABLE IF EXISTS `mst_kas_harian`;
CREATE TABLE IF NOT EXISTS `mst_kas_harian` (
  `no_trans` varchar(50) NOT NULL,
  `tgl_kas` date NOT NULL DEFAULT '0000-00-00',
  `id_kas` varchar(50) NOT NULL DEFAULT '',
  `nm_kas` varchar(150) DEFAULT NULL,
  `sa_kas` double DEFAULT '0',
  `created_by` varchar(225) DEFAULT NULL,
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_lokasi` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_kas`,`id_lokasi`,`tgl_kas`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_kas_trans
DROP TABLE IF EXISTS `mst_kas_trans`;
CREATE TABLE IF NOT EXISTS `mst_kas_trans` (
  `id_trans` varchar(50) NOT NULL DEFAULT '0',
  `id_kas` varchar(10) DEFAULT NULL,
  `tgl_trans` date DEFAULT NULL,
  `uraian_trans` varchar(250) NOT NULL DEFAULT '',
  `jumlah` double DEFAULT '0',
  `saldo_kas` double DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_lokasi` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_trans`,`uraian_trans`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='transaksi kas harian';

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_kota
DROP TABLE IF EXISTS `mst_kota`;
CREATE TABLE IF NOT EXISTS `mst_kota` (
  `kota_anggota` varchar(150) NOT NULL DEFAULT '',
  `created_by` varchar(150) NOT NULL DEFAULT '',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kota_anggota`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_pelanggan
DROP TABLE IF EXISTS `mst_pelanggan`;
CREATE TABLE IF NOT EXISTS `mst_pelanggan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nm_pelanggan` varchar(125) NOT NULL DEFAULT '',
  `alm_pelanggan` text,
  `telp_pelanggan` varchar(50) DEFAULT NULL,
  `hutang_pelanggan` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nm_pelanggan`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=70461 DEFAULT CHARSET=latin1 COMMENT='hutang pelanggan';

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_pelanggan_copy
DROP TABLE IF EXISTS `mst_pelanggan_copy`;
CREATE TABLE IF NOT EXISTS `mst_pelanggan_copy` (
  `ID` int(11) NOT NULL,
  `nm_pelanggan` varchar(125) NOT NULL DEFAULT '',
  `alm_pelanggan` text,
  `telp_pelanggan` varchar(50) DEFAULT NULL,
  `hutang_pelanggan` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nm_pelanggan`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='hutang pelanggan';

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_pelanggan_copy_copy
DROP TABLE IF EXISTS `mst_pelanggan_copy_copy`;
CREATE TABLE IF NOT EXISTS `mst_pelanggan_copy_copy` (
  `ID` int(11) NOT NULL,
  `nm_pelanggan` varchar(125) NOT NULL DEFAULT '',
  `alm_pelanggan` text,
  `telp_pelanggan` varchar(50) DEFAULT NULL,
  `hutang_pelanggan` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nm_pelanggan`),
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='hutang pelanggan';

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_pelanggan_hutang
DROP TABLE IF EXISTS `mst_pelanggan_hutang`;
CREATE TABLE IF NOT EXISTS `mst_pelanggan_hutang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nm_pelanggan` varchar(250) DEFAULT NULL,
  `nota_baru` double DEFAULT NULL,
  `nota_lama` double DEFAULT NULL,
  `nitipuang` double DEFAULT NULL,
  `nitipbarang` double DEFAULT NULL,
  `notrans` varchar(50) DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lokasi` varchar(50) DEFAULT NULL,
  `userid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1416 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_pelanggan_nitip
DROP TABLE IF EXISTS `mst_pelanggan_nitip`;
CREATE TABLE IF NOT EXISTS `mst_pelanggan_nitip` (
  `ID` tinyint(4) NOT NULL,
  `Tanggal` tinyint(4) NOT NULL,
  `NoUrut` tinyint(4) NOT NULL,
  `Deskripsi` tinyint(4) NOT NULL,
  `Nomor` tinyint(4) NOT NULL,
  `ID_Lokasi` tinyint(4) NOT NULL,
  `total_belanja` tinyint(4) NOT NULL,
  `total_bayar` tinyint(4) NOT NULL,
  `jml_dibayar` tinyint(4) NOT NULL,
  `nitipbarang` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_pelanggan_trans
DROP TABLE IF EXISTS `mst_pelanggan_trans`;
CREATE TABLE IF NOT EXISTS `mst_pelanggan_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nm_plg` varchar(125) DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jumlah` double DEFAULT NULL,
  `notrans` varchar(50) DEFAULT NULL,
  `lokasi` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_pelanggan_view
DROP TABLE IF EXISTS `mst_pelanggan_view`;
CREATE TABLE IF NOT EXISTS `mst_pelanggan_view` (
  `ID` tinyint(4) NOT NULL,
  `nm_pelanggan` tinyint(4) NOT NULL,
  `alm_pelanggan` tinyint(4) NOT NULL,
  `telp_pelanggan` tinyint(4) NOT NULL,
  `hutang_pelanggan` tinyint(4) NOT NULL,
  `doc_date` tinyint(4) NOT NULL,
  `created_by` tinyint(4) NOT NULL,
  `Nama` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_produsen
DROP TABLE IF EXISTS `mst_produsen`;
CREATE TABLE IF NOT EXISTS `mst_produsen` (
  `nm_produsen` varchar(125) NOT NULL DEFAULT '',
  ` alm_produsen` text,
  ` telp_produsen` varchar(50) DEFAULT NULL,
  ` hutang_produsen` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`nm_produsen`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_propinsi
DROP TABLE IF EXISTS `mst_propinsi`;
CREATE TABLE IF NOT EXISTS `mst_propinsi` (
  `prop_anggota` varchar(100) NOT NULL DEFAULT '',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`prop_anggota`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='data propinsi';

-- Data exporting was unselected.
-- Dumping structure for table posdb.mst_status
DROP TABLE IF EXISTS `mst_status`;
CREATE TABLE IF NOT EXISTS `mst_status` (
  `nm_status` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`nm_status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.nomor_transaksi
DROP TABLE IF EXISTS `nomor_transaksi`;
CREATE TABLE IF NOT EXISTS `nomor_transaksi` (
  `nomor` varchar(50) NOT NULL DEFAULT '',
  `jenis_transaksi` varchar(50) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nomor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.pangkalan
DROP TABLE IF EXISTS `pangkalan`;
CREATE TABLE IF NOT EXISTS `pangkalan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(250) NOT NULL,
  `alamat` text,
  `kota` varchar(50) DEFAULT NULL,
  `propinsi` varchar(50) DEFAULT NULL,
  `jatah` decimal(10,2) DEFAULT NULL,
  `stat` enum('Y','N') DEFAULT 'Y',
  `hp` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`nama`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.perkiraan
DROP TABLE IF EXISTS `perkiraan`;
CREATE TABLE IF NOT EXISTS `perkiraan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Klas` int(11) DEFAULT NULL,
  `ID_SubKlas` int(11) DEFAULT NULL,
  `ID_Dept` int(11) DEFAULT NULL,
  `ID_Unit` int(11) DEFAULT NULL,
  `ID_Laporan` int(11) DEFAULT NULL,
  `ID_LapDetail` int(11) DEFAULT NULL,
  `ID_Agt` int(11) DEFAULT NULL,
  `ID_Calc` int(11) DEFAULT NULL,
  `ID_Simpanan` int(11) DEFAULT NULL,
  `NoUrut` int(11) DEFAULT NULL,
  `Kode` varchar(4) DEFAULT NULL,
  `Perkiraan` varchar(50) DEFAULT NULL,
  `SaldoAwal` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=328 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.pinjaman
DROP TABLE IF EXISTS `pinjaman`;
CREATE TABLE IF NOT EXISTS `pinjaman` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `ID_Unit` int(10) NOT NULL DEFAULT '0',
  `ID_Dept` int(10) NOT NULL DEFAULT '0',
  `ID_Agt` int(10) DEFAULT NULL,
  `ID_Bulan` int(10) DEFAULT NULL,
  `Tahun` int(10) DEFAULT NULL,
  `jml_pinjaman` double DEFAULT '0',
  `cicilan` double DEFAULT '0',
  `cicilan_end` double DEFAULT '0',
  `lama_cicilan` double DEFAULT '1',
  `cara_bayar` varchar(50) DEFAULT NULL,
  `keterangan` text,
  `mulai_bayar` varchar(50) DEFAULT NULL,
  `stat_pinjaman` int(11) DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='table daftar pinjaman anggota';

-- Data exporting was unselected.
-- Dumping structure for table posdb.pinjaman_bayar
DROP TABLE IF EXISTS `pinjaman_bayar`;
CREATE TABLE IF NOT EXISTS `pinjaman_bayar` (
  `ID_pinjaman` int(10) NOT NULL DEFAULT '0',
  `ID_Agt` int(10) NOT NULL DEFAULT '0',
  `ID_Bulan` int(10) NOT NULL DEFAULT '0',
  `Tahun` int(10) NOT NULL DEFAULT '0',
  `Debet` double DEFAULT '0',
  `Kredit` double DEFAULT '0',
  `saldo` double DEFAULT '0',
  `keterangan` text,
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Agt`,`ID_Bulan`,`Tahun`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='table transaksi pembayaran pinjaman';

-- Data exporting was unselected.
-- Dumping structure for table posdb.pinjaman_limit
DROP TABLE IF EXISTS `pinjaman_limit`;
CREATE TABLE IF NOT EXISTS `pinjaman_limit` (
  `ID_Agt` int(10) NOT NULL DEFAULT '0',
  `Tahun` int(10) NOT NULL DEFAULT '0',
  `max_limit` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_Agt`,`Tahun`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='table daftar limit maximal anggota dapat meminjam, 0 adalah no limit';

-- Data exporting was unselected.
-- Dumping structure for table posdb.saldoawallpg
DROP TABLE IF EXISTS `saldoawallpg`;
CREATE TABLE IF NOT EXISTS `saldoawallpg` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Barang` int(11) NOT NULL,
  `Jumlah` double DEFAULT NULL,
  PRIMARY KEY (`ID_Barang`),
  KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Saldo Awal LPG base on transaksi mulai 2016';

-- Data exporting was unselected.
-- Dumping structure for table posdb.setup_simpanan
DROP TABLE IF EXISTS `setup_simpanan`;
CREATE TABLE IF NOT EXISTS `setup_simpanan` (
  `id_simpanan` int(10) NOT NULL DEFAULT '0',
  `nm_simpanan` varchar(50) DEFAULT NULL,
  `min_simpanan` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_simpanan`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='table pengaturan simpanan anggota';

-- Data exporting was unselected.
-- Dumping structure for table posdb.set_simpanan_log
DROP TABLE IF EXISTS `set_simpanan_log`;
CREATE TABLE IF NOT EXISTS `set_simpanan_log` (
  `id_simpanan` int(10) NOT NULL DEFAULT '0',
  `nm_simpanan` varchar(50) DEFAULT NULL,
  `min_simpanan` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED COMMENT='table pengaturan simpanan anggota';

-- Data exporting was unselected.
-- Dumping structure for table posdb.status
DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `ID` int(11) DEFAULT NULL,
  `Status1` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.sub_klasifikasi
DROP TABLE IF EXISTS `sub_klasifikasi`;
CREATE TABLE IF NOT EXISTS `sub_klasifikasi` (
  `ID` int(11) DEFAULT NULL,
  `ID_Klasifikasi` int(11) DEFAULT NULL,
  `ID_Neraca` int(11) DEFAULT NULL,
  `Kode` varchar(2) DEFAULT NULL,
  `SubKlasifikasi` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.tipe_jurnal
DROP TABLE IF EXISTS `tipe_jurnal`;
CREATE TABLE IF NOT EXISTS `tipe_jurnal` (
  `ID` int(11) DEFAULT NULL,
  `Jenis` varchar(30) DEFAULT NULL,
  `Kode` varchar(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.tipe_transaksi
DROP TABLE IF EXISTS `tipe_transaksi`;
CREATE TABLE IF NOT EXISTS `tipe_transaksi` (
  `ID` int(11) DEFAULT NULL,
  `Tipe` varchar(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.transaksi
DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE IF NOT EXISTS `transaksi` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Jurnal` int(11) DEFAULT NULL,
  `ID_Perkiraan` int(11) DEFAULT NULL,
  `ID_Dept` int(11) DEFAULT NULL,
  `Debet` double DEFAULT NULL,
  `Kredit` double DEFAULT NULL,
  `Keterangan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.transaksi_cashflow
DROP TABLE IF EXISTS `transaksi_cashflow`;
CREATE TABLE IF NOT EXISTS `transaksi_cashflow` (
  `ID_CC` tinyint(4) NOT NULL,
  `Tanggal` tinyint(4) NOT NULL,
  `Jumlah` tinyint(4) NOT NULL,
  `keterangan` tinyint(4) NOT NULL,
  `ID_Unit` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.transaksi_del
DROP TABLE IF EXISTS `transaksi_del`;
CREATE TABLE IF NOT EXISTS `transaksi_del` (
  `ID` int(11) DEFAULT NULL,
  `ID_Jurnal` int(11) DEFAULT NULL,
  `ID_Perkiraan` int(11) DEFAULT NULL,
  `ID_Dept` int(11) DEFAULT NULL,
  `Debet` double DEFAULT NULL,
  `Kredit` double DEFAULT NULL,
  `Keterangan` varchar(100) DEFAULT NULL,
  `ID_Unit` int(11) DEFAULT NULL,
  `ID_Tipe` int(11) DEFAULT NULL,
  `Tanggal` datetime DEFAULT NULL,
  `ID_Bulan` smallint(6) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `NoUrut` int(11) DEFAULT NULL,
  `Nomor` varchar(10) DEFAULT NULL,
  `Ket` varchar(60) DEFAULT NULL,
  `ID_Mark` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.
-- Dumping structure for table posdb.transaksi_log
DROP TABLE IF EXISTS `transaksi_log`;
CREATE TABLE IF NOT EXISTS `transaksi_log` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `ID_Trans` varchar(50) DEFAULT NULL,
  `Keterangan` text,
  `old_val` varchar(50) DEFAULT NULL,
  `new_val` varchar(50) DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='log transaksi user';

-- Data exporting was unselected.
-- Dumping structure for table posdb.transaksi_new
DROP TABLE IF EXISTS `transaksi_new`;
CREATE TABLE IF NOT EXISTS `transaksi_new` (
  `ID` int(11) DEFAULT NULL,
  `ID_Jurnal` int(11) DEFAULT NULL,
  `ID_Perkiraan` int(11) DEFAULT NULL,
  `ID_Dept` int(11) DEFAULT NULL,
  `Debet` double DEFAULT NULL,
  `Kredit` double DEFAULT NULL,
  `Keterangan` varchar(100) DEFAULT NULL,
  `ID_Unit` int(11) DEFAULT NULL,
  `ID_Tipe` int(11) DEFAULT NULL,
  `Tanggal` datetime DEFAULT NULL,
  `ID_Bulan` smallint(6) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `NoUrut` int(11) DEFAULT NULL,
  `Nomor` varchar(10) DEFAULT NULL,
  `Ket` varchar(60) DEFAULT NULL,
  `ID_Mark` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.transaksi_rekap
DROP TABLE IF EXISTS `transaksi_rekap`;
CREATE TABLE IF NOT EXISTS `transaksi_rekap` (
  `ID` int(11) NOT NULL,
  `ID_Jurnal` int(11) DEFAULT NULL,
  `ID_Perkiraan` int(11) DEFAULT NULL,
  `ID_Dept_T` int(11) DEFAULT NULL,
  `Debet` double DEFAULT NULL,
  `Kredit` double DEFAULT NULL,
  `Keterangan` varchar(100) DEFAULT NULL,
  `ID_P` int(11) DEFAULT NULL,
  `ID_Klas` int(11) DEFAULT NULL,
  `ID_SubKlas` int(11) DEFAULT NULL,
  `ID_Dept` int(11) DEFAULT NULL,
  `ID_Unit` int(11) DEFAULT NULL,
  `ID_Laporan` int(11) DEFAULT NULL,
  `ID_LapDetail` int(11) DEFAULT NULL,
  `ID_Agt` int(11) DEFAULT NULL,
  `ID_Calc` int(11) DEFAULT NULL,
  `ID_Simpanan` int(11) DEFAULT NULL,
  `NoUrut_P` int(11) DEFAULT NULL,
  `Kode` varchar(4) DEFAULT NULL,
  `Perkiraan` varchar(50) DEFAULT NULL,
  `SaldoAwal` double DEFAULT NULL,
  `ID_J` int(11) DEFAULT NULL,
  `ID_Unit_J` int(11) DEFAULT NULL,
  `ID_Tipe` int(11) DEFAULT NULL,
  `ID_Dept_J` int(11) DEFAULT NULL,
  `Tanggal` date DEFAULT NULL,
  `ID_Bulan` smallint(6) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `NoUrut` int(11) DEFAULT NULL,
  `Nomor` varchar(10) DEFAULT NULL,
  `Keterangan_J` varchar(60) DEFAULT NULL,
  `ID_Mark` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.transaksi_temp
DROP TABLE IF EXISTS `transaksi_temp`;
CREATE TABLE IF NOT EXISTS `transaksi_temp` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `ID_Unit` int(10) DEFAULT NULL,
  `ID_Dept` int(10) DEFAULT NULL,
  `ID_Klas` int(10) DEFAULT NULL,
  `ID_SubKlas` int(10) DEFAULT NULL,
  `ID_CC` int(10) DEFAULT NULL,
  `ID_Perkiraan` int(10) NOT NULL DEFAULT '0',
  `Debet` double NOT NULL DEFAULT '0',
  `Kredit` double NOT NULL DEFAULT '0',
  `Keterangan` text,
  `ID_Bulan` varchar(50) NOT NULL DEFAULT '',
  `Tahun` varchar(50) NOT NULL DEFAULT '',
  `Tanggal` date DEFAULT '0000-00-00',
  `created_by` varchar(50) NOT NULL,
  `ID_Stat` int(10) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=9426 DEFAULT CHARSET=latin1 COMMENT='pencatatan transaksi sebelum posting ke jurnal';

-- Data exporting was unselected.
-- Dumping structure for table posdb.transaksi_view
DROP TABLE IF EXISTS `transaksi_view`;
CREATE TABLE IF NOT EXISTS `transaksi_view` (
  `Tanggal` tinyint(4) NOT NULL,
  `kredit` tinyint(4) NOT NULL,
  `ID_CC` tinyint(4) NOT NULL,
  `id_lokasi` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.unit_jurnal
DROP TABLE IF EXISTS `unit_jurnal`;
CREATE TABLE IF NOT EXISTS `unit_jurnal` (
  `ID` int(11) NOT NULL DEFAULT '0',
  `Kode` varchar(10) DEFAULT NULL,
  `Unit` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.useroto
DROP TABLE IF EXISTS `useroto`;
CREATE TABLE IF NOT EXISTS `useroto` (
  `userid` varchar(50) DEFAULT NULL,
  `idmenu` varchar(50) DEFAULT NULL,
  `c` enum('Y','N') DEFAULT 'N',
  `e` enum('Y','N') DEFAULT 'N',
  `v` enum('Y','N') DEFAULT 'N',
  `p` enum('Y','N') DEFAULT 'N',
  `d` enum('Y','N') DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userid` varchar(50) NOT NULL DEFAULT '',
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `idlevel` varchar(50) DEFAULT NULL,
  `active` enum('Y','N') DEFAULT 'Y',
  `createdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lokasi` varchar(50) DEFAULT NULL,
  `LokasiToko` int(11) DEFAULT NULL,
  `server` int(11) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.user_level
DROP TABLE IF EXISTS `user_level`;
CREATE TABLE IF NOT EXISTS `user_level` (
  `idlevel` int(50) NOT NULL AUTO_INCREMENT,
  `nmlevel` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idlevel`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.user_logs
DROP TABLE IF EXISTS `user_logs`;
CREATE TABLE IF NOT EXISTS `user_logs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(50) DEFAULT NULL,
  `lokasi` int(11) DEFAULT NULL,
  `ipaddress` varchar(50) DEFAULT NULL,
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.user_lokasi
DROP TABLE IF EXISTS `user_lokasi`;
CREATE TABLE IF NOT EXISTS `user_lokasi` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `lokasi` varchar(150) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.user_oto_area
DROP TABLE IF EXISTS `user_oto_area`;
CREATE TABLE IF NOT EXISTS `user_oto_area` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `userid` varchar(100) NOT NULL DEFAULT '',
  `lokasi` varchar(100) NOT NULL DEFAULT '',
  `c` enum('N','Y') DEFAULT 'N',
  PRIMARY KEY (`lokasi`,`userid`),
  UNIQUE KEY `id` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.v_dept_trans
DROP TABLE IF EXISTS `v_dept_trans`;
CREATE TABLE IF NOT EXISTS `v_dept_trans` (
  `ID_Dept` int(10) NOT NULL DEFAULT '0',
  `ID_Klas` int(10) DEFAULT NULL,
  `ID_SubKlas` int(10) DEFAULT NULL,
  `ID_Perkiraan` int(10) NOT NULL DEFAULT '0',
  `ID_Bulan` int(10) NOT NULL DEFAULT '0',
  `ID_Tahun` int(10) NOT NULL DEFAULT '0',
  `SaldoAwal` double DEFAULT '0',
  `Kredit` double DEFAULT '0',
  `Debet` double DEFAULT '0',
  PRIMARY KEY (`ID_Perkiraan`,`ID_Dept`,`ID_Bulan`,`ID_Tahun`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='summary transaksi by simpanan by departemen';

-- Data exporting was unselected.
-- Dumping structure for table posdb.v_neraca
DROP TABLE IF EXISTS `v_neraca`;
CREATE TABLE IF NOT EXISTS `v_neraca` (
  `ID_Head` int(10) DEFAULT NULL,
  `ID_Jenis` int(10) DEFAULT NULL,
  `ID_SubJenis` int(10) NOT NULL DEFAULT '0',
  `SubJenis` varchar(200) DEFAULT NULL,
  `ID_Calc` int(10) DEFAULT NULL,
  `ID_KBR` int(10) DEFAULT NULL,
  `ID_USP` int(10) DEFAULT NULL,
  `Debet` double DEFAULT '0',
  `Kredit` double DEFAULT '0',
  `SaldoAwal` double DEFAULT '0',
  `SaldoAkhir` double DEFAULT '0',
  PRIMARY KEY (`ID_SubJenis`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='membentuk laporan neraca';

-- Data exporting was unselected.
-- Dumping structure for table posdb.v_neraca_lajur
DROP TABLE IF EXISTS `v_neraca_lajur`;
CREATE TABLE IF NOT EXISTS `v_neraca_lajur` (
  `ID_Dept` varchar(250) NOT NULL DEFAULT '',
  `SaldoAwal` double DEFAULT '0',
  `Simp_Pokok` double DEFAULT '0',
  `Simp_Wajib` double DEFAULT '0',
  `Simp_Khusus` double DEFAULT '0',
  `Barang` double DEFAULT '0',
  `Pinjaman` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Dept`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.v_superuser_neraca_lajur
DROP TABLE IF EXISTS `v_superuser_neraca_lajur`;
CREATE TABLE IF NOT EXISTS `v_superuser_neraca_lajur` (
  `ID_Dept` varchar(250) NOT NULL DEFAULT '',
  `SaldoAwal` double DEFAULT '0',
  `Simp_Pokok` double DEFAULT '0',
  `Simp_Wajib` double DEFAULT '0',
  `Simp_Khusus` double DEFAULT '0',
  `Barang` double DEFAULT '0',
  `Pinjaman` double DEFAULT '0',
  `doc_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_Dept`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.z_inv_konversi
DROP TABLE IF EXISTS `z_inv_konversi`;
CREATE TABLE IF NOT EXISTS `z_inv_konversi` (
  `ID` int(11) DEFAULT NULL,
  `Tanggal` datetime DEFAULT NULL,
  `ID_Source` int(11) DEFAULT NULL,
  `Jml_Source` smallint(6) DEFAULT NULL,
  `ID_Dest` int(11) DEFAULT NULL,
  `Jml_Dest` smallint(6) DEFAULT NULL,
  `ID_Post` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.z_inv_pemakaian
DROP TABLE IF EXISTS `z_inv_pemakaian`;
CREATE TABLE IF NOT EXISTS `z_inv_pemakaian` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Tanggal` datetime DEFAULT NULL,
  `Bulan` tinyint(4) DEFAULT NULL,
  `Tahun` smallint(6) DEFAULT NULL,
  `ID_Jenis` tinyint(4) DEFAULT NULL,
  `ID_Barang` int(11) DEFAULT NULL,
  `Jumlah` smallint(6) DEFAULT NULL,
  `Harga` float DEFAULT NULL,
  `Keterangan` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.z_inv_pemakaian_jenis
DROP TABLE IF EXISTS `z_inv_pemakaian_jenis`;
CREATE TABLE IF NOT EXISTS `z_inv_pemakaian_jenis` (
  `ID` int(11) DEFAULT NULL,
  `JenisPakai` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table posdb.z_t_inv_konversi
DROP TABLE IF EXISTS `z_t_inv_konversi`;
CREATE TABLE IF NOT EXISTS `z_t_inv_konversi` (
  `ID` int(11) DEFAULT NULL,
  `ID_Source` int(11) DEFAULT NULL,
  `ID_Dest` int(11) DEFAULT NULL,
  `Jml_Dest` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for trigger posdb.inv_barang_after_insert
DROP TRIGGER IF EXISTS `inv_barang_after_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inv_barang_after_insert` AFTER INSERT ON `inv_barang` FOR EACH ROW BEGIN
/* insert data ke table barang*/
	REPLACE INTO barang
	(kode,nama,kategori,golongan,satuan,hpp,harga_toko)
	VALUES
	(NEW.Kode,NEW.Nama_Barang,
	(SELECT Kategori FROM inv_barang_kategori WHERE ID=NEW.ID_Kategori),
	(SELECT JenisBarang FROM inv_barang_jenis WHERE ID=NEW.ID_Jenis),
	(SELECT Satuan FROm inv_barang_satuan WHERE ID=NEW.ID_Satuan),
	NEW.Harga_Beli,
	NEW.Harga_Jual);
/* insert data ke table inv_konversi */
	REPLACE INTO inv_konversi 
	VALUES(NEW.ID,NEW.Nama_Barang,NEW.ID_Satuan,NEW.ID_Satuan,'1','0000-00-00',
	'Superuser');
	
	/*INSERT INTO inv_material_stok (id_lokasi,id_barang,nm_barang,nm_satuan)
	VALUES('1',NEW.ID,new.Nama_Barang,NEW.id_satuan);*/
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.inv_barang_t_del
DROP TRIGGER IF EXISTS `inv_barang_t_del`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inv_barang_t_del` AFTER DELETE ON `inv_barang` FOR EACH ROW BEGIN
	/*delete data di tabel barang dan table inv_konversi*/
	DELETE FROM barang where nama=OLD.Nama_Barang;
	DELETE FROM inv_konversi where id_barang=OLD.ID;
	/*DELETE FROm inv_material_stok where nm_barang=OLD.Nama_Barang;*/
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.inv_barang_t_upd
DROP TRIGGER IF EXISTS `inv_barang_t_upd`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inv_barang_t_upd` AFTER UPDATE ON `inv_barang` FOR EACH ROW BEGIN
UPDATE BARANG set nama=NEW.Nama_Barang,
			kategori=(select Kategori from inv_barang_kategori where ID=new.ID_Kategori),
			golongan=(select JenisBarang from inv_barang_jenis where ID=new.ID_Jenis),
			satuan=(select Satuan from inv_barang_satuan where ID=new.ID_Satuan),
			hpp=new.Harga_Beli,
			harga_toko=new.Harga_Jual
WHERE nama=New.Nama_Barang;
/* update unit konversi 
UPDATE inv_konversi SET nm_satuan=NEW.ID_Satuan,sat_beli=NEW.ID_Satuan
WHERE sat_beli=OLD.ID_Satuan and id_barang=OLD.ID;
/*
INSERT INTO inv_material_stok (id_lokasi,id_barang,nm_barang,nm_satuan)
	VALUES('1',NEW.ID,new.Nama_Barang,NEW.id_satuan);	*/	
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.inv_material_stok_after_update
DROP TRIGGER IF EXISTS `inv_material_stok_after_update`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `inv_material_stok_after_update` AFTER UPDATE ON `inv_material_stok` FOR EACH ROW BEGIN
	/*UPDATE inv_penjualan_detail set ID_Post=NEW.stock,dummistk=NEW.streset where ID_Barang=NEW.ID_Barang and left(Tanggal,10)=LEFT(NEW.doc_date,10);*/
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.inv_pembelian_detail_before_insert
DROP TRIGGER IF EXISTS `inv_pembelian_detail_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `inv_pembelian_detail_before_insert` BEFORE INSERT ON `inv_pembelian_detail` FOR EACH ROW BEGIN
	SET NEW.stockAwal=(Select Stock from inv_material_stok where id_barang=NEW.id_barang and id_lokasi=3);
	SET NEW.tglUpdate=now();
	SET NEW.stockAkhir=((Select Stock from inv_material_stok where id_barang=NEW.id_barang and id_lokasi=3)+NEW.Jumlah);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.inv_penjualan_detail_before_insert
DROP TRIGGER IF EXISTS `inv_penjualan_detail_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `inv_penjualan_detail_before_insert` BEFORE INSERT ON `inv_penjualan_detail` FOR EACH ROW BEGIN
	SET NEW.stockAwal=(SELECT stock FROM inv_material_stok where id_barang=NEW.id_barang and id_lokasi=3 limit 1);
	SET NEW.tglUpdate=NOW();

		SET NEW.stockAkhir=IF(NEW.ID_Jenis=1,(SELECT stock FROM inv_material_stok where id_barang=NEW.id_barang and id_lokasi=3 limit 1)-NEW.jumlah,
		(SELECT stock FROM inv_material_stok where id_barang=NEW.id_barang and id_lokasi=3 limit 1)+NEW.jumlah);

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.mst_kas_t_del
DROP TRIGGER IF EXISTS `mst_kas_t_del`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `mst_kas_t_del` AFTER DELETE ON `mst_kas_harian` FOR EACH ROW BEGIN
	delete from mst_kas_trans where id_trans=old.no_trans;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.mst_kas_t_new
DROP TRIGGER IF EXISTS `mst_kas_t_new`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `mst_kas_t_new` AFTER INSERT ON `mst_kas_harian` FOR EACH ROW BEGIN
	insert into mst_kas_trans (id_trans,id_kas,tgl_trans,uraian_trans,saldo_kas,id_lokasi,created_by)
	values(New.no_trans,new.id_kas,new.tgl_kas,concat('Saldo Awal ',New.tgl_kas),new.sa_kas,new.id_lokasi,'1');
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.mst_pelanggan_after_delete
DROP TRIGGER IF EXISTS `mst_pelanggan_after_delete`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `mst_pelanggan_after_delete` AFTER DELETE ON `mst_pelanggan` FOR EACH ROW BEGIN
INSERT INTO  mst_pelanggan_trans (nm_plg,jumlah,notrans,lokasi)
 VALUES(OLD.nm_pelanggan,OLD.hutang_pelanggan,concat(OLD.alm_pelanggan,'-dibayar oleh ',OLD.created_by),
 (SELECT LokasiToko FROM Users where userid=OLD.created_by));
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.mst_pelanggan_after_update
DROP TRIGGER IF EXISTS `mst_pelanggan_after_update`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `mst_pelanggan_after_update` AFTER UPDATE ON `mst_pelanggan` FOR EACH ROW BEGIN
 /*INSERT INTO  mst_pelanggan_trans (nm_plg,jumlah,notrans,lokasi)
 VALUES(NEW.nm_pelanggan,NEW.hutang_pelanggan,concat(NEW.alm_pelanggan,'- Nota oleh ',NEW.created_by),
 (SELECT LokasiToko FROM Users where userid=NEW.created_by));*/
 INSERT INTO mst_anggota (ID_Jenis,No_Agt,Nama,NIP,Kota,Propinsi,Telepon,Faksimili,ID_Check,ID_Kelamin,Alamat) 
 VALUES (1,concat('PL-',RIGHT(YEAR(NOW()),2),RIGHT(CONCAT('00000',(SELECT ID FROM mst_anggota_view order by id desc limit 1)),5)) , 
 NEW.nm_pelanggan,'Harga_Jual','Purwakarta','Jawa Barat','','' ,'0','0','');
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_alamat_new
DROP TRIGGER IF EXISTS `t_alamat_new`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_alamat_new` AFTER INSERT ON `mst_anggota` FOR EACH ROW BEGIN
	replace into mst_kota(kota_anggota) values(New.Kota);
	replace into mst_propinsi(prop_anggota) values(NEW.Propinsi);
	/*buat akun perkiraan
	 akun Simp.Pokok
	
	insert into perkiraan
		(ID_Klas,ID_SubKlas,ID_Dept,ID_Unit,ID_Laporan,ID_LapDetail,
		 ID_Agt,ID_Calc,ID_Simpanan,NoUrut,SaldoAwal)
		values(
		(select ID_Klasifikasi from jenis_simpanan where ID='1'),
		(select ID_SubKlas from jenis_simpanan where ID='1'),
		NEW.ID_Dept,
		(select ID_Unit from jenis_simpanan where ID='1'),
		(select ID_Laporan from jenis_simpanan where ID='1'),
		(select ID_LapDetail from jenis_simpanan where ID='1'),
		NEW.ID,
		(select ID_Calc from jenis_simpanan where ID='1'),
		'1','0','0') ;
  /*Simp.Wajib  
	insert into perkiraan
		(ID_Klas,ID_SubKlas,ID_Dept,ID_Unit,ID_Laporan,ID_LapDetail,
		 ID_Agt,ID_Calc,ID_Simpanan,NoUrut,SaldoAwal)
		values(
		(select ID_Klasifikasi from jenis_simpanan where ID='2'),
		(select ID_SubKlas from jenis_simpanan where ID='2'),
		NEW.ID_Dept,
		(select ID_Unit from jenis_simpanan where ID='2'),
		(select ID_Laporan from jenis_simpanan where ID='2'),
		(select ID_LapDetail from jenis_simpanan where ID='2'),
		NEW.ID,
		(select ID_Calc from jenis_simpanan where ID='2'),
		'2','0','0') ;
  /*Simp.Khusus  
	insert into perkiraan
		(ID_Klas,ID_SubKlas,ID_Dept,ID_Unit,ID_Laporan,ID_LapDetail,
		 ID_Agt,ID_Calc,ID_Simpanan,NoUrut,SaldoAwal)
		values(
		(select ID_Klasifikasi from jenis_simpanan where ID='3'),
		(select ID_SubKlas from jenis_simpanan where ID='3'),
		NEW.ID_Dept,
		(select ID_Unit from jenis_simpanan where ID='3'),
		(select ID_Laporan from jenis_simpanan where ID='3'),
		(select ID_LapDetail from jenis_simpanan where ID='3'),
		NEW.ID,
		(select ID_Calc from jenis_simpanan where ID='3'),
		'3','0','0') ;
 
  /*Pinjaman,Barang 
	insert into perkiraan
		(ID_Klas,ID_SubKlas,ID_Dept,ID_Unit,ID_Laporan,ID_LapDetail,
		 ID_Agt,ID_Calc,ID_Simpanan,NoUrut,SaldoAwal)
		values(
		(select ID_Klasifikasi from jenis_simpanan where ID='4'),
		(select ID_SubKlas from jenis_simpanan where ID='4'),
		NEW.ID_Dept,
		(select ID_Unit from jenis_simpanan where ID='4'),
		(select ID_Laporan from jenis_simpanan where ID='4'),
		(select ID_LapDetail from jenis_simpanan where ID='4'),
		NEW.ID,
		(select ID_Calc from jenis_simpanan where ID='4'),
		'4','0','0') ;
 
  /*Barang 
	insert into perkiraan
		(ID_Klas,ID_SubKlas,ID_Dept,ID_Unit,ID_Laporan,ID_LapDetail,
		 ID_Agt,ID_Calc,ID_Simpanan,NoUrut,SaldoAwal)
		values(
		(select ID_Klasifikasi from jenis_simpanan where ID='5'),
		(select ID_SubKlas from jenis_simpanan where ID='5'),
		NEW.ID_Dept,
		(select ID_Unit from jenis_simpanan where ID='5'),
		(select ID_Laporan from jenis_simpanan where ID='5'),
		(select ID_LapDetail from jenis_simpanan where ID='5'),
		NEW.ID,
		(select ID_Calc from jenis_simpanan where ID='5'),
		'5','0','0') ;

	/*limit pinjaman
	replace into pinjaman_limit (ID_Agt,Tahun,max_limit) values(NEW.ID,year(NEW.TanggalMasuk),New.Status);*/
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_alamat_upd
DROP TRIGGER IF EXISTS `t_alamat_upd`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_alamat_upd` AFTER UPDATE ON `mst_anggota` FOR EACH ROW BEGIN
	update mst_kota set kota_anggota=New.Kota where kota_anggota=OLD.Kota;
	update mst_propinsi set prop_anggota=NEW.Propinsi where prop_anggota=OLD.Propinsi;
	

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_ID_Unit_del
DROP TRIGGER IF EXISTS `t_ID_Unit_del`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_ID_Unit_del` AFTER DELETE ON `user_lokasi` FOR EACH ROW BEGIN
	delete from unit_jurnal where ID=old.ID;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_ID_Unit_new
DROP TRIGGER IF EXISTS `t_ID_Unit_new`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_ID_Unit_new` AFTER INSERT ON `user_lokasi` FOR EACH ROW BEGIN
insert into unit_jurnal (ID,Kode,Unit) values(new.ID,new.ID,new.lokasi);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_ID_Unit_upd
DROP TRIGGER IF EXISTS `t_ID_Unit_upd`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_ID_Unit_upd` AFTER UPDATE ON `user_lokasi` FOR EACH ROW BEGIN
	update unit_jurnal set Unit=new.lokasi where ID=Old.ID;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_pinjaman_del
DROP TRIGGER IF EXISTS `t_pinjaman_del`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_pinjaman_del` AFTER DELETE ON `pinjaman` FOR EACH ROW BEGIN
	delete from pinjaman_bayar where ID_pinjaman=OLD.ID;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_pinjaman_new
DROP TRIGGER IF EXISTS `t_pinjaman_new`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_pinjaman_new` AFTER INSERT ON `pinjaman` FOR EACH ROW BEGIN
insert into pinjaman_bayar (
  ID_pinjaman,ID_Agt,Tanggal,Tahun,Debet,saldo,keterangan) (
	select ID,ID_Agt,Tanggal,Tahun,jml_pinjaman,jml_pinjaman,keterangan
	from pinjaman where ID=NEW.ID);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_pinjaman_upd
DROP TRIGGER IF EXISTS `t_pinjaman_upd`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_pinjaman_upd` AFTER UPDATE ON `pinjaman` FOR EACH ROW BEGIN
	replace into pinjaman_bayar (
  	ID_pinjaman,ID_Agt,Tanggal,Tahun,Debet,saldo,keterangan) (
	select ID,ID_Agt,Tanggal,Tahun,jml_pinjaman,jml_pinjaman,keterangan
	from pinjaman where ID=OLD.ID);

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_setup_simpanan_log
DROP TRIGGER IF EXISTS `t_setup_simpanan_log`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_setup_simpanan_log` AFTER UPDATE ON `setup_simpanan` FOR EACH ROW BEGIN
insert into set_simpanan_log  select * from setup_simpanan where id_simpanan=OLD.id_simpanan;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_SimpSumDept_new
DROP TRIGGER IF EXISTS `t_SimpSumDept_new`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_SimpSumDept_new` AFTER INSERT ON `transaksi_new` FOR EACH ROW begin
	/*update table v_dept_trans (rekap transaksi per departemen)*/
end//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_transaksi_del
DROP TRIGGER IF EXISTS `t_transaksi_del`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_transaksi_del` AFTER DELETE ON `transaksi` FOR EACH ROW begin
/* 
jika data pada tabel transaksi di hapus otomatis data pada tabel transaksi new 
akan terhapus juga dan data yang di delete akan di simpan secara otomatis ke table transaksi log
dan jika no_perkiraan sesuai dengan di table temp stat akan kembali ke 0*/
delete from transaksi_new where ID=OLD.ID;
insert into transaksi_log (ID_trans,Keterangan,old_val,new_val) values(OLD.ID_Jurnal,concat('Delete ',OLD.Keterangan),OLD.Debet,OLD.Kredit);
update transaksi_temp set ID_stat='0' where ID_Perkiraan=OLD.ID_Perkiraan;
end//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_transaksi_new
DROP TRIGGER IF EXISTS `t_transaksi_new`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_transaksi_new` AFTER INSERT ON `transaksi` FOR EACH ROW begin
/* jika ada data baru di tabel transaksi akan tersimpan juga di table traksaksi new
Tabel transaksi new digunakan untuk mempermudah dan mempersingkat loading data*/

INSERT into transaksi_new 
	(ID,ID_Jurnal,ID_Perkiraan,ID_Dept,Debet,Kredit,Keterangan,
	 ID_Unit,ID_Tipe,Tanggal,ID_Bulan,Tahun,NoUrut,Nomor,Ket,ID_Mark)
	values
	(New.ID,New.ID_Jurnal,New.ID_Perkiraan,New.ID_Dept,
	New.Debet,New.Kredit,New.Keterangan,
	(select ID_Unit from jurnal where ID=new.ID_Jurnal),
	(select ID_Tipe from jurnal where ID=new.ID_Jurnal),
	(select Tanggal from jurnal where ID=new.ID_Jurnal),
	(select ID_Bulan from jurnal where ID=new.ID_Jurnal),
	(select Tahun from jurnal where ID=new.ID_Jurnal),
	(select NoUrut from jurnal where ID=new.ID_Jurnal),
	(select Nomor from jurnal where ID=new.ID_Jurnal),
	(select Keterangan from jurnal where ID=new.ID_Jurnal),
	(select ID_Mark from jurnal where ID=new.ID_Jurnal)
	);
	
end//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_transaksi_new_del
DROP TRIGGER IF EXISTS `t_transaksi_new_del`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_transaksi_new_del` AFTER DELETE ON `transaksi_new` FOR EACH ROW BEGIN
/* data yang di delete akan di simpan secara otomatis ke table transaksi_del
untuk keperluan restore jika diperlukan
*/
insert into transaksi_del select * from transaksi_new where ID=OLD.ID;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_transaksi_upd
DROP TRIGGER IF EXISTS `t_transaksi_upd`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_transaksi_upd` AFTER UPDATE ON `transaksi` FOR EACH ROW begin
/* 
	Jika table transaksi mengalami update akan otomatis data yang sama di table
	transaksi_new juga akan otomatis ter update
*/
replace into transaksi_new 
	(ID,ID_Jurnal,ID_Perkiraan,ID_Dept,Debet,Kredit,Keterangan,
	 ID_Unit,ID_Tipe,Tanggal,ID_Bulan,Tahun,NoUrut,Nomor,Ket,ID_Mark)
	values
	(New.ID,New.ID_Jurnal,New.ID_Perkiraan,New.ID_Dept,
	New.Debet,New.Kredit,New.Keterangan,
	(select ID_Unit from jurnal where ID=new.ID_Jurnal),
	(select ID_Tipe from jurnal where ID=new.ID_Jurnal),
	(select Tanggal from jurnal where ID=new.ID_Jurnal),
	(select ID_Bulan from jurnal where ID=new.ID_Jurnal),
	(select Tahun from jurnal where ID=new.ID_Jurnal),
	(select NoUrut from jurnal where ID=new.ID_Jurnal),
	(select Nomor from jurnal where ID=new.ID_Jurnal),
	(select Keterangan from jurnal where ID=new.ID_Jurnal),
	(select ID_Mark from jurnal where ID=new.ID_Jurnal)
	);
insert into transaksi_log (ID_trans,Keterangan,old_val,new_val) values(OLD.ID_Jurnal,'Hapus',OLD.Debet,OLD.Kredit);	
end//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_update_stat_pinjaman
DROP TRIGGER IF EXISTS `t_update_stat_pinjaman`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_update_stat_pinjaman` AFTER INSERT ON `pinjaman_bayar` FOR EACH ROW BEGIN

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_v_neraca_del
DROP TRIGGER IF EXISTS `t_v_neraca_del`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_v_neraca_del` AFTER DELETE ON `lap_subjenis` FOR EACH ROW BEGIN
	delete from v_neraca where ID=OLD.ID;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_v_neraca_new
DROP TRIGGER IF EXISTS `t_v_neraca_new`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_v_neraca_new` AFTER INSERT ON `lap_subjenis` FOR EACH ROW BEGIN
	replace into v_neraca (ID_Head,ID_Jenis,ID_SubJenis,SubJenis,ID_Calc,ID_KBR,ID_USP) 
	select ID_Lap,ID_Jenis,ID,SubJenis,ID_Calc,ID_KBR,ID_USP
	from lap_subjenis where ID=new.ID;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger posdb.t_v_neraca_upd
DROP TRIGGER IF EXISTS `t_v_neraca_upd`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `t_v_neraca_upd` AFTER UPDATE ON `lap_subjenis` FOR EACH ROW BEGIN
	replace into v_neraca (ID_Head,ID_Jenis,ID_SubJenis,SubJenis,ID_Calc,ID_KBR,ID_USP) 
	select ID_Lap,ID_Jenis,ID,SubJenis,ID_Calc,ID_KBR,ID_USP
	from lap_subjenis where ID=new.ID;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for view posdb.inv_barang_data
DROP VIEW IF EXISTS `inv_barang_data`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `inv_barang_data`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`127.0.0.1` VIEW `inv_barang_data` AS select `b`.`ID` AS `ID`,`b`.`Kode` AS `Kode`,`b`.`ID_Kategori` AS `ID_Kategori`,`b`.`ID_Jenis` AS `ID_Jenis`,`b`.`ID_Pemasok` AS `ID_Pemasok`,`b`.`Nama_Barang` AS `Nama_Barang`,`b`.`Harga_Beli` AS `Harga_Beli`,`b`.`Harga_Jual` AS `Harga_Jual`,`b`.`Harga_Cabang` AS `Harga_Cabang`,`b`.`Harga_Partai` AS `Harga_Partai`,`b`.`ID_Satuan` AS `ID_Satuan`,`b`.`Status` AS `Status`,`b`.`minstok` AS `minstok`,ucase(`bs`.`Satuan`) AS `Satuan`,ucase(`k`.`Kategori`) AS `Kategori`,ucase(`j`.`JenisBarang`) AS `Jenis` from (((`inv_barang` `b` left join `inv_barang_satuan` `bs` on((`bs`.`ID` = `b`.`ID_Satuan`))) left join `inv_barang_kategori` `k` on((`k`.`ID` = `b`.`ID_Kategori`))) left join `inv_barang_jenis` `j` on((`j`.`ID` = `b`.`ID_Jenis`))) order by `b`.`Nama_Barang` ;

-- Dumping structure for view posdb.inv_barang_jenis_v
DROP VIEW IF EXISTS `inv_barang_jenis_v`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `inv_barang_jenis_v`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` VIEW `inv_barang_jenis_v` AS select `b`.`ID_Kategori` AS `ID_Kategori`,`k`.`Kategori` AS `Kategori`,`b`.`ID_Jenis` AS `ID`,`j`.`JenisBarang` AS `JenisBarang` from ((`inv_barang` `b` left join `inv_barang_kategori` `k` on((`k`.`ID` = `b`.`ID_Kategori`))) left join `inv_barang_jenis` `j` on((`j`.`ID` = `b`.`ID_Jenis`))) where (`j`.`JenisBarang` is not null) group by `b`.`ID_Jenis` ;

-- Dumping structure for view posdb.inv_barang_stock
DROP VIEW IF EXISTS `inv_barang_stock`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `inv_barang_stock`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` VIEW `inv_barang_stock` AS select `im`.`ID_Kategori` AS `ID_Kategori`,`im`.`ID_Jenis` AS `ID_Jenis`,`im`.`ID` AS `ID`,`im`.`ID_Satuan` AS `ID_Satuan`,`im`.`Nama_Barang` AS `Nama_Barang`,`im`.`Kode` AS `Kode`,sum(`ms`.`stock`) AS `stock`,`im`.`Status` AS `Status`,`s`.`Satuan` AS `Satuan`,`k`.`Kategori` AS `Kategori`,sum(`ms`.`harga_beli`) AS `harga_beli`,`ms`.`batch` AS `batch` from (((`inv_barang` `im` left join `inv_material_stok` `ms` on((`ms`.`id_barang` = `im`.`ID`))) left join `inv_barang_satuan` `s` on((`s`.`ID` = `im`.`ID_Satuan`))) left join `inv_barang_kategori` `k` on((`k`.`ID` = `im`.`ID_Kategori`))) group by `im`.`ID`,`ms`.`batch` ;

-- Dumping structure for view posdb.inv_barang_view
DROP VIEW IF EXISTS `inv_barang_view`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `inv_barang_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` VIEW `inv_barang_view` AS select `b`.`ID` AS `ID`,`b`.`Kode` AS `Kode`,`b`.`ID_Kategori` AS `ID_Kategori`,`b`.`ID_Jenis` AS `ID_Jenis`,`b`.`ID_Pemasok` AS `ID_Pemasok`,`b`.`Nama_Barang` AS `Nama_Barang`,`b`.`Harga_Beli` AS `Harga_Beli`,`b`.`Harga_Jual` AS `Harga_Jual`,`b`.`Harga_Cabang` AS `Harga_Cabang`,`b`.`Harga_Partai` AS `Harga_Partai`,`b`.`ID_Satuan` AS `ID_Satuan`,`b`.`Status` AS `Status`,`b`.`minstok` AS `minstok`,ucase(`bs`.`Satuan`) AS `Satuan`,ucase(`k`.`Kategori`) AS `Kategori`,ucase(`j`.`JenisBarang`) AS `Jenis`,`b`.`locked` AS `locked` from (((`inv_barang` `b` left join `inv_barang_satuan` `bs` on((`bs`.`ID` = `b`.`ID_Satuan`))) left join `inv_barang_kategori` `k` on((`k`.`ID` = `b`.`ID_Kategori`))) left join `inv_barang_jenis` `j` on((`j`.`ID` = `b`.`ID_Jenis`))) order by `b`.`Nama_Barang` ;

-- Dumping structure for view posdb.inv_pembelian_lpg
DROP VIEW IF EXISTS `inv_pembelian_lpg`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `inv_pembelian_lpg`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` VIEW `inv_pembelian_lpg` AS select `ipd`.`ID` AS `ID`,`ip`.`Tanggal` AS `Tanggal`,`p`.`nama` AS `Deskripsi`,`ib`.`ID_Pemasok` AS `ID_Pemasok`,`ipd`.`ID_Barang` AS `ID_Barang`,`ib`.`Nama_Barang` AS `Nama_Barang`,`ipd`.`Jumlah` AS `Jumlah`,`ipd`.`Harga_Beli` AS `Harga_Beli`,`ipd`.`Batch` AS `Batch` from (((`inv_pembelian_detail` `ipd` left join `inv_pembelian` `ip` on((`ip`.`ID` = `ipd`.`ID_Beli`))) left join `inv_barang` `ib` on(((`ib`.`ID` = `ipd`.`ID_Barang`) and (`ib`.`Status` = 'LPG')))) left join `pangkalan` `p` on((`p`.`id` = `ib`.`ID_Pemasok`))) where (`ip`.`ID_Pemasok` in (select `mst_anggota`.`ID` from `mst_anggota` where (`mst_anggota`.`NIP` = 'LPG')) and (`ib`.`Status` = 'LPG')) ;

-- Dumping structure for view posdb.inv_pembelian_pkln
DROP VIEW IF EXISTS `inv_pembelian_pkln`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `inv_pembelian_pkln`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` VIEW `inv_pembelian_pkln` AS select distinct `ip`.`Tanggal` AS `Tanggal`,`p`.`nama` AS `Deskripsi`,`ipd`.`ID_Barang` AS `ID_Barang`,`ib`.`Nama_Barang` AS `Nama_Barang`,sum(`ipd`.`Jumlah`) AS `Jumlah`,`ipd`.`Harga_Beli` AS `Harga_Beli`,`ib`.`ID_Pemasok` AS `ID_Pemasok` from (((`inv_pembelian_detail` `ipd` left join `inv_pembelian` `ip` on((`ip`.`ID` = `ipd`.`ID_Beli`))) left join `inv_barang` `ib` on((`ib`.`ID` = `ipd`.`ID_Barang`))) left join `pangkalan` `p` on((`p`.`id` = `ib`.`ID_Pemasok`))) where `ip`.`ID_Pemasok` in (select `mst_anggota`.`ID` from `mst_anggota` where (`mst_anggota`.`NIP` = 'LPG')) group by `ip`.`Tanggal`,`ipd`.`ID_Barang` order by `ipd`.`ID_Barang`,`ip`.`Tanggal` ;

-- Dumping structure for view posdb.inv_penjualan_view
DROP VIEW IF EXISTS `inv_penjualan_view`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `inv_penjualan_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` VIEW `inv_penjualan_view` AS select `ipd`.`ID` AS `ID`,`ipd`.`ID_Jenis` AS `ID_Jenis`,`ipd`.`Tanggal` AS `Tanggal`,`ipd`.`Bulan` AS `Bulan`,`ipd`.`Tahun` AS `Tahun`,`ipd`.`ID_Jual` AS `ID_Jual`,`ipd`.`ID_Barang` AS `ID_Barang`,`ipd`.`stockAwal` AS `stockAwal`,`ipd`.`Jumlah` AS `Jumlah`,`ipd`.`stockAkhir` AS `stockAkhir`,`ipd`.`Harga` AS `Harga`,`ipd`.`ID_Post` AS `ID_Post`,`ipd`.`dummistk` AS `dummistk`,`ipd`.`Keterangan` AS `Keterangan`,`ipd`.`ID_Satuan` AS `ID_Satuan`,`ipd`.`Batch` AS `Batch`,`ipd`.`CreatedTime` AS `CreatedTime`,`ipd`.`tglUpdate` AS `tglUpdate`,`ip`.`Bulan` AS `Bln`,`ip`.`Tahun` AS `Thn`,`ip`.`NoUrut` AS `NoUrut`,`ip`.`Nomor` AS `Nomor`,`ip`.`Deskripsi` AS `Deskripsi`,`ip`.`ID_Close` AS `ID_Lokasi`,`ip`.`ID_Post` AS `IDP`,`ip`.`ID_Anggota` AS `ID_Anggota`,`ib`.`Nama_Barang` AS `Nama_Barang`,`ib`.`Harga_Beli` AS `Harga_Beli`,`ibs`.`Satuan` AS `Satuan` from (((`inv_penjualan_detail` `ipd` left join `inv_penjualan` `ip` on((`ip`.`ID` = `ipd`.`ID_Jual`))) left join `inv_barang` `ib` on((`ib`.`ID` = `ipd`.`ID_Barang`))) left join `inv_barang_satuan` `ibs` on((`ibs`.`ID` = `ib`.`ID_Satuan`))) ;

-- Dumping structure for view posdb.inv_transaksi_lpg
DROP VIEW IF EXISTS `inv_transaksi_lpg`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `inv_transaksi_lpg`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` VIEW `inv_transaksi_lpg` AS select 1 AS `Num`,`inv_pembelian_detail`.`ID` AS `ID`,`inv_pembelian_detail`.`ID_Barang` AS `ID_Barang`,`inv_pembelian_detail`.`Tanggal` AS `Tanggal`,`inv_pembelian_detail`.`Bulan` AS `Bulan`,`inv_pembelian_detail`.`Tahun` AS `Tahun`,`inv_pembelian_detail`.`Jumlah` AS `Beli`,0 AS `Jual`,`inv_pembelian_detail`.`Harga_Beli` AS `Harga_Beli`,'Beli' AS `Proses`,(select `pangkalan`.`nama` from `pangkalan` where (`pangkalan`.`id` = (select `inv_barang`.`ID_Pemasok` from `inv_barang` where (`inv_barang`.`ID` = `inv_pembelian_detail`.`ID_Barang`)))) AS `Konsumen` from `inv_pembelian_detail` where `inv_pembelian_detail`.`ID_Barang` in (select `inv_barang`.`ID` from `inv_barang` where (`inv_barang`.`Status` = 'LPG')) union all select 2 AS `Num`,`pjd`.`ID` AS `ID`,`pjd`.`ID_Barang` AS `ID_Barang`,`pjd`.`Tanggal` AS `Tanggal`,`pjd`.`Bulan` AS `Bulan`,`pjd`.`Tahun` AS `Tahun`,0 AS `Beli`,`pjd`.`Jumlah` AS `Jumlah`,`pjd`.`Harga` AS `Harga`,'Jual' AS `Proses`,`pj`.`Deskripsi` AS `Deskripsi` from (`inv_penjualan_detail` `pjd` left join `inv_penjualan` `pj` on((`pj`.`ID` = `pjd`.`ID_Jual`))) where `pjd`.`ID_Barang` in (select `inv_barang`.`ID` from `inv_barang` where (`inv_barang`.`Status` = 'LPG')) ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
