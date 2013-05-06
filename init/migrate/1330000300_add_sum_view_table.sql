
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `sumview` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `user_id` int(10) unsigned NOT NULL default '1',
    `project_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '',
    `formula` varchar(500) NOT NULL DEFAULT '' COMMENT '',        
    PRIMARY KEY (`id`),
    INDEX `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
