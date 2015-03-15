--
--    Copyright 2010-2014 Erik Landsness
--    This file is part of 360 Feedback.
--
--    360 Feedback is free software: you can redistribute it and/or modify
--    it under the terms of the GNU General Public License as published by
--    the Free Software Foundation, either version 3 of the License, or any later version.
--
--    360 Feedback is distributed in the hope that it will be useful,
--    but WITHOUT ANY WARRANTY; without even the implied warranty of
--    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
--    GNU General Public License for more details.
--
--    You should have received a copy of the GNU General Public License
--    along with 360 Feedback.  If not, see <http://www.gnu.org/licenses/>.
--
-- MySQL dump 10.13  Distrib 5.5.38, for Linux (x86_64)
--
-- Host: localhost    Database: 360review
-- ------------------------------------------------------
-- Server version	5.5.38

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
-- Table structure for table `tbl_answers`
--

DROP TABLE IF EXISTS `tbl_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_answers` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `reviewer` int(10) unsigned NOT NULL,
  `question` int(10) unsigned NOT NULL,
  `answer` int(2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55396 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_keep_stop_start`
--

DROP TABLE IF EXISTS `tbl_keep_stop_start`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_keep_stop_start` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reviewer` int(10) NOT NULL,
  `data` text NOT NULL,
  `kss_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6372 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_personalities`
--

DROP TABLE IF EXISTS `tbl_personalities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_personalities` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_personalities`
--

LOCK TABLES `tbl_personalities` WRITE;
/*!40000 ALTER TABLE `tbl_personalities` DISABLE KEYS */;
INSERT INTO `tbl_personalities` VALUES (1,'Entrepreneur'),(2,'Competitor'),(3,'Producer'),(4,'Stabilizer'),(5,'Team Builder'),(6,'Creator'),(7,'Performer'),(8,'Attacker'),(9,'Commander'),(10,'Avoider'),(11,'Pleaser'),(12,'Drifter');
/*!40000 ALTER TABLE `tbl_personalities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_questions`
--

DROP TABLE IF EXISTS `tbl_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_questions` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `personality` int(1) NOT NULL,
  `definition` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_questions`
--

LOCK TABLES `tbl_questions` WRITE;
/*!40000 ALTER TABLE `tbl_questions` DISABLE KEYS */;
INSERT INTO `tbl_questions` VALUES (1,'Enthusiastic',1,'Having or showing intense and eager enjoyment, interest, or approval.'),(2,'Inspiring',1,'Fill (someone) with the urge or ability to do or feel something, esp. to do something creative.'),(3,'Rushed',7,'Communicates with urgent haste.'),(4,'Likes the spotlight',7,'Enjoys doing things that make him/her the center of attention.'),(5,'Calm',4,'Not showing or feeling nervousness, anger, or other emotions.'),(6,'Realistic',4,'Having or showing a sensible and practical idea of what can be achieved or expected.'),(7,'Too cautious',10,'Avoids taking risks.  Too attentive to potential problems or dangers.'),(8,'Subdued',10,'Quiet and rather reflective or depressed.  Soft and restrained.'),(9,'Assertive',2,'Having or showing a confident and forceful personality.'),(10,'Honest',2,'Free of deceit and untruthfulness; sincere.'),(11,'Argumentative',8,'Given to expressing divergent or opposite views.'),(12,'Critical',8,'Expressing adverse or disapproving judgments.'),(13,'Supportive',5,'Providing encouragement or emotional help.'),(14,'Warm',5,'Marked by or revealing friendliness or sincerity; cordial.'),(15,'Too nice',11,'Overly pleasing and agreeable in nature.  Goes out of his/her way to please people.'),(16,'Too trusting',11,'Showing or tending to have a belief in a person\'s honesty or sincerity even if there is reason to doubt it.'),(17,'Logical',3,'Characterized by clear, sound reasoning.'),(18,'Focused',3,'Having close or narrow attention; Has deep concentration.'),(19,'Controlling',9,'Maintains influence or authority over others.  Feels the need to be in control of others; micro manages.'),(20,'Inflexible',9,'Not flexible; unwilling to change or compromise.'),(21,'Vivid',6,'Communicates in a way that produces powerful feelings or strong, clear images in the mind.'),(22,'Humorous',6,'Causing lighthearted laughter and amusement; comic.  Having or showing a sense of humor.'),(23,'Disorganized',12,'Unable to plan one\'s activities efficiently.'),(24,'Unclear',12,'Communicates in a way that the purpose is not obvious or definite; ambiguous.');
/*!40000 ALTER TABLE `tbl_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_reviewers`
--

DROP TABLE IF EXISTS `tbl_reviewers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_reviewers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session` int(10) unsigned NOT NULL,
  `email` text NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  `viewed` tinyint(1) NOT NULL DEFAULT '0',
  `self` tinyint(1) NOT NULL DEFAULT '0',
  `rev_key` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4693 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_sessions`
--

DROP TABLE IF EXISTS `tbl_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `employee` text NOT NULL,
  `emp_email` text NOT NULL,
  `manager` text NOT NULL,
  `mgr_email` text NOT NULL,
  `due_date` int(10) unsigned NOT NULL,
  `emp_key` text NOT NULL,
  `mgr_key` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=227 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `view_answers`
--

DROP TABLE IF EXISTS `view_answers`;
/*!50001 DROP VIEW IF EXISTS `view_answers`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `view_answers` (
  `sessid` tinyint NOT NULL,
  `emp_key` tinyint NOT NULL,
  `rev_id` tinyint NOT NULL,
  `done` tinyint NOT NULL,
  `self` tinyint NOT NULL,
  `reviewer` tinyint NOT NULL,
  `question` tinyint NOT NULL,
  `answer` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `view_answers`
--

/*!50001 DROP TABLE IF EXISTS `view_answers`*/;
/*!50001 DROP VIEW IF EXISTS `view_answers`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`cleardata`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_answers` AS select `tbl_sessions`.`id` AS `sessid`,`tbl_sessions`.`emp_key` AS `emp_key`,`tbl_reviewers`.`id` AS `rev_id`,`tbl_reviewers`.`done` AS `done`,`tbl_reviewers`.`self` AS `self`,`tbl_answers`.`reviewer` AS `reviewer`,`tbl_answers`.`question` AS `question`,`tbl_answers`.`answer` AS `answer` from ((`tbl_sessions` left join `tbl_reviewers` on((`tbl_sessions`.`id` = `tbl_reviewers`.`session`))) left join `tbl_answers` on((`tbl_reviewers`.`id` = `tbl_answers`.`reviewer`))) */;
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

