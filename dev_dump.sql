-- MySQL dump 10.13  Distrib 5.1.58, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: bender
-- ------------------------------------------------------
-- Server version	5.1.58-1ubuntu1

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
-- Table structure for table `bender_addresses`
--

DROP TABLE IF EXISTS `bender_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_addresses` (
  `id_address` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `street` varchar(140) NOT NULL,
  `zip_code` int(5) unsigned zerofill NOT NULL,
  `city` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  PRIMARY KEY (`id_address`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bender_addresses`
--

LOCK TABLES `bender_addresses` WRITE;
/*!40000 ALTER TABLE `bender_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `bender_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bender_person_address`
--

DROP TABLE IF EXISTS `bender_person_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_person_address` (
  `id_person` int(11) NOT NULL,
  `id_address` int(11) unsigned NOT NULL,
  `type` int(2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `id_person` (`id_person`,`id_address`),
  KEY `id_person_2` (`id_person`),
  KEY `id_address` (`id_address`),
  CONSTRAINT `bender_person_address_ibfk_2` FOREIGN KEY (`id_address`) REFERENCES `bender_addresses` (`id_address`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `bender_person_address_ibfk_1` FOREIGN KEY (`id_person`) REFERENCES `bender_persons` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bender_person_address`
--

LOCK TABLES `bender_person_address` WRITE;
/*!40000 ALTER TABLE `bender_person_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `bender_person_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bender_persons`
--

DROP TABLE IF EXISTS `bender_persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_persons` (
  `id_person` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE latin1_spanish_ci NOT NULL COMMENT 'Nombre',
  `last_name` varchar(100) COLLATE latin1_spanish_ci NOT NULL COMMENT 'apellidos',
  `birth_date` date NOT NULL COMMENT 'fecha de nacimiento',
  PRIMARY KEY (`id_person`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bender_persons`
--

LOCK TABLES `bender_persons` WRITE;
/*!40000 ALTER TABLE `bender_persons` DISABLE KEYS */;
/*!40000 ALTER TABLE `bender_persons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bender_posts`
--

DROP TABLE IF EXISTS `bender_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_posts` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `title` varchar(150) COLLATE latin1_spanish_ci NOT NULL COMMENT 'titulo del post',
  `content` text COLLATE latin1_spanish_ci NOT NULL COMMENT 'Contenido del post',
  `slug` varchar(170) COLLATE latin1_spanish_ci NOT NULL COMMENT 'slug, util para permalinks',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_post`),
  UNIQUE KEY `slug` (`slug`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `bender_posts_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `bender_users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bender_posts`
--

LOCK TABLES `bender_posts` WRITE;
/*!40000 ALTER TABLE `bender_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `bender_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bender_users`
--

DROP TABLE IF EXISTS `bender_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `id_person` int(11) NOT NULL,
  `username` varchar(30) COLLATE latin1_spanish_ci NOT NULL COMMENT 'Nombre de usuario',
  `password` varchar(60) COLLATE latin1_spanish_ci NOT NULL COMMENT 'Password del usuario',
  `is_admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `id_person_2` (`id_person`),
  KEY `id_person` (`id_person`),
  CONSTRAINT `bender_users_ibfk_1` FOREIGN KEY (`id_person`) REFERENCES `bender_persons` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bender_users`
--

LOCK TABLES `bender_users` WRITE;
/*!40000 ALTER TABLE `bender_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `bender_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bender_workers`
--

DROP TABLE IF EXISTS `bender_workers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bender_workers` (
  `id_worker` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_person` int(11) NOT NULL COMMENT 'Id de la persona ',
  `hired_at` date NOT NULL COMMENT 'La fecha de contratación del trabajador',
  `salary` float DEFAULT '1000' COMMENT 'El sueldo del trabajador',
  PRIMARY KEY (`id_worker`),
  KEY `id_person` (`id_person`),
  CONSTRAINT `bender_workers_ibfk_1` FOREIGN KEY (`id_person`) REFERENCES `bender_persons` (`id_person`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bender_workers`
--

LOCK TABLES `bender_workers` WRITE;
/*!40000 ALTER TABLE `bender_workers` DISABLE KEYS */;
/*!40000 ALTER TABLE `bender_workers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-01-27 18:52:17
