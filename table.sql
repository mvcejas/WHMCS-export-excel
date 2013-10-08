/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.32-0ubuntu0.13.04.1 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `manualentry` (
	`id` int ,
	`descrizione` text (196605),
	`fatt` int ,
	`importo` Decimal ,
	`cod` char ,
	`c_entrate` Decimal ,
	`c_uscite` Decimal ,
	`b_entrate` Decimal ,
	`b_uscite` Decimal ,
	`p_lordo` Decimal ,
	`p_netto` Decimal ,
	`p_uscite` Decimal ,
	`datepaid` date ,
	`notes` text (196605)
); 
