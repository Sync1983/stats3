SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `logger` ADD `referal` varchar(150) NOT NULL DEFAULT '' COMMENT '' AFTER `money`;
ALTER TABLE `logger` ADD `reg_time` int(15) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER `referal`;

SET FOREIGN_KEY_CHECKS = 1;
