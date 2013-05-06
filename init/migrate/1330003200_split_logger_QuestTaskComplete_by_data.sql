SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `log_QuestTaskComplete` ADD COLUMN `completeTask` int(2) NULL  AFTER `data`;

SET FOREIGN_KEY_CHECKS = 1;
