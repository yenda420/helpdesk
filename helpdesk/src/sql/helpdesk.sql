-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Pon 27. kvě 2024, 20:39
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `helpdesk`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `admins`
--

CREATE TABLE `admins` (
  `adminId` int(10) UNSIGNED NOT NULL,
  `adminName` varchar(45) NOT NULL,
  `adminSurname` varchar(45) NOT NULL,
  `adminEmail` varchar(45) NOT NULL,
  `adminPasswd` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `admins`
--

INSERT INTO `admins` (`adminId`, `adminName`, `adminSurname`, `adminEmail`, `adminPasswd`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918'),
(30, 'sales', 'admin', 'sales@admin.com', '6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc'),
(32, 'log', 'admin', 'log@admin.com', '6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc'),
(52, 'Admin', 'LogisticsSales', 'log@sales.admin', '6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc');

-- --------------------------------------------------------

--
-- Struktura tabulky `conversation`
--

CREATE TABLE `conversation` (
  `convoId` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `adminId` int(10) UNSIGNED NOT NULL,
  `ticketId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Vypisuji data pro tabulku `conversation`
--

INSERT INTO `conversation` (`convoId`, `userId`, `adminId`, `ticketId`) VALUES
(2, 88, 1, 125),
(3, 88, 1, 125),
(4, 88, 1, 125),
(5, 88, 1, 125),
(6, 88, 1, 125),
(7, 88, 1, 130);

-- --------------------------------------------------------

--
-- Struktura tabulky `departments`
--

CREATE TABLE `departments` (
  `departmentId` int(10) UNSIGNED NOT NULL,
  `departmentName` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `departments`
--

INSERT INTO `departments` (`departmentId`, `departmentName`) VALUES
(0, 'Unassigned'),
(1, 'Super-admin'),
(2, 'Logistics'),
(16, 'Sales'),
(20, 'Administrations');

-- --------------------------------------------------------

--
-- Struktura tabulky `department_lists`
--

CREATE TABLE `department_lists` (
  `departmentId` int(10) UNSIGNED NOT NULL,
  `adminId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `department_lists`
--

INSERT INTO `department_lists` (`departmentId`, `adminId`) VALUES
(1, 1),
(2, 30),
(2, 32),
(2, 52),
(16, 30),
(16, 32),
(16, 52);

-- --------------------------------------------------------

--
-- Struktura tabulky `messages`
--

CREATE TABLE `messages` (
  `msgId` int(10) UNSIGNED NOT NULL,
  `msgContent` longtext NOT NULL,
  `senderUserId` int(10) UNSIGNED DEFAULT NULL,
  `senderAdminId` int(10) UNSIGNED DEFAULT NULL,
  `conversationId` int(10) UNSIGNED NOT NULL,
  `userReacted` tinyint(1) DEFAULT 0,
  `adminReacted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `requests`
--

CREATE TABLE `requests` (
  `requestId` int(10) NOT NULL,
  `reqName` varchar(45) NOT NULL,
  `reqSurname` varchar(45) NOT NULL,
  `reqEmail` varchar(45) NOT NULL,
  `reqPasswd` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `requests`
--

INSERT INTO `requests` (`requestId`, `reqName`, `reqSurname`, `reqEmail`, `reqPasswd`) VALUES
(140, 'request', 'one', 'request@one.com', '6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc');

-- --------------------------------------------------------

--
-- Struktura tabulky `tickets`
--

CREATE TABLE `tickets` (
  `ticketId` int(10) UNSIGNED NOT NULL,
  `title` varchar(45) NOT NULL,
  `status` enum('Waiting','Pending','Resolved') NOT NULL,
  `resolver` int(10) UNSIGNED DEFAULT NULL,
  `ticketDesc` longtext NOT NULL,
  `ticketDate` date NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `ticketTypeId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `tickets`
--

INSERT INTO `tickets` (`ticketId`, `title`, `status`, `resolver`, `ticketDesc`, `ticketDate`, `userId`, `ticketTypeId`) VALUES
(125, 'My account got lost', 'Pending', 1, 'I tried to log in and...', '2024-03-14', 88, 11),
(129, 'Uvař čaj', 'Waiting', NULL, 'Uvař čaj', '2024-03-18', 110, 7),
(130, 'asdfasdfasfd', 'Resolved', 1, 'asdfasfdasfdasf', '2024-05-26', 88, 7);

-- --------------------------------------------------------

--
-- Struktura tabulky `ticket_types`
--

CREATE TABLE `ticket_types` (
  `ticketTypeId` int(10) UNSIGNED NOT NULL,
  `ticketTypeName` varchar(45) NOT NULL,
  `departmentId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `ticket_types`
--

INSERT INTO `ticket_types` (`ticketTypeId`, `ticketTypeName`, `departmentId`) VALUES
(7, 'Technical Issues', 2),
(8, 'Billing and Payments', 0),
(9, 'Product Inquiries', 16),
(10, 'Complaints and Feedback', 2),
(11, 'Account Management', 16),
(12, 'Policy Questions', 2),
(21, 'Administration problems', 20),
(22, 'Other', 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `userId` int(10) UNSIGNED NOT NULL,
  `userName` varchar(45) NOT NULL,
  `userSurname` varchar(45) NOT NULL,
  `userEmail` varchar(45) NOT NULL,
  `userPasswd` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`userId`, `userName`, `userSurname`, `userEmail`, `userPasswd`) VALUES
(88, 'user3', 'user3', 'user3@user3.com', '6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc'),
(109, 'request', 'three', 'request@three.com', '6892013d1b9cebd6b285cf87a9727cd9705a0234cd6787aa1cbb23932477a8bc'),
(110, 'z', 'n', 'z.n@k.cz', '82cb1afac451095fc29b51f54a7b749bc9d816ded14c9b2518a9dfeb4d772fb6');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`adminId`),
  ADD UNIQUE KEY `adminEmail_UNIQUE` (`adminEmail`);

--
-- Indexy pro tabulku `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`convoId`),
  ADD KEY `FK_convoUsers` (`userId`),
  ADD KEY `FK_convoAdmins` (`adminId`),
  ADD KEY `FK_convoTickets` (`ticketId`);

--
-- Indexy pro tabulku `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`departmentId`);

--
-- Indexy pro tabulku `department_lists`
--
ALTER TABLE `department_lists`
  ADD PRIMARY KEY (`departmentId`,`adminId`),
  ADD KEY `FK_Department_listsAdmins` (`adminId`),
  ADD KEY `FK_Department_listsDepartments` (`departmentId`);

--
-- Indexy pro tabulku `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msgId`),
  ADD KEY `FK_msgUsers` (`senderUserId`),
  ADD KEY `FK_msgAdmins` (`senderAdminId`),
  ADD KEY `FK_msgConvo` (`conversationId`);

--
-- Indexy pro tabulku `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`requestId`),
  ADD UNIQUE KEY `reqEmail_UNIQUE` (`reqEmail`);

--
-- Indexy pro tabulku `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticketId`),
  ADD KEY `FK_Resolver_AdminID` (`resolver`),
  ADD KEY `FK_TicketTicket_types` (`ticketTypeId`),
  ADD KEY `FK_TicketsUsers` (`userId`);

--
-- Indexy pro tabulku `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`ticketTypeId`,`departmentId`),
  ADD KEY `FK_Ticket_typesDepartments` (`departmentId`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userEmail_UNIQUE` (`userEmail`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `admins`
--
ALTER TABLE `admins`
  MODIFY `adminId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pro tabulku `conversation`
--
ALTER TABLE `conversation`
  MODIFY `convoId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pro tabulku `departments`
--
ALTER TABLE `departments`
  MODIFY `departmentId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pro tabulku `messages`
--
ALTER TABLE `messages`
  MODIFY `msgId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pro tabulku `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticketId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT pro tabulku `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `ticketTypeId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `FK_convoAdmins` FOREIGN KEY (`adminId`) REFERENCES `admins` (`adminId`),
  ADD CONSTRAINT `FK_convoTickets` FOREIGN KEY (`ticketId`) REFERENCES `tickets` (`ticketId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_convoUsers` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Omezení pro tabulku `department_lists`
--
ALTER TABLE `department_lists`
  ADD CONSTRAINT `FK_Department_listsAdmins` FOREIGN KEY (`adminId`) REFERENCES `admins` (`adminId`),
  ADD CONSTRAINT `FK_Department_listsDepartments` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`departmentId`);

--
-- Omezení pro tabulku `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `FK_msgAdmins` FOREIGN KEY (`senderAdminId`) REFERENCES `admins` (`adminId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_msgConvo` FOREIGN KEY (`conversationId`) REFERENCES `conversation` (`convoId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_msgUsers` FOREIGN KEY (`senderUserId`) REFERENCES `users` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `FK_Resolver_AdminID` FOREIGN KEY (`resolver`) REFERENCES `admins` (`adminId`),
  ADD CONSTRAINT `FK_TicketTicket_types` FOREIGN KEY (`ticketTypeId`) REFERENCES `ticket_types` (`ticketTypeId`),
  ADD CONSTRAINT `FK_ticketsUsers` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Omezení pro tabulku `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD CONSTRAINT `FK_Ticket_typesDepartments` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`departmentId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
