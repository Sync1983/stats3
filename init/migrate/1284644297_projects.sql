
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `ar_singleton` (
    `id` int(11) NOT NULL COMMENT '' auto_increment,
    `kind` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci,
    `data` text NULL DEFAULT NULL COMMENT '' COLLATE utf8_general_ci,
    PRIMARY KEY (`kind`),
    INDEX `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `project` ADD `key` char(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci AFTER id;
ALTER TABLE `project` ADD `dir` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci AFTER url;
ALTER TABLE `project` ADD INDEX `id` (`id`);
ALTER TABLE `project` ADD INDEX `key` (`key`);


SET FOREIGN_KEY_CHECKS = 1;
