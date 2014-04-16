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