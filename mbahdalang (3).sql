-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2025 at 04:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mbahdalang`
--

-- --------------------------------------------------------

--
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int(11) NOT NULL,
  `nomer_kamar` varchar(50) NOT NULL,
  `status` enum('Available','Not Available') DEFAULT 'Available',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  `tenant_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kamar`
--

INSERT INTO `kamar` (`id_kamar`, `nomer_kamar`, `status`, `start_date`, `end_date`, `price`, `tenant_name`) VALUES
(1, 'A1', 'Not Available', '0000-00-00', '0000-00-00', '', ''),
(2, 'A2', 'Available', '0000-00-00', '0000-00-00', '', ''),
(3, 'A3', 'Available', '0000-00-00', '0000-00-00', '', ''),
(4, 'A4', 'Available', NULL, NULL, NULL, NULL),
(5, 'A5', 'Available', NULL, NULL, NULL, NULL),
(6, 'B1', 'Available', '0000-00-00', '0000-00-00', '', ''),
(7, 'B2', 'Available', NULL, NULL, NULL, NULL),
(8, 'B3', 'Available', NULL, NULL, NULL, NULL),
(9, 'B4', 'Available', NULL, NULL, NULL, NULL),
(10, 'B5', 'Available', '0000-00-00', '0000-00-00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_penyewa` int(11) DEFAULT NULL,
  `id_kamar` int(11) DEFAULT NULL,
  `id_reservasi` int(11) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('Berhasil','Gagal','Menunggu') DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id_reservasi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kamar` int(11) NOT NULL,
  `tanggal_checkin` date NOT NULL,
  `tanggal_checkout` date NOT NULL,
  `status_konfirmasi` enum('Confirmed','Not Confirmed') DEFAULT 'Not Confirmed',
  `harga` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id_reservasi`, `id_user`, `id_kamar`, `tanggal_checkin`, `tanggal_checkout`, `status_konfirmasi`, `harga`) VALUES
(11, 5, 1, '2025-06-21', '2025-07-21', 'Not Confirmed', ''),
(13, 5, 6, '2025-06-30', '2025-09-30', 'Not Confirmed', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email_user` varchar(100) NOT NULL,
  `username_user` varchar(100) NOT NULL,
  `password_user` varchar(100) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `tanggallahir_user` varchar(100) NOT NULL,
  `nomer_hp` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `email_user`, `username_user`, `password_user`, `nama_user`, `tanggallahir_user`, `nomer_hp`, `alamat`) VALUES
(3, 'audyrivandika@gmail.com', 'rivandika', '$2y$10$qFldk6g/lzc8nIYxUYit1e7yjgVKeGLHn2p.0AgUtnHkAzBpQ..QS', 'rivandika audy rasyid', '2004-12-31', '085373078887', 'jalan nangka'),
(4, 'audy@gmail.com', 'audy', '$2y$10$GXNSEj4GuBQC/5UMITgiM.1exTccllBlSRy0ttG/mbWXnhTVzicea', 'rivandika audy rasyid', '2025-06-10', '0', ''),
(5, 'aa@gmail.com', 'dika', '$2y$10$sB.LqNTyjEkEBU8T816DxeiKWC4xuAr3LclthuLProDu1QXE59L8S', 'aaaa', '2025-06-01', '09876432', 'disini');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_penyewa` (`id_penyewa`),
  ADD KEY `id_kamar` (`id_kamar`),
  ADD KEY `fk_pembayaran_reservasi` (`id_reservasi`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id_reservasi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id_reservasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_reservasi` FOREIGN KEY (`id_reservasi`) REFERENCES `reservasi` (`id_reservasi`),
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_penyewa`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `reservasi_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
