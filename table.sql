/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.32-0ubuntu0.13.04.1 : Database - whmcs
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`whmcs` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `whmcs`;

/*Table structure for table `manualentry` */

DROP TABLE IF EXISTS `manualentry`;

CREATE TABLE `manualentry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descrizione` text,
  `fatt` text,
  `importo` text,
  `cod` text,
  `c_entrate` text,
  `c_uscite` text,
  `b_entrate` text,
  `b_uscite` text,
  `p_lordo` text,
  `p_netto` text,
  `p_uscite` text,
  `datepaid` text,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
