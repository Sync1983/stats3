SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `log_featureUse` DROP COLUMN  `data`;
ALTER TABLE `log_levelUp` DROP COLUMN     `data`;
ALTER TABLE `log_Login` DROP COLUMN       `data`;
ALTER TABLE `log_NewPlayer` DROP COLUMN   `data`;
ALTER TABLE `log_OutEnergy` DROP COLUMN   `data`;
ALTER TABLE `log_payCost` DROP COLUMN     `data`;
ALTER TABLE `log_QuestDone` DROP COLUMN   `data`;
ALTER TABLE `log_QuestStart` DROP COLUMN  `data`;
ALTER TABLE `log_QuestTaskComplete` DROP COLUMN `data`;
ALTER TABLE `log_viralRecive` DROP COLUMN       `data`;
ALTER TABLE `log_viralSend` DROP COLUMN         `data`;

SET FOREIGN_KEY_CHECKS = 1;
