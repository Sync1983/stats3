
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `chart` (
    `id` int(10) unsigned NOT NULL COMMENT '' auto_increment,
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci,
    `uid` char(50) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    `project_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    PRIMARY KEY (`id`),
    INDEX `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `chart2counter` (
    `chart_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    `counter_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    PRIMARY KEY (`chart_id`, `counter_id`),
    INDEX `by_counter` (`counter_id`, `chart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `counter` (
    `id` int(10) unsigned NOT NULL COMMENT '' auto_increment,
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci,
    `project_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    `uid` char(50) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    PRIMARY KEY (`id`),
    INDEX `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `project` ADD `api_key` char(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci AFTER id;
ALTER TABLE `project` DROP INDEX `key`;
ALTER TABLE `project` DROP `key`;
ALTER TABLE project ADD INDEX `key` (`api_key`(5));


SET FOREIGN_KEY_CHECKS = 1;
