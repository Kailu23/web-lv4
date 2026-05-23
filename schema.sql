-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2026 at 07:50 PM
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
-- Database: `lv4`
--
CREATE DATABASE IF NOT EXISTS `lv4` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lv4`;

-- --------------------------------------------------------

--
-- Table structure for table `filmovi`
--

CREATE TABLE `filmovi` (
  `id` int(11) NOT NULL,
  `naslov` varchar(255) NOT NULL,
  `zanr` varchar(100) NOT NULL,
  `godina` int(11) NOT NULL,
  `trajanje_min` int(11) NOT NULL,
  `ocjena` decimal(3,1) NOT NULL,
  `reziser` varchar(100) DEFAULT NULL,
  `zemlja` varchar(100) DEFAULT NULL,
  `opis` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `filmovi`
--

INSERT INTO `filmovi` (`id`, `naslov`, `zanr`, `godina`, `trajanje_min`, `ocjena`, `reziser`, `zemlja`, `opis`, `created_at`) VALUES
(1, 'The Shawshank Redemption', 'Drama', 1994, 142, 9.3, 'Frank Darabont', 'USA', NULL, '2026-05-23 14:00:26'),
(2, 'The Godfather', 'Crime, Drama', 1972, 175, 9.2, 'Francis Ford Coppola', 'USA', NULL, '2026-05-23 14:00:26'),
(3, 'The Dark Knight', 'Action, Crime', 2008, 152, 9.0, 'Christopher Nolan', 'UK/USA', NULL, '2026-05-23 14:00:26'),
(4, 'Schindler\'s List', 'Biography, Drama', 1993, 195, 9.0, 'Steven Spielberg', 'USA', NULL, '2026-05-23 14:00:26'),
(5, '12 Angry Men', 'Crime, Drama', 1957, 96, 9.0, 'Sidney Lumet', 'USA', NULL, '2026-05-23 14:00:26'),
(6, 'Pulp Fiction', 'Crime, Drama', 1994, 154, 8.9, 'Quentin Tarantino', 'USA', NULL, '2026-05-23 14:00:26'),
(7, 'The Lord of the Rings: The Return of the King', 'Action, Adventure', 2003, 201, 9.0, 'Peter Jackson', 'NZ/USA', NULL, '2026-05-23 14:00:26'),
(8, 'Fight Club', 'Drama', 1999, 139, 8.8, 'David Fincher', 'USA', NULL, '2026-05-23 14:00:26'),
(9, 'Inception', 'Action, Adventure', 2010, 148, 8.8, 'Christopher Nolan', 'USA/UK', NULL, '2026-05-23 14:00:26'),
(10, 'The Matrix', 'Action, Sci-Fi', 1999, 136, 8.7, 'Lana Wachowski', 'USA', NULL, '2026-05-23 14:00:26'),
(11, 'Goodfellas', 'Biography, Crime', 1990, 145, 8.7, 'Martin Scorsese', 'USA', NULL, '2026-05-23 14:00:26'),
(12, 'One Flew Over the Cuckoo\'s Nest', 'Drama', 1975, 133, 8.7, 'Milos Forman', 'USA', NULL, '2026-05-23 14:00:26'),
(13, 'Seven Samurai', 'Action, Drama', 1954, 207, 8.6, 'Akira Kurosawa', 'Japan', NULL, '2026-05-23 14:00:26'),
(14, 'Se7en', 'Crime, Drama', 1995, 127, 8.6, 'David Fincher', 'USA', NULL, '2026-05-23 14:00:26'),
(15, 'The Silence of the Lambs', 'Crime, Drama', 1991, 118, 8.6, 'Jonathan Demme', 'USA', NULL, '2026-05-23 14:00:26'),
(16, 'City of God', 'Crime, Drama', 2002, 130, 8.6, 'Fernando Meirelles', 'Brazil', NULL, '2026-05-23 14:00:26'),
(17, 'Life Is Beautiful', 'Comedy, Drama', 1997, 116, 8.6, 'Roberto Benigni', 'Italy', NULL, '2026-05-23 14:00:26'),
(18, 'Interstellar', 'Adventure, Drama', 2014, 169, 8.7, 'Christopher Nolan', 'USA/UK', NULL, '2026-05-23 14:00:26'),
(19, 'Saving Private Ryan', 'Drama, War', 1998, 169, 8.6, 'Steven Spielberg', 'USA', NULL, '2026-05-23 14:00:26'),
(20, 'Parasite', 'Drama, Thriller', 2019, 132, 8.5, 'Bong Joon Ho', 'South Korea', NULL, '2026-05-23 14:00:26'),
(21, 'The Green Mile', 'Crime, Drama', 1999, 189, 8.6, 'Frank Darabont', 'USA', NULL, '2026-05-23 14:00:26'),
(22, 'Star Wars: Episode IV - A New Hope', 'Action, Adventure', 1977, 121, 8.6, 'George Lucas', 'USA', NULL, '2026-05-23 14:00:26'),
(23, 'Terminator 2: Judgment Day', 'Action, Sci-Fi', 1991, 137, 8.6, 'James Cameron', 'USA', NULL, '2026-05-23 14:00:26'),
(24, 'Back to the Future', 'Adventure, Comedy', 1985, 116, 8.5, 'Robert Zemeckis', 'USA', NULL, '2026-05-23 14:00:26'),
(25, 'The Pianist', 'Biography, Drama', 2002, 150, 8.5, 'Roman Polanski', 'France/Poland', NULL, '2026-05-23 14:00:26'),
(26, 'Psycho', 'Horror, Mystery', 1960, 109, 8.5, 'Alfred Hitchcock', 'USA', NULL, '2026-05-23 14:00:26'),
(27, 'Gladiator', 'Action, Adventure', 2000, 155, 8.5, 'Ridley Scott', 'USA/UK', NULL, '2026-05-23 14:00:26'),
(28, 'The Lion King', 'Animation, Adventure', 1994, 88, 8.5, 'Roger Allers', 'USA', NULL, '2026-05-23 14:00:26'),
(29, 'The Departed', 'Crime, Drama', 2006, 151, 8.5, 'Martin Scorsese', 'USA', NULL, '2026-05-23 14:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE `korisnici` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `uloga` enum('korisnik','admin') DEFAULT 'korisnik',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`id`, `username`, `email`, `lozinka`, `uloga`, `created_at`) VALUES
(1, 'admin', 'admin@lv4.hr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-05-23 14:00:26'),
(2, 'test', 'test@test.t', '$2y$10$qGAfhffWxlwOoqwPSMlhwuxN6pouYO/9EW4BSmRaMNkl3XoLCRkZG', 'korisnik', '2026-05-23 14:23:49'),
(3, 'test2', 'test2@test.hr', '$2y$10$iE2vcmI5.8iKpv2ZcsYPbe1Q3gS2KQBh99bosIpqGFJwRl5KANkpa', 'korisnik', '2026-05-23 15:21:50');

-- --------------------------------------------------------

--
-- Table structure for table `ocjene`
--

CREATE TABLE `ocjene` (
  `id` int(11) NOT NULL,
  `id_korisnik` int(11) NOT NULL,
  `id_slika` int(11) NOT NULL,
  `ocjena` tinyint(4) NOT NULL CHECK (`ocjena` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `vrijeme_ocjene` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ocjene`
--

INSERT INTO `ocjene` (`id`, `id_korisnik`, `id_slika`, `ocjena`, `komentar`, `vrijeme_ocjene`) VALUES
(1, 2, 2, 4, '', '2026-05-23 14:46:18'),
(6, 2, 1, 5, '', '2026-05-23 15:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `slike`
--

CREATE TABLE `slike` (
  `id` int(11) NOT NULL,
  `naziv_datoteke` varchar(255) NOT NULL,
  `opis` varchar(255) DEFAULT NULL,
  `putanja` varchar(500) NOT NULL,
  `izvor` enum('lokalno','api') DEFAULT 'lokalno',
  `uploaded_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slike`
--

INSERT INTO `slike` (`id`, `naziv_datoteke`, `opis`, `putanja`, `izvor`, `uploaded_by`, `created_at`) VALUES
(1, 'photo1.jpg', 'Meowtain', 'public/images/photo1.jpg', 'lokalno', 1, '2026-05-23 14:22:48'),
(2, 'photo2.jpg', 'Fake RNG', 'public/images/photo2.jpg', 'lokalno', 1, '2026-05-23 14:23:05'),
(3, 'api_slika_1.jpg', 'Slika 1', 'https://picsum.photos/900/600?random=1', 'api', 1, '2026-05-23 15:30:16'),
(4, 'api_slika_2.jpg', 'Slika 2', 'https://picsum.photos/900/600?random=2', 'api', 1, '2026-05-23 15:30:16'),
(5, 'api_slika_3.jpg', 'Slika 3', 'https://picsum.photos/900/600?random=3', 'api', 1, '2026-05-23 15:30:16'),
(6, 'api_slika_4.jpg', 'Slika 4', 'https://picsum.photos/900/600?random=4', 'api', 1, '2026-05-23 15:30:16'),
(7, 'api_slika_5.jpg', 'Slika 5', 'https://picsum.photos/900/600?random=5', 'api', 1, '2026-05-23 15:30:16'),
(8, 'api_slika_6.jpg', 'Slika 6', 'https://picsum.photos/900/600?random=6', 'api', 1, '2026-05-23 15:30:16'),
(9, 'api_slika_7.jpg', 'Slika 7', 'https://picsum.photos/900/600?random=7', 'api', 1, '2026-05-23 15:30:16'),
(10, 'api_slika_8.jpg', 'Slika 8', 'https://picsum.photos/900/600?random=8', 'api', 1, '2026-05-23 15:30:16'),
(11, 'api_slika_9.jpg', 'Slika 9', 'https://picsum.photos/900/600?random=9', 'api', 1, '2026-05-23 15:30:16'),
(12, 'api_slika_10.jpg', 'Slika 10', 'https://picsum.photos/900/600?random=10', 'api', 1, '2026-05-23 15:30:16'),
(13, 'api_slika_11.jpg', 'Slika 11', 'https://picsum.photos/900/600?random=11', 'api', 1, '2026-05-23 15:30:16'),
(14, 'api_slika_12.jpg', 'Slika 12', 'https://picsum.photos/900/600?random=12', 'api', 1, '2026-05-23 15:30:16');

-- --------------------------------------------------------

--
-- Table structure for table `zeljeni_filmovi`
--

CREATE TABLE `zeljeni_filmovi` (
  `id` int(11) NOT NULL,
  `korisnik_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `filmovi`
--
ALTER TABLE `filmovi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ocjene`
--
ALTER TABLE `ocjene`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_slika` (`id_korisnik`,`id_slika`),
  ADD KEY `id_slika` (`id_slika`);

--
-- Indexes for table `slike`
--
ALTER TABLE `slike`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `zeljeni_filmovi`
--
ALTER TABLE `zeljeni_filmovi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_film` (`korisnik_id`,`film_id`),
  ADD KEY `film_id` (`film_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `filmovi`
--
ALTER TABLE `filmovi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ocjene`
--
ALTER TABLE `ocjene`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `slike`
--
ALTER TABLE `slike`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `zeljeni_filmovi`
--
ALTER TABLE `zeljeni_filmovi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ocjene`
--
ALTER TABLE `ocjene`
  ADD CONSTRAINT `ocjene_ibfk_1` FOREIGN KEY (`id_korisnik`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ocjene_ibfk_2` FOREIGN KEY (`id_slika`) REFERENCES `slike` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `slike`
--
ALTER TABLE `slike`
  ADD CONSTRAINT `slike_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `korisnici` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `zeljeni_filmovi`
--
ALTER TABLE `zeljeni_filmovi`
  ADD CONSTRAINT `zeljeni_filmovi_ibfk_1` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnici` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `zeljeni_filmovi_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `filmovi` (`id`) ON DELETE CASCADE;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"lv4\",\"table\":\"filmovi\"},{\"db\":\"lv4\",\"table\":\"zeljeni_filmovi\"},{\"db\":\"lv4\",\"table\":\"korisnici\"},{\"db\":\"lv4\",\"table\":\"slike\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-05-23 17:50:28', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"en_GB\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
