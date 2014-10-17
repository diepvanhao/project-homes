/*Hao 4/4 */
ALTER TABLE `home_order`   
  ADD COLUMN `order_name` VARCHAR(128) NULL AFTER `id`;

/*Hao 7/4*/

ALTER TABLE `home_order`   
  CHANGE `oder_day_update` `order_day_update` VARCHAR(128) CHARSET utf8 COLLATE utf8_unicode_ci NULL;

/*Hao 11/4*/

ALTER TABLE `home_history_log`   
  ADD COLUMN `order_id` INT(11) NULL AFTER `client_id`;

/*Hao 15/4*/

ALTER TABLE `home_history_log`   
  CHANGE `log_contract_head_offcie` `log_contact_head_offcie` TINYINT(1) NULL;

ALTER TABLE `home_history_log`   
  CHANGE `log_contact_head_offcie` `log_contact_head_office` TINYINT(1) NULL;

ALTER TABLE `home_history_aspirations`   
  ADD COLUMN `user_id` INT(11) NULL AFTER `client_id`,
  ADD COLUMN `order_id` INT(11) NULL AFTER `user_id`;

/*Hao 16/4*/
ALTER TABLE `home_order`   
  ADD COLUMN `change` INT(2) NULL AFTER `broker_id`,
  ADD COLUMN `change_house_array` VARCHAR(128) NULL AFTER `change`;

/*Hao 21/4  */
ALTER TABLE `home_house`   
  DROP COLUMN `house_original_price`, 
  DROP COLUMN `house_status`, 
  DROP COLUMN `house_room_type`, 
  DROP COLUMN `house_administrative_expense`, 
  DROP COLUMN `broker_id`;

CREATE TABLE `home_room`(  
  `id` INT(11) NOT NULL,
  `broker_id` INT(11),
  `house_id` INT(11),
  PRIMARY KEY (`id`)
);

CREATE TABLE `home_room_detail`(  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `room_number` INT(11),
  `room_type` VARCHAR(128),
  `room_size` VARCHAR(128),
  `room_status` TINYINT(1),
  `room_rent` FLOAT,
  `room_key_money` FLOAT,
  `room_administative_expense` FLOAT,
  `room_deposit` FLOAT,
  `room_photo` VARCHAR(128),
  `room_id` INT(11),
  PRIMARY KEY (`id`)
);

ALTER TABLE `home_introduce_house`   
  ADD COLUMN `room_id` INT(11) NULL AFTER `house_id`;

ALTER TABLE `home_order`   
  ADD COLUMN `room_id` INT(11) NULL AFTER `house_id`;

ALTER TABLE `home_contract_detail`   
  DROP COLUMN `contract_plus_money`;

CREATE TABLE `home_plus_money`(  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `contract_detail_id` INT(11),
  `label` VARCHAR(128),
  `price` FLOAT,
  PRIMARY KEY (`id`)
);

ALTER TABLE `home_history_log`   
  CHANGE `log_date_appointment` `log_date_appointment_from` VARCHAR(128) CHARSET utf8 COLLATE utf8_unicode_ci NULL,
  ADD COLUMN `log_date_appointment_to` VARCHAR(128) NULL AFTER `log_time_mail`,
  ADD COLUMN `log_payment_date_appointment_from` VARCHAR(128) NULL AFTER `log_date_appointment_to`,
  ADD COLUMN `log_payment_date_appointment_to` VARCHAR(128) NULL AFTER `log_payment_date_appointment_from`,
  ADD COLUMN `log_payment_appointment_status` TINYINT(1) NULL AFTER `log_payment_date_appointment_to`,
  ADD COLUMN `log_payment_appointment_report` TINYINT(1) NULL AFTER `log_payment_appointment_status`;

/*Hao 24/4*/

ALTER TABLE home_room DROP PRIMARY KEY, ADD PRIMARY KEY(id, broker_id);

ALTER TABLE `home_room_detail`   
  CHANGE `room_administative_expense` `room_administrative_expense` FLOAT NULL;

/*Hao 7/5*/
ALTER TABLE `home_introduce_house`   
  ADD COLUMN `order_id` INT(11) NULL AFTER `room_id`;


/*Hao 10/5*/
ALTER TABLE `home_room`   
  CHANGE `house_id` `house_id` INT(11) NOT NULL,
  ADD COLUMN `room_detail_id` INT(11) NULL AFTER `house_id`, 
  DROP PRIMARY KEY,
  ADD PRIMARY KEY (`id`, `broker_id`, `house_id`);

ALTER TABLE `home_room_detail`   
  DROP COLUMN `room_id`;

/*Hao 14/5*/
ALTER TABLE `home_house`   
  DROP COLUMN `house_size`, 
  DROP COLUMN `house_discount`;

ALTER TABLE `home_room_detail`   
  ADD COLUMN `room_discount` VARCHAR(128) NULL AFTER `room_deposit`;

/*Hao 15/5*/
CREATE TABLE `house_city`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `city_name` VARCHAR(128),
  PRIMARY KEY (`id`)
);

CREATE TABLE `house_district`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `district_name` VARCHAR(128),
  PRIMARY KEY (`id`)
);
CREATE TABLE `house_ward`(  
  `id` INT NOT NULL AUTO_INCREMENT,
  `ward_name` VARCHAR(128),
  PRIMARY KEY (`id`)
);

CREATE TABLE `house_street`(  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `street_name` VARCHAR(128),
  PRIMARY KEY (`id`)
);

CREATE TABLE `house_type`(  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type_name` VARCHAR(128),
  PRIMARY KEY (`id`)
);

CREATE TABLE `house_structure`(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `structure_name` VARCHAR(128),
  PRIMARY KEY (`id`)
);
CREATE TABLE `house_room_type`(  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `room_name` VARCHAR(128),
  PRIMARY KEY (`id`)
);

ALTER TABLE `home_history_log`   
  ADD COLUMN `source_id` INT(11) NULL AFTER `order_id`;

CREATE TABLE `home_source`(  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `source_name` VARCHAR(128),
  PRIMARY KEY (`id`)
);

ALTER TABLE `home_contract_detail`   
  ADD COLUMN `contract_application` TINYINT(1) NULL AFTER `contract_name`,
  ADD COLUMN `contract_application_status` TINYINT(1) NULL AFTER `contract_application`;

ALTER TABLE `home_contract_detail`   
  CHANGE `contract_application_status` `contract_application_date` VARCHAR(128) NULL;


-- Mai 20-05
ALTER TABLE  `home_room_detail` CHANGE  `room_number`  `room_number` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `room_rent`  `room_rent` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE  `room_key_money`  `room_key_money` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE  `room_administrative_expense`  `room_administrative_expense` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE  `room_deposit`  `room_deposit` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;


ALTER TABLE  `home_room` CHANGE  `id`  `id` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

/*21/5 Hao*/

ALTER TABLE `home_contract`   
  ADD COLUMN `client_id` INT(11) NULL AFTER `user_id`;

/*28/5 Hao*/

ALTER TABLE `house_district`   
  ADD COLUMN `city_id` INT(11) NULL AFTER `district_name`;

ALTER TABLE `house_street`   
  ADD COLUMN `district_id` INT(11) NULL AFTER `street_name`;

ALTER TABLE `house_ward`   
  ADD COLUMN `street_id` INT(11) NULL AFTER `ward_name`;

/*3/6 Hao*/
ALTER TABLE `home_order`   
  CHANGE `room_id` `room_id` VARCHAR(128) NULL;

/*12/6 Hao*/
ALTER TABLE `home_contract_detail`   
  ADD COLUMN `contract_broker_fee` VARCHAR(128) NULL AFTER `contract_application_date`,
  ADD COLUMN `contract_ads_fee` VARCHAR(128) NULL AFTER `contract_broker_fee`,
  ADD COLUMN `contract_ads_payment` TINYINT(1) NULL AFTER `contract_ads_fee`;

CREATE TABLE `home_contract_partner`(  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `contract_detail_id` INT(11),
  `partner_id` INT(11),
  `partner_percent` VARCHAR(128),
  PRIMARY KEY (`id`)
);
/*16/6 Hao*/
ALTER TABLE `home_history_log`   
  DROP COLUMN `log_payment_date_appointment_from`, 
  DROP COLUMN `log_payment_date_appointment_to`, 
  DROP COLUMN `log_payment_appointment_status`, 
  DROP COLUMN `log_payment_appointment_report`;

ALTER TABLE `home_contract_detail`   
  ADD COLUMN `contract_payment_date_from` VARCHAR(128) NULL AFTER `contract_ads_payment`,
  ADD COLUMN `contract_payment_date_to` VARCHAR(128) NULL AFTER `contract_payment_date_from`,
  ADD COLUMN `contract_payment_status` TINYINT(1) NULL AFTER `contract_payment_date_to`,
  ADD COLUMN `contract_payment_report` TINYINT(1) NULL AFTER `contract_payment_status`;

ALTER TABLE `home_contract_detail`   
  CHANGE `contract_ads_payment` `contract_transaction_finish` TINYINT(1) NULL;

/*19/6 Hao*/

ALTER TABLE `home_introduce_house`   
  CHANGE `room_id` `room_id` VARCHAR(128) NULL;

/*23/6*/
ALTER TABLE `home_contract_detail`   
  ADD COLUMN `contract_ambition` TINYINT(1) NULL AFTER `contract_payment_report`;

/*30/6 hao*/

CREATE TABLE `home_event`(  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11),
  `event_title` VARCHAR(128),
  `event_start` VARCHAR(128),
  `event_end` VARCHAR(128),
  `event_url` varchar(128),
  PRIMARY KEY (`id`)
);

/*10/7 hao*/
CREATE TABLE `home_user_target`(  
    `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11),
  `target` INT(11),
  `create_date` VARCHAR(128),
  PRIMARY KEY (`id`)
);

ALTER TABLE `home_user`   
  DROP COLUMN `user_target`;

ALTER TABLE  `home_user_target` CHANGE  `create_date`  `create_date` DATE NOT NULL;

/*17/7 hao */
ALTER TABLE `home_client`   
  ADD COLUMN `client_read_way` VARCHAR(128) NULL AFTER `client_name`;
ALTER TABLE `home_order`   
  CHANGE `order_rent_cost` `order_rent_cost` VARCHAR(128) NULL;

/*23/7 Hao*/
ALTER TABLE `home_room_detail`   
  ADD COLUMN `room_type_number` VARCHAR(128) NULL AFTER `room_type`;
ALTER TABLE `home_client`   
  ADD COLUMN `client_room_type_number` VARCHAR(128) NULL AFTER `client_room_type`;

/*30/7 hao*/
ALTER TABLE `home_house`   
  ADD COLUMN `house_lock` TINYINT(1) DEFAULT 0  NULL AFTER `house_owner_id`;

ALTER TABLE `home_room_detail`   
  ADD COLUMN `room_lock` TINYINT(1) DEFAULT 0  NULL AFTER `room_photo`;

ALTER TABLE `home_client`   
  ADD COLUMN `client_lock` TINYINT(1) DEFAULT 0  NULL AFTER `client_room_type_number`;

ALTER TABLE `home_agent`   
  ADD COLUMN `agent_lock` TINYINT(1) DEFAULT 0  NULL AFTER `agent_fax`;

ALTER TABLE `home_source`   
  ADD COLUMN `source_lock` TINYINT(1) DEFAULT 0  NULL AFTER `source_name`;

ALTER TABLE `home_broker_company`   
  ADD COLUMN `broker_company_lock` TINYINT(1) DEFAULT 0  NULL AFTER `broker_company_undertake`;

/*4-8 Hao*/
ALTER TABLE `home_history_aspirations`   
  ADD COLUMN `aspirations_type_room_number` VARCHAR(128) NULL AFTER `aspirations_type_room`;

/*13-8 Hao */
ALTER TABLE `home_house`   
  ADD COLUMN `house_search` VARCHAR(128) NULL AFTER `house_lock`;

ALTER TABLE `home_broker_company`   
  ADD COLUMN `broker_company_search` VARCHAR(128) NULL AFTER `broker_company_lock`;

ALTER TABLE `home_agent`   
  ADD COLUMN `agent_search` VARCHAR(128) NULL AFTER `agent_lock`;

ALTER TABLE `home_client`   
  ADD COLUMN `client_search` VARCHAR(128) NULL AFTER `client_lock`;

ALTER TABLE `home_house_owner`   
  ADD COLUMN `house_owner_search` VARCHAR(128) NULL AFTER `house_owner_photo`;

ALTER TABLE `home_user`   
  ADD COLUMN `user_search` VARCHAR(128) NULL AFTER `user_path_thumb`;

-- 14-08
ALTER TABLE  `home_order` 
CHANGE  `order_day_create`  `order_day_create` INT( 11 ) UNSIGNED NULL DEFAULT NULL ,
CHANGE  `order_day_update`  `order_day_update` INT( 11 ) UNSIGNED NULL DEFAULT NULL;

/*19/9 Hao*/
CREATE TABLE `home_group`(  
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(128),
  PRIMARY KEY (`id`)
) CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `home_user`   
  ADD COLUMN `group_id` INT(11) NULL AFTER `user_search`;

ALTER TABLE `home_group`   
  ADD COLUMN `group_lock` TINYINT(1) DEFAULT 0  NULL AFTER `group_name`;


/*14/10 Hao*/
ALTER TABLE `home_history_log`   
  CHANGE `log_revisit` `log_revisit` VARCHAR(128) NULL;

/*17/10 Hao*/
ALTER TABLE `home_contract_detail`   
  ADD COLUMN `money_payment` FLOAT NULL AFTER `contract_ambition`,
  ADD COLUMN `room_rented` TINYINT(1) NULL AFTER `money_payment`;