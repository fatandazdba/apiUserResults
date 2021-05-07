-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.1.19-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para miw_results
CREATE DATABASE IF NOT EXISTS `miw_results` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `miw_results`;

-- Volcando estructura para tabla miw_results.results
CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `result` int(11) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_USER_ID_idx` (`user_id`),
  CONSTRAINT `FK_9FA3E414A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla miw_results.results: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
INSERT INTO `results` (`id`, `user_id`, `result`, `time`) VALUES
	(3, 5, 122, '2021-03-01 19:02:02'),
	(4, 1, 12, '2018-12-26 21:39:21'),
	(5, 1, 12, '2018-12-27 15:59:38'),
	(6, 1, 12, '2018-12-27 16:01:47'),
	(7, 1, 9860, '2018-01-01 12:12:12'),
	(8, 1, 4560, '2018-01-01 12:12:12'),
	(9, 1, 123, '2018-01-01 12:12:12');
/*!40000 ALTER TABLE `results` ENABLE KEYS */;

-- Volcando estructura para tabla miw_results.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `admin` tinyint(1) DEFAULT '0',
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `IDX_UNIQ_USER` (`username`),
  UNIQUE KEY `IDX_UNIQ_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Volcando datos para la tabla miw_results.users: ~26 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `email`, `enabled`, `admin`, `password`) VALUES
	(1, 'freddy_870', 'freddy@gmail.com_870', 1, 0, '$2y$10$tqKfDAaC9sIUA6mporYaq.gEYV15C6c5milXQL9FURqdUHnbVtbjm'),
	(2, 'freddy_229', 'freddy@gmail.com_229', 1, 0, '$2y$10$sFPZ0YFR4.XJSvE7LJjKYuH6EsBKBZ/xTQzfe0l4j0yTHbZB/u2e6'),
	(3, 'freddy_44', 'freddy@gmail.com_44', 1, 0, '$2y$10$TVtvfwAe41r4Tu84xlyL4eaYlP1CH2naXqA9fNlU6TvW/aM4qe7NS'),
	(4, 'freddy_273', 'freddy@gmail.com_273', 1, 0, '$2y$10$m.uQ5oRhXjxfbyE1KIkrxuG7bsImHH.ev5o5ZGSstZ.hVR/.zBEuS'),
	(5, 'freddy_192', 'freddy@gmail.com_192', 1, 0, '$2y$10$PfcQllhmGpi72ACE69eDC.ZSTmMKkbobmZ7vfhp1OHMZHos9oA3kq'),
	(7, 'freddy_183', 'freddy@gmail.com_183', 1, 0, '$2y$10$/Wp.eNH/za0Czf9pkSJRVOcHwGKUNeIFcnpQHDASm0lPJS/i8Wmmq'),
	(8, 'freddy_345', 'freddy@gmail.com_345', 1, 0, '$2y$10$/x2.yV7fSsrHawRnBno9/.Qn07vlnszSva5sDCvboIg8GPm4zGuNK'),
	(9, 'freddy_53', 'freddy@gmail.com_53', 1, 0, '$2y$10$uWBEnn3zAVb3qhTLYZhvXeQ.RpVbYY2y1LwwkeuXIToYD6tiTIEwu'),
	(19, 'freddy1420160', 'freddy1420160@live.com', 1, 0, '$2y$10$xitkan92nEvLi98HVh0.HeMM6tZqtdNbMJbq.xvpS98oQgTzLEWym'),
	(20, 'freddy960443', 'freddy960443@live.com', 1, 0, '$2y$10$jGHymivwMJKXaLulsD4Pa.y.wbr.cowRHSa5GtLKJ7jqXI5awQntO'),
	(21, 'freddy1148507', 'freddy1148507@live.com', 1, 0, '$2y$10$p6ER5qSoaGpAMsJJOHf1s....yRedHsfehNCqH2H6XeFcFeD..VLC'),
	(22, 'freddy1256396', 'freddy1256396@live.com', 1, 0, '$2y$10$.2kkK47NKLsFfIU/3VOSfuL8y62MIBijeKKDSt7atYvYrZgLJocb.'),
	(23, 'freddy805359', 'freddy805359@live.com', 1, 0, '$2y$10$ID9Uy.bHi5vrGnfxomzwRuL2cD8HnXJo1kk828M2Wejj8wcXG76Si'),
	(24, 'freddy1621928', 'freddy1621928@live.com', 1, 0, '$2y$10$2MHJYAI2Ld3OknP0EiOBHObTNphnlcQEKHYwKIH4a1g9Z/1KeI15O'),
	(25, 'freddy1409887', 'freddy1409887@live.com', 1, 0, '$2y$10$0caOaDFHu4oNlkglr4STWeFDZIBws7E70oRPtwLXUrkCBVYJBU7Ua'),
	(26, 'freddy1642209', 'freddy1642209@live.com', 1, 0, '$2y$10$4vw/jBdsmQkNAUmvzw.I/uVX0Fuc5VH298Js2UNV7fo6ozRbPfhA2'),
	(27, 'freddy893174', 'freddy893174@live.com', 1, 0, '$2y$10$lZsz9q3Yp780ZDyyoD3Qa.YMDD67a5IuWEI/uqf51EbzQ3GjLEVPa'),
	(28, 'freddy1902650', 'freddy1902650@live.com', 1, 0, '$2y$10$RtZ1eyf3O7PMOmtDcj2E6OmnsvsKM14A452TSEVdApkmqeScQ2dAq'),
	(29, 'freddy314600', 'freddy314600@live.com', 1, 0, '$2y$10$ZTPrGFCEm2G8Q6BLpvzMiOPczlxHvPTxBqbuCGJ3/Z3nudQfVdsIO'),
	(30, 'freddy2180344', 'freddy2180344@live.com', 1, 0, '$2y$10$FwWDrW79CUte6OtG/4KDq.l8uBUa1S8SB9RagEiW5gwT0rwmR57US'),
	(31, 'freddy1265575', 'freddy1265575@live.com', 1, 0, '$2y$10$6wW0V9z0N.o3IclsgUu0IuIOWKzBlp4zj5mtxaQLI5Z1okpK4R/I6'),
	(32, 'freddy1681724', 'freddy1681724@live.com', 1, 0, '$2y$10$U0iiHWkWlbpnK/XnarOVVemnESl87Z31tpqIUIsuyHLmyfn3k5wju'),
	(34, 'freddy1292954', 'freddy74281@gmail.com', 1, 0, '$2y$10$nd62dr2jBfoV56bvqvHCmubV9CSXP5LddxomQCUGm6umRIUp1RvJq'),
	(35, 'User_name1', 'User1_email@example.com', 1, 0, '$2y$10$0gxJDfFZ3zE2zgmm9aftBuqwwZuHI/vpHvymNYZ2UxOlvQ5C1wE2u'),
	(36, 'UUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUuuUUUUUU', 'User_email@example.com', 1, 0, '$2y$10$kbhYRM73oFOQGwqaHCPpSOVl8HaYByCL.fbrade0Yj2AzKKgYebIG'),
	(37, 'User_name', 'ALEX@GMAIL.COM', 1, 0, '$2y$10$SHMsO1oSjPZ2yEf/t6QEBOxi0wi1wiIEUAW5DSKf3j5qwp5uQXFeO');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
