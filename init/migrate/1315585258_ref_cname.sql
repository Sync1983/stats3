
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `referrer` ADD `cname` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci AFTER uid;


SET FOREIGN_KEY_CHECKS = 1;
