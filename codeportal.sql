-- Progettazione Web 
DROP DATABASE if exists codeportal; 
CREATE DATABASE codeportal; 
USE codeportal; 
-- MySQL dump 10.13  Distrib 5.6.20, for Win32 (x86)
--
-- Host: localhost    Database: codeportal
-- ------------------------------------------------------
-- Server version	5.6.20

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
-- Table structure for table `amicizia`
--

DROP TABLE IF EXISTS `amicizia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `amicizia` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Utente1` int(11) NOT NULL,
  `Utente2` int(11) NOT NULL,
  `DataRichiesta` date NOT NULL,
  `DataAmicizia` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Utente1` (`Utente1`,`Utente2`),
  KEY `Utente2` (`Utente2`),
  CONSTRAINT `amicizia_ibfk_1` FOREIGN KEY (`Utente1`) REFERENCES `utente` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `amicizia_ibfk_2` FOREIGN KEY (`Utente2`) REFERENCES `utente` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amicizia`
--

LOCK TABLES `amicizia` WRITE;
/*!40000 ALTER TABLE `amicizia` DISABLE KEYS */;
INSERT INTO `amicizia` VALUES (1,2,1,'2018-05-20','2018-05-20'),(2,2,3,'2018-05-20','2018-05-20');
/*!40000 ALTER TABLE `amicizia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commento`
--

DROP TABLE IF EXISTS `commento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commento` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Istante` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Risposta` int(11) NOT NULL,
  `Riga` int(11) DEFAULT NULL,
  `Modifica` int(11) DEFAULT NULL,
  `Testo` text NOT NULL,
  `Autore` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Autore` (`Autore`,`Istante`),
  KEY `Risposta` (`Risposta`),
  CONSTRAINT `commento_ibfk_1` FOREIGN KEY (`Risposta`) REFERENCES `risposta` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `commento_ibfk_2` FOREIGN KEY (`Autore`) REFERENCES `utente` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commento`
--

LOCK TABLES `commento` WRITE;
/*!40000 ALTER TABLE `commento` DISABLE KEYS */;
INSERT INTO `commento` VALUES (1,'2018-05-21 14:57:59',1,NULL,NULL,'Ok',2),(3,'2018-05-21 15:18:33',1,NULL,NULL,'Prova',1);
/*!40000 ALTER TABLE `commento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dislikes`
--

DROP TABLE IF EXISTS `dislikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dislikes` (
  `Utente` int(11) NOT NULL,
  `Risposta` int(11) NOT NULL,
  PRIMARY KEY (`Utente`,`Risposta`),
  KEY `Risposta` (`Risposta`),
  CONSTRAINT `dislikes_ibfk_1` FOREIGN KEY (`Utente`) REFERENCES `utente` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `dislikes_ibfk_2` FOREIGN KEY (`Risposta`) REFERENCES `risposta` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dislikes`
--

LOCK TABLES `dislikes` WRITE;
/*!40000 ALTER TABLE `dislikes` DISABLE KEYS */;
INSERT INTO `dislikes` VALUES (2,1);
/*!40000 ALTER TABLE `dislikes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dislikes_monitor` AFTER INSERT ON `dislikes` FOR EACH ROW BEGIN

	DECLARE esperienza_like INTEGER DEFAULT 0;
	DECLARE codici_like INTEGER DEFAULT 0;
	DECLARE utente_target INTEGER DEFAULT 0;

	# Check coerenza
	IF EXISTS (
        SELECT *
        FROM likes D
        WHERE D.Utente=NEW.Utente
        	AND D.Risposta=NEW.Risposta
    ) THEN
        DELETE D.*
        FROM likes D
        WHERE D.Utente=NEW.Utente
        	AND D.Risposta=NEW.Risposta;
    END IF;

    # Update esperienza
	SELECT U.Esperienza INTO esperienza_like
	FROM utente U
	WHERE U.ID = NEW.Utente;

	SELECT count(*) INTO codici_like
	FROM utente U INNER JOIN risposta R ON R.Autore=U.ID
	WHERE U.ID = NEW.Utente;

	SELECT R.Autore INTO utente_target
	FROM risposta R
	WHERE R.ID = NEW.Risposta;

	IF utente_target<>NEW.Utente THEN
    	UPDATE utente U
		SET U.Esperienza = IF((U.Esperienza -(esperienza_like+1))>=0, (U.Esperienza -(esperienza_like+1)), 0)
		WHERE U.ID = utente_target;
    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `Utente` int(11) NOT NULL,
  `Risposta` int(11) NOT NULL,
  PRIMARY KEY (`Utente`,`Risposta`),
  KEY `Risposta` (`Risposta`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`Utente`) REFERENCES `utente` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`Risposta`) REFERENCES `risposta` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (1,1),(3,1);
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `likes_monitor` AFTER INSERT ON `likes` FOR EACH ROW BEGIN

	DECLARE esperienza_like INTEGER DEFAULT 0;
	DECLARE codici_like INTEGER DEFAULT 0;
	DECLARE utente_target INTEGER DEFAULT 0;

	# Check coerenza
	IF EXISTS (
        SELECT *
        FROM dislikes D
        WHERE D.Utente=NEW.Utente
        	AND D.Risposta=NEW.Risposta
    ) THEN
        DELETE D.*
        FROM dislikes D
        WHERE D.Utente=NEW.Utente
        	AND D.Risposta=NEW.Risposta;
    END IF;

    # Update esperienza
	SELECT U.Esperienza INTO esperienza_like
	FROM utente U
	WHERE U.ID = NEW.Utente;

	SELECT count(*) INTO codici_like
	FROM utente U INNER JOIN risposta R ON R.Autore=U.ID
	WHERE U.ID = NEW.Utente;

	SELECT R.Autore INTO utente_target
	FROM risposta R
	WHERE R.ID = NEW.Risposta;

	IF utente_target<>NEW.Utente THEN
    	UPDATE utente U
		SET U.Esperienza = U.Esperienza+(esperienza_like+1)
		WHERE U.ID = utente_target;
    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `messaggio`
--

DROP TABLE IF EXISTS `messaggio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messaggio` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Istante` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Oggetto` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Testo` text COLLATE latin1_general_ci NOT NULL,
  `Destinatario` int(11) NOT NULL,
  `Mittente` int(11) NOT NULL,
  `Visualizzato` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Destinatario` (`Destinatario`),
  KEY `Mittente` (`Mittente`),
  CONSTRAINT `messaggio_ibfk_1` FOREIGN KEY (`Destinatario`) REFERENCES `utente` (`ID`) ON DELETE NO ACTION,
  CONSTRAINT `messaggio_ibfk_2` FOREIGN KEY (`Mittente`) REFERENCES `utente` (`ID`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messaggio`
--

LOCK TABLES `messaggio` WRITE;
/*!40000 ALTER TABLE `messaggio` DISABLE KEYS */;
INSERT INTO `messaggio` VALUES (1,'2018-05-21 15:24:41','Messaggio di prova','Questo Ã¨ un messaggio di prova per la ricezione messaggi.',2,1,0),(2,'2018-05-21 15:25:33','Risposta al messaggio di prova','La risposta al messaggio precedente.',1,2,1);
/*!40000 ALTER TABLE `messaggio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `richiesta`
--

DROP TABLE IF EXISTS `richiesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `richiesta` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Istante` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Titolo` varchar(130) COLLATE latin1_general_ci NOT NULL,
  `Descrizione` text COLLATE latin1_general_ci NOT NULL,
  `Linguaggio` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Visibilita` tinyint(1) NOT NULL,
  `Autore` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `richiesta_ibfk_1` (`Autore`),
  CONSTRAINT `richiesta_ibfk_1` FOREIGN KEY (`Autore`) REFERENCES `utente` (`ID`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `richiesta`
--

LOCK TABLES `richiesta` WRITE;
/*!40000 ALTER TABLE `richiesta` DISABLE KEYS */;
INSERT INTO `richiesta` VALUES (2,'2018-05-20 11:33:50','Primitiva gestione semafori','Mi serve una primitiva per sistemi a 64bit con preemption per la gestione efficiente di semafori.\r\n\r\nEsiste un array globale di descrittori semafori chiamato array_dess.\r\n\r\nOgni descrittore contiene:\r\n1. int counter: il numero di vie ancora disponibili\r\n(se negativo indica processi in attesa)\r\n2. proc_elem* pointer: la lista su cui i processi si pongono in attesa\r\n\r\nEnd','C++',1,3);
/*!40000 ALTER TABLE `richiesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `risposta`
--

DROP TABLE IF EXISTS `risposta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `risposta` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Codice` text COLLATE latin1_general_ci NOT NULL,
  `Richiesta` int(11) NOT NULL,
  `UltimaModifica` date NOT NULL,
  `Autore` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Richiesta` (`Richiesta`),
  KEY `Autore` (`Autore`),
  CONSTRAINT `risposta_ibfk_1` FOREIGN KEY (`Richiesta`) REFERENCES `richiesta` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risposta`
--

LOCK TABLES `risposta` WRITE;
/*!40000 ALTER TABLE `risposta` DISABLE KEYS */;
INSERT INTO `risposta` VALUES (1,'extern \"C\" void c_sem_wait(natl sem) {\r\n	if(!sem_valido(sem)) {\r\n		flog(LOG_WARN, \"Il semaforo %d non Ã¨ valido\", sem);\r\n		abort_process();\r\n	}\r\n\r\n	des_sem *s = &array_dess[sem];\r\n\r\n	(s->counter)--;\r\n\r\n	if (s->counter<0) {\r\n		inserimento_lista(s->pointer);\r\n		schedulatore();\r\n	}\r\n}\r\n\r\nextern \"C\" void c_sem_signal(natl sem) {\r\n	if(!sem_valido(sem)) {\r\n		flog(LOG_WARN, \"Il semaforo %d non Ã¨ valido.\", sem);\r\n		abort_process();\r\n	}\r\n\r\n	des_sem* s= &array_dess[sem];\r\n\r\n	if(s->counter < 0 ) {\r\n		proc_elem* lavoro = 0;\r\n		rimozione_lista(s->pointer, lavoro);\r\n		inserimento_lista(pronti, lavoro);\r\n		(s->counter)++;\r\n	}\r\n}\r\n\r\n// End',2,'2018-05-21',2),(2,'Codice prova 2',2,'2018-05-22',1);
/*!40000 ALTER TABLE `risposta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utente`
--

DROP TABLE IF EXISTS `utente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utente` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Username` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Cognome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Password` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `Amministratore` tinyint(1) NOT NULL DEFAULT '0',
  `Esperienza` int(11) NOT NULL DEFAULT '0',
  `Image` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utente`
--

LOCK TABLES `utente` WRITE;
/*!40000 ALTER TABLE `utente` DISABLE KEYS */;
INSERT INTO `utente` VALUES (1,'utente1@pweb.it','Utente1','Mario','Virdis','766724e2275657b656fcc8f94ecacadff3a798689303c5aa4d0e579ced7066b9',0,0,0),(2,'pweb@pweb.it','pweb','Mario','Virdis','766724e2275657b656fcc8f94ecacadff3a798689303c5aa4d0e579ced7066b9',1,2,1),(3,'utente2@pweb.it','Utente2','Mario','Virdis','766724e2275657b656fcc8f94ecacadff3a798689303c5aa4d0e579ced7066b9',0,0,0);
/*!40000 ALTER TABLE `utente` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-04 13:44:26
