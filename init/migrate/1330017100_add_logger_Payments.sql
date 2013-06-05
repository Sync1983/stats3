SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `logger_chart` (
    `id`          int(3) unsigned NOT NULL auto_increment,
    `project_id`  int(3) UNSIGNED NOT NULL DEFAULT '0',
    `member_id`   int(3) unsigned NOT NULL DEFAULT '0',    
    `name`        VARCHAR(100) NOT NULL DEFAULT 'Имя',
    `query`       VARCHAR(500) NOT NULL DEFAULT 'SELECT count(*) FROM log_Login',
    `x_values`    VARCHAR(100) NOT NULL DEFAULT 'Время',
    `y_values`    VARCHAR(100) NOT NULL DEFAULT 'Кол-во',
    PRIMARY KEY (`id`,`project_id`,`member_id`),
    INDEX `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

SET FOREIGN_KEY_CHECKS = 1;
