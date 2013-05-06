
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `member` ADD `is_admin` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '' AFTER hashed_password;
ALTER TABLE `member` ADD `projects_ids` blob NULL DEFAULT NULL COMMENT '' AFTER is_admin;

UPDATE member set is_admin=1 where id = 4;


SET FOREIGN_KEY_CHECKS = 1;
