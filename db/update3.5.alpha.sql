ALTER TABLE `zt_feedback` ADD `subStatus` varchar(30) NOT NULL AFTER `status`;

UPDATE `zt_workflowfield` SET `type` = 'varchar', `length` = 30 WHERE `options` = 'user';
UPDATE `zt_workflowlabel` SET `code` = CONCAT('browse', `id`), `params` = REPLACE(`params`, '\"key\":', '\"field\":') WHERE `buildin` = '0';
