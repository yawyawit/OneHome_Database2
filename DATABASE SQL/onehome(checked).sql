-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2026 at 06:45 AM
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
-- Database: `home`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `address_label` varchar(35) NOT NULL,
  `street_address` varchar(30) NOT NULL,
  `city` varchar(25) NOT NULL,
  `province` varchar(25) NOT NULL,
  `postal_code` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(4) NOT NULL,
  `user_id` int(5) NOT NULL,
  `admin_level` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(5) NOT NULL,
  `request_id` int(5) NOT NULL,
  `quote_id` int(5) NOT NULL,
  `agreed_price` decimal(10,2) NOT NULL,
  `scheduled_start` datetime(6) NOT NULL,
  `scheduled_end` datetime(6) NOT NULL,
  `booking_status` varchar(35) NOT NULL,
  `completed_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `username` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispute`
--

CREATE TABLE `dispute` (
  `dispute_id` int(5) NOT NULL,
  `booking_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `dispute_status` enum('PAID','UNPAID','','') NOT NULL,
  `resolution` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `resolved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(5) NOT NULL,
  `booking_id` int(5) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` varchar(35) NOT NULL,
  `payment_status` enum('PAID','UNPAID','','') NOT NULL,
  `gateway_reference` varchar(35) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payout`
--

CREATE TABLE `payout` (
  `payout_id` int(5) NOT NULL,
  `booking_id` int(5) NOT NULL,
  `provider_id` int(5) NOT NULL,
  `payout_status` enum('PAID','UNPAID','','') NOT NULL,
  `gateway_reference` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE `providers` (
  `provider_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `business_name` varchar(30) NOT NULL,
  `provider_status` varchar(25) NOT NULL,
  `verification_status` varchar(25) NOT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE `quote` (
  `quote_id` int(5) NOT NULL,
  `request_id` int(5) NOT NULL,
  `provider_id` int(5) NOT NULL,
  `offering_id` int(5) NOT NULL,
  `proposed_price` decimal(10,2) NOT NULL,
  `scope` varchar(50) NOT NULL,
  `proposed_start` datetime(6) NOT NULL,
  `proposed_end` datetime(6) NOT NULL,
  `quote_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(5) NOT NULL,
  `booking_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_category`
--

CREATE TABLE `service_category` (
  `category_id` int(5) NOT NULL,
  `category_name` varchar(25) NOT NULL,
  `description` varchar(100) NOT NULL,
  `category_status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_offering`
--

CREATE TABLE `service_offering` (
  `offering_id` int(5) NOT NULL,
  `provider_id` int(5) NOT NULL,
  `category_id` int(5) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(100) NOT NULL,
  `pricing_type` varchar(35) NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `offering_status` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_request`
--

CREATE TABLE `service_request` (
  `request_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `category_id` int(5) NOT NULL,
  `address_id` int(5) NOT NULL,
  `description` varchar(100) NOT NULL,
  `preffered_start` datetime(6) NOT NULL,
  `request_status` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(5) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `password` varchar(90) NOT NULL,
  `account_status` varchar(30) NOT NULL,
  `user_role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `fk_address_customer` (`customer_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `fk_admin_user` (`user_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `fk_booking_request` (`request_id`),
  ADD KEY `fk_booking_quote` (`quote_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `fk_customer_user` (`user_id`);

--
-- Indexes for table `dispute`
--
ALTER TABLE `dispute`
  ADD PRIMARY KEY (`dispute_id`),
  ADD KEY `booking_id` (`booking_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`);

--
-- Indexes for table `payout`
--
ALTER TABLE `payout`
  ADD PRIMARY KEY (`payout_id`),
  ADD KEY `fk_payout_booking` (`booking_id`),
  ADD KEY `fk_payout_provider` (`provider_id`);

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`provider_id`),
  ADD KEY `fk_provider_user` (`user_id`);

--
-- Indexes for table `quote`
--
ALTER TABLE `quote`
  ADD PRIMARY KEY (`quote_id`),
  ADD KEY `fk_quote_request` (`request_id`),
  ADD KEY `fk_quote_provider` (`provider_id`),
  ADD KEY `fk_quote_offering` (`offering_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_review_booking` (`booking_id`),
  ADD KEY `fk_review_customer` (`customer_id`);

--
-- Indexes for table `service_category`
--
ALTER TABLE `service_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `service_offering`
--
ALTER TABLE `service_offering`
  ADD PRIMARY KEY (`offering_id`),
  ADD KEY `fk_offering_provider` (`provider_id`),
  ADD KEY `fk_offering_category` (`category_id`);

--
-- Indexes for table `service_request`
--
ALTER TABLE `service_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_request_customer` (`customer_id`),
  ADD KEY `fk_request_category` (`category_id`),
  ADD KEY `fk_request_address` (`address_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payout`
--
ALTER TABLE `payout`
  MODIFY `payout_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `providers`
--
ALTER TABLE `providers`
  MODIFY `provider_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quote`
--
ALTER TABLE `quote`
  MODIFY `quote_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_category`
--
ALTER TABLE `service_category`
  MODIFY `category_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_offering`
--
ALTER TABLE `service_offering`
  MODIFY `offering_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_request`
--
ALTER TABLE `service_request`
  MODIFY `request_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `fk_address_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `fk_booking_quote` FOREIGN KEY (`quote_id`) REFERENCES `quote` (`quote_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_booking_request` FOREIGN KEY (`request_id`) REFERENCES `service_request` (`request_id`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customer_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `dispute`
--
ALTER TABLE `dispute`
  ADD CONSTRAINT `dispute_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `dispute_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON UPDATE CASCADE;

--
-- Constraints for table `payout`
--
ALTER TABLE `payout`
  ADD CONSTRAINT `fk_payout_booking` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_payout_provider` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`) ON DELETE CASCADE;

--
-- Constraints for table `providers`
--
ALTER TABLE `providers`
  ADD CONSTRAINT `fk_provider_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `quote`
--
ALTER TABLE `quote`
  ADD CONSTRAINT `fk_quote_offering` FOREIGN KEY (`offering_id`) REFERENCES `service_offering` (`offering_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_quote_provider` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_quote_request` FOREIGN KEY (`request_id`) REFERENCES `service_request` (`request_id`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_booking` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `service_offering`
--
ALTER TABLE `service_offering`
  ADD CONSTRAINT `fk_offering_category` FOREIGN KEY (`category_id`) REFERENCES `service_category` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_offering_provider` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`) ON DELETE CASCADE;

--
-- Constraints for table `service_request`
--
ALTER TABLE `service_request`
  ADD CONSTRAINT `fk_request_address` FOREIGN KEY (`address_id`) REFERENCES `address` (`address_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_request_category` FOREIGN KEY (`category_id`) REFERENCES `service_category` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_request_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
