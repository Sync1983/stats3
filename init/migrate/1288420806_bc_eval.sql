
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `chart` ADD `bc_eval` varchar(500) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci AFTER project_id;


ALTER TABLE `counter` ADD `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '' AFTER uid;


SET FOREIGN_KEY_CHECKS = 1;
