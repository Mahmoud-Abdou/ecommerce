-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2020 at 08:09 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `markets_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app_settings_key_index` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `app_settings`
--

TRUNCATE TABLE `app_settings`;
--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `key`, `value`) VALUES
(7, 'date_format', 'l jS F Y (H:i:s)'),
(8, 'language', 'en'),
(17, 'is_human_date_format', '1'),
(18, 'app_name', 'Smart Delivery'),
(19, 'app_short_description', 'Manage Mobile Application'),
(20, 'mail_driver', 'smtp'),
(21, 'mail_host', 'smtp.hostinger.com'),
(22, 'mail_port', '587'),
(23, 'mail_username', 'productdelivery@smartersvision.com'),
(24, 'mail_password', 'NnvAwk&&E7'),
(25, 'mail_encryption', 'ssl'),
(26, 'mail_from_address', 'productdelivery@smartersvision.com'),
(27, 'mail_from_name', 'Smarter Vision'),
(30, 'timezone', 'America/Montserrat'),
(32, 'theme_contrast', 'light'),
(33, 'theme_color', 'primary'),
(34, 'app_logo', '020a2dd4-4277-425a-b450-426663f52633'),
(35, 'nav_color', 'navbar-light bg-white'),
(38, 'logo_bg_color', 'bg-white'),
(66, 'default_role', 'admin'),
(68, 'facebook_app_id', '518416208939727'),
(69, 'facebook_app_secret', '93649810f78fa9ca0d48972fee2a75cd'),
(71, 'twitter_app_id', 'twitter'),
(72, 'twitter_app_secret', 'twitter 1'),
(74, 'google_app_id', '527129559488-roolg8aq110p8r1q952fqa9tm06gbloe.apps.googleusercontent.com'),
(75, 'google_app_secret', 'FpIi8SLgc69ZWodk-xHaOrxn'),
(77, 'enable_google', '1'),
(78, 'enable_facebook', '1'),
(93, 'enable_stripe', '1'),
(94, 'stripe_key', 'pk_test_pltzOnX3zsUZMoTTTVUL4O41'),
(95, 'stripe_secret', 'sk_test_o98VZx3RKDUytaokX4My3a20'),
(101, 'custom_field_models.0', 'App\\Models\\User'),
(104, 'default_tax', '10'),
(107, 'default_currency', '$'),
(108, 'fixed_header', '0'),
(109, 'fixed_footer', '0'),
(110, 'fcm_key', 'AAAAHMZiAQA:APA91bEb71b5sN5jl-w_mmt6vLfgGY5-_CQFxMQsVEfcwO3FAh4-mk1dM6siZwwR3Ls9U0pRDpm96WN1AmrMHQ906GxljILqgU2ZB6Y1TjiLyAiIUETpu7pQFyicER8KLvM9JUiXcfWK'),
(111, 'enable_notifications', '1'),
(112, 'paypal_username', 'sb-z3gdq482047_api1.business.example.com'),
(113, 'paypal_password', 'JV2A7G4SEMLMZ565'),
(114, 'paypal_secret', 'AbMmSXVaig1ExpY3utVS3dcAjx7nAHH0utrZsUN6LYwPgo7wfMzrV5WZ'),
(115, 'enable_paypal', '1'),
(116, 'main_color', '#25D366'),
(117, 'main_dark_color', '#25D366'),
(118, 'second_color', '#043832'),
(119, 'second_dark_color', '#ccccdd'),
(120, 'accent_color', '#8c98a8'),
(121, 'accent_dark_color', '#9999aa'),
(122, 'scaffold_dark_color', '#2c2c2c'),
(123, 'scaffold_color', '#fafafa'),
(124, 'google_maps_key', 'AIzaSyAT07iMlfZ9bJt1gmGj9KhJDLFY8srI6dA'),
(125, 'mobile_language', 'en'),
(126, 'app_version', '1.0.0'),
(127, 'enable_version', '1');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE IF NOT EXISTS `carts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_product_id_foreign` (`product_id`),
  KEY `carts_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `carts`
--

TRUNCATE TABLE `carts`;
-- --------------------------------------------------------

--
-- Table structure for table `cart_options`
--

DROP TABLE IF EXISTS `cart_options`;
CREATE TABLE IF NOT EXISTS `cart_options` (
  `option_id` int(10) UNSIGNED NOT NULL,
  `cart_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`option_id`,`cart_id`),
  KEY `cart_options_cart_id_foreign` (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `cart_options`
--

TRUNCATE TABLE `cart_options`;
-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `categories`
--

TRUNCATE TABLE `categories`;
--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Drinks', 'Inventore a ut quidem animi recusandae fugit quia voluptatem. Porro quam vel debitis rerum architecto repellendus molestiae. Voluptatibus dolorem et eius tempore saepe vel. Non voluptatem saepe mollitia nam ut. Consequuntur quis quaerat sed qui explicabo necessitatibus.', '2020-04-16 17:07:52', '2020-04-16 17:07:52'),
(2, 'Medicines', 'Quos eveniet sed iste ex occaecati. Necessitatibus tenetur neque repudiandae qui eligendi quidem quos. Dolores velit impedit et quas perspiciatis. In soluta nam aut delectus inventore accusantium fugit. Reprehenderit dolorem aut iusto corrupti numquam laboriosam.', '2020-04-16 17:07:52', '2020-04-16 17:07:52'),
(3, 'Medicines', 'Excepturi assumenda nulla saepe ipsa. Aut nostrum delectus error nihil. Consequatur quasi consequatur dolorem placeat omnis. Reiciendis et dolores sint eveniet voluptatum incidunt. Blanditiis officiis esse minima quidem.', '2020-04-16 17:07:52', '2020-04-16 17:07:52'),
(4, 'Medicines', 'Non nostrum expedita possimus laudantium blanditiis natus. Repudiandae quo facilis delectus in cumque incidunt corporis. Quo qui veniam rem sint. Accusantium inventore beatae iusto error. Suscipit tempora fugiat commodi.', '2020-04-16 17:07:52', '2020-04-16 17:07:52'),
(5, 'Fruit', 'Est quia accusamus non cum ea adipisci reiciendis ut. Est sapiente autem natus libero impedit esse qui. Et eius ipsa ut corrupti vel amet dignissimos. Fugiat eos velit quasi aut maxime. Quia eum inventore aut sint animi.', '2020-04-16 17:07:52', '2020-04-16 17:07:52'),
(6, 'Vegetables', 'Eos tempora voluptatem vitae. Velit consectetur dolorem impedit voluptatem. Porro ducimus ipsum natus quibusdam architecto nisi dicta. Dolore ut iusto id. Omnis nesciunt atque totam minima cumque.', '2020-04-16 17:07:52', '2020-04-16 17:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(63) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_digits` tinyint(3) UNSIGNED NOT NULL,
  `rounding` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `currencies`
--

TRUNCATE TABLE `currencies`;
--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `symbol`, `code`, `decimal_digits`, `rounding`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', '$', 'USD', 2, 0, '2019-10-22 14:50:48', '2019-10-22 14:50:48'),
(2, 'Euro', '€', 'EUR', 2, 0, '2019-10-22 14:51:39', '2019-10-22 14:51:39'),
(3, 'Indian Rupee', 'টকা', 'INR', 2, 0, '2019-10-22 14:52:50', '2019-10-22 14:52:50'),
(4, 'Indonesian Rupiah', 'Rp', 'IDR', 0, 0, '2019-10-22 14:53:22', '2019-10-22 14:53:22'),
(5, 'Brazilian Real', 'R$', 'BRL', 2, 0, '2019-10-22 14:54:00', '2019-10-22 14:54:00'),
(6, 'Cambodian Riel', '៛', 'KHR', 2, 0, '2019-10-22 14:55:51', '2019-10-22 14:55:51'),
(7, 'Vietnamese Dong', '₫', 'VND', 0, 0, '2019-10-22 14:56:26', '2019-10-22 14:56:26');

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

DROP TABLE IF EXISTS `custom_fields`;
CREATE TABLE IF NOT EXISTS `custom_fields` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(56) COLLATE utf8mb4_unicode_ci NOT NULL,
  `values` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT NULL,
  `required` tinyint(1) DEFAULT NULL,
  `in_table` tinyint(1) DEFAULT NULL,
  `bootstrap_column` tinyint(4) DEFAULT NULL,
  `order` tinyint(4) DEFAULT NULL,
  `custom_field_model` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `custom_fields`
--

TRUNCATE TABLE `custom_fields`;
--
-- Dumping data for table `custom_fields`
--

INSERT INTO `custom_fields` (`id`, `name`, `type`, `values`, `disabled`, `required`, `in_table`, `bootstrap_column`, `order`, `custom_field_model`, `created_at`, `updated_at`) VALUES
(4, 'phone', 'text', NULL, 0, 0, 0, 6, 2, 'App\\Models\\User', '2019-09-06 20:30:00', '2019-09-06 20:31:47'),
(5, 'bio', 'textarea', NULL, 0, 0, 0, 6, 1, 'App\\Models\\User', '2019-09-06 20:43:58', '2019-09-06 20:43:58'),
(6, 'address', 'text', NULL, 0, 0, 0, 6, 3, 'App\\Models\\User', '2019-09-06 20:49:22', '2019-09-06 20:49:22');

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_values`
--

DROP TABLE IF EXISTS `custom_field_values`;
CREATE TABLE IF NOT EXISTS `custom_field_values` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `view` longtext COLLATE utf8mb4_unicode_ci,
  `custom_field_id` int(10) UNSIGNED NOT NULL,
  `customizable_type` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customizable_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_field_values_custom_field_id_foreign` (`custom_field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `custom_field_values`
--

TRUNCATE TABLE `custom_field_values`;
--
-- Dumping data for table `custom_field_values`
--

INSERT INTO `custom_field_values` (`id`, `value`, `view`, `custom_field_id`, `customizable_type`, `customizable_id`, `created_at`, `updated_at`) VALUES
(29, '+136 226 5669', '+136 226 5669', 4, 'App\\Models\\User', 2, '2019-09-06 20:52:30', '2019-09-06 20:52:30'),
(30, 'Lobortis mattis aliquam faucibus purus. Habitasse platea dictumst vestibulum rhoncus est pellentesque elit. Nunc vel risus commodo viverra maecenas accumsan lacus vel.', 'Lobortis mattis aliquam faucibus purus. Habitasse platea dictumst vestibulum rhoncus est pellentesque elit. Nunc vel risus commodo viverra maecenas accumsan lacus vel.', 5, 'App\\Models\\User', 2, '2019-09-06 20:52:30', '2019-10-16 18:32:35'),
(31, '2911 Corpening Drive South Lyon, MI 48178', '2911 Corpening Drive South Lyon, MI 48178', 6, 'App\\Models\\User', 2, '2019-09-06 20:52:30', '2019-10-16 18:32:35'),
(32, '+136 226 5660', '+136 226 5660', 4, 'App\\Models\\User', 1, '2019-09-06 20:53:58', '2019-09-27 07:12:04'),
(33, 'Faucibus ornare suspendisse sed nisi lacus sed. Pellentesque sit amet porttitor eget dolor morbi non arcu. Eu scelerisque felis imperdiet proin fermentum leo vel orci porta', 'Faucibus ornare suspendisse sed nisi lacus sed. Pellentesque sit amet porttitor eget dolor morbi non arcu. Eu scelerisque felis imperdiet proin fermentum leo vel orci porta', 5, 'App\\Models\\User', 1, '2019-09-06 20:53:58', '2019-10-16 18:23:53'),
(34, '569 Braxton Street Cortland, IL 60112', '569 Braxton Street Cortland, IL 60112', 6, 'App\\Models\\User', 1, '2019-09-06 20:53:58', '2019-10-16 18:23:53'),
(35, '+1 098-6543-236', '+1 098-6543-236', 4, 'App\\Models\\User', 3, '2019-10-15 16:21:32', '2019-10-17 22:21:43'),
(36, 'Aliquet porttitor lacus luctus accumsan tortor posuere ac ut. Tortor pretium viverra suspendisse', 'Aliquet porttitor lacus luctus accumsan tortor posuere ac ut. Tortor pretium viverra suspendisse', 5, 'App\\Models\\User', 3, '2019-10-15 16:21:32', '2019-10-17 22:21:12'),
(37, '1850 Big Elm Kansas City, MO 64106', '1850 Big Elm Kansas City, MO 64106', 6, 'App\\Models\\User', 3, '2019-10-15 16:21:32', '2019-10-17 22:21:43'),
(38, '+1 248-437-7610', '+1 248-437-7610', 4, 'App\\Models\\User', 4, '2019-10-16 18:31:46', '2019-10-16 18:31:46'),
(39, 'Faucibus ornare suspendisse sed nisi lacus sed. Pellentesque sit amet porttitor eget dolor morbi non arcu. Eu scelerisque felis imperdiet proin fermentum leo vel orci porta', 'Faucibus ornare suspendisse sed nisi lacus sed. Pellentesque sit amet porttitor eget dolor morbi non arcu. Eu scelerisque felis imperdiet proin fermentum leo vel orci porta', 5, 'App\\Models\\User', 4, '2019-10-16 18:31:46', '2019-10-16 18:31:46'),
(40, '1050 Frosty Lane Sidney, NY 13838', '1050 Frosty Lane Sidney, NY 13838', 6, 'App\\Models\\User', 4, '2019-10-16 18:31:46', '2019-10-16 18:31:46'),
(41, '+136 226 5669', '+136 226 5669', 4, 'App\\Models\\User', 5, '2019-12-15 17:49:44', '2019-12-15 17:49:44'),
(42, '<p>Short Bio</p>', 'Short Bio', 5, 'App\\Models\\User', 5, '2019-12-15 17:49:44', '2019-12-15 17:49:44'),
(43, '4722 Villa Drive', '4722 Villa Drive', 6, 'App\\Models\\User', 5, '2019-12-15 17:49:44', '2019-12-15 17:49:44'),
(44, '+136 955 6525', '+136 955 6525', 4, 'App\\Models\\User', 6, '2020-03-29 16:28:04', '2020-03-29 16:28:04'),
(45, '<p>Short bio for this driver</p>', 'Short bio for this driver', 5, 'App\\Models\\User', 6, '2020-03-29 16:28:05', '2020-03-29 16:28:05'),
(46, '4722 Villa Drive', '4722 Villa Drive', 6, 'App\\Models\\User', 6, '2020-03-29 16:28:05', '2020-03-29 16:28:05');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_addresses`
--

DROP TABLE IF EXISTS `delivery_addresses`;
CREATE TABLE IF NOT EXISTS `delivery_addresses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_addresses_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `delivery_addresses`
--

TRUNCATE TABLE `delivery_addresses`;
--
-- Dumping data for table `delivery_addresses`
--

INSERT INTO `delivery_addresses` (`id`, `description`, `address`, `latitude`, `longitude`, `is_default`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Rerum nam quia ut.', '780 Danielle Pass\nBernadineview, PA 74484', '13.671036', '38.981422', 1, 4, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(2, 'Eos reprehenderit facilis suscipit non consectetur nam.', '587 Stephan Flat\nEast Seamus, OH 89200', '14.94995', '-149.993957', 1, 1, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(3, 'Placeat maiores quas nesciunt itaque dignissimos nulla ab aut.', '177 Herzog Ports\nNew Gilda, IA 60178-4902', '4.362036', '-129.126371', 1, 5, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(4, 'Laborum optio iusto id eveniet eius similique voluptatem.', '3111 Raven Islands\nNorth Alexie, MD 41828', '-20.22822', '-171.153027', 0, 1, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(5, 'Reprehenderit et eos fugit laborum autem eius doloribus.', '8638 Hansen Field Apt. 139\nDanielburgh, SD 79185', '22.956202', '-42.706714', 0, 1, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(6, 'Id magni rerum quis accusantium corrupti asperiores incidunt.', '1446 Pacocha Pine\nLake Gay, VT 47638', '66.425958', '-74.988137', 1, 4, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(7, 'Est delectus consectetur a rerum.', '405 Ankunding Center\nLake Oniefurt, NV 53506', '28.576204', '133.368856', 0, 4, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(8, 'Voluptas perspiciatis quis ratione.', '3804 Royal Run Apt. 003\nLake Manuelamouth, WV 79128', '89.535792', '154.023147', 0, 3, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(9, 'Dolorem est mollitia nihil.', '62564 Kerluke Dale\nModestohaven, WI 54029-2759', '-76.944605', '-1.270647', 1, 2, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(10, 'Distinctio voluptate rem laudantium corporis ut.', '856 Barrows Summit Apt. 160\nRickeyshire, NM 24821-0258', '45.588193', '-119.45361', 0, 6, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(11, 'Laborum delectus iure sint qui.', '18056 Boyle Turnpike Suite 860\nDelphinemouth, VT 15406', '-60.011049', '58.116693', 0, 6, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(12, 'Ut omnis qui veritatis sequi aspernatur.', '7645 Monahan Squares\nLarkinside, LA 43607', '82.550686', '173.364226', 0, 3, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(13, 'Animi quia rerum ipsam aut qui porro magnam.', '310 Akeem Extensions Apt. 211\nHelmerville, FL 35724-6930', '32.052243', '-140.058428', 1, 1, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(14, 'Eum consequatur odio libero ea odit temporibus.', '70872 Lowe Crossroad Apt. 647\nLednerfurt, VA 17158-6264', '-35.941066', '-14.568363', 0, 5, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(15, 'Officiis nulla aut nobis aut nulla aut inventore sed.', '742 O\'Kon Courts Apt. 149\nSouth Ruthie, TX 78155', '51.078434', '-69.629144', 1, 1, '2020-04-16 17:07:58', '2020-04-16 17:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
CREATE TABLE IF NOT EXISTS `drivers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `delivery_fee` double(5,2) NOT NULL DEFAULT '0.00',
  `total_orders` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `earning` double(9,2) NOT NULL DEFAULT '0.00',
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drivers_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `drivers`
--

TRUNCATE TABLE `drivers`;
-- --------------------------------------------------------

--
-- Table structure for table `drivers_payouts`
--

DROP TABLE IF EXISTS `drivers_payouts`;
CREATE TABLE IF NOT EXISTS `drivers_payouts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `method` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(9,2) NOT NULL DEFAULT '0.00',
  `paid_date` datetime NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drivers_payouts_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `drivers_payouts`
--

TRUNCATE TABLE `drivers_payouts`;
-- --------------------------------------------------------

--
-- Table structure for table `driver_markets`
--

DROP TABLE IF EXISTS `driver_markets`;
CREATE TABLE IF NOT EXISTS `driver_markets` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `market_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`market_id`),
  KEY `driver_markets_market_id_foreign` (`market_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `driver_markets`
--

TRUNCATE TABLE `driver_markets`;
--
-- Dumping data for table `driver_markets`
--

INSERT INTO `driver_markets` (`user_id`, `market_id`) VALUES
(5, 1),
(5, 2),
(5, 4),
(6, 2),
(6, 3),
(6, 4);

-- --------------------------------------------------------

--
-- Table structure for table `earnings`
--

DROP TABLE IF EXISTS `earnings`;
CREATE TABLE IF NOT EXISTS `earnings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `market_id` int(10) UNSIGNED NOT NULL,
  `total_orders` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `total_earning` double(9,2) NOT NULL DEFAULT '0.00',
  `admin_earning` double(9,2) NOT NULL DEFAULT '0.00',
  `market_earning` double(9,2) NOT NULL DEFAULT '0.00',
  `delivery_fee` double(9,2) NOT NULL DEFAULT '0.00',
  `tax` double(9,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `earnings_market_id_foreign` (`market_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `earnings`
--

TRUNCATE TABLE `earnings`;
-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `faq_category_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faqs_faq_category_id_foreign` (`faq_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `faqs`
--

TRUNCATE TABLE `faqs`;
--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `faq_category_id`, `created_at`, `updated_at`) VALUES
(1, 'Repudiandae ea non possimus sunt. Culpa nesciunt illo qui provident doloribus.', 'ME\' beautifully printed on it were white, but there was silence for some time busily writing in his turn; and both creatures hid their faces in their paws. \'And how do you know what they\'re like.\'.', 4, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(2, 'Exercitationem nam sed dignissimos eius est. Nihil quia minima excepturi adipisci.', 'Gryphon is, look at the Hatter, \'when the Queen furiously, throwing an inkstand at the Mouse\'s tail; \'but why do you mean \"purpose\"?\' said Alice. \'I\'m glad they\'ve begun asking riddles.--I believe I.', 4, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(3, 'Velit suscipit minus unde quam delectus voluptatibus odit. Unde itaque velit aut eveniet.', 'I should think you\'ll feel it a minute or two, it was good manners for her neck would bend about easily in any direction, like a frog; and both creatures hid their faces in their mouths; and the.', 3, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(4, 'Voluptatem odio corrupti quo ut est modi. Non inventore aut aut.', 'You grant that?\' \'I suppose they are the jurors.\' She said the Caterpillar, and the arm that was trickling down his cheeks, he went on again:-- \'You may not have lived much under the window, she.', 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(5, 'Rerum est illo voluptas nobis. Nam ex nulla sit nam. Distinctio fugiat amet molestiae explicabo.', 'Queen. \'You make me smaller, I suppose.\' So she stood looking at the cook, and a bright brass plate with the other: the only one way up as the doubled-up soldiers were silent, and looked at poor.', 4, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(6, 'Consectetur in laborum consequatur magnam. Consequatur qui natus suscipit et quia.', 'I must have a trial: For really this morning I\'ve nothing to do.\" Said the mouse to the other: the Duchess by this time). \'Don\'t grunt,\' said Alice; \'you needn\'t be afraid of interrupting him,).', 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(7, 'Illo neque sunt at modi sit quia. Rerum ducimus quam ullam tempore omnis quia similique.', 'Then she went hunting about, and crept a little nervous about it while the rest waited in silence. At last the Mouse, in a tone of great relief. \'Now at OURS they had settled down again, the cook.', 1, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(8, 'Nemo a dolore qui fugiat esse. Tenetur id qui velit quibusdam.', 'That he met in the kitchen. \'When I\'M a Duchess,\' she said to herself. \'Shy, they seem to encourage the witness at all: he kept shifting from one foot up the fan she was quite silent for a great.', 4, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(9, 'Veniam porro qui exercitationem quo itaque. Cum animi ad quia quia quis. In nihil sunt ut dolorem.', 'Queen to play croquet with the Dormouse. \'Write that down,\' the King said to Alice, and she ran off as hard as it was her dream:-- First, she tried the effect of lying down on one of them.\' In.', 2, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(10, 'Nihil totam eveniet id ipsam alias velit fugiat. Eos natus beatae provident eligendi ullam et ipsa.', 'King put on her face like the look of it in time,\' said the Cat; and this was not going to say,\' said the Gryphon, sighing in his confusion he bit a large ring, with the words came very queer.', 2, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(11, 'Voluptatibus necessitatibus aut deserunt eos. Odit ad hic rem ut nihil.', 'I can guess that,\' she added aloud. \'Do you take me for his housemaid,\' she said to herself; \'his eyes are so VERY remarkable in that; nor did Alice think it so quickly that the Queen say only.', 2, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(12, 'Qui earum excepturi sed qui. In incidunt sequi quo perferendis recusandae expedita.', 'Pool of Tears \'Curiouser and curiouser!\' cried Alice in a sorrowful tone; \'at least there\'s no harm in trying.\' So she went round the hall, but they were lying on their slates, \'SHE doesn\'t believe.', 3, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(13, 'Ipsam omnis quisquam vel repudiandae. Est consequatur reiciendis accusamus. Quae eum eos est eos.', 'Mock Turtle in a great hurry, muttering to himself as he spoke, and added \'It isn\'t a letter, written by the time she had tired herself out with trying, the poor child, \'for I never knew so much.', 2, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(14, 'Aut et possimus animi. Libero enim sed delectus architecto quo quis. Distinctio odio ab ipsum qui.', 'Improve his shining tail, And pour the waters of the hall; but, alas! either the locks were too large, or the key was lying on the top of her sharp little chin. \'I\'ve a right to think,\' said Alice.', 4, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(15, 'Fugiat et ab quia aut occaecati. Doloremque autem et consequatur et.', 'The Queen\'s Croquet-Ground A large rose-tree stood near the entrance of the fact. \'I keep them to sell,\' the Hatter asked triumphantly. Alice did not feel encouraged to ask any more questions about.', 3, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(16, 'Aut vero non nihil nulla. Et sequi dolorem qui recusandae.', 'IS it to speak first, \'why your cat grins like that?\' \'It\'s a pun!\' the King eagerly, and he hurried off. Alice thought over all the other side, the puppy made another rush at Alice the moment they.', 4, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(17, 'Laborum commodi iure voluptates dolores minima. Ea velit temporibus enim voluptates.', 'It doesn\'t look like it?\' he said. \'Fifteenth,\' said the Cat, \'if you don\'t like them raw.\' \'Well, be off, then!\' said the March Hare. Visit either you like: they\'re both mad.\' \'But I don\'t believe.', 1, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(18, 'Fugit harum a nulla est. Ea labore et ut vel omnis.', 'I\'m not particular as to bring tears into her eyes--and still as she could for sneezing. There was exactly one a-piece all round. \'But she must have been changed in the sand with wooden spades, then.', 2, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(19, 'Molestias sed at perferendis sint id et dolor. Aut quidem officiis at sit distinctio hic.', 'Rabbit angrily. \'Here! Come and help me out of that is--\"Be what you mean,\' the March Hare. \'Sixteenth,\' added the Hatter, and he poured a little timidly, \'why you are very dull!\' \'You ought to tell.', 3, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(20, 'Rerum modi quia dolore dignissimos sed sit. Maxime explicabo et repellat mollitia non vero.', 'Gryphon, and the sounds will take care of the baby, and not to make out who was passing at the end of half those long words, and, what\'s more, I don\'t take this young lady to see how he can EVEN.', 4, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(21, 'Quibusdam ut fugit consequatur sed. Nisi iste aliquam sunt animi. Ex animi expedita animi sapiente.', 'Pigeon went on, half to Alice. \'Nothing,\' said Alice. \'Why, you don\'t know what you would seem to see its meaning. \'And just as well wait, as she passed; it was all very well as pigs, and was going.', 3, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(22, 'Quis non nisi labore rerum aut. Mollitia et velit voluptas. Sit expedita aut non quis numquam.', 'Cat. \'--so long as there seemed to be treated with respect. \'Cheshire Puss,\' she began, rather timidly, saying to her to speak with. Alice waited patiently until it chose to speak with. Alice waited.', 4, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(23, 'Minima ipsa itaque modi est tenetur a. Dolorem nisi odio odit aut fugiat.', 'Caterpillar. \'Well, perhaps you haven\'t found it advisable--\"\' \'Found WHAT?\' said the Hatter, \'you wouldn\'t talk about trouble!\' said the Caterpillar contemptuously. \'Who are YOU?\' Which brought.', 3, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(24, 'Aperiam dolor ipsa fugit saepe id ut minima et. Dolores dicta quisquam in enim.', 'Alice, as she could see it trot away quietly into the sky all the children she knew, who might do something better with the strange creatures of her own child-life, and the procession came opposite.', 2, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(25, 'Accusamus necessitatibus ut veniam reiciendis fugiat ut. Unde animi possimus ex molestiae.', 'Alice; \'all I know THAT well enough; don\'t be nervous, or I\'ll have you executed, whether you\'re a little more conversation with her head!\' the Queen shouted at the mushroom (she had grown in the.', 4, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(26, 'Sed corrupti rem esse. Et perferendis dolores et. Consectetur vel similique ipsa facere et eaque.', 'Lizard in head downwards, and the Queen to-day?\' \'I should like it put the Lizard in head downwards, and the shrill voice of the legs of the well, and noticed that one of them.\' In another moment.', 3, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(27, 'Eligendi sunt dolorem numquam. Dolores maiores architecto perspiciatis in ea laudantium ex sed.', 'Duck. \'Found IT,\' the Mouse had changed his mind, and was coming to, but it was very hot, she kept on puzzling about it just at present--at least I mean what I used to say.\' \'So he did, so he with.', 1, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(28, 'Vero illo aut labore voluptatem consequatur voluptatum itaque. Quia eum tempora et aut et officia.', 'I\'ll eat it,\' said the Queen. \'I haven\'t opened it yet,\' said the Gryphon. \'It\'s all her coaxing. Hardly knowing what she did, she picked her way out. \'I shall do nothing of tumbling down stairs!.', 4, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(29, 'Accusamus aperiam officia fuga dicta fuga architecto. Voluptatem aliquam commodi nemo dolor.', 'VERY long claws and a large crowd collected round it: there was a most extraordinary noise going on shrinking rapidly: she soon found an opportunity of adding, \'You\'re looking for eggs, I know is.', 4, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(30, 'Dolores deleniti vitae sapiente cum doloremque corporis. Est autem odit quod repellendus qui omnis.', 'All this time she heard a little bottle that stood near the looking-glass. There was a table in the face. \'I\'ll put a white one in by mistake; and if it wasn\'t very civil of you to set them free.', 3, '2020-04-16 17:08:01', '2020-04-16 17:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

DROP TABLE IF EXISTS `faq_categories`;
CREATE TABLE IF NOT EXISTS `faq_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `faq_categories`
--

TRUNCATE TABLE `faq_categories`;
--
-- Dumping data for table `faq_categories`
--

INSERT INTO `faq_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Products', '2019-08-31 11:31:52', '2019-08-31 11:31:52'),
(2, 'Services', '2019-08-31 11:32:03', '2019-08-31 11:32:03'),
(3, 'Delivery', '2019-08-31 11:32:11', '2019-08-31 11:32:11'),
(4, 'Misc', '2019-08-31 11:32:17', '2019-08-31 11:32:17');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favorites_product_id_foreign` (`product_id`),
  KEY `favorites_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `favorites`
--

TRUNCATE TABLE `favorites`;
--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `product_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 5, 4, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(2, 20, 2, '2020-04-16 17:08:01', '2020-04-16 17:08:01'),
(3, 17, 3, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(4, 2, 4, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(5, 3, 4, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(6, 22, 3, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(7, 2, 4, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(8, 15, 1, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(9, 21, 4, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(10, 11, 4, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(11, 4, 2, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(12, 2, 5, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(13, 29, 4, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(14, 13, 2, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(15, 8, 6, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(16, 21, 6, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(17, 13, 5, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(18, 18, 6, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(19, 9, 5, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(20, 1, 2, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(21, 17, 5, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(22, 3, 2, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(23, 21, 6, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(24, 22, 6, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(25, 5, 6, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(26, 26, 5, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(27, 18, 5, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(28, 20, 6, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(29, 7, 3, '2020-04-16 17:08:02', '2020-04-16 17:08:02'),
(30, 16, 5, '2020-04-16 17:08:02', '2020-04-16 17:08:02');

-- --------------------------------------------------------

--
-- Table structure for table `favorite_options`
--

DROP TABLE IF EXISTS `favorite_options`;
CREATE TABLE IF NOT EXISTS `favorite_options` (
  `option_id` int(10) UNSIGNED NOT NULL,
  `favorite_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`option_id`,`favorite_id`),
  KEY `favorite_options_favorite_id_foreign` (`favorite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `favorite_options`
--

TRUNCATE TABLE `favorite_options`;
--
-- Dumping data for table `favorite_options`
--

INSERT INTO `favorite_options` (`option_id`, `favorite_id`) VALUES
(1, 1),
(1, 5),
(2, 6),
(3, 2),
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
CREATE TABLE IF NOT EXISTS `fields` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `fields`
--

TRUNCATE TABLE `fields`;
--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Grocery', 'Eum similique maiores atque quia explicabo. Dolores quia placeat consequatur id quis perspiciatis. Ducimus sit ducimus officia labore maiores et porro. Est iusto natus nesciunt debitis consequuntur totam. Et illo et autem inventore earum corrupti.', '2020-04-11 14:03:21', '2020-04-11 14:03:21'),
(2, 'Pharmacy', 'Eaque et aut natus. Minima blanditiis ut sunt distinctio ad. Quasi doloremque rerum ex rerum. Molestias similique similique aut rerum delectus blanditiis et. Dolorem et quas nostrum est nobis.', '2020-04-11 14:03:21', '2020-04-11 14:03:21'),
(3, 'Restaurant', 'Est nihil omnis natus ducimus ducimus excepturi quos. Et praesentium in quia veniam. Tempore aut nesciunt consequatur pariatur recusandae. Voluptatem commodi eius quaerat est deleniti impedit. Qui quo harum est sequi incidunt labore eligendi cum.', '2020-04-11 14:03:21', '2020-04-11 14:03:21'),
(4, 'Store', 'Ex nostrum suscipit aut et labore. Ut dolor ut eum eum voluptatem ex. Sapiente in tempora soluta voluptatem. Officia accusantium quae sit. Rerum esse ipsa molestias dolorem et est autem consequatur.', '2020-04-11 14:03:21', '2020-04-11 14:03:21'),
(5, 'Electronics', 'Dolorum earum ut blanditiis blanditiis. Facere quis voluptates assumenda saepe. Ab aspernatur voluptatibus rem doloremque cum impedit. Itaque blanditiis commodi repudiandae asperiores. Modi atque placeat consectetur et aut blanditiis.', '2020-04-11 14:03:21', '2020-04-11 14:03:21'),
(6, 'Furniture', 'Est et iste enim. Quam repudiandae commodi rerum non esse. Et in aut sequi est aspernatur. Facere non modi expedita asperiores. Ipsa laborum saepe deserunt qui consequatur voluptas inventore dolorum.', '2020-04-11 14:03:21', '2020-04-11 14:03:21');

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `market_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galleries_market_id_foreign` (`market_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `galleries`
--

TRUNCATE TABLE `galleries`;
--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `description`, `market_id`, `created_at`, `updated_at`) VALUES
(1, 'Cumque quia necessitatibus et est incidunt quos.', 7, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(2, 'Velit odit earum soluta accusamus.', 5, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(3, 'Quia doloribus ratione sint accusamus.', 4, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(4, 'Qui quo blanditiis impedit odit ut possimus consectetur fugiat.', 6, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(5, 'Rerum quasi ratione amet architecto quae.', 2, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(6, 'Ullam laboriosam qui delectus aperiam aliquam est.', 8, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(7, 'Aspernatur nam soluta neque nihil facilis sit.', 3, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(8, 'Quod adipisci architecto at quas provident cumque at.', 10, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(9, 'Blanditiis accusamus eius odit velit laudantium quasi.', 2, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(10, 'Mollitia repudiandae dolorem consequatur id quas molestias.', 7, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(11, 'Ut impedit aut itaque non magni iste nam.', 7, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(12, 'Alias aut impedit necessitatibus officiis in aut.', 5, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(13, 'Aut et commodi et nisi recusandae.', 3, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(14, 'Magni et qui quidem tempore.', 3, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(15, 'Cupiditate animi placeat vitae culpa sunt ducimus sunt.', 1, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(16, 'Alias fugit sit ducimus fugit maiores.', 5, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(17, 'Nobis corporis quos nostrum totam.', 2, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(18, 'Provident et voluptatum aut error qui rerum.', 3, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(19, 'Rerum ullam veniam est qui quo quaerat nemo alias.', 6, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(20, 'Labore dolor sit ut eum fuga.', 1, '2020-04-16 17:07:56', '2020-04-16 17:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `markets`
--

DROP TABLE IF EXISTS `markets`;
CREATE TABLE IF NOT EXISTS `markets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `information` text COLLATE utf8mb4_unicode_ci,
  `admin_commission` double(8,2) NOT NULL DEFAULT '0.00',
  `delivery_fee` double(8,2) NOT NULL DEFAULT '0.00',
  `delivery_range` double(8,2) NOT NULL DEFAULT '0.00',
  `default_tax` double(8,2) NOT NULL DEFAULT '0.00',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `available_for_delivery` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `markets`
--

TRUNCATE TABLE `markets`;
--
-- Dumping data for table `markets`
--

INSERT INTO `markets` (`id`, `name`, `description`, `address`, `latitude`, `longitude`, `phone`, `mobile`, `information`, `admin_commission`, `delivery_fee`, `delivery_range`, `default_tax`, `closed`, `available_for_delivery`, `created_at`, `updated_at`) VALUES
(1, 'Shop Hessel and Sons', 'Nihil totam voluptatibus ab et dolores deserunt porro nihil. Cumque cumque quos aliquam libero. Porro explicabo doloremque voluptatibus similique.', '392 Giovanny PineGorczanybury, CO 13600-6178', '52.51840', '13.40767', '674-873-6439 x071', '+1 (705) 564-1321', 'Eos nam est rerum magnam sunt. Animi id fuga architecto praesentium unde corporis. Magnam sit accusamus quis rerum soluta.', 45.72, 3.85, 95.02, 7.08, 0, 1, '2020-04-12 16:50:58', '2020-04-15 17:06:19'),
(2, 'Grocery Towne LLC', 'Minus in et iste ut iure sed. Inventore neque accusantium dolor praesentium commodi. Et nihil enim quod perferendis inventore minus ratione. Doloribus quam commodi perferendis quia amet.', '537 Batz ForgesGibsonburgh, DE 74012', '40.2634', '-3.712133', '307.782.2121 x34984', '+1-352-363-0087', 'Et officia aspernatur est et voluptas. Voluptatum rerum cum exercitationem aut non sit nulla. Asperiores amet reprehenderit est.', 40.38, 1.04, 99.95, 13.88, 0, 0, '2020-04-12 16:50:58', '2020-04-15 14:08:20'),
(3, 'Grocery Marquardt LLC', 'Quia soluta eum nostrum nihil in. Magnam maiores nesciunt unde. Itaque omnis inventore est omnis.', '5692 Johns BrookPort Casandrabury, CA 52751-4379', '52.5185037815', '13.40876789731', '1-831-919-4594 x9486', '363-942-7301 x0341', 'Qui voluptatibus doloremque beatae tempora quia reiciendis. Nam et quas eos perspiciatis eos aut. Placeat fugiat vel in ut sed blanditiis laborum veniam.', 45.23, 4.49, 96.12, 8.06, 0, 0, '2020-04-12 16:50:59', '2020-04-15 14:08:30'),
(4, 'Mall Parker-Walter', 'Aut corrupti sunt repudiandae vero id voluptatem voluptatum quia. Maxime dolor similique ratione quas laudantium qui iure. Maxime architecto eum quaerat qui alias necessitatibus.', '2195 Barrows GardenBillmouth, DE 79105', '40.263325079024', '-3.7119565627789', '1-268-559-7341', '837.460.7352 x44454', 'Sunt vero fuga ad doloribus. Sit quod error ipsa et corporis ut non voluptatem. Et sint ratione debitis laboriosam officia labore deserunt.', 12.09, 8.92, 7.76, 20.53, 1, 1, '2020-04-12 16:50:59', '2020-04-15 14:08:46'),
(5, 'Shop Gleichner and Sons', 'Eveniet nobis optio et ut illo facilis fugiat. Omnis voluptatem eligendi quos ut quidem vero excepturi. Qui velit dolorem dolorum maiores.', '7414 Ward IslandNew Olaf, CO 18119', '52.518703907815', '13.409577689731', '+1-882-206-0154', '(295) 475-9585 x55036', 'Maxime ipsam consequuntur nam culpa. Corporis esse unde id fugit. Eaque aut ex est placeat harum consequatur quo.', 34.85, 6.50, 12.77, 5.80, 0, 1, '2020-04-12 16:50:59', '2020-04-15 17:06:45'),
(6, 'Furniture Brakus Inc', 'Quos fuga autem tenetur. Dignissimos deleniti et laudantium laudantium. Aut veniam quis fuga illum quam exercitationem veniam sunt.', '9469 Roslyn Drive Apt. 119Berryberg, WV 70819', '40.263225079024', '-3.712365627789', '(272) 925-1675', '893-382-9663 x07822', 'Nulla maiores ut dolorem quisquam sed aut. Ut quod earum quo et assumenda ut quaerat aut. In est laborum ipsa ipsum commodi non.', 33.94, 5.93, 61.05, 9.80, 0, 1, '2020-04-12 16:50:59', '2020-04-15 17:06:27'),
(7, 'Market Schroeder, Metz and Torphy', 'Necessitatibus dolorem nihil inventore sed in est corporis sint. Est quo occaecati illum. Ratione reiciendis sed assumenda nesciunt minus.', '395 Stefan Field Suite 965Westland, NY 90647-0835', '52.518803907815', '13.407877689731', '(589) 428-1103', '(342) 650-1700', 'Repellat ut provident molestiae sequi illum non. Officiis quam hic ut assumenda enim et necessitatibus. Quam accusamus cupiditate ipsam corrupti quibusdam tenetur magnam.', 36.48, 8.71, 69.55, 25.62, 0, 0, '2020-04-12 16:50:59', '2020-04-15 14:09:32'),
(8, 'Grocery Lubowitz Inc', 'Cumque non at sint et qui tempore vitae non. Temporibus sit in perferendis facilis unde esse. Perferendis fugiat officiis in et.', '46778 Cali LoafNorth Meganehaven, KY 34487-5419', '40.264315079024', '-3.7118765627789', '+1 (962) 856-4285', '+18453121889', 'Perferendis consequatur voluptas qui impedit illum consectetur maxime. Molestias distinctio error nulla dolorem vel sunt eaque. Animi labore quod nulla expedita dolorum nisi ea.', 12.58, 1.97, 61.10, 15.63, 1, 1, '2020-04-12 16:50:59', '2020-04-15 17:06:37'),
(9, 'Market Bogan, Runolfsdottir and Casper', 'Et mollitia delectus autem aut aliquid. Eligendi aperiam incidunt consequatur. Illum eum doloribus nam non laboriosam corporis dolor. Veniam natus voluptas autem ducimus qui ab.', '159 Bogisich Falls Apt. 101Mableburgh, WV 10857', '40.262315079024', '-3.7119365627789', '1-292-441-6638 x8693', '(675) 819-0667', 'Quidem quia quis rem ex. Incidunt unde quae quibusdam. A reiciendis fuga asperiores commodi.', 13.85, 7.56, 22.88, 20.51, 0, 1, '2020-04-12 16:50:59', '2020-04-15 17:06:12'),
(10, 'Market Roob PLC', 'Sed ratione eveniet ab illum qui omnis repellendus. Ut accusantium inventore expedita voluptas rerum. Totam qui aliquam sint fugiat rem ea.', '57550 Lebsack ForkOceanestad, WV 21732-0232', '52.519303907815', '13.408657989731', '+17568340591', '236-684-3422', 'Omnis tempore accusantium corporis quisquam ad. Eos exercitationem nemo nostrum. Aut velit animi eaque.', 26.40, 1.54, 46.72, 25.24, 1, 1, '2020-04-12 16:50:59', '2020-04-15 14:10:23');

-- --------------------------------------------------------
--
-- Table structure for table `markets_payouts`
--

DROP TABLE IF EXISTS `markets_payouts`;
CREATE TABLE IF NOT EXISTS `markets_payouts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `market_id` int(10) UNSIGNED NOT NULL,
  `method` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(9,2) NOT NULL DEFAULT '0.00',
  `paid_date` datetime NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `markets_payouts_market_id_foreign` (`market_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `markets_payouts`
--

TRUNCATE TABLE `markets_payouts`;
-- --------------------------------------------------------

--
-- Table structure for table `market_fields`
--

DROP TABLE IF EXISTS `market_fields`;
CREATE TABLE IF NOT EXISTS `market_fields` (
  `field_id` int(10) UNSIGNED NOT NULL,
  `market_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`field_id`,`market_id`),
  KEY `market_fields_market_id_foreign` (`market_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `market_fields`
--

TRUNCATE TABLE `market_fields`;
--
-- Dumping data for table `market_fields`
--

INSERT INTO `market_fields` (`field_id`, `market_id`) VALUES
(1, 7),
(1, 9),
(2, 1),
(2, 2),
(2, 7),
(3, 2),
(3, 6),
(4, 1),
(4, 3),
(5, 8),
(5, 10),
(6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `market_reviews`
--

DROP TABLE IF EXISTS `market_reviews`;
CREATE TABLE IF NOT EXISTS `market_reviews` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` tinyint(3) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `market_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `market_reviews_user_id_foreign` (`user_id`),
  KEY `market_reviews_market_id_foreign` (`market_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `market_reviews`
--

TRUNCATE TABLE `market_reviews`;
--
-- Dumping data for table `market_reviews`
--

INSERT INTO `market_reviews` (`id`, `review`, `rate`, `user_id`, `market_id`, `created_at`, `updated_at`) VALUES
(1, 'Queen jumped up and throw us, with the Lory, who at last she stretched her arms folded, frowning like a Jack-in-the-box, and up the conversation a little. \'\'Tis so,\' said the Gryphon. \'How the.', 1, 1, 2, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(2, 'I\'m sure I have to beat them off, and found in it a minute or two the Caterpillar angrily, rearing itself upright as it was written to nobody, which isn\'t usual, you know.\' Alice had no idea what a.', 4, 6, 2, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(3, 'Alice began to say which), and they went up to the other: the only one way of keeping up the fan she was up to the Gryphon. \'I mean, what makes them sour--and camomile that makes the matter on, What.', 4, 4, 8, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(4, 'Alice began to repeat it, when a sharp hiss made her so savage when they arrived, with a large caterpillar, that was sitting between them, fast asleep, and the baby--the fire-irons came first; then.', 5, 5, 10, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(5, 'Alice. \'Oh, don\'t bother ME,\' said Alice a little before she made some tarts, All on a little quicker. \'What a number of changes she had never seen such a long tail, certainly,\' said Alice loudly.', 4, 3, 1, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(6, 'MINE.\' The Queen smiled and passed on. \'Who ARE you doing out here? Run home this moment, and fetch me a good deal worse off than before, as the rest of it in a minute, nurse! But I\'ve got to?\'.', 1, 5, 1, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(7, 'She took down a jar from one end of trials, \"There was some attempts at applause, which was sitting next to her. The Cat seemed to be seen--everything seemed to think that very few little girls of.', 4, 2, 10, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(8, 'Alice, \'to speak to this last remark, \'it\'s a vegetable. It doesn\'t look like one, but the Gryphon went on to the other, looking uneasily at the flowers and those cool fountains, but she stopped.', 1, 3, 3, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(9, 'I wish you could manage it?) \'And what are YOUR shoes done with?\' said the Caterpillar. \'Well, perhaps you haven\'t found it so yet,\' said the Mouse replied rather crossly: \'of course you know why.', 2, 5, 5, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(10, 'Duchess sang the second time round, she found a little shaking among the branches, and every now and then another confusion of voices--\'Hold up his head--Brandy now--Don\'t choke him--How was it, old.', 4, 5, 6, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(11, 'As soon as look at me like that!\' He got behind him, and said \'That\'s very curious!\' she thought. \'But everything\'s curious today. I think you\'d better leave off,\' said the Mouse in the air, I\'m.', 3, 4, 1, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(12, 'I dare say there may be ONE.\' \'One, indeed!\' said Alice, \'a great girl like you,\' (she might well say that \"I see what would be wasting our breath.\" \"I\'ll be judge, I\'ll be jury,\" Said cunning old.', 1, 5, 6, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(13, 'King. \'Shan\'t,\' said the voice. \'Fetch me my gloves this moment!\' Then came a little wider. \'Come, it\'s pleased so far,\' said the Mock Turtle. Alice was thoroughly puzzled. \'Does the boots and.', 1, 3, 8, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(14, 'I can\'t get out of a procession,\' thought she, \'what would become of you? I gave her answer. \'They\'re done with blacking, I believe.\' \'Boots and shoes under the sea,\' the Gryphon said, in a confused.', 4, 6, 1, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(15, 'And the moral of THAT is--\"Take care of the house, \"Let us both go to law: I will tell you just now what the next witness. It quite makes my forehead ache!\' Alice watched the Queen ordering off her.', 2, 2, 7, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(16, 'Queen jumped up on to himself as he said in a deep voice, \'What are you getting on now, my dear?\' it continued, turning to the shore. CHAPTER III. A Caucus-Race and a Dodo, a Lory and an Eaglet, and.', 3, 4, 1, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(17, 'Majesty!\' the soldiers remaining behind to execute the unfortunate gardeners, who ran to Alice for protection. \'You shan\'t be beheaded!\' said Alice, feeling very glad to find that she hardly knew.', 2, 3, 5, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(18, 'Mock Turtle, and said nothing. \'When we were little,\' the Mock Turtle sighed deeply, and began, in a great thistle, to keep herself from being run over; and the beak-- Pray how did you do either!\'.', 4, 2, 3, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(19, 'Alice remarked. \'Right, as usual,\' said the Mock Turtle went on, yawning and rubbing its eyes, \'Of course, of course; just what I like\"!\' \'You might just as the doubled-up soldiers were always.', 2, 4, 1, '2020-04-16 17:07:57', '2020-04-16 17:07:57'),
(20, 'Hatter replied. \'Of course not,\' said the Caterpillar. \'Well, I\'ve tried banks, and I\'ve tried to look over their heads. She felt very curious to see that the Gryphon replied very politely, feeling.', 2, 3, 3, '2020-04-16 17:07:57', '2020-04-16 17:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `collection_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(10) UNSIGNED NOT NULL,
  `manipulations` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_properties` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsive_images` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `media`
--

TRUNCATE TABLE `media`;
-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `migrations`
--

TRUNCATE TABLE `migrations`;
--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_05_26_175145_create_permission_tables', 1),
(4, '2018_06_12_140344_create_media_table', 1),
(5, '2018_06_13_035117_create_uploads_table', 1),
(6, '2018_07_17_180731_create_settings_table', 1),
(7, '2018_07_24_211308_create_custom_fields_table', 1),
(8, '2018_07_24_211327_create_custom_field_values_table', 1),
(9, '2019_08_29_213820_create_fields_table', 1),
(10, '2019_08_29_213821_create_markets_table', 1),
(11, '2019_08_29_213822_create_categories_table', 1),
(12, '2019_08_29_213826_create_option_groups_table', 1),
(13, '2019_08_29_213829_create_faq_categories_table', 1),
(14, '2019_08_29_213833_create_order_statuses_table', 1),
(15, '2019_08_29_213837_create_products_table', 1),
(16, '2019_08_29_213838_create_options_table', 1),
(17, '2019_08_29_213842_create_galleries_table', 1),
(18, '2019_08_29_213847_create_product_reviews_table', 1),
(19, '2019_08_29_213921_create_payments_table', 1),
(20, '2019_08_29_213922_create_delivery_addresses_table', 1),
(21, '2019_08_29_213926_create_faqs_table', 1),
(22, '2019_08_29_213940_create_market_reviews_table', 1),
(23, '2019_08_30_152927_create_favorites_table', 1),
(24, '2019_08_31_111104_create_orders_table', 1),
(25, '2019_09_04_153857_create_carts_table', 1),
(26, '2019_09_04_153858_create_favorite_options_table', 1),
(27, '2019_09_04_153859_create_cart_options_table', 1),
(28, '2019_09_04_153958_create_product_orders_table', 1),
(29, '2019_09_04_154957_create_product_order_options_table', 1),
(30, '2019_09_04_163857_create_user_markets_table', 1),
(31, '2019_10_22_144652_create_currencies_table', 1),
(32, '2019_12_14_134302_create_driver_markets_table', 1),
(33, '2020_03_25_094752_create_drivers_table', 1),
(34, '2020_03_25_094802_create_earnings_table', 1),
(35, '2020_03_25_094809_create_drivers_payouts_table', 1),
(36, '2020_03_25_094817_create_markets_payouts_table', 1),
(37, '2020_03_27_094855_create_notifications_table', 1),
(38, '2020_04_11_135804_create_market_fields_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `model_has_permissions`
--

TRUNCATE TABLE `model_has_permissions`;
-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `model_has_roles`
--

TRUNCATE TABLE `model_has_roles`;
--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(4, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `notifications`
--

TRUNCATE TABLE `notifications`;
-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(8,2) NOT NULL DEFAULT '0.00',
  `product_id` int(10) UNSIGNED NOT NULL,
  `option_group_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `options_product_id_foreign` (`product_id`),
  KEY `options_option_group_id_foreign` (`option_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `options`
--

TRUNCATE TABLE `options`;
--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `name`, `description`, `price`, `product_id`, `option_group_id`, `created_at`, `updated_at`) VALUES
(1, 'Tomato', 'Qui voluptas deleniti.', 11.17, 19, 2, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(2, 'Oil', 'Aliquam ducimus officiis est.', 23.86, 15, 3, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(3, 'Tomato', 'Itaque dolores quae in est.', 42.38, 29, 3, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(4, '500g', 'Qui consequatur enim numquam et vel.', 40.41, 13, 2, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(5, '5L', 'Aut et iste ea cum.', 29.95, 26, 1, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(6, 'XL', 'Quia voluptas asperiores nihil dolores.', 27.54, 14, 3, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(7, 'L', 'Quia amet et.', 27.49, 23, 2, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(8, 'Red', 'Dolore ea dolores sed dolores.', 34.63, 26, 1, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(9, 'White', 'Sint est asperiores est.', 49.54, 28, 2, '2020-04-16 17:07:58', '2020-04-16 17:07:58'),
(10, 'XL', 'Nihil vitae cumque magni molestiae eos.', 38.00, 23, 3, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(11, 'S', 'Minima ex explicabo ut aut.', 27.30, 18, 1, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(12, 'Oil', 'Perferendis id deleniti doloremque.', 39.27, 14, 3, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(13, 'Green', 'Id quis consectetur consequatur rem.', 45.74, 27, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(14, 'L', 'Molestias pariatur maxime laborum.', 10.45, 21, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(15, '5L', 'Esse illum et provident.', 17.76, 25, 3, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(16, '500g', 'At reiciendis maxime voluptatum animi.', 44.87, 21, 3, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(17, 'L', 'Consequatur ipsum adipisci rerum inventore.', 31.64, 4, 2, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(18, 'Tomato', 'Porro odit magnam sed.', 26.09, 11, 1, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(19, 'Green', 'Itaque ducimus est ut fugiat.', 22.23, 24, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(20, '500g', 'Nemo est odit deserunt asperiores.', 36.83, 18, 2, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(21, 'XL', 'Tempora sequi omnis saepe ipsam.', 33.81, 15, 3, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(22, '5L', 'Amet rerum distinctio ut quasi doloribus.', 19.40, 7, 1, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(23, 'White', 'Libero iste quam.', 46.20, 29, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(24, 'L', 'Libero sed laborum vero.', 36.18, 5, 2, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(25, '5L', 'Enim dicta voluptate qui at mollitia.', 37.51, 19, 1, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(26, 'Tomato', 'Quasi praesentium quo dicta.', 36.41, 9, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(27, 'L', 'Ut atque magnam doloribus.', 15.34, 2, 1, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(28, 'Green', 'Enim quos perferendis qui dolorem.', 41.20, 8, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(29, 'S', 'Autem et repellendus.', 33.64, 19, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(30, '5L', 'Enim pariatur aliquid sit id.', 31.02, 8, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(31, 'S', 'In placeat quos culpa esse.', 33.45, 3, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(32, 'White', 'Rem repellat rem magnam eaque.', 28.23, 5, 1, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(33, 'Red', 'Aut est voluptas unde ut similique.', 22.91, 22, 1, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(34, '500g', 'Aut fuga porro laboriosam.', 25.48, 28, 2, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(35, 'S', 'Ut perferendis unde ipsum recusandae.', 23.53, 29, 2, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(36, '1Kg', 'Blanditiis doloremque enim sunt officiis.', 25.88, 28, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(37, '1Kg', 'Exercitationem consequatur et quas dignissimos.', 37.59, 25, 2, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(38, 'XL', 'Occaecati id voluptas ut.', 38.51, 20, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(39, 'L', 'Facere delectus ut.', 36.47, 13, 1, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(40, 'XL', 'Repellat voluptatem dicta accusamus consequuntur saepe.', 39.01, 16, 3, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(41, '5L', 'Exercitationem dignissimos et explicabo omnis.', 19.26, 13, 2, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(42, 'Tomato', 'Sit et aut libero.', 26.47, 16, 1, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(43, 'S', 'Vel nobis deserunt nisi non maiores.', 10.93, 4, 4, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(44, 'White', 'Cum qui quisquam consequatur et.', 31.03, 5, 3, '2020-04-16 17:07:59', '2020-04-16 17:07:59'),
(45, '5L', 'Aperiam nam id maiores consequatur.', 46.12, 29, 3, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(46, '1Kg', 'Minus dolorem quod numquam explicabo.', 43.68, 15, 1, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(47, 'S', 'Unde est et harum perferendis minima.', 45.43, 11, 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(48, '500g', 'Qui rem provident omnis tempore.', 29.92, 10, 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(49, 'XL', 'Qui architecto nobis cum.', 16.48, 1, 3, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(50, '1Kg', 'Voluptatem eius odit qui earum.', 35.46, 2, 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(51, '500g', 'Corporis quos et voluptatem velit.', 47.98, 5, 1, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(52, 'Red', 'Velit officia qui maiores dolor.', 38.30, 21, 3, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(53, 'White', 'Saepe voluptas deleniti ullam.', 48.31, 29, 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(54, '2L', 'Aut cupiditate similique dicta velit.', 27.61, 4, 3, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(55, '500g', 'Minus voluptates eius sit ea.', 46.02, 28, 4, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(56, 'S', 'Sed sed nihil nihil.', 12.04, 28, 4, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(57, '1Kg', 'Dolorum voluptatibus perspiciatis dolor.', 11.91, 12, 1, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(58, 'S', 'Aliquam distinctio explicabo.', 24.85, 12, 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(59, 'Green', 'Voluptatum pariatur nulla quia.', 26.78, 17, 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(60, 'White', 'Consequatur nostrum harum veniam.', 15.68, 4, 1, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(61, 'Green', 'Impedit illum animi est.', 23.60, 8, 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(62, 'Oil', 'Veritatis tenetur ex et reprehenderit.', 10.18, 18, 1, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(63, 'Tomato', 'Numquam aut consectetur.', 15.08, 20, 1, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(64, '500g', 'Earum quaerat nam.', 33.89, 30, 3, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(65, 'Red', 'Quasi expedita deserunt soluta.', 38.46, 10, 3, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(66, 'Green', 'Et sed tempore veniam sed.', 45.67, 11, 4, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(67, 'Green', 'Illum rerum velit qui voluptas voluptatem.', 47.26, 12, 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(68, '1Kg', 'Repellat suscipit asperiores.', 42.49, 7, 1, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(69, 'Green', 'Fuga voluptas delectus.', 28.66, 21, 1, '2020-04-16 17:08:00', '2020-04-16 17:08:00'),
(70, '1Kg', 'Quis placeat dolores fugit assumenda.', 34.91, 19, 2, '2020-04-16 17:08:00', '2020-04-16 17:08:00');

-- --------------------------------------------------------

--
-- Table structure for table `option_groups`
--

DROP TABLE IF EXISTS `option_groups`;
CREATE TABLE IF NOT EXISTS `option_groups` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `option_groups`
--

TRUNCATE TABLE `option_groups`;
--
-- Dumping data for table `option_groups`
--

INSERT INTO `option_groups` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Size', '2019-08-31 09:55:28', '2019-08-31 09:55:28'),
(2, 'Color', '2019-10-09 12:26:28', '2019-10-09 12:26:28'),
(3, 'Parfum', '2019-10-09 12:26:28', '2019-10-09 12:26:28'),
(4, 'Taste', '2019-10-09 12:26:28', '2019-10-09 12:26:28');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_status_id` int(10) UNSIGNED NOT NULL,
  `tax` double(5,2) NOT NULL DEFAULT '0.00',
  `delivery_fee` double(5,2) NOT NULL DEFAULT '0.00',
  `hint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `driver_id` int(10) UNSIGNED DEFAULT NULL,
  `delivery_address_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_order_status_id_foreign` (`order_status_id`),
  KEY `orders_driver_id_foreign` (`driver_id`),
  KEY `orders_delivery_address_id_foreign` (`delivery_address_id`),
  KEY `orders_payment_id_foreign` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `orders`
--

TRUNCATE TABLE `orders`;
-- --------------------------------------------------------

--
-- Table structure for table `order_statuses`
--

DROP TABLE IF EXISTS `order_statuses`;
CREATE TABLE IF NOT EXISTS `order_statuses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `order_statuses`
--

TRUNCATE TABLE `order_statuses`;
--
-- Dumping data for table `order_statuses`
--

INSERT INTO `order_statuses` (`id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Order Received', '2019-08-30 15:39:28', '2019-10-15 17:03:14'),
(2, 'Preparing', '2019-10-15 17:03:50', '2019-10-15 17:03:50'),
(3, 'Ready', '2019-10-15 17:04:30', '2019-10-15 17:04:30'),
(4, 'On the Way', '2019-10-15 17:04:13', '2019-10-15 17:04:13'),
(5, 'Delivered', '2019-10-15 17:04:30', '2019-10-15 17:04:30');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `password_resets`
--

TRUNCATE TABLE `password_resets`;
-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `price` double(8,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `payments`
--

TRUNCATE TABLE `payments`;
-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `permissions`
--

TRUNCATE TABLE `permissions`;
--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'users.profile', 'web', '2020-03-29 13:58:02', '2020-03-29 13:58:02', NULL),
(2, 'dashboard', 'web', '2020-03-29 13:58:02', '2020-03-29 13:58:02', NULL),
(3, 'medias.create', 'web', '2020-03-29 13:58:02', '2020-03-29 13:58:02', NULL),
(4, 'medias.delete', 'web', '2020-03-29 13:58:02', '2020-03-29 13:58:02', NULL),
(5, 'medias', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(6, 'permissions.index', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(7, 'permissions.edit', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(8, 'permissions.update', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(9, 'permissions.destroy', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(10, 'roles.index', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(11, 'roles.edit', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(12, 'roles.update', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(13, 'roles.destroy', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(14, 'customFields.index', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(15, 'customFields.create', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(16, 'customFields.store', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(17, 'customFields.show', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(18, 'customFields.edit', 'web', '2020-03-29 13:58:03', '2020-03-29 13:58:03', NULL),
(19, 'customFields.update', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(20, 'customFields.destroy', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(21, 'users.login-as-user', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(22, 'users.index', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(23, 'users.create', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(24, 'users.store', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(25, 'users.show', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(26, 'users.edit', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(27, 'users.update', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(28, 'users.destroy', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(29, 'app-settings', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(30, 'markets.index', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(31, 'markets.create', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(32, 'markets.store', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(33, 'markets.edit', 'web', '2020-03-29 13:58:04', '2020-03-29 13:58:04', NULL),
(34, 'markets.update', 'web', '2020-03-29 13:58:05', '2020-03-29 13:58:05', NULL),
(35, 'markets.destroy', 'web', '2020-03-29 13:58:05', '2020-03-29 13:58:05', NULL),
(36, 'categories.index', 'web', '2020-03-29 13:58:05', '2020-03-29 13:58:05', NULL),
(37, 'categories.create', 'web', '2020-03-29 13:58:05', '2020-03-29 13:58:05', NULL),
(38, 'categories.store', 'web', '2020-03-29 13:58:05', '2020-03-29 13:58:05', NULL),
(39, 'categories.edit', 'web', '2020-03-29 13:58:05', '2020-03-29 13:58:05', NULL),
(40, 'categories.update', 'web', '2020-03-29 13:58:05', '2020-03-29 13:58:05', NULL),
(41, 'categories.destroy', 'web', '2020-03-29 13:58:05', '2020-03-29 13:58:05', NULL),
(42, 'faqCategories.index', 'web', '2020-03-29 13:58:06', '2020-03-29 13:58:06', NULL),
(43, 'faqCategories.create', 'web', '2020-03-29 13:58:06', '2020-03-29 13:58:06', NULL),
(44, 'faqCategories.store', 'web', '2020-03-29 13:58:06', '2020-03-29 13:58:06', NULL),
(45, 'faqCategories.edit', 'web', '2020-03-29 13:58:06', '2020-03-29 13:58:06', NULL),
(46, 'faqCategories.update', 'web', '2020-03-29 13:58:06', '2020-03-29 13:58:06', NULL),
(47, 'faqCategories.destroy', 'web', '2020-03-29 13:58:06', '2020-03-29 13:58:06', NULL),
(48, 'orderStatuses.index', 'web', '2020-03-29 13:58:06', '2020-03-29 13:58:06', NULL),
(49, 'orderStatuses.show', 'web', '2020-03-29 13:58:06', '2020-03-29 13:58:06', NULL),
(50, 'orderStatuses.edit', 'web', '2020-03-29 13:58:06', '2020-03-29 13:58:06', NULL),
(51, 'orderStatuses.update', 'web', '2020-03-29 13:58:07', '2020-03-29 13:58:07', NULL),
(52, 'products.index', 'web', '2020-03-29 13:58:07', '2020-03-29 13:58:07', NULL),
(53, 'products.create', 'web', '2020-03-29 13:58:07', '2020-03-29 13:58:07', NULL),
(54, 'products.store', 'web', '2020-03-29 13:58:07', '2020-03-29 13:58:07', NULL),
(55, 'products.edit', 'web', '2020-03-29 13:58:07', '2020-03-29 13:58:07', NULL),
(56, 'products.update', 'web', '2020-03-29 13:58:07', '2020-03-29 13:58:07', NULL),
(57, 'products.destroy', 'web', '2020-03-29 13:58:07', '2020-03-29 13:58:07', NULL),
(58, 'galleries.index', 'web', '2020-03-29 13:58:07', '2020-03-29 13:58:07', NULL),
(59, 'galleries.create', 'web', '2020-03-29 13:58:07', '2020-03-29 13:58:07', NULL),
(60, 'galleries.store', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(61, 'galleries.edit', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(62, 'galleries.update', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(63, 'galleries.destroy', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(64, 'productReviews.index', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(65, 'productReviews.create', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(66, 'productReviews.store', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(67, 'productReviews.edit', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(68, 'productReviews.update', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(69, 'productReviews.destroy', 'web', '2020-03-29 13:58:08', '2020-03-29 13:58:08', NULL),
(70, 'nutrition.index', 'web', '2020-03-29 13:58:09', '2020-03-29 13:58:09', NULL),
(71, 'nutrition.create', 'web', '2020-03-29 13:58:09', '2020-03-29 13:58:09', NULL),
(72, 'nutrition.store', 'web', '2020-03-29 13:58:09', '2020-03-29 13:58:09', NULL),
(73, 'nutrition.edit', 'web', '2020-03-29 13:58:09', '2020-03-29 13:58:09', NULL),
(74, 'nutrition.update', 'web', '2020-03-29 13:58:09', '2020-03-29 13:58:09', NULL),
(75, 'nutrition.destroy', 'web', '2020-03-29 13:58:09', '2020-03-29 13:58:09', NULL),
(76, 'options.index', 'web', '2020-03-29 13:58:09', '2020-03-29 13:58:09', NULL),
(77, 'options.create', 'web', '2020-03-29 13:58:09', '2020-03-29 13:58:09', NULL),
(78, 'options.store', 'web', '2020-03-29 13:58:09', '2020-03-29 13:58:09', NULL),
(79, 'options.show', 'web', '2020-03-29 13:58:10', '2020-03-29 13:58:10', NULL),
(80, 'options.edit', 'web', '2020-03-29 13:58:10', '2020-03-29 13:58:10', NULL),
(81, 'options.update', 'web', '2020-03-29 13:58:10', '2020-03-29 13:58:10', NULL),
(82, 'options.destroy', 'web', '2020-03-29 13:58:10', '2020-03-29 13:58:10', NULL),
(83, 'payments.index', 'web', '2020-03-29 13:58:10', '2020-03-29 13:58:10', NULL),
(84, 'payments.show', 'web', '2020-03-29 13:58:10', '2020-03-29 13:58:10', NULL),
(85, 'payments.update', 'web', '2020-03-29 13:58:10', '2020-03-29 13:58:10', NULL),
(86, 'faqs.index', 'web', '2020-03-29 13:58:10', '2020-03-29 13:58:10', NULL),
(87, 'faqs.create', 'web', '2020-03-29 13:58:11', '2020-03-29 13:58:11', NULL),
(88, 'faqs.store', 'web', '2020-03-29 13:58:11', '2020-03-29 13:58:11', NULL),
(89, 'faqs.edit', 'web', '2020-03-29 13:58:11', '2020-03-29 13:58:11', NULL),
(90, 'faqs.update', 'web', '2020-03-29 13:58:11', '2020-03-29 13:58:11', NULL),
(91, 'faqs.destroy', 'web', '2020-03-29 13:58:11', '2020-03-29 13:58:11', NULL),
(92, 'marketReviews.index', 'web', '2020-03-29 13:58:11', '2020-03-29 13:58:11', NULL),
(93, 'marketReviews.create', 'web', '2020-03-29 13:58:11', '2020-03-29 13:58:11', NULL),
(94, 'marketReviews.store', 'web', '2020-03-29 13:58:12', '2020-03-29 13:58:12', NULL),
(95, 'marketReviews.edit', 'web', '2020-03-29 13:58:12', '2020-03-29 13:58:12', NULL),
(96, 'marketReviews.update', 'web', '2020-03-29 13:58:12', '2020-03-29 13:58:12', NULL),
(97, 'marketReviews.destroy', 'web', '2020-03-29 13:58:12', '2020-03-29 13:58:12', NULL),
(98, 'favorites.index', 'web', '2020-03-29 13:58:12', '2020-03-29 13:58:12', NULL),
(99, 'favorites.create', 'web', '2020-03-29 13:58:12', '2020-03-29 13:58:12', NULL),
(100, 'favorites.store', 'web', '2020-03-29 13:58:12', '2020-03-29 13:58:12', NULL),
(101, 'favorites.edit', 'web', '2020-03-29 13:58:12', '2020-03-29 13:58:12', NULL),
(102, 'favorites.update', 'web', '2020-03-29 13:58:12', '2020-03-29 13:58:12', NULL),
(103, 'favorites.destroy', 'web', '2020-03-29 13:58:13', '2020-03-29 13:58:13', NULL),
(104, 'orders.index', 'web', '2020-03-29 13:58:13', '2020-03-29 13:58:13', NULL),
(105, 'orders.create', 'web', '2020-03-29 13:58:13', '2020-03-29 13:58:13', NULL),
(106, 'orders.store', 'web', '2020-03-29 13:58:13', '2020-03-29 13:58:13', NULL),
(107, 'orders.show', 'web', '2020-03-29 13:58:13', '2020-03-29 13:58:13', NULL),
(108, 'orders.edit', 'web', '2020-03-29 13:58:13', '2020-03-29 13:58:13', NULL),
(109, 'orders.update', 'web', '2020-03-29 13:58:13', '2020-03-29 13:58:13', NULL),
(110, 'orders.destroy', 'web', '2020-03-29 13:58:13', '2020-03-29 13:58:13', NULL),
(111, 'notifications.index', 'web', '2020-03-29 13:58:14', '2020-03-29 13:58:14', NULL),
(112, 'notifications.show', 'web', '2020-03-29 13:58:14', '2020-03-29 13:58:14', NULL),
(113, 'notifications.destroy', 'web', '2020-03-29 13:58:14', '2020-03-29 13:58:14', NULL),
(114, 'carts.index', 'web', '2020-03-29 13:58:14', '2020-03-29 13:58:14', NULL),
(115, 'carts.edit', 'web', '2020-03-29 13:58:14', '2020-03-29 13:58:14', NULL),
(116, 'carts.update', 'web', '2020-03-29 13:58:14', '2020-03-29 13:58:14', NULL),
(117, 'carts.destroy', 'web', '2020-03-29 13:58:14', '2020-03-29 13:58:14', NULL),
(118, 'currencies.index', 'web', '2020-03-29 13:58:14', '2020-03-29 13:58:14', NULL),
(119, 'currencies.create', 'web', '2020-03-29 13:58:15', '2020-03-29 13:58:15', NULL),
(120, 'currencies.store', 'web', '2020-03-29 13:58:15', '2020-03-29 13:58:15', NULL),
(121, 'currencies.edit', 'web', '2020-03-29 13:58:15', '2020-03-29 13:58:15', NULL),
(122, 'currencies.update', 'web', '2020-03-29 13:58:15', '2020-03-29 13:58:15', NULL),
(123, 'currencies.destroy', 'web', '2020-03-29 13:58:15', '2020-03-29 13:58:15', NULL),
(124, 'deliveryAddresses.index', 'web', '2020-03-29 13:58:15', '2020-03-29 13:58:15', NULL),
(125, 'deliveryAddresses.create', 'web', '2020-03-29 13:58:15', '2020-03-29 13:58:15', NULL),
(126, 'deliveryAddresses.store', 'web', '2020-03-29 13:58:15', '2020-03-29 13:58:15', NULL),
(127, 'deliveryAddresses.edit', 'web', '2020-03-29 13:58:16', '2020-03-29 13:58:16', NULL),
(128, 'deliveryAddresses.update', 'web', '2020-03-29 13:58:16', '2020-03-29 13:58:16', NULL),
(129, 'deliveryAddresses.destroy', 'web', '2020-03-29 13:58:16', '2020-03-29 13:58:16', NULL),
(130, 'drivers.index', 'web', '2020-03-29 13:58:16', '2020-03-29 13:58:16', NULL),
(131, 'drivers.create', 'web', '2020-03-29 13:58:16', '2020-03-29 13:58:16', NULL),
(132, 'drivers.store', 'web', '2020-03-29 13:58:16', '2020-03-29 13:58:16', NULL),
(133, 'drivers.show', 'web', '2020-03-29 13:58:16', '2020-03-29 13:58:16', NULL),
(134, 'drivers.edit', 'web', '2020-03-29 13:58:16', '2020-03-29 13:58:16', NULL),
(135, 'drivers.update', 'web', '2020-03-29 13:58:16', '2020-03-29 13:58:16', NULL),
(136, 'drivers.destroy', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(137, 'earnings.index', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(138, 'earnings.create', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(139, 'earnings.store', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(140, 'earnings.show', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(141, 'earnings.edit', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(142, 'earnings.update', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(143, 'earnings.destroy', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(144, 'driversPayouts.index', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(145, 'driversPayouts.create', 'web', '2020-03-29 13:58:17', '2020-03-29 13:58:17', NULL),
(146, 'driversPayouts.store', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(147, 'driversPayouts.show', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(148, 'driversPayouts.edit', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(149, 'driversPayouts.update', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(150, 'driversPayouts.destroy', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(151, 'marketsPayouts.index', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(152, 'marketsPayouts.create', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(153, 'marketsPayouts.store', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(154, 'marketsPayouts.show', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(155, 'marketsPayouts.edit', 'web', '2020-03-29 13:58:18', '2020-03-29 13:58:18', NULL),
(156, 'marketsPayouts.update', 'web', '2020-03-29 13:58:19', '2020-03-29 13:58:19', NULL),
(157, 'marketsPayouts.destroy', 'web', '2020-03-29 13:58:19', '2020-03-29 13:58:19', NULL),
(158, 'permissions.create', 'web', '2020-03-29 13:59:15', '2020-03-29 13:59:15', NULL),
(159, 'permissions.store', 'web', '2020-03-29 13:59:15', '2020-03-29 13:59:15', NULL),
(160, 'permissions.show', 'web', '2020-03-29 13:59:15', '2020-03-29 13:59:15', NULL),
(161, 'roles.create', 'web', '2020-03-29 13:59:15', '2020-03-29 13:59:15', NULL),
(162, 'roles.store', 'web', '2020-03-29 13:59:15', '2020-03-29 13:59:15', NULL),
(163, 'roles.show', 'web', '2020-03-29 13:59:16', '2020-03-29 13:59:16', NULL),
(164, 'fields.index', 'web', '2020-04-11 14:04:39', '2020-04-11 14:04:39', NULL),
(165, 'fields.create', 'web', '2020-04-11 14:04:39', '2020-04-11 14:04:39', NULL),
(166, 'fields.store', 'web', '2020-04-11 14:04:39', '2020-04-11 14:04:39', NULL),
(167, 'fields.edit', 'web', '2020-04-11 14:04:39', '2020-04-11 14:04:39', NULL),
(168, 'fields.update', 'web', '2020-04-11 14:04:39', '2020-04-11 14:04:39', NULL),
(169, 'fields.destroy', 'web', '2020-04-11 14:04:40', '2020-04-11 14:04:40', NULL),
(170, 'optionGroups.index', 'web', '2020-04-11 14:04:40', '2020-04-11 14:04:40', NULL),
(171, 'optionGroups.create', 'web', '2020-04-11 14:04:40', '2020-04-11 14:04:40', NULL),
(172, 'optionGroups.store', 'web', '2020-04-11 14:04:40', '2020-04-11 14:04:40', NULL),
(173, 'optionGroups.edit', 'web', '2020-04-11 14:04:40', '2020-04-11 14:04:40', NULL),
(174, 'optionGroups.update', 'web', '2020-04-11 14:04:40', '2020-04-11 14:04:40', NULL),
(175, 'optionGroups.destroy', 'web', '2020-04-11 14:04:40', '2020-04-11 14:04:40', NULL),
(176, 'category_translation.index', 'web', '2020-05-10 09:06:46', '2020-05-10 09:06:46', NULL),
(177, 'category_translation.create', 'web', '2020-05-10 09:08:57', '2020-05-10 09:08:57', NULL),
(178, 'category_translation.store', 'web', '2020-05-10 09:09:21', '2020-05-10 09:09:21', NULL),
(179, 'category_translation.show', 'web', '2020-05-10 09:09:48', '2020-05-10 09:09:48', NULL),
(180, 'category_translation.edit', 'web', '2020-05-10 09:10:10', '2020-05-10 09:10:10', NULL),
(181, 'category_translation.destroy', 'web', '2020-05-10 09:11:33', '2020-05-10 09:11:33', NULL),
(null, 'product_translation.index', 'web', '2020-05-10 09:06:46', '2020-05-10 09:06:46', NULL),
(null, 'product_translation.create', 'web', '2020-05-10 09:08:57', '2020-05-10 09:08:57', NULL),
(null, 'product_translation.store', 'web', '2020-05-10 09:09:21', '2020-05-10 09:09:21', NULL),
(null, 'product_translation.show', 'web', '2020-05-10 09:09:48', '2020-05-10 09:09:48', NULL),
(null, 'product_translation.edit', 'web', '2020-05-10 09:10:10', '2020-05-10 09:10:10', NULL),
(null, 'product_translation.destroy', 'web', '2020-05-10 09:11:33', '2020-05-10 09:11:33', NULL);


-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(8,2) NOT NULL DEFAULT '0.00',
  `discount_price` double(8,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `capacity` double(9,2) NOT NULL DEFAULT '0.00',
  `package_items_count` double(9,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(127) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `deliverable` tinyint(1) NOT NULL DEFAULT '1',
  `market_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_market_id_foreign` (`market_id`),
  KEY `products_category_id_foreign` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `products`
--

TRUNCATE TABLE `products`;
--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `discount_price`, `description`, `capacity`, `package_items_count`, `unit`, `featured`, `deliverable`, `market_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Cupcake', 19.17, 13.62, 'Ea libero minus labore nihil accusantium ut. Id recusandae excepturi enim saepe cumque. Non ratione fugiat sit illo et illo sapiente facere. Recusandae cumque corporis consequatur amet maxime nam.', 279.98, 2.00, 'm²', 1, 0, 4, 3, '2020-04-16 17:07:53', '2020-04-16 17:07:53'),
(2, 'Pasta', 33.79, 29.71, 'Autem ut nihil omnis dolores numquam sit. Porro non omnis ea porro architecto eos. Distinctio dolor tenetur architecto.', 352.63, 5.00, 'g', 1, 1, 5, 6, '2020-04-16 17:07:53', '2020-04-16 17:07:53'),
(3, 'Eggs', 13.14, 0.00, 'Corporis rerum eum recusandae omnis. Quia earum suscipit est consequatur error maiores in. Odit ab perferendis dignissimos voluptas enim quam. In ducimus perspiciatis necessitatibus et et.', 268.27, 4.00, 'Oz', 0, 0, 5, 3, '2020-04-16 17:07:53', '2020-04-16 17:07:53'),
(4, 'Fish', 30.92, 25.44, 'Autem voluptatem dolorem quis id earum alias architecto. Eos maiores temporibus omnis et voluptatem dolorem velit.', 61.32, 1.00, 'm', 0, 0, 3, 6, '2020-04-16 17:07:53', '2020-04-16 17:07:53'),
(5, 'Tuna steak', 34.95, 31.95, 'Et officiis enim fugit fuga itaque qui a. Et doloremque aliquid illo quibusdam maiores rerum velit. Sequi vel nihil id omnis. Et sapiente aut deserunt tempora dolores qui.', 197.78, 2.00, 'Kg', 1, 1, 7, 2, '2020-04-16 17:07:53', '2020-04-16 17:07:53'),
(6, 'Cupcake', 27.22, 23.46, 'Aut sint eaque velit tempora nostrum ab sunt. Voluptatibus reiciendis iusto inventore exercitationem doloremque odio. Autem et id cumque autem. Corporis et quam accusamus aut ullam sequi.', 471.67, 2.00, 'm²', 0, 0, 2, 5, '2020-04-16 17:07:53', '2020-04-16 17:07:53'),
(7, 'Sugar', 33.88, 0.00, 'Sequi eos hic nobis nemo sunt eos. Eligendi molestiae vero consequatur quis voluptatum aut iusto. Voluptas saepe fugit et culpa autem.', 421.02, 4.00, 'Oz', 0, 1, 1, 1, '2020-04-16 17:07:53', '2020-04-16 17:07:53'),
(8, 'Onion', 32.28, 0.00, 'Et id consequuntur voluptas repellat facilis hic dolores. Sint natus soluta assumenda omnis. Dolorem fugiat omnis asperiores ut est et.', 310.02, 5.00, 'Oz', 0, 0, 9, 6, '2020-04-16 17:07:53', '2020-04-16 17:07:53'),
(9, 'Sandwich', 40.40, 36.28, 'Consequatur voluptates animi quidem voluptatem libero. Nemo et consequatur ipsum enim fugiat ratione. Iure consequatur quis maxime.', 451.34, 2.00, 'm', 1, 1, 5, 2, '2020-04-16 17:07:53', '2020-04-16 17:07:53'),
(10, 'Cheese', 18.10, 15.20, 'Sit velit repudiandae libero dolorem. Sapiente nostrum harum consequatur qui. Dolores odit enim voluptatibus voluptatem. Est pariatur aliquam quidem nulla mollitia dolorum.', 423.37, 5.00, 'ml', 0, 1, 4, 4, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(11, 'Vinegar', 48.82, 44.80, 'Natus mollitia et nobis voluptates veritatis. Voluptatem doloribus perspiciatis sint et quasi occaecati consequatur ut. Illo ut dolorem enim blanditiis ea. Non dolores dolor consequatur qui.', 317.65, 3.00, 'L', 0, 1, 7, 3, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(12, 'Spaghetti', 48.58, 40.38, 'Et sit nesciunt aliquid voluptatem est quo. Velit architecto cum vel qui eum tempora officia. Voluptatem porro at laudantium.', 477.70, 1.00, 'm²', 0, 1, 5, 5, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(13, 'Aspirin', 46.80, 44.21, 'Nulla eum iusto ipsa nemo eos non. Exercitationem repellendus eos molestias assumenda. Unde provident ut ut non.', 439.88, 6.00, 'm', 0, 0, 5, 3, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(14, 'Spaghetti', 37.77, 0.00, 'Voluptas illum ipsam facere quia eius amet quia. Ducimus et at aut sequi est aut voluptatem. Praesentium sint facilis dicta consequatur iure nihil.', 331.13, 1.00, 'g', 0, 1, 1, 3, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(15, 'Vinegar', 48.72, 0.00, 'Veritatis corrupti eligendi est omnis quae aut. Et molestiae animi et repudiandae omnis reiciendis est. Rerum odit eveniet modi dolorem quam. Itaque et nostrum vel aut repellat. Sint iusto id optio.', 265.02, 6.00, 'Kg', 0, 0, 1, 4, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(16, 'Fish', 22.62, 18.55, 'Labore ad dolorum laboriosam. Consequatur iure et omnis praesentium molestiae necessitatibus esse. Et rerum consequatur et occaecati dignissimos neque sint.', 255.33, 2.00, 'Oz', 0, 1, 7, 2, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(17, 'Salt', 35.56, 0.00, 'Consequuntur rerum sed exercitationem. Commodi consequuntur aut et. Fugiat nobis quos saepe. Deserunt quos voluptas recusandae praesentium nostrum ea dolorum dolor.', 246.52, 4.00, 'm²', 0, 1, 6, 3, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(18, 'Pasta', 24.05, 17.00, 'Illo ad vero modi rerum. Pariatur temporibus et incidunt dolor rerum est. Repudiandae fuga in sed quod numquam iure. Neque est aperiam fugit magnam dolor aut doloribus.', 122.41, 4.00, 'm', 1, 1, 4, 3, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(19, 'Bread', 44.46, 42.54, 'Consectetur quis commodi sunt. Doloribus rerum rem eum et sit qui. Veniam numquam distinctio aspernatur ratione. Aut dolorem nulla id ullam voluptas.', 135.22, 1.00, 'g', 1, 1, 9, 6, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(20, 'Cookie', 15.38, 0.00, 'Optio rerum veritatis neque ut voluptatum. Reiciendis hic et aut quia qui. Error ut omnis excepturi ut eum qui dolorem.', 122.40, 4.00, 'Oz', 1, 0, 1, 1, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(21, 'Tuna steak', 38.89, 0.00, 'Quia unde natus sapiente et esse eum ipsa impedit. Sequi magnam possimus architecto illum temporibus facilis. Harum tenetur dolor quo ex labore.', 371.15, 4.00, 'L', 0, 0, 6, 6, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(22, 'Tuna steak', 19.10, 12.18, 'Ea fuga provident sit dolorum nam ea ut. Aut nihil ea est beatae aspernatur distinctio. Recusandae sequi inventore qui quis consectetur ipsam vel.', 11.15, 4.00, 'm', 0, 1, 6, 4, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(23, 'Aspirin', 26.32, 0.00, 'Rerum ullam ut nam est saepe aut. Quas a laboriosam cumque rem minus labore. Ea adipisci rerum et officia et. Nemo quod et quisquam consequatur.', 125.27, 2.00, 'Oz', 0, 1, 1, 1, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(24, 'Fish', 43.52, 42.21, 'Modi est quisquam consequatur iste magnam perferendis. Est dolor est qui ut animi animi occaecati. Nisi quis ab rerum quasi.', 380.79, 4.00, 'Kg', 0, 0, 9, 2, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(25, 'Aspirin', 32.63, 0.00, 'Commodi quidem optio id et minus eum omnis. Vero quaerat et amet nesciunt asperiores modi. Provident quidem rerum eum consectetur.', 124.01, 6.00, 'L', 1, 1, 2, 5, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(26, 'Cheese', 49.28, 41.05, 'Culpa sint fuga vitae eaque voluptatem at. Nisi et doloribus quo velit. Dolorem consequatur autem quia et explicabo harum. Accusamus omnis reprehenderit dolorem ullam et in sit.', 287.96, 6.00, 'ml', 1, 1, 8, 6, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(27, 'Acarbose', 19.62, 18.01, 'Ea sapiente omnis dolores. Qui sequi tenetur nobis quisquam ullam delectus. Facere aut incidunt id perferendis. Magnam tempore ut quam quis autem omnis.', 178.47, 6.00, 'ml', 1, 1, 9, 1, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(28, 'Eggs', 44.28, 40.29, 'Ea dicta sed sapiente in. Necessitatibus qui tenetur dolorem neque. Est ipsam sunt error distinctio sit facere. Ipsum est sed ut reprehenderit et.', 258.86, 3.00, 'g', 0, 1, 9, 4, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(29, 'Sandwich', 35.83, 27.24, 'Fugiat dolores voluptas facere aut sequi omnis. Molestias ea nulla expedita repellat voluptas voluptatem aut.', 99.15, 6.00, 'm', 1, 0, 5, 3, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(30, 'Cheese', 26.81, 0.00, 'Quam non ut suscipit pariatur. Totam ut labore quisquam blanditiis dolor fugiat minus. Pariatur ipsum harum accusamus odit adipisci.', 35.16, 1.00, 'm', 0, 1, 1, 1, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(31, 'Fish', 40.23, 37.49, 'Eum sit et sint. Aut nobis delectus tenetur magnam. Qui cupiditate temporibus tempore nihil. Quis omnis fugit necessitatibus possimus optio atque aut.', 433.50, 6.00, 'm', 1, 0, 2, 3, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(32, 'Bread', 38.74, 0.00, 'Qui sit explicabo vitae reprehenderit id provident. Magnam sint aspernatur doloribus tenetur aut. Hic et aliquam ea ea. Quasi debitis reprehenderit dignissimos consequatur occaecati sit qui.', 74.86, 3.00, 'Oz', 0, 0, 8, 6, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(33, 'Spaghetti', 14.39, 8.77, 'Tenetur eos sit impedit et eos unde corporis. Natus dolor aut facere quisquam rerum totam. Qui aut aut minima non voluptatem. Quia distinctio id ducimus est sunt.', 89.92, 2.00, 'm', 0, 0, 1, 3, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(34, 'Eggs', 12.60, 8.20, 'Qui nam velit aut autem aut. Non rerum excepturi velit odio. Quisquam et aut illum ullam vel.', 340.95, 6.00, 'ml', 1, 0, 7, 1, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(35, 'Cookie', 28.30, 18.66, 'Est qui dignissimos iure sit laudantium voluptatem. Quibusdam quis officiis illo voluptates. Recusandae sit quia nam cupiditate autem voluptates aut.', 481.14, 1.00, 'Oz', 0, 0, 6, 2, '2020-04-16 17:07:54', '2020-04-16 17:07:54'),
(36, 'Pasta', 48.44, 0.00, 'Neque sit voluptas autem architecto. Nemo est aliquid qui itaque quae id voluptatem. Voluptas et provident aspernatur omnis natus fugit. Atque sunt temporibus dolores.', 8.57, 2.00, 'ml', 0, 1, 6, 4, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(37, 'Rice', 35.55, 0.00, 'Voluptas quis quidem rerum iste vel. Molestias soluta vel dolorem eaque qui autem pariatur. Consequatur eveniet possimus et in aut. Laborum blanditiis quisquam voluptatem ut reprehenderit.', 172.10, 5.00, 'm²', 0, 0, 3, 4, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(38, 'Rice', 36.31, 0.00, 'Qui quia consequatur sapiente exercitationem id est ut. Deserunt dolor nisi ullam voluptas id. Quo labore voluptas quibusdam ut alias. Eos odio numquam optio saepe iure.', 138.25, 3.00, 'Oz', 0, 1, 4, 2, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(39, 'Spaghetti', 41.39, 0.00, 'Id sapiente magnam non et et dolor quasi. Id quia aperiam ipsum amet laborum. Enim laudantium quae officiis tenetur.', 248.75, 5.00, 'Oz', 1, 1, 1, 3, '2020-04-16 17:07:55', '2020-04-16 17:07:55'),
(40, 'Aspirin', 44.80, 37.39, 'Itaque dolores aut perspiciatis et mollitia sit. Aperiam velit non natus aut pariatur rerum sint. Quod tenetur earum velit reiciendis amet necessitatibus. Aperiam vero sit in.', 164.98, 2.00, 'Kg', 1, 0, 6, 4, '2020-04-16 17:07:55', '2020-04-16 17:07:55');

-- --------------------------------------------------------

--
-- Table structure for table `product_orders`
--

DROP TABLE IF EXISTS `product_orders`;
CREATE TABLE IF NOT EXISTS `product_orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `price` double(8,2) NOT NULL DEFAULT '0.00',
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `product_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_orders_product_id_foreign` (`product_id`),
  KEY `product_orders_order_id_foreign` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `product_orders`
--

TRUNCATE TABLE `product_orders`;
-- --------------------------------------------------------

--
-- Table structure for table `product_order_options`
--

DROP TABLE IF EXISTS `product_order_options`;
CREATE TABLE IF NOT EXISTS `product_order_options` (
  `product_order_id` int(10) UNSIGNED NOT NULL,
  `option_id` int(10) UNSIGNED NOT NULL,
  `price` double(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`product_order_id`,`option_id`),
  KEY `product_order_options_option_id_foreign` (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `product_order_options`
--

TRUNCATE TABLE `product_order_options`;
-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` tinyint(3) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reviews_user_id_foreign` (`user_id`),
  KEY `product_reviews_product_id_foreign` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `product_reviews`
--

TRUNCATE TABLE `product_reviews`;
--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `review`, `rate`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 'So she set to work, and very nearly getting up and said, \'That\'s right, Five! Always lay the blame on others!\' \'YOU\'D better not do that again!\' which produced another dead silence. \'It\'s a pun!\'.', 2, 2, 22, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(2, 'Lory. Alice replied very politely, \'if I had not gone far before they saw the Mock Turtle had just succeeded in bringing herself down to her feet in the pool, and the cool fountains. CHAPTER VIII.', 2, 2, 10, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(3, 'I don\'t know,\' he went on in the wind, and the sounds will take care of the accident, all except the King, \'unless it was very provoking to find that she began thinking over all she could do, lying.', 4, 3, 2, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(4, 'Who would not open any of them. However, on the glass table as before, \'and things are worse than ever,\' thought the poor animal\'s feelings. \'I quite agree with you,\' said the Caterpillar. Alice.', 1, 6, 14, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(5, 'Cheshire Cat sitting on a bough of a globe of goldfish she had plenty of time as she could not swim. He sent them word I had our Dinah here, I know I do!\' said Alice to find that she wanted much to.', 1, 6, 23, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(6, 'Five and Seven said nothing, but looked at Alice, as she could, and soon found an opportunity of adding, \'You\'re looking for them, but they were lying on the Duchess\'s cook. She carried the.', 1, 3, 13, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(7, 'Alice did not get dry very soon. \'Ahem!\' said the King said to the Duchess: \'flamingoes and mustard both bite. And the Gryphon as if it began ordering people about like that!\' He got behind him, and.', 2, 6, 23, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(8, 'Please, Ma\'am, is this New Zealand or Australia?\' (and she tried to get her head on her spectacles, and began to repeat it, when a cry of \'The trial\'s beginning!\' was heard in the last time she went.', 2, 4, 8, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(9, 'Mouse, who was gently brushing away some dead leaves that had fluttered down from the roof. There were doors all round her once more, while the Mock Turtle said with some surprise that the mouse to.', 3, 5, 22, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(10, 'Alice began in a minute or two sobs choked his voice. \'Same as if she meant to take out of the court. All this time she had quite a long silence after this, and after a pause: \'the reason is, that.', 3, 3, 18, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(11, 'Cat: now I shall have somebody to talk nonsense. The Queen\'s argument was, that you couldn\'t cut off a head could be NO mistake about it: it was quite pleased to find that the mouse to the game, the.', 3, 5, 9, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(12, 'Alice a good many little girls eat eggs quite as much as she went back to the Classics master, though. He was looking down at her for a few minutes she heard a little wider. \'Come, it\'s pleased so.', 3, 2, 18, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(13, 'Mystery,\' the Mock Turtle: \'crumbs would all wash off in the sea!\' cried the Mouse, in a thick wood. \'The first thing I\'ve got back to the general conclusion, that wherever you go to law: I will.', 2, 5, 14, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(14, 'Soup! \'Beautiful Soup! Who cares for you?\' said the Duchess: \'and the moral of that is--\"Oh, \'tis love, \'tis love, \'tis love, \'tis love, \'tis love, \'tis love, \'tis love, \'tis love, \'tis love, \'tis.', 4, 2, 21, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(15, 'And yet I wish you wouldn\'t mind,\' said Alice: \'three inches is such a thing as \"I get what I was going to dive in among the leaves, which she had but to get in?\' she repeated, aloud. \'I must be the.', 3, 5, 19, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(16, 'WAS a curious appearance in the newspapers, at the March Hare went \'Sh! sh!\' and the pool a little more conversation with her head!\' Those whom she sentenced were taken into custody by the pope, was.', 2, 3, 13, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(17, 'Alice looked down at her own child-life, and the White Rabbit blew three blasts on the top of her head struck against the door, she found herself at last turned sulky, and would only say, \'I am.', 4, 2, 27, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(18, 'Alice, as the hall was very nearly getting up and say \"Who am I to do such a puzzled expression that she never knew so much about a thousand times as large as himself, and this Alice would not open.', 2, 2, 11, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(19, 'I sleep\" is the same thing as a partner!\' cried the Gryphon, and, taking Alice by the pope, was soon left alone. \'I wish I hadn\'t to bring tears into her face. \'Very,\' said Alice: \'besides, that\'s.', 5, 4, 2, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(20, 'I\'ve tried hedges,\' the Pigeon had finished. \'As if I shall fall right THROUGH the earth! How funny it\'ll seem, sending presents to one\'s own feet! And how odd the directions will look! ALICE\'S.', 3, 2, 18, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(21, 'The baby grunted again, so violently, that she was now more than nine feet high. \'Whoever lives there,\' thought Alice, \'it\'ll never do to ask: perhaps I shall ever see such a simple question,\' added.', 3, 5, 14, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(22, 'Duchess, \'and that\'s a fact.\' Alice did not notice this question, but hurriedly went on, \'and most things twinkled after that--only the March Hare. \'He denies it,\' said Alice, a good many little.', 3, 2, 6, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(23, 'Alice, \'shall I NEVER get any older than I am so VERY remarkable in that; nor did Alice think it was,\' he said. (Which he certainly did NOT, being made entirely of cardboard.) \'All right, so far,\'.', 4, 4, 27, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(24, 'There\'s no pleasing them!\' Alice was soon left alone. \'I wish the creatures argue. It\'s enough to get in?\' asked Alice again, for this curious child was very deep, or she fell past it. \'Well!\'.', 3, 1, 4, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(25, 'Alice, rather alarmed at the picture.) \'Up, lazy thing!\' said the King: \'however, it may kiss my hand if it began ordering people about like mad things all this time, as it went. So she went hunting.', 1, 1, 28, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(26, 'I THINK; or is it I can\'t understand it myself to begin again, it was the White Rabbit hurried by--the frightened Mouse splashed his way through the air! Do you think you could only see her. She is.', 3, 1, 14, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(27, 'In another minute the whole thing very absurd, but they were trying which word sounded best. Some of the garden: the roses growing on it (as she had been anxiously looking across the field after it.', 4, 6, 7, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(28, 'I\'M a Duchess,\' she said to the other: the only difficulty was, that you think you could keep it to half-past one as long as there seemed to be full of smoke from one foot up the chimney, and said.', 4, 5, 5, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(29, 'Alice, (she had grown so large in the distance, and she said to the Mock Turtle said: \'no wise fish would go through,\' thought poor Alice, who always took a great crowd assembled about them--all.', 2, 6, 7, '2020-04-16 17:07:56', '2020-04-16 17:07:56'),
(30, 'I think--\' (for, you see, so many lessons to learn! Oh, I shouldn\'t like THAT!\' \'Oh, you foolish Alice!\' she answered herself. \'How can you learn lessons in here? Why, there\'s hardly enough of it in.', 3, 3, 3, '2020-04-16 17:07:56', '2020-04-16 17:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `default` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `roles`
--

TRUNCATE TABLE `roles`;
--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `default`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'admin', 'web', 0, '2018-07-21 15:37:56', '2019-09-07 21:42:01', NULL),
(3, 'manager', 'web', 0, '2019-09-07 21:41:38', '2019-09-07 21:41:38', NULL),
(4, 'client', 'web', 1, '2019-09-07 21:41:54', '2019-09-07 21:41:54', NULL),
(5, 'driver', 'web', 0, '2019-12-15 17:50:21', '2019-12-15 17:50:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `role_has_permissions`
--

TRUNCATE TABLE `role_has_permissions`;
--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(2, 2),
(3, 2),
(3, 3),
(4, 2),
(5, 2),
(5, 3),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(30, 3),
(30, 4),
(30, 5),
(31, 2),
(32, 2),
(33, 2),
(33, 3),
(34, 2),
(34, 3),
(35, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(42, 3),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(48, 2),
(48, 3),
(48, 5),
(50, 2),
(51, 2),
(52, 2),
(52, 3),
(53, 2),
(53, 3),
(54, 2),
(54, 3),
(55, 2),
(55, 3),
(56, 2),
(56, 3),
(57, 2),
(57, 3),
(58, 2),
(58, 3),
(59, 2),
(59, 3),
(60, 2),
(60, 3),
(61, 2),
(61, 3),
(62, 2),
(62, 3),
(63, 2),
(63, 3),
(64, 2),
(64, 3),
(67, 2),
(68, 2),
(69, 2),
(70, 2),
(70, 3),
(71, 2),
(71, 3),
(72, 2),
(72, 3),
(73, 2),
(73, 3),
(74, 2),
(74, 3),
(75, 2),
(75, 3),
(76, 2),
(76, 3),
(77, 2),
(77, 3),
(78, 2),
(78, 3),
(79, 2),
(80, 2),
(80, 3),
(81, 2),
(81, 3),
(82, 2),
(82, 3),
(83, 2),
(83, 3),
(83, 4),
(83, 5),
(85, 2),
(86, 2),
(86, 3),
(86, 4),
(86, 5),
(87, 2),
(88, 2),
(89, 2),
(90, 2),
(91, 2),
(92, 2),
(92, 3),
(92, 4),
(92, 5),
(95, 2),
(96, 2),
(97, 2),
(98, 2),
(98, 3),
(98, 4),
(98, 5),
(103, 2),
(103, 3),
(103, 4),
(103, 5),
(104, 2),
(104, 3),
(104, 4),
(104, 5),
(107, 2),
(107, 3),
(107, 4),
(107, 5),
(108, 2),
(108, 3),
(109, 2),
(109, 3),
(110, 2),
(110, 3),
(111, 2),
(111, 3),
(111, 4),
(111, 5),
(112, 2),
(113, 2),
(113, 3),
(113, 4),
(113, 5),
(114, 2),
(114, 3),
(114, 4),
(114, 5),
(117, 2),
(117, 3),
(117, 4),
(117, 5),
(118, 2),
(119, 2),
(120, 2),
(121, 2),
(122, 2),
(123, 2),
(124, 2),
(129, 2),
(130, 2),
(130, 3),
(130, 5),
(131, 2),
(134, 2),
(134, 3),
(135, 2),
(135, 3),
(137, 2),
(137, 3),
(138, 2),
(144, 2),
(144, 5),
(145, 2),
(145, 3),
(145, 5),
(146, 2),
(146, 3),
(146, 5),
(148, 2),
(149, 2),
(151, 2),
(152, 2),
(152, 3),
(153, 2),
(153, 3),
(155, 2),
(156, 2),
(158, 2),
(159, 2),
(160, 2),
(164, 2),
(164, 3),
(164, 4),
(164, 5),
(165, 2),
(166, 2),
(167, 2),
(168, 2),
(169, 2);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `uploads`
--

TRUNCATE TABLE `uploads`;
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` char(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_brand` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_last_four` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `braintree_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_api_token_unique` (`api_token`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `api_token`, `device_token`, `stripe_id`, `card_brand`, `card_last_four`, `trial_ends_at`, `braintree_id`, `paypal_email`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Michael E. Quinn', 'admin@demo.com', '$2y$10$YOn/Xq6vfvi9oaixrtW8QuM2W0mawkLLqIxL.IoGqrsqOqbIsfBNu', 'PivvPlsQWxPl1bB5KrbKNBuraJit0PrUZekQUgtLyTRuyBq921atFtoR1HuA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'T4PQhFvBcAA7k02f7ejq4I7z7QKKnvxQLV0oqGnuS6Ktz6FdWULrWrzZ3oYn', '2018-08-06 21:58:41', '2019-09-27 06:49:45'),
(2, 'Barbara J. Glanz', 'manager@demo.com', '$2y$10$YOn/Xq6vfvi9oaixrtW8QuM2W0mawkLLqIxL.IoGqrsqOqbIsfBNu', 'tVSfIKRSX2Yn8iAMoUS3HPls84ycS8NAxO2dj2HvePbbr4WHorp4gIFRmFwB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5nysjzVKI4LU92bjRqMUSYdOaIo1EcPC3pIMb6Tcj2KXSUMriGrIQ1iwRdd0', '2018-08-14 16:06:28', '2019-09-25 21:09:35'),
(3, 'Charles W. Abeyta', 'client@demo.com', '$2y$10$EBubVy3wDbqNbHvMQwkj3OTYVitL8QnHvh/zV0ICVOaSbALy5dD0K', 'fXLu7VeYgXDu82SkMxlLPG1mCAXc4EBIx6O5isgYVIKFQiHah0xiOHmzNsBv', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'V6PIUfd8JdHT2zkraTlnBcRSINZNjz5Ou7N0WtUGRyaTweoaXKpSfij6UhqC', '2019-10-12 21:31:26', '2020-03-29 16:44:30'),
(4, 'Robert E. Brock', 'client1@demo.com', '$2y$10$pmdnepS1FhZUMqOaFIFnNO0spltJpziz3j13UqyEwShmLhokmuoei', 'Czrsk9rwD0c75NUPkzNXM2WvbxYHKj8p0nG29pjKT0PZaTgMVzuVyv4hOlte', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-10-15 16:55:39', '2020-03-29 16:59:39'),
(5, 'Sanchez Roberto', 'driver@demo.com', '$2y$10$T/jwzYDJfC8c9CdD5PbpuOKvEXlpv4.RR1jMT0PgIMT.fzeGw67JO', 'OuMsmU903WMcMhzAbuSFtxBekZVdXz66afifRo3YRCINi38jkXJ8rpN0FcfS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2019-12-15 17:49:44', '2020-03-29 16:22:10'),
(6, 'John Doe', 'driver1@demo.com', '$2y$10$YF0jCx2WCQtfZOq99hR8kuXsAE0KSnu5OYSomRtI9iCVguXDoDqVm', 'zh9mzfNO2iPtIxj6k4Jpj8flaDyOsxmlGRVUZRnJqOGBr8IuDyhb3cGoncvS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-03-29 16:28:04', '2020-03-29 16:28:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_markets`
--

DROP TABLE IF EXISTS `user_markets`;
CREATE TABLE IF NOT EXISTS `user_markets` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `market_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`market_id`),
  KEY `user_markets_market_id_foreign` (`market_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Truncate table before insert `user_markets`
--

TRUNCATE TABLE `user_markets`;
--
-- Dumping data for table `user_markets`
--

INSERT INTO `user_markets` (`user_id`, `market_id`) VALUES
(1, 2),
(1, 3),
(1, 5),
(1, 6),
(2, 3),
(2, 4);

-- --------------------------------------------------------

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_options`
--
ALTER TABLE `cart_options`
  ADD CONSTRAINT `cart_options_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_options_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD CONSTRAINT `custom_field_values_custom_field_id_foreign` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD CONSTRAINT `delivery_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `drivers_payouts`
--
ALTER TABLE `drivers_payouts`
  ADD CONSTRAINT `drivers_payouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `driver_markets`
--
ALTER TABLE `driver_markets`
  ADD CONSTRAINT `driver_markets_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `driver_markets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `earnings`
--
ALTER TABLE `earnings`
  ADD CONSTRAINT `earnings_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_faq_category_id_foreign` FOREIGN KEY (`faq_category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorite_options`
--
ALTER TABLE `favorite_options`
  ADD CONSTRAINT `favorite_options_favorite_id_foreign` FOREIGN KEY (`favorite_id`) REFERENCES `favorites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorite_options_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `galleries_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `markets_payouts`
--
ALTER TABLE `markets_payouts`
  ADD CONSTRAINT `markets_payouts_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `market_fields`
--
ALTER TABLE `market_fields`
  ADD CONSTRAINT `market_fields_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `market_fields_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `market_reviews`
--
ALTER TABLE `market_reviews`
  ADD CONSTRAINT `market_reviews_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `market_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_option_group_id_foreign` FOREIGN KEY (`option_group_id`) REFERENCES `option_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_delivery_address_id_foreign` FOREIGN KEY (`delivery_address_id`) REFERENCES `delivery_addresses` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_order_status_id_foreign` FOREIGN KEY (`order_status_id`) REFERENCES `order_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_orders`
--
ALTER TABLE `product_orders`
  ADD CONSTRAINT `product_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_order_options`
--
ALTER TABLE `product_order_options`
  ADD CONSTRAINT `product_order_options_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_order_options_product_order_id_foreign` FOREIGN KEY (`product_order_id`) REFERENCES `product_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_markets`
--
ALTER TABLE `user_markets`
  ADD CONSTRAINT `user_markets_market_id_foreign` FOREIGN KEY (`market_id`) REFERENCES `markets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_markets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;










-- edits by solid solutions

ALTER TABLE `categories` ADD `parent_id` INT NOT NULL DEFAULT '0' AFTER `description`;


--
-- Table structure for table `languages`
--
DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` 
( `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `code` VARCHAR(255) NOT NULL , 
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;


--
-- Truncate table before insert `languages`
--

TRUNCATE TABLE `languages`;
--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`name`, `code`) VALUES
('english', 'EN'),
('arabic', 'AR');

-- --------------------------------------------------------

--
-- Table structure for table `category_translates`
--
DROP TABLE IF EXISTS `category_translates`;
CREATE TABLE IF NOT EXISTS `category_translates` 
( 
 `idoftable` INT NOT NULL AUTO_INCREMENT ,
 `category_id` INT NOT NULL ,
 `name` VARCHAR(255) NOT NULL , 
 `description` TEXT NOT NULL , 
 `language_id` INT NOT NULL , 
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`idoftable`)
) ENGINE = InnoDB;

--
-- Truncate table before insert `category_translates`
--

TRUNCATE TABLE `category_translates`;
--
-- Dumping data for table `category_translates`
--


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- --------------------------------------------------------

--
-- Table structure for table `category_translates`
--

CREATE TABLE `product_translates` 
( 
  `idoftable` INT NOT NULL AUTO_INCREMENT ,
 `product_id` INT NOT NULL ,
 `name` VARCHAR(255) NOT NULL , 
 `description` TEXT NOT NULL , 
 `language_id` INT NOT NULL , 
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`idoftable`)
) ENGINE = InnoDB;

-- --------------------------------------------------------

--
-- Table structure for table `market_translates`
--

CREATE TABLE `market_translates` 
( 
  `idoftable` INT NOT NULL AUTO_INCREMENT ,
  `market_id` INT NOT NULL ,
  `language_id` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NOT NULL , 
  `information` TEXT NOT NULL , 
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idoftable`)
) ENGINE = InnoDB;

ALTER TABLE `categories`
  DROP `name`,
  DROP `description`;

ALTER TABLE `products`
  DROP `name`,
  DROP `description`;

ALTER TABLE `order_statuses` CHANGE `status` `state` VARCHAR(127) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` ( 
 `id` INT NOT NULL AUTO_INCREMENT ,
 `code` VARCHAR(255) NOT NULL , 
 `value` DOUBLE NOT NULL , 
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE = InnoDB;


DROP TABLE IF EXISTS `chats` ;

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mess` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `to` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations` ;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_05_26_175145_create_permission_tables', 1),
(4, '2018_06_12_140344_create_media_table', 1),
(5, '2018_06_13_035117_create_uploads_table', 1),
(6, '2018_07_17_180731_create_settings_table', 1),
(7, '2018_07_24_211308_create_custom_fields_table', 1),
(8, '2018_07_24_211327_create_custom_field_values_table', 1),
(9, '2019_08_29_213820_create_fields_table', 1),
(10, '2019_08_29_213821_create_markets_table', 1),
(11, '2019_08_29_213822_create_categories_table', 1),
(12, '2019_08_29_213826_create_option_groups_table', 1),
(13, '2019_08_29_213829_create_faq_categories_table', 1),
(14, '2019_08_29_213833_create_order_statuses_table', 1),
(15, '2019_08_29_213837_create_products_table', 1),
(16, '2019_08_29_213838_create_options_table', 1),
(17, '2019_08_29_213842_create_galleries_table', 1),
(18, '2019_08_29_213847_create_product_reviews_table', 1),
(19, '2019_08_29_213921_create_payments_table', 1),
(20, '2019_08_29_213922_create_delivery_addresses_table', 1),
(21, '2019_08_29_213926_create_faqs_table', 1),
(22, '2019_08_29_213940_create_market_reviews_table', 1),
(23, '2019_08_30_152927_create_favorites_table', 1),
(24, '2019_08_31_111104_create_orders_table', 1),
(25, '2019_09_04_153857_create_carts_table', 1),
(26, '2019_09_04_153858_create_favorite_options_table', 1),
(27, '2019_09_04_153859_create_cart_options_table', 1),
(28, '2019_09_04_153958_create_product_orders_table', 1),
(29, '2019_09_04_154957_create_product_order_options_table', 1),
(30, '2019_09_04_163857_create_user_markets_table', 1),
(31, '2019_10_22_144652_create_currencies_table', 1),
(32, '2019_12_14_134302_create_driver_markets_table', 1),
(33, '2020_03_25_094752_create_drivers_table', 1),
(34, '2020_03_25_094802_create_earnings_table', 1),
(35, '2020_03_25_094809_create_drivers_payouts_table', 1),
(36, '2020_03_25_094817_create_markets_payouts_table', 1),
(37, '2020_03_27_094855_create_notifications_table', 1),
(38, '2020_04_11_135804_create_market_fields_table', 1),
(39, '2020_05_05_205804_create_category_translates_table', 1),
(40, '2020_05_05_212233_create_languages_table', 1),
(41, '2020_05_05_232027_create_product_translates_table', 1),
(42, '2020_05_06_050645_create_market_translates_table', 1),
(43, '2020_05_13_055133_create_promo_codes_table', 1),
(44, '2020_05_16_142510_create_chats_table', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
