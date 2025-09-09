-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 09:39 AM
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
-- Database: `the_white_palace`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$yNRowRY..BGD99/JP3gBhu/merwv1J73HHxiUwXoQdOdmkBZUaJRC', '2025-09-07 15:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_phone` varchar(30) DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `nights` int(11) DEFAULT 0,
  `total_price` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `room_id`, `user_name`, `user_email`, `user_phone`, `check_in`, `check_out`, `nights`, `total_price`, `status`, `created_at`) VALUES
(21, 46, 'Md. Saqline Zaman', 'saqlinemoaj@gmail.com', '01728108888', '2025-09-09', '2025-09-10', 1, 4000.00, 'rejected', '2025-09-08 22:15:17'),
(22, 46, 'Md. Saqline Zaman', 'saqlinemoaj@gmail.com', '01728108888', '2025-09-09', '2025-09-10', 1, 4000.00, 'rejected', '2025-09-08 22:15:52'),
(23, 46, 'admins', 'ceno@mailinator.com', '06859989977', '2025-09-09', '2025-09-10', 1, 4000.00, 'pending', '2025-09-08 22:16:18'),
(24, 46, 'Md. Saqline Zaman', 'saqlinemoaj@gmail.com', '01728108888', '2025-09-09', '2025-09-10', 1, 4000.00, 'rejected', '2025-09-08 22:17:21'),
(25, 46, 'Md. Saqline Zaman', 'saqlinemoaj@gmail.com', '01728108888', '2025-09-09', '2025-09-10', 1, 4000.00, 'rejected', '2025-09-08 22:18:01'),
(26, 50, 'Henry Rich', 'qehadax@mailinator.com', '7212245032', '2025-09-09', '2025-09-10', 1, 10000.00, 'rejected', '2025-09-08 22:24:42'),
(27, 50, 'Md. Saqline Zaman', 'saqlinemoaj@gmail.com', '01728108888', '2025-09-09', '2025-09-10', 1, 10000.00, 'rejected', '2025-09-08 22:25:14'),
(28, 50, 'admins', 'ceno@mailinator.com', '06859989977', '2025-09-09', '2025-09-10', 1, 10000.00, 'pending', '2025-09-08 22:40:31'),
(29, 50, 'admins', 'ceno@mailinator.com', '06859989977', '2025-10-22', '2026-01-24', 94, 940000.00, 'rejected', '2025-09-08 22:41:19'),
(30, 50, 'Alfreda', 'admin@gmail.com', '06859989977', '2025-09-09', '2025-09-10', 1, 10000.00, 'rejected', '2025-09-08 22:42:01'),
(31, 48, 'Alfreda', 'admin@gmail.com', '06859989977', '2025-09-09', '2025-09-10', 1, 6500.00, 'pending', '2025-09-09 06:30:36'),
(32, 47, 'Henry Rich', 'qehadax@mailinator.com', '7212245032', '2025-09-09', '2025-09-10', 1, 5000.00, 'approved', '2025-09-09 06:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `room_type`, `image`, `created_at`) VALUES
(28, 'Couple', 'couple4.jpg', '2025-09-08 21:46:41'),
(29, 'Family suite', 'family suite.jpg', '2025-09-08 21:47:17'),
(30, 'Personal Resort', 'resort.jpg', '2025-09-08 21:47:55'),
(31, 'Double', 'double1.jpg', '2025-09-08 21:50:02'),
(32, 'Single', 'single2.jpg', '2025-09-08 21:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `capacity` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `gallery_images` text DEFAULT NULL,
  `total_rooms` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `category_id`, `name`, `price`, `capacity`, `description`, `amenities`, `image_url`, `gallery_images`, `total_rooms`) VALUES
(45, 32, 'Single Room', 1500.00, '1', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '[\"AC\",\"Free Wifi\",\"Smoking\"]', 'admin/uploads/rooms/room_1757368614_70c6d4.jpg', '[]', 10),
(46, 28, 'Couple Room', 4000.00, '10', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '[\"Ac\",\"Free Wifi\",\"Tv\",\"Ocean View\",\"Private Pool\"]', 'admin/uploads/rooms/room_1757369244_b062ff.jpg', '[\"admin\\/uploads\\/rooms\\/gallery_1757369244_0_92a6.jpg\",\"admin\\/uploads\\/rooms\\/gallery_1757369244_1_0ac9.jpg\"]', 10),
(47, 31, 'Double room', 5000.00, '3', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '[\"Ac\",\"Free Wifi\",\"Tv\",\"Ocean View\",\"Fridge\",\"2 single size bed\"]', 'admin/uploads/rooms/room_1757369519_cb9f30.jpg', '[\"admin\\/uploads\\/rooms\\/gallery_1757369519_0_4e3c.jpg\"]', 6),
(48, 29, 'Family Suite', 6500.00, '4', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '[\"Ac\",\"Free Wifi\",\"Tv\",\"Ocean View\",\"Private Pool\",\"Mini Fridge\",\"2 king size bed\"]', 'admin/uploads/rooms/room_1757369634_52ff13.jpg', '[\"admin\\/uploads\\/rooms\\/gallery_1757369634_0_3873.jpg\"]', 5),
(50, 30, 'Resort', 10000.00, '6', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '[\"Ac\",\"Free Wifi\",\"Tv\",\"Ocean View\",\"Private Pool\",\"Mini Fridge\",\"3 rooms\",\"Free tour guide\"]', 'admin/uploads/rooms/room_1757370095_1d9152.jpg', '[]', 2),
(51, 30, 'Deluxe Suite', 12000.00, '5', 'Lorem ispum is a dummy text since 1991 its e very popular text .', '[\"Ac\",\"Free Wifi\",\"Tv\",\"Ocean View\",\"Private Pool\",\"Mini Fridge\",\"2 king size bed\",\"4 room\",\"free brekfast for first day\"]', 'admin/uploads/rooms/room_1757399791_59a564.jpg', '[\"admin\\/uploads\\/rooms\\/gallery_1757399791_0_fd69.jpg\",\"admin\\/uploads\\/rooms\\/gallery_1757399791_1_aa8f.jpg\"]', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_ibfk_1` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
