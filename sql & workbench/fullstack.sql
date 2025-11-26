-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 04:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fullstack`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `username` varchar(20) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `nrp_mahasiswa` char(9) DEFAULT NULL,
  `npk_dosen` char(6) DEFAULT NULL,
  `isadmin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`username`, `password`, `nrp_mahasiswa`, `npk_dosen`, `isadmin`) VALUES
('admin', '$2y$10$zk2pN5CxpmuvRvBZpKdU3O77bP3Tn3lfgn6v.eIH4IVNweAUnSkSC', NULL, NULL, 1),
('fikri', '$2y$10$.v.XpOunlExjcALoIH6icOeR/1JrIL712nsx/RQKDfXeRBlZWm6NS', NULL, '222131', 0),
('heru', '$2y$10$u/TN2TxiHkKXy8b7AhaJL.7I6lGP4z4xZ/YJsvvVi52LVMVuZfsR2', NULL, '192014', 0),
('ming', '$2y$10$rUZ8IWFNh2lWdPNEM3Aot.CxDAmmXZw7SErY5qRWrUvoIQA8fjx3a', NULL, '218122', 0),
('s160423014', '$2y$10$DLGTh8IqUIijXeByyZi9lOeIjbqdY6VUKXOcyweo8owiS4LIVZAxu', '160423014', NULL, 0),
('s160423099', '$2y$10$GBFFNVWeTRF8uKzH/Fwmtu6pKzPo6NoYe2MW3tibmgOxYn9S38ioC', '160423099', NULL, 0),
('s160423125', '$2y$10$EBrcwjK4z5yxn8ciygS/.O9bhpIpDIqJkt7tw16lWANpRO7DptPlK', '160423125', NULL, 0),
('s160423135', '$2y$10$9AF0ugBOaEgjsc3KMmeh..LJzvWVGnCFrQWXmcjJ9jz6b3mH5I77e', '160423135', NULL, 0),
('tyrza', '$2y$10$tBAKcOB/yt9c0f8rkV/Qk.FrokAgjqYEzh50khfz43Y/KR.riDU4K', NULL, '210134', 0);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `idchat` int(11) NOT NULL,
  `idthread` int(11) NOT NULL,
  `username_pembuat` varchar(20) NOT NULL,
  `isi` text DEFAULT NULL,
  `tanggal_pembuatan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `npk` char(6) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `foto_extension` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`npk`, `nama`, `foto_extension`) VALUES
('192014', 'Heru Arwoko, M.T.', 'jpg'),
('210134', 'Tyrza Adelia, M.Inf.Tech.', 'jpg'),
('218122', 'Mikhael Ming Khosasih, M.M., M.Kom.', 'jpg'),
('222131', 'Fikri Baharuddin, M.Kom.', 'jpg');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `idevent` int(11) NOT NULL,
  `idgrup` int(11) NOT NULL,
  `judul` varchar(45) DEFAULT NULL,
  `judul_slug` varchar(45) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jenis` enum('Privat','Publik') DEFAULT NULL,
  `poster_extension` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grup`
--

CREATE TABLE `grup` (
  `idgrup` int(11) NOT NULL,
  `username_pembuat` varchar(20) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `deskripsi` varchar(45) DEFAULT NULL,
  `tanggal_pembentukan` datetime DEFAULT NULL,
  `jenis` enum('Privat','Publik') DEFAULT NULL,
  `kode_pendaftaran` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `grup`
--

INSERT INTO `grup` (`idgrup`, `username_pembuat`, `nama`, `deskripsi`, `tanggal_pembentukan`, `jenis`, `kode_pendaftaran`) VALUES
(1, 'heru', 'Panitia ILPC', 'Informatics Logical Programming Competition -', '2023-08-01 08:00:00', 'Publik', 'ILPC2024'),
(2, 'tyrza', 'Panitia Maniac', 'Multimedia and Information Academic Committee', '2023-08-05 09:00:00', 'Privat', 'MANIAC24'),
(3, 'ming', 'Panitia Industrial Game', 'Simulasi permainan industri dan manajemen pab', '2023-08-10 10:00:00', 'Publik', 'IG2024'),
(4, 'fikri', 'Panitia CEG', 'Chemical Engineering Games - Lomba keilmuan t', '2023-08-12 13:00:00', 'Privat', 'CEG2024'),
(5, 'tyrza', 'Escape', 'Lomba yang diadakan oleh KMM SPORT Ubaya terk', '2025-11-26 00:00:00', 'Publik', '5S0DMWPZ');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nrp` char(9) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `gender` enum('Pria','Wanita') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `angkatan` year(4) DEFAULT NULL,
  `foto_extention` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nrp`, `nama`, `gender`, `tanggal_lahir`, `angkatan`, `foto_extention`) VALUES
('160423014', 'Kevin Margason Winata', 'Pria', '2005-03-08', '2023', 'jpg'),
('160423099', 'Agnesha Riby Tjoanda', 'Wanita', '2005-03-08', '2023', 'jpg'),
('160423125', 'Ariyanto Chandra', 'Pria', '2006-04-10', '2023', 'jpg'),
('160423135', 'Hon Felix Edward', 'Pria', '2025-10-01', '2023', 'jpg');

-- --------------------------------------------------------

--
-- Table structure for table `member_grup`
--

CREATE TABLE `member_grup` (
  `idgrup` int(11) NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `member_grup`
--

INSERT INTO `member_grup` (`idgrup`, `username`) VALUES
(1, 'heru'),
(2, 's160423125'),
(2, 'tyrza'),
(3, 'ming'),
(4, 'fikri'),
(5, 'tyrza');

-- --------------------------------------------------------

--
-- Table structure for table `thread`
--

CREATE TABLE `thread` (
  `idthread` int(11) NOT NULL,
  `username_pembuat` varchar(20) NOT NULL,
  `idgrup` int(11) NOT NULL,
  `tanggal_pembuatan` datetime DEFAULT NULL,
  `status` enum('Open','Close') DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`username`),
  ADD KEY `fk_akun_mahasiswa_idx` (`nrp_mahasiswa`),
  ADD KEY `fk_akun_dosen1_idx` (`npk_dosen`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`idchat`),
  ADD KEY `fk_chat_thread1_idx` (`idthread`),
  ADD KEY `fk_chat_akun1_idx` (`username_pembuat`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`npk`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`idevent`),
  ADD KEY `fk_event_grup1_idx` (`idgrup`);

--
-- Indexes for table `grup`
--
ALTER TABLE `grup`
  ADD PRIMARY KEY (`idgrup`),
  ADD KEY `fk_grup_akun1_idx` (`username_pembuat`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nrp`);

--
-- Indexes for table `member_grup`
--
ALTER TABLE `member_grup`
  ADD PRIMARY KEY (`idgrup`,`username`),
  ADD KEY `fk_grup_has_akun_akun1_idx` (`username`),
  ADD KEY `fk_grup_has_akun_grup1_idx` (`idgrup`);

--
-- Indexes for table `thread`
--
ALTER TABLE `thread`
  ADD PRIMARY KEY (`idthread`),
  ADD KEY `fk_thread_akun1_idx` (`username_pembuat`),
  ADD KEY `fk_thread_grup1_idx` (`idgrup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `idchat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `idevent` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grup`
--
ALTER TABLE `grup`
  MODIFY `idgrup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `thread`
--
ALTER TABLE `thread`
  MODIFY `idthread` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akun`
--
ALTER TABLE `akun`
  ADD CONSTRAINT `fk_akun_dosen1` FOREIGN KEY (`npk_dosen`) REFERENCES `dosen` (`npk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_akun_mahasiswa` FOREIGN KEY (`nrp_mahasiswa`) REFERENCES `mahasiswa` (`nrp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `fk_chat_akun1` FOREIGN KEY (`username_pembuat`) REFERENCES `akun` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_chat_thread1` FOREIGN KEY (`idthread`) REFERENCES `thread` (`idthread`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_event_grup1` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`idgrup`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grup`
--
ALTER TABLE `grup`
  ADD CONSTRAINT `fk_grup_akun1` FOREIGN KEY (`username_pembuat`) REFERENCES `akun` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `member_grup`
--
ALTER TABLE `member_grup`
  ADD CONSTRAINT `fk_grup_has_akun_akun1` FOREIGN KEY (`username`) REFERENCES `akun` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grup_has_akun_grup1` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`idgrup`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thread`
--
ALTER TABLE `thread`
  ADD CONSTRAINT `fk_thread_akun1` FOREIGN KEY (`username_pembuat`) REFERENCES `akun` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_thread_grup1` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`idgrup`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
