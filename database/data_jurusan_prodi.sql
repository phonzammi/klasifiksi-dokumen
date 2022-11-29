-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 29, 2022 at 10:22 AM
-- Server version: 10.3.22-MariaDB-0+deb10u1
-- PHP Version: 7.4.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jetstrap-livewire`
--

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id`, `nama_jurusan`, `created_at`, `updated_at`) VALUES
(1, 'TEKNIK SIPIL', NULL, NULL),
(2, 'TEKNIK KIMIA', NULL, NULL),
(3, 'TEKNIK MESIN', NULL, NULL),
(4, 'TEKNIK ELEKTRO', NULL, NULL),
(5, 'TATA NIAGA', NULL, NULL),
(6, 'TEKNOLOGI INFORMASI KOMPUTER (TIK)', NULL, NULL);

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`id`, `jurusan_id`, `nama_prodi`, `created_at`, `updated_at`) VALUES
(1, 1, 'PST Teknologi Rekayasa Konstruksi Jalan dan Jembatan (TRKJJ)', NULL, NULL),
(2, 1, 'PST Teknologi Rekayasa Konstruksi Bangunan Gedung (TRKBG)', NULL, NULL),
(3, 1, 'PS D3 Teknologi Konstruksi Bangunan Air (TKBA)', NULL, NULL),
(4, 1, 'PS D3 Teknologi Konstruksi Jalan dan Jembatan (TKJJ)', NULL, NULL),
(5, 1, 'PS D2 Jalur Cepat Pengukuran dan Penggambaran Tapak Bangunan Gedung (P2TBG)', NULL, NULL),
(6, 2, 'Sarjana Terapan Teknologi Rekayasa Kimia Industri', NULL, NULL),
(7, 2, 'Diploma Tiga Teknologi Kimia', NULL, NULL),
(8, 2, 'Diploma Tiga Teknologi Pengolahan Minyak dan Gas', NULL, NULL),
(9, 3, 'Sarjana Terapan Teknologi Rekayasa Manufaktur', NULL, NULL),
(10, 3, 'Sarjana Terapan Teknologi Rekayasa Pengelasan dan Fabrikasi', NULL, NULL),
(11, 3, 'Diploma Tiga Teknologi Mesin', NULL, NULL),
(12, 3, 'Diploma Tiga Teknologi Industri', NULL, NULL),
(13, 4, 'Sarjana Terapan Teknologi Rekayasa Instrumentasi dan Kontrol', NULL, NULL),
(14, 4, 'Sarjana Terapan Teknologi Rekayasa Jaringan Telekomunikasi', NULL, NULL),
(15, 4, 'Sarjana Terapan Teknologi Rekayasa Pembangkit Energi', NULL, NULL),
(16, 4, 'Diploma Tiga Teknologi Listrik', NULL, NULL),
(17, 4, 'Diploma Tiga Teknologi Telekomunikasi', NULL, NULL),
(18, 4, 'Diploma Tiga Teknologi Elektronika', NULL, NULL),
(19, 5, 'Magister Terapan Keuangan Islam', NULL, NULL),
(20, 5, 'Sarjana Terapan Akuntansi Lembaga Keuangan Syariah', NULL, NULL),
(21, 5, 'Sarjana Terapan Manajemen Keuangan Sektor Publik', NULL, NULL),
(22, 5, 'Diploma Tiga Akuntansi', NULL, NULL),
(23, 5, 'Diploma Tiga Administrasi Bisnis', NULL, NULL),
(24, 6, 'Sarjana Terapan Teknologi Rekayasa Komputer Jaringan', NULL, NULL),
(25, 6, 'Sarjana Terapan Teknik Informatika', NULL, NULL),
(26, 6, 'Sarjana Terapan Teknologi Rekayasa Multimedia', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
