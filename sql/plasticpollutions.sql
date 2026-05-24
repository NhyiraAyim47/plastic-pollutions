-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2026 at 11:16 AM
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
-- Database: `plasticpollutions`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `action`, `ip_address`, `created_at`) VALUES
(1, 2, 'Account verified via OTP', '::1', '2026-05-16 20:55:24'),
(2, 2, 'Donated GHS 50.00 to #CleanOurCoast', NULL, '2026-05-17 05:57:45'),
(3, 3, 'Account verified via OTP', '::1', '2026-05-22 17:13:30'),
(4, 3, 'Logged out', '::1', '2026-05-22 17:35:12'),
(5, 2, 'Logged in', '::1', '2026-05-22 17:36:05'),
(6, 2, 'Logged in', '::1', '2026-05-22 23:18:52'),
(7, 2, 'Logged out', '::1', '2026-05-22 23:18:58'),
(8, 2, 'Logged in', '::1', '2026-05-23 08:33:39'),
(9, 2, 'Logged out', '::1', '2026-05-23 08:33:53'),
(10, 6, 'Account verified via OTP', '::1', '2026-05-23 09:01:02'),
(11, 6, 'Donated GHS 200.00 to General Fund', NULL, '2026-05-23 09:03:17'),
(12, 2, 'Logged in', '::1', '2026-05-23 09:27:50'),
(13, 6, 'Logged out', '::1', '2026-05-23 09:57:45'),
(14, 2, 'Logged out', '::1', '2026-05-23 10:03:17'),
(15, 2, 'Logged in', '::1', '2026-05-23 10:10:21');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `content` longtext NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `author` varchar(120) DEFAULT 'PlasticPollutions Team',
  `category` varchar(80) DEFAULT 'News',
  `views` int(11) DEFAULT 0,
  `published_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `excerpt`, `content`, `image_url`, `author`, `category`, `views`, `published_at`) VALUES
(1, 'Ghana Launches National Plastic Waste Policy 2026', 'ghana-plastic-waste-policy-2026', 'The Government of Ghana has unveiled a comprehensive national policy targeting a 60% reduction in single-use plastics by 2030.', '<p>The Government of Ghana announced a landmark policy on plastic waste management, setting an ambitious target of reducing single-use plastic consumption by 60% before 2030. The policy mandates manufacturers to adopt biodegradable packaging, introduces a plastic levy on imported consumer goods, and establishes 50 new community recycling centres across the country.</p><p>Minister for Environment, Science, Technology and Innovation praised the initiative saying it aligns Ghana with global sustainability goals under the UN Plastic Treaty negotiations.</p>', 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=800', 'PlasticPollutions Team', 'Policy', 0, '2026-05-16 20:03:50'),
(2, 'Ocean Plastic: The Silent Crisis Hitting West Africa', 'ocean-plastic-west-africa', 'New research reveals West African coastlines now carry some of the highest concentrations of marine plastic debris globally.', '<p>A study published in the Marine Pollution Bulletin has found that beaches along the West African coast, from Senegal to Nigeria, are accumulating plastic at rates 30% higher than the global average. The primary sources identified include river runoff during rainy seasons, inadequate waste collection infrastructure, and artisanal fishing net disposal.</p><p>PlasticPollutions has partnered with local fishing communities in the Volta Region to implement net-return schemes and shoreline clean-up protocols.</p>', 'https://images.unsplash.com/photo-1621451537084-482c73073a0f?w=800', 'PlasticPollutions Team', 'Research', 1, '2026-05-16 20:03:50'),
(3, 'Recycling Rates in Ghana: The 5% Problem', 'recycling-rates-ghana', 'Despite growing awareness, less than 5% of plastic waste in Ghana is formally recycled. We break down why—and what must change.', '<p>Ghana generates an estimated 1.1 million tonnes of plastic waste annually, yet formal recycling infrastructure captures only a fraction of this. Challenges include a lack of sorting facilities, low commodity prices for recycled plastic, and limited consumer awareness about proper disposal.</p><p>PlasticPollutions advocates for Extended Producer Responsibility (EPR) legislation that would compel manufacturers to fund collection and recycling systems for their packaging.</p>', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800', 'PlasticPollutions Team', 'Environment', 0, '2026-05-16 20:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(180) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `sent_at`) VALUES
(1, 'Ebenezer Ayim', 'Ebenezerayim0@gmail.com', 'Other', 'I want to help', 1, '2026-05-22 16:13:18');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(5) DEFAULT 'GHS',
  `campaign` varchar(150) DEFAULT 'General Fund',
  `message` text DEFAULT NULL,
  `reference` varchar(30) NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'completed',
  `donated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `amount`, `currency`, `campaign`, `message`, `reference`, `status`, `donated_at`) VALUES
(1, 2, 50.00, 'GHS', '#CleanOurCoast', 'Because I want to', 'PP31D2DD752343', 'completed', '2026-05-17 05:57:45'),
(2, 6, 200.00, 'GHS', 'General Fund', 'Because I want to', 'PP305C9C0D650D', 'completed', '2026-05-23 09:03:17');

-- --------------------------------------------------------

--
-- Table structure for table `petitions`
--

CREATE TABLE `petitions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(180) NOT NULL,
  `signed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `petitions`
--

INSERT INTO `petitions` (`id`, `user_id`, `name`, `email`, `signed_at`) VALUES
(1, NULL, 'Ebenezer Ayim', 'Ebenezerayim0@gmail.com', '2026-05-22 16:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `pledges`
--

CREATE TABLE `pledges` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(180) NOT NULL,
  `pledge` text NOT NULL,
  `pledged_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `pu_id` varchar(8) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `is_verified` tinyint(1) DEFAULT 0,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expires` datetime DEFAULT NULL,
  `login_attempts` tinyint(4) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `pu_id`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `is_verified`, `otp_code`, `otp_expires`, `login_attempts`, `locked_until`, `created_at`, `updated_at`) VALUES
(2, 'PU356432', 'Amos Oware', 'Oware', 'ayimnhyirahamos@gmail.com', '$2y$12$jZfRZerGbCcnFmHADvQpVO/TazgirqHt1pyma.jgHPyFZYdUtk8jG', 'admin', 1, NULL, NULL, 0, NULL, '2026-05-16 20:54:41', '2026-05-23 10:07:50'),
(3, 'PU058245', 'Ebenezer', 'Ayim', 'ebenezerayim0@gmail.com', '$2y$12$u.4jiTa8Kke7POsJt/8N7ebFnmEfJzLxCeBB7RhYC6IjmmaGZmzIi', 'user', 1, NULL, NULL, 0, NULL, '2026-05-22 17:13:05', '2026-05-22 17:13:30'),
(6, 'PU995044', 'Nhyira', 'Ayim', 'nhyirahayim@gmail.com', '$2y$12$7UBX5dIaR0Kiu4548pVYZ.99LjK.KF1T2ooDLTOxzZppxl1HwdC/W', 'user', 1, NULL, NULL, 0, NULL, '2026-05-23 09:00:35', '2026-05-23 09:01:02');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `page` varchar(255) DEFAULT '/',
  `visited_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `ip_address`, `page`, `visited_at`) VALUES
(1, '::1', '/', '2026-05-16 20:05:44'),
(2, '::1', '/', '2026-05-17 05:57:55'),
(3, '::1', '/campaigns', '2026-05-17 05:58:12'),
(4, '::1', '/campaigns', '2026-05-17 14:42:15'),
(5, '::1', '/', '2026-05-22 15:50:40'),
(6, '::1', '/what-to-do', '2026-05-22 15:51:25'),
(7, '::1', '/latest', '2026-05-22 16:10:30'),
(8, '::1', '/contact', '2026-05-22 16:10:36'),
(9, '::1', '/how-to-help', '2026-05-22 16:59:53'),
(10, '::1', '/campaigns', '2026-05-22 17:14:04'),
(11, '::1', '/', '2026-05-22 17:35:13'),
(12, '::1', '/how-to-help', '2026-05-22 20:58:27'),
(13, '::1', '/contact', '2026-05-22 21:04:35'),
(14, '::1', '/what-to-do', '2026-05-22 21:04:46'),
(15, '::1', '/team', '2026-05-22 21:08:31'),
(16, '::1', '/share', '2026-05-22 21:14:59'),
(17, '::1', '/', '2026-05-22 21:40:58'),
(18, '::1', '/', '2026-05-22 23:09:13'),
(19, '::1', '/share', '2026-05-22 23:11:46'),
(20, '::1', '/', '2026-05-23 07:16:51'),
(21, '::1', '/', '2026-05-23 08:19:47'),
(22, '::1', '/team', '2026-05-23 09:03:23'),
(23, '::1', '/', '2026-05-23 09:25:48'),
(24, '::1', '/contact', '2026-05-23 09:29:58'),
(25, '::1', '/campaigns', '2026-05-23 09:34:46'),
(26, '::1', '/how-to-help', '2026-05-23 09:34:54'),
(27, '::1', '/share', '2026-05-23 09:35:10'),
(28, '::1', '/latest', '2026-05-23 09:43:51'),
(29, '::1', '/what-to-do', '2026-05-23 09:51:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `petitions`
--
ALTER TABLE `petitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pledges`
--
ALTER TABLE `pledges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pu_id` (`pu_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `petitions`
--
ALTER TABLE `petitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pledges`
--
ALTER TABLE `pledges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `petitions`
--
ALTER TABLE `petitions`
  ADD CONSTRAINT `petitions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pledges`
--
ALTER TABLE `pledges`
  ADD CONSTRAINT `pledges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
