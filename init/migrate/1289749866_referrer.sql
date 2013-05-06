
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `referrer` (
    `id` int(10) unsigned NOT NULL COMMENT '' auto_increment,
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci,
    `project_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    `uid` smallint(6) NOT NULL DEFAULT '0' COMMENT '',
    PRIMARY KEY (`id`),
    INDEX `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
