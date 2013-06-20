DROP PROCEDURE IF EXISTS `proc_test`;

DELIMITER $$

create procedure `proc_test` 
  (IN filter VARCHAR(50), IN ins varchar(100)
    ) 
BEGIN 
  SET @sql1:=CONCAT(
    "SELECT quest_id as x_id,sum(id) as y2_pays FROM log_payBetweenTasks where quest_id IN ",ins," and ",
    filter,
    " GROUP BY quest_id;"
  ); 
  
  SET @sql2:=CONCAT(
    "SELECT count(DISTINCT ext_id) as y1_users, item_id as x_id FROM log_QuestDone WHERE item_id IN ",ins," and ",
    filter,
    " GROUP BY item_id;"
  ); 

PREPARE AQ FROM @sql1; 
EXECUTE AQ;
DEALLOCATE PREPARE AQ; 

PREPARE AQ FROM @sql2; 
EXECUTE AQ;
DEALLOCATE PREPARE AQ; 
END $$

DELIMITER ;
CALL proc_test("project_id=4 and reg_time>=1371254400 and reg_time<=1371340800","(58653439, 166313904, 139018748, 48104627, 126560962, 89600443, 177560197, 27451782, 241207992)");
