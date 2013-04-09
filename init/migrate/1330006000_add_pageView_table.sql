SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `page_view` (
    `page_id`  int(3) unsigned NOT NULL DEFAULT '0',    
    `position`    int(3) unsigned NOT NULL DEFAULT '0',    
    `data_type`   int(3) UNSIGNED NOT NULL DEFAULT '0',    
    `counter_id`  int(3) UNSIGNED NOT NULL DEFAULT '0',
    `view_preset` int(3) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`position`,`page_id`),
    INDEX `position` (`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
