-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2026 at 07:33 PM
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
-- Database: `reservation_sport`
--

-- --------------------------------------------------------

--
-- Table structure for table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `terrain_id` int(11) DEFAULT NULL,
  `note` int(11) DEFAULT NULL CHECK (`note` between 1 and 5),
  `commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avis`
--

INSERT INTO `avis` (`id`, `utilisateur_id`, `terrain_id`, `note`, `commentaire`) VALUES
(1, 3, 10, 5, 'had great time there !');

-- --------------------------------------------------------

--
-- Table structure for table `disponibilites`
--

CREATE TABLE `disponibilites` (
  `id` int(11) NOT NULL,
  `terrain_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `heure_debut` time DEFAULT NULL,
  `heure_fin` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matchs`
--

CREATE TABLE `matchs` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `nombre_joueurs_max` int(11) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participations`
--

CREATE TABLE `participations` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `match_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `terrain_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `heure_debut` time DEFAULT NULL,
  `heure_fin` time DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'confirmée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `utilisateur_id`, `terrain_id`, `date`, `heure_debut`, `heure_fin`, `statut`) VALUES
(5, 3, 8, '2026-05-04', '16:00:00', '18:00:00', 'cancelled'),
(6, 3, 8, '2026-05-28', '14:00:00', '16:00:00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `terrains`
--

CREATE TABLE `terrains` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `type_sport` varchar(50) DEFAULT NULL,
  `prix` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terrains`
--

INSERT INTO `terrains` (`id`, `nom`, `localisation`, `type_sport`, `prix`) VALUES
(2, 'Stade Al Majd', 'Tangier', 'Football', 120.00),
(3, 'Royal Tennis Club', 'Tangier', 'Tennis', 130.00),
(4, 'Tanger Balia Court', 'Tangier', 'Basketball', 80.00),
(5, 'Palmeraie Tennis Club', 'Marrakesh', 'Tennis', 95.00),
(6, 'Atlas Football Park', 'Marrakesh', 'Football', 100.00),
(7, 'Palmeraie Padel', 'Marrakesh', 'Padel', 145.00),
(8, 'Casa Padel Arena', 'Casablanca', 'Padel', 160.00),
(9, 'Court Central Casa', 'Casablanca', 'Basketball', 80.00),
(10, 'Arena Sport Casa', 'Casablanca', 'Football', 140.00),
(11, 'Tanger Padel Center', 'Tangier', 'Padel', 150.00),
(12, 'Stade Ibn Batouta', 'Tangier', 'Football', 90.00),
(13, 'Beach Tennis Malabata', 'Tangier', 'Tennis', 110.00),
(14, 'Gueliz Basketball Club', 'Marrakesh', 'Basketball', 75.00),
(15, 'Red City Football', 'Marrakesh', 'Football', 85.00),
(16, 'Hivernage Padel Club', 'Marrakesh', 'Padel', 155.00),
(17, 'Ain Diab Tennis Club', 'Casablanca', 'Tennis', 120.00),
(18, 'Maarif Football Ground', 'Casablanca', 'Football', 100.00),
(19, 'Anfa Basketball Arena', 'Casablanca', 'Basketball', 90.00);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Houyam Sbihi', 'houyam@test.com', '$2y$10$jxsgG3h2Fjqfa2C7XfNTBuYxdzUgjZ/b71VcxG5A5vm8sSjQxMs.O', 'admin'),
(2, 'Houyam Sbihi', 'houyamsbihi@gmail.com', '$2y$10$TFKyThT/TwhBl8aA4AvUterq8/f15ZCMUKHnfpikBuiMS2JxUArmS', 'user'),
(3, 'aya abbadi', 'aya@test.com', '$2y$10$BOskc.9oyE1RUUzyRlLUOuVQu9mDvA.6PbafbrO01fMeAfLlnX0va', 'user'),
(4, 'Alice Campbell', 'alice123@gmail.com', '$2y$10$wZo9nnD8VkhIo7/uoMfYpOQC9PpDL.G3Zo2BjWyuow1tGhJsWz1Uq', 'user'),
(5, 'alaoui', 'alaoui@test.com', '$2y$10$JMtp4J2/B4rKiEgh705pbe1ez8QvcI/F1f8UgndP3EQrakAaHH6Ai', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `terrain_id` (`terrain_id`);

--
-- Indexes for table `disponibilites`
--
ALTER TABLE `disponibilites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `terrain_id` (`terrain_id`);

--
-- Indexes for table `matchs`
--
ALTER TABLE `matchs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Indexes for table `participations`
--
ALTER TABLE `participations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `match_id` (`match_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_reservation` (`terrain_id`,`date`,`heure_debut`,`heure_fin`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Indexes for table `terrains`
--
ALTER TABLE `terrains`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `disponibilites`
--
ALTER TABLE `disponibilites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `matchs`
--
ALTER TABLE `matchs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participations`
--
ALTER TABLE `participations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `terrains`
--
ALTER TABLE `terrains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`terrain_id`) REFERENCES `terrains` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `disponibilites`
--
ALTER TABLE `disponibilites`
  ADD CONSTRAINT `disponibilites_ibfk_1` FOREIGN KEY (`terrain_id`) REFERENCES `terrains` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matchs`
--
ALTER TABLE `matchs`
  ADD CONSTRAINT `matchs_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `participations`
--
ALTER TABLE `participations`
  ADD CONSTRAINT `participations_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participations_ibfk_2` FOREIGN KEY (`match_id`) REFERENCES `matchs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`terrain_id`) REFERENCES `terrains` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
