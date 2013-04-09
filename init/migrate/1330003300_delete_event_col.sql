SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `log_addStock` DROP COLUMN `event`;
ALTER TABLE `log_costStock` DROP COLUMN `event`;
ALTER TABLE `log_featureUse` DROP COLUMN `event`;
ALTER TABLE `log_levelUp` DROP COLUMN `event`;
ALTER TABLE `log_Login` DROP COLUMN `event`;
ALTER TABLE `log_NewPlayer` DROP COLUMN `event`;
ALTER TABLE `log_OutEnergy` DROP COLUMN `event`;
ALTER TABLE `log_payCost` DROP COLUMN `event`;
ALTER TABLE `log_QuestDone` DROP COLUMN `event`;
ALTER TABLE `log_QuestStart` DROP COLUMN `event`;
ALTER TABLE `log_QuestTaskComplete` DROP COLUMN `event`;
ALTER TABLE `log_viralRecive` DROP COLUMN `event`;
ALTER TABLE `log_viralSend` DROP COLUMN `event`;

SET FOREIGN_KEY_CHECKS = 1;
