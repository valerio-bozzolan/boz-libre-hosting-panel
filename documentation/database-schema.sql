-- MySQL dump 10.15  Distrib 10.0.38-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: hostingclients
-- ------------------------------------------------------
-- Server version	10.0.38-MariaDB-0+deb8u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `aw34w3_bakups`
--

DROP TABLE IF EXISTS `aw34w3_bakups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_bakups` (
  `ID_backup` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `backup_name` varchar(15) NOT NULL,
  `backup_description` varchar(64) NOT NULL,
  PRIMARY KEY (`ID_backup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_database`
--

DROP TABLE IF EXISTS `aw34w3_database`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_database` (
  `database_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `database_name` varchar(64) NOT NULL,
  `database_active` tinyint(1) NOT NULL DEFAULT '1',
  `database_creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`database_ID`),
  UNIQUE KEY `database_name` (`database_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_database_user`
--

DROP TABLE IF EXISTS `aw34w3_database_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_database_user` (
  `database_ID` int(10) unsigned NOT NULL,
  `user_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_ID`,`database_ID`),
  KEY `ID_database` (`database_ID`),
  CONSTRAINT `aw34w3_database_user_ibfk_1` FOREIGN KEY (`database_ID`) REFERENCES `aw34w3_database` (`database_ID`) ON DELETE CASCADE,
  CONSTRAINT `aw34w3_database_user_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `aw34w3_user` (`user_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_domain`
--

DROP TABLE IF EXISTS `aw34w3_domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_domain` (
  `domain_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(64) NOT NULL,
  `domain_active` tinyint(1) NOT NULL DEFAULT '1',
  `domain_born` date NOT NULL,
  `domain_expiration` date NOT NULL,
  PRIMARY KEY (`domain_ID`),
  UNIQUE KEY `domain_name` (`domain_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_domain_user`
--

DROP TABLE IF EXISTS `aw34w3_domain_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_domain_user` (
  `domain_ID` int(10) unsigned NOT NULL,
  `user_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_ID`,`domain_ID`),
  KEY `ID_domain` (`domain_ID`),
  CONSTRAINT `aw34w3_domain_user_ibfk_1` FOREIGN KEY (`domain_ID`) REFERENCES `aw34w3_domain` (`domain_ID`) ON DELETE CASCADE,
  CONSTRAINT `aw34w3_domain_user_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `aw34w3_user` (`user_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_ftp`
--

DROP TABLE IF EXISTS `aw34w3_ftp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_ftp` (
  `domain_ID` int(10) unsigned NOT NULL,
  `ftp_login` varchar(32) NOT NULL,
  `ftp_active` tinyint(1) NOT NULL DEFAULT '1',
  `ftp_password` varchar(64) NOT NULL,
  `ftp_directory` varchar(128) NOT NULL DEFAULT '/',
  `ftp_ulbandwidth` smallint(5) NOT NULL DEFAULT '600' COMMENT 'Upload Kilobytes per second',
  `ftp_dlbandwidth` smallint(5) NOT NULL DEFAULT '600' COMMENT 'Download kilobytes per second',
  `ftp_ipaccess` varchar(15) NOT NULL DEFAULT '*',
  `ftp_quotasize` smallint(5) NOT NULL DEFAULT '500' COMMENT 'Megabytes',
  `ftp_quotafiles` int(11) NOT NULL DEFAULT '0' COMMENT 'Number of files',
  `ftp_comment` tinytext,
  PRIMARY KEY (`domain_ID`,`ftp_login`),
  UNIQUE KEY `FTP_login` (`ftp_login`),
  KEY `domain_name` (`domain_ID`),
  CONSTRAINT `aw34w3_ftp_ibfk_1` FOREIGN KEY (`domain_ID`) REFERENCES `aw34w3_domain` (`domain_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_mailbox`
--

DROP TABLE IF EXISTS `aw34w3_mailbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_mailbox` (
  `domain_ID` int(10) unsigned NOT NULL,
  `mailbox_username` varchar(64) NOT NULL DEFAULT '',
  `mailbox_password` text NOT NULL COMMENT 'doveadm pw -s SHA512-CRYPT',
  `mailbox_receive` tinyint(4) NOT NULL DEFAULT '1',
  `mailbox_reset_token` text,
  `mailbox_description` text,
  PRIMARY KEY (`domain_ID`,`mailbox_username`),
  KEY `ID_domain` (`domain_ID`),
  CONSTRAINT `aw34w3_mailbox_ibfk_1` FOREIGN KEY (`domain_ID`) REFERENCES `aw34w3_domain` (`domain_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `aw34w3_mailbox_simple`
--

DROP TABLE IF EXISTS `aw34w3_mailbox_simple`;
/*!50001 DROP VIEW IF EXISTS `aw34w3_mailbox_simple`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `aw34w3_mailbox_simple` (
  `email` tinyint NOT NULL,
  `domain` tinyint NOT NULL,
  `username` tinyint NOT NULL,
  `receive` tinyint NOT NULL,
  `password` tinyint NOT NULL,
  `path` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `aw34w3_mailforward_mail2mail`
--

DROP TABLE IF EXISTS `aw34w3_mailforward_mail2mail`;
/*!50001 DROP VIEW IF EXISTS `aw34w3_mailforward_mail2mail`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `aw34w3_mailforward_mail2mail` (
  `source` tinyint NOT NULL,
  `destination` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `aw34w3_mailforward_simple`
--

DROP TABLE IF EXISTS `aw34w3_mailforward_simple`;
/*!50001 DROP VIEW IF EXISTS `aw34w3_mailforward_simple`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `aw34w3_mailforward_simple` (
  `source` tinyint NOT NULL,
  `destination` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `aw34w3_mailforwardfrom`
--

DROP TABLE IF EXISTS `aw34w3_mailforwardfrom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_mailforwardfrom` (
  `mailforwardfrom_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mailforwardfrom_username` varchar(32) NOT NULL DEFAULT '',
  `domain_ID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`mailforwardfrom_ID`),
  UNIQUE KEY `mailforwardfrom_username` (`mailforwardfrom_username`,`domain_ID`),
  KEY `ID_domain` (`domain_ID`),
  CONSTRAINT `aw34w3_mailforwardfrom_ibfk_1` FOREIGN KEY (`domain_ID`) REFERENCES `aw34w3_domain` (`domain_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_mailforwardto`
--

DROP TABLE IF EXISTS `aw34w3_mailforwardto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_mailforwardto` (
  `mailforwardfrom_ID` int(10) unsigned NOT NULL,
  `mailforwardto_address` varchar(128) NOT NULL,
  PRIMARY KEY (`mailforwardfrom_ID`,`mailforwardto_address`),
  KEY `mailforward_ID` (`mailforwardfrom_ID`,`mailforwardto_address`),
  CONSTRAINT `aw34w3_mailforwardto_ibfk_1` FOREIGN KEY (`mailforwardfrom_ID`) REFERENCES `aw34w3_mailforwardfrom` (`mailforwardfrom_ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_payments_log`
--

DROP TABLE IF EXISTS `aw34w3_payments_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_payments_log` (
  `ID_client` bigint(20) unsigned NOT NULL,
  `transaction` float(10,2) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  KEY `ID_client` (`ID_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_plans`
--

DROP TABLE IF EXISTS `aw34w3_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_plans` (
  `ID_plan` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `monthlyprice` float(10,2) NOT NULL,
  `max_FTP_creations` int(11) NOT NULL,
  `max_database_creations` int(11) NOT NULL,
  PRIMARY KEY (`ID_plan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aw34w3_user`
--

DROP TABLE IF EXISTS `aw34w3_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aw34w3_user` (
  `user_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_uid` varchar(64) NOT NULL,
  `user_role` enum('user','admin') NOT NULL DEFAULT 'user',
  `user_email` varchar(128) NOT NULL,
  `user_active` tinyint(1) NOT NULL DEFAULT '1',
  `user_name` varchar(64) NOT NULL,
  `user_surname` varchar(64) NOT NULL,
  `user_password` varchar(40) NOT NULL,
  `user_birth` date DEFAULT NULL,
  `user_registration_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_last_login` datetime DEFAULT NULL,
  `user_last_online` datetime DEFAULT NULL,
  `user_reset` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`user_ID`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `user_login` (`user_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `aw34w3_mailbox_simple`
--

/*!50001 DROP TABLE IF EXISTS `aw34w3_mailbox_simple`*/;
/*!50001 DROP VIEW IF EXISTS `aw34w3_mailbox_simple`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `aw34w3_mailbox_simple` AS select concat(`mailbox`.`mailbox_username`,'@',`domain`.`domain_name`) AS `email`,`domain`.`domain_name` AS `domain`,`mailbox`.`mailbox_username` AS `username`,`mailbox`.`mailbox_receive` AS `receive`,`mailbox`.`mailbox_password` AS `password`,concat(replace(`domain`.`domain_name`,'/',''),'/',replace(`mailbox`.`mailbox_username`,'/',''),'/') AS `path` from (`aw34w3_mailbox` `mailbox` join `aw34w3_domain` `domain`) where ((`mailbox`.`domain_ID` = `domain`.`domain_ID`) and (`domain`.`domain_active` = '1')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `aw34w3_mailforward_mail2mail`
--

/*!50001 DROP TABLE IF EXISTS `aw34w3_mailforward_mail2mail`*/;
/*!50001 DROP VIEW IF EXISTS `aw34w3_mailforward_mail2mail`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `aw34w3_mailforward_mail2mail` AS select concat(`mailforwardfrom`.`mailforwardfrom_username`,'@',`domain`.`domain_name`) AS `source`,`mailforwardto`.`mailforwardto_address` AS `destination` from ((`aw34w3_mailforwardfrom` `mailforwardfrom` join `aw34w3_domain` `domain`) join `aw34w3_mailforwardto` `mailforwardto`) where ((`mailforwardfrom`.`domain_ID` = `domain`.`domain_ID`) and (`domain`.`domain_active` = 1) and (`mailforwardto`.`mailforwardfrom_ID` = `mailforwardfrom`.`mailforwardfrom_ID`)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `aw34w3_mailforward_simple`
--

/*!50001 DROP TABLE IF EXISTS `aw34w3_mailforward_simple`*/;
/*!50001 DROP VIEW IF EXISTS `aw34w3_mailforward_simple`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 SQL SECURITY DEFINER */
/*!50001 VIEW `aw34w3_mailforward_simple` AS select concat(`mailforwardfrom`.`mailforwardfrom_username`,'@',`domain`.`domain_name`) AS `source`,group_concat(`mailforwardto`.`mailforwardto_address` separator ',') AS `destination` from ((`aw34w3_mailforwardfrom` `mailforwardfrom` join `aw34w3_domain` `domain`) join `aw34w3_mailforwardto` `mailforwardto`) where ((`mailforwardfrom`.`domain_ID` = `domain`.`domain_ID`) and (`domain`.`domain_active` = 1) and (`mailforwardto`.`mailforwardfrom_ID` = `mailforwardfrom`.`mailforwardfrom_ID`)) group by `mailforwardfrom`.`mailforwardfrom_ID` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-07 16:24:12
