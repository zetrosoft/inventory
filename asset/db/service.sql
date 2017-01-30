-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.8 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             7.0.0.4312
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table putridb.inv_pembayaran_service
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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table putridb.inv_pembayaran_service: 8 rows
DELETE FROM `inv_pembayaran_service`;
/*!40000 ALTER TABLE `inv_pembayaran_service` DISABLE KEYS */;
INSERT INTO `inv_pembayaran_service` (`id_service`, `no_trans`, `tgl_ambil`, `ket_service`, `biaya_service`, `nm_barang_ganti`, `qty_barang`, `satuan_barang`, `harga_barang`) VALUES
	(1, '4000000012', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, '4000000012', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(3, '4000000013', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, '4000000014', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(5, '4000000015', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(6, '4000000015', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(7, '4000000015', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(8, '4000000013', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `inv_pembayaran_service` ENABLE KEYS */;


-- Dumping structure for table putridb.inv_penjualan_service
DROP TABLE IF EXISTS `inv_penjualan_service`;
CREATE TABLE IF NOT EXISTS `inv_penjualan_service` (
  `no_trans` varchar(50) NOT NULL,
  `tgl_service` date DEFAULT NULL,
  `nm_pelanggan` varchar(150) DEFAULT NULL,
  `alm_pelanggan` tinytext,
  `nm_barang` varchar(100) DEFAULT NULL,
  `ket_service` text,
  `gr_service` enum('Y','N') DEFAULT 'N',
  `id_lokasi` varchar(50) DEFAULT NULL,
  `stat_service` enum('Y','N','D') DEFAULT 'N',
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`no_trans`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table putridb.inv_penjualan_service: 3 rows
DELETE FROM `inv_penjualan_service`;
/*!40000 ALTER TABLE `inv_penjualan_service` DISABLE KEYS */;
INSERT INTO `inv_penjualan_service` (`no_trans`, `tgl_service`, `nm_pelanggan`, `alm_pelanggan`, `nm_barang`, `ket_service`, `gr_service`, `id_lokasi`, `stat_service`, `created_by`, `created_date`) VALUES
	('4000000013', '2013-01-19', 'ABU', 'Jl.lurah Kawi Jhh', 'PRINTER CANON IP210', 'Warna Pudar\r\nKertas Macet\r\nSering Mati\r\nTidak Terdeteksi', 'N', '1', 'N', 'superuser', '2013-01-20 00:44:13'),
	('4000000014', '2013-01-19', 'ANDI', 'Jl. Kapten Halim 15', 'PRINTER HP 2210', 'Mati', 'N', '1', 'N', 'superuser', '2013-01-20 00:39:15'),
	('4000000015', '2013-01-19', 'BUDI', 'Jl. Veteran 15', 'MONITOR 14\' ACCER', 'Sering Mati Sendiri', 'Y', '1', 'N', 'superuser', '2013-01-20 00:43:21');
/*!40000 ALTER TABLE `inv_penjualan_service` ENABLE KEYS */;


-- Dumping structure for trigger putridb.penjualan_srv_new
DROP TRIGGER IF EXISTS `penjualan_srv_new`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `penjualan_srv_new` AFTER INSERT ON `inv_penjualan_service` FOR EACH ROW BEGIN
	INSERT INTO inv_pembayaran_service (no_trans)
	VALUES(NEW.no_trans);
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
