SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `logger` ADD `level` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER `value`;
ALTER TABLE `logger` ADD `session` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER `level`;
ALTER TABLE `logger` ADD `return` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER `session`;
ALTER TABLE `logger` ADD `energy` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER `return`;
ALTER TABLE `logger` ADD `real` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER `energy`;
ALTER TABLE `logger` ADD `bonus` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER `real`;
ALTER TABLE `logger` ADD `money` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER `bonus`;

SET FOREIGN_KEY_CHECKS = 1;
