-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 03:14 AM
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
-- Database: `realestatephp`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `user_id`, `property_id`, `time`, `status`) VALUES
(8, 58, 33, '2025-04-02 13:30:00', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `action_date` datetime NOT NULL DEFAULT current_timestamp(),
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `user_id`, `action_type`, `action_date`, `description`) VALUES
(26, 57, 'ADMIN_EDIT_PROPERTY', '2025-04-27 09:06:44', 'Admin edited property with ID: 27'),
(27, 57, 'ADMIN_EDIT_PROPERTY', '2025-04-27 09:15:40', 'Admin edited property with ID: 27'),
(28, 56, 'ADD_PROPERTY', '2025-04-27 09:17:30', 'Added property with title: t'),
(29, 57, 'ADMIN_EDIT_PROPERTY', '2025-04-27 09:27:13', 'Admin edited property with ID: 27'),
(30, 57, 'ADMIN_EDIT_PROPERTY', '2025-04-27 09:27:40', 'Admin edited property with ID: 27'),
(31, 56, 'EDIT_PROPERTY', '2025-04-27 09:28:36', 'Edited property with ID: 25'),
(32, 56, 'EDIT_PROPERTY', '2025-04-27 09:38:01', 'Edited property with ID: 25'),
(33, 56, 'EDIT_PROPERTY', '2025-04-27 09:38:43', 'Edited property with ID: 25'),
(34, 57, 'ADMIN_EDIT_PROPERTY', '2025-04-27 09:44:56', 'Admin edited property with ID: 27'),
(35, 57, 'ADMIN_EDIT_PROPERTY', '2025-04-27 09:45:17', 'Admin edited property with ID: 27'),
(36, 57, 'ADMIN_ADD_PROPERTY', '2025-04-27 09:54:54', 'Admin added property with title: test admin for Seller/Agent ID: 56'),
(37, 56, 'EDIT_PROPERTY', '2025-04-27 09:56:57', 'Edited property with ID: 28'),
(38, 56, 'EDIT_PROPERTY', '2025-04-27 10:05:45', 'Edited property with ID: 29'),
(39, 56, 'EDIT_PROPERTY', '2025-04-27 10:06:05', 'Edited property with ID: 27'),
(40, 64, 'ADD_PROPERTY', '2025-04-27 10:31:50', 'Added property with title: seller'),
(41, 64, 'ADD_PROPERTY', '2025-04-27 10:50:19', 'Added property with title: multiple test'),
(42, 65, 'REGISTRATION', '2025-04-27 11:53:15', 'User registered with name: DEMO_NAME'),
(43, 65, 'ADD_PROPERTY', '2025-04-27 12:05:01', 'Added property with title: Royal Villas'),
(44, 56, 'ADD_PROPERTY', '2025-04-27 12:12:03', 'Added property with title: tgr'),
(45, 56, 'ADD_PROPERTY', '2025-04-27 12:23:46', 'Added property with title: eewrw'),
(46, 56, 'DELETE_PROPERTY', '2025-04-27 12:25:38', 'Seller/Agent deleted property with ID: 34'),
(47, 56, 'ADD_PROPERTY', '2025-04-27 12:26:07', 'Added property with title: rrrrrr'),
(48, 56, 'EDIT_PROPERTY', '2025-04-27 20:38:56', 'Edited property with ID: 33'),
(49, 56, 'ADD_PROPERTY', '2025-04-27 21:06:06', 'Added property with title: test'),
(50, 64, 'ADD_PROPERTY', '2025-04-27 21:14:33', 'Added property with title: seller test'),
(51, 57, 'ADMIN_EDIT_PROPERTY', '2025-04-27 23:12:06', 'Admin edited property with ID: 33'),
(52, 56, 'ADD_PROPERTY', '2025-04-28 02:27:28', 'Added property with title: test multiple image'),
(53, 56, 'EDIT_PROPERTY', '2025-04-28 02:37:50', 'Edited property with ID: 38'),
(54, 56, 'EDIT_PROPERTY', '2025-04-28 02:38:20', 'Edited property with ID: 38'),
(55, 57, 'ADMIN_EDIT_PROPERTY', '2025-04-27 19:49:09', 'Admin edited property with ID: 32');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorite`
--

CREATE TABLE `favorite` (
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite`
--

INSERT INTO `favorite` (`user_id`, `property_id`, `date`) VALUES
(65, 32, '2025-04-27'),
(56, 32, '2025-04-27'),
(58, 36, '2025-04-27'),
(56, 38, '2025-04-27');

-- --------------------------------------------------------

--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `offer_id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `offer_price` decimal(12,2) DEFAULT NULL,
  `offer_date` date DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected','Sold') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`offer_id`, `property_id`, `buyer_id`, `offer_price`, `offer_date`, `status`) VALUES
(12, 32, 56, 454534.00, '2025-04-27', 'Sold'),
(13, 35, 58, 789.00, '2025-04-27', 'Sold');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `offer_id` int(11) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `amount_paid` decimal(12,2) DEFAULT NULL,
  `payment_method` enum('Credit Card','Bank Transfer','PayPal','Stripe') DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `status` enum('Completed','Pending') DEFAULT 'Pending',
  `payment_type` enum('Sale','Rental') DEFAULT 'Sale',
  `rental_interest_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `offer_id`, `seller_id`, `amount_paid`, `payment_method`, `payment_date`, `status`, `payment_type`, `rental_interest_id`) VALUES
(9, 12, 65, 454534.00, 'Credit Card', '2025-04-27', 'Completed', 'Sale', NULL),
(10, 13, 56, 789.00, '', '2025-04-27', 'Completed', 'Sale', NULL),
(11, NULL, 56, 23.00, 'Stripe', '2025-04-27', 'Completed', 'Rental', NULL),
(12, NULL, 64, 28.00, 'Stripe', '2025-04-27', 'Completed', 'Rental', 2);

--
-- Triggers `payment`
--
DELIMITER $$
CREATE TRIGGER `update_property_status_on_payment` AFTER UPDATE ON `payment` FOR EACH ROW BEGIN
  IF NEW.status = 'Completed' THEN
    UPDATE property_listings
    SET STATUS = 'sold'
    WHERE PROPERTY_ID = (
      SELECT property_id FROM offer WHERE offer_id = NEW.offer_id
    );
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `property_image`
--

CREATE TABLE `property_image` (
  `image_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_image`
--

INSERT INTO `property_image` (`image_id`, `property_id`, `image_url`) VALUES
(24, 32, 'download.jpeg'),
(25, 38, 'hw3_q3.drawio (1).png'),
(26, 38, 'hw3_q3.drawio.png');

-- --------------------------------------------------------

--
-- Table structure for table `property_listings`
--

CREATE TABLE `property_listings` (
  `property_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `property_type` varchar(100) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `size_sqft` int(11) DEFAULT NULL,
  `nearest_school` varchar(255) DEFAULT NULL,
  `bus_availability` enum('Yes','No') NOT NULL,
  `tram_availability` enum('Yes','No') NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `status` enum('available','sold','hold') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `zip` varchar(100) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pool_available` enum('Yes','No') DEFAULT 'No',
  `is_dog_friendly` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_listings`
--

INSERT INTO `property_listings` (`property_id`, `title`, `description`, `price`, `location`, `property_type`, `bedrooms`, `bathrooms`, `size_sqft`, `nearest_school`, `bus_availability`, `tram_availability`, `seller_id`, `status`, `created_at`, `zip`, `street`, `state`, `pool_available`, `is_dog_friendly`) VALUES
(32, 'Royal Villas', 'Experience modern living in this beautifully designed home featuring spacious interiors and elegant finishes.', 54623.00, 'Houston', 'Rental', 3, 3, 8797, 'H-ISD', 'Yes', 'Yes', 65, 'available', '2025-04-27 10:05:01', '77054', '8181 Fannin ', 'Texas', 'Yes', 'Yes'),
(33, 'tgr', 'sdds', 23.00, 'Houston', 'Rental', 3, 34, 34343, 'wwwwqwew', 'Yes', 'Yes', 56, 'available', '2025-04-27 10:12:03', '77054', 'fds', 'TX', 'Yes', 'Yes'),
(35, 'rrrrrr', '44', 43.00, 'dfd', 'Sale', 4, 4, 4, 'cxxc', 'Yes', 'Yes', 56, 'sold', '2025-04-27 10:26:07', '34', 'sdsd', 'dsd', 'Yes', 'Yes'),
(36, 'test', '32', 33.00, 'Houston', 'Rental', 4, 31, 3, 'w323', 'Yes', 'Yes', 56, 'available', '2025-04-27 19:06:06', '77054', '23', 'TX', 'Yes', 'Yes'),
(37, 'seller test', 'dasd', 28.00, 'Houston', 'Rental', 45, 341, 33, '1232', 'Yes', 'Yes', 64, 'sold', '2025-04-27 19:14:33', '77054', '3232', 'TX', 'Yes', 'Yes'),
(38, 'test multiple image', 'sdfs', 3232.00, 'dss', 'Sale', 2, 2, 1231231, 'school', 'Yes', 'Yes', 56, 'available', '2025-04-28 00:27:28', '23132', 'dfdfs', 'sdas', 'Yes', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `rental_contracts`
--

CREATE TABLE `rental_contracts` (
  `property_id` int(11) NOT NULL,
  `available_date` date DEFAULT NULL,
  `security_deposit` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_contracts`
--

INSERT INTO `rental_contracts` (`property_id`, `available_date`, `security_deposit`) VALUES
(32, '2025-04-10', 8767.00),
(33, '2025-04-19', 344.00),
(36, '2025-05-03', 4543.00),
(37, '2025-04-18', 121.00);

-- --------------------------------------------------------

--
-- Table structure for table `rental_interest`
--

CREATE TABLE `rental_interest` (
  `interest_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `interest_date` datetime DEFAULT current_timestamp(),
  `payment_status` enum('Pending','Paid') DEFAULT 'Pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_interest`
--

INSERT INTO `rental_interest` (`interest_id`, `property_id`, `buyer_id`, `status`, `interest_date`, `payment_status`, `payment_method`, `payment_date`) VALUES
(1, 33, 58, 'Accepted', '2025-04-27 00:00:00', 'Paid', NULL, NULL),
(2, 37, 58, 'Accepted', '2025-04-27 14:22:50', 'Paid', 'Stripe', '2025-04-27'),
(3, 37, 58, 'Rejected', '2025-04-27 00:00:00', 'Pending', NULL, NULL),
(4, 37, 56, 'Rejected', '2025-04-27 00:00:00', 'Pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `status` enum('Open','Resolved') DEFAULT 'Open',
  `resolution` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `user_id`, `comment`, `image`, `timestamp`, `status`, `resolution`) VALUES
(6, 56, 'hi how are you ', '', '2025-04-27 20:02:49', 'Resolved', 'resolving in progress');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_num` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `date_of_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `email`, `phone_num`, `password`, `role`, `date_of_creation`, `reset_token`) VALUES
(54, 'Shashank', 'shashankarjundandu@gmail.com', '7637463482', '$2y$10$70wk4hmTBZvDBFQ0ipaMy.rYenZil5QlPpyAglq7nkXpS4NO92yKq', 'admin', '2025-04-20 18:07:17', NULL),
(55, 'dsa', 'shashankarjun2002@gmail.com', '09515282677', '$2y$10$THBD3FS5/G/.5L3n.lVms.v1YwCzQn3bfRPB9wvKAqznRJv2zTym2', 'Buyer', '2025-04-20 21:28:36', NULL),
(56, 'shanmukha sai Vemulapalli', 'shanmukhavemulapalli@gmail.com', '09515282677', '$2y$10$PJpF70aHKeszyLksM0CaneT0hoNp/.9tC31aHp3ZOs1gZkrQn4y0e', 'Agent', '2025-04-21 00:12:46', NULL),
(57, 'admin', 'ssv1766@gmail.com', '09515282677', '$2y$10$FdhNUQEJMMVWCgFkMkEU1.82SrsA7MR0OgPE1D5J98YC/k07vXs/e', 'Admin', '2025-04-21 00:15:00', NULL),
(58, 'seller', 'vsai176618@gmail.com', '09515282677', '$2y$10$cImO9TD/BWx9whQ5e.5WXOv8zboTfp65PxqJa7RjBAHYfWNf3Cd5C', 'Buyer', '2025-04-21 00:48:00', NULL),
(63, 'pott', 'jaswanthproduturu@gmail.com', '3432432432432', '$2y$10$wsx6RAT.fTlLX091VDs1Yel6bCihyKCMByzcZoFtxQFddpqp5clRu', 'Seller', '2025-04-23 23:42:39', NULL),
(64, 'shanmukha sai Vemulapalli', 'svemula6@CougarNet.UH.EDU', '09515282677', '$2y$10$dB9UN9efP60jtxDktJn.eu2idEtktY6knHWnEuUCpPP1L8WAp/RS2', 'Seller', '2025-04-26 22:15:47', NULL),
(65, 'DEMO_NAME', 'seller.groupone@gmail.com', '09515282677', '$2y$10$U06R4hWgf1EZw/.rY2u3C.zFPtMTGWdvsZfMbf1HDZMa3buhj6Y.W', 'Seller', '2025-04-27 04:53:15', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `fk_chat_user` (`user_id`);

--
-- Indexes for table `favorite`
--
ALTER TABLE `favorite`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `offer_id` (`offer_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `rental_interest_id` (`rental_interest_id`);

--
-- Indexes for table `property_image`
--
ALTER TABLE `property_image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `PROPERTY_ID` (`property_id`);

--
-- Indexes for table `property_listings`
--
ALTER TABLE `property_listings`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `rental_contracts`
--
ALTER TABLE `rental_contracts`
  ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `rental_interest`
--
ALTER TABLE `rental_interest`
  ADD PRIMARY KEY (`interest_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `property_image`
--
ALTER TABLE `property_image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `property_listings`
--
ALTER TABLE `property_listings`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `rental_interest`
--
ALTER TABLE `rental_interest`
  MODIFY `interest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`USER_ID`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `property_listings` (`property_id`);

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `fk_chat_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `favorite`
--
ALTER TABLE `favorite`
  ADD CONSTRAINT `favorite_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorite_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `property_listings` (`property_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `offer_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property_listings` (`property_id`),
  ADD CONSTRAINT `offer_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`offer_id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `user` (`USER_ID`),
  ADD CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`rental_interest_id`) REFERENCES `rental_interest` (`interest_id`);

--
-- Constraints for table `property_image`
--
ALTER TABLE `property_image`
  ADD CONSTRAINT `property_image_ibfk_1` FOREIGN KEY (`PROPERTY_ID`) REFERENCES `property_listings` (`PROPERTY_ID`);

--
-- Constraints for table `property_listings`
--
ALTER TABLE `property_listings`
  ADD CONSTRAINT `property_listings_ibfk_1` FOREIGN KEY (`SELLER_ID`) REFERENCES `user` (`USER_ID`),
  ADD CONSTRAINT `property_listings_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `user` (`USER_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rental_contracts`
--
ALTER TABLE `rental_contracts`
  ADD CONSTRAINT `rental_contracts_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property_listings` (`property_id`);

--
-- Constraints for table `rental_interest`
--
ALTER TABLE `rental_interest`
  ADD CONSTRAINT `rental_interest_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `rental_interest_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `property_listings` (`property_id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`USER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
