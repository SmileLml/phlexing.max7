REPLACE INTO `zt_basicmeas` (`purpose`, `scope`, `object`, `name`, `code`, `unit`, `configure`, `params`, `definition`, `source`, `collectType`, `collectConf`, `execTime`, `collectedBy`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `order`, `deleted`) VALUES
('scale','project','softRequest','项目软件需求实时规模','pgmSRRealScale','故事点或功能点','CREATE FUNCTION `qc_pgmsrrealscale`($project int) RETURNS float (10,2)\r\nBEGIN\r\n  declare totalEstimate float (10,2) default 0__DELIMITER__\r\n  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_story where id in (select story from zt_projectstory where project=$project) and type=\'story\' and deleted=\'0\' and closedReason not in (\'subdivided\', \'duplicate\', \'willnotdo\', \'cancel\', \'bydesign\') into totalEstimate__DELIMITER__\r\n  return totalEstimate__DELIMITER__\r\nEND','{\"$project\":{\"showName\":\"\\u6240\\u5c5e\\u9879\\u76ee\",\"varName\":\"$project\",\"varType\":false,\"options\":\"project\",\"defaultValue\":\"702\"}}','项目软件需求实际的规模','从需求表中查询该项目下的所有软件需求，对规模进行求和。','crontab','{\"week\":\"1,2,3,4,5,6,0\",\"type\":\"week\"}','00:00','','system','0000-00-00 00:00:00','admin','2020-07-07 14:28:01',25,'0');

ALTER TABLE `zt_auditplan` ADD `execution` mediumint(8) unsigned NOT NULL AFTER `project`;

SET global sql_mode = '';
USE `__TABLE__`;
DROP FUNCTION IF EXISTS `qc_pgmsrrealscale`;
CREATE FUNCTION `qc_pgmsrrealscale`($project int) RETURNS float(10,2)
BEGIN
  declare totalEstimate float(10,2) default 0__DELIMITER__
  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_story where id in (select story from zt_projectstory where project=$project) and type='story' and deleted='0' and closedReason not in ('subdivided', 'duplicate', 'willnotdo', 'cancel', 'bydesign') into totalEstimate__DELIMITER__  
  return totalEstimate__DELIMITER__
END;
