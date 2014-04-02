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
CREATE DATABASE /*!32312 IF NOT EXISTS*/`home` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `home`;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_agent` */

insert  into `home_agent`(`id`,`agent_name`,`agent_address`,`agent_email`,`agent_phone`,`agent_fax`) values (1,'Evolable haohoaho','9, Dinh Tien Hoang, Quan 1.','evolable@evolableasia.vn','0800008','089899899'),(2,'YouNet','23, Lu gia, quan 11','younet@yahoo.com','009000709','');

/*Table structure for table `home_broker_company` */

DROP TABLE IF EXISTS `home_broker_company`;

CREATE TABLE `home_broker_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `broker_company_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broker_company_address` text COLLATE utf8_unicode_ci,
  `broker_company_phone` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broker_company_email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broker_company_fax` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broker_company_undertake` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_broker_company` */

insert  into `home_broker_company`(`id`,`user_id`,`broker_company_name`,`broker_company_address`,`broker_company_phone`,`broker_company_email`,`broker_company_fax`,`broker_company_undertake`) values (3,63,'Sumo','Hokaido','098779438','hokaido@hokaido.com','123456789','Yamaha'),(4,64,'Homes','Hockaido','09879998','homes@hokaido.com','097889798','Exciter'),(5,64,'HAGL','Gia Lai, VietNam','80976634','hagl@hagl.com','253465467','Bầu Đức');

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
  `house_owner_id` int(11) DEFAULT NULL,
  `broker_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_house` */

insert  into `home_house`(`id`,`user_id`,`house_name`,`house_address`,`house_size`,`house_area`,`house_original_price`,`house_status`,`house_build_time`,`house_type`,`house_description`,`house_room_type`,`house_photo`,`house_administrative_expense`,`house_discount`,`house_structure`,`house_owner_id`,`broker_id`) values (9,63,'Biệt Thự 222','20 , Trương Định, Q.1','2000x3000 m','Taliban',3000000000,'','03/17/2014','Cao cấp','Tòa nhà chống động đất','Cao Cầu','',13000,0.5,'Binladen',4,3),(10,63,'Biá»‡t thá»± 777','20, TrÆ°Æ¡ng Äá»‹nh, Q.1','2000x3000 m','Do Thi Phu My Hung',1800000000,'','03/17/2014','Chung Cu','Nha rong thoang mat, an toan, gan khu truong hoc va cho','Tuong duc','',2000,2,'Binladen',0,0),(11,63,'Biá»‡t thá»± 777','400 nguyá»…n Thá»‹ tháº­p','1000x1000m','Khu tá»± trá»‹ TÃ¢n CÆ°Æ¡ng',1800000000,'','03/11/2014','Chung Cu','Nha rong thoang mat, an toan, gan khu truong hoc va cho','Tuong duc','',432543,0.5,'Binladen',5,0),(12,63,'Biá»‡t thá»± 247','20, TrÆ°Æ¡ng Äá»‹nh, Q.1','2000x3000 m','Do Thi Phu My Hung',3000000000,'','','','','','',0,0,'',6,0),(13,63,'Biá»‡t thá»± 247','20, TrÆ°Æ¡ng Äá»‹nh, Q.1','1000x1000m','Do Thi Phu My Hung',1800000000,'','','','','','',0,0,'',7,0),(14,63,'Biá»‡t thá»± 777','25, Le Van Luong, P.Tan Quy, Q.7,TPHCM','2000x3000 m','Taliban',3000000000,'','','','','','',0,0,'',8,0),(15,63,'Biá»‡t thá»± 247','20, TrÆ°Æ¡ng Äá»‹nh, Q.1','300x2000 m','Do Thi Phu My Hung',1800000000,'','','','','','',0,0,'',9,0),(16,63,'Biệt thự 777','25, Le Van Luong, P.Tan Quy, Q.7,TPHCM','1000x1000m','Taliban',1800000000,'','','','','','',0,0,'',0,3),(17,63,'Biệt thự 777','25, Le Van Luong, P.Tan Quy, Q.7,TPHCM','1000x1000m','Taliban',1800000000,'','','','','','',0,0,'',0,3),(18,63,'Biá»‡t thá»± 777','25, Le Van Luong, P.Tan Quy, Q.7,TPHCM','1000x1000m','Taliban',1800000000,'','','','','','',0,0,'',10,0),(19,63,'Biá»‡t thá»± 247','400 nguyá»…n Thá»‹ tháº­p','2000x3000 m','Taliban',1800000000,'','','','','','',0,0,'',11,0),(20,63,'Biệt thự 777','25, Le Van Luong, P.Tan Quy, Q.7,TPHCM','1000x1000m','Taliban',3000000000,'','','','','','',0,0,'',12,NULL);

/*Table structure for table `home_house_owner` */

DROP TABLE IF EXISTS `home_house_owner`;

CREATE TABLE `home_house_owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_owner_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_owner_address` text COLLATE utf8_unicode_ci,
  `house_owner_phone` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_owner_fax` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_owner_email` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_owner_photo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_house_owner` */

insert  into `home_house_owner`(`id`,`house_owner_name`,`house_owner_address`,`house_owner_phone`,`house_owner_fax`,`house_owner_email`,`house_owner_photo`) values (2,'Bau Duc','234 Gia Lai-KonTum','09344823948','0948583982','bauduc@hagl.com.vn',''),(3,'Quang VÅ©','20/10 Ä‘Æ°á»ng 77, p.TÃ¢n Quy, Q.7','09080','98923','quangvu@yahoo.com',''),(4,'Lý Hạo','20/10, đường 77, P.Tân Quy, Q.7','309534534','4095t340t6','luubi@truongphi.com',''),(5,'LÆ°u Bá»‹','234 Gia Lai-KonTum','09344823948','0948583982','bauduc@hagl.com.vn',''),(6,'LÆ°u Bá»‹','','','','',''),(7,'Bau Duc','','','','',''),(8,'Quang VÅ©','','','','',''),(9,'LÆ°u Bá»‹','','','','',''),(10,'Quang VÅ©','','','','',''),(11,'Bau Duc','','','','',''),(12,'Quang Vũ','','','','',''),(13,'Binladen','20/10, đường 77, P.Tân Quy, Q.7','309534534','4095t340t6','luubi@truongphi.com','');

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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_user` */

insert  into `home_user`(`id`,`agent_id`,`user_username`,`user_password`,`user_fname`,`user_lname`,`user_code`,`user_address`,`user_email`,`user_phone`,`user_gender`,`user_birthday`,`user_photo`,`user_authorities`,`user_position`,`user_locked`,`user_target`,`user_path_photo`,`user_path_thumb`) values (63,0,'lyhao','d67f543992e78fd5521f090bde352690','ly','aa','4S7Yk9Ke','空室を確認したい、物件を見たい、詳しい情報を知りたい、など','lyhao@ambition.com','','male','','0_9874.jpg','1','',0,0,'./uploads_user/1000/63/','0_9874_thumb.jpg'),(64,1,'12345','e4e253c1e6bd413af96cedb40d1a8bab','lya','thong','LDJSyIKy','Q 7','admin@ambition.com','878567876','male','03/11/2014','0_6259.jpg','2','Leader',0,276787,'./uploads_user/1000/64/','0_6259_thumb.jpg'),(65,2,'123456','ccd3cc7cd7e60c0ea0bac70b25fcbdfc','sato','hanayuki','EN35McFo','Bo Dao Nha','diepvanhao@yahoo.com','123456789','other','04/28/1984','0_9543.jpg','3','Giam doc',1,3546,'./uploads_user/1000/65/','0_9543_thumb.jpg'),(66,2,'messi','e3ce6fcddd9278db1c501f172a12f75f','leon','messia','1Lovit7J','Argentina','diepvanhao@hotmail.com','','male','','0_8096.jpg','4','',0,0,'./uploads_user/1000/66/','0_8096_thumb.jpg');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
