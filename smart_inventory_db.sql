-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 10:31 PM
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
-- Database: `smart_inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `action`, `performed_by`, `timestamp`) VALUES
(2, 5, 'Promoted to Manager', 1, '2026-01-28 11:02:15');

-- --------------------------------------------------------

--
-- Table structure for table `inventoryitem`
--

CREATE TABLE `inventoryitem` (
  `ItemID` int(11) NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `SupplierID` int(11) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventoryitem`
--

INSERT INTO `inventoryitem` (`ItemID`, `ItemName`, `Category`, `Quantity`, `Location`, `Price`, `SupplierID`, `Image`, `CreatedAt`, `UpdatedAt`, `is_deleted`) VALUES
(1, 'Aashirvaad Atta 5kg', 'Rice & Grains', 50, 'Vadlamudi', 280.00, 1, 'https://www.bbassets.com/media/uploads/p/xxl/126903_12-aashirvaad-atta-whole-wheat.jpg', '2026-03-25 12:31:43', '2026-03-30 16:01:04', 0),
(2, 'Aashirvaad Rice 5kg', 'Rice & Grains', 60, 'Guntur', 320.00, 1, 'https://www.bbassets.com/media/uploads/p/xxl/40004703_8-aashirvaad-rice.jpg', '2026-03-25 12:31:43', '2026-03-30 16:00:26', 0),
(3, 'Sunfeast Marie Light 100g', 'Snacks', 97, 'Tenali', 20.00, 1, 'https://www.bbassets.com/media/uploads/p/xxl/40223727_9-sunfeast-marie-light-active.jpg', '2026-03-25 12:31:43', '2026-03-30 16:00:54', 0),
(4, 'Sunfeast Dark Fantasy 100g', 'Snacks', 60, 'Chapurel', 35.00, 1, 'https://www.bbassets.com/media/uploads/p/xxl/286082_10-sunfeast-dark-fantasy-choco-fills.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(5, 'Bingo Mad Angles 50g', 'Snacks', 49, 'Vadlamudi', 20.00, 1, 'https://www.bbassets.com/media/uploads/p/xxl/40019249_8-bingo-mad-angles-achaari-masti.jpg', '2026-03-25 12:31:43', '2026-03-30 16:00:09', 0),
(6, 'Bingo Original Style 30g', 'Snacks', 40, 'Guntur', 10.00, 1, 'https://www.bbassets.com/media/uploads/p/xxl/102735_11-bingo-original-style-cream-onion.jpg', '2026-03-25 12:31:43', '2026-03-30 16:01:14', 0),
(7, 'Surf Excel Matic 500g', 'Household', 30, 'Tenali', 95.00, 2, 'https://www.bbassets.com/media/uploads/p/xxl/40004660_14-surf-excel-matic-top-load-detergent-powder.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(8, 'Vim Dishwash Bar 200g', 'Household', 30, 'Chapurel', 25.00, 2, 'https://www.bbassets.com/media/uploads/p/xxl/40063990_10-vim-dishwash-bar.jpg', '2026-03-25 12:31:43', '2026-03-30 16:00:17', 0),
(9, 'Lifebuoy Soap 75g', 'Personal Care', 70, 'Vadlamudi', 28.00, 2, 'https://www.bbassets.com/media/uploads/p/xxl/270555_12-lifebuoy-total-10-soap.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(10, 'Dove Soap 75g', 'Personal Care', 45, 'Guntur', 55.00, 2, 'https://www.bbassets.com/media/uploads/p/xxl/40063604_10-dove-cream-beauty-bathing-bar.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(11, 'Clinic Plus Shampoo 200ml', 'Personal Care', 35, 'Tenali', 85.00, 2, 'https://www.bbassets.com/media/uploads/p/xxl/272066_12-clinic-plus-strength-shine-shampoo.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(12, 'Pepsodent Toothpaste 150g', 'Personal Care', 40, 'Chapurel', 60.00, 2, 'https://www.bbassets.com/media/uploads/p/xxl/272089_11-pepsodent-germicheck-toothpaste.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(13, 'Lakme Face Wash 100ml', 'Personal Care', 25, 'Vadlamudi', 115.00, 2, 'https://www.bbassets.com/media/uploads/p/xxl/40088513_10-lakme-face-wash-blush-glow-strawberry.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(14, 'Britannia Good Day 100g', 'Snacks', 50, 'Guntur', 25.00, 3, 'https://www.bbassets.com/media/uploads/p/xxl/100003536_9-britannia-good-day-butter-biscuits.jpg', '2026-03-25 12:31:43', '2026-03-30 15:59:55', 0),
(15, 'Britannia Bourbon 100g', 'Snacks', 80, 'Tenali', 25.00, 3, 'https://www.bbassets.com/media/uploads/p/xxl/264581_11-britannia-bourbon-the-original-creme-biscuits.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(16, 'Britannia Marie Gold 200g', 'Snacks', 75, 'Chapurel', 30.00, 3, 'https://www.bbassets.com/media/uploads/p/xxl/40001610_9-britannia-marie-gold-biscuits.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(17, 'Britannia Milk Bikis 100g', 'Snacks', 70, 'Vadlamudi', 20.00, 3, 'https://www.bbassets.com/media/uploads/p/xxl/100012364_8-britannia-milk-bikis-milk-cream-biscuits.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(18, 'Britannia NutriChoice 100g', 'Snacks', 50, 'Guntur', 35.00, 3, 'https://www.bbassets.com/media/uploads/p/xxl/40197800_9-britannia-nutrichoice-digestive-biscuits.jpg', '2026-03-25 12:31:43', '2026-03-26 04:10:58', 0),
(19, 'Britannia Bread Loaf', 'Bakery', 30, 'Tenali', 40.00, 3, 'https://www.bbassets.com/media/uploads/p/xxl/40060419_11-britannia-bread-super-soft-sandwich.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(20, 'Maggi Noodles 70g', 'Instant Food', 40, 'Chapurel', 14.00, 4, 'https://www.bbassets.com/media/uploads/p/xxl/266160_14-maggi-masala-instant-noodles-vegetarian.jpg', '2026-03-25 12:31:43', '2026-03-30 16:00:41', 0),
(21, 'Maggi Masala 2 Minute 4pcs', 'Instant Food', 60, 'Vadlamudi', 55.00, 4, 'https://www.bbassets.com/media/uploads/p/xxl/1206569_9-maggi-noodles-masala-4x70-g.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(22, 'Nescafe Classic 50g', 'Beverages', 35, 'Guntur', 125.00, 4, 'https://www.bbassets.com/media/uploads/p/xxl/266287_12-nescafe-classic-coffee.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(23, 'Nescafe Sunrise 100g', 'Beverages', 30, 'Tenali', 155.00, 4, 'https://www.bbassets.com/media/uploads/p/xxl/266291_11-nescafe-sunrise-premium-instant-coffee.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(24, 'KitKat 4 Finger 41.5g', 'Snacks', 80, 'Chapurel', 40.00, 4, 'https://www.bbassets.com/media/uploads/p/xxl/40025116_12-kitkat-4-finger-chocolate.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(25, 'Munch Chocolate 30g', 'Snacks', 60, 'Vadlamudi', 20.00, 4, 'https://www.bbassets.com/media/uploads/p/xxl/270491_11-nestle-munch-chocolate.jpg', '2026-03-25 12:31:43', '2026-03-30 15:59:32', 0),
(26, 'Milkmaid Condensed Milk 400g', 'Dairy', 25, 'Guntur', 105.00, 4, 'https://www.bbassets.com/media/uploads/p/xxl/40003963_10-milkmaid-condensed-milk.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(27, 'Amul Milk Packet 500ml', 'Dairy', 80, 'Tenali', 28.00, 5, 'https://www.bbassets.com/media/uploads/p/xxl/40007594_11-amul-taaza-homogenised-toned-milk.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(28, 'Amul Butter 100g', 'Dairy', 50, 'Chapurel', 55.00, 5, 'https://www.bbassets.com/media/uploads/p/xxl/104860_15-amul-butter-pasteurised.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(29, 'Amul Ghee 500ml', 'Dairy', 30, 'Vadlamudi', 290.00, 5, 'https://www.bbassets.com/media/uploads/p/xxl/40080691_12-amul-pure-ghee.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(30, 'Amul Paneer 200g', 'Dairy', 30, 'Guntur', 85.00, 5, 'https://www.bbassets.com/media/uploads/p/xxl/279588_12-amul-malai-paneer.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:17', 0),
(31, 'Amul Dahi 400g', 'Dairy', 40, 'Tenali', 40.00, 5, 'https://www.bbassets.com/media/uploads/p/xxl/40047047_11-amul-masti-dahi-curd.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:18', 0),
(32, 'Amul Cheese Slices 200g', 'Dairy', 25, 'Chapurel', 130.00, 5, 'https://www.bbassets.com/media/uploads/p/xxl/279587_11-amul-cheese-slices.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:18', 0),
(33, 'Eggs Tray 30pcs', 'Dairy', 20, 'Vadlamudi', 180.00, 5, 'https://www.bbassets.com/media/uploads/p/xxl/40098568_9-fresho-eggs-white-farm.jpg', '2026-03-25 12:31:43', '2026-03-25 12:47:18', 0),
(34, 'Parle-G Gold 200g', 'Snacks', 20, 'Guntur', 20.00, 6, NULL, '2026-03-25 12:31:43', '2026-03-25 12:56:25', 0),
(35, 'Monaco Classic 100g', 'Snacks', 80, 'Tenali', 25.00, 6, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(36, 'Hide & Seek Choco 100g', 'Snacks', 65, 'Chapurel', 30.00, 6, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(37, 'Parle Krackjack 100g', 'Snacks', 70, 'Vadlamudi', 20.00, 6, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(38, 'Parle 20-20 Cashew 100g', 'Snacks', 50, 'Guntur', 35.00, 6, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(39, 'Haldirams Mixture 200g', 'Snacks', 55, 'Tenali', 60.00, 7, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(40, 'Haldirams Aloo Bhujia 200g', 'Snacks', 50, 'Chapurel', 60.00, 7, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(41, 'Haldirams Dal Makhani 300g', 'Instant Food', 35, 'Vadlamudi', 110.00, 7, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(42, 'Haldirams Peanuts 200g', 'Snacks', 45, 'Guntur', 50.00, 7, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(43, 'Haldirams Sev 150g', 'Snacks', 40, 'Tenali', 45.00, 7, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(44, 'Lays Classic Salted 50g', 'Snacks', 95, 'Chapurel', 20.00, 8, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(45, 'Lays Magic Masala 50g', 'Snacks', 90, 'Vadlamudi', 20.00, 8, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(46, 'Kurkure Masala Munch 50g', 'Snacks', 20, 'Guntur', 20.00, 8, NULL, '2026-03-25 12:31:43', '2026-03-25 22:33:59', 0),
(47, 'Pepsi 750ml', 'Beverages', 60, 'Tenali', 40.00, 8, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(48, '7Up 750ml', 'Beverages', 55, 'Chapurel', 40.00, 8, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(49, 'Mirinda Orange 750ml', 'Beverages', 50, 'Vadlamudi', 40.00, 8, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(50, 'Slice Mango 600ml', 'Beverages', 50, 'Guntur', 35.00, 8, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(51, 'Coca-Cola 750ml', 'Beverages', 60, 'Tenali', 45.00, 9, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(52, 'Sprite 750ml', 'Beverages', 55, 'Chapurel', 40.00, 9, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(53, 'Maaza Mango 600ml', 'Beverages', 65, 'Vadlamudi', 35.00, 9, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(54, 'Limca 750ml', 'Beverages', 45, 'Guntur', 40.00, 9, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(55, 'Mineral Water Kinley 1L', 'Beverages', 150, 'Tenali', 15.00, 9, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(56, 'Minute Maid Pulpy Orange 1L', 'Beverages', 35, 'Chapurel', 65.00, 9, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(57, 'MDH Red Chilli Powder 200g', 'Spices', 65, 'Vadlamudi', 55.00, 10, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(58, 'MDH Turmeric Powder 100g', 'Spices', 50, 'Guntur', 35.00, 10, NULL, '2026-03-25 12:31:43', '2026-03-30 15:59:45', 0),
(59, 'MDH Garam Masala 100g', 'Spices', 60, 'Tenali', 65.00, 10, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(60, 'MDH Biryani Masala 50g', 'Spices', 55, 'Chapurel', 40.00, 10, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(61, 'MDH Coriander Powder 200g', 'Spices', 50, 'Vadlamudi', 45.00, 10, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(62, 'MDH Cumin Powder 100g', 'Spices', 45, 'Guntur', 50.00, 10, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(63, 'MDH Sambar Masala 100g', 'Spices', 40, 'Tenali', 55.00, 10, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0),
(64, 'MDH Chana Masala 100g', 'Spices', 40, 'Chapurel', 50.00, 10, NULL, '2026-03-25 12:31:43', '2026-03-25 12:31:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `prediction`
--

CREATE TABLE `prediction` (
  `PredictionID` int(11) NOT NULL,
  `ItemID` int(11) DEFAULT NULL,
  `PredictedRestockDate` date NOT NULL,
  `PredictedQuantity` int(11) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prediction`
--

INSERT INTO `prediction` (`PredictionID`, `ItemID`, `PredictedRestockDate`, `PredictedQuantity`, `CreatedAt`, `UpdatedAt`) VALUES
(32, 1, '2026-05-01', 40, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(33, 1, '2026-06-01', 40, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(34, 1, '2026-07-01', 40, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(35, 1, '2026-08-01', 40, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(36, 1, '2026-09-01', 40, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(37, 1, '2026-10-01', 40, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(38, 3, '2026-05-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(39, 3, '2026-06-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(40, 3, '2026-07-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(41, 3, '2026-08-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(42, 3, '2026-09-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(43, 3, '2026-10-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(44, 34, '2026-05-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(45, 34, '2026-06-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(46, 34, '2026-07-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(47, 34, '2026-08-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(48, 34, '2026-09-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(49, 34, '2026-10-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(50, 20, '2026-05-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(51, 20, '2026-06-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(52, 20, '2026-07-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(53, 20, '2026-08-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(54, 20, '2026-09-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(55, 20, '2026-10-01', 100, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(56, 6, '2026-05-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(57, 6, '2026-06-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(58, 6, '2026-07-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(59, 6, '2026-08-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(60, 6, '2026-09-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(61, 6, '2026-10-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(62, 46, '2026-05-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(63, 46, '2026-06-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(64, 46, '2026-07-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(65, 46, '2026-08-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(66, 46, '2026-09-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(67, 46, '2026-10-01', 80, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(68, 2, '2026-05-01', 35, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(69, 2, '2026-06-01', 35, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(70, 2, '2026-07-01', 35, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(71, 2, '2026-08-01', 35, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(72, 2, '2026-09-01', 35, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(73, 2, '2026-10-01', 35, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(74, 8, '2026-05-01', 55, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(75, 8, '2026-06-01', 55, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(76, 8, '2026-07-01', 55, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(77, 8, '2026-08-01', 55, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(78, 8, '2026-09-01', 55, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(79, 8, '2026-10-01', 55, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(80, 5, '2026-05-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(81, 5, '2026-06-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(82, 5, '2026-07-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(83, 5, '2026-08-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(84, 5, '2026-09-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(85, 5, '2026-10-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(86, 58, '2026-05-01', 70, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(87, 58, '2026-06-01', 70, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(88, 58, '2026-07-01', 70, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(89, 58, '2026-08-01', 70, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(90, 58, '2026-09-01', 70, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(91, 58, '2026-10-01', 70, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(92, 14, '2026-05-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(93, 14, '2026-06-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(94, 14, '2026-07-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(95, 14, '2026-08-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(96, 14, '2026-09-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(97, 14, '2026-10-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(98, 25, '2026-05-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(99, 25, '2026-06-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(100, 25, '2026-07-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(101, 25, '2026-08-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(102, 25, '2026-09-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22'),
(103, 25, '2026-10-01', 90, '2026-04-06 19:19:22', '2026-04-06 19:19:22');

-- --------------------------------------------------------

--
-- Table structure for table `predictions`
--

CREATE TABLE `predictions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `avg_daily_sales` float DEFAULT NULL,
  `max_daily_sales` int(11) DEFAULT NULL,
  `safety_stock` int(11) DEFAULT NULL,
  `reorder_point` int(11) DEFAULT NULL,
  `current_stock` int(11) DEFAULT NULL,
  `decision` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `predictions`
--

INSERT INTO `predictions` (`id`, `product_id`, `avg_daily_sales`, `max_daily_sales`, `safety_stock`, `reorder_point`, `current_stock`, `decision`, `created_at`) VALUES
(13, 1, 40, 40, 280, 840, 50, 'Reorder Now', '2026-04-06 19:34:42'),
(14, 3, 80, 80, 560, 1680, 97, 'Reorder Now', '2026-04-06 19:34:42'),
(15, 34, 100, 100, 700, 2100, 20, 'Reorder Now', '2026-04-06 19:34:42'),
(16, 20, 100, 100, 700, 2100, 40, 'Reorder Now', '2026-04-06 19:34:42'),
(17, 6, 80, 80, 560, 1680, 40, 'Reorder Now', '2026-04-06 19:34:42'),
(18, 46, 80, 80, 560, 1680, 20, 'Reorder Now', '2026-04-06 19:34:42'),
(19, 2, 35, 35, 245, 735, 60, 'Reorder Now', '2026-04-06 19:34:42'),
(20, 8, 55, 55, 385, 1155, 30, 'Reorder Now', '2026-04-06 19:34:42'),
(21, 5, 90, 90, 630, 1890, 49, 'Reorder Now', '2026-04-06 19:34:42'),
(22, 58, 70, 70, 490, 1470, 50, 'Reorder Now', '2026-04-06 19:34:42'),
(23, 14, 90, 90, 630, 1890, 50, 'Reorder Now', '2026-04-06 19:34:42'),
(24, 25, 90, 90, 630, 1890, 60, 'Reorder Now', '2026-04-06 19:34:42');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `ReportID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ReportType` enum('Sales Report','Inventory Report') NOT NULL,
  `DateRangeStart` date NOT NULL,
  `DateRangeEnd` date NOT NULL,
  `GeneratedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`ReportID`, `UserID`, `ReportType`, `DateRangeStart`, `DateRangeEnd`, `GeneratedAt`) VALUES
(1, 1, '', '2026-01-27', '2026-01-28', '2026-01-28 10:19:15'),
(2, 1, '', '2024-02-27', '2026-01-28', '2026-01-28 10:20:50'),
(3, 1, '', '2026-01-28', '2026-01-28', '2026-01-28 10:21:20'),
(4, 1, '', '2026-01-28', '2026-01-28', '2026-01-28 10:21:33'),
(5, 1, '', '2026-01-28', '2026-01-28', '2026-01-28 10:21:48'),
(6, 1, '', '2026-01-28', '2026-01-28', '2026-01-28 10:22:15'),
(7, 1, '', '2026-01-28', '2026-01-28', '2026-01-28 12:11:12'),
(8, 1, '', '2026-01-28', '2026-01-28', '2026-01-28 12:11:35'),
(9, 1, '', '2026-01-29', '2026-01-29', '2026-01-29 04:57:39'),
(10, 1, '', '2026-01-28', '2026-01-29', '2026-01-29 05:03:06'),
(11, 1, '', '2026-01-28', '2026-01-30', '2026-01-29 05:55:11'),
(12, 1, '', '2026-02-04', '2026-02-04', '2026-02-03 20:34:58'),
(13, 1, '', '2026-01-29', '2026-02-04', '2026-02-04 00:29:55'),
(14, 1, '', '2026-02-01', '2026-02-04', '2026-02-04 04:51:48'),
(15, 1, '', '2026-01-29', '2026-02-04', '2026-02-04 05:26:25'),
(16, 1, '', '2026-02-12', '2026-02-12', '2026-02-12 16:31:35'),
(17, 1, '', '2026-03-04', '2026-03-25', '2026-03-25 00:45:07'),
(18, 1, '', '2026-03-25', '2026-03-26', '2026-03-25 06:56:25'),
(19, 1, '', '2026-03-25', '2026-03-31', '2026-03-25 14:30:36'),
(20, 1, '', '2026-03-25', '2026-03-26', '2026-03-25 22:36:46'),
(21, 5, '', '2026-03-28', '2026-03-31', '2026-03-30 19:32:24');

-- --------------------------------------------------------

--
-- Table structure for table `salestransaction`
--

CREATE TABLE `salestransaction` (
  `TransactionID` int(11) NOT NULL,
  `ItemID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `QuantitySold` int(11) NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL,
  `SaleDate` date NOT NULL,
  `CustomerName` varchar(255) DEFAULT NULL,
  `CustomerEmail` varchar(255) DEFAULT NULL,
  `CustomerPhone` varchar(20) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salestransaction`
--

INSERT INTO `salestransaction` (`TransactionID`, `ItemID`, `UserID`, `QuantitySold`, `TotalPrice`, `SaleDate`, `CustomerName`, `CustomerEmail`, `CustomerPhone`, `CreatedAt`, `UpdatedAt`) VALUES
(20, 1, 1, 40, 11200.00, '2026-03-25', 'ezoo', 'ezo@gmail.com', '9052546255', '2026-03-25 12:55:25', '2026-03-25 12:55:25'),
(21, 3, 1, 80, 1600.00, '2026-03-25', 'samir', 'samir@gmail.com', '8639973692', '2026-03-25 12:55:46', '2026-03-25 12:55:46'),
(22, 34, 1, 100, 2000.00, '2026-03-25', 'yasir', 'y.osman@gmail.com', '9052546259', '2026-03-25 12:56:25', '2026-03-25 12:56:25'),
(23, 20, 6, 100, 1400.00, '2026-03-26', 'Elobid', 'elobid@gmail.com', '9052546259', '2026-03-25 22:04:07', '2026-03-25 22:04:07'),
(24, 6, 1, 80, 800.00, '2026-03-26', 'yaohan', 'yaohan@gmail.com', '9052546259', '2026-03-25 22:32:41', '2026-03-25 22:32:41'),
(25, 46, 1, 80, 1600.00, '2026-03-26', 'osman', 'osman@gmail.com', '8639973695', '2026-03-25 22:33:59', '2026-03-25 22:33:59'),
(27, 2, 6, 35, 11200.00, '2026-03-31', 'mohmmed', 'moh@gmail.com', '8639973675', '2026-03-30 19:02:35', '2026-03-30 19:02:35'),
(28, 8, 6, 55, 1375.00, '2026-03-31', 'samir', 'samir@gmail.com', '8639973692', '2026-03-30 19:02:59', '2026-03-30 19:02:59'),
(29, 5, 6, 90, 1800.00, '2026-03-31', 'ali', 'ali@gmail.com', '9052546255', '2026-03-30 19:03:16', '2026-03-30 19:03:16'),
(30, 58, 6, 70, 2450.00, '2026-03-31', 'yasir', 'y.9009.osman@gmail.com', '9052546256', '2026-03-30 19:03:45', '2026-03-30 19:03:45'),
(31, 14, 6, 90, 2250.00, '2026-03-31', 'yasir', 'y.9009.osman@gmail.com', '8639973692', '2026-03-30 19:05:52', '2026-03-30 19:05:52'),
(32, 25, 6, 90, 1800.00, '2026-03-31', 'Elobid', 'elobid@gmail.com', '9052546255', '2026-03-30 19:06:18', '2026-03-30 19:06:18');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SupplierID` int(11) NOT NULL,
  `SupplierName` varchar(100) NOT NULL,
  `ContactPerson` varchar(100) DEFAULT NULL,
  `ContactEmail` varchar(100) DEFAULT NULL,
  `ContactPhone` varchar(15) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SupplierID`, `SupplierName`, `ContactPerson`, `ContactEmail`, `ContactPhone`, `Address`, `CreatedAt`, `UpdatedAt`, `is_deleted`) VALUES
(16, 'ITC Limited – Guntur Branch', 'Venkata Rao Naidu', 'venkata.rao@itc.in', '9848011111', 'Brodipet, Guntur, Andhra Pradesh', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(17, 'Hindustan Unilever – Vijayawada', 'Srinivas Murthy', 'srinivas.murthy@hul.in', '9848022222', 'MG Road, Vijayawada, Andhra Pradesh', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(18, 'Britannia Industries – Hyderabad', 'Ramesh Chandra', 'ramesh.chandra@britannia.in', '9849033333', 'Kukatpally, Hyderabad, Telangana', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(19, 'Nestlé India – Secunderabad', 'Kiran Kumar Reddy', 'kiran.kumar@nestle.in', '9849044444', 'Secunderabad, Hyderabad, Telangana', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(20, 'Amul – Hyderabad Distribution', 'Laxmi Prasad', 'laxmi.prasad@amul.coop', '9849055555', 'Begumpet, Hyderabad, Telangana', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(21, 'Parle Products – Vijayawada', 'Suresh Babu', 'suresh.babu@parle.in', '9848066666', 'Governorpet, Vijayawada, Andhra Pradesh', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(22, 'Haldirams – Hyderabad Branch', 'Nagarjuna Rao', 'nagarjuna.rao@haldirams.com', '9849077777', 'Ameerpet, Hyderabad, Telangana', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(23, 'PepsiCo India – Guntur', 'Anil Kumar Sharma', 'anil.kumar@pepsico.in', '9848088888', 'Nagarampalem, Guntur, Andhra Pradesh', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(24, 'Coca-Cola India – Vijayawada', 'Mohan Varma', 'mohan.varma@coca-cola.in', '9848099999', 'Benz Circle, Vijayawada, Andhra Pradesh', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(25, 'MDH Spices – Hyderabad', 'Ravi Shankar', 'ravi.shankar@mdhspices.in', '9849010101', 'Dilsukhnagar, Hyderabad, Telangana', '2026-03-25 11:34:28', '2026-03-25 11:34:28', 0),
(26, 'ITC Limited – Guntur Branch', 'Venkata Rao Naidu', 'venkata.rao@itc.in', '9848011111', 'Brodipet, Guntur, Andhra Pradesh', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0),
(27, 'Hindustan Unilever – Vijayawada', 'Srinivas Murthy', 'srinivas.murthy@hul.in', '9848022222', 'MG Road, Vijayawada, Andhra Pradesh', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0),
(28, 'Britannia Industries – Hyderabad', 'Ramesh Chandra', 'ramesh.chandra@britannia.in', '9849033333', 'Kukatpally, Hyderabad, Telangana', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0),
(29, 'Nestlé India – Secunderabad', 'Kiran Kumar Reddy', 'kiran.kumar@nestle.in', '9849044444', 'Secunderabad, Hyderabad, Telangana', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0),
(30, 'Amul – Hyderabad Distribution', 'Laxmi Prasad', 'laxmi.prasad@amul.coop', '9849055555', 'Begumpet, Hyderabad, Telangana', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0),
(31, 'Parle Products – Vijayawada', 'Suresh Babu', 'suresh.babu@parle.in', '9848066666', 'Governorpet, Vijayawada, Andhra Pradesh', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0),
(32, 'Haldirams – Hyderabad Branch', 'Nagarjuna Rao', 'nagarjuna.rao@haldirams.com', '9849077777', 'Ameerpet, Hyderabad, Telangana', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0),
(33, 'PepsiCo India – Guntur', 'Anil Kumar Sharma', 'anil.kumar@pepsico.in', '9848088888', 'Nagarampalem, Guntur, Andhra Pradesh', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0),
(34, 'Coca-Cola India – Vijayawada', 'Mohan Varma', 'mohan.varma@coca-cola.in', '9848099999', 'Benz Circle, Vijayawada, Andhra Pradesh', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0),
(35, 'MDH Spices – Hyderabad', 'Ravi Shankar', 'ravi.shankar@mdhspices.in', '9849010101', 'Dilsukhnagar, Hyderabad, Telangana', '2026-03-25 12:32:11', '2026-03-25 12:32:11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_activity_log`
--

CREATE TABLE `supplier_activity_log` (
  `log_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Role` enum('Admin','Manager','Employee') NOT NULL,
  `FirstName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `ProfileImage` varchar(255) DEFAULT NULL,
  `JobTitle` varchar(100) DEFAULT NULL,
  `Department` varchar(100) DEFAULT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role_assigned_by` int(11) DEFAULT NULL,
  `role_assigned_at` timestamp NULL DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Email`, `Role`, `FirstName`, `LastName`, `PhoneNumber`, `Address`, `DateOfBirth`, `ProfileImage`, `JobTitle`, `Department`, `Gender`, `CreatedAt`, `UpdatedAt`, `role_assigned_by`, `role_assigned_at`, `otp`, `otp_expiry`) VALUES
(1, 'Admin', '$2y$10$CNTbouITG2GEG2.x.7mAMOZ09oI5wyrdKfTDabL9Z1vYnKeLicZTO', 'admin@gmail.com', 'Admin', 'Yasir', 'Osman', '9052546255', 'Vadlmudi, Guntur, Andhra Pradesh,India', '2002-08-11', '1_1769596946_WhatsApp Image 2026-01-28 at 4.11.59 PM.jpeg', 'Data Analysis', 'Analytics', 'Male', '2024-10-05 10:07:11', '2026-03-25 00:32:18', NULL, NULL, NULL, NULL),
(5, 'yasir912', '$2y$10$oETTYTWnAnWmC94z9.S.7exv4Lt.y..4uM7WYVVpTBQIObUM6C2wK', 'y.9009.osman@gmail.com', 'Manager', 'Yasir', 'Osman', '8008576988', 'Portsudan, Sudan', '2002-08-12', '5_1769599983_WhatsApp Image 2026-01-28 at 4.11.59 PM.jpeg', 'HOD', 'Analytics', 'Male', '2026-01-28 01:01:47', '2026-03-30 19:10:38', 1, '2026-01-28 11:02:15', NULL, NULL),
(6, 'elobaid', '$2y$10$Y/PgLCOZRGxQDC/q.WC5/uDPfKiUhE24ubTcRU73iAxBcT6TMuyVq', 'elo@gmail.com', 'Employee', 'elobaid', 'babiker', NULL, NULL, NULL, '6_1774421375_134124711121043502.jpg', NULL, NULL, 'Male', '2026-01-28 06:14:54', '2026-04-06 20:26:59', NULL, NULL, NULL, NULL),
(7, 'yasir', '$2y$10$modx4o1PD8If5k6dSOjjOuWWQZIgj6VctEmdkixkafW0cd.2Uun9O', 'yasirosman912@gmail.com', 'Employee', 'Yasir', 'Osman', NULL, NULL, NULL, '7_1774421485_Screenshot 2025-04-16 234058.jpg', NULL, NULL, 'Male', '2026-01-30 05:28:06', '2026-03-30 19:07:48', NULL, NULL, NULL, NULL),
(8, 'alia123', '$2y$10$T/PcPB.4wcFJubanEKhQeOGvBe/Bu.LGsRYK9Q8EAYX/bKZLzOnW2', 'smart.inventory.demo@gmail.com', 'Employee', 'alia', 'oihf', NULL, NULL, NULL, NULL, NULL, NULL, 'Male', '2026-01-30 06:01:54', '2026-01-30 06:10:58', NULL, NULL, '864958', '2026-01-30 07:20:58'),
(9, 'izoo', '$2y$10$0KemFmtAE3y2dtjLGN3QaOGwO0gid//ilTOWIjSWXo8fzH5HSqceu', 'izzaldinbadawi@gmail.com', 'Employee', 'izo', 'badawi', NULL, NULL, NULL, NULL, NULL, NULL, 'Other', '2026-02-04 05:10:21', '2026-02-04 05:10:21', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_performed_by` (`performed_by`);

--
-- Indexes for table `inventoryitem`
--
ALTER TABLE `inventoryitem`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `SupplierID` (`SupplierID`);

--
-- Indexes for table `prediction`
--
ALTER TABLE `prediction`
  ADD PRIMARY KEY (`PredictionID`),
  ADD KEY `idx_item_id` (`ItemID`);

--
-- Indexes for table `predictions`
--
ALTER TABLE `predictions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `idx_user_id` (`UserID`),
  ADD KEY `idx_report_type` (`ReportType`);

--
-- Indexes for table `salestransaction`
--
ALTER TABLE `salestransaction`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `idx_item_id` (`ItemID`),
  ADD KEY `idx_user_id` (`UserID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SupplierID`),
  ADD KEY `idx_supplier_name` (`SupplierName`);

--
-- Indexes for table `supplier_activity_log`
--
ALTER TABLE `supplier_activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_supplier_id` (`supplier_id`),
  ADD KEY `idx_performed_by` (`performed_by`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `role_assigned_by` (`role_assigned_by`),
  ADD KEY `idx_email` (`Email`),
  ADD KEY `idx_username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inventoryitem`
--
ALTER TABLE `inventoryitem`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `prediction`
--
ALTER TABLE `prediction`
  MODIFY `PredictionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `predictions`
--
ALTER TABLE `predictions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `salestransaction`
--
ALTER TABLE `salestransaction`
  MODIFY `TransactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `supplier_activity_log`
--
ALTER TABLE `supplier_activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`UserID`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_log_ibfk_2` FOREIGN KEY (`performed_by`) REFERENCES `user` (`UserID`) ON DELETE SET NULL;

--
-- Constraints for table `inventoryitem`
--
ALTER TABLE `inventoryitem`
  ADD CONSTRAINT `inventoryitem_ibfk_1` FOREIGN KEY (`SupplierID`) REFERENCES `supplier` (`SupplierID`) ON DELETE SET NULL;

--
-- Constraints for table `prediction`
--
ALTER TABLE `prediction`
  ADD CONSTRAINT `prediction_ibfk_1` FOREIGN KEY (`ItemID`) REFERENCES `inventoryitem` (`ItemID`) ON DELETE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `salestransaction`
--
ALTER TABLE `salestransaction`
  ADD CONSTRAINT `salestransaction_ibfk_1` FOREIGN KEY (`ItemID`) REFERENCES `inventoryitem` (`ItemID`) ON DELETE CASCADE,
  ADD CONSTRAINT `salestransaction_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_activity_log`
--
ALTER TABLE `supplier_activity_log`
  ADD CONSTRAINT `supplier_activity_log_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`SupplierID`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_activity_log_ibfk_2` FOREIGN KEY (`performed_by`) REFERENCES `user` (`UserID`) ON DELETE SET NULL;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_assigned_by`) REFERENCES `user` (`UserID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
