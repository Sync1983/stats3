SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `counter2_tmp`;

CREATE TABLE `counter2_tmp` (  
  `user_id` int(3) unsigned NOT NULL DEFAULT '1',
  `project_id` int(3) unsigned NOT NULL DEFAULT '0',
  `hash` int(10) unsigned NOT NULL,
  `stamp` int(15) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',  
  `axist` varchar(30) NOT NULL DEFAULT '0',
  `value` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY `p_h_s` (`project_id`,`hash`,`stamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `counter2_tmp` (SELECT user_id,project_id,(crc32(CONCAT(name,axist))) as hash,stamp,name,axist,value FROM counter2);
RENAME TABLE counter2 TO counter2_old;
RENAME TABLE counter2_tmp TO counter2;

SET FOREIGN_KEY_CHECKS = 1;
