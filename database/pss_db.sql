CREATE DATABASE  IF NOT EXISTS `pss_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pss_db`;
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: pss_db
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (3,1,5,1,'2026-02-11 03:19:58','2026-02-11 03:20:03');
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `weight` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int DEFAULT '0',
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`status`),
  KEY `idx_price` (`price`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Lucky Me Beef Na Beef','Instant noodles with beef flavor','Noodles',12.50,'55g',150,'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(2,'Del Monte Tomato Sauce','Filipino style tomato sauce','Canned Goods',28.00,'250g',200,'https://images.unsplash.com/photo-1599639957043-f3aa5c986398?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(3,'Alaska Evaporated Milk','Premium quality evaporated milk','Dairy',45.00,'370ml',180,'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(5,'Pancit Canton Calamansi','Instant pancit canton calamansi flavor','Noodles',13.00,'60g',300,'https://images.unsplash.com/photo-1612927601601-6638404737ce?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(6,'Mang Tomas All Around Sauce','Filipino liver sauce','Condiments',48.00,'330g',100,'https://images.unsplash.com/photo-1472476443507-c7a5948772fc?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(7,'Nestle Bear Brand','Sterilized milk drink','Dairy',22.00,'300ml',250,'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(8,'UFC Banana Catsup','Filipino banana ketchup','Condiments',42.00,'320g',150,'https://images.unsplash.com/photo-1584949602334-204ce8d758ce?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(9,'Spam Classic','Classic pork luncheon meat','Canned Goods',185.00,'340g',80,'https://images.unsplash.com/photo-1600952841320-db92ec4047ca?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(10,'Argentina Corned Beef','Premium corned beef','Canned Goods',55.00,'150g',120,'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(11,'555 Sardines in Tomato Sauce','Sardines in tomato sauce','Canned Goods',24.00,'155g',200,'https://images.unsplash.com/photo-1580476262798-bddd9f4b7369?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(12,'Purefoods Corned Beef','Classic corned beef','Canned Goods',58.00,'175g',140,'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(13,'Monde Nissin Butter Coconut Crackers','Butter coconut flavored crackers','Snacks',45.00,'350g',160,'https://images.unsplash.com/photo-1598214886806-c87b84b7078b?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(14,'Jack n Jill Piattos Cheese','Potato crisps cheese flavor','Snacks',18.00,'40g',280,'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(15,'Oishi Prawn Crackers','Spicy prawn crackers','Snacks',15.00,'60g',300,'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(16,'Kopiko Brown Coffee','3-in-1 instant coffee','Beverages',85.00,'300g',90,'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(17,'Great Taste White Coffee','White coffee 3-in-1','Beverages',78.00,'270g',110,'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(18,'Milo Chocolate Drink','Chocolate malt powder drink','Beverages',165.00,'400g',75,'https://images.unsplash.com/photo-1556881261-c5c1e0a00097?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(19,'Silver Swan Soy Sauce','All purpose soy sauce','Condiments',35.00,'385ml',170,'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(20,'Datu Puti Vinegar','Filipino cane vinegar','Condiments',28.00,'385ml',190,'https://images.unsplash.com/photo-1472476443507-c7a5948772fc?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(21,'NutriAsia Golden Fiesta Oil','Cooking oil','Cooking Essentials',145.00,'1L',100,'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(22,'Magnolia Butter','Premium butter','Dairy',95.00,'200g',60,'https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(23,'Eden Cheese','Filipino favorite cheese','Dairy',78.00,'165g',85,'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(24,'Reno Liver Spread','Classic liver spread','Canned Goods',32.00,'85g',130,'https://images.unsplash.com/photo-1544025162-d76694265947?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(25,'Hunt\'s Pork and Beans','Pork and beans in tomato sauce','Canned Goods',38.00,'175g',145,'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(26,'Royal Peanut Butter Creamy','Creamy peanut butter','Spreads',125.00,'340g',95,'https://images.unsplash.com/photo-1567334539731-8743c68ca3e5?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(27,'Lady\'s Choice Mayonnaise','Real mayonnaise','Condiments',68.00,'220ml',120,'https://images.unsplash.com/photo-1530509677229-e7c7c7a41f1e?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(28,'Rebisco Crackers','Plain crackers','Snacks',35.00,'250g',180,'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(29,'Sky Flakes Crackers','Classic crackers','Snacks',38.00,'250g',200,'https://images.unsplash.com/photo-1601001435866-7dde9becb135?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(30,'Cream-O Chocolate','Chocolate sandwich cookies','Snacks',42.00,'132g',220,'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(31,'Nescafe Classic','Pure coffee','Beverages',125.00,'50g',70,'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(32,'Tang Orange','Powdered juice drink','Beverages',45.00,'175g',110,'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(33,'Zest-O Orange Juice','Ready to drink orange juice','Beverages',12.00,'200ml',250,'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(34,'C2 Green Tea Apple','Green tea drink','Beverages',25.00,'500ml',180,'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(35,'Coca Cola','Softdrink','Beverages',38.00,'1L',150,'https://images.unsplash.com/photo-1554866585-cd94860890b7?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14'),(36,'Sprite','Lemon lime softdrink','Beverages',38.00,'1L',150,'https://images.unsplash.com/photo-1625740234566-ed40fae165d5?w=500','active','2026-02-11 02:56:14','2026-02-11 02:56:14');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middlename` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suffix` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barangay` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `block_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lot_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `terms_agreed` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'John Noel','Del Agua','Orano','','sumi','$2y$10$Y65uTkCVdpf/kEBS04ujfu5Yigd7izpLmOSN9ivhljnm34afmkLZK','johnnoelorano@gmail.com','09923139504','NCR','METRO MANILA','CALOOCAN CITY','171','2','3','1421',1,'2026-02-11 02:47:01','2026-02-11 02:47:01',NULL,'active');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  KEY `idx_user` (`user_id`),
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'pss_db'
--

--
-- Dumping routines for database 'pss_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-11 11:28:59
