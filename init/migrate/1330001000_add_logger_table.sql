SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `logger` (
    `id` int(10) unsigned NOT NULL auto_increment,    
    `project_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    `ext_id` varchar(150) NOT NULL DEFAULT '' COMMENT '',
    `stamp` int(15) NOT NULL DEFAULT '0' COMMENT '',    
    `event` varchar(50) NOT NULL DEFAULT '' COMMENT '',
    `item_id` int(15) NOT NULL DEFAULT '0' COMMENT '',
    `value` int(15) NOT NULL DEFAULT '0' COMMENT '',    
    `data` varchar(500) NOT NULL DEFAULT '0' COMMENT '',    
    PRIMARY KEY (`id`),
    INDEX `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
