-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: gestion_courrier
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.24.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accuse_receptions`
--

DROP TABLE IF EXISTS `accuse_receptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accuse_receptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_enregistrement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_accuse_reception` date DEFAULT NULL,
  `date_reception` date NOT NULL,
  `receptionne_par` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_expediteur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `objet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resume` text COLLATE utf8mb4_unicode_ci,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `commentaires` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('reçu','en attente','traité') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reçu',
  `archive` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_archive` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type_courrier` enum('externe','interne') COLLATE utf8mb4_unicode_ci DEFAULT 'externe',
  `service_destinataire_id` bigint unsigned DEFAULT NULL,
  `service_concerne` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accuse_receptions_numero_enregistrement_unique` (`numero_enregistrement`),
  KEY `fk_user_id` (`user_id`),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accuse_receptions`
--

LOCK TABLES `accuse_receptions` WRITE;
/*!40000 ALTER TABLE `accuse_receptions` DISABLE KEYS */;
INSERT INTO `accuse_receptions` VALUES (81,'123/34',NULL,'2025-04-12','Sarah','DP03','brayane','test','test',NULL,NULL,'traité',NULL,NULL,'2025-04-12 10:57:26','2025-04-20 15:33:06','externe',NULL,NULL,8),(99,'760/08',NULL,'2025-08-02','Ozias','DG023/SEC/DRC','Ozias','accusé de reception','Félicitation',NULL,'transmis','traité','Expositions','en cours','2025-08-02 18:15:52','2025-08-03 19:10:51','externe',NULL,NULL,9),(100,'123/349',NULL,'2025-08-03','MOI','OK','OK','OK','OK',NULL,NULL,'reçu',NULL,NULL,'2025-08-03 20:41:36','2025-08-03 20:42:34','externe',NULL,NULL,9);
/*!40000 ALTER TABLE `accuse_receptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `annexe_messages`
--

DROP TABLE IF EXISTS `annexe_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `annexe_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message_id` bigint unsigned NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `annexe_messages_message_id_foreign` (`message_id`),
  CONSTRAINT `annexe_messages_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `annexe_messages`
--

LOCK TABLES `annexe_messages` WRITE;
/*!40000 ALTER TABLE `annexe_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `annexe_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `annexes`
--

DROP TABLE IF EXISTS `annexes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `annexes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `accuse_de_reception_id` bigint unsigned DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reponse_id` bigint unsigned DEFAULT NULL,
  `reponse_finale_id` int DEFAULT NULL,
  `telegramme_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_accuse_de_reception_id` (`accuse_de_reception_id`),
  KEY `fk_telegramme_id` (`telegramme_id`),
  KEY `fk_annexes_reponse_finale` (`reponse_finale_id`),
  CONSTRAINT `fk_accuse_de_reception_id` FOREIGN KEY (`accuse_de_reception_id`) REFERENCES `accuse_receptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_annexes_reponse_finale` FOREIGN KEY (`reponse_finale_id`) REFERENCES `reponses_finales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_telegramme_id` FOREIGN KEY (`telegramme_id`) REFERENCES `telegrammes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `annexes`
--

LOCK TABLES `annexes` WRITE;
/*!40000 ALTER TABLE `annexes` DISABLE KEYS */;
INSERT INTO `annexes` VALUES (6,NULL,'annexes/jFTYYyqadPhVXlamfh9hN3dE6eazTcRjFMWfKGrh.pdf','2025-03-07 16:40:05','2025-03-07 16:40:05',NULL,NULL,NULL),(7,NULL,'annexes/Zn51V20MLDDKYYCukYdkZbJX2eu88Ba4hENrtbeQ.pdf','2025-03-07 17:21:09','2025-03-07 17:21:09',NULL,NULL,NULL),(8,NULL,'annexes/lz9b2nhf6RvjU3RhVGldITb3CyEaWJCoCQivbZYi.pdf','2025-03-09 09:23:34','2025-03-09 09:23:34',NULL,NULL,NULL),(9,NULL,'annexes/tY9Wivy93GNxzWWetnPFsJSMGmtlN30XAQoxTelX.pdf','2025-03-09 09:25:44','2025-03-09 09:25:44',NULL,NULL,NULL),(10,NULL,'annexes/9ynfWJyubhPq9tt9z0MtofIKwVTiWelzZRVCS1U8.docx','2025-03-09 09:34:00','2025-03-09 09:34:00',NULL,NULL,NULL),(11,NULL,'annexes/wRtI2UswuhVBGdshZiyP0gBl0MkFRi2QYU6QVXSG.pdf','2025-03-09 10:00:18','2025-03-09 10:00:18',NULL,NULL,NULL),(40,NULL,'annexes/5LOk8XWVjsKBb84nv7z07nXk8iCDHEqSORSGPfHR.pdf','2025-03-13 04:44:23','2025-03-13 04:44:23',3,NULL,NULL),(41,NULL,'annexes/AyKdGaYLchQVZgpMvYUxfZ14QEu7lMzmAiMwPBbH.pdf','2025-03-13 05:38:15','2025-03-13 05:38:15',4,NULL,NULL),(42,NULL,'annexes/ne4F4VZNb5007CZ8cADOeOoMi2KBY62NWusZ17Ch.pdf','2025-03-13 05:58:01','2025-03-13 05:58:01',5,NULL,NULL),(47,NULL,'annexes/KBCia52xlweaGCppTtgIIpnQczJ6g2FOUsq51RO1.pdf','2025-03-13 06:56:33','2025-03-13 06:56:33',NULL,NULL,NULL),(48,NULL,'annexes/zgeLzifc4UkfJWum6SFHwlC2dukQdxP6dwv4gIaZ.pdf','2025-03-13 06:56:51','2025-03-13 06:56:51',NULL,NULL,NULL),(52,NULL,'annexes/c0rkBxeB4HKDYvl9rL8sPHYbD0UMlZu20WBt8InM.pdf','2025-03-13 07:16:51','2025-03-13 07:16:51',8,NULL,NULL),(54,NULL,'annexes/2mTN6i6JM39osfbfBzKGTdpS3Tc5QZtfOsDnyPmp.pdf','2025-03-14 00:00:32','2025-03-14 00:00:32',9,NULL,NULL),(56,NULL,'annexes/CBKFiytPDI5L5ApAPA5ye01b5BUWpIa1Dnrmz5no.pdf','2025-03-14 00:12:28','2025-03-14 00:12:28',10,NULL,NULL),(59,NULL,'annexes/gWtI7rx3I8u6SOmFpY2woARenqOaiQ7lSbZapvUl.pdf','2025-03-14 01:13:47','2025-03-14 01:13:47',11,NULL,NULL),(60,NULL,'annexes/6FcQ2z80VaZtTKGydDjyQeQz4qQufJ36dXMhiVJy.pdf','2025-03-14 01:20:27','2025-03-14 01:20:27',12,NULL,NULL),(61,NULL,'annexes/Cxm55rQciq5YacVzh5SAXtuIRCoihYUKlsswwk5a.pdf','2025-03-14 01:27:51','2025-03-14 01:27:51',13,NULL,NULL),(62,NULL,'annexes/DAtZ0XeaIQIiiMO1N9xoW2RfDrcYbp8FpCalQu0D.pdf','2025-03-14 01:45:44','2025-03-14 01:45:44',14,NULL,NULL),(63,NULL,'annexes/2ckjmHypAzQbJdlHVdgZE22gYmlmGbmRJ8cGvBYR.pdf','2025-03-14 01:51:27','2025-03-14 01:51:27',15,NULL,NULL),(64,NULL,'annexes/mRX0tnRMo3tLyAmZhFM1Hv47Fxe5DTrQNjA3niGC.pdf','2025-03-14 01:57:31','2025-03-14 01:57:31',16,NULL,NULL),(65,NULL,'annexes/Wwn31ijBGOZ5FdTV1HtCCNzX5ouAxvsKlKKmMVED.pdf','2025-03-14 01:59:33','2025-03-14 01:59:33',17,NULL,NULL),(68,NULL,'annexes/LF2us9YMu28t22ZCZaGAVHVmrr5aIg88Jqv2OF06.pdf','2025-03-14 02:30:18','2025-03-14 02:30:18',18,NULL,NULL),(69,NULL,'annexes/7C16D9OUc2BBz8iKhlDhFLXNVHzSpdWN2RasmzSO.pdf','2025-03-14 10:50:21','2025-03-14 10:50:21',19,NULL,NULL),(70,NULL,'annexes/urVq6iIkyzoTrgsKzpRUJQA8XTN2QTfxqQg27pHb.pdf','2025-03-14 11:10:45','2025-03-14 11:10:45',20,NULL,NULL),(71,NULL,'annexes/4eivtJRQK1A3WBARJDpPuVPxNzWVy6IyzEweaSAl.pdf','2025-03-14 11:29:38','2025-03-14 11:29:38',21,NULL,NULL),(73,NULL,'annexes/rfwRpNzl8asqnusUPg3kZ1JJmuNLNABNEoPmgJAI.pdf','2025-03-14 12:16:04','2025-03-14 12:16:04',22,NULL,NULL),(76,NULL,'annexes/VNu0iUt21iBzHRqdenhYmAZ1PXeriGCYL8Crzhz8.pdf','2025-03-14 13:17:35','2025-03-14 13:17:35',23,NULL,NULL),(78,NULL,'annexes/V74zVVUq0kKG3dKArVOpv4BzO1rib5JZdeRzENXd.pdf','2025-03-14 13:23:45','2025-03-14 13:23:45',24,NULL,NULL),(79,NULL,'annexes/DZlN2hqYAwev6ZCRdH8JVnvFasPVBpG0nysXUtKO.pdf','2025-03-14 13:25:02','2025-03-14 13:25:02',25,NULL,NULL),(81,NULL,'annexes/6yMLSZoXm4nzmrNBmcMDTSG8dJhrhGxJZbm5XhPM.pdf','2025-03-14 13:40:49','2025-03-14 13:40:49',26,NULL,NULL),(84,NULL,'annexes/rPNgrAXB4rKEdaMvVyAtWJUkAMSabibbb1tFvJQy.pdf','2025-03-14 17:45:32','2025-03-14 17:45:32',27,NULL,NULL),(85,NULL,'annexes/Hokr0sZocdJagdDFctxPTyawRaIFqTm5NilFeMHE.pdf','2025-03-14 17:47:28','2025-03-14 17:47:28',28,NULL,NULL),(87,NULL,'annexes/tdiz9ZAO16B2w2VyRz2fOII7xhvqPXqE3TOKfzil.pdf','2025-03-15 09:53:56','2025-03-15 09:53:56',29,NULL,NULL),(90,NULL,'annexes/NQopdCFMnKdeZKSj2Oe4A2L3n318edjbmCDv7ppx.pdf','2025-03-16 13:25:28','2025-03-16 13:25:28',30,NULL,NULL),(93,NULL,'annexes/WwAEyYYBxPG50wPTKlLe2B0oay3kjZ5y0h33qcey.pdf','2025-03-16 19:28:54','2025-03-16 19:28:54',31,NULL,NULL),(95,NULL,'annexes/Ee3NZOcxSyAideBIVob85lTtw6lCNehVSK2Fjo73.pdf','2025-03-16 21:18:46','2025-03-16 21:18:46',32,NULL,NULL),(97,NULL,'annexes/hq0O35313RKvGtZPtzbDuChzmOA4IPFdNEfAah0b.pdf','2025-03-16 21:49:22','2025-03-16 21:49:22',33,NULL,NULL),(99,NULL,'annexes/kFwoXRcSij2hrv9A3wd3PVMKkBgIEXLDva8tPTJY.pdf','2025-03-16 22:09:27','2025-03-16 22:09:27',34,NULL,NULL),(107,NULL,'annexes/XJ4MKe3Hr42OjaTF7T5WB2sOgDFlkUdS7BbPNI1y.pdf','2025-03-17 21:13:24','2025-03-17 21:13:24',36,NULL,NULL),(108,NULL,'annexes/HbRBTHdPXgPaqYCBnBdNDhuplwqh0yXW1VEXPcw3.docx','2025-03-18 11:16:29','2025-03-18 11:16:29',37,NULL,NULL),(113,NULL,'annexes/Mo5n41UXjvBvVFV2eIKndhTmZVnX6vMbX5YUedQJ.docx','2025-03-18 16:55:43','2025-03-18 16:55:43',38,NULL,NULL),(116,NULL,'annexes/PsHKqP1jvU8Y8VW9V3mKhNu9iq3zIqrxLZ8KuzwP.docx','2025-03-18 19:29:38','2025-03-18 19:29:38',39,NULL,NULL),(121,NULL,'annexes/ic4NEX6QUjLTREDMr7M1mOgFx02wHR4dfiL2nGyA.pdf','2025-03-18 19:44:22','2025-03-18 19:44:22',40,NULL,NULL),(122,NULL,'annexes/qUD1osvriJkevofPbDL22IdCWc9LuJqu7CdYgMTr.pdf','2025-03-20 06:39:15','2025-03-20 06:39:15',41,NULL,NULL),(132,NULL,'annexes/Wf8ePFrfySrIPtIFfPHGgkJBK9KGXilJIwERbPZV.pdf','2025-03-23 20:07:45','2025-03-23 20:07:45',42,NULL,NULL),(136,81,'accuse_81.pdf','2025-04-12 10:57:26','2025-04-12 10:57:26',NULL,NULL,NULL),(137,81,'annexes/EXXIHgbNdzgSALC0Ya6ybsBCQpoEwOTsSz0GneeE.pdf','2025-04-12 18:25:15','2025-04-12 18:25:15',NULL,NULL,NULL),(139,NULL,'annexes/HpUXXKTYLiOYPuStQmDFqEPWPq4YT6cJL0GSzYox.pdf','2025-04-14 07:56:45','2025-04-14 07:56:45',43,NULL,NULL),(140,NULL,'annexes/LTkJq3zbSixbcG48hDzZTnm7CRfJ6OmaUmhNRV6V.pdf','2025-04-14 22:17:47','2025-04-14 22:17:47',44,NULL,NULL),(150,NULL,'annexes/HhzHzvfn9UEdVgkLy08UUWLGwYBkugm4I7cnsx5J.pdf','2025-08-02 03:31:36','2025-08-02 03:31:36',45,NULL,NULL),(153,NULL,'annexes/gOgCZqcJBcawXG60uzewelTeaDcT0Rj6xyPv296E.pdf','2025-08-02 08:38:02','2025-08-02 08:38:02',49,NULL,NULL),(155,NULL,'annexes/Jp8O05IQKeClG0SJJpX6XNVbaEbz4W2FVJNl2zJX.pdf','2025-08-02 09:16:48','2025-08-02 09:16:48',51,NULL,NULL),(159,NULL,'annexes/gVZ6yaDZNYgdkan7CmIojtfH4JYX13CSzDZozNRN.pdf','2025-08-02 10:20:03','2025-08-02 10:20:03',52,NULL,NULL),(167,99,'accuse_99.pdf','2025-08-02 18:15:58','2025-08-02 18:15:58',NULL,NULL,NULL),(168,99,'annexes/0i6xlS0jgg0Degv5lnBWsOvlFKcDvQM6JZlLGEGQ.pdf','2025-08-02 18:26:22','2025-08-02 18:26:22',NULL,NULL,NULL),(169,NULL,'annexes/m3JqO154rsvTDla78oS7IhUoOymx8YjUt0KI5wtO.pdf','2025-08-02 18:43:10','2025-08-02 18:43:10',53,NULL,NULL),(170,NULL,'annexes/CZ6cAHlqqAY4k3of5z7xcpXnYyoTfHwKDxx9OkvL.pdf','2025-08-02 18:49:58','2025-08-02 18:49:58',NULL,6,51),(171,100,'accuse_100.pdf','2025-08-03 20:41:36','2025-08-03 20:41:36',NULL,NULL,NULL);
/*!40000 ALTER TABLE `annexes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `annexes_dossier_personnel`
--

DROP TABLE IF EXISTS `annexes_dossier_personnel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `annexes_dossier_personnel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dossier_personnel_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `annexes_dossier_personnel_dossier_personnel_id_foreign` (`dossier_personnel_id`),
  CONSTRAINT `annexes_dossier_personnel_dossier_personnel_id_foreign` FOREIGN KEY (`dossier_personnel_id`) REFERENCES `dossiers_personnels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `annexes_dossier_personnel`
--

LOCK TABLES `annexes_dossier_personnel` WRITE;
/*!40000 ALTER TABLE `annexes_dossier_personnel` DISABLE KEYS */;
/*!40000 ALTER TABLE `annexes_dossier_personnel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archives`
--

DROP TABLE IF EXISTS `archives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archives` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_enregistrement` varchar(255) NOT NULL,
  `numero_reference` varchar(255) DEFAULT NULL,
  `resume` text,
  `service_concerne` varchar(255) DEFAULT NULL,
  `commentaires` text,
  `statut` enum('clos','en cours','autre') DEFAULT 'en cours',
  `categorie` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_enregistrement` (`numero_enregistrement`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archives`
--

LOCK TABLES `archives` WRITE;
/*!40000 ALTER TABLE `archives` DISABLE KEYS */;
/*!40000 ALTER TABLE `archives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courriers`
--

DROP TABLE IF EXISTS `courriers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courriers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date_reception` date DEFAULT NULL,
  `numero_enregistrement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expediteur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `objet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fichier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('reçu','en attente','validé','traité') COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `annotations` text COLLATE utf8mb4_unicode_ci,
  `validated_by` bigint unsigned DEFAULT NULL,
  `validation_comment` text COLLATE utf8mb4_unicode_ci,
  `validation_date` timestamp NULL DEFAULT NULL,
  `transmis_a_directeur` tinyint(1) NOT NULL DEFAULT '0',
  `reponse_directeur` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courriers_numero_enregistrement_unique` (`numero_enregistrement`),
  KEY `courriers_user_id_foreign` (`user_id`),
  KEY `courriers_validated_by_foreign` (`validated_by`),
  CONSTRAINT `courriers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `courriers_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courriers`
--

LOCK TABLES `courriers` WRITE;
/*!40000 ALTER TABLE `courriers` DISABLE KEYS */;
/*!40000 ALTER TABLE `courriers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courriers_internes`
--

DROP TABLE IF EXISTS `courriers_internes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courriers_internes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_enregistrement` varchar(255) NOT NULL,
  `date_envoi` date NOT NULL,
  `service_expediteur_id` bigint unsigned NOT NULL,
  `service_destinataire_id` bigint unsigned NOT NULL,
  `date_limite_reponse` date NOT NULL,
  `statut` enum('en attente','répondu','en retard') DEFAULT 'en attente',
  `commentaire` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_expediteur_id` (`service_expediteur_id`),
  KEY `service_destinataire_id` (`service_destinataire_id`),
  CONSTRAINT `courriers_internes_ibfk_1` FOREIGN KEY (`service_expediteur_id`) REFERENCES `services` (`id`),
  CONSTRAINT `courriers_internes_ibfk_2` FOREIGN KEY (`service_destinataire_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courriers_internes`
--

LOCK TABLES `courriers_internes` WRITE;
/*!40000 ALTER TABLE `courriers_internes` DISABLE KEYS */;
/*!40000 ALTER TABLE `courriers_internes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demandes_conges`
--

DROP TABLE IF EXISTS `demandes_conges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demandes_conges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` bigint unsigned NOT NULL,
  `type_conge` enum('vacances','maladie','autre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `motif` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('en_attente','acceptee','refusee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `demandes_conges_agent_id_foreign` (`agent_id`),
  KEY `demandes_conges_user_id_foreign` (`user_id`),
  CONSTRAINT `demandes_conges_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`),
  CONSTRAINT `demandes_conges_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demandes_conges`
--

LOCK TABLES `demandes_conges` WRITE;
/*!40000 ALTER TABLE `demandes_conges` DISABLE KEYS */;
INSERT INTO `demandes_conges` VALUES (1,2,'vacances','2025-04-13','2025-04-13','urgent','acceptee',8,'2025-04-12 23:37:02','2025-04-12 23:37:02'),(2,9,'vacances','2025-04-13','2025-04-17','Mission','refusee',11,'2025-04-13 20:43:28','2025-04-29 11:32:56'),(3,10,'maladie','2025-02-14','2025-03-30','Ras','acceptee',11,'2025-04-14 05:57:25','2025-04-29 11:32:49');
/*!40000 ALTER TABLE `demandes_conges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `depense_caisses`
--

DROP TABLE IF EXISTS `depense_caisses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `depense_caisses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `rubrique` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `date_depense` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `justificatifs` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `depense_caisses_user_id_foreign` (`user_id`),
  CONSTRAINT `depense_caisses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `depense_caisses`
--

LOCK TABLES `depense_caisses` WRITE;
/*!40000 ALTER TABLE `depense_caisses` DISABLE KEYS */;
INSERT INTO `depense_caisses` VALUES (1,8,'achat pc',80.00,'2025-04-13','hp','2025-04-12 23:33:15','2025-04-12 23:33:15',NULL),(2,10,'achat pc',90.00,'2025-04-13','HP','2025-04-13 00:26:21','2025-04-13 00:26:21',NULL),(3,10,'achat terrain',120.00,'2025-04-13','LG','2025-04-13 00:30:30','2025-04-13 00:30:30',NULL),(6,10,'achat smartphone',200.00,'2025-04-13','journée de droit de la femme','2025-04-13 20:08:41','2025-04-13 20:15:17',NULL),(8,10,'achat Iphone',40.00,'2025-04-13','test','2025-04-13 20:28:54','2025-04-14 06:27:48',NULL),(10,10,'Communication',50.00,'2025-04-14','telephone bureau','2025-04-14 06:15:52','2025-04-14 06:15:52',NULL),(11,10,'Realisation activité point de presse',500.00,'2025-04-14','RAS','2025-04-14 06:18:21','2025-04-14 06:30:50',NULL);
/*!40000 ALTER TABLE `depense_caisses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dossiers_personnels`
--

DROP TABLE IF EXISTS `dossiers_personnels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dossiers_personnels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `agent_id` bigint unsigned NOT NULL,
  `poste` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_embauche` date DEFAULT NULL,
  `matricule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contrat_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dossiers_personnels_user_id_foreign` (`user_id`),
  KEY `dossiers_personnels_agent_id_foreign` (`agent_id`),
  CONSTRAINT `dossiers_personnels_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dossiers_personnels_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dossiers_personnels`
--

LOCK TABLES `dossiers_personnels` WRITE;
/*!40000 ALTER TABLE `dossiers_personnels` DISABLE KEYS */;
INSERT INTO `dossiers_personnels` VALUES (1,8,9,'Réceptionniste','2025-04-13','102','CDI','OK','2025-04-12 23:17:25','2025-04-12 23:17:25');
/*!40000 ALTER TABLE `dossiers_personnels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fonds_demandes`
--

DROP TABLE IF EXISTS `fonds_demandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fonds_demandes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `motif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('en_attente','approuve','rejete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fonds_demandes_user_id_foreign` (`user_id`),
  CONSTRAINT `fonds_demandes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fonds_demandes`
--

LOCK TABLES `fonds_demandes` WRITE;
/*!40000 ALTER TABLE `fonds_demandes` DISABLE KEYS */;
INSERT INTO `fonds_demandes` VALUES (1,8,100.00,'Achat PC','en_attente','2025-04-12 23:19:37','2025-04-12 23:19:37'),(3,10,100.00,'Investissement','rejete','2025-04-13 21:05:05','2025-04-14 10:33:58'),(4,10,500.00,'Reserve','approuve','2025-04-14 05:51:30','2025-04-14 06:13:57'),(6,10,500.00,'Investissement','approuve','2025-04-15 05:41:08','2025-04-15 05:41:14');
/*!40000 ALTER TABLE `fonds_demandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `justificatifs`
--

DROP TABLE IF EXISTS `justificatifs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `justificatifs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `depense_caisse_id` bigint unsigned DEFAULT NULL,
  `fichier` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `depense_caisse_id` (`depense_caisse_id`),
  CONSTRAINT `justificatifs_ibfk_1` FOREIGN KEY (`depense_caisse_id`) REFERENCES `depense_caisses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `justificatifs`
--

LOCK TABLES `justificatifs` WRITE;
/*!40000 ALTER TABLE `justificatifs` DISABLE KEYS */;
/*!40000 ALTER TABLE `justificatifs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (57,8,9,'Hello, je démarre cette conversation.','2025-04-12 17:56:41','2025-04-15 05:34:03',1),(59,8,10,'Hello, je démarre cette conversation.','2025-04-12 18:27:06','2025-04-16 02:04:51',1),(60,8,10,'reponse imediat','2025-04-12 18:27:54','2025-04-16 02:04:51',1),(61,10,9,'Hello, je démarre cette conversation.','2025-04-12 18:34:36','2025-05-12 04:57:59',1),(62,10,9,'OK','2025-04-12 18:34:44','2025-05-12 04:57:59',1),(63,10,8,'BJR','2025-04-12 18:35:20','2025-04-14 22:05:12',1),(64,9,10,'bjr','2025-04-12 18:36:34','2025-04-15 21:12:10',1),(65,10,11,'Hello, je démarre cette conversation.','2025-04-13 00:27:17','2025-05-12 05:30:37',1),(66,11,10,'merci','2025-04-13 00:27:32','2025-04-15 05:28:55',1),(68,10,8,'Mbote','2025-04-14 05:54:39','2025-04-14 22:05:12',1),(69,8,10,'Mbote ça va','2025-04-14 05:55:08','2025-04-16 02:04:51',1),(70,9,8,'Bonjour','2025-04-15 05:19:21','2025-04-15 05:37:12',1),(71,8,9,'Oui bonjour','2025-04-15 05:22:03','2025-04-15 05:34:03',1),(72,9,8,'Oui bonjour','2025-04-15 05:34:03','2025-04-15 05:37:12',1),(73,8,9,'OK','2025-04-15 05:37:12','2025-04-15 05:37:12',0),(74,10,8,'ok','2025-04-16 02:04:51','2025-04-16 02:04:51',0),(87,5,2,'Hello, je démarre cette conversation.','2025-08-02 14:42:43','2025-08-02 14:42:43',0),(90,3,9,'Hello, je démarre cette conversation.','2025-08-02 18:23:40','2025-08-03 22:03:07',1),(91,9,3,'OK','2025-08-02 18:31:20','2025-08-03 22:05:49',1),(93,3,9,'salut','2025-08-03 20:25:16','2025-08-03 22:03:07',1),(94,9,3,'merci','2025-08-03 20:26:57','2025-08-03 22:05:49',1),(95,9,3,'bonsoir','2025-08-03 20:27:47','2025-08-03 22:05:49',1),(96,3,9,'cc','2025-08-03 20:31:09','2025-08-03 22:03:07',1),(97,9,3,'ok','2025-08-03 20:31:26','2025-08-03 22:05:49',1),(98,9,3,'BB','2025-08-03 20:43:32','2025-08-03 22:05:49',1),(99,9,3,'merci','2025-08-03 20:46:34','2025-08-03 22:05:49',1),(100,3,9,'cc','2025-08-03 20:59:38','2025-08-03 22:03:07',1),(101,3,6,'Hello, je démarre cette conversation.','2025-08-03 21:08:46','2025-08-03 22:00:48',1),(102,6,3,'bonjour','2025-08-03 21:21:27','2025-08-03 22:01:34',1),(103,6,3,'cc','2025-08-03 21:27:35','2025-08-03 22:01:34',1),(104,3,6,'merci','2025-08-03 21:29:58','2025-08-03 22:00:48',1),(105,3,6,'ok','2025-08-03 21:39:04','2025-08-03 22:00:48',1),(106,6,3,'merci','2025-08-03 21:51:27','2025-08-03 22:01:34',1),(107,3,6,'ok','2025-08-03 21:52:08','2025-08-03 22:00:48',1),(108,6,3,'merci','2025-08-03 21:52:30','2025-08-03 22:01:34',1),(109,6,3,'ok','2025-08-03 22:00:47','2025-08-03 22:01:34',1),(110,3,6,'ok','2025-08-03 22:01:34','2025-08-03 22:01:34',0),(111,3,5,'Hello, je démarre cette conversation.','2025-08-03 22:04:02','2025-08-03 22:05:29',1),(112,3,5,'ok','2025-08-03 22:05:00','2025-08-03 22:05:29',1),(113,5,3,'ok','2025-08-03 22:05:29','2025-08-03 22:05:51',1);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (5,'0001_01_01_000000_create_users_table',1),(6,'0001_01_01_000001_create_cache_table',1),(7,'0001_01_01_000002_create_jobs_table',1),(8,'2025_03_07_000847_create_courriers_table',1),(9,'2025_03_20_150424_create_messages_table',2),(10,'2025_03_20_150529_create_annexe_messages_table',2),(11,'2025_04_11_123156_create_tenants_table',3),(12,'2025_04_12_214614_create_dossiers_personnels_table',4),(13,'2025_04_12_215036_create_demande_conges_table',5),(14,'2025_04_12_222439_create_fonds_demandes_table',6),(15,'2025_04_12_222547_create_depense_caisses_table',6),(16,'2025_04_13_222155_create_annexes_dossier_personnel_table',7);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('agent02@gmail.com','$2y$12$etoti8f8kdGMocuFn7Kh6OkTrrPuOCB0fnJ3sxIMZG.wCHxbz0xCG','2025-03-09 19:47:42');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reponses`
--

DROP TABLE IF EXISTS `reponses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reponses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_enregistrement` varchar(255) NOT NULL,
  `numero_reference` varchar(255) NOT NULL,
  `service_concerne` varchar(255) NOT NULL,
  `observation` text,
  `commentaires` text,
  `annexe_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `telegramme_id` bigint unsigned DEFAULT NULL,
  `reponse_id` int DEFAULT NULL,
  `archive` varchar(255) DEFAULT NULL,
  `status_archive` varchar(255) DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `annexe_id` (`annexe_id`),
  KEY `fk_telegramme` (`telegramme_id`),
  KEY `reponses_user_id_foreign` (`user_id`),
  KEY `fk_reponse_parent` (`reponse_id`),
  CONSTRAINT `fk_reponse_parent` FOREIGN KEY (`reponse_id`) REFERENCES `reponses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_telegramme` FOREIGN KEY (`telegramme_id`) REFERENCES `telegrammes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reponses_ibfk_1` FOREIGN KEY (`annexe_id`) REFERENCES `annexes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reponses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reponses`
--

LOCK TABLES `reponses` WRITE;
/*!40000 ALTER TABLE `reponses` DISABLE KEYS */;
INSERT INTO `reponses` VALUES (44,'123/34','DP03','RH','test','test',NULL,'2025-04-14 22:17:47','2025-04-14 22:17:47',39,NULL,NULL,NULL,11),(53,'760/08','DG023/SEC/DRC','Comptabilité','brayan','fond recu',NULL,'2025-08-02 18:43:10','2025-08-03 19:10:51',51,NULL,'Expositions','en cours',6),(54,'123/349','OK','Comptabilité','ok','ok',NULL,'2025-08-03 21:31:05','2025-08-03 21:31:05',52,NULL,NULL,NULL,6);
/*!40000 ALTER TABLE `reponses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reponses_finales`
--

DROP TABLE IF EXISTS `reponses_finales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reponses_finales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_enregistrement` varchar(255) NOT NULL,
  `numero_reference` varchar(255) NOT NULL,
  `service_concerne` varchar(255) NOT NULL,
  `observation` text,
  `telegramme_id` bigint unsigned NOT NULL,
  `reponse_id` int NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_reponse_finale_reponse` (`reponse_id`),
  KEY `fk_reponse_finale_telegramme` (`telegramme_id`),
  KEY `fk_reponse_finale_user` (`user_id`),
  CONSTRAINT `fk_reponse_finale_reponse` FOREIGN KEY (`reponse_id`) REFERENCES `reponses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reponse_finale_telegramme` FOREIGN KEY (`telegramme_id`) REFERENCES `telegrammes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reponse_finale_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reponses_finales`
--

LOCK TABLES `reponses_finales` WRITE;
/*!40000 ALTER TABLE `reponses_finales` DISABLE KEYS */;
INSERT INTO `reponses_finales` VALUES (6,'760/08','DG023/SEC/DRC','Comptabilité','ok clasé',51,53,3,'2025-08-02 18:49:58','2025-08-02 18:49:58'),(7,'123/349','OK','Comptabilité','ok',52,54,3,'2025-08-03 22:06:39','2025-08-03 22:06:39');
/*!40000 ALTER TABLE `reponses_finales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('ujftsqZpNhFWCbNgXWaQ5Qo808M9dHQ00UGO9c8X',3,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 Edg/138.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiSUxhU2M4N3JSSndKTTh2bk5yUWpxdEZmQzljVHVreTU2RmhRemFRQSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxNzoiY29kZV9hY2Nlc192YWxpZGUiO2I6MTtzOjE1OiJjb2RlX2FjY2VzX3RpbWUiO086MjU6IklsbHVtaW5hdGVcU3VwcG9ydFxDYXJib24iOjM6e3M6NDoiZGF0ZSI7czoyNjoiMjAyNS0wOC0wNCAwNjoyOTo0Mi42OTEwMjYiO3M6MTM6InRpbWV6b25lX3R5cGUiO2k6MztzOjg6InRpbWV6b25lIjtzOjM6IlVUQyI7fX0=',1754305501),('YtTW6s8bx1TOcPxWU8JtaovptARh9ixCLrf26WlX',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMzRKc3VoN2ZXbUdZR2JaREt1QmRsbUgwaERFT25Pazk4MjdRS2drZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjE3OiJjb2RlX2FjY2VzX3ZhbGlkZSI7YjoxO3M6MTU6ImNvZGVfYWNjZXNfdGltZSI7TzoyNToiSWxsdW1pbmF0ZVxTdXBwb3J0XENhcmJvbiI6Mzp7czo0OiJkYXRlIjtzOjI2OiIyMDI1LTA4LTA0IDA4OjA2OjQ0LjU1MzU0MyI7czoxMzoidGltZXpvbmVfdHlwZSI7aTozO3M6ODoidGltZXpvbmUiO3M6MzoiVVRDIjt9fQ==',1754301968);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telegrammes`
--

DROP TABLE IF EXISTS `telegrammes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telegrammes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_enregistrement` varchar(255) NOT NULL,
  `numero_reference` varchar(255) NOT NULL,
  `service_concerne` varchar(255) NOT NULL,
  `observation` text,
  `commentaires` text,
  `archive` varchar(255) DEFAULT NULL,
  `status_archive` varchar(255) DEFAULT NULL,
  `annexes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `telegrammes_user_id_foreign` (`user_id`),
  CONSTRAINT `telegrammes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telegrammes`
--

LOCK TABLES `telegrammes` WRITE;
/*!40000 ALTER TABLE `telegrammes` DISABLE KEYS */;
INSERT INTO `telegrammes` VALUES (39,'123/34','DP03','RH','Test','Test',NULL,NULL,NULL,'2025-04-14 09:29:03','2025-04-14 09:29:03',8),(40,'123/34','DP03','caisse','test','test',NULL,NULL,NULL,'2025-04-14 10:51:52','2025-04-14 10:51:52',8),(41,'123/34','DP03','caisse','test','test',NULL,NULL,NULL,'2025-04-15 05:23:26','2025-04-15 05:23:26',8),(42,'123/34','DP03','caisse','TEST','TEST',NULL,NULL,NULL,'2025-04-15 05:38:11','2025-04-15 05:38:11',8),(51,'760/08','DG023/SEC/DRC','Comptabilité','Brayan','approuver','Expositions','en cours',NULL,'2025-08-02 18:36:39','2025-08-03 19:10:51',3),(52,'123/349','OK','Comptabilité','OK','OK',NULL,NULL,NULL,'2025-08-03 20:43:10','2025-08-03 20:43:10',3),(53,'123/349','OK','Comptabilité','ok','ok',NULL,NULL,NULL,'2025-08-03 21:32:05','2025-08-03 21:32:05',3),(54,'123/349','OK','Comptabilité','ok','ok',NULL,NULL,NULL,'2025-08-03 21:38:07','2025-08-03 21:38:07',3),(55,'760/08','DG023/SEC/DRC','Informatique','ok','ok',NULL,NULL,NULL,'2025-08-03 22:07:29','2025-08-03 22:07:29',3);
/*!40000 ALTER TABLE `telegrammes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `database` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_database_unique` (`database`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (1,'fpc','gestion_courrier','2025-04-11 11:54:46','2025-04-11 11:54:46');
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('agent','chef_service','directeur_general','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenant_id` bigint unsigned DEFAULT NULL,
  `entreprise` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abonnement_expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'Directeur','directeur@gmail.com',NULL,NULL,'$2y$12$bSO2E0PVovwgcXmYsGYw9eEd0yQS4J5hkpdowWvfhluuOUX4ArNi2','directeur_general',NULL,'2025-03-10 21:44:38','2025-03-10 21:44:38',1,'fpc',NULL),(3,'Secrétariat DG','cservice@gmail.com','secrétariat',NULL,'$2y$12$ByH2cAIDggkJbeWChARdces1NCYFBLlp2OrmnhTxrfEGo.cSZ/H5S','admin',NULL,'2025-03-10 21:57:47','2025-04-02 19:09:34',NULL,'fpc',NULL),(5,'Informatique','info02@gmail.com','Informatique',NULL,'$2y$12$mejMDkQkX/RXKE8tOVSLY.C8ZTOfCC1F1jiPGc0iM5jW9v5LHXWb.','chef_service',NULL,'2025-03-16 21:36:14','2025-03-16 21:36:14',NULL,'fpc',NULL),(6,'Comptabilite','compt02@gmail.com','Comptabilité',NULL,'$2y$12$fNFRKtjFnxzzlBsyKMyNfe/z3ILkmjRddKvZYIw.WbvvH6pdPv1Be','chef_service',NULL,'2025-03-16 22:06:47','2025-03-16 22:06:47',NULL,'fpc',NULL),(8,'Secrétariat','sec@gmail.com',NULL,NULL,'$2y$12$SOg0Ae5NDQtdBQ81eofaA.2r.EplgDdV3z3K/VTFJpqfDjFq7.lcK','admin',NULL,'2025-04-11 14:23:23','2025-04-12 18:02:25',1,'JCPCAE',NULL),(9,'Réception','recept@gmail.com','secrétariat',NULL,'$2y$12$SOg0Ae5NDQtdBQ81eofaA.2r.EplgDdV3z3K/VTFJpqfDjFq7.lcK','agent',NULL,'2025-04-12 18:31:09','2025-04-12 18:31:09',NULL,'fpc',NULL),(10,'caisse','caisse@gmail.com','caisse',NULL,'$2y$12$SOg0Ae5NDQtdBQ81eofaA.2r.EplgDdV3z3K/VTFJpqfDjFq7.lcK','chef_service',NULL,'2025-04-12 18:42:23','2025-04-12 18:45:23',NULL,'JCPCAE','2025-05-31 02:05:04'),(11,'RH','rh@gmail.com','RH',NULL,'$2y$12$SOg0Ae5NDQtdBQ81eofaA.2r.EplgDdV3z3K/VTFJpqfDjFq7.lcK','chef_service',NULL,'2025-04-13 01:21:06','2025-04-13 01:21:06',NULL,'JCPCAE',NULL),(12,'bbg meo','dorsal@gmail.com',NULL,NULL,'$2y$12$4jbIzy2Wz.KV2PfW6R8tXOvN7M73uGoMvJnvJNKKhL/Ylvt9X7qWK','agent',NULL,'2025-05-25 07:28:50','2025-05-25 07:28:50',NULL,'FJKOE','2025-06-01 08:28:50');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs`
--

LOCK TABLES `utilisateurs` WRITE;
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` VALUES (3,'bbg','meo','FJKOE','ZROPE03E','OR3¨P0','ZRUIJ3?','1234','bbg02@gmail.com','123456766','2025-04-29 11:46:02','2025-04-29 11:46:02');
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-05  9:01:16
