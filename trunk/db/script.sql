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