
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `project` ADD `check_status_url` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci AFTER has_active_stats;
ALTER TABLE `project` ADD `notify_phones` varchar(500) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci AFTER check_status_url;


SET FOREIGN_KEY_CHECKS = 1;
