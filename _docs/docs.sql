-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 14, 2016 at 03:12 AM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `docs`
--

-- --------------------------------------------------------

--
-- Table structure for table `Appointments`
--

CREATE TABLE IF NOT EXISTS `Appointments` (
  `appointmentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `statusID` tinyint(3) unsigned NOT NULL,
  `userID` int(11) NOT NULL,
  `withUserID` int(11) NOT NULL,
  `scheduled` datetime NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `noteID` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`appointmentID`),
  KEY `Note` (`noteID`),
  KEY `User` (`userID`),
  KEY `withUser` (`withUserID`),
  KEY `statusID` (`statusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `Appointments`
--

INSERT INTO `Appointments` (`appointmentID`, `statusID`, `userID`, `withUserID`, `scheduled`, `created`, `noteID`) VALUES
(1, 3, 3, 3, '2016-08-23 07:05:00', '2016-08-21 22:16:30', 1),
(2, 1, 3, 4, '2016-08-23 08:00:00', '2016-08-21 22:17:14', 2),
(3, 1, 19, 5, '2016-10-15 08:00:00', '2016-10-14 17:42:35', 4);

-- --------------------------------------------------------

--
-- Table structure for table `AppointmentStatuses`
--

CREATE TABLE IF NOT EXISTS `AppointmentStatuses` (
  `statusID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`statusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `AppointmentStatuses`
--

INSERT INTO `AppointmentStatuses` (`statusID`, `name`) VALUES
(1, 'Pending'),
(2, 'Open'),
(3, 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `AppointmentSymptoms`
--

CREATE TABLE IF NOT EXISTS `AppointmentSymptoms` (
  `appointmentSymptomsID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `appointmentID` int(11) unsigned NOT NULL,
  `symptomID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`appointmentSymptomsID`),
  KEY `symptomID` (`symptomID`),
  KEY `appointmentID` (`appointmentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `AppointmentSymptoms`
--

INSERT INTO `AppointmentSymptoms` (`appointmentSymptomsID`, `appointmentID`, `symptomID`) VALUES
(1, 1, 2),
(2, 1, 4),
(3, 1, 6),
(4, 2, 2),
(5, 2, 4),
(6, 2, 9),
(7, 3, 4),
(8, 3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `migration_versions`
--

CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migration_versions`
--

INSERT INTO `migration_versions` (`version`) VALUES
('20160420100723'),
('20160421101155'),
('20160421111646'),
('20160421134507'),
('20160424212908'),
('20160424212909'),
('20160425111200'),
('20160425111214'),
('20160425111216'),
('20160425111217'),
('20160425111219'),
('20160425112344'),
('20160425202340'),
('20160425202346'),
('20160427221831'),
('20160429173328'),
('20160501210407'),
('20160501215743'),
('20160501220316'),
('20160505102010'),
('20160507123358'),
('20161014204012'),
('20161015213759');

-- --------------------------------------------------------

--
-- Table structure for table `Notes`
--

CREATE TABLE IF NOT EXISTS `Notes` (
  `noteID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(500) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`noteID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `Notes`
--

INSERT INTO `Notes` (`noteID`, `content`, `created`, `userID`) VALUES
(1, 'test 123', '2016-08-21 22:16:30', 3),
(2, 'test 234', '2016-08-21 22:17:14', 3),
(3, 'reminder to myself', '2016-08-21 22:20:01', 3),
(4, 'ghhg', '2016-10-14 17:42:35', 19),
(5, 'hyhyhy', '2016-10-15 19:08:14', 19),
(6, 'yuiti', '2016-10-15 22:39:04', 3),
(7, 'yuiti', '2016-10-15 22:39:26', 3),
(8, 'yuiti', '2016-10-15 22:39:32', 3),
(9, 'test 14141', '2016-10-15 22:41:41', 3),
(10, 'test 14141', '2016-10-15 22:41:48', 3),
(11, 'jrtjrtjrtjtr', '2016-10-15 22:42:58', 3),
(12, 'test 121fsa', '2016-10-15 22:43:58', 3),
(13, 'wgewgweg', '2016-10-15 23:01:03', 3),
(14, '1234', '2016-11-13 23:59:27', 3);

-- --------------------------------------------------------

--
-- Table structure for table `Ratings`
--

CREATE TABLE IF NOT EXISTS `Ratings` (
  `ratingID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `noteID` int(11) unsigned DEFAULT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `userID` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ratingID`),
  KEY `noteID` (`noteID`),
  KEY `userID` (`userID`),
  KEY `createdBy` (`createdBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Reminders`
--

CREATE TABLE IF NOT EXISTS `Reminders` (
  `reminderID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `noteID` int(11) unsigned NOT NULL,
  `scheduled` datetime NOT NULL,
  `status` tinyint(3) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY (`reminderID`),
  KEY `userID` (`userID`),
  KEY `noteID` (`noteID`),
  KEY `createdByUserID` (`createdBy`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `Reminders`
--

INSERT INTO `Reminders` (`reminderID`, `userID`, `noteID`, `scheduled`, `status`, `created`, `createdBy`) VALUES
(1, 4, 3, '2016-08-23 00:00:00', 0, '2016-08-21 22:20:01', 3),
(2, 3, 5, '2016-10-16 00:00:00', 0, '2016-10-15 19:08:14', 19),
(3, 3, 6, '2016-10-19 00:00:00', 0, '2016-10-15 22:39:04', 3),
(4, 3, 7, '2016-10-19 00:00:00', 0, '2016-10-15 22:39:26', 3),
(5, 3, 8, '2016-10-19 00:00:00', 0, '2016-10-15 22:39:32', 3),
(6, 3, 9, '2016-10-18 00:00:00', 0, '2016-10-15 22:41:41', 3),
(7, 3, 10, '2016-10-18 00:00:00', 0, '2016-10-15 22:41:48', 3),
(8, 3, 11, '2016-10-17 00:00:00', 0, '2016-10-15 22:42:58', 3),
(9, 3, 12, '2016-10-20 00:00:00', 0, '2016-10-15 22:43:58', 3),
(10, 3, 13, '2016-10-18 00:00:00', 0, '2016-10-15 23:01:03', 3),
(11, 3, 14, '2016-11-15 00:00:00', 0, '2016-11-13 23:59:27', 3);

-- --------------------------------------------------------

--
-- Table structure for table `Resources`
--

CREATE TABLE IF NOT EXISTS `Resources` (
  `resourceID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`resourceID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

--
-- Dumping data for table `Resources`
--

INSERT INTO `Resources` (`resourceID`, `name`) VALUES
(3, 'Docs_MainBundle_Controller_DefaultController_indexAction'),
(4, 'Docs_MainBundle_Controller_RemindersController_listAction'),
(5, 'Docs_MainBundle_Controller_UsersController_listAvailableAction'),
(6, 'Docs_MainBundle_Controller_AppointmentsController_listAction'),
(7, 'Docs_MainBundle_Controller_AppointmentsController_listClosedAction'),
(8, 'Docs_MainBundle_Controller_UsersController_listAllAction'),
(9, 'Docs_MainBundle_Controller_UsersController_listAction'),
(10, 'Docs_MainBundle_Controller_AppointmentsController_addAppointmentModalAction'),
(11, 'Docs_MainBundle_Controller_DocumentsController_listDocumentsAction'),
(12, 'Docs_MainBundle_Controller_ProfilePageController_indexAction'),
(13, 'Docs_MainBundle_Controller_RatingController_saveAction'),
(14, 'Docs_MainBundle_Controller_UsersController_listAllGridAction'),
(15, 'Docs_MainBundle_Controller_AppointmentsExportController_exportAction'),
(16, 'Docs_MainBundle_Controller_AppointmentUpdateController_approveAction'),
(17, 'Docs_MainBundle_Controller_AppointmentUpdateController_closeAction'),
(18, 'Docs_MainBundle_Controller_RatingController_listAction'),
(20, 'Docs_MainBundle_Controller_AppointmentUpdateController_declineAction'),
(22, 'Docs_MainBundle_Controller_RemindersController_addReminderModalAction');

-- --------------------------------------------------------

--
-- Table structure for table `RoleResources`
--

CREATE TABLE IF NOT EXISTS `RoleResources` (
  `roleResourceID` int(11) NOT NULL AUTO_INCREMENT,
  `rights` int(11) DEFAULT NULL,
  `resourceID` int(11) NOT NULL,
  `roleID` int(11) NOT NULL,
  PRIMARY KEY (`roleResourceID`),
  KEY `IDX_DAFA81A9B79B5C79` (`resourceID`),
  KEY `IDX_DAFA81A983ACDD40` (`roleID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=57 ;

--
-- Dumping data for table `RoleResources`
--

INSERT INTO `RoleResources` (`roleResourceID`, `rights`, `resourceID`, `roleID`) VALUES
(1, NULL, 3, 1),
(2, NULL, 3, 2),
(3, NULL, 3, 3),
(4, NULL, 4, 1),
(5, NULL, 4, 2),
(6, NULL, 4, 3),
(7, NULL, 5, 1),
(8, NULL, 5, 2),
(9, NULL, 5, 3),
(10, NULL, 6, 1),
(11, NULL, 6, 2),
(12, NULL, 6, 3),
(13, NULL, 7, 1),
(14, NULL, 7, 2),
(15, NULL, 7, 3),
(16, NULL, 8, 1),
(17, NULL, 8, 2),
(18, NULL, 8, 3),
(19, NULL, 9, 1),
(20, NULL, 4, 5),
(21, NULL, 3, 5),
(22, NULL, 6, 5),
(23, NULL, 7, 5),
(24, NULL, 8, 5),
(25, NULL, 9, 5),
(26, NULL, 10, 2),
(27, NULL, 10, 3),
(28, NULL, 11, 2),
(29, NULL, 11, 3),
(30, NULL, 12, 1),
(31, NULL, 12, 2),
(32, NULL, 12, 3),
(33, NULL, 13, 1),
(34, NULL, 13, 2),
(35, NULL, 13, 3),
(36, NULL, 14, 5),
(37, NULL, 14, 1),
(38, NULL, 14, 3),
(39, NULL, 10, 1),
(40, NULL, 15, 1),
(41, NULL, 15, 2),
(42, NULL, 15, 3),
(43, NULL, 15, 5),
(44, NULL, 15, 5),
(45, NULL, 16, 3),
(46, NULL, 17, 3),
(47, NULL, 18, 1),
(48, NULL, 18, 2),
(49, NULL, 18, 3),
(50, NULL, 18, 5),
(51, NULL, 20, 1),
(52, NULL, 20, 2),
(53, NULL, 20, 3),
(54, NULL, 20, 5),
(55, NULL, 22, 5),
(56, NULL, 14, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE IF NOT EXISTS `Roles` (
  `name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `Roles`
--

INSERT INTO `Roles` (`name`, `roleID`) VALUES
('ROLE_ADMIN', 1),
('ROLE_USER', 2),
('ROLE_GOOGLE_USER', 3),
('ROLE_DOC', 5);

-- --------------------------------------------------------

--
-- Table structure for table `Services`
--

CREATE TABLE IF NOT EXISTS `Services` (
  `serviceID` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `serviceKey` varchar(32) NOT NULL,
  PRIMARY KEY (`serviceID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Services`
--

INSERT INTO `Services` (`serviceID`, `name`, `serviceKey`) VALUES
(1, 'api-docs', '098f6bcd4621d373cade4e832627b4f6');

-- --------------------------------------------------------

--
-- Table structure for table `Symptoms`
--

CREATE TABLE IF NOT EXISTS `Symptoms` (
  `symptomID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`symptomID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `Symptoms`
--

INSERT INTO `Symptoms` (`symptomID`, `name`) VALUES
(1, 'Sore throat'),
(2, 'Runny nose'),
(3, 'Vomiting'),
(4, 'Fever'),
(5, 'Cough'),
(6, 'Generally feeling unwell'),
(7, 'Headache'),
(8, 'Earache'),
(9, 'Muscle pain'),
(10, 'Loss of taste and smell'),
(11, 'Chest pain'),
(12, 'Diarrhea');

-- --------------------------------------------------------

--
-- Table structure for table `UserRatings`
--

CREATE TABLE IF NOT EXISTS `UserRatings` (
  `userRatingID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `ratingID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`userRatingID`),
  KEY `userID` (`userID`),
  KEY `ratingID` (`ratingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `UserRoles`
--

CREATE TABLE IF NOT EXISTS `UserRoles` (
  `userRoleID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `roleID` int(11) DEFAULT NULL,
  PRIMARY KEY (`userRoleID`),
  KEY `roleID` (`roleID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Dumping data for table `UserRoles`
--

INSERT INTO `UserRoles` (`userRoleID`, `userID`, `roleID`) VALUES
(1, 1, 1),
(3, 2, 2),
(4, 3, 3),
(5, 4, 5),
(6, 5, 5),
(7, 6, 5),
(8, 7, 5),
(10, 3, 5),
(11, 8, 5),
(12, 9, 5),
(13, 10, 5),
(14, 11, 5),
(15, 12, 5),
(16, 13, 5),
(17, 14, 5),
(18, 15, 5),
(19, 17, 2),
(20, 18, 2),
(21, 19, 2),
(22, 20, 2),
(23, 21, 2),
(24, 22, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `googleID` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `UNIQ_D5428AEDF85E0677` (`username`),
  UNIQUE KEY `UNIQ_D5428AEDE7927C74` (`email`),
  UNIQUE KEY `UNIQ_D5428AED8FFBE0F7` (`salt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `firstName`, `lastName`, `username`, `password`, `email`, `salt`, `is_active`, `googleID`, `created`) VALUES
(1, 'hristo', 'botev', 'hbotev', '$2y$10$f29552efe949a425738bdu3FGONKljnQzMQr1rPbRqLPjxGekxuAK', 'hbotev@gmail.com', 'bS3KpLAQar', 1, '0', '2016-05-02 21:00:00'),
(2, 'hristo', 'botev', 'hbotev2', '$2y$10$5alR2amqY/K0LxcgxQ/5veba3Uxc8edo4M6xN7v0fo9MDm4e2AheC', 'hbotev2@abv.bg', 'a6245234f4a7313fa330c90eb7414043', 1, '0', '2016-05-02 21:00:00'),
(3, 'Hristo', 'Botev', 'Hristo Botev', NULL, 'icoo0oobotev@gmail.com', NULL, 1, '110532046243401050166', '2016-05-02 21:00:00'),
(4, 'Dummy', 'last 1', 'dummyDoc1', '$2y$10$wKQ5Xe5yHT2OmqqEqYHui.oINYAnMaoO2BCSkdBIEjpCdbFhlSkLS', 'dummy@email.com', 'e271390a9e497368fb3e1aea775b8292', 1, '0', '2016-05-02 21:00:00'),
(5, 'Dummy', 'last 2', 'dummyDoc2', '$2y$10$yMmsT8j4ITUOrKt48sECEuOj80gVaJNhe.sZZ6D5.fQGXpTM.NWrm', 'dummy2@email.com', '33d78dd73a1ae69e3eb6bb77212e132a', 1, '0', '2016-05-02 21:00:00'),
(6, 'Dummy', 'last 3', 'dummyDoc3', '$2y$10$/p37CiI1Mk4z.0pjTZbrCufbBUdlf.83aKBqcWPcj2d/w9f.cGZle', 'dummy3@email.com', '48eec844f119889db406d85c24607f8b', 1, '0', '2016-05-02 21:00:00'),
(7, 'Dummy', 'last 4', 'dummyDoc4', '$2y$10$xsXeB/msKajS6RHW6Vbr0.5PJOwtrpBTfD2ncRCTBEG2og1u8tM5C', 'dummy4@email.com', '54d6b71ef8a9986b3825eac28c3de8b1', 1, '0', '2016-05-02 21:00:00'),
(8, 'Dummy', 'last 5', 'dummyDoc5', '$2y$10$0L.ibreV7UrtPNHYzUZZtug9jZD4CI0dyn52SzSVCgQKEU2Us/QvK', 'dummy5@gmail.com', 'dc9bf4dd204eefe0d98b3e36014d4f77', 1, '0', '2016-05-25 18:31:49'),
(9, 'Dummy', 'last6', 'dummyDoc6', '$2y$10$nJvidov9sfJBVe0sALyZteCqbeYc6Tvy2EpGr5McVB.1c0VlnLaPa', 'dummy6@gmail.com', 'b0d5cd29b95c497888dedc13dcda0e2a', 1, '0', '2016-05-25 18:32:26'),
(10, 'Dummy', 'last7', 'dummyDoc7', '$2y$10$YFoEUNeMcQDFlEDpmpvbrOnrgK2m4oJNcln5sbGGMcYezZ3.Z8XUm', 'dummy7@gmail.com', 'b4f46ba62e3ec9187f124dff20babf2b', 1, '0', '2016-05-25 18:32:44'),
(11, 'Dummy', 'last8', 'dummyDoc8', '$2y$10$aDpmz0a5buwPAWUbfdNjs.Uub2hKerxjByzeEXebicXRRhZ.zQh1m', 'dummy8@gmail.com', 'f3a799395053a6d23aaef1d63b22e56a', 1, '0', '2016-05-25 18:33:09'),
(12, 'Dummy', 'last9', 'dummyDoc9', '$2y$10$wfEhMZmxsfMPCZC971Xtne0LALm7u6K4aEdAy/7MlipilJ/6HtdQK', 'dummy9@gmail.com', '5454c6ff1fad4fb2f0fb40bbeb72da9e', 1, '0', '2016-05-25 18:36:50'),
(13, 'Dummy', 'last10', 'dummyDoc10', '$2y$10$nTkzTBqwyDr2ltLJBZwFmeI0lnjWgRCh4onP3kiAANNgsfVOfSx92', 'dummy10@gmail.com', '7426455d89564e71f6da59ff020c0acf', 1, '0', '2016-05-25 18:37:05'),
(14, 'Dummy', 'last11', 'dummyDoc11', '$2y$10$KhABsDElcWLLBTTFxq1iqe5JlCxJUfyupZJe07HuuiU2h17G/OwMS', 'dummy11@gmail.com', 'ecfcf58791bf5a622f42c48bc2da74be', 1, '0', '2016-05-25 18:37:16'),
(15, 'Dummy', 'last12', 'dummyDoc12', '$2y$10$6b6rfvY.Vm5wfSbA2niB2.3Tb1vMAOxKSqYnZYVRRhSeugyWjlKDW', 'dummy12@gmail.com', '3819ff62f36cb9039c81406c65291be0', 1, '0', '2016-05-25 18:37:30'),
(16, 'Dummy', 'test 1234', 'test1234', '$2y$10$4vxiBoNV5qzF37t9BusLVecaEEh5XrXPaHr2dNkldDqu6dDh.s3eu', 'asdfoijasdf@abv.bg', '246c616ecae24dd7db2c585fa50c01b8', 1, '0', '2016-09-07 01:39:06'),
(17, 'qwer', 'asdf', 'asdtasdtas', '$2y$10$M6LMpho.4BG53Pypzwzsle9GU77sefmv6Od.DzL0//3jS02BHsYJy', 'asdfoijasdf1234@abv.bg', 'f5609862078f2bf60c19e602a15652b7', 1, '0', '2016-09-08 05:20:28'),
(18, 'qwrqwrqw', 'asdfasdf', 'new test 1234', '$2y$10$sPsp.B0hxnTxUGhFxDu/NuhIC2CBj0ZZlwZOAFSe/ztaN1fUJSoMW', 'alabala1@a.bv.bg', '1e229807fb60f2e6e17d2e270471b4fa', 1, '0', '2016-09-08 05:24:21'),
(19, 'alabala', 'alsdkjasdf', 'TEst 159595', '$2y$10$0kmLoX0sw4yiOScoCAXPoOicb56gAR7OngjLFpyrioNmU56rjnusG', 'aasdfoij@abv.bg', 'e2742bade69a2f655f78bcf27ec44d67', 1, '0', '2016-09-09 22:45:27'),
(20, 'qwoeij', 'oijfrfrffr', 'new test 15996', '$2y$10$DJtSd/XVPM2iE5xFzF61t.SN4XZOD0pJ/SntXCDhFipdhs8U8olIi', 'asdofij@abv.bg', '6ac24b8a68698616aeedd1310b9d6f3f', 1, '0', '2016-09-09 22:50:14'),
(21, 'fwqoifwj', 'oijfroierf', 'fqwefqwef', '$2y$10$yKa0hDw1SCrhMHc530yl0OD4Iiy0hHqZyAoOWUCpiPgosw2HRDwTe', 'fqfqwefqwef@abv.bg', 'd2e52fde2b269c613e2abf5fa99ed09f', 1, '0', '2016-09-09 22:51:51'),
(22, 'wtoejiwteoji', 'joierwojieo', '7456745', '$2y$10$xxqCer45BuVc1a/qV3bmbeaqILSqXM3dK3VzTHWAYk8r5ma5rc5Jm', 'gwergwe@abv.bg', '4b3b153b4c4131c544ab2f445c2ce56f', 1, '0', '2016-09-09 22:55:22');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Appointments`
--
ALTER TABLE `Appointments`
  ADD CONSTRAINT `fk_appointments_appointment_status1` FOREIGN KEY (`statusID`) REFERENCES `AppointmentStatuses` (`statusID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_appointments_note` FOREIGN KEY (`noteID`) REFERENCES `Notes` (`noteID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_appointments_user1` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_appointments_user2` FOREIGN KEY (`withUserID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `AppointmentSymptoms`
--
ALTER TABLE `AppointmentSymptoms`
  ADD CONSTRAINT `AppointmentSymptoms_ibfk_1` FOREIGN KEY (`AppointmentID`) REFERENCES `Appointments` (`appointmentID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `AppointmentSymptoms_ibfk_2` FOREIGN KEY (`SymptomID`) REFERENCES `Symptoms` (`symptomID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_as_appointment` FOREIGN KEY (`appointmentID`) REFERENCES `Appointments` (`appointmentID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_as_symptom` FOREIGN KEY (`symptomID`) REFERENCES `Symptoms` (`symptomID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Notes`
--
ALTER TABLE `Notes`
  ADD CONSTRAINT `fk_note_user` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Ratings`
--
ALTER TABLE `Ratings`
  ADD CONSTRAINT `fk_rating_notes` FOREIGN KEY (`noteID`) REFERENCES `Notes` (`noteID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `rk_rating_user_1` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `rk_rating_user_2` FOREIGN KEY (`createdBy`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Reminders`
--
ALTER TABLE `Reminders`
  ADD CONSTRAINT `fk_reminder_note` FOREIGN KEY (`noteID`) REFERENCES `Notes` (`noteID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reminder_user_id1` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reminder_user_id2` FOREIGN KEY (`createdBy`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `RoleResources`
--
ALTER TABLE `RoleResources`
  ADD CONSTRAINT `FK_DAFA81A983ACDD40` FOREIGN KEY (`roleID`) REFERENCES `Roles` (`roleID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_DAFA81A9B79B5C79` FOREIGN KEY (`resourceID`) REFERENCES `Resources` (`resourceID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `UserRatings`
--
ALTER TABLE `UserRatings`
  ADD CONSTRAINT `fj_userRating_rating` FOREIGN KEY (`ratingID`) REFERENCES `Ratings` (`ratingID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_userRatings_user` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `UserRoles`
--
ALTER TABLE `UserRoles`
  ADD CONSTRAINT `FK_D2AABFB25FD86D04` FOREIGN KEY (`userID`) REFERENCES `Users` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_D2AABFB283ACDD40` FOREIGN KEY (`roleID`) REFERENCES `Roles` (`roleID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
