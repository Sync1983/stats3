SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `log_addStock` DROP COLUMN `data`;
ALTER TABLE `log_costStock` DROP COLUMN `data`;
ALTER TABLE `log_viralRecive` DROP COLUMN `type`;
ALTER TABLE `log_viralSend` DROP COLUMN `type`;
ALTER TABLE `log_viralRecive` DROP COLUMN `name`;
ALTER TABLE `log_viralSend` DROP COLUMN `name`;
ALTER TABLE `log_featureUse` DROP COLUMN `pack`;
ALTER TABLE `log_featureUse` DROP COLUMN `name`;

ALTER TABLE `log_addStock`    MODIFY `referal` varchar(30);
ALTER TABLE `log_costStock`   MODIFY `referal` varchar(30);
ALTER TABLE `log_featureUse`  MODIFY `referal` varchar(30);
ALTER TABLE `log_levelUp`     MODIFY `referal` varchar(30);
ALTER TABLE `log_Login`       MODIFY `referal` varchar(30);
ALTER TABLE `log_NewPlayer`   MODIFY `referal` varchar(30);
ALTER TABLE `log_OutEnergy`   MODIFY `referal` varchar(30);
ALTER TABLE `log_payCost`     MODIFY `referal` varchar(30);
ALTER TABLE `log_QuestDone`   MODIFY `referal` varchar(30);
ALTER TABLE `log_QuestStart`  MODIFY `referal` varchar(30);
ALTER TABLE `log_QuestTaskComplete` MODIFY `referal` varchar(30);
ALTER TABLE `log_viralRecive` MODIFY `referal` varchar(30);
ALTER TABLE `log_viralSend`   MODIFY `referal` varchar(30);



SET FOREIGN_KEY_CHECKS = 1;
