SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `log_MAU` (  
  `stamp` int(15) NOT NULL DEFAULT '0',  
  `project_id` int(10) unsigned NOT NULL DEFAULT '0',    
  `referal` varchar(150) NOT NULL DEFAULT '',  
  PRIMARY KEY (`stamp`,`project_id`),
  KEY `id` (`stamp`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `log_MAU`
PARTITION BY RANGE(stamp) (
  PARTITION a01 VALUES LESS THAN (UNIX_TIMESTAMP('2013-01-01 00:00:00')), 
  PARTITION a02 VALUES LESS THAN (UNIX_TIMESTAMP('2013-02-01 00:00:00')), 
  PARTITION a03 VALUES LESS THAN (UNIX_TIMESTAMP('2013-03-01 00:00:00')), 
  PARTITION a04 VALUES LESS THAN (UNIX_TIMESTAMP('2013-04-01 00:00:00')), 
  PARTITION a05 VALUES LESS THAN (UNIX_TIMESTAMP('2013-05-01 00:00:00')), 
  PARTITION a06 VALUES LESS THAN (UNIX_TIMESTAMP('2013-06-01 00:00:00')), 
  PARTITION a07 VALUES LESS THAN (UNIX_TIMESTAMP('2013-07-01 00:00:00')), 
  PARTITION a08 VALUES LESS THAN (UNIX_TIMESTAMP('2013-08-01 00:00:00')), 
  PARTITION a09 VALUES LESS THAN (UNIX_TIMESTAMP('2013-09-01 00:00:00')), 
  PARTITION a10 VALUES LESS THAN (UNIX_TIMESTAMP('2013-10-01 00:00:00')), 
  PARTITION a11 VALUES LESS THAN (UNIX_TIMESTAMP('2013-11-01 00:00:00')), 
  PARTITION a12 VALUES LESS THAN (UNIX_TIMESTAMP('2013-12-01 00:00:00')), 
  PARTITION a13 VALUES LESS THAN (UNIX_TIMESTAMP('2014-01-01 00:00:00')), 
  PARTITION a14 VALUES LESS THAN (UNIX_TIMESTAMP('2015-02-01 00:00:00')), 
PARTITION a15 VALUES LESS THAN (UNIX_TIMESTAMP('2016-03-01 00:00:00')) 
);
SET FOREIGN_KEY_CHECKS = 1;