ALTER TABLE `zt_host`
  ADD COLUMN `agentPort` varchar(10) NOT NULL,
  ADD COLUMN `instanceNum` tinyint(0) NOT NULL DEFAULT 0,
  ADD COLUMN `pri` smallint(5) unsigned NOT NULL DEFAULT 0,
  ADD COLUMN `heartbeatTime` datetime NOT NULL,
  ADD COLUMN `tags` varchar(50)  NOT NULL DEFAULT '',
  ADD COLUMN `provider` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `bridgeID` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `cloudKey` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `cloudSecret` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `cloudRegion` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `cloudNamespace` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `cloudUser` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `cloudAccount` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `cloudPassword` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `couldVPC` varchar(255) NOT NULL DEFAULT '';

ALTER TABLE `zt_host` CHANGE `status` `status` varchar(50) NOT NULL AFTER `language`;

-- DROP TABLE IF EXISTS `zt_vm`;
CREATE TABLE IF NOT EXISTS `zt_vm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hostID` int(10) unsigned NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `osCategory` varchar(50) NOT NULL DEFAULT '',
  `osType` varchar(50) NOT NULL DEFAULT '',
  `osArch` varchar(50) NOT NULL DEFAULT '',
  `osLang` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `destroyAt` datetime NULL,
  `ip` varchar(200) NOT NULL DEFAULT '',
  `agentPort` varchar(255) NOT NULL DEFAULT '',
  `macAddress` varchar(255) NOT NULL DEFAULT '',
  `workspace` varchar(255) NOT NULL DEFAULT '',
  `templateID` int(10) unsigned NOT NULL DEFAULT 0,
  `baseImageID` int(10) unsigned NOT NULL DEFAULT 0,
  `baseImagePath` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(255) NOT NULL DEFAULT '',
  `heatbeat` datetime NULL,
  `vnc` varchar(255) NOT NULL DEFAULT '',
  `instance` varchar(255) NOT NULL DEFAULT '',
  `eip` varchar(255) NOT NULL DEFAULT '',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `public` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- DROP TABLE IF EXISTS `zt_baseimage`;
CREATE TABLE IF NOT EXISTS `zt_baseimage` (
  `id` SMALLINT(7) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `osType` varchar(50) NOT NULL DEFAULT '',
  `os` varchar(50) NOT NULL DEFAULT '',
  `osCategory` varchar(50) NOT NULL DEFAULT '',
  `osArch` varchar(50) NOT NULL DEFAULT '',
  `osLang` varchar(50) NOT NULL DEFAULT '',
  `suggestCore` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `suggestMemory` mediumint(6) unsigned NOT NULL DEFAULT 0,
  `suggestVolume` mediumint(6) unsigned NOT NULL DEFAULT 0,

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- DROP TABLE IF EXISTS `zt_vmtemplate`;
CREATE TABLE IF NOT EXISTS `zt_vmtemplate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hostID` int(10) unsigned NOT NULL DEFAULT 0,
  `templateName` varchar(255) NOT NULL DEFAULT '',
  `osType` varchar(50) NOT NULL DEFAULT '',
  `osCategory` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



-- DROP TABLE IF EXISTS `zt_browser`;
CREATE TABLE IF NOT EXISTS `zt_browser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '',
  `version` varchar(255) NOT NULL DEFAULT '',
  `lang` varchar(255) NOT NULL DEFAULT '',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- DROP TABLE IF EXISTS `zt_baseimagebrowser`;
CREATE TABLE IF NOT EXISTS `zt_baseimagebrowser` (
  `vmBackingID` int(10) NOT NULL,
  `browserID` int(10) NOT NULL,
  PRIMARY KEY (`vmBackingID`, `browserID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_workflowdatasource` ADD `code` varchar(30) NOT NULL AFTER `name`;
ALTER TABLE `zt_workflowdatasource` ADD `buildin` tinyint(1) unsigned NOT NULL AFTER `valueField`;
ALTER TABLE `zt_workflow` ADD `titleField` varchar(30) NOT NULL AFTER `name`;
ALTER TABLE `zt_workflow` ADD `contentField` text NOT NULL AFTER `titleField`;
ALTER TABLE `zt_workflowaction` ADD `method` varchar(50) NOT NULL AFTER `action`;
ALTER TABLE `zt_workflowlabel` ADD `action` varchar(30) NOT NULL DEFAULT 'browse' after `module`;
ALTER TABLE `zt_workflowrelation` ADD `actionCodes` text NOT NULL AFTER `actions`;
ALTER TABLE `zt_workflowrelation` ADD `buildin` enum('0', '1') NOT NULL DEFAULT '0' AFTER `actionCodes`;

UPDATE `zt_file` AS t1, `zt_workflow` AS t2 SET t1.extra = 'file' WHERE t1.objectType = t2.module AND t1.extra = '' AND t2.type = 'flow' AND t2.buildin = '0';

UPDATE `zt_workflowaction` SET `method` = `action` WHERE `action` IN ('browse', 'create', 'batchcreate', 'edit', 'view', 'delete', 'link', 'unlink', 'export', 'exporttemplate', 'import', 'showimport', 'report');
UPDATE `zt_workflowaction` SET `method` = 'operate' WHERE `method` = '';
UPDATE `zt_workflowdatasource` SET `datasource` = REPLACE(`datasource`, '"value":"50"', '"value":"0"') WHERE `type` = 'system' AND `datasource` LIKE '%"name":"limit"%' AND `datasource` LIKE '%"value":"50"%';

UPDATE `zt_workflowaction` SET `method` = '';
UPDATE `zt_workflowaction` SET `method` = `action` WHERE `action` IN ('browse', 'create', 'batchcreate', 'edit', 'view', 'delete', 'link', 'unlink', 'export', 'exporttemplate', 'import', 'showimport', 'report');
UPDATE `zt_workflowaction` SET `method` = 'create'       WHERE `method` = '' AND `type` = 'single' AND `virtual` = 1;
UPDATE `zt_workflowaction` SET `method` = 'batchcreate'  WHERE `method` = '' AND `type` = 'batch'  AND `virtual` = 1;
UPDATE `zt_workflowaction` SET `method` = 'operate'      WHERE `method` = '' AND `type` = 'single';
UPDATE `zt_workflowaction` SET `method` = 'batchoperate' WHERE `method` = '' AND `type` = 'batch';

ALTER TABLE `zt_feedback` ADD `type` char(30) NOT NULL AFTER `title`;
ALTER TABLE `zt_feedback` ADD `solution` char(30) NOT NULL AFTER `type`;
ALTER TABLE `zt_feedback`
ADD `notifyEmail` varchar(100) COLLATE 'utf8_general_ci' NOT NULL AFTER `notify`,
ADD `feedbackBy` varchar(100) NOT NULL AFTER `assignedDate`;
