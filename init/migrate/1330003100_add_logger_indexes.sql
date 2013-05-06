SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `log_addStock` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_costStock` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_featureUse` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_levelUp` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_Login` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_NewPlayer` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_OutEnergy` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_payCost` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_QuestDone` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_QuestStart` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_QuestTaskComplete` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_viralRecive` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);
ALTER TABLE `log_viralSend` ADD INDEX `stamp` (`stamp` ASC), ADD INDEX `s_i` (`stamp` ASC, `item_id` ASC);

SET FOREIGN_KEY_CHECKS = 1;


