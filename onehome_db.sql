-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2026 at 05:27 AM
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
-- Database: `onehome_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `address_id` int(5) NOT NULL,
  `customer_id` varchar(5) NOT NULL,
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
  `admin_id` int(4) DEFAULT NULL,
  `user_id` int(5) NOT NULL,
  `admin_level` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(5) DEFAULT NULL,
  `request_id` int(5) NOT NULL,
  `quote_id` int(5) NOT NULL,
  `agreed_price` decimal(10,2) NOT NULL,
  `scheduled_start` datetime(6) NOT NULL,
  `scheduled_end` datetime(6) NOT NULL,
  `booking_status` datetime(6) NOT NULL,
  `completed_at` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `username` int(30) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payout`
--

CREATE TABLE `payout` (
  `payout_id` int(5) DEFAULT NULL,
  `booking_id` int(5) NOT NULL,
  `provider_id` int(5) NOT NULL,
  `payout_status` varchar(5) NOT NULL,
  `gateway_reference` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE `providers` (
  `provider_id` int(5) DEFAULT NULL,
  `user_id` int(5) NOT NULL,
  `business_name` varchar(30) NOT NULL,
  `provider_status` varchar(25) NOT NULL,
  `verification_status` varchar(25) NOT NULL,
  `created_at` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE `quote` (
  `quote_id` int(5) DEFAULT NULL,
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
  `review_id` int(5) DEFAULT NULL,
  `booking_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `rating` int(5) NOT NULL,
  `comment` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_category`
--

CREATE TABLE `service_category` (
  `category_id` int(5) DEFAULT NULL,
  `category_name` varchar(25) NOT NULL,
  `description` varchar(100) NOT NULL,
  `category_status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_offering`
--

CREATE TABLE `service_offering` (
  `offering_id` int(5) DEFAULT NULL,
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
  `request_id` int(5) DEFAULT NULL,
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
  `user_id` int(5) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(25) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `password` varchar(90) NOT NULL,
  `account_status` varchar(30) NOT NULL,
  `user_role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
