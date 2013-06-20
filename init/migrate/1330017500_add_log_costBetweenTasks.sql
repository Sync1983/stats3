SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `log_costBetweenTasks` (
    `id`          int(10) UNSIGNED NOT NULL auto_increment,
    `project_id`  int(3) unsigned NOT NULL DEFAULT '0',
    `ext_id`      VARCHAR(150) NOT NULL DEFAULT '0',        
    `reg_time`    int(15) unsigned NOT NULL DEFAULT '0',
    `stamp`       int(15) unsigned NOT NULL DEFAULT '0',
    `pay`         int(5) unsigned NOT NULL DEFAULT '0',
    `quest_id`    int(15) unsigned NOT NULL DEFAULT '0',
    `active_task` int(5) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`,`project_id`),
    INDEX pid (`ext_id`,`reg_time`,`project_id`,`stamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
