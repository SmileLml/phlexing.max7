-- DROP TABLE IF EXISTS `zt_workflowreport`;
CREATE TABLE IF NOT EXISTS `zt_workflowreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('pie', 'line', 'bar') NOT NULL DEFAULT 'pie',
  `countType` enum('sum', 'count') NOT NULL DEFAULT 'sum',
  `displayType` enum('value', 'percent') NOT NULL DEFAULT 'value',
  `dimension` varchar(130) NOT NULL,
  `fields` text NOT NULL,
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY `id` (`id`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_workflowaction` ADD `status` varchar(10) NOT NULL DEFAULT 'enable' AFTER `desc`;
ALTER TABLE `zt_workflowaction` ADD `blocks` text NOT NULL AFTER `toList`;
ALTER TABLE `zt_workflowaction` ADD `virtual` tinyint(1) unsigned NOT NULL AFTER `buildin`;

ALTER TABLE `zt_workflowfield` ADD `expression` text NOT NULL AFTER `control`;
ALTER TABLE `zt_workflowfield` ADD `searchOrder` smallint(5) UNSIGNED NOT NULL DEFAULT '0' AFTER `order`;
ALTER TABLE `zt_workflowfield` ADD `exportOrder` smallint(5) UNSIGNED NOT NULL DEFAULT '0' AFTER `searchOrder`;

ALTER TABLE `zt_workflowlabel` ADD `orderBy` text NOT NULL AFTER `params`;

ALTER TABLE `zt_workflowdatasource` CHANGE `type` `type` enum('system', 'sql', 'func', 'option', 'lang', 'category') NOT NULL DEFAULT 'option';
ALTER TABLE `zt_workflowlayout` CHANGE `totalShow` `summary` varchar(20) NOT NULL;

ALTER TABLE `zt_workflowfield` DROP `isKey`;

UPDATE `zt_workflowfield`  SET `control` = 'richtext' WHERE `control` = 'textarea';
UPDATE `zt_workflowlayout` SET `summary` = ''         WHERE `summary` = '0';
UPDATE `zt_workflowlayout` SET `summary` = 'sum'      WHERE `summary` = '1';

UPDATE `zt_workflowdatasource` SET `datasource` = '{\"app\":\"system\",\"module\":\"execution\",\"method\":\"getPairs\",\"methodDesc\":\"Get project pairs.\",\"params\":[{\"name\":\"mode\",\"type\":\"string\",\"desc\":\"all|noclosed or empty\",\"value\":\"all\"}]}' WHERE `id` = 2;
UPDATE `zt_workflowdatasource` SET `datasource` = '{\"app\":\"system\",\"module\":\"product\",\"method\":\"getLinePairs\",\"methodDesc\":\"Get line pairs.\",\"params\":[{\"name\":\"useShort\",\"type\":\"bool\",\"desc\":\"\",\"value\":\"\"}]}' WHERE `id` = 3;
UPDATE `zt_workflowdatasource` SET `datasource` = '{\"app\":\"system\",\"module\":\"user\",\"method\":\"getPairs\",\"methodDesc\":\"\",\"params\":[{\"name\":\"params\",\"type\":\"\",\"desc\":\"\",\"value\":\"noclosed|noletter\"},{\"name\":\"usersToAppended\",\"type\":\"\",\"desc\":\"\",\"value\":\"\"}]}' WHERE `id` = 8;
