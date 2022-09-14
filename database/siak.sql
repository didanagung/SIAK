-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2022 at 07:51 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siak`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `no_reff` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_reff` varchar(40) NOT NULL,
  `keterangan` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`no_reff`, `id_user`, `nama_reff`, `keterangan`) VALUES
(110, 1, 'Harta Lancar', '-'),
(111, 1, 'Kas', '-'),
(112, 1, 'Piutang Usaha', '-'),
(113, 1, 'Perlengkapan', '-'),
(114, 1, 'Surat-surat Berharga', '-'),
(115, 1, 'Iklan Dibayar Dimuka', '-'),
(116, 1, 'Sewa Dibayar Dimuka', '-'),
(120, 1, 'Harta Tetap', '-'),
(121, 1, 'Tanah', '-'),
(122, 1, 'Peralatan ', '-'),
(123, 1, 'Akumulasi Penyusutan Peralatan', '-'),
(124, 1, 'Gedung', '-'),
(125, 1, 'Akumulasi Penyusutan Gedung', '-'),
(130, 1, 'Investasi Jangka Panjang', '-'),
(131, 1, 'Investasi Dalam Saham', '-'),
(132, 1, 'Investasi Dalam Obligasi', '-'),
(140, 1, 'Harta Tidak Berwujud', '-'),
(141, 1, 'Goodwil', '-'),
(142, 1, 'Hak Paten', '-'),
(143, 1, 'Hak Cipta', '-'),
(144, 1, 'Hak Merek', '-'),
(200, 1, 'Utang', '-'),
(201, 1, 'Pendapatan diterima dimuka', '.'),
(210, 1, 'Utang Jangka Pendek', '-'),
(211, 1, 'Utang Usaha', '-'),
(212, 1, 'Utang Gaji', '-'),
(213, 1, 'Utang Pajak', '-'),
(214, 1, 'Utang Bunga', '-'),
(215, 1, 'Asuransi Diterima Dimuka', '-'),
(216, 1, 'Sewa Diterima Dimuka', '-'),
(220, 1, 'Utang Jangka Panjang', '-'),
(221, 1, 'Utang Obligasi', '-'),
(222, 1, 'Utang Hipotik', '-'),
(300, 1, 'Modal', '-'),
(311, 1, 'Modal Pemilik', '-'),
(312, 1, 'Prive Pemilik', '-'),
(400, 1, 'Pendapatan', '--'),
(411, 1, 'Pendapatan Jasa', '-'),
(412, 1, 'Pendapatan Lain-lain', '-'),
(500, 1, 'beban-beban', '-'),
(511, 1, 'Beban Gaji', '-'),
(512, 1, 'Beban Air, Listrik, dan Telepon', '-'),
(513, 1, 'Beban Pajak', '-'),
(514, 1, 'Beban Bunga', '-'),
(515, 1, 'Beban Sewa', '.'),
(516, 1, 'Beban Lain-lain', '-'),
(517, 1, 'Beban Penyusutan', '.');

-- --------------------------------------------------------

--
-- Table structure for table `penyesuaian`
--

CREATE TABLE `penyesuaian` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `no_reff` int(11) NOT NULL,
  `tgl_input` datetime NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `jenis_saldo` enum('debit','kredit','','') NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penyesuaian`
--

INSERT INTO `penyesuaian` (`id_transaksi`, `id_user`, `no_reff`, `tgl_input`, `tgl_transaksi`, `jenis_saldo`, `saldo`) VALUES
(41, 1, 112, '2022-09-10 00:03:04', '2022-09-30', 'debit', 2310000),
(42, 1, 411, '2022-09-10 00:03:28', '2022-09-30', 'kredit', 2310000),
(43, 1, 517, '2022-09-10 00:03:55', '2022-09-30', 'debit', 1500000),
(44, 1, 123, '2022-09-10 00:04:14', '2022-09-30', 'kredit', 1500000),
(45, 1, 511, '2022-09-10 00:04:42', '2022-09-30', 'debit', 475000),
(46, 1, 212, '2022-09-10 00:05:03', '2022-09-30', 'kredit', 475000),
(47, 1, 201, '2022-09-10 00:05:21', '2022-09-30', 'debit', 1000000),
(48, 1, 411, '2022-09-10 00:05:39', '2022-09-30', 'kredit', 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `no_reff` int(11) NOT NULL,
  `tgl_input` datetime NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `jenis_saldo` enum('debit','kredit','','') NOT NULL,
  `saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `no_reff`, `tgl_input`, `tgl_transaksi`, `jenis_saldo`, `saldo`) VALUES
(100, 1, 111, '2022-09-09 23:49:10', '2022-09-01', 'debit', 20890000),
(101, 1, 122, '2022-09-09 23:49:43', '2022-09-01', 'debit', 34810000),
(102, 1, 300, '2022-09-09 23:50:18', '2022-09-01', 'kredit', 55700000),
(103, 1, 122, '2022-09-09 23:51:39', '2022-09-04', 'debit', 3050000),
(104, 1, 211, '2022-09-09 23:52:20', '2022-09-04', 'kredit', 3050000),
(105, 1, 111, '2022-09-09 23:52:44', '2022-09-10', 'debit', 20000000),
(106, 1, 411, '2022-09-09 23:53:09', '2022-09-10', 'kredit', 20000000),
(107, 1, 515, '2022-09-09 23:53:49', '2022-09-11', 'debit', 13790000),
(108, 1, 111, '2022-09-09 23:54:08', '2022-09-11', 'kredit', 13790000),
(109, 1, 512, '2022-09-09 23:54:49', '2022-09-14', 'debit', 10050000),
(110, 1, 111, '2022-09-09 23:55:25', '2022-09-14', 'kredit', 10050000),
(111, 1, 111, '2022-09-09 23:55:56', '2022-09-15', 'debit', 4800000),
(112, 1, 201, '2022-09-09 23:56:31', '2022-09-15', 'kredit', 4800000),
(113, 1, 112, '2022-09-09 23:57:21', '2022-09-19', 'debit', 21900000),
(114, 1, 411, '2022-09-09 23:57:50', '2022-09-19', 'kredit', 21900000),
(115, 1, 312, '2022-09-09 23:58:16', '2022-09-22', 'debit', 2500000),
(116, 1, 111, '2022-09-09 23:58:40', '2022-09-22', 'kredit', 2500000),
(117, 1, 111, '2022-09-09 23:59:20', '2022-09-23', 'debit', 29550000),
(118, 1, 411, '2022-09-09 23:59:56', '2022-09-23', 'kredit', 29550000),
(119, 1, 516, '2022-09-10 00:00:24', '2022-09-25', 'debit', 2260000),
(120, 1, 111, '2022-09-10 00:00:46', '2022-09-25', 'kredit', 2260000),
(121, 1, 511, '2022-09-10 00:01:25', '2022-09-27', 'debit', 38210000),
(122, 1, 111, '2022-09-10 00:01:43', '2022-09-27', 'kredit', 38210000);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `role` enum('direktur','bendahara') NOT NULL,
  `jk` enum('laki-laki','perempuan','','') NOT NULL,
  `alamat` varchar(40) NOT NULL,
  `email` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `role`, `jk`, `alamat`, `email`, `username`, `password`, `last_login`) VALUES
(1, 'Ridha', 'direktur', 'perempuan', 'JL.H.B Jassin No.337', 'hidayatchandra08@gmail.com', 'ridha', '69005bb62e9622ee1de61958aacf0f63', '2022-09-10 00:48:45'),
(2, 'Dudi', 'bendahara', 'laki-laki', 'Cikahuripan', 'erostea@gmail.com', 'dudi', '69005bb62e9622ee1de61958aacf0f63', '2022-09-05 22:45:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`no_reff`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `penyesuaian`
--
ALTER TABLE `penyesuaian`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`,`no_reff`),
  ADD KEY `no_reff` (`no_reff`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `no_reff` (`no_reff`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `penyesuaian`
--
ALTER TABLE `penyesuaian`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akun`
--
ALTER TABLE `akun`
  ADD CONSTRAINT `akun_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `penyesuaian`
--
ALTER TABLE `penyesuaian`
  ADD CONSTRAINT `penyesuaian_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `penyesuaian_ibfk_2` FOREIGN KEY (`no_reff`) REFERENCES `akun` (`no_reff`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`no_reff`) REFERENCES `akun` (`no_reff`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
