ALTER TABLE `zt_workflow` ADD `flowchart` text NOT NULL AFTER `name`;
ALTER TABLE `zt_workflow` ADD `status` varchar(10) NOT NULL DEFAULT 'wait' AFTER `version`;

ALTER TABLE `zt_workflowfield` CHANGE `canExport` `canExport` enum('0', '1') NOT NULL DEFAULT '0';
ALTER TABLE `zt_workflowfield` CHANGE `canSearch` `canSearch` enum('0', '1') NOT NULL DEFAULT '0';

UPDATE `zt_workflow` SET `status` = 'normal' WHERE `type` = 'table';
UPDATE `zt_workflow` SET `status` = 'normal' WHERE `buildin` = '1';

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `datasource`) VALUES ('lang', '需求类型',  'storyType');
UPDATE `zt_workflowfield` SET `type` = 'varchar', `control` = 'select' WHERE `module` = 'story' AND `field` = 'type';
UPDATE `zt_workflowfield` as t1 INNER JOIN (select id from `zt_workflowdatasource` order by id desc limit 1) as t2 SET `options`= t2.id WHERE t1.`module` = 'story' AND t1.`field` = 'type';

UPDATE `zt_workflowfield` SET `type` = 'text', `control` = 'textarea' WHERE `module` = 'feedback' AND `field` = 'desc';

UPDATE `zt_workflowaction` SET `open` = 'modal' WHERE `buildin` = '1' AND `action` = 'exporttemplate';
UPDATE `zt_workflowaction` SET `open` = 'modal' WHERE `buildin` = '1' AND `action` = 'showimport';
UPDATE `zt_workflowaction` SET `type` = 'batch' WHERE `buildin` = '1' AND `action` like 'batch%';
