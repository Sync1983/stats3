SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `log_addStock` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_addStock`
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
#==================================
ALTER TABLE `log_costStock` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_costStock`
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
#==================================
ALTER TABLE `log_featureUse` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_featureUse`
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
#==================================
ALTER TABLE `log_levelUp` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_levelUp`
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
#==================================
ALTER TABLE `log_Login` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_Login`
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
#==================================
ALTER TABLE `log_NewPlayer` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_NewPlayer`
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
#==================================
ALTER TABLE `log_OutEnergy` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_OutEnergy`
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
#==================================
ALTER TABLE `log_payCost` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_payCost`
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
#==================================
ALTER TABLE `log_QuestDone` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_QuestDone`
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
#==================================
ALTER TABLE `log_QuestStart` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_QuestStart`
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
#==================================
ALTER TABLE `log_QuestTaskComplete` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_QuestTaskComplete`
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
#==================================
ALTER TABLE `log_viralRecive` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_viralRecive`
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
#==================================
ALTER TABLE `log_viralSend` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`,`stamp`);
ALTER TABLE `log_viralSend`
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
#==================================
SET FOREIGN_KEY_CHECKS = 1;