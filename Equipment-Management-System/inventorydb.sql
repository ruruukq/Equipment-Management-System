-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2025 at 12:12 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventorydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `AdName` varchar(100) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `AdName`, `AdminEmail`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'Anthony P. Bautista', 'srcequipmentmanagement@gmail.com', 'admin', '$2y$10$dhqcjBoWEHZmCLY.AfqDTu30JJGcgsboYyUkLk.Nh/MU7WQALQFU2', '2025-03-13 06:24:24');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_settings`
--

CREATE TABLE `invoice_settings` (
  `id` int(11) NOT NULL,
  `last_invoice_number` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_settings`
--

INSERT INTO `invoice_settings` (`id`, `last_invoice_number`) VALUES
(1, 755);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(250) NOT NULL,
  `action_made` varchar(255) NOT NULL,
  `timelog` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action_made`, `timelog`) VALUES
(180, 1, 'Admin Blocked User', '2025-02-26 09:28:40'),
(181, 1, 'Admin Blocked User', '2025-02-26 09:32:33'),
(182, 1, 'Admin Blocked User', '2025-02-26 09:33:40'),
(183, 1, 'You\'ve Change your Password', '2025-02-26 18:42:32'),
(184, 1, 'Admin Blocked User', '2025-02-26 18:50:21'),
(185, 1, 'Admin Unblocked User', '2025-02-26 18:53:08'),
(186, 1, 'Admin Added Brand: ahagdha', '2025-02-26 19:00:02'),
(187, 1, 'Admin Updated BrandPLAV', '2025-02-26 19:07:37'),
(188, 1, 'Admin Updated Brand to PLAVE', '2025-02-26 19:07:56'),
(189, 1, 'Admin updated the brand from PLAV to PLAVE', '2025-02-26 19:10:54'),
(190, 1, 'Admin Added Brand: PLLIS', '2025-02-26 19:11:55'),
(191, 1, 'Admin Added Brand: PLIIV', '2025-02-26 19:12:29'),
(192, 1, 'Admin Updated the Brand from PLIIV to PLIIVS', '2025-02-26 19:12:36'),
(193, 1, 'Admin Added Category: DASH', '2025-02-26 19:15:21'),
(194, 1, 'Admin Deleted Category', '2025-02-26 19:15:44'),
(195, 1, 'Admin Deleted ', '2025-02-26 19:16:39'),
(196, 1, 'Admin Deleted Brand: ', '2025-02-26 19:17:37'),
(197, 1, 'Admin Deleted Brand: PLIIVS', '2025-02-26 19:19:10'),
(198, 1, 'Admin Added Category: DAHS', '2025-02-26 19:21:05'),
(199, 1, 'Admin Updated the Category from  to DASH', '2025-02-26 19:21:16'),
(200, 1, 'Admin Updated the Category from  to DASHES', '2025-02-26 19:22:50'),
(201, 1, 'Admin Updated the Category from  to DASH', '2025-02-26 19:23:25'),
(202, 1, 'Admin Updated the Category from DASH to DASHES', '2025-02-26 19:24:54'),
(203, 1, 'Admin Updated the Category from DASHES to DASH', '2025-02-26 19:25:03'),
(204, 1, 'Admin Deleted Category: ', '2025-02-26 19:27:36'),
(205, 1, 'Admin Added Category: DASH', '2025-02-26 19:27:55'),
(206, 1, 'Admin Deleted Category: ', '2025-02-26 19:27:59'),
(207, 1, 'Admin Added Category: DASH', '2025-02-26 19:29:17'),
(208, 1, 'Admin Updated the Category from DASH to DASHES', '2025-02-26 19:31:29'),
(209, 1, 'Admin Updated the Category from DASHES to DASH', '2025-02-26 19:31:35'),
(210, 1, 'Admin Deleted Category: DASH', '2025-02-26 19:31:39'),
(211, 1, 'Admin Added Product: ASTERUM', '2025-02-26 19:44:37'),
(212, 1, 'Admin Updated the Product: ProductName from RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD to RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOAR, Category from 13 to 13, Brand from 28 to 28', '2025-02-26 19:59:12'),
(213, 1, 'Admin Updated the Product: ProductName from RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOAR to RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD, Category from 13 to 13, Brand from 28 to 28', '2025-02-26 19:59:20'),
(214, 1, 'Admin Updated the Product: Product Name from RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD to RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOAR, Category from 13 to 13, Brand from 28 to 28', '2025-02-26 20:00:04'),
(215, 1, 'Admin Updated the Product: Product Name from RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOAR to RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD, Category from 13 to 13, Brand from 28 to 28', '2025-02-26 20:00:08'),
(216, 1, 'Admin Updated : Product Name from RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD to RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOAR, Category from 13 to 13, Brand from 28 to 28', '2025-02-26 20:01:06'),
(217, 1, 'Admin Updated : Product Name from RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOAR to RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD, Category from 13 to 13, Brand from 28 to 28', '2025-02-26 20:01:09'),
(218, 1, 'Admin Added Product: sdfngh', '2025-02-26 20:11:17'),
(219, 1, 'Admin Deleted Product: ', '2025-02-26 20:14:57'),
(220, 1, 'Admin Added Product: wqrewtryetryt', '2025-02-26 20:15:45'),
(221, 1, 'Admin Deleted Product: ', '2025-02-26 20:15:52'),
(222, 1, 'Admin Added Product: fesgrdhfg', '2025-02-26 20:17:58'),
(223, 1, 'Admin Deleted Product: fesgrdhfg', '2025-02-26 20:18:02'),
(224, 1, 'Admin Deleted Product: ASTERUM', '2025-02-26 20:28:00'),
(225, 1, 'You\'ve Change your Password', '2025-02-26 20:29:01'),
(226, 1, 'Admin Added Product: ASTERUM', '2025-02-26 20:29:42'),
(227, 1, 'You\'ve Change your Password', '2025-02-26 20:31:58'),
(228, 1, 'You\'ve Change your Password', '2025-02-26 20:34:43'),
(229, 1, 'You\'ve Change your Password', '2025-02-26 20:36:06'),
(230, 1, 'You\'ve Change your Password', '2025-02-26 20:36:37'),
(231, 1, 'You\'ve Change your Password', '2025-02-26 20:39:55'),
(232, 1, 'Admin Updated Product\'s Information', '2025-02-26 20:41:24'),
(233, 1, 'You\'ve Change your Password', '2025-02-26 20:41:54'),
(234, 1, 'You\'ve Change your Password', '2025-02-26 20:48:16'),
(235, 1, 'Admin Updated Product\'s Information', '2025-02-26 21:01:44'),
(236, 1, 'Admin Updated Product\'s Information', '2025-02-26 21:02:29'),
(237, 1, 'Admin Updated Product\'s Information', '2025-02-26 21:04:21'),
(238, 1, 'Admin Updated Product\'s Information', '2025-02-26 21:05:20'),
(239, 1, 'Admin Updated ASTERUM Information', '2025-02-26 21:07:15'),
(240, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-02-26 21:13:40'),
(241, 1, 'Admin Updated ASTERUM Information', '2025-02-26 21:14:07'),
(242, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-02-26 21:16:33'),
(243, 1, 'Admin Updated ASTERUM Information', '2025-02-26 21:16:43'),
(244, 1, 'Admin Updated ASTERUMS Information', '2025-02-26 21:28:55'),
(245, 1, 'Admin Updated  Image', '2025-02-26 21:32:46'),
(246, 1, 'Admin Updated  Image', '2025-02-26 21:35:53'),
(247, 1, 'Admin Updated Product Image', '2025-02-26 21:44:47'),
(248, 1, 'Admin Updated  Image', '2025-02-26 21:45:18'),
(249, 1, 'Admin Updated  Image', '2025-02-26 21:48:08'),
(250, 1, 'Admin Updated Image for Product: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-26 21:49:01'),
(251, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD\'s Image', '2025-02-26 21:50:41'),
(252, 1, 'Admin Issued New Product for Kimberly: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-26 22:18:14'),
(253, 1, 'Admin Returned Issued', '2025-02-26 22:20:22'),
(254, 1, 'Admin Returned Issued', '2025-02-26 22:20:25'),
(255, 1, 'Admin Issued New Product for Kimberly: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-26 22:21:18'),
(256, 1, 'Admin Returned Issued', '2025-02-26 22:21:38'),
(257, 1, 'Admin Issued New Product for Kimberly: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-26 22:22:10'),
(258, 1, 'Admin Issued New Product for kylie jenner: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-26 22:22:31'),
(259, 1, 'Admin Returned Issued', '2025-02-26 22:23:02'),
(260, 1, 'Admin Returned Issued', '2025-02-26 22:23:09'),
(261, 1, 'Admin Returned Issued', '2025-02-26 22:23:20'),
(262, 1, 'Admin Returned Issued', '2025-02-26 22:23:25'),
(263, 1, 'Admin Returned Issued', '2025-02-26 22:23:33'),
(264, 1, 'You\'ve Change your Password', '2025-02-27 00:57:37'),
(265, 1, 'You\'ve Logged in from the System', '2025-02-27 01:13:23'),
(266, 0, 'Admin Updated Fine Issued', '2025-02-27 01:22:08'),
(267, 1, 'Admin Issued New Product for test: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:25:40'),
(268, 0, 'Admin Updated Fine Issued', '2025-02-27 01:26:38'),
(269, 1, ' Returned  of ', '2025-02-27 01:26:54'),
(270, 1, ' Returned  of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:28:45'),
(271, 1, ' Returned 1 of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:30:42'),
(272, 1, 'Admin Issued New Product for Kimberly: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:32:58'),
(273, 1, ' Returned 1 of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:33:03'),
(274, 1, ' Returned 1 of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:35:29'),
(275, 1, ' Returned 1 of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:36:39'),
(276, 1, 'Unknown User Returned 1 of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:38:58'),
(277, 1, 'Unknown User Returned 1 of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:39:34'),
(278, 1, 'Admin Issued New Product for Kimberly: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:42:05'),
(279, 1, 'Kimberly Returned 1 of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:42:17'),
(280, 1, 'Kimberly Returned 9 Product of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-02-27 01:43:10'),
(281, 1, 'Admin Charges a Fine to Kimberly for not returning the Product RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD on time.', '2025-02-27 01:49:14'),
(282, 1, 'Admin Charges a ₱30 Fine to kylie jenner for not returning the Product RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD on time', '2025-02-27 01:50:45'),
(283, 1, 'Admin Unblocked User', '2025-02-27 01:51:27'),
(284, 1, 'Admin Registered  ', '2025-02-27 01:53:09'),
(285, 1, 'Admin Registered Giselle ', '2025-02-27 01:54:18'),
(286, 1, 'Admin Blocked Unknown User ', '2025-02-27 01:57:22'),
(287, 1, 'Admin Unblocked Unknown User ', '2025-02-27 01:58:03'),
(288, 1, 'Admin Blocked Anuj kumar', '2025-02-27 02:00:43'),
(289, 1, 'Admin Unblocked Anuj kumar', '2025-02-27 02:00:47'),
(290, 1, 'Admin Updated the Role of Amit', '2025-02-27 02:09:16'),
(291, 1, 'Admin Updated the Role of sdfsd to School Staff', '2025-02-27 02:15:21'),
(292, 1, 'Admin Reset Password of sdfsd', '2025-02-27 02:16:10'),
(293, 1, 'Admin Updated the Role of sdfsd to Instructor', '2025-02-27 02:16:58'),
(294, 1, 'Admin Updated the Role of sdfsd to Student', '2025-02-27 02:19:25'),
(295, 1, 'Admin Updated the Role of sdfsd to Instructor', '2025-02-27 02:21:08'),
(296, 1, 'Admin Updated the Role of sdfsd to School Staff', '2025-02-27 02:21:41'),
(297, 1, 'Admin Updated the Role of sdfsd to Others', '2025-02-27 02:21:46'),
(298, 1, 'Admin Updated the Role of sdfsd to Student', '2025-02-27 02:21:58'),
(299, 1, 'Admin Updated the Role of sdfsd to School Staff', '2025-02-27 02:23:09'),
(300, 1, 'You\'ve Logged in from the System', '2025-02-27 02:28:23'),
(301, 0, 'Someone was trying to access your account', '2025-02-27 02:56:02'),
(302, 0, 'Someone was trying to access your account', '2025-02-27 02:57:35'),
(303, 0, 'Someone was trying to access your account', '2025-02-27 03:01:14'),
(304, 1, 'Someone was trying to access your account', '2025-02-27 03:04:45'),
(305, 1, 'You\'ve Logged in from the System', '2025-02-27 03:05:07'),
(306, 1, 'You\'ve Logged in from the System', '2025-02-27 03:10:01'),
(307, 1, 'Logged out from the System', '2025-02-27 03:10:03'),
(308, 1, 'You\'ve Logged in from the System', '2025-02-27 03:20:05'),
(309, 1, 'You\'ve Logged in from the System', '2025-02-27 03:40:10'),
(310, 1, 'You\'ve Logged in from the System', '2025-03-01 04:51:47'),
(311, 1, 'You\'ve Logged in from the System', '2025-03-01 05:23:18'),
(312, 1, 'You\'ve Logged in from the System', '2025-03-01 05:39:05'),
(313, 1, 'You\'ve Logged in from the System', '2025-03-01 06:07:45'),
(314, 1, 'You\'ve Logged in from the System', '2025-03-01 06:46:19'),
(315, 1, 'You\'ve Logged in from the System', '2025-03-01 06:47:02'),
(316, 1, 'You\'ve Logged in from the System', '2025-03-01 06:58:16'),
(317, 1, 'You\'ve Logged in from the System', '2025-03-01 06:58:50'),
(318, 1, 'You\'ve Logged in from the System', '2025-03-01 06:59:45'),
(319, 1, 'You\'ve Logged in from the System', '2025-03-01 07:00:29'),
(320, 1, 'You\'ve Logged in from the System', '2025-03-01 07:00:48'),
(321, 1, 'You\'ve Logged in from the System', '2025-03-01 07:01:48'),
(322, 1, 'You\'ve Logged in from the System', '2025-03-01 07:03:22'),
(323, 1, 'You\'ve Logged in from the System', '2025-03-01 07:25:16'),
(324, 1, 'You\'ve Logged in from the System', '2025-03-01 07:28:23'),
(325, 1, 'You\'ve Logged in from the System', '2025-03-01 07:28:48'),
(326, 1, 'You\'ve Logged in from the System', '2025-03-01 07:30:23'),
(327, 1, 'You\'ve Logged in from the System', '2025-03-01 07:32:00'),
(328, 1, 'You\'ve Logged in from the System', '2025-03-01 07:32:58'),
(329, 1, 'You\'ve Logged in from the System', '2025-03-01 07:37:24'),
(330, 1, 'You\'ve Logged in from the System', '2025-03-01 07:39:14'),
(331, 1, 'You\'ve Logged in from the System', '2025-03-01 07:41:35'),
(332, 1, 'You\'ve Logged in from the System', '2025-03-01 07:46:07'),
(333, 1, 'You\'ve Logged in from the System', '2025-03-01 07:46:52'),
(334, 1, 'You\'ve Logged in from the System', '2025-03-01 07:47:28'),
(335, 1, 'You\'ve Logged in from the System', '2025-03-01 07:52:20'),
(336, 1, 'You\'ve Logged in from the System', '2025-03-01 07:54:54'),
(337, 1, 'You\'ve Logged in from the System', '2025-03-01 07:56:45'),
(338, 1, 'Someone was trying to access your account', '2025-03-01 07:58:54'),
(339, 1, 'You\'ve Logged in from the System', '2025-03-01 08:00:11'),
(340, 1, 'You\'ve Logged in from the System', '2025-03-01 08:00:53'),
(341, 1, 'You\'ve Logged in from the System', '2025-03-01 08:02:26'),
(342, 1, 'You\'ve Logged in from the System', '2025-03-01 08:03:25'),
(343, 1, 'You\'ve Logged in from the System', '2025-03-01 08:04:28'),
(344, 1, 'You\'ve Logged in from the System', '2025-03-01 08:05:20'),
(345, 1, 'You\'ve Logged in from the System', '2025-03-01 08:10:14'),
(346, 1, 'You Updated your information', '2025-03-01 08:18:45'),
(347, 1, 'You updated the following information: User Name', '2025-03-01 08:20:47'),
(348, 1, 'No changes were made.', '2025-03-01 08:22:23'),
(349, 1, 'You Updated your Name to ADMINN', '2025-03-01 08:26:08'),
(350, 1, 'You Updated your Username to admin', '2025-03-01 08:26:12'),
(351, 1, '', '2025-03-01 08:26:16'),
(352, 1, 'You Updated your Username to admin1', '2025-03-01 08:26:42'),
(353, 1, '', '2025-03-01 08:27:46'),
(354, 1, 'You Updated your Username to admin', '2025-03-01 08:33:30'),
(355, 1, '', '2025-03-01 08:33:51'),
(356, 1, 'You Updated your Email to ', '2025-03-01 08:35:43'),
(357, 1, 'You Updated your Email to admin@gmail.com', '2025-03-01 08:36:16'),
(358, 1, 'You Updated your Email to admin@gmail.com', '2025-03-01 08:36:31'),
(359, 1, 'You Updated your Email to admin@gmail.com', '2025-03-01 08:36:36'),
(360, 1, 'You Updated your Email to admin@gmail.com', '2025-03-01 08:38:18'),
(361, 1, 'You Updated your Email to admin@gmail.com', '2025-03-01 08:38:21'),
(362, 1, 'You Updated your Email to admin1@gmail.com', '2025-03-01 08:38:26'),
(363, 1, 'You Updated your Username to admin1', '2025-03-01 08:39:19'),
(364, 1, 'You Updated your Name to ADMIN', '2025-03-01 08:39:23'),
(365, 1, 'You updated the following information: ', '2025-03-01 08:39:35'),
(366, 1, 'You updated the following information: ', '2025-03-01 08:44:34'),
(367, 1, 'You Updated your Name to ADMINN', '2025-03-01 08:44:38'),
(368, 1, 'You Updated your Username to admin', '2025-03-01 08:44:42'),
(369, 1, 'You Updated your Email to admin1@gmail.com', '2025-03-01 08:44:45'),
(370, 1, 'You Updated your Email to admin@gmail.com', '2025-03-01 08:45:14'),
(371, 1, 'You updated the following information: Your Name to ADMINN', '2025-03-01 09:00:50'),
(372, 1, 'You updated the following information: Your Username to admin', '2025-03-01 09:00:53'),
(373, 1, 'You updated the following information: Your Username to admin1', '2025-03-01 09:00:56'),
(374, 1, 'You updated the following information: Your Email to admin1@gmail.com', '2025-03-01 09:01:00'),
(375, 1, ' Your Name to ADMIN and Your Username to admin and Your Email to admin@gmail.com', '2025-03-01 09:01:51'),
(376, 1, ' Your Name to ADMINN and Your Username to admin1', '2025-03-01 09:02:22'),
(377, 1, ' Your Username to admin11 and Your Email to admin1@gmail.com', '2025-03-01 09:02:39'),
(378, 1, ' Your Name to ADMINN1 and Your Email to admin11@gmail.com', '2025-03-01 09:02:53'),
(379, 1, 'You updatedYour Name to ADMINN and Your Username to admin1', '2025-03-01 09:03:44'),
(380, 1, 'You updated Your Name to ADMINNn and Your Email to admin1@gmail.com', '2025-03-01 09:04:10'),
(381, 1, 'You updated Your Username to admin11 and Your Email to admin11@gmail.com', '2025-03-01 09:04:23'),
(382, 1, 'You Updated Your Name to ADMIN and Your Username to admin and Your Email to admin@gmail.com', '2025-03-01 09:04:49'),
(383, 1, 'You Updated Your Name to ADMIN11, Your Username to admin11 and Your Email to admin11@gmail.com', '2025-03-01 09:06:58'),
(384, 1, 'You Updated Your Name to ADMIN111, Your Username to admin111 and Your Email to admin111@gmail.com', '2025-03-01 09:07:22'),
(385, 1, 'You Updated Your Name to ADMIN11 and Your Username to admin11', '2025-03-01 09:07:26'),
(386, 1, 'You Updated Your Name to ADMIN1 and Your Email to admin11@gmail.com', '2025-03-01 09:07:40'),
(387, 1, 'You Updated Your Username to admin1 and Your Email to admin1@gmail.com', '2025-03-01 09:07:53'),
(388, 1, 'You Updated Your Name to ADMIN, Your Username to admin and Your Email to admin@gmail.com', '2025-03-01 09:08:07'),
(389, 1, 'You\'ve Logged in from the System', '2025-03-01 09:16:39'),
(390, 1, 'You Updated Your Name to ADMIN1', '2025-03-01 09:16:53'),
(391, 1, 'You Updated Your Name to ADMIN', '2025-03-01 09:16:57'),
(392, 1, 'You Updated Your Name to ADMIN1', '2025-03-01 09:18:05'),
(393, 1, 'You Updated Your Name to ADMIN', '2025-03-01 09:18:16'),
(394, 1, 'You\'ve Logged in from the System', '2025-03-01 09:55:16'),
(395, 1, 'You\'ve Logged in from the System', '2025-03-01 11:49:47'),
(396, 1, 'You Updated Your Email to srcequipmentmanagement@gmail.com', '2025-03-01 11:50:27'),
(397, 1, 'Someone was trying to access your account', '2025-03-01 11:55:19'),
(398, 1, 'Someone was trying to access your account', '2025-03-01 11:56:10'),
(399, 1, 'You\'ve Logged in from the System', '2025-03-01 12:00:02'),
(400, 1, 'You\'ve Logged in from the System', '2025-03-01 12:01:42'),
(401, 1, 'Admin Changed Their Password', '2025-03-01 12:02:23'),
(402, 1, 'Admin Changed Their Password', '2025-03-01 12:03:02'),
(403, 1, 'Admin Changed Their Password', '2025-03-01 12:03:14'),
(404, 1, 'You\'ve Logged in from the System', '2025-03-01 12:11:39'),
(405, 1, 'You\'ve Logged in from the System', '2025-03-01 12:16:08'),
(406, 1, 'You\'ve Logged in from the System', '2025-03-02 04:21:32'),
(407, 1, 'Admin Added Category: KPOP', '2025-03-02 04:23:05'),
(408, 1, 'Admin Added Product: ASTERUM', '2025-03-02 04:23:44'),
(409, 1, 'Admin Deleted Category: KPOP', '2025-03-02 04:23:54'),
(410, 1, 'Admin Added Category: KPOP', '2025-03-02 04:24:12'),
(411, 1, 'You Updated Your Name to ADMINN', '2025-03-02 04:35:01'),
(412, 1, 'You Updated Your Name to ADMIN', '2025-03-02 04:35:23'),
(413, 1, 'Admin Added Product: WFY', '2025-03-02 04:39:09'),
(414, 1, 'Admin Deleted Product: WFY', '2025-03-02 04:39:28'),
(415, 1, 'Admin Added Product: WFY', '2025-03-02 04:40:38'),
(416, 1, 'Admin Deleted Category: KPOP', '2025-03-02 04:41:07'),
(417, 1, 'Admin Added Category: KPOP', '2025-03-02 04:43:51'),
(418, 1, 'Admin Added Product: PLAVE', '2025-03-02 04:45:08'),
(419, 1, 'Admin Deleted Brand: PLLIS', '2025-03-02 04:45:17'),
(420, 1, 'Admin Added Product: PLAVEE', '2025-03-02 04:49:08'),
(421, 1, 'Admin Deleted Category: KPOP', '2025-03-02 04:50:01'),
(422, 1, 'Admin Added Category: KPOP', '2025-03-02 04:50:52'),
(423, 1, 'You\'ve Logged in from the System', '2025-03-02 04:57:23'),
(424, 1, 'Admin Added Product: BAMBY', '2025-03-02 05:18:23'),
(425, 1, 'Admin Deleted Product: PLAVEE', '2025-03-02 05:27:15'),
(426, 1, 'Admin Added Product: ASTERUM', '2025-03-02 05:28:05'),
(427, 1, 'Admin Added Product: BAMBY', '2025-03-02 05:33:45'),
(428, 1, 'Admin Added Product: BAMBY', '2025-03-02 05:34:58'),
(429, 1, 'Admin Added Product: BAMBY', '2025-03-02 05:36:54'),
(430, 1, 'Admin Added Product: ASTERUM', '2025-03-02 05:40:37'),
(431, 1, 'Admin Added Product: ASTERUM', '2025-03-02 05:44:02'),
(432, 1, 'Admin Deleted Category: KPOP', '2025-03-02 05:46:24'),
(433, 1, 'Admin Added Category: KPOP', '2025-03-02 05:46:40'),
(434, 1, 'Admin Deleted Category: KPOP', '2025-03-02 05:46:55'),
(435, 1, 'Admin Added Category: KPOP', '2025-03-02 05:47:09'),
(436, 1, 'Admin Updated the Category from KPOP to KPOPP11', '2025-03-02 05:47:39'),
(437, 1, 'Admin Updated the Category from KPOPP11 to KPOP', '2025-03-02 05:48:03'),
(438, 1, 'Admin Updated the Category from KPOP to KPOPPP', '2025-03-02 06:00:21'),
(439, 1, 'Admin Updated the Category from Instruments to Instrument', '2025-03-02 06:15:26'),
(440, 1, 'Admin Updated the Category from Instrument to Instruments', '2025-03-02 06:15:38'),
(441, 1, 'Admin Updated the Category from KPOPPPP to KPOP', '2025-03-02 06:15:44'),
(442, 1, 'Admin Updated the Category from KPOP to KPOPPPP', '2025-03-02 06:22:57'),
(443, 1, 'Admin Updated the Category from KPOP1 to KPOP', '2025-03-02 06:38:50'),
(444, 1, 'Admin Updated the Category from KPOPppp111 to KPOP', '2025-03-02 06:43:54'),
(445, 1, 'Admin Updated the Category from KPOP to KPOPPPP', '2025-03-02 06:48:20'),
(446, 1, 'Admin Added Category: KPOP', '2025-03-02 06:53:06'),
(447, 1, 'Admin Added Product: ASTERUM', '2025-03-02 06:53:29'),
(448, 1, 'Admin Updated the Category from KPOP to KPOP1', '2025-03-02 06:53:39'),
(449, 1, 'Admin Added Category: KPOP1', '2025-03-02 06:54:00'),
(450, 1, 'Admin Deleted/Marked Inactive Category: 37', '2025-03-02 07:09:13'),
(451, 1, 'Admin Added Category: KPOP', '2025-03-02 07:09:46'),
(452, 1, 'Admin Added Product: ASTERUM', '2025-03-02 07:10:17'),
(453, 1, 'Admin Deleted/Marked Inactive Category: 38', '2025-03-02 07:10:24'),
(454, 1, 'Admin Deleted/Marked Inactive Category: 38', '2025-03-02 07:10:38'),
(455, 1, 'Admin Deleted Category: KPOP', '2025-03-02 07:11:16'),
(456, 1, 'Admin Updated the Category from Computer to Computer1', '2025-03-02 07:12:28'),
(457, 1, 'Admin Updated the Category from Computer1 to Computer', '2025-03-02 07:12:45'),
(458, 1, 'Admin Updated the Category from Computer to Computer1', '2025-03-02 07:13:48'),
(459, 1, 'Admin Updated the Category from Computer1 to Computer', '2025-03-02 07:20:10'),
(460, 1, 'Admin Added Category: KPOP', '2025-03-02 07:20:21'),
(461, 1, 'Admin Deleted Category: KPOP', '2025-03-02 07:20:32'),
(462, 1, 'Admin Added Category: KPOP', '2025-03-02 07:20:41'),
(463, 1, 'Admin Updated the Category from KPOP to KPOPPP', '2025-03-02 07:20:52'),
(464, 1, 'Admin Added Category: KPOP', '2025-03-02 07:21:37'),
(465, 1, 'Admin Deleted Category: KPOPPP', '2025-03-02 07:21:44'),
(466, 1, 'Admin Updated the Category from KPOP to KPOPp', '2025-03-02 07:22:36'),
(467, 1, 'Admin Updated the Category from KPOPp to KPOP', '2025-03-02 07:22:45'),
(468, 1, 'Admin Added Product: PLIIII', '2025-03-02 07:23:44'),
(469, 1, 'Admin Added Product: PLIIII', '2025-03-02 07:25:02'),
(470, 1, 'Admin Added Product: WFY', '2025-03-02 07:26:37'),
(471, 1, 'Admin Added Product: WFY', '2025-03-02 07:28:05'),
(472, 1, 'Admin Added Product: WFY', '2025-03-02 07:30:40'),
(473, 1, 'Admin Deleted Product: WFY', '2025-03-02 07:32:05'),
(474, 1, 'Admin Added Product: WFY', '2025-03-02 07:32:28'),
(475, 1, 'Admin Added Product: WFY', '2025-03-02 07:33:35'),
(476, 1, 'Admin Added Product: WFY', '2025-03-02 07:36:48'),
(477, 1, 'Admin Added Product: PLIIII', '2025-03-02 07:37:50'),
(478, 1, 'Admin Added Product: WFY', '2025-03-02 07:41:27'),
(479, 1, 'Admin Deleted Category: KPOP', '2025-03-02 07:41:45'),
(480, 1, 'Admin Added Category: KPOP', '2025-03-02 07:42:46'),
(481, 1, 'Admin Issued New Product for jack frost: ASTERUM', '2025-03-02 07:46:07'),
(482, 1, 'Admin Deleted Product: WFY', '2025-03-02 07:47:41'),
(483, 1, 'jack frost Has Returned 5 Products of ASTERUM', '2025-03-02 07:48:42'),
(484, 1, 'Admin Added Product: WFY', '2025-03-02 07:49:16'),
(485, 1, 'Admin Issued New Product for jack frost: WFY', '2025-03-02 07:49:34'),
(486, 1, 'Admin Deleted Product: WFY', '2025-03-02 07:49:40'),
(487, 1, 'Admin Added Product: WFY', '2025-03-02 07:50:08'),
(488, 1, 'Admin Updated the Brand from PLAVE to PLAVEE', '2025-03-02 07:52:30'),
(489, 1, 'Admin Updated the Brand from PLAVEE to PLAVE', '2025-03-02 07:52:43'),
(490, 1, 'Admin Deleted Brand: PLAVE', '2025-03-02 07:52:50'),
(491, 1, 'Admin Added Brand: PLAVE', '2025-03-02 07:52:59'),
(492, 1, 'Admin Updated the Category from KPOP to KPOPPP', '2025-03-02 08:20:39'),
(493, 1, 'Admin Deleted Category: KPOPPP', '2025-03-02 08:20:49'),
(494, 1, 'Admin Added Category: KPOPPP', '2025-03-02 08:21:00'),
(495, 1, 'Admin Added Category: PLAVE', '2025-03-02 08:26:06'),
(496, 1, 'Admin Deleted Category: PLAVE', '2025-03-02 08:26:09'),
(497, 1, 'Admin Updated the Brand from PLAVE to PLAVEEE', '2025-03-02 08:26:46'),
(498, 1, 'Admin Updated the Brand from PLAVEEE to PLAVE', '2025-03-02 08:26:56'),
(499, 1, 'Admin Updated the Brand from PLAVE to PLAVEEEE', '2025-03-02 08:29:27'),
(500, 1, 'Admin Updated the Brand from PLAVEEEE to PLAVE', '2025-03-02 08:29:48'),
(501, 1, 'Admin Updated the Brand from PLAVE to PLAVEEEE', '2025-03-02 08:30:25'),
(502, 1, 'Admin Updated the Brand from PLAVEEEE to PLAVE', '2025-03-02 08:30:34'),
(503, 1, 'Admin Updated the Category from KPOPPP to KPOP', '2025-03-02 08:30:43'),
(504, 1, 'Admin Updated ASTERUM Information', '2025-03-02 08:40:35'),
(505, 1, 'Admin Updated WFY Information', '2025-03-02 08:49:29'),
(506, 1, 'Admin Updated WFY Information', '2025-03-02 08:50:39'),
(507, 1, 'You Updated Your Username to adminn', '2025-03-02 09:03:38'),
(508, 1, 'You Updated Your Username to admin', '2025-03-02 09:03:51'),
(509, 1, 'You changed Password', '2025-03-02 09:04:05'),
(510, 1, 'You changed Password', '2025-03-02 09:04:22'),
(511, 1, 'You\'ve Logged in from the System', '2025-03-02 09:04:58'),
(512, 1, 'Logged out from the System', '2025-03-02 09:07:11'),
(513, 1, 'Logged in from the System', '2025-03-02 09:07:24'),
(514, 1, 'Admin Blocked Anuj kumar', '2025-03-02 09:08:04'),
(515, 1, 'Admin Unblocked Anuj kumar', '2025-03-02 09:08:14'),
(516, 1, 'Admin Reset Password of Anuj kumar', '2025-03-02 09:08:30'),
(517, 1, 'Admin Updated the Role of sdfsd to Others', '2025-03-02 09:08:44'),
(518, 1, 'Admin Updated the Role of sdfsd to School Staff', '2025-03-02 09:08:54'),
(519, 1, 'Admin Registered TANTAN ', '2025-03-02 09:10:49'),
(520, 1, 'Admin Registered Eunho ', '2025-03-02 09:21:29'),
(521, 1, 'Admin Registered Noah as  ', '2025-03-02 09:23:06'),
(522, 1, 'Admin Registered Hamin as  ', '2025-03-02 09:23:50'),
(523, 1, 'Admin Registered Yejun as Instructor ', '2025-03-02 09:24:52'),
(524, 1, 'Admin Updated the Role of jack frost to Student', '2025-03-02 09:26:11'),
(525, 1, 'Logged out from the System', '2025-03-02 09:27:48'),
(526, 1, 'Logged in from the System', '2025-03-02 09:28:05'),
(527, 1, 'Logged out from the System', '2025-03-02 09:28:36'),
(528, 1, 'Logged in from the System', '2025-03-02 09:37:55'),
(529, 1, 'Admin Updated the Category from KPOP to KPOPPP', '2025-03-02 09:38:05'),
(530, 1, 'Admin Updated the Category from KPOPPP to KPOP', '2025-03-02 09:38:55'),
(531, 1, 'Admin Deleted Category: KPOP', '2025-03-02 09:39:06'),
(532, 1, 'Admin Deleted Brand: PLAVE', '2025-03-02 09:40:40'),
(533, 1, 'Admin Added Brand: PLAVE', '2025-03-02 09:41:01'),
(534, 1, 'Admin Added Category: KPOP', '2025-03-02 09:41:11'),
(535, 1, 'Admin Deleted Category: KPOP', '2025-03-02 09:44:28'),
(536, 1, 'Admin Deleted Brand: PLAVE', '2025-03-02 09:44:38'),
(537, 1, 'Admin Added Category: KPOP', '2025-03-02 09:44:48'),
(538, 1, 'Admin Added Brand: PLAVE', '2025-03-02 09:44:53'),
(539, 1, 'Admin Deleted Category: KPOP', '2025-03-02 09:54:12'),
(540, 1, 'Admin Added Category: KPOP', '2025-03-02 09:54:45'),
(541, 1, 'Logged out from the System', '2025-03-02 09:55:37'),
(542, 1, 'Logged in from the System', '2025-03-02 16:14:57'),
(543, 1, 'Admin Issued New Product for Kimberly Quinez: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-03-02 16:15:54'),
(544, 1, 'Kimberly Quinez Has Returned 3 Products of ', '2025-03-02 17:50:12'),
(545, 1, 'Admin added 8 quantity to the issued product for User ID: SID014', '2025-03-02 18:12:38'),
(546, 1, 'Admin Issued New Product for Kimberly Quinez: RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-03-02 18:15:35'),
(547, 1, 'Admin added 3 quantity to the issued product for User ID: SID014', '2025-03-02 18:15:49'),
(548, 1, 'Kimberly Quinez Has Returned 3 Products of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-03-02 18:21:23'),
(549, 1, 'Logged out from the System', '2025-03-02 18:22:07'),
(550, 1, 'Logged in from the System', '2025-03-02 18:32:02'),
(551, 1, 'Admin Deleted Category: KPOP', '2025-03-02 18:32:13'),
(552, 1, 'Admin Added Category: KPOP', '2025-03-02 18:43:02'),
(553, 1, 'Logged out from the System', '2025-03-02 18:43:40'),
(554, 1, 'Logged in from the System', '2025-03-02 18:44:47'),
(555, 1, 'Logged out from the System', '2025-03-02 18:45:07'),
(556, 1, 'Logged in from the System', '2025-03-03 05:30:20'),
(557, 1, 'Admin Updated DELTA FORCE GWM306 RECHARGEABLE WIRELESS RGB MOUSE 1600DPI Information', '2025-03-03 05:33:04'),
(558, 1, 'Admin Updated DELTA FORCE GWM306 RECHARGEABLE WIRELESS RGB MOUSE 1600DPI Information', '2025-03-03 05:49:27'),
(559, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-03-03 05:51:17'),
(560, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-03-03 05:56:51'),
(561, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-03-03 05:57:15'),
(562, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-03-03 05:57:25'),
(563, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-03-03 05:58:00'),
(564, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-03-03 05:58:09'),
(565, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-03-03 05:59:12'),
(566, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-03-03 05:59:24'),
(567, 1, 'Admin Updated RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD Information', '2025-03-03 06:00:34'),
(568, 1, 'Kimberly Quinez Has Returned 1 Products of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-03-03 06:04:54'),
(569, 1, 'Kimberly Quinez Has Returned 1 Products of RAZER BLACK WIDOW V3 MINI GREEN SWITCH KEYBOARD', '2025-03-03 06:18:24'),
(570, 1, 'Logged out from the System', '2025-03-03 06:18:54'),
(571, 1, 'Logged in from the System', '2025-03-03 06:19:05'),
(572, 1, 'Logged out from the System', '2025-03-03 06:23:35'),
(573, 1, 'Logged in from the System', '2025-03-03 06:24:51'),
(574, 1, 'You Updated Your Name to Property Custodian', '2025-03-03 06:26:54'),
(575, 1, 'Admin Deleted Product: WFY', '2025-03-03 08:20:47'),
(576, 1, 'You Updated Your Name to Juan Dela Cruz', '2025-03-03 10:15:50'),
(577, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-03 10:47:07'),
(578, 1, 'Admin Added Brand: Razer', '2025-03-03 10:47:30'),
(579, 1, 'Admin Updated Black Widow V3 Mini Green Switch Keyboard Information', '2025-03-03 10:48:07'),
(580, 1, 'Admin Updated A2 Plus Wireless Bluetooth Desktop Speakers Information', '2025-03-03 10:48:56'),
(581, 1, 'Admin Updated C40 Classic Guitar Information', '2025-03-03 10:49:11'),
(582, 1, 'Admin Deleted Product: ASTERUM', '2025-03-03 10:49:25'),
(583, 1, 'Admin Deleted Category: KPOP', '2025-03-03 10:49:30'),
(584, 1, 'Admin Deleted Category: Books', '2025-03-03 10:49:33'),
(585, 1, 'Admin Deleted Brand: AHHAHAHHA', '2025-03-03 10:49:45'),
(586, 1, 'Admin Deleted Brand: PLAVE', '2025-03-03 10:49:47'),
(587, 1, 'Logged out from the System', '2025-03-03 11:28:17'),
(588, 1, 'Logged in from the System', '2025-03-03 11:28:25'),
(589, 1, 'Logged out from the System', '2025-03-03 11:28:28'),
(590, 1, 'Logged in from the System', '2025-03-03 11:49:31'),
(591, 1, 'Logged out from the System', '2025-03-03 11:49:36'),
(592, 1, 'Logged in from the System', '2025-03-03 12:23:49'),
(593, 1, 'Admin Issued New Product for kylie jenner: A2 Plus Wireless Bluetooth Desktop Speakers', '2025-03-03 12:29:56'),
(594, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 16:45:47'),
(595, 1, 'Kimberly Quinez Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 16:46:57'),
(596, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 16:47:08'),
(597, 1, 'Admin Issued New Product for jack frost: C40 Classic Guitar', '2025-03-03 16:48:01'),
(598, 1, 'jack frost Has Returned 1 Products of C40 Classic Guitar', '2025-03-03 16:50:35'),
(599, 1, 'Admin Issued New Product for jack frost: Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 16:53:50'),
(600, 1, 'jack frost Has Returned 3 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 16:56:32'),
(601, 1, 'Admin Issued New Product for jack frost: Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 16:57:10'),
(602, 1, 'jack frost Has Returned 6 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 17:00:05'),
(603, 1, 'Admin Issued New Product for jack frost: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:00:27'),
(604, 1, 'Admin Issued New Product for Kimberly Quinez: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:02:30'),
(605, 1, 'Kimberly Quinez Has Returned 6 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:04:46'),
(606, 1, 'jack frost Has Returned 5 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:04:52'),
(607, 1, 'Admin Issued New Product for Kimberly Quinez: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:07:05'),
(608, 1, 'Kimberly Quinez Has Returned 3 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:09:56'),
(609, 1, 'Kimberly Quinez Has Returned 1 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:10:54'),
(610, 1, 'Admin Charges a ₱ Fine to Kimberly Quinez for not returning the Product GWM306 Rechargeable Wireless RGB Mouse 1600DPI  on time', '2025-03-03 17:11:37'),
(611, 1, 'Admin Issued New Product for Kimberly Quinez: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:12:01'),
(612, 1, 'Kimberly Quinez Has Returned 2 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:14:51'),
(613, 1, 'Kimberly Quinez Has Returned 1 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:18:19'),
(614, 1, 'Kimberly Quinez Has Returned 1 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:20:54'),
(615, 1, 'Kimberly Quinez Has Returned 1 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-03 17:22:02'),
(616, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 17:23:37'),
(617, 1, 'Kimberly Quinez Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 17:28:44'),
(618, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 17:34:07'),
(619, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 17:37:29'),
(620, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 17:41:35'),
(621, 1, 'Admin Issued New Product for jack frost: Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 17:42:08'),
(622, 1, 'jack frost Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 17:45:05'),
(623, 1, 'jack frost Has Returned 3 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 17:52:33'),
(624, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-03 18:04:31'),
(625, 1, 'Admin Updated the Role of Ariana to Student', '2025-03-03 18:16:59'),
(626, 1, 'Logged in from the System', '2025-03-04 09:38:23'),
(627, 1, 'Property Custodian Printed the Product', '2025-03-04 09:51:22'),
(628, 1, 'Admin Issued New Product for Kimberly Quinez: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-04 09:57:51'),
(629, 1, 'Kimberly Quinez Has Returned 2 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-04 09:58:59'),
(630, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-04 10:01:47'),
(631, 1, 'Kimberly Quinez Has Returned 1 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-04 10:06:20'),
(632, 1, 'Logged out from the System', '2025-03-04 10:07:21'),
(633, 1, 'Logged in from the System', '2025-03-04 18:38:13'),
(634, 1, 'Kimberly Quinez Has Returned 1 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-04 18:40:17'),
(635, 1, 'You Updated Your Name to Juan Dela Cru', '2025-03-04 18:53:44'),
(636, 1, 'You Updated Your Name to Juan Dela Cruz', '2025-03-04 18:53:49'),
(637, 1, 'Logged out from the System', '2025-03-04 18:58:52'),
(638, 1, 'Logged in from the System', '2025-03-04 19:39:59'),
(639, 1, 'Admin Issued New Product for Kimberly Quinez: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-04 19:42:27'),
(640, 1, 'Kimberly Quinez Has Returned 3 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-04 19:43:38'),
(641, 1, 'Admin Registered John Kelvin as School Staff ', '2025-03-04 19:45:39'),
(642, 1, 'Admin Added Category: AHHAHAA', '2025-03-04 19:52:58'),
(643, 1, 'Admin Blocked Ariana', '2025-03-04 19:59:01'),
(644, 1, 'Admin Unblocked Ariana', '2025-03-04 19:59:19'),
(645, 1, 'Admin Reset Password of Kimberly Quinez', '2025-03-04 20:07:19'),
(646, 1, 'Logged out from the System', '2025-03-04 20:17:40'),
(647, 1, 'Logged in from the System', '2025-03-04 20:32:00'),
(648, 1, 'Logged in from the System', '2025-03-05 04:50:42'),
(649, 1, 'Admin Issued New Product for Kimberly Quinez: A2 Plus Wireless Bluetooth Desktop Speakers', '2025-03-05 04:58:03'),
(650, 1, 'Admin Reset Password of Kimberly Quinez', '2025-03-05 05:03:43'),
(651, 1, 'You Updated Your Name to Property Custodian', '2025-03-05 05:20:36'),
(652, 1, 'You Updated Your Name to William Smith', '2025-03-05 05:20:45'),
(653, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 07:15:38'),
(654, 1, 'Kimberly Quinez Has Returned 3 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 07:18:57'),
(655, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 07:19:30'),
(656, 1, 'Kimberly Quinez Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 08:34:25'),
(657, 1, 'Kimberly Quinez Has Returned 1 Products of A2 Plus Wireless Bluetooth Desktop Speakers', '2025-03-05 08:34:38'),
(658, 1, 'Kimberly Quinez Has Returned 2 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-05 08:34:44'),
(659, 1, 'Kimberly Quinez Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 08:34:53'),
(660, 1, 'Admin Updated Black Widow V3 Mini Green Switch Keyboard Information', '2025-03-05 08:35:16'),
(661, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 08:36:44'),
(662, 1, 'Kimberly Quinez Has Returned 3 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 08:37:36'),
(663, 1, 'Kimberly Quinez Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 08:44:03'),
(664, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 08:44:21'),
(665, 1, 'Admin Issued New Product for jack frost: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:03:29'),
(666, 1, 'jack frost Has Returned 3 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:03:46'),
(667, 1, 'jack frost Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:09:31'),
(668, 1, 'jack frost Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:09:53'),
(669, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:10:41'),
(670, 1, 'Kimberly Quinez Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:17:29'),
(671, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:24:19'),
(672, 1, 'Kimberly Quinez Has Returned 3 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:24:27'),
(673, 1, 'Admin Issued New Product for kylie jenner: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:30:17'),
(674, 1, 'kylie jenner Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:30:24'),
(675, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:40:47'),
(676, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:41:01'),
(677, 1, 'Kimberly Quinez Has Returned 7 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 09:41:09'),
(678, 1, 'kylie jenner Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:02:43'),
(679, 1, 'kylie jenner Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:05:35'),
(680, 1, 'kylie jenner Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:08:20'),
(681, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:09:58'),
(682, 1, 'kylie jenner Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:13:11'),
(683, 1, 'jack frost Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:13:21'),
(684, 1, 'Kimberly Quinez Has Returned 2 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:18:35'),
(685, 1, 'Admin Issued New Product for kylie jenner: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:27:32'),
(686, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:29:26'),
(687, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:31:16'),
(688, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:34:28'),
(689, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:42:02'),
(690, 1, 'Admin Issued New Product for Kimberly Quinez: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-05 10:42:31'),
(691, 1, 'Kimberly Quinez Has Returned 1 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-05 10:42:38'),
(692, 1, 'Admin Charges a ₱ Fine to Kimberly Quinez for not returning the Product GWM306 Rechargeable Wireless RGB Mouse 1600DPI  on time', '2025-03-05 10:46:57'),
(693, 1, 'Admin Charges a ₱ Fine to Kimberly Quinez for not returning the Product GWM306 Rechargeable Wireless RGB Mouse 1600DPI  on time', '2025-03-05 10:47:02'),
(694, 1, 'Admin Charges a ₱ Fine to Kimberly Quinez for not returning the Product GWM306 Rechargeable Wireless RGB Mouse 1600DPI  on time', '2025-03-05 10:47:43'),
(695, 1, 'Admin Charges a ₱ Fine to Kimberly Quinez for not returning the Product GWM306 Rechargeable Wireless RGB Mouse 1600DPI  on time', '2025-03-05 10:47:58'),
(696, 1, 'Logged out from the System', '2025-03-05 10:48:34'),
(697, 1, 'Logged in from the System', '2025-03-05 10:48:39'),
(698, 1, 'Admin Charges a ₱ Fine to Kimberly Quinez for not returning the Product GWM306 Rechargeable Wireless RGB Mouse 1600DPI  on time', '2025-03-05 10:48:46'),
(699, 1, 'Admin Charges a ₱ Fine to Kimberly Quinez for not returning the Product GWM306 Rechargeable Wireless RGB Mouse 1600DPI  on time', '2025-03-05 10:49:45'),
(700, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:51:19'),
(701, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 10:51:25'),
(702, 1, 'Admin Deleted Category: AHHAHAA', '2025-03-05 10:56:12'),
(703, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 11:01:29'),
(704, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-05 11:01:56'),
(705, 1, 'You Updated Your Name to William Smit', '2025-03-05 11:15:23'),
(706, 1, 'You Updated Your Name to William Smith', '2025-03-05 11:15:30'),
(707, 1, 'Logged out from the System', '2025-03-05 11:57:21'),
(708, 1, 'Logged in from the System', '2025-03-06 14:09:36'),
(709, 1, 'Logged out from the System', '2025-03-06 14:10:13'),
(710, 1, 'Logged in from the System', '2025-03-06 15:08:09'),
(711, 1, 'Logged out from the System', '2025-03-06 15:09:40'),
(712, 1, 'Logged in from the System', '2025-03-06 15:50:20'),
(713, 1, 'Admin Blocked Ariana', '2025-03-06 15:56:42'),
(714, 1, 'Admin Unblocked Ariana', '2025-03-06 16:00:24'),
(715, 1, 'Logged out from the System', '2025-03-06 16:54:27'),
(716, 1, 'Logged in from the System', '2025-03-07 00:01:07'),
(717, 1, 'Admin Deleted Category: Instruments', '2025-03-07 00:01:45'),
(718, 1, 'Admin Added Category: Instruments', '2025-03-07 00:02:04'),
(719, 1, 'Logged out from the System', '2025-03-07 00:02:35'),
(720, 1, 'Logged in from the System', '2025-03-07 00:24:21'),
(721, 1, 'Admin Updated the Role of KIm to Student', '2025-03-07 00:27:10'),
(722, 1, 'Logged out from the System', '2025-03-07 00:27:34'),
(723, 1, 'Logged in from the System', '2025-03-07 08:23:16'),
(724, 1, 'Logged out from the System', '2025-03-07 08:27:11'),
(725, 1, 'Logged in from the System', '2025-03-07 08:27:17'),
(726, 1, 'Logged out from the System', '2025-03-07 08:34:11'),
(727, 1, 'Logged in from the System', '2025-03-07 08:35:57'),
(728, 1, 'Logged out from the System', '2025-03-07 08:36:11'),
(729, 1, 'Logged in from the System', '2025-03-07 08:36:19'),
(730, 1, 'You Updated Your Name to Property Custodian', '2025-03-07 08:37:26'),
(731, 1, 'Admin Added Category: asdf', '2025-03-07 08:55:12'),
(732, 1, 'Admin Deleted Category: asdf', '2025-03-07 08:55:14'),
(733, 1, 'Admin Added Category: asdfgfb', '2025-03-07 08:58:36'),
(734, 1, 'Admin Added Category: asdf', '2025-03-07 08:58:41'),
(735, 1, 'Admin Deleted Category: asdf', '2025-03-07 08:58:47'),
(736, 1, 'Admin Deleted Category: asdfgfb', '2025-03-07 08:58:50'),
(737, 1, 'Logged out from the System', '2025-03-07 09:01:08'),
(738, 1, 'Logged in from the System', '2025-03-07 09:03:02'),
(739, 1, 'Admin Added Category: asdf', '2025-03-07 09:03:15'),
(740, 1, 'Logged out from the System', '2025-03-07 09:04:07'),
(741, 1, 'Logged in from the System', '2025-03-07 09:08:25'),
(742, 1, 'Admin Added Category: sadfv', '2025-03-07 09:08:30'),
(743, 1, 'Admin Added Category: sczdvxfcgbn', '2025-03-07 09:11:05'),
(744, 1, 'Admin Deleted Category: sczdvxfcgbn', '2025-03-07 09:11:09'),
(745, 1, 'Admin Deleted Category: sadfv', '2025-03-07 09:11:12'),
(746, 1, 'Admin Deleted Category: asdf', '2025-03-07 09:11:15'),
(747, 1, 'Admin Added Category: szdvfdg', '2025-03-07 09:13:28'),
(748, 1, 'Admin Deleted Category: szdvfdg', '2025-03-07 09:21:27'),
(749, 1, 'Admin Added Category: adsfdb', '2025-03-07 09:21:32'),
(750, 1, 'Admin Added Category: szxvcbv', '2025-03-07 09:23:14'),
(751, 1, 'Admin Deleted Category: szxvcbv', '2025-03-07 09:23:16'),
(752, 1, 'Admin Deleted Category: adsfdb', '2025-03-07 09:23:19'),
(753, 1, 'Admin Added Category: zvcvb', '2025-03-07 09:24:06'),
(754, 1, 'Admin Deleted Category: zvcvb', '2025-03-07 09:24:09'),
(755, 1, 'Admin Added Category: sadsfdgfn', '2025-03-07 09:28:07'),
(756, 1, 'Admin Deleted Category: sadsfdgfn', '2025-03-07 09:28:09'),
(757, 1, 'Admin Added Category: asadsfdbv', '2025-03-07 09:29:29'),
(758, 1, 'Admin Deleted Category: asadsfdbv', '2025-03-07 09:29:42'),
(759, 1, 'Admin Added Category: asdxvcb', '2025-03-07 09:30:25'),
(760, 1, 'Admin Added Category: adsfdb', '2025-03-07 09:31:55'),
(761, 1, 'Admin Deleted Category: adsfdb', '2025-03-07 09:31:58'),
(762, 1, 'Admin Deleted Category: asdxvcb', '2025-03-07 09:32:00'),
(763, 1, 'Admin Added Category: xcv', '2025-03-07 09:33:30'),
(764, 1, 'Admin Deleted Category: xcv', '2025-03-07 09:33:35'),
(765, 1, 'Admin Added Category: adsfv', '2025-03-07 09:35:50'),
(766, 1, 'Admin Deleted Category: adsfv', '2025-03-07 09:36:01'),
(767, 1, 'Admin Added Category: sadsfb', '2025-03-07 09:36:10'),
(768, 1, 'Admin Deleted Category: sadsfb', '2025-03-07 09:36:13'),
(769, 1, 'Admin Updated the Category from Computer to Compute', '2025-03-07 09:37:31'),
(770, 1, 'Admin Updated the Category from Compute to Computer', '2025-03-07 09:37:40'),
(771, 1, 'Admin Added Brand: asdfg', '2025-03-07 09:38:39'),
(772, 1, 'Admin Updated the Brand from asdfg to asdf', '2025-03-07 09:39:25'),
(773, 1, 'Admin Deleted Brand: asdf', '2025-03-07 09:39:29'),
(774, 1, 'Admin Added Product: wrwetrt', '2025-03-07 09:41:51'),
(775, 1, 'Admin Updated HAHAADJHFKJ Information', '2025-03-07 09:43:06'),
(776, 1, 'Admin Updated HAHAADJHFKJ\'s Image', '2025-03-07 09:43:28'),
(777, 1, 'Admin Deleted Product: HAHAADJHFKJ', '2025-03-07 09:43:35'),
(778, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-07 09:47:56'),
(779, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-07 09:48:37'),
(780, 1, 'Kimberly Quinez Has Returned 7 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-07 09:50:51'),
(781, 1, 'Kimberly Quinez Has Returned 4 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-07 09:51:02'),
(782, 1, 'Admin Registered AHKDIG as Others ', '2025-03-07 09:54:13'),
(783, 1, 'Admin Reset Password of AHKDIG', '2025-03-07 09:54:32'),
(784, 1, 'Admin Updated the Role of AHKDIG to Student', '2025-03-07 09:54:40'),
(785, 1, 'Admin Blocked AHKDIG', '2025-03-07 09:55:39'),
(786, 1, 'Admin Unblocked AHKDIG', '2025-03-07 09:55:54'),
(787, 1, 'You Updated Your Username to adminn', '2025-03-07 09:56:16'),
(788, 1, 'You Updated Your Name to Property Custodia', '2025-03-07 09:58:05'),
(789, 1, 'You Updated Your Name to Property Custodian', '2025-03-07 09:58:13'),
(790, 1, 'You Updated Your Username to admin', '2025-03-07 09:58:39'),
(791, 1, 'You Updated Your Name to Property Custodia', '2025-03-07 09:59:59'),
(792, 1, 'You Updated Your Name to Property Custodian', '2025-03-07 10:00:02'),
(793, 1, 'You Updated Your Name to Property Custodi', '2025-03-07 10:00:46'),
(794, 1, 'You Updated Your Name to Property Custodian', '2025-03-07 10:01:07'),
(795, 1, 'You Updated Your Name to Property Custodia', '2025-03-07 10:01:27'),
(796, 1, 'You Updated Your Name to Property Custodian', '2025-03-07 10:01:31'),
(797, 1, 'You changed Password', '2025-03-07 10:03:02'),
(798, 1, 'You changed Password', '2025-03-07 10:03:19');
INSERT INTO `logs` (`id`, `user_id`, `action_made`, `timelog`) VALUES
(799, 1, 'You Updated Your Name to Property Custodia', '2025-03-07 10:04:00'),
(800, 1, 'You Updated Your Name to Property Custodian', '2025-03-07 10:04:04'),
(801, 1, 'You changed Password', '2025-03-07 10:04:18'),
(802, 1, 'You changed Password', '2025-03-07 10:04:24'),
(803, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-07 10:05:24'),
(804, 1, 'Kimberly Quinez Has Returned 4 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-07 10:05:32'),
(805, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-07 10:05:53'),
(806, 1, 'Logged out from the System', '2025-03-07 10:06:37'),
(807, 1, 'Logged in from the System', '2025-03-07 10:09:23'),
(808, 1, 'Logged out from the System', '2025-03-07 13:11:02'),
(809, 1, 'Logged in from the System', '2025-03-08 10:33:21'),
(810, 1, 'Logged out from the System', '2025-03-08 10:38:10'),
(811, 1, 'Logged in from the System', '2025-03-08 14:15:39'),
(812, 1, 'Admin Blocked AEGSRETR', '2025-03-08 14:23:38'),
(813, 1, 'Admin Unblocked AEGSRETR', '2025-03-08 14:23:54'),
(814, 1, 'Admin Updated the Role of AEGSRETR to ', '2025-03-08 14:38:45'),
(815, 1, 'Logged out from the System', '2025-03-08 15:12:50'),
(816, 1, 'Logged in from the System', '2025-03-09 14:52:06'),
(817, 1, 'Logged in from the System', '2025-03-09 15:57:01'),
(818, 1, 'Admin Blocked Unknown User', '2025-03-09 16:22:57'),
(819, 1, 'Admin Updated the Role of AEGSRETR to ', '2025-03-09 18:23:50'),
(820, 1, 'Admin Blocked AEGSRETR', '2025-03-09 18:57:27'),
(821, 1, 'Admin Unblocked AEGSRETR', '2025-03-09 18:57:40'),
(822, 1, 'Logged out from the System', '2025-03-09 19:04:22'),
(823, 1, 'Logged in from the System', '2025-03-09 19:04:27'),
(824, 1, 'Logged out from the System', '2025-03-09 19:04:48'),
(825, 1, 'Logged in from the System', '2025-03-09 19:04:52'),
(826, 1, 'Admin Blocked AEGSRETR', '2025-03-09 19:05:36'),
(827, 1, 'Admin Unblocked AEGSRETR', '2025-03-09 19:05:59'),
(828, 1, 'Admin Blocked AEGSRETR', '2025-03-09 19:13:14'),
(829, 1, 'Admin Unblocked AEGSRETR', '2025-03-09 19:13:29'),
(830, 1, 'Admin Blocked AEGSRETR', '2025-03-09 19:34:55'),
(831, 1, 'Logged in from the System', '2025-03-10 09:23:30'),
(832, 1, 'Admin Unblocked AEGSRETR', '2025-03-10 09:23:41'),
(833, 1, 'Admin Blocked AEGSRETR', '2025-03-10 09:28:47'),
(834, 1, 'Admin Blocked Unknown User', '2025-03-10 09:54:58'),
(835, 1, 'Admin Blocked Unknown User', '2025-03-10 09:55:16'),
(836, 1, 'Admin Blocked Unknown User', '2025-03-10 09:55:17'),
(837, 1, 'Admin Blocked Unknown User', '2025-03-10 09:55:25'),
(838, 1, 'Admin Blocked Unknown User', '2025-03-10 09:55:26'),
(839, 1, 'Admin Unblocked AEGSRETR', '2025-03-10 09:56:10'),
(840, 1, 'Admin Blocked AEGSRETR', '2025-03-10 10:01:26'),
(841, 1, 'Admin Unblocked AEGSRETR', '2025-03-10 10:05:10'),
(842, 1, 'Admin Blocked AEGSRETR', '2025-03-10 10:26:14'),
(843, 1, 'Admin Blocked Kimberly Quinez', '2025-03-10 11:28:03'),
(844, 1, 'Admin Unblocked AEGSRETR', '2025-03-10 11:28:22'),
(845, 1, 'Admin Unblocked Kimberly Quinez', '2025-03-10 11:28:32'),
(846, 1, 'Admin Blocked Kimberly Quinez', '2025-03-10 11:28:42'),
(847, 1, 'Admin Unblocked Kimberly Quinez', '2025-03-10 11:30:05'),
(848, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-10 12:40:12'),
(849, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-10 12:40:29'),
(850, 1, 'Admin Added Product: asdfghj', '2025-03-10 12:42:05'),
(851, 1, 'Admin Deleted Product: asdfghj', '2025-03-10 12:46:45'),
(852, 1, 'Admin Added Product: szdxfcv', '2025-03-10 12:51:27'),
(853, 1, 'Admin Deleted Product: szdxfcv', '2025-03-10 12:52:46'),
(854, 1, 'Logged out from the System', '2025-03-10 12:53:40'),
(855, 1, 'Logged in from the System', '2025-03-10 14:04:51'),
(856, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-10 14:30:34'),
(857, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-10 14:33:34'),
(858, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-10 14:35:32'),
(859, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-10 14:35:57'),
(860, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-10 14:36:32'),
(861, 1, 'Admin Issued New Product for Kimberly Quinez: Black Widow V3 Mini Green Switch Keyboard', '2025-03-10 15:40:38'),
(862, 1, 'Kimberly Quinez Has Returned 1 Products of Black Widow V3 Mini Green Switch Keyboard', '2025-03-10 15:41:16'),
(863, 1, 'Logged out from the System', '2025-03-10 15:47:36'),
(864, 1, 'Logged in from the System', '2025-03-10 15:47:58'),
(865, 1, 'Logged out from the System', '2025-03-10 15:48:16'),
(866, 1, 'Logged in from the System', '2025-03-10 15:48:50'),
(867, 1, 'Logged in from the System', '2025-03-10 15:49:16'),
(868, 1, 'Logged in from the System', '2025-03-10 15:54:10'),
(869, 1, 'Logged out from the System', '2025-03-10 15:56:10'),
(870, 1, 'Logged in from the System', '2025-03-10 16:03:55'),
(871, 1, 'Logged out from the System', '2025-03-10 16:04:05'),
(872, 1, 'Logged in from the System', '2025-03-11 07:00:20'),
(873, 1, 'Logged out from the System', '2025-03-11 07:01:40'),
(874, 1, 'Logged in from the System', '2025-03-11 18:34:28'),
(875, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-11 19:00:33'),
(876, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-11 19:00:43'),
(877, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-11 19:00:51'),
(878, 1, 'Logged out from the System', '2025-03-11 19:03:08'),
(879, 1, 'Logged in from the System', '2025-03-11 19:03:15'),
(880, 1, 'Admin Added Product: asdfgvnb', '2025-03-11 19:03:35'),
(881, 1, 'Admin Updated asdfgvnb\'s Image', '2025-03-11 19:04:23'),
(882, 1, 'Admin Updated asdfgvnb Information', '2025-03-11 19:04:30'),
(883, 1, 'Admin Updated AHHAHAHA Information', '2025-03-11 19:04:40'),
(884, 1, 'Admin Updated AHHAHAHA Information', '2025-03-11 19:04:50'),
(885, 1, 'Admin Deleted Product: AHHAHAHA', '2025-03-11 19:04:56'),
(886, 1, 'Logged out from the System', '2025-03-11 19:05:26'),
(887, 1, 'Logged in from the System', '2025-03-12 05:20:25'),
(888, 1, 'Logged out from the System', '2025-03-12 05:30:10'),
(889, 1, 'Logged in from the System', '2025-03-12 05:30:23'),
(890, 1, 'Admin Added Category: asdfgf', '2025-03-12 05:30:28'),
(891, 1, 'Admin Deleted Category: asdfgf', '2025-03-12 05:30:38'),
(892, 1, 'Logged out from the System', '2025-03-12 05:42:28'),
(893, 1, 'Logged in from the System', '2025-03-12 05:42:34'),
(894, 1, 'Logged in from the System', '2025-03-12 05:53:59'),
(895, 1, 'Logged out from the System', '2025-03-12 06:01:04'),
(896, 1, 'Logged in from the System', '2025-03-12 06:26:43'),
(897, 1, 'Logged out from the System', '2025-03-12 06:27:00'),
(898, 1, 'Logged in automatically', '2025-03-12 06:27:00'),
(899, 1, 'Logged out from the System', '2025-03-12 06:27:04'),
(900, 1, 'Logged in automatically', '2025-03-12 06:27:04'),
(901, 1, 'Logged out from the System', '2025-03-12 06:27:54'),
(902, 1, 'Logged in automatically', '2025-03-12 06:27:54'),
(903, 1, 'Logged out from the System', '2025-03-12 06:41:42'),
(904, 1, 'Logged in from the System', '2025-03-12 06:41:47'),
(905, 1, 'Logged out from the System', '2025-03-12 06:42:25'),
(906, 1, 'Logged out from the System', '2025-03-12 06:42:28'),
(907, 1, 'Logged out from the System', '2025-03-12 06:42:37'),
(908, 1, 'Logged in from the System', '2025-03-12 06:42:42'),
(909, 1, 'Logged out from the System', '2025-03-12 07:00:03'),
(910, 1, 'Logged in from the System', '2025-03-12 07:00:08'),
(911, 1, 'Logged out from the System', '2025-03-12 07:05:45'),
(912, 1, 'Logged in from the System', '2025-03-12 07:05:50'),
(913, 1, 'Logged out from the System', '2025-03-12 07:06:19'),
(914, 1, 'Logged in from the System', '2025-03-12 07:06:35'),
(915, 1, 'Logged in from the System', '2025-03-12 07:06:56'),
(916, 1, 'Logged out from the System', '2025-03-12 07:07:16'),
(917, 1, 'Logged in from the System', '2025-03-12 07:07:31'),
(918, 1, 'Admin Added Category: asdfg', '2025-03-12 07:07:51'),
(919, 1, 'Admin Updated the Category from asdfg to fd', '2025-03-12 07:08:05'),
(920, 1, 'Admin Deleted Category: fd', '2025-03-12 07:08:07'),
(921, 1, 'Logged out from the System', '2025-03-12 07:16:02'),
(922, 1, 'Logged in from the System', '2025-03-12 07:16:08'),
(923, 1, 'Admin Blocked AEGSRETR', '2025-03-12 07:35:18'),
(924, 1, 'Admin Unblocked AEGSRETR', '2025-03-12 07:35:21'),
(925, 1, 'Admin Added Category: xvb', '2025-03-12 07:35:54'),
(926, 1, 'Admin Updated the Category from xvb to agfdhdfh', '2025-03-12 07:36:02'),
(927, 1, 'Admin Updated the Category from agfdhdfh to AASA', '2025-03-12 07:36:27'),
(928, 1, 'Admin Deleted Category: AASA', '2025-03-12 07:36:29'),
(929, 1, 'Admin Added Brand: asdsfdg', '2025-03-12 07:36:46'),
(930, 1, 'Admin Updated the Brand from asdsfdg to ghdgsd', '2025-03-12 07:36:53'),
(931, 1, 'Admin Deleted Brand: ghdgsd', '2025-03-12 07:37:02'),
(932, 1, 'Admin Added Product: wretfhgasdfd', '2025-03-12 07:37:57'),
(933, 1, 'Admin Updated wretfhgasdfd Information', '2025-03-12 07:38:04'),
(934, 1, 'Admin Updated wretfhgasdfd\'s Image', '2025-03-12 07:38:15'),
(935, 1, 'Admin Updated wretfhgasdfd Information', '2025-03-12 07:38:25'),
(936, 1, 'Admin Deleted Product: wretfhgasdfd', '2025-03-12 07:38:32'),
(937, 1, 'Admin Issued New Product for Kimberly Quinez: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-12 07:39:32'),
(938, 1, 'Kimberly Quinez Has Returned 2 Products of GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-12 07:39:46'),
(939, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-12 07:40:22'),
(940, 1, 'Admin Updated the Role of AEGSRETR to Instructor', '2025-03-12 07:42:50'),
(941, 1, 'Admin Registered  as Instructor ', '2025-03-12 08:08:44'),
(942, 1, 'Admin Registered asfdg as Student ', '2025-03-12 08:12:47'),
(943, 1, 'Admin Registered sdfdb as Student ', '2025-03-12 08:26:21'),
(944, 1, 'Admin Registered AAAA as School Staff ', '2025-03-12 08:28:04'),
(945, 1, 'You Updated Your Name to Property Custodia', '2025-03-12 08:28:57'),
(946, 1, 'You Updated Your Name to Property Custodian', '2025-03-12 08:29:01'),
(947, 1, 'You your changed Password', '2025-03-12 08:29:13'),
(948, 1, 'Logged out from the System', '2025-03-12 08:29:36'),
(949, 1, 'Logged in from the System', '2025-03-12 08:29:40'),
(950, 1, 'You your changed Password', '2025-03-12 08:29:57'),
(951, 1, 'Logged out from the System', '2025-03-12 08:30:00'),
(952, 1, 'Logged in from the System', '2025-03-12 08:30:07'),
(953, 1, 'Logged out from the System', '2025-03-12 08:31:27'),
(954, 1, 'Logged in from the System', '2025-03-12 19:22:18'),
(955, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-12 19:26:49'),
(956, 1, 'Logged out from the System', '2025-03-12 19:49:02'),
(957, 1, 'Logged in from the System', '2025-03-13 06:18:44'),
(958, 1, 'Logged out from the System', '2025-03-13 06:19:00'),
(959, 1, 'Logged in from the System', '2025-03-13 06:19:06'),
(960, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI \'s Image', '2025-03-13 06:49:48'),
(961, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI \'s Image', '2025-03-13 06:50:19'),
(962, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-13 06:52:29'),
(963, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-13 06:53:02'),
(964, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-13 06:54:04'),
(965, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-13 06:56:04'),
(966, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-13 06:56:18'),
(967, 1, 'Admin Added Equipment: asdfgh', '2025-03-13 09:04:55'),
(968, 1, 'Admin Issued New Product for Kimberly Quinez: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 09:44:20'),
(969, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 10:09:42'),
(970, 1, 'Admin Updated the Role of AAAA to School Staff', '2025-03-13 10:45:05'),
(971, 1, 'Admin Updated the Role of AAAA to School Utilities', '2025-03-13 10:46:15'),
(972, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 10:49:43'),
(973, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 11:09:19'),
(974, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 11:10:31'),
(975, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 11:39:19'),
(976, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 11:46:45'),
(977, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 11:47:16'),
(978, 0, 'Admin Issued New Product for User ID SID014: Black Widow V3 Mini Green Switch Keyboard', '2025-03-13 11:48:20'),
(979, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 11:55:07'),
(980, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 11:55:26'),
(981, 0, 'Admin Issued New Product for User ID SID014: Black Widow V3 Mini Green Switch Keyboard', '2025-03-13 12:00:28'),
(982, 0, 'Admin Issued New Product for User ID SID015: A2 Plus Wireless Bluetooth Desktop Speakers', '2025-03-13 12:08:36'),
(983, 1, 'kylie jenner Has Returned 1 Product of A2 Plus Wireless Bluetooth Desktop Speakers (SKU: 14)', '2025-03-13 12:17:18'),
(984, 1, 'Kimberly Quinez Has Returned 1 Product of GWM306 Rechargeable Wireless RGB Mouse 1600DPI  (SKU: 12) with Remarks: Damaged', '2025-03-13 12:28:21'),
(985, 1, 'Kimberly Quinez Has Returned 1 Product of GWM306 Rechargeable Wireless RGB Mouse 1600DPI  (SKU: 13) with Remarks: Damaged', '2025-03-13 12:29:20'),
(986, 1, 'Kimberly Quinez Has Returned 1 Product of Black Widow V3 Mini Green Switch Keyboard (SKU: 10) with Remarks: Good Condition', '2025-03-13 12:35:26'),
(987, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 12:43:04'),
(988, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 12:43:19'),
(989, 1, 'Kimberly Quinez Has Returned 1 Product of GWM306 Rechargeable Wireless RGB Mouse 1600DPI  (SKU: 12) with Remarks: Good Condition', '2025-03-13 12:50:56'),
(990, 0, 'Admin Issued New Product for User ID SID014: Black Widow V3 Mini Green Switch Keyboard', '2025-03-13 12:56:43'),
(991, 0, 'Admin Issued New Product for User ID SID014: Black Widow V3 Mini Green Switch Keyboard', '2025-03-13 12:56:58'),
(992, 1, 'Kimberly Quinez Has Returned 1 Product of Black Widow V3 Mini Green Switch Keyboard (SKU: 10) with Remarks: Good Condition', '2025-03-13 12:57:09'),
(993, 0, 'Admin Issued New Product for User ID SID014: Black Widow V3 Mini Green Switch Keyboard', '2025-03-13 13:02:08'),
(994, 0, 'Admin Issued New Product for User ID SID014: Black Widow V3 Mini Green Switch Keyboard', '2025-03-13 13:03:03'),
(995, 0, 'Admin Issued New Product for User ID SID014: Black Widow V3 Mini Green Switch Keyboard', '2025-03-13 13:03:20'),
(996, 1, 'Kimberly Quinez Has Returned 1 Product of Black Widow V3 Mini Green Switch Keyboard (SKU: 10) with Remarks: Good Condition', '2025-03-13 13:03:42'),
(997, 1, 'Kimberly Quinez Has Returned 1 Product of Black Widow V3 Mini Green Switch Keyboard (SKU: 11) with Remarks: Good Condition', '2025-03-13 13:04:03'),
(998, 1, 'Admin Updated GWM306 Rechargeable Wireless RGB Mouse 1600DPI  Information', '2025-03-13 13:11:36'),
(999, 1, 'Admin Equipment Product: asdfgh', '2025-03-13 13:14:54'),
(1000, 0, 'Admin Issued New Product for User ID SID014: A2 Plus Wireless Bluetooth Desktop Speakers', '2025-03-13 13:21:50'),
(1001, 1, 'Kimberly Quinez Has Returned 1 Product of A2 Plus Wireless Bluetooth Desktop Speakers (SKU: 14) with Remarks: Good Condition', '2025-03-13 13:26:56'),
(1002, 1, 'Logged out from the System', '2025-03-13 13:55:22'),
(1003, 1, 'Logged in from the System', '2025-03-13 13:55:27'),
(1004, 1, 'You Updated Your Name to Property Custodia', '2025-03-13 14:20:48'),
(1005, 1, 'You Updated Your Name to Property Custodian', '2025-03-13 14:20:52'),
(1006, 0, 'Admin Issued New Product for User ID SID014: Black Widow V3 Mini Green Switch Keyboard', '2025-03-13 14:22:39'),
(1007, 1, 'You Updated Your Name to Anthony P. Bautista', '2025-03-13 14:24:24'),
(1008, 1, 'Kimberly Quinez Has Returned 1 Product of Black Widow V3 Mini Green Switch Keyboard (SKU: 10) with Remarks: Good Condition', '2025-03-13 14:27:18'),
(1009, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 14:27:37'),
(1010, 0, 'Admin Issued New Product for User ID SID014: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 14:27:53'),
(1011, 1, 'Kimberly Quinez Has Returned 1 Product of GWM306 Rechargeable Wireless RGB Mouse 1600DPI  (SKU: 12) with Remarks: Damaged', '2025-03-13 14:29:25'),
(1012, 1, 'Kimberly Quinez Has Returned 1 Product of GWM306 Rechargeable Wireless RGB Mouse 1600DPI  (SKU: 13) with Remarks: Good Condition', '2025-03-13 14:31:09'),
(1013, 0, 'Admin Issued New Product for User ID SID016: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 14:36:11'),
(1014, 0, 'Admin Issued New Product for User ID SID016: GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', '2025-03-13 14:45:02'),
(1015, 1, 'Logged out from the System', '2025-03-13 19:03:29'),
(1016, 1, 'Logged in from the System', '2025-03-13 19:13:59'),
(1017, 1, 'Logged in from the System', '2025-03-13 19:34:27'),
(1018, 1, 'Admin Deleted SKU: 11796971', '2025-03-13 20:05:59'),
(1019, 1, 'Logged out from the System', '2025-03-13 20:07:37'),
(1020, 1, 'Admin Deleted SKU: 11796971', '2025-03-13 20:11:23'),
(1021, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:37:16'),
(1022, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:37:53'),
(1023, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:38:33'),
(1024, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:39:11'),
(1025, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:39:54'),
(1026, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:42:47'),
(1027, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:44:16'),
(1028, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:44:21'),
(1029, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:44:40'),
(1030, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:44:46'),
(1031, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:46:01'),
(1032, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:48:02'),
(1033, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:49:29'),
(1034, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:49:41'),
(1035, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:49:44'),
(1036, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:49:49'),
(1037, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:49:55'),
(1038, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:51:17'),
(1039, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:51:40'),
(1040, 1, 'Property Custodian Printed an Equipment', '2025-03-13 20:51:42'),
(1041, 1, 'Logged in from the System', '2025-03-14 07:09:04'),
(1042, 1, 'Property Custodian Printed an Equipment', '2025-03-14 07:12:23');

-- --------------------------------------------------------

--
-- Table structure for table `tblbrands`
--

CREATE TABLE `tblbrands` (
  `id` int(11) NOT NULL,
  `BrandName` varchar(159) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbrands`
--

INSERT INTO `tblbrands` (`id`, `BrandName`, `creationDate`, `UpdationDate`) VALUES
(23, 'Royal Kludge', '2025-02-12 06:48:54', NULL),
(24, 'Acura', '2025-02-12 06:50:27', NULL),
(26, 'Delta Force', '2025-02-12 13:34:07', NULL),
(27, 'Rex', '2025-02-13 09:01:00', '2025-02-22 15:30:30'),
(28, 'Ryzen', '2025-02-13 09:18:03', NULL),
(31, 'Yamaha', '2025-02-13 09:29:35', NULL),
(32, 'Audioengine ', '2025-02-13 09:44:13', NULL),
(61, 'Razer', '2025-03-03 02:47:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `id` int(11) NOT NULL,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `CategoryName`, `Status`, `CreationDate`, `UpdationDate`) VALUES
(13, 'Computer', NULL, '2025-02-12 05:21:07', '2025-03-07 01:37:40'),
(14, 'Electronic Devices', 1, '2025-02-12 05:21:18', '2025-02-25 19:19:29'),
(50, 'Instruments', NULL, '2025-03-06 16:02:04', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblissuedproducts`
--

CREATE TABLE `tblissuedproducts` (
  `id` int(11) NOT NULL,
  `ProductId` int(11) DEFAULT NULL,
  `SNumber` int(250) DEFAULT NULL,
  `UserID` varchar(150) DEFAULT NULL,
  `IssuesDate` timestamp NULL DEFAULT current_timestamp(),
  `ReturnDate` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `ExpReturn` date DEFAULT NULL,
  `ReturnStatus` int(1) DEFAULT NULL,
  `fine` decimal(11,0) NOT NULL,
  `remark` mediumtext NOT NULL,
  `quantity` int(100) NOT NULL,
  `borrowedqty` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblproducts`
--

CREATE TABLE `tblproducts` (
  `id` int(11) NOT NULL,
  `ProductName` varchar(255) DEFAULT NULL,
  `CategoryName` varchar(250) DEFAULT NULL,
  `BrandName` varchar(250) DEFAULT NULL,
  `SNumber` varchar(25) DEFAULT NULL,
  `ProductPrice` decimal(10,2) DEFAULT NULL,
  `productImage` varchar(250) NOT NULL,
  `isIssued` int(100) NOT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `productQty` int(100) NOT NULL,
  `availableQty` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblproducts`
--

INSERT INTO `tblproducts` (`id`, `ProductName`, `CategoryName`, `BrandName`, `SNumber`, `ProductPrice`, `productImage`, `isIssued`, `RegDate`, `UpdationDate`, `productQty`, `availableQty`) VALUES
(19, 'Black Widow V3 Mini Green Switch Keyboard', 'Computer', 'Razer', '8886419347309', 595.00, 'f488b61131f44639073aa30abe7925c6.png', 0, '2025-02-12 11:44:20', '2025-03-13 06:27:18', 2, 2),
(23, 'GWM306 Rechargeable Wireless RGB Mouse 1600DPI ', 'Computer', 'Delta Force', NULL, 500.00, '6e266024b947ffff40df973db21d2818.png', 0, '2025-02-12 13:38:21', '2025-03-13 23:11:57', 2, 2),
(28, 'A2 Plus Wireless Bluetooth Desktop Speakers', 'Electronic Devices', 'AudioEngine', '11796971', 21759.00, '5e06502989c30ec0eb2f227a47307ade.png', 0, '2025-02-13 11:17:07', '2025-03-13 12:11:39', 1, 1),
(29, 'C40 Classic Guitar', 'Instruments', 'YAMAHA', 'EYP-2-1', 8800.00, '254cb6b26b0c2f72354fccc754f60bee.png', 0, '2025-02-13 11:19:14', '2025-03-13 03:07:55', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblreturn`
--

CREATE TABLE `tblreturn` (
  `id` int(11) NOT NULL,
  `UserId` varchar(250) NOT NULL,
  `ProductId` int(250) NOT NULL,
  `notes` text NOT NULL,
  `ReturnedDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblreturn`
--

INSERT INTO `tblreturn` (`id`, `UserId`, `ProductId`, `notes`, `ReturnedDate`, `quantity`) VALUES
(1, '0', 19, 'Product returned by user', '2025-03-04 17:44:03', 2),
(2, '0', 19, 'Product returned by user', '2025-03-04 17:44:21', 1),
(3, '0', 19, 'Returned Product', '2025-03-04 18:03:46', 3),
(4, '0', 19, 'Returned Product', '2025-03-04 18:09:31', 1),
(5, '0', 19, 'Returned Product', '2025-03-04 18:09:53', 1),
(6, '0', 19, 'Returned Product', '2025-03-04 18:17:29', 2),
(7, '0', 19, 'Returned Product', '2025-03-04 18:24:27', 3),
(8, '0', 19, 'Returned Product', '2025-03-04 18:30:24', 2),
(9, '0', 19, 'Returned Product', '2025-03-05 01:41:01', 1),
(10, '0', 19, 'Returned Product', '2025-03-04 18:41:09', 7),
(11, '0', 19, 'Returned Product', '2025-03-05 02:02:43', 1),
(12, '0', 19, 'Returned Product', '2025-03-05 02:05:35', 2),
(13, '0', 19, 'Returned Product', '2025-03-05 02:08:20', 1),
(14, '0', 19, 'Returned Product', '2025-03-05 02:09:58', 1),
(15, '0', 19, 'Returned Product', '2025-03-04 19:13:11', 1),
(16, '0', 19, 'Returned Product', '2025-03-05 02:13:21', 2),
(17, '0', 19, 'Returned Product', '2025-03-05 02:18:35', 2),
(18, 'SID014', 19, 'Returned Product', '2025-03-04 16:00:00', 12),
(19, '0', 19, 'Returned Product', '2025-03-05 02:31:16', 1),
(20, '0', 19, 'Returned Product', '2025-03-05 02:34:27', 1),
(21, '0', 19, 'Returned Product', '2025-03-05 02:42:02', 1),
(22, 'SID014', 23, 'Borrowed Product', '2025-03-04 16:00:00', 4),
(23, '0', 23, 'Returned Product', '2025-03-05 02:42:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblsku`
--

CREATE TABLE `tblsku` (
  `id` int(11) NOT NULL,
  `ProductId` int(11) DEFAULT NULL,
  `SNumber` varchar(250) DEFAULT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `IsIssued` int(100) NOT NULL,
  `remarks` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsku`
--

INSERT INTO `tblsku` (`id`, `ProductId`, `SNumber`, `RegDate`, `IsIssued`, `remarks`) VALUES
(10, 19, '8886419347309', '2025-03-13 03:05:45', 0, 'Good Condition'),
(11, 19, '88864193473010', '2025-03-13 03:05:53', 0, 'Good Condition'),
(12, 23, '4897038379157', '2025-03-13 03:06:59', 0, 'Damaged'),
(13, 23, '4897038379158', '2025-03-13 03:07:06', 0, 'Good Condition'),
(15, 29, 'EYP-2-1', '2025-03-13 03:07:55', 0, 'Good Condition'),
(17, 28, '11796971', '2025-03-13 12:11:39', 0, 'Good Condition');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `UserId` varchar(100) DEFAULT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `MobileNumber` char(11) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `Usertype` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `UserId`, `FullName`, `EmailId`, `MobileNumber`, `Password`, `Status`, `RegDate`, `UpdationDate`, `Usertype`) VALUES
(12, 'SID014', 'Kimberly Quinez', 'Kimberly.aquinez@gmail.com', '09666505315', '$2y$10$nhvXEQuI2ooA.dh7pjBw6.fFM9rU6292JNcG86GMdZwC3YwK/yygK', 1, '2025-02-11 11:19:56', '2025-03-10 03:30:05', 'Student'),
(25, 'SID013', 'Kendall Jenner', 'ken@gmail.com', '09814359869', 'af974cf3ae8a5bf92832a864766f5b6c', 1, '2025-02-12 07:57:07', '2025-02-25 18:04:32', 'Student'),
(27, 'SID015', 'kylie jenner', 'ky@gmail.com', '09829345985', '4163e9094a21d3a9b421a3e812340d92', 1, '2025-02-12 07:58:36', '2025-02-25 18:04:41', 'Student'),
(28, 'SID001', 'John Kelvin Aquino', 'kelvs@gmail.com', '09655567897', '71f9e2f6a84864fa53c5a4e82a8f7b8f', 1, '2025-02-20 13:38:31', NULL, ''),
(30, 'SID003', 'Raven Bernaldo', 'rav@gmail.com', '09927346757', 'b139bbc9e9477b7ab3d0bf03037a1843', 1, '2025-02-20 13:39:40', '2025-02-24 21:53:22', ''),
(72, 'SID029', 'BELLE', 'belle@gmail.com', '01324635733', '202cb962ac59075b964b07152d234b70', 1, '2025-03-07 04:11:38', NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_settings`
--
ALTER TABLE `invoice_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbrands`
--
ALTER TABLE `tblbrands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblissuedproducts`
--
ALTER TABLE `tblissuedproducts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblproducts`
--
ALTER TABLE `tblproducts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblreturn`
--
ALTER TABLE `tblreturn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblsku`
--
ALTER TABLE `tblsku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `StudentId` (`UserId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice_settings`
--
ALTER TABLE `invoice_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1043;

--
-- AUTO_INCREMENT for table `tblbrands`
--
ALTER TABLE `tblbrands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `tblissuedproducts`
--
ALTER TABLE `tblissuedproducts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `tblproducts`
--
ALTER TABLE `tblproducts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `tblreturn`
--
ALTER TABLE `tblreturn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tblsku`
--
ALTER TABLE `tblsku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
