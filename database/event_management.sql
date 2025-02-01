-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2025 at 09:54 AM
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
-- Database: `movie_rating`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendee_registration`
--

CREATE TABLE `attendee_registration` (
  `id` int(11) NOT NULL,
  `name_attendee` varchar(255) NOT NULL,
  `phone_attendee` varchar(40) NOT NULL,
  `email_attendee` varchar(100) NOT NULL,
  `attendee_opinion` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendee_registration`
--

INSERT INTO `attendee_registration` (`id`, `name_attendee`, `phone_attendee`, `email_attendee`, `attendee_opinion`, `user_id`, `id_event`, `created_at`, `updated_at`) VALUES
(23, 'Oli Ahammed Sarker', '01600000127', 'oliahammed65@gmail.com', 'hukihkj', 54, 1, '2025-01-31 23:33:30', '2025-01-31 23:33:30'),
(24, 'Oli Ahammed Sarker', '01600000127', 'oliahammed65@gmail.com', 'sdfsd', 53, 52, '2025-02-01 12:09:37', '2025-02-01 12:09:37'),
(25, 'Oli Ahammed Sarker', '1600000127', 'oliahammed65@gmail.com', 'dsfsdf', 53, 1, '2025-02-01 13:04:25', '2025-02-01 13:04:25'),
(26, 'Oli Ahammed Sarker', '1627324997', 'oliahammed02@gmail.com', 'sdfsd', 53, 42, '2025-02-01 13:04:31', '2025-02-01 13:04:31'),
(27, 'Oli Ahammed Sarker', '1600000127', 'oliahammed65@gmail.com', 'sdfsdf', 53, 46, '2025-02-01 14:12:25', '2025-02-01 14:12:25');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `max_capacity` int(6) NOT NULL,
  `description` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `event_name`, `place`, `max_capacity`, `description`, `event_date`, `created_at`, `updated_at`) VALUES
(1, 'event 1 update', 'place 1 update', 18, 'description ', '2025-02-25', '2025-01-25 16:17:24', '2025-02-01 14:10:04'),
(42, 'event 2 update', 'place 2 update', 3, 'dsfsdf update', '2025-01-31', '2025-01-31 13:58:12', '2025-01-31 23:31:43'),
(46, 'event 3 ', 'place 3 ', 5, 'update', '2025-01-15', '2025-01-31 14:02:56', '2025-02-01 14:10:31');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(999) NOT NULL,
  `phone` varchar(99) NOT NULL,
  `password` varchar(999) NOT NULL,
  `email` varchar(999) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `phone`, `password`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'anik', '1600000000', '$2y$10$6s7Kg3A0iUf.k5r2D/ItHuoigNjQoKLuY.khe482CBIdueqgc12Mq', 'oliahammed@gmail.com', 0, '2025-02-01 05:22:36', '2025-02-01 05:23:06'),
(53, 'Oli Ahmmed', '01600000127', '$2y$10$6s7Kg3A0iUf.k5r2D/ItHuoigNjQoKLuY.khe482CBIdueqgc12Mq', 'oliahammed65@gmail.com', 1, '2025-02-01 05:22:36', '2025-02-01 05:23:06'),
(54, 'anik', '01627324997', '$2y$10$98O95SUe1K8Ay7X2NCTAfO0yq2QkuJr//55w.IBL3XjwbLZhfwNSa', 'oliahammed02@gmail.com', 0, '2025-02-01 05:22:36', '2025-02-01 05:23:06'),
(67, 'atik', '631313213', '$2y$10$bnZ2qhhIQ8UYegGWdaC.qOS7CCCod0ki2NM0b5ZGIkU1G6Gwmek4C', 'atik@gmail.com', 0, '2025-02-01 06:40:15', '2025-02-01 06:40:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendee_registration`
--
ALTER TABLE `attendee_registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`user_id`),
  ADD KEY `eventID` (`id_event`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventDate` (`event_date`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendee_registration`
--
ALTER TABLE `attendee_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
