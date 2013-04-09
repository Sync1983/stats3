
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `project` (
    `id` int(10) unsigned NOT NULL COMMENT '' auto_increment,
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    `url` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
