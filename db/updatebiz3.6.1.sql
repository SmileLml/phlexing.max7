ALTER TABLE `zt_workflowaction` ADD `linkages` text NOT NULL AFTER `hooks`;
ALTER TABLE `zt_workflowfield`  CHANGE `control` `control` varchar(20) NOT NULL;

REPLACE INTO `zt_workflowlayout` (`module`, `action`, `field`, `order`, `defaultValue`, `layoutRules`) SELECT `module`, 'batchcreate', `field`, `order`, `defaultValue`, `layoutRules` FROM `zt_workflowlayout` WHERE `action` = 'create';
REPLACE INTO `zt_workflowlayout` (`module`, `action`, `field`, `order`, `defaultValue`, `layoutRules`) SELECT `module`, 'batchedit', `field`, `order`, `defaultValue`, `layoutRules` FROM `zt_workflowlayout` WHERE `action` = 'edit';

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'workflowcondition', 'browse' FROM `zt_grouppriv` WHERE `module` = 'workflowaction' AND `method` = 'setCondition';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'workflowcondition', 'create' FROM `zt_grouppriv` WHERE `module` = 'workflowaction' AND `method` = 'setCondition';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'workflowcondition', 'edit'   FROM `zt_grouppriv` WHERE `module` = 'workflowaction' AND `method` = 'setCondition';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'workflowcondition', 'delete' FROM `zt_grouppriv` WHERE `module` = 'workflowaction' AND `method` = 'setCondition';

DELETE FROM `zt_grouppriv` WHERE `module` = 'workflow' AND `method` = 'setSubModule';
DELETE FROM `zt_grouppriv` WHERE `module` = 'workflowaction' AND `method` = 'setCondition';

ALTER TABLE `zt_workflow` ADD `js` text NOT NULL AFTER `name`;
ALTER TABLE `zt_workflow` ADD `css` text NOT NULL AFTER `js`;
ALTER TABLE `zt_workflowaction` ADD `js` text NOT NULL AFTER `linkages`;
ALTER TABLE `zt_workflowaction` ADD `css` text NOT NULL AFTER `js`;

UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'product'  AND `action` = 'view';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'story'    AND `action` = 'edit';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'story'    AND `action` = 'view';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'project'  AND `action` = 'view';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'task'     AND `action` = 'edit';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'task'     AND `action` = 'view';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'bug'      AND `action` = 'edit';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'bug'      AND `action` = 'view';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'testcase' AND `action` = 'edit';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'testcase' AND `action` = 'view';
UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'testtask' AND `action` = 'view';

CREATE TABLE IF NOT EXISTS `zt_workflowlinkdata` (
  `objectType` varchar(30) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `linkedType` varchar(30) NOT NULL,
  `linkedID` mediumint(8) unsigned NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  UNIQUE KEY `unique` (`objectType`, `objectID`, `linkedType`, `linkedID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

UPDATE `zt_workflowdatasource` SET `datasource` = 'select id,title from zt_story where deleted=\"0\"'       WHERE `type` = 'sql' AND `datasource` = 'select id,title from zt_story';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select id,name from zt_task where deleted=\"0\"'         WHERE `type` = 'sql' AND `datasource` = 'select id,name from zt_task';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select id,title from zt_bug where deleted=\"0\"'         WHERE `type` = 'sql' AND `datasource` = 'select id,title from zt_bug';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select id,name from zt_build where deleted=\"0\"'        WHERE `type` = 'sql' AND `datasource` = 'select id,name from zt_build';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select id,name from zt_module where deleted=\"0\"'       WHERE `type` = 'sql' AND `datasource` = 'select id,name from zt_module';
UPDATE `zt_workflowdatasource` SET `datasource` = 'select id,title from zt_productplan where deleted=\"0\"' WHERE `type` = 'sql' AND `datasource` = 'select id,title from zt_productplan';

UPDATE `zt_workflow` SET `navigator` = 'primary' WHERE `module` in ('product', 'project');
