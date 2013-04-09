SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `counter2` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `user_id` int(10) unsigned NOT NULL default '1',
    `project_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '',
    `stamp` int(15) NOT NULL DEFAULT '0' COMMENT '',
    `axist` varchar(30) NOT NULL DEFAULT '0' COMMENT '',
    `value` varchar(100) NOT NULL DEFAULT '0' COMMENT '',    
    PRIMARY KEY (`id`),
    INDEX `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
