-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2024 at 12:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `umkm`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'hp'),
(2, 'jam');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_user` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'gambar_default.png',
  `id_kategori` int(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_user`, `id_produk`, `nama_produk`, `harga`, `gambar`, `id_kategori`) VALUES
(6, 2, 'Iphone 115', 760, 'product-item5.jpg', 1),
(6, 15, 'Iphone 13', 1200, 'product-item2.jpg', 1),
(6, 16, 'Iphone 13', 890, 'product-item4.jpg', 1),
(6, 17, 'Iphone 15', 2350, 'product-item5.jpg', 1),
(6, 18, 'Jam Tangan', 560, 'cart-item2.jpg', 2),
(6, 19, 'Jam Tangan', 560, 'product-item7.jpg', 2),
(6, 20, 'Jam Tangan 03', 560, 'product-item8.jpg', 2),
(6, 21, 'Jam Tangan 03', 560, 'insta-item2.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(11) NOT NULL,
  `role` enum('admin','super admin') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `role`) VALUES
(6, 'joko', '$2y$10$t1CvwK2AgJYf0WnIA9soleTwmv1Vg6XASNWNRO7ppGrMc/nv2bihG', 'joko@gmail.', 'admin'),
(7, 'van', '$2y$10$gZPI2hjtfmpuisegRCNsHOf/ORWa50c5aYOBgOCQ8FMwzG56UiiI.', 'van@gmail.c', 'super admin'),
(9, 'dodo', '$2y$10$rexsMRw19CjuxqKWlMJBf.aJpQNuBuat6Oq/oT7PGJv97OnmpuiUG', 'mbox.gaming', 'admin'),
(10, 'intan', '$2y$10$b63bNP11Hifafk8I.Ju9xe8q9xiQGd/P71EcpzrDr1n2zVGC4RyNK', 'intanvanes@', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
