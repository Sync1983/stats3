SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `log_MAU` MODIFY `referal` int(10);
ALTER TABLE `log_MAU` ADD `mau` int(10) after `referal`;
ALTER TABLE `log_MAU` DROP PRIMARY KEY, ADD PRIMARY KEY (`stamp`,`project_id`,`referal`);

SET FOREIGN_KEY_CHECKS = 1;