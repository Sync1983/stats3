SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `preset` 
  ADD `v_name` varchar(255) DEFAULT 'Values' COMMENT '' COLLATE utf8_general_ci AFTER name;

SET FOREIGN_KEY_CHECKS = 1;
