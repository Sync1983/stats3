SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `pager` (
    `id`  int(3) unsigned NOT NULL auto_increment,
    `project_id`  int(3) UNSIGNED NOT NULL DEFAULT '0',
    `member_id`    int(3) unsigned NOT NULL DEFAULT '0',    
    `name`   VARCHAR(500) NOT NULL DEFAULT 'Имя',
    PRIMARY KEY (`id`,`project_id`,`member_id`),
    INDEX `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
