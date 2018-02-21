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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amicizia`
--

LOCK TABLES `amicizia` WRITE;
/*!40000 ALTER TABLE `amicizia` DISABLE KEYS */;
INSERT INTO `amicizia` VALUES (1,2,3,'2018-02-12','2018-02-12'),(2,2,1,'2018-02-12',NULL),(4,3,4,'2018-02-12','2018-02-12'),(5,5,3,'2018-02-16','2018-02-16');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commento`
--

LOCK TABLES `commento` WRITE;
/*!40000 ALTER TABLE `commento` DISABLE KEYS */;
INSERT INTO `commento` VALUES (1,'2018-02-20 10:37:45',3,NULL,NULL,'L\'istruzione dhiuehd è sconsigliata.',3),(2,'2018-02-21 16:32:00',3,NULL,NULL,'Valuta di usare sintassi ES6',2);
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
  CONSTRAINT `dislikes_ibfk_1` FOREIGN KEY (`Utente`) REFERENCES `utente` (`ID`),
  CONSTRAINT `dislikes_ibfk_2` FOREIGN KEY (`Risposta`) REFERENCES `risposta` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dislikes`
--

LOCK TABLES `dislikes` WRITE;
/*!40000 ALTER TABLE `dislikes` DISABLE KEYS */;
INSERT INTO `dislikes` VALUES (2,3);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER chk_dislikes

AFTER INSERT ON dislikes

FOR EACH ROW

BEGIN



	IF EXISTS (

        SELECT *

        FROM likes L

        WHERE L.Utente=NEW.Utente

        	AND L.Risposta=NEW.Risposta

    ) THEN

    

    	DELETE L.*

        FROM likes L

        WHERE L.Utente=NEW.Utente

        	AND L.Risposta=NEW.Risposta;

    

    END IF;



END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `include`
--

DROP TABLE IF EXISTS `include`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `include` (
  `Messaggio` int(11) NOT NULL,
  `Richiesta` int(11) NOT NULL,
  PRIMARY KEY (`Messaggio`,`Richiesta`),
  KEY `Richiesta` (`Richiesta`),
  CONSTRAINT `include_ibfk_1` FOREIGN KEY (`Messaggio`) REFERENCES `messaggio` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `include_ibfk_2` FOREIGN KEY (`Richiesta`) REFERENCES `richiesta` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `include`
--

LOCK TABLES `include` WRITE;
/*!40000 ALTER TABLE `include` DISABLE KEYS */;
/*!40000 ALTER TABLE `include` ENABLE KEYS */;
UNLOCK TABLES;

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
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`Utente`) REFERENCES `utente` (`ID`),
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`Risposta`) REFERENCES `risposta` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (1,3),(3,3),(4,3),(5,3);
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
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER chk_like

AFTER INSERT ON likes

FOR EACH ROW

BEGIN



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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messaggio`
--

LOCK TABLES `messaggio` WRITE;
/*!40000 ALTER TABLE `messaggio` DISABLE KEYS */;
INSERT INTO `messaggio` VALUES (1,'2018-02-12 15:48:24','Messaggio di Prova','Con questo messaggio voglio testare il meccanismo delle email su questa piattaforma.',3,2,1),(2,'2018-02-12 15:49:24','Secondo messaggio di prova','Con questo secondo messaggio di prova voglio testare il ritorno dal form delle nuove email se era aperto email inviate.',3,2,1),(3,'2018-02-12 15:51:03','Re: Secondo Messaggio di prova','Ok i messaggi sono arrivati!',2,3,1),(8,'2018-02-16 15:34:22','Oggetto Mooooooolto Luuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuungooooooooooooooooooooooooooooooooooooooo','Messaggio per la visualizzazione di prova di un messaggio molto lungo con un oggetto molto lungo.\r\n<?php\r\n/* Questo file contiene funzioni per ottenere varie informazioni dal database sottoforma di object. */\r\n\r\nrequire_once __DIR__.\'/../path.php\';\r\nrequire UTILS_DIR.\'managerDB.php\';\r\n\r\n// Ordina le richieste di codici per popolarita\'\r\nfunction topRatedRequests() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT DT.* \'.\r\n			 \'FROM ( \'.\r\n				  \'SELECT R.*, \'.\r\n				  		\'U.ID AS UserID, \'.\r\n						\'U.Username AS Autore, \'.\r\n						\'U.Image, \'.\r\n						\'count(DISTINCT RA.ID) AS NumResponses \'.\r\n				  \'FROM (richiesta R INNER JOIN utente U ON U.ID=R.Autore) \'.\r\n				  		\'LEFT OUTER JOIN risposta RA ON RA.Richiesta=R.ID \'.\r\n				  \'WHERE R.Visibilita=1 \'.\r\n				  \'GROUP BY R.ID, U.Username \'.\r\n				  \'ORDER BY NumResponses DESC, R.Istante DESC \'.\r\n			 \') DT \'.\r\n			 \'WHERE DT.NumResponses>=20\';\r\n\r\n	$rows = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if ($rows == null || $rows->num_rows == 0)\r\n		return null;\r\n\r\n	return $rows;\r\n}\r\n\r\n// Restituisce le richieste dei soli amici\r\nfunction friendsRequests() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT R.*, U.ID AS UserID, U.Username AS Autore, U.Image \'.\r\n			 \'FROM richiesta R INNER JOIN utente U ON U.ID = R.Autore \'.\r\n			 \'WHERE EXISTS ( \'.\r\n			 \'   SELECT * \'.\r\n			 \'   FROM amicizia A \'.\r\n			 \'   WHERE (A.Utente1=R.Autore AND A.Utente2=\'.$_SESSION[\'userID\'].\') \'.\r\n			 \'   	OR (A.Utente1=\'.$_SESSION[\'userID\'].\' AND A.Utente2=R.Autore) \'.\r\n			 \'      AND A.DataAmicizia IS NOT NULL \'.\r\n			 \') AND R.Visibilita=1 \'.\r\n			 \'ORDER BY R.Istante DESC\';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce le richieste ordinate temporalmente\r\nfunction recentRequests() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT R.*, U.ID AS UserID, U.Username AS Autore, U.Image \'.\r\n			 \'FROM richiesta R INNER JOIN utente U ON U.ID = R.Autore \'.\r\n			 \'WHERE R.Visibilita=1 \'.\r\n			 \'ORDER BY R.Istante DESC\';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce il numero di codici in totale nella piattaforma\r\nfunction getNumOfTotalCodes() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT count(*) AS Num \'.\r\n			 \'FROM risposta\';\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result->num_rows==0)\r\n		return 0;\r\n	return $result->fetch_assoc()[\'Num\'];\r\n}\r\n\r\n// Restituisce il numero di codici dell\'utente $id\r\nfunction getNumOfCodes($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT count(ID) AS Num \'.\r\n		     \'FROM risposta \'.\r\n		     \'WHERE Autore=\'.$id;\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result->num_rows==0)\r\n		return 0;\r\n	return $result->fetch_assoc()[\'Num\'];\r\n}\r\n\r\n// Restituisce il numero di richieste effettuate dall\'utente loggato\r\nfunction getNumOfRequests($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT count(ID) AS Num \'.\r\n			 \'FROM richiesta \'.\r\n			 \'WHERE Autore=\'.$id;\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result->fetch_assoc()[\'Num\'];\r\n}\r\n\r\n// Restituisce il numero di commenti effettuati dall\'utente loggato\r\nfunction getNumOfComments($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT count(ID) AS Num \'.\r\n		     \'FROM commento \'.\r\n		     \'WHERE Autore=\'.$id;\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result->num_rows==0)\r\n		return 0;\r\n	return $result->fetch_assoc()[\'Num\'];\r\n}\r\n\r\n// Restituisce l\'esperienza dell\'utente loggato\r\nfunction getExperience($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT Experience \'.\r\n			 \'FROM utente \'.\r\n			 \'WHERE ID=\'.$id;\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result==null)\r\n		return 0;\r\n	return $result->fetch_assoc()[\'Experience\'];\r\n}\r\n\r\n// Restituisce il contenuto dell\'email di id $mailID\r\nfunction getMail($mailID) {\r\n	global $dbmanager;\r\n\r\n	$mailID = $dbmanager->sqlInjectionFilter($mailID);\r\n\r\n	$query = \'SELECT M.*, U.Username AS UsernameMittente, U2.Username AS UsernameDestinatario \'.\r\n			 \'FROM ( \'.\r\n			 	\'SELECT * FROM messaggio \'.\r\n			 	\'WHERE Destinatario=\'.$_SESSION[\'userID\'].\' \'.\r\n			 	\'OR Mittente=\'.$_SESSION[\'userID\'].\' \'.\r\n			 \') M \'.\r\n			 \' INNER JOIN utente U ON U.ID=M.Mittente \'.\r\n			 \'   INNER JOIN utente U2 ON U2.ID=M.Destinatario \'.\r\n			 \'WHERE M.ID=\'.$mailID;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n	return $result;\r\n}\r\n\r\n// Restituisce tutte le email in arrivo all\'utente loggato\r\nfunction getMailsIn() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT M.*, U1.ID AS UserID, U1.Username AS UsernameTarget, U1.Image, 1 AS dir \'.\r\n			 \'FROM messaggio M INNER JOIN utente U1 ON U1.ID=M.Mittente \'.\r\n			 \'WHERE M.Destinatario=\'.$_SESSION[\'userID\'].\' \'.\r\n			 \'ORDER BY M.Istante DESC\';\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n	return $result;\r\n}\r\n\r\n// Restituisce tutte le email inviate dall\'utente loggato\r\nfunction getMailsOut() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT M.*, U1.ID AS UserID, U1.Username AS UsernameTarget, U1.Image, 0 AS dir \'.\r\n			 \'FROM messaggio M INNER JOIN utente U1 ON U1.ID=M.Destinatario \'.\r\n			 \'WHERE M.Mittente=\'.$_SESSION[\'userID\'].\' \'.\r\n			 \'ORDER BY M.Istante DESC\';\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n	return $result;\r\n}\r\n\r\n// Restituisce gli amici dell\'utente loggato con username like $pattern\r\nfunction getFriendsOfUserLike($pattern) {\r\n	global $dbmanager;\r\n\r\n	$pattern = $dbmanager->sqlInjectionFilter($pattern);\r\n\r\n	$query = \'SELECT IF(U1.Username=\"\'.$_SESSION[\'username\'].\'\",U2.Username,U1.Username) AS Username \'.\r\n			 \'FROM utente U1 INNER JOIN amicizia A ON A.Utente1=U1.ID \'.\r\n			 \'    INNER JOIN utente U2 ON A.Utente2 = U2.ID \'.\r\n			 \'WHERE A.DataAmicizia IS NOT NULL \'.\r\n			 \'  AND ((U1.ID=\'.$_SESSION[\'userID\'].\' AND U2.Username LIKE \"%\'.$pattern.\'%\") \'.\r\n			 \'       OR (U2.ID=\'.$_SESSION[\'userID\'].\' AND U1.Username LIKE \"%\'.$pattern.\'%\")) \';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce le richieste di amicizia ancora in sospeso per l\'utente loggato\r\nfunction getFriendReqs() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT A.ID, \'.\r\n					\'A.Utente1 AS UserID, \'.\r\n			 		\'U1.Username, \'.\r\n			 		\'U1.Image \'.\r\n			 \'FROM utente U1 INNER JOIN amicizia A ON A.Utente1=U1.ID \'.\r\n			 \'WHERE A.DataAmicizia IS NULL \'.\r\n			 \'  AND A.Utente2=\'.$_SESSION[\'userID\'];\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce i nomi utenti registrati presso il sito like $pattern\r\nfunction getUsersLike($pattern) {\r\n	global $dbmanager;\r\n\r\n	$pattern = $dbmanager->sqlInjectionFilter($pattern);\r\n\r\n	$query = \'SELECT U.ID, U.Username \'.\r\n			 \'FROM utente U \'.\r\n			 \'WHERE U.Username LIKE \"%\'.$pattern.\'%\" \'.\r\n			   \'AND U.Username <> \"\'.$_SESSION[\'username\'].\'\"\';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce l\'username di un utente a partire dal suo id\r\nfunction id2Username($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT U.Username \'.\r\n			 \'FROM utente U \'.\r\n			 \'WHERE U.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce il formato immagine dell\'immagine utente\r\nfunction id2Pic($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT U.Image \'.\r\n			 \'FROM utente U \'.\r\n			 \'WHERE U.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result==null || $result->num_rows == 0)\r\n		return null;\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce l\'utente\r\nfunction getUser($id) {\r\n	global $dbmanager;\r\n\r\n	if ($id == null) {\r\n		return null;\r\n	}\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT U.* \'.\r\n			 \'FROM utente U \'.\r\n			 \'WHERE U.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce una richiesta a partire dall\'id\r\nfunction getRequest($id) {\r\n	global $dbmanager;\r\n\r\n	if ($id == null) {\r\n		return null;\r\n	}\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT R.* \'.\r\n			 \'FROM richiesta R \'.\r\n			 \'WHERE R.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce la risposta di id $id\r\nfunction getReply($id) {\r\n	global $dbmanager;\r\n\r\n	if ($id == null) {\r\n		return null;\r\n	}\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT R.* \'.\r\n			 \'FROM risposta R \'.\r\n			 \'WHERE R.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce i codici proposti come risposta di una richiesta di id $id\r\nfunction getReplies($id) {\r\n	global $dbmanager;\r\n\r\n	if ($id==null) {\r\n		return null;\r\n	}\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT RA.*, U.Username \'.\r\n			 \'FROM risposta RA INNER JOIN utente U ON U.ID=RA.Autore \'.\r\n			 \'WHERE RA.Richiesta=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Controlla se l\'utente loggato e\' amico di quello passato\r\nfunction checkFriendship($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT IF(A.DataAmicizia IS NULL, \'.\r\n						\'IF( A.Utente1=\'.$_SESSION[\'userID\'].\', 1, 0), \'.\r\n					  	\'2 ) AS Flag \'.\r\n			 \'FROM amicizia A \'.\r\n			 \'WHERE (A.Utente1=\'.$_SESSION[\'userID\'].\' AND A.Utente2=\'.$id.\') \'.\r\n			 	\'OR (A.Utente1=\'.$id.\' AND A.Utente2=\'.$_SESSION[\'userID\'].\') \';\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	// Se non ci sono risultati imposto il valore 0\r\n	if($result == null || $result->num_rows == 0) {\r\n		$result = [\'Flag\'=>0];\r\n	} else {\r\n		$result = $result->fetch_assoc();\r\n	}\r\n\r\n	return $result[\'Flag\'];\r\n}\r\n\r\n// Restituisce le richieste nel database in base alle informazioni fornite\r\nfunction getRequestsLike($title, $author, $language) {\r\n	global $dbmanager;\r\n\r\n	if ($title==null && $author == null && $language==null)\r\n		return null;\r\n\r\n	$title = $dbmanager->sqlInjectionFilter($title);\r\n	$author = $dbmanager->sqlInjectionFilter($author);\r\n	$language = $dbmanager->sqlInjectionFilter($language);\r\n\r\n	$query = \'SELECT R.*, U.Username, IFNULL(DT.NumRisposte, 0) AS NumRisposte \'.\r\n			 \'FROM (richiesta R INNER JOIN utente U ON U.ID=R.Autore) \'.\r\n			 	\' LEFT OUTER JOIN ( \'.\r\n			 		\'SELECT R2.ID, count(*) AS NumRisposte \'.\r\n			 		\'FROM richiesta R2 INNER JOIN risposta RA ON RA.Richiesta = R2.ID \'.\r\n			 		\'GROUP BY R2.ID \'.\r\n			 	\' ) DT ON DT.ID=R.ID \'.\r\n			 \'WHERE \'.\r\n			 		(($title!=null)? \' R.Titolo LIKE \"%\'.$title.\'%\" AND \' : \'\' ).\r\n			 		(($author!=null)? \' U.Username LIKE \"%\'.$author.\'%\" AND \' : \'\' ).\r\n			 		(($language!=null)? \' R.Linguaggio LIKE \"%\'.$language.\'%\" AND \' : \'\' ).\r\n			 		\' 1 \'.\r\n			 \'ORDER BY R.Istante DESC\';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce il numero di like di un codice\r\nfunction retLikes($code) {\r\n	global $dbmanager;\r\n\r\n	if ($code==null) {\r\n		return 0;\r\n	}\r\n\r\n	$code = $dbmanager->sqlInjectionFilter($code);\r\n\r\n	$query = \'SELECT count(*) AS Likes \'.\r\n			 \'FROM risposta R INNER JOIN likes L ON L.Risposta=R.ID \'.\r\n			 \'WHERE R.ID=\'.$code;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if ($result==null)\r\n		return null;\r\n\r\n	if ($result->num_rows==0)\r\n		return 0;\r\n\r\n	$result = $result->fetch_assoc();\r\n\r\n	return $result[\'Likes\'];\r\n}\r\n\r\n// Restituisce il numero di dislike di un codice\r\nfunction retDislikes($code) {\r\n	global $dbmanager;\r\n\r\n	if ($code==null) {\r\n		return 0;\r\n	}\r\n\r\n	$code = $dbmanager->sqlInjectionFilter($code);\r\n\r\n	$query = \'SELECT count(*) AS Dislikes \'.\r\n			 \'FROM risposta R INNER JOIN dislikes D ON D.Risposta=R.ID \'.\r\n			 \'WHERE R.ID=\'.$code;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if ($result==null)\r\n		return null;\r\n\r\n	if ($result->num_rows==0)\r\n		return 0;\r\n\r\n	$result = $result->fetch_assoc();\r\n\r\n	return $result[\'Dislikes\'];\r\n}\r\n\r\n// Restituisce vero se l\'utente ha già messo mi piace al codice\r\nfunction retIsLiked($code) {\r\n	global $dbmanager;\r\n\r\n	if ($code==null) {\r\n		return null;\r\n	}\r\n\r\n	$code = $dbmanager->sqlInjectionFilter($code);\r\n\r\n	$query = \'SELECT IF( EXISTS ( \'.\r\n				\'SELECT * \'.\r\n				\'FROM likes L \'.\r\n				\'WHERE L.Utente=\'.$_SESSION[\'userID\'].\' \'.\r\n				\'AND L.Risposta=\'.$code.\' \'.\r\n			 \' ) ,1,0) AS Result \';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if ($result==null)\r\n		return null;\r\n\r\n	if ($result->num_rows==0)\r\n		return null;\r\n\r\n	$result = $result->fetch_assoc();\r\n\r\n	return $result[\'Result\'];\r\n}\r\n\r\n// Restituisce vero se l\'utente ha già messo mi piace al codice\r\nfunction retIsDisliked($code) {\r\n	global $dbmanager;\r\n\r\n	if ($code==null) {\r\n		return null;\r\n	}\r\n\r\n	$code = $dbmanager->sqlInjectionFilter($code);\r\n\r\n	$query = \'SELECT IF( EXISTS ( \'.\r\n				\'SELECT * \'.\r\n				\'FROM dislikes L \'.\r\n				\'WHERE L.Utente=\'.$_SESSION[\'userID\'].\' \'.\r\n				\'AND L.Risposta=\'.$code.\' \'.\r\n			 \' ) ,1,0) AS Result \';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if ($result==null)\r\n		return null;\r\n\r\n	if ($result->num_rows==0)\r\n		return null;\r\n\r\n	$result = $result->fetch_assoc();\r\n\r\n	return $result[\'Result\'];\r\n}\r\n\r\n?>',2,3,1);
/*!40000 ALTER TABLE `messaggio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migliore`
--

DROP TABLE IF EXISTS `migliore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migliore` (
  `Richiesta` int(11) NOT NULL,
  `Risposta` int(11) NOT NULL,
  PRIMARY KEY (`Richiesta`,`Risposta`),
  KEY `Risposta` (`Risposta`),
  CONSTRAINT `migliore_ibfk_1` FOREIGN KEY (`Richiesta`) REFERENCES `richiesta` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `migliore_ibfk_2` FOREIGN KEY (`Risposta`) REFERENCES `risposta` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migliore`
--

LOCK TABLES `migliore` WRITE;
/*!40000 ALTER TABLE `migliore` DISABLE KEYS */;
/*!40000 ALTER TABLE `migliore` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio`
--

DROP TABLE IF EXISTS `portfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio` (
  `Utente` int(11) NOT NULL,
  `Risposta` int(11) NOT NULL,
  PRIMARY KEY (`Utente`,`Risposta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio`
--

LOCK TABLES `portfolio` WRITE;
/*!40000 ALTER TABLE `portfolio` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolio` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `richiesta`
--

LOCK TABLES `richiesta` WRITE;
/*!40000 ALTER TABLE `richiesta` DISABLE KEYS */;
INSERT INTO `richiesta` VALUES (1,'2017-12-18 19:14:54','Multi-threading e socket server.','Necessito di libreria multi threading per connesioni socket server.','Java',1,3),(2,'2017-12-18 23:32:04','OpenGL videogame.','Libreria per videogiochi che sfrutti la OpenGL.','C++',1,1),(3,'2017-12-18 23:45:14','RestFul API','Vorrei sviluppare una API Restful che permetta di ottenere posizione GPS.','Qualunque',1,1),(4,'2017-12-19 00:43:57','Richiesta 8','Come da titolo.','Python',1,3),(5,'2017-12-19 00:43:57','Richiesta 404.','Come da titolo.','C#',1,3),(6,'2017-12-19 00:44:53','Richiesta 996','Come da titolo.','Verilog',1,3),(7,'2018-02-20 15:54:05','Nuova richiesta di prova','Una richiesta qualunque','Python',0,2);
/*!40000 ALTER TABLE `richiesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `risponde`
--

DROP TABLE IF EXISTS `risponde`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `risponde` (
  `Messaggio` int(11) NOT NULL,
  `Risposta` int(11) NOT NULL,
  PRIMARY KEY (`Messaggio`,`Risposta`),
  KEY `Risposta` (`Risposta`),
  CONSTRAINT `risponde_ibfk_1` FOREIGN KEY (`Messaggio`) REFERENCES `messaggio` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `risponde_ibfk_2` FOREIGN KEY (`Risposta`) REFERENCES `risposta` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risponde`
--

LOCK TABLES `risponde` WRITE;
/*!40000 ALTER TABLE `risponde` DISABLE KEYS */;
/*!40000 ALTER TABLE `risponde` ENABLE KEYS */;
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
  CONSTRAINT `risposta_ibfk_1` FOREIGN KEY (`Richiesta`) REFERENCES `richiesta` (`ID`) ON DELETE NO ACTION,
  CONSTRAINT `risposta_ibfk_2` FOREIGN KEY (`Autore`) REFERENCES `utente` (`ID`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `risposta`
--

LOCK TABLES `risposta` WRITE;
/*!40000 ALTER TABLE `risposta` DISABLE KEYS */;
INSERT INTO `risposta` VALUES (1,'<ok>',2,'2017-12-22',2),(3,'<?php\r\n/* Questo file contiene funzioni per ottenere varie informazioni dal database sottoforma di object. */\r\n\r\nrequire_once __DIR__.\'/../path.php\';\r\nrequire UTILS_DIR.\'managerDB.php\';\r\n\r\n// Ordina le richieste di codici per popolarita\'\r\nfunction topRatedRequests() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT DT.* \'.\r\n			 \'FROM ( \'.\r\n				  \'SELECT R.*, \'.\r\n				  		\'U.ID AS UserID, \'.\r\n						\'U.Username AS Autore, \'.\r\n						\'U.Image, \'.\r\n						\'count(DISTINCT RA.ID) AS NumResponses \'.\r\n				  \'FROM (richiesta R INNER JOIN utente U ON U.ID=R.Autore) \'.\r\n				  		\'LEFT OUTER JOIN risposta RA ON RA.Richiesta=R.ID \'.\r\n				  \'WHERE R.Visibilita=1 \'.\r\n				  \'GROUP BY R.ID, U.Username \'.\r\n				  \'ORDER BY NumResponses DESC, R.Istante DESC \'.\r\n			 \') DT \'.\r\n			 \'WHERE DT.NumResponses>=20\';\r\n\r\n	$rows = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if ($rows == null || $rows->num_rows == 0)\r\n		return null;\r\n\r\n	return $rows;\r\n}\r\n\r\n// Restituisce le richieste dei soli amici\r\nfunction friendsRequests() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT R.*, U.ID AS UserID, U.Username AS Autore, U.Image \'.\r\n			 \'FROM richiesta R INNER JOIN utente U ON U.ID = R.Autore \'.\r\n			 \'WHERE EXISTS ( \'.\r\n			 \'   SELECT * \'.\r\n			 \'   FROM amicizia A \'.\r\n			 \'   WHERE (A.Utente1=R.Autore AND A.Utente2=\'.$_SESSION[\'userID\'].\') \'.\r\n			 \'   	OR (A.Utente1=\'.$_SESSION[\'userID\'].\' AND A.Utente2=R.Autore) \'.\r\n			 \'      AND A.DataAmicizia IS NOT NULL \'.\r\n			 \') AND R.Visibilita=1 \'.\r\n			 \'ORDER BY R.Istante DESC\';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce le richieste ordinate temporalmente\r\nfunction recentRequests() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT R.*, U.ID AS UserID, U.Username AS Autore, U.Image \'.\r\n			 \'FROM richiesta R INNER JOIN utente U ON U.ID = R.Autore \'.\r\n			 \'WHERE R.Visibilita=1 \'.\r\n			 \'ORDER BY R.Istante DESC\';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce il numero di codici in totale nella piattaforma\r\nfunction getNumOfTotalCodes() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT count(*) AS Num \'.\r\n			 \'FROM risposta\';\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result->num_rows==0)\r\n		return 0;\r\n	return $result->fetch_assoc()[\'Num\'];\r\n}\r\n\r\n// Restituisce il numero di codici dell\'utente $id\r\nfunction getNumOfCodes($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT count(ID) AS Num \'.\r\n		     \'FROM risposta \'.\r\n		     \'WHERE Autore=\'.$id;\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result->num_rows==0)\r\n		return 0;\r\n	return $result->fetch_assoc()[\'Num\'];\r\n}\r\n\r\n// Restituisce il numero di richieste effettuate dall\'utente loggato\r\nfunction getNumOfRequests($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT count(ID) AS Num \'.\r\n			 \'FROM richiesta \'.\r\n			 \'WHERE Autore=\'.$id;\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result->fetch_assoc()[\'Num\'];\r\n}\r\n\r\n// Restituisce il numero di commenti effettuati dall\'utente loggato\r\nfunction getNumOfComments($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT count(ID) AS Num \'.\r\n		     \'FROM commento \'.\r\n		     \'WHERE Autore=\'.$id;\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result->num_rows==0)\r\n		return 0;\r\n	return $result->fetch_assoc()[\'Num\'];\r\n}\r\n\r\n// Restituisce l\'esperienza dell\'utente loggato\r\nfunction getExperience($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT Experience \'.\r\n			 \'FROM utente \'.\r\n			 \'WHERE ID=\'.$id;\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result==null)\r\n		return 0;\r\n	return $result->fetch_assoc()[\'Experience\'];\r\n}\r\n\r\n// Restituisce il contenuto dell\'email di id $mailID\r\nfunction getMail($mailID) {\r\n	global $dbmanager;\r\n\r\n	$mailID = $dbmanager->sqlInjectionFilter($mailID);\r\n\r\n	$query = \'SELECT M.*, U.Username AS UsernameMittente, U2.Username AS UsernameDestinatario \'.\r\n			 \'FROM ( \'.\r\n			 	\'SELECT * FROM messaggio \'.\r\n			 	\'WHERE Destinatario=\'.$_SESSION[\'userID\'].\' \'.\r\n			 	\'OR Mittente=\'.$_SESSION[\'userID\'].\' \'.\r\n			 \') M \'.\r\n			 \' INNER JOIN utente U ON U.ID=M.Mittente \'.\r\n			 \'   INNER JOIN utente U2 ON U2.ID=M.Destinatario \'.\r\n			 \'WHERE M.ID=\'.$mailID;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n	return $result;\r\n}\r\n\r\n// Restituisce tutte le email in arrivo all\'utente loggato\r\nfunction getMailsIn() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT M.*, U1.ID, U1.Username AS UsernameTarget, U1.Image, 1 AS dir \'.\r\n			 \'FROM messaggio M INNER JOIN utente U1 ON U1.ID=M.Mittente \'.\r\n			 \'WHERE M.Destinatario=\'.$_SESSION[\'userID\'].\' \'.\r\n			 \'ORDER BY M.Istante DESC\';\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n	return $result;\r\n}\r\n\r\n// Restituisce tutte le email inviate dall\'utente loggato\r\nfunction getMailsOut() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT M.*, U1.ID, U1.Username AS UsernameTarget, U1.Image, 0 AS dir \'.\r\n			 \'FROM messaggio M INNER JOIN utente U1 ON U1.ID=M.Destinatario \'.\r\n			 \'WHERE M.Mittente=\'.$_SESSION[\'userID\'].\' \'.\r\n			 \'ORDER BY M.Istante DESC\';\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n	return $result;\r\n}\r\n\r\n// Restituisce gli amici dell\'utente loggato con username like $pattern\r\nfunction getFriendsOfUserLike($pattern) {\r\n	global $dbmanager;\r\n\r\n	$pattern = $dbmanager->sqlInjectionFilter($pattern);\r\n\r\n	$query = \'SELECT IF(U1.Username=\"\'.$_SESSION[\'username\'].\'\",U2.Username,U1.Username) AS Username \'.\r\n			 \'FROM utente U1 INNER JOIN amicizia A ON A.Utente1=U1.ID \'.\r\n			 \'    INNER JOIN utente U2 ON A.Utente2 = U2.ID \'.\r\n			 \'WHERE A.DataAmicizia IS NOT NULL \'.\r\n			 \'  AND ((U1.ID=\'.$_SESSION[\'userID\'].\' AND U2.Username LIKE \"%\'.$pattern.\'%\") \'.\r\n			 \'       OR (U2.ID=\'.$_SESSION[\'userID\'].\' AND U1.Username LIKE \"%\'.$pattern.\'%\")) \';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce le richieste di amicizia ancora in sospeso per l\'utente loggato\r\nfunction getFriendReqs() {\r\n	global $dbmanager;\r\n\r\n	$query = \'SELECT A.ID, \'.\r\n					\'A.Utente1 AS UserID, \'.\r\n			 		\'U1.Username, \'.\r\n			 		\'U1.Image \'.\r\n			 \'FROM utente U1 INNER JOIN amicizia A ON A.Utente1=U1.ID \'.\r\n			 \'WHERE A.DataAmicizia IS NULL \'.\r\n			 \'  AND A.Utente2=\'.$_SESSION[\'userID\'];\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce i nomi utenti registrati presso il sito like $pattern\r\nfunction getUsersLike($pattern) {\r\n	global $dbmanager;\r\n\r\n	$pattern = $dbmanager->sqlInjectionFilter($pattern);\r\n\r\n	$query = \'SELECT U.ID, U.Username \'.\r\n			 \'FROM utente U \'.\r\n			 \'WHERE U.Username LIKE \"%\'.$pattern.\'%\" \'.\r\n			   \'AND U.Username <> \"\'.$_SESSION[\'username\'].\'\"\';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce l\'username di un utente a partire dal suo id\r\nfunction id2Username($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT U.Username \'.\r\n			 \'FROM utente U \'.\r\n			 \'WHERE U.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce il formato immagine dell\'immagine utente\r\nfunction id2Pic($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT U.Image \'.\r\n			 \'FROM utente U \'.\r\n			 \'WHERE U.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	if($result==null || $result->num_rows == 0)\r\n		return null;\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce l\'utente\r\nfunction getUser($id) {\r\n	global $dbmanager;\r\n\r\n	if ($id == null) {\r\n		return null;\r\n	}\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT U.* \'.\r\n			 \'FROM utente U \'.\r\n			 \'WHERE U.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce una richiesta a partire dall\'id\r\nfunction getRequest($id) {\r\n	global $dbmanager;\r\n\r\n	if ($id == null) {\r\n		return null;\r\n	}\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT R.* \'.\r\n			 \'FROM richiesta R \'.\r\n			 \'WHERE R.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce la risposta di id $id\r\nfunction getReply($id) {\r\n	global $dbmanager;\r\n\r\n	if ($id == null) {\r\n		return null;\r\n	}\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT R.* \'.\r\n			 \'FROM risposta R \'.\r\n			 \'WHERE R.ID=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Restituisce i codici proposti come risposta di una richiesta di id $id\r\nfunction getReplies($id) {\r\n	global $dbmanager;\r\n\r\n	if ($id==null) {\r\n		return null;\r\n	}\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT RA.*, U.Username \'.\r\n			 \'FROM risposta RA INNER JOIN utente U ON U.ID=RA.Autore \'.\r\n			 \'WHERE RA.Richiesta=\'.$id;\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n// Controlla se l\'utente loggato e\' amico di quello passato\r\nfunction checkFriendship($id) {\r\n	global $dbmanager;\r\n\r\n	$id = $dbmanager->sqlInjectionFilter($id);\r\n\r\n	$query = \'SELECT IF(A.DataAmicizia IS NULL, \'.\r\n						\'IF( A.Utente1=\'.$_SESSION[\'userID\'].\', 1, 0), \'.\r\n					  	\'2 ) AS Flag \'.\r\n			 \'FROM amicizia A \'.\r\n			 \'WHERE (A.Utente1=\'.$_SESSION[\'userID\'].\' AND A.Utente2=\'.$id.\') \'.\r\n			 	\'OR (A.Utente1=\'.$id.\' AND A.Utente2=\'.$_SESSION[\'userID\'].\') \';\r\n	$result = $dbmanager->performQuery($query);\r\n	$dbmanager->closeConnection();\r\n\r\n	// Se non ci sono risultati imposto il valore 0\r\n	if($result == null || $result->num_rows == 0) {\r\n		$result = [\'Flag\'=>0];\r\n	} else {\r\n		$result = $result->fetch_assoc();\r\n	}\r\n\r\n	return $result[\'Flag\'];\r\n}\r\n\r\n// Restituisce le richieste nel database in base alle informazioni fornite\r\nfunction getRequestsLike($title, $author, $language) {\r\n	global $dbmanager;\r\n\r\n	if ($title==null && $author == null && $language==null)\r\n		return null;\r\n\r\n	$title = $dbmanager->sqlInjectionFilter($title);\r\n	$author = $dbmanager->sqlInjectionFilter($author);\r\n	$language = $dbmanager->sqlInjectionFilter($language);\r\n\r\n	$query = \'SELECT R.*, U.Username, IFNULL(DT.NumRisposte, 0) AS NumRisposte \'.\r\n			 \'FROM (richiesta R INNER JOIN utente U ON U.ID=R.Autore) \'.\r\n			 	\' LEFT OUTER JOIN ( \'.\r\n			 		\'SELECT R2.ID, count(*) AS NumRisposte \'.\r\n			 		\'FROM richiesta R2 INNER JOIN risposta RA ON RA.Richiesta = R2.ID \'.\r\n			 		\'GROUP BY R2.ID \'.\r\n			 	\' ) DT ON DT.ID=R.ID \'.\r\n			 \'WHERE \'.\r\n			 		(($title!=null)? \' R.Titolo LIKE \"%\'.$title.\'%\" AND \' : \'\' ).\r\n			 		(($author!=null)? \' U.Username LIKE \"%\'.$author.\'%\" AND \' : \'\' ).\r\n			 		(($language!=null)? \' R.Linguaggio LIKE \"%\'.$language.\'%\" AND \' : \'\' ).\r\n			 		\' 1 \'.\r\n			 \'ORDER BY R.Istante DESC\';\r\n\r\n	$result = $dbmanager->performQuery($query);\r\n\r\n	$dbmanager->closeConnection();\r\n\r\n	return $result;\r\n}\r\n\r\n?>',2,'2018-02-14',3);
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
  `Image` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utente`
--

LOCK TABLES `utente` WRITE;
/*!40000 ALTER TABLE `utente` DISABLE KEYS */;
INSERT INTO `utente` VALUES (1,'m.virdis1@studenti.unipi.it','NP-Ok','Alberto Maria','Nobili','17ca572925b63317ff8e75be03d96eae1224ddef0269d3d5f8792c4221c77fc7',0,0,NULL),(2,'pweb@unipi.it','pweb','Mario','Virdis','7ea964fea37a8edd647bd2e3b0a64ba06d2df93857d6177d4fb9854f5e936d30',0,0,'image/png'),(3,'virdis.mario97@gmail.com','VMind','Mario','Virdis','17ca572925b63317ff8e75be03d96eae1224ddef0269d3d5f8792c4221c77fc7',1,0,'image/jpeg'),(4,'test1@test','VMmai','Marco','Luciolli','7ea964fea37a8edd647bd2e3b0a64ba06d2df93857d6177d4fb9854f5e936d30',0,0,NULL),(5,'anna9709@libero.it','annacapitani','Anna','Capitani','b2290c781112630d050ee2359c4847b680991b1d33feab3b4f78eea724aed03c',0,0,'image/x-icon');
/*!40000 ALTER TABLE `utente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `valuta`
--

DROP TABLE IF EXISTS `valuta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `valuta` (
  `Utente` int(11) NOT NULL,
  `Risposta` int(11) NOT NULL,
  `Stelle` int(11) NOT NULL,
  PRIMARY KEY (`Utente`,`Risposta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `valuta`
--

LOCK TABLES `valuta` WRITE;
/*!40000 ALTER TABLE `valuta` DISABLE KEYS */;
/*!40000 ALTER TABLE `valuta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-02-21 17:39:05
