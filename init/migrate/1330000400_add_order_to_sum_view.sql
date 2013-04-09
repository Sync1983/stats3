SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `sumview` 
  ADD `order_id` int(5) unsigned DEFAULT 100 AFTER user_id;

SET FOREIGN_KEY_CHECKS = 1;
