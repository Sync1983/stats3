
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `chart` ADD `eval` varchar(500) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_general_ci AFTER project_id;

CREATE TABLE `chart2eval_counter` (
    `chart_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    `counter_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '',
    PRIMARY KEY (`chart_id`, `counter_id`),
    INDEX `by_counter` (`counter_id`, `chart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

SET FOREIGN_KEY_CHECKS = 1;
