
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `project` ADD `last_sms_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER notify_phones;


SET FOREIGN_KEY_CHECKS = 1;
