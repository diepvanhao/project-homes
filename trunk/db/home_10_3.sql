/*
SQLyog Community v11.31 (32 bit)
MySQL - 5.6.14 : Database - home
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


/*Table structure for table `home_agent` */

DROP TABLE IF EXISTS `home_agent`;

CREATE TABLE `home_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_address` text COLLATE utf8_unicode_ci,
  `agent_email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_phone` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agent_fax` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_agent` */

/*Table structure for table `home_broker_company` */

DROP TABLE IF EXISTS `home_broker_company`;

CREATE TABLE `home_broker_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `broker_company_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broker_company_address` text COLLATE utf8_unicode_ci,
  `broker_company_phone` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broker_company_email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broker_company_fax` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broker_company_undertake` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_broker_company` */

/*Table structure for table `home_client` */

DROP TABLE IF EXISTS `home_client`;

CREATE TABLE `home_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `client_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_birthday` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_address` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_phone` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_income` float DEFAULT NULL,
  `client_occupation` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_company` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_fax` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_gender` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_reason_change` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_time_change` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_photo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_resident_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_resident_phone` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_client` */

/*Table structure for table `home_contract` */

DROP TABLE IF EXISTS `home_contract`;

CREATE TABLE `home_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_contract` */

/*Table structure for table `home_contract_detail` */

DROP TABLE IF EXISTS `home_contract_detail`;

CREATE TABLE `home_contract_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT NULL,
  `contract_plus_money` float DEFAULT NULL,
  `contract_cost` float DEFAULT NULL,
  `contract_total` float DEFAULT NULL,
  `contract_signature_day` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_handover_day` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_condition` text COLLATE utf8_unicode_ci,
  `contract_valuation` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_date_create` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_date_update` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_cancel` tinyint(1) DEFAULT '0',
  `contract_period_from` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_period_to` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contract_deposit_1` float DEFAULT NULL,
  `contract_deposit_2` float DEFAULT NULL,
  `contract_key_money` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_contract_detail` */

/*Table structure for table `home_history_aspirations` */

DROP TABLE IF EXISTS `home_history_aspirations`;

CREATE TABLE `home_history_aspirations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `aspirations_type_house` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aspirations_rent_cost` float DEFAULT NULL,
  `aspirations_type_room` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aspirations_build_time` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aspirations_area` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aspirations_size` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `aspirations_comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_history_aspirations` */

/*Table structure for table `home_history_log` */

DROP TABLE IF EXISTS `home_history_log`;

CREATE TABLE `home_history_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `log_time_call` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_time_arrive_company` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_comment` text COLLATE utf8_unicode_ci,
  `log_date_appointment` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_status_appointment` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_shop_sign` tinyint(1) DEFAULT NULL,
  `log_local_sign` tinyint(1) DEFAULT NULL,
  `log_introduction` tinyint(1) DEFAULT NULL,
  `log_tel` tinyint(1) DEFAULT NULL,
  `log_mail` tinyint(1) DEFAULT NULL,
  `log_flyer` tinyint(1) DEFAULT NULL,
  `log_line` tinyint(1) DEFAULT NULL,
  `log_contract_head_offcie` tinyint(1) DEFAULT NULL,
  `log_tel_status` tinyint(1) DEFAULT NULL,
  `log_mail_status` tinyint(1) DEFAULT NULL,
  `log_revisit` int(2) DEFAULT NULL,
  `log_time_mail` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_history_log` */

/*Table structure for table `home_house` */

DROP TABLE IF EXISTS `home_house`;

CREATE TABLE `home_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `house_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_address` text COLLATE utf8_unicode_ci,
  `house_size` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_area` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_original_price` float DEFAULT NULL,
  `house_status` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_build_time` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_type` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_description` text COLLATE utf8_unicode_ci,
  `house_room_type` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_photo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_administrative_expense` float DEFAULT NULL,
  `house_discount` float DEFAULT NULL,
  `house_structure` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_house` */

/*Table structure for table `home_house_owner` */

DROP TABLE IF EXISTS `home_house_owner`;

CREATE TABLE `home_house_owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_id` int(11) DEFAULT NULL,
  `house_owner_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_owner_address` text COLLATE utf8_unicode_ci,
  `house_owner_phone` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_owner_fax` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_owner_email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_owner_photo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_house_owner` */

/*Table structure for table `home_introduce_house` */

DROP TABLE IF EXISTS `home_introduce_house`;

CREATE TABLE `home_introduce_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `house_id` int(11) DEFAULT NULL,
  `introduce_house_content` text COLLATE utf8_unicode_ci,
  `introduce_house_photo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_introduce_house` */

/*Table structure for table `home_order` */

DROP TABLE IF EXISTS `home_order`;

CREATE TABLE `home_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `house_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `order_rent_cost` float DEFAULT NULL,
  `order_day_create` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_status` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_comment` text COLLATE utf8_unicode_ci,
  `oder_day_update` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_id` int(11) DEFAULT NULL,
  `broker_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_order` */

/*Table structure for table `home_user` */

DROP TABLE IF EXISTS `home_user`;

CREATE TABLE `home_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) DEFAULT NULL,
  `user_username` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_password` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_fname` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_lname` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_code` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_address` text COLLATE utf8_unicode_ci,
  `user_email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_phone` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_gender` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_birthday` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_photo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_authorities` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_position` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_locked` tinyint(1) DEFAULT NULL,
  `user_target` int(11) DEFAULT NULL,
  `user_path_photo` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_path_thumb` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_user` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
