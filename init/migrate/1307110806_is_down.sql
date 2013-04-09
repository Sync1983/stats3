
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `project` ADD `is_down` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER last_sms_time;

SET FOREIGN_KEY_CHECKS = 1;
