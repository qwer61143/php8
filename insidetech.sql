-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-03-14 02:25:23
-- 伺服器版本： 10.4.27-MariaDB
-- PHP 版本： 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `insidetech`
--

-- --------------------------------------------------------

--
-- 資料表結構 `goods`
--

CREATE TABLE `goods` (
  `good_id` int(11) NOT NULL,
  `good_name` varchar(30) NOT NULL,
  `good_price` int(11) NOT NULL,
  `good_sold` int(11) DEFAULT 0,
  `good_total` int(11) NOT NULL,
  `good_uptime` datetime DEFAULT NULL,
  `good_info` varchar(100) DEFAULT NULL,
  `good_pic` varchar(100) DEFAULT NULL,
  `good_seller` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` datetime NOT NULL,
  `order_good_id` varchar(200) NOT NULL,
  `shipping_address` varchar(50) NOT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `order_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `order_item`
--

CREATE TABLE `order_item` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `seller`
--

CREATE TABLE `seller` (
  `seller_id` int(11) NOT NULL,
  `rating` float DEFAULT NULL,
  `goods` varchar(200) DEFAULT '" "'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `userdata`
--

CREATE TABLE `userdata` (
  `u_id` int(11) NOT NULL,
  `u_name` varchar(20) NOT NULL,
  `u_password` varchar(60) NOT NULL,
  `u_cname` varchar(20) DEFAULT NULL,
  `u_sex` enum('F','M') DEFAULT NULL,
  `u_birthday` date DEFAULT NULL,
  `u_phone` varchar(20) NOT NULL,
  `u_email` varchar(30) NOT NULL,
  `u_adress` varchar(30) DEFAULT NULL,
  `u_level` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `u_jointime` datetime NOT NULL,
  `u_logintime` datetime NOT NULL,
  `u_logins` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`good_id`),
  ADD KEY `good_seller` (`good_seller`);

--
-- 資料表索引 `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_order_customer` (`customer_id`);

--
-- 資料表索引 `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- 資料表索引 `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`seller_id`);

--
-- 資料表索引 `userdata`
--
ALTER TABLE `userdata`
  ADD PRIMARY KEY (`u_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `goods`
--
ALTER TABLE `goods`
  MODIFY `good_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `order_item`
--
ALTER TABLE `order_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `userdata`
--
ALTER TABLE `userdata`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`good_seller`) REFERENCES `seller` (`seller_id`);

--
-- 資料表的限制式 `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_customer` FOREIGN KEY (`customer_id`) REFERENCES `userdata` (`u_id`);

--
-- 資料表的限制式 `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `goods` (`good_id`);

--
-- 資料表的限制式 `seller`
--
ALTER TABLE `seller`
  ADD CONSTRAINT `fk_seller_seller` FOREIGN KEY (`seller_id`) REFERENCES `userdata` (`u_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
