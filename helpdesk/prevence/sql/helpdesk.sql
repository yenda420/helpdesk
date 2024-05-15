CREATE DATABASE  IF NOT EXISTS `helpdesk` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `helpdesk`;
-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: test
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `adminId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adminName` varchar(45) NOT NULL,
  `adminSurname` varchar(45) NOT NULL,
  `adminEmail` varchar(45) NOT NULL,
  `adminPasswd` varchar(255) NOT NULL,
  PRIMARY KEY (`adminId`),
  UNIQUE KEY `adminEmail_UNIQUE` (`adminEmail`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','admin','admin@admin.com','8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'),(30,'sales','admin','sales@admin.com','6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc'),(32,'log','admin','log@admin.com','6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc'),(52,'Admin','LogisticsSales','log@sales.admin','6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversation`
--

DROP TABLE IF EXISTS `conversation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversation` (
  `convoId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `adminId` int(10) unsigned NOT NULL,
  `ticketId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`convoId`),
  KEY `FK_convoUsers` (`userId`),
  KEY `FK_convoAdmins` (`adminId`),
  KEY `FK_convoTickets` (`ticketId`),
  CONSTRAINT `FK_convoAdmins` FOREIGN KEY (`adminId`) REFERENCES `admins` (`adminId`),
  CONSTRAINT `FK_convoTickets` FOREIGN KEY (`ticketId`) REFERENCES `tickets` (`ticketId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_convoUsers` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversation`
--

LOCK TABLES `conversation` WRITE;
/*!40000 ALTER TABLE `conversation` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department_lists`
--

DROP TABLE IF EXISTS `department_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `department_lists` (
  `departmentId` int(10) unsigned NOT NULL,
  `adminId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`departmentId`,`adminId`),
  KEY `FK_Department_listsAdmins` (`adminId`),
  KEY `FK_Department_listsDepartments` (`departmentId`),
  CONSTRAINT `FK_Department_listsAdmins` FOREIGN KEY (`adminId`) REFERENCES `admins` (`adminId`),
  CONSTRAINT `FK_Department_listsDepartments` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`departmentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department_lists`
--

LOCK TABLES `department_lists` WRITE;
/*!40000 ALTER TABLE `department_lists` DISABLE KEYS */;
INSERT INTO `department_lists` VALUES (1,1),(2,30),(2,32),(2,52),(16,30),(16,32),(16,52);
/*!40000 ALTER TABLE `department_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `departmentId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `departmentName` varchar(45) NOT NULL,
  PRIMARY KEY (`departmentId`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (0,'Unassigned'),(1,'Super-admin'),(2,'Logistics'),(16,'Sales'),(20,'Administrations');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `msgId` int(10) unsigned NOT NULL,
  `msgContent` longtext NOT NULL,
  `senderUserId` int(10) unsigned DEFAULT NULL,
  `senderAdminId` int(10) unsigned DEFAULT NULL,
  `conversationId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`msgId`),
  KEY `FK_msgUsers` (`senderUserId`),
  KEY `FK_msgAdmins` (`senderAdminId`),
  KEY `FK_msgConvo` (`conversationId`),
  CONSTRAINT `FK_msgAdmins` FOREIGN KEY (`senderAdminId`) REFERENCES `admins` (`adminId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_msgConvo` FOREIGN KEY (`conversationId`) REFERENCES `conversation` (`convoId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_msgUsers` FOREIGN KEY (`senderUserId`) REFERENCES `users` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requests` (
  `requestId` int(10) NOT NULL,
  `reqName` varchar(45) NOT NULL,
  `reqSurname` varchar(45) NOT NULL,
  `reqEmail` varchar(45) NOT NULL,
  `reqPasswd` varchar(255) NOT NULL,
  PRIMARY KEY (`requestId`),
  UNIQUE KEY `reqEmail_UNIQUE` (`reqEmail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
INSERT INTO `requests` VALUES (140,'request','one','request@one.com','6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc');
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_types`
--

DROP TABLE IF EXISTS `ticket_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_types` (
  `ticketTypeId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticketTypeName` varchar(45) NOT NULL,
  `departmentId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ticketTypeId`,`departmentId`),
  KEY `FK_Ticket_typesDepartments` (`departmentId`),
  CONSTRAINT `FK_Ticket_typesDepartments` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`departmentId`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_types`
--

LOCK TABLES `ticket_types` WRITE;
/*!40000 ALTER TABLE `ticket_types` DISABLE KEYS */;
INSERT INTO `ticket_types` VALUES (7,'Technical Issues',2),(8,'Billing and Payments',0),(9,'Product Inquiries',16),(10,'Complaints and Feedback',2),(11,'Account Management',16),(12,'Policy Questions',2),(21,'Administration problems',20),(22,'Other',2),(22,'Other',16),(22,'Other',20);
/*!40000 ALTER TABLE `ticket_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `ticketId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `status` enum('Waiting','Pending','Resolved') NOT NULL,
  `resolver` int(10) unsigned DEFAULT NULL,
  `ticketDesc` longtext NOT NULL,
  `ticketDate` date NOT NULL,
  `userId` int(10) unsigned NOT NULL,
  `ticketTypeId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ticketId`),
  KEY `FK_Resolver_AdminID` (`resolver`),
  KEY `FK_TicketTicket_types` (`ticketTypeId`),
  KEY `FK_TicketsUsers` (`userId`),
  CONSTRAINT `FK_Resolver_AdminID` FOREIGN KEY (`resolver`) REFERENCES `admins` (`adminId`),
  CONSTRAINT `FK_TicketTicket_types` FOREIGN KEY (`ticketTypeId`) REFERENCES `ticket_types` (`ticketTypeId`),
  CONSTRAINT `FK_ticketsUsers` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (125,'My account got lost','Waiting',NULL,'I tried to log in and...','2024-03-14',88,11),(129,'Uvař čaj','Waiting',NULL,'Uvař čaj','2024-03-18',110,7);
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `userId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userName` varchar(45) NOT NULL,
  `userSurname` varchar(45) NOT NULL,
  `userEmail` varchar(45) NOT NULL,
  `userPasswd` varchar(255) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userEmail_UNIQUE` (`userEmail`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (88,'user3','user3','user3@user3.com','6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc'),(109,'request','three','request@three.com','6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc'),(110,'z','n','z.n@k.cz','82cb1afac451095fc29b51f54a7b749bc9d816ded14c9b2518a9dfeb4d772fb6');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-15 22:54:00
