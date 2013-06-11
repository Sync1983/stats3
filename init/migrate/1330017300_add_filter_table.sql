SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `filter` (
    `id`          int(3) UNSIGNED NOT NULL auto_increment,
    `project_id`  int(3) unsigned NOT NULL DEFAULT '0',
    `name`   VARCHAR(50) NOT NULL DEFAULT '0',        
    `data`   VARCHAR(500) NOT NULL DEFAULT '0',        
    PRIMARY KEY (`id`,`project_id`),
    INDEX pid (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
