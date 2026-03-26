CREATE TABLE IF NOT EXISTS `zt_workflowrelation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `prev` varchar(30) NOT NULL,
  `next` varchar(30) NOT NULL, 
  `field` varchar(50) NOT NULL,
  `actions` varchar(20) NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_workflowrelationlayout` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `prev` varchar(30) NOT NULL,
  `next` varchar(30) NOT NULL,
  `action` varchar(50) NOT NULL,
  `field` varchar(50) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY `id` (`id`),
  KEY `prev` (`prev`),
  KEY `next` (`next`),
  KEY `action` (`action`),
  KEY `order` (`order`),
  UNIQUE KEY `unique` (`prev`, `next`, `action`, `field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_workflowaction` ADD `type` enum('single', 'batch') NOT NULL DEFAULT 'single' AFTER `name`;
ALTER TABLE `zt_workflowaction` ADD `batchMode` enum('same', 'different') NOT NULL DEFAULT 'different' AFTER `type`;
ALTER TABLE `zt_workflowfield` ADD `readonly` enum('0', '1') NOT NULL DEFAULT '0' AFTER `desc`;
ALTER TABLE `zt_workflowlayout` ADD `readonly` enum('0', '1') NOT NULL DEFAULT '0' AFTER `position`;

INSERT INTO `zt_workflowrelation` (`prev`, `next`, `field`, `actions`) SELECT t1.child, t1.module, t2.field, 'create' FROM `zt_workflow` AS t1 LEFT JOIN `zt_workflowfield` AS t2 ON (t1.module = t2.module) WHERE t1.type = 'flow' AND t1.child != '' AND t2.isForeignKey = '1';

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'workflowrelation', 'admin' FROM `zt_grouppriv` WHERE `module` = 'workflow' AND `method` = 'setSubModule'; 

UPDATE `zt_workflow` SET `parent` = '', `child` = '' WHERE `type` = 'flow';
UPDATE `zt_workflowaction` SET `open` = 'normal' WHERE `action` = 'view' AND `open` = 'none';
UPDATE `zt_workflowfield` SET `readonly` = '1' WHERE `field` in ('id', 'parent', 'createdBy', 'createdDate', 'editedBy', 'editedDate', 'deleted');
UPDATE `zt_workflowfield` SET `options` = 'prevModule' WHERE `options` = 'submodule';

UPDATE `zt_workflowfield` SET `readonly` = '1' WHERE `buildin` = '1' AND `field` != 'subStatus';
UPDATE `zt_workflowdatasource` SET `datasource` = '{"app":"system","module":"branch","method":"getAllPairs","methodDesc":"Get pairs.","params":[{"name":"params","type":"string","desc":"","value":""}]}' WHERE `datasource` LIKE '%"module":"branch"%';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `type`='sql' AND `datasource`='select id,name from zt_build' LIMIT 1) WHERE `module` = 'bug' AND `field` = 'openedBuild';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `type`='sql' AND `datasource`='select id,name from zt_build' LIMIT 1) WHERE `module` = 'bug' AND `field` = 'resolvedBuild';

ALTER TABLE `zt_workflowfield` DROP `isForeignKey`;
