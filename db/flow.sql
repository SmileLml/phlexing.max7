-- DROP TABLE IF EXISTS `zt_workflow`;
CREATE TABLE IF NOT EXISTS `zt_workflow` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent` varchar(30) NOT NULL, 
  `child` varchar(30) NOT NULL, 
  `type` varchar(10) NOT NULL DEFAULT 'flow',
  `app` varchar(20) NOT NULL,
  `position` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `table` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL,
  `titleField` varchar(30) NOT NULL,
  `contentField` text NOT NULL,
  `flowchart` text NOT NULL,
  `js` text NOT NULL,
  `css` text NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `buildin` tinyint(1) unsigned NOT NULL,
  `administrator` text NOT NULL,
  `desc` text NOT NULL,
  `version` varchar(10) NOT NULL DEFAULT '1.0',
  `status` varchar(10) NOT NULL DEFAULT 'wait',
  `approval` enum('enabled', 'disabled') NOT NULL DEFAULT 'disabled',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY `id` (`id`),
  KEY `type` (`type`),
  KEY `app` (`app`),
  KEY `module` (`module`),
  KEY `order` (`order`),
  UNIQUE KEY `unique` (`app`, `module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowaction`;
CREATE TABLE IF NOT EXISTS `zt_workflowaction` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `action` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` enum('single', 'batch') NOT NULL DEFAULT 'single',
  `batchMode` enum('same', 'different') NOT NULL DEFAULT 'different',
  `extensionType` varchar(10) NOT NULL DEFAULT 'override' COMMENT 'none | extend | override',
  `open` varchar(20) NOT NULL,
  `position` enum('menu', 'browseandview', 'browse', 'view') NOT NULL DEFAULT 'browseandview',
  `layout` char(20) NOT NULL,
  `show` enum('dropdownlist', 'direct') NOT NULL DEFAULT 'dropdownlist',
  `order` smallint(5) unsigned NOT NULL,
  `buildin` tinyint(1) unsigned NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'custom',
  `virtual` tinyint(1) unsigned NOT NULL,
  `conditions` text NOT NULL,
  `verifications` text NOT NULL,
  `hooks` text NOT NULL,
  `linkages` text NOT NULL,
  `js` text NOT NULL,
  `css` text NOT NULL,
  `toList` char(255) NOT NULL,
  `blocks` text NOT NULL,
  `desc` text NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'enable',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY `id` (`id`),
  KEY `module` (`module`),
  KEY `action` (`action`),
  KEY `order` (`order`),
  UNIQUE KEY `unique` (`module`, `action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowdatasource`;
CREATE TABLE IF NOT EXISTS `zt_workflowdatasource` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('system', 'sql', 'func', 'option', 'lang', 'category') NOT NULL DEFAULT 'option',
  `name` varchar(30) NOT NULL,
  `code` varchar(30) NOT NULL,
  `datasource` text NOT NULL, 
  `view` varchar(20) NOT NULL,
  `keyField` varchar(255) NOT NULL,
  `valueField` varchar(255) NOT NULL,
  `buildin` tinyint(1) unsigned NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` char(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowfield`;
CREATE TABLE IF NOT EXISTS `zt_workflowfield` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `field`  varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'varchar',
  `length` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `control` varchar(20) NOT NULL,
  `expression` text NOT NULL,
  `options` text NOT NULL,
  `default` varchar(100) NOT NULL,
  `rules` varchar(255) NOT NULL,
  `placeholder` varchar(100) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `searchOrder` smallint(5) unsigned NOT NULL DEFAULT '0', 
  `exportOrder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `canExport` enum('0', '1') NOT NULL DEFAULT '0',
  `canSearch` enum('0', '1') NOT NULL DEFAULT '0',
  `isValue` enum('0', '1') NOT NULL DEFAULT '0',
  `readonly` enum('0', '1') NOT NULL DEFAULT '0',
  `buildin` tinyint(1) unsigned NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'custom',
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY `id` (`id`),
  KEY `module` (`module`),
  KEY `field` (`field`),
  KEY `order` (`order`),
  UNIQUE KEY `unique` (`module`, `field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowui`;
CREATE TABLE IF NOT EXISTS `zt_workflowui` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `action` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL,
  `conditions` text NOT NULL,
  PRIMARY KEY `id` (`id`),
  KEY `module` (`module`),
  KEY `action` (`action`),
  UNIQUE KEY `unique` (`module`, `action`, `name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowlayout`;
CREATE TABLE IF NOT EXISTS `zt_workflowlayout` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `action` varchar(50) NOT NULL,
  `ui` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `field`  varchar(50) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `width` smallint(5) NOT NULL,
  `position` text NOT NULL,
  `readonly` enum('0', '1') NOT NULL DEFAULT '0',
  `mobileShow` enum('0', '1') NOT NULL DEFAULT '1',
  `summary` varchar(20) NOT NULL,
  `defaultValue` text NOT NULL,
  `layoutRules` varchar(255) NOT NULL,
  PRIMARY KEY `id` (`id`),
  KEY `module` (`module`),
  KEY `action` (`action`),
  KEY `order` (`order`),
  UNIQUE KEY `unique` (`module`, `action`, `field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowlabel`;
CREATE TABLE IF NOT EXISTS `zt_workflowlabel` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL DEFAULT 'browse',
  `code` varchar(30) NOT NULL,
  `label` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `orderBy` text NOT NULL,
  `order` tinyint(3) NOT NULL,
  `buildin` tinyint(1) unsigned NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'custom',
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` char(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowlinkdata`;
CREATE TABLE IF NOT EXISTS `zt_workflowlinkdata` (
  `objectType` varchar(30) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `linkedType` varchar(30) NOT NULL,
  `linkedID` mediumint(8) unsigned NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  UNIQUE KEY `unique` (`objectType`, `objectID`, `linkedType`, `linkedID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowrelation`;
CREATE TABLE IF NOT EXISTS `zt_workflowrelation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `prev` varchar(30) NOT NULL, 
  `next` varchar(30) NOT NULL,
  `field` varchar(50) NOT NULL,
  `actions` varchar(20) NOT NULL,
  `actionCodes` text NOT NULL,
  `buildin` enum('0', '1') NOT NULL DEFAULT '0',
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowrelationlayout`;
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

-- DROP TABLE IF EXISTS `zt_workflowrule`;
CREATE TABLE IF NOT EXISTS `zt_workflowrule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('system', 'regex', 'func') NOT NULL DEFAULT 'regex',
  `name` varchar(30) NOT NULL,
  `rule` text NOT NULL, 
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` char(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowsql`;
CREATE TABLE IF NOT EXISTS `zt_workflowsql` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `field` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `sql` text NOT NULL,
  `vars` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY `id` (`id`),
  KEY `module` (`module`),
  KEY `field` (`field`),
  KEY `action` (`action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowversion`;
CREATE TABLE IF NOT EXISTS `zt_workflowversion` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `version` varchar(10) NOT NULL,
  `fields` text NOT NULL,
  `actions` text NOT NULL,
  `layouts` text NOT NULL,
  `sqls` text NOT NULL,
  `labels` text NOT NULL,
  `table` text NOT NULL,
  `datas` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `moduleversion` (`module`, `version`),
  KEY `module` (`module`),
  KEY `version` (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workflowreport`;
CREATE TABLE IF NOT EXISTS `zt_workflowreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL COMMENT 'module name',
  `name` varchar(100) NOT NULL COMMENT 'report name',
  `type` enum('pie', 'line', 'bar') NOT NULL DEFAULT 'pie' COMMENT 'report type',
  `countType` enum('sum', 'count') NOT NULL DEFAULT 'sum' COMMENT 'report count method',
  `displayType` enum('value', 'percent') NOT NULL DEFAULT 'value' COMMENT 'report display method',
  `dimension` varchar(130) NOT NULL COMMENT 'dimension field code of zt_workflowfield',
  `fields` text NOT NULL COMMENT 'count fileds code of zt_workflowfield,use comma split',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_action` CHANGE `action` `action` varchar(80) NOT NULL DEFAULT '';
ALTER TABLE `zt_action` CHANGE `extra` `extra` text;
ALTER TABLE `zt_file` CHANGE `objectType` `objectType` char(30) NOT NULL;

INSERT INTO `zt_workflowrule` VALUES (1,'system','必填','notempty','admin','2020-10-14 14:06:14','','0000-00-00 00:00:00'),(2,'system','唯一','unique','admin','2020-10-14 14:06:14','','0000-00-00 00:00:00'),(3,'system','日期','date','admin','2020-10-14 14:06:14','','0000-00-00 00:00:00'),(4,'system','email','email','admin','2020-10-14 14:06:14','','0000-00-00 00:00:00'),(5,'system','数字','float','admin','2020-10-14 14:06:14','','0000-00-00 00:00:00'),(6,'system','电话','phone','admin','2020-10-14 14:06:14','','0000-00-00 00:00:00'),(7,'system','IP','ip','admin','2020-10-14 14:06:14','','0000-00-00 00:00:00');

REPLACE INTO `zt_grouppriv` VALUES
(1,'apppriv','flow'),
(1,'workflow','activate'),
(1,'workflow','backup'),
(1,'workflow','browseDB'),
(1,'workflow','browseFlow'),
(1,'workflow','copy'),
(1,'workflow','create'),
(1,'workflow','deactivate'),
(1,'workflow','delete'),
(1,'workflow','edit'),
(1,'workflow','flowchart'),
(1,'workflow','setCSS'),
(1,'workflow','setJS'),
(1,'workflow','release'),
(1,'workflow','ui'),
(1,'workflow','upgrade'),
(1,'workflow','view'),
(1,'workflowaction','browse'),
(1,'workflowaction','create'),
(1,'workflowaction','delete'),
(1,'workflowaction','edit'),
(1,'workflowaction','setCSS'),
(1,'workflowaction','setJS'),
(1,'workflowaction','setNotice'),
(1,'workflowaction','setVerification'),
(1,'workflowaction','sort'),
(1,'workflowaction','view'),
(1,'workflowcondition','browse'),
(1,'workflowcondition','create'),
(1,'workflowcondition','delete'),
(1,'workflowcondition','edit'),
(1,'workflowdatasource','browse'),
(1,'workflowdatasource','create'),
(1,'workflowdatasource','delete'),
(1,'workflowdatasource','edit'),
(1,'workflowfield','browse'),
(1,'workflowfield','create'),
(1,'workflowfield','delete'),
(1,'workflowfield','edit'),
(1,'workflowfield','export'),
(1,'workflowfield','exportTemplate'),
(1,'workflowfield','setValue'),
(1,'workflowfield','setExport'),
(1,'workflowfield','setSearch'),
(1,'workflowfield','showImport'),
(1,'workflowfield','sort'),
(1,'workflowfield','import'),
(1,'workflowhook','browse'),
(1,'workflowhook','create'),
(1,'workflowhook','delete'),
(1,'workflowhook','edit'),
(1,'workflowlabel','browse'),
(1,'workflowlabel','create'),
(1,'workflowlabel','delete'),
(1,'workflowlabel','edit'),
(1,'workflowlabel','sort'),
(1,'workflowlayout','admin'),
(1,'workflowlayout','block'),
(1,'workflowlinkage','browse'),
(1,'workflowlinkage','create'),
(1,'workflowlinkage','delete'),
(1,'workflowlinkage','edit'),
(1,'workflowrelation','admin'),
(1,'workflowreport','browse'),
(1,'workflowreport','create'),
(1,'workflowreport','edit'),
(1,'workflowreport','delete'),
(1,'workflowreport','sort'),
(1,'workflowrule','browse'),
(1,'workflowrule','create'),
(1,'workflowrule','delete'),
(1,'workflowrule','edit'),
(1,'workflowrule','view');

ALTER TABLE `zt_workflow` ADD `navigator` varchar(10) NOT NULL AFTER `type`;
ALTER TABLE `zt_workflow` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `status`;
ALTER TABLE `zt_workflowdatasource` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `buildin`;
ALTER TABLE `zt_workflowaction` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `status`;
ALTER TABLE `zt_workflowlayout` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd';

ALTER TABLE `zt_workflow` DROP INDEX `unique`;
ALTER TABLE `zt_workflow` ADD UNIQUE `unique` (`app`, `module`, `vision`);
ALTER TABLE `zt_workflowaction` DROP INDEX `unique`;
ALTER TABLE `zt_workflowaction` ADD UNIQUE `unique` (`module`, `action`, `vision`);
ALTER TABLE `zt_workflowlayout` DROP INDEX `unique`;
ALTER TABLE `zt_workflowlayout` ADD UNIQUE `unique` (`module`, `action`, `field`, `vision`);

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('system',      '产品',           'products',        '1', '{\"app\":\"system\",\"module\":\"product\",\"method\":\"getPairs\",\"methodDesc\":\"Get product pairs.\",\"params\":[{\"name\":\"mode\",\"type\":\"string\",\"desc\":\"\",\"value\":\"all\"}]}',       '',     '',     ''),
('system',      '项目',           'projects',        '1', '{\"app\":\"system\",\"module\":\"project\",\"method\":\"getPairsByModel\",\"methodDesc\":\"Get project pairs by model and project.\",\"params\":[{\"name\":\"model\",\"type\":\"string\",\"desc\":\"all|scrum|waterfall\",\"value\":\"all\"},{\"name\":\"programID\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"param\",\"type\":\"\",\"desc\":\"\",\"value\":\"\"}]}',  '',     '',     ''),
('system',      '产品线',         'productLines',    '1', '{\"app\":\"system\",\"module\":\"product\",\"method\":\"getLinePairs\",\"methodDesc\":\"Get line pairs.\",\"params\":[{\"name\":\"useShort\",\"type\":\"bool\",\"desc\":\"\",\"value\":\"\"}]}',  '',     '',     ''),
('sql',         '需求',           'stories',         '1', 'select id,title from zt_story where deleted=\"0\"',    'view_datasource_4',    'id',   'title'),
('sql',         '任务',           'tasks',           '1', 'select id,name from zt_task where deleted=\"0\" and vision=\"rnd\"',      'view_datasource_5',    'id',   'name'),
('sql',         'Bug',            'bugs',            '1', 'select id,title from zt_bug where deleted=\"0\"',      'view_datasource_6',    'id',   'title'),
('system',      '权限分组',       'groups',          '1', '{\"app\":\"system\",\"module\":\"group\",\"method\":\"getPairs\",\"methodDesc\":\"\",\"params\":[]}',  '',     '',     ''),
('system',      '用户',           'users',           '1', '{\"app\":\"system\",\"module\":\"user\",\"method\":\"getPairs\",\"methodDesc\":\"\",\"params\":[{\"name\":\"params\",\"type\":\"\",\"desc\":\"\",\"value\":\"noclosed|noletter\"},{\"name\":\"usersToAppended\",\"type\":\"\",\"desc\":\"\",\"value\":\"\"}]}',        '',     '',     ''),
('system',      '产品分支',       'branches',        '1', '{\"app\":\"system\",\"module\":\"branch\",\"method\":\"getAllPairs\",\"methodDesc\":\"Get pairs.\",\"params\":[{\"name\":\"params\",\"type\":\"string\",\"desc\":\"\",\"value\":\"\"}]}',      '',     '',     ''),
('sql',         '版本',           'builds',          '1', 'select id,name from zt_build where deleted=\"0\"',     'view_datasource_10',   'id',   'name'),
('sql',         '模块',           'modules',         '1', 'select id,name from zt_module where deleted=\"0\"',    'view_datasource_11',   'id',   'name'),
('sql',         '计划',           'plans',           '1', 'select id,title from zt_productplan where deleted=\"0\"',      'view_datasource_12',   'id',   'title'),
('lang',        '产品类型',       'productType',     '1', 'productType',    '',     '',     ''),
('lang',        '产品状态',       'productStatus',   '1', 'productStatus',  '',     '',     ''),
('lang',        '产品访问控制',   'productAcl',      '1', 'productAcl',     '',     '',     ''),
('lang',        '项目类型',       'projectType',     '1', 'projectType',    '',     '',     ''),
('lang',        '项目状态',       'projectStatus',   '1', 'projectStatus',  '',     '',     ''),
('lang',        '项目访问控制',   'projectAcl',      '1', 'projectAcl',     '',     '',     ''),
('lang',        '发布状态',       'releaseStatus',   '1', 'releaseStatus',  '',     '',     ''),
('lang',        '需求来源',       'storySource',     '1', 'storySource',    '',     '',     ''),
('lang',        '需求优先级',     'storyPri',        '1', 'storyPri',       '',     '',     ''),
('lang',        '需求状态',       'storyStatus',     '1', 'storyStatus',    '',     '',     ''),
('lang',        '需求阶段',       'storyStage',      '1', 'storyStage',     '',     '',     ''),
('lang',        'Bug严重程度',    'bugSeverity',     '1', 'bugSeverity',    '',     '',     ''),
('lang',        'Bug优先级',      'bugPri',          '1', 'bugPri',         '',     '',     ''),
('lang',        'Bug类型',        'bugType',         '1', 'bugType',        '',     '',     ''),
('lang',        'Bug操作系统',    'bugOs',           '1', 'bugOs',          '',     '',     ''),
('lang',        'Bug浏览器',      'bugBrowser',      '1', 'bugBrowser',     '',     '',     ''),
('lang',        'Bug状态',        'bugStatus',       '1', 'bugStatus',      '',     '',     ''),
('lang',        '任务类型',       'taskType',        '1', 'taskType',       '',     '',     ''),
('lang',        '任务优先级',     'taskPri',         '1', 'taskPri',        '',     '',     ''),
('lang',        '任务状态',       'taskStatus',      '1', 'taskStatus',     '',     '',     ''),
('lang',        '测试用例优先级', 'testcasePri',     '1', 'testcasePri',    '',     '',     ''),
('lang',        '测试用例类型',   'testcaseType',    '1', 'testcaseType',   '',     '',     ''),
('lang',        '测试用例阶段',   'testcaseStage',   '1', 'testcaseStage',  '',     '',     ''),
('lang',        '测试用例状态',   'testcaseStatus',  '1', 'testcaseStatus', '',     '',     ''),
('lang',        '测试单优先级',   'testtaskPri',     '1', 'testtaskPri',    '',     '',     ''),
('lang',        '测试单状态',     'testtaskStatus',  '1', 'testtaskStatus', '',     '',     ''),
('lang',        '反馈状态',       'feedbackStatus',  '1', 'feedbackStatus', '',     '',     ''),
('lang',        'Bug解决方案',    'bugResolution',   '1', 'bugResolution',  '',     '',     ''),
('sql',         '用例',           'cases',           '1', 'select id,title from zt_case where deleted=\"0\"',     'view_datasource_41',   'id',   'title'),
('system',      '反馈分支',       'feedbackModules', '1', '{\"app\":\"system\",\"module\":\"tree\",\"method\":\"getOptionMenu\",\"methodDesc\":\"Create an option menu in html.\",\"params\":[{\"name\":\"rootID\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"type\",\"type\":\"string\",\"desc\":\"\",\"value\":\"feedback\"},{\"name\":\"startModule\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"branch\",\"type\":\"\",\"desc\":\"\",\"value\":\"0\"}]}',   '',     '',     ''),
('lang',        '需求类型',       'storyType',       '1', 'storyType',      '',     '',     ''),
('system',	'执行',	          'executions',      '1', '{\"app\":\"system\",\"module\":\"execution\",\"method\":\"getPairs\",\"methodDesc\":\"Get execution pairs.\",\"params\":[{\"name\":\"projectID\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"type\",\"type\":\"string\",\"desc\":\"all|sprint|stage|kanban\",\"value\":\"all\"},{\"name\":\"mode\",\"type\":\"string\",\"desc\":\"all|noclosed|stagefilter or empty\",\"value\":\"\"}]}',  '',     '',     ''),
('lang',        '项目模型',       'projectModel',    '1', 'projectModel',   '',     '',     ''),
('lang',        '反馈类型',       'feedbackType',    '1', 'feedbackType',   '',     '',     ''),
('lang',        '反馈处理方案',   'feedbackSuolution', '1', 'feedbackSuolution',    '',     '',     ''),
('lang',        '反馈关闭原因',   'feedbackclosedReason', '1', 'feedbackclosedReason', '', '', ''),
('lang',        '任务关闭原因',   'taskReason', '1', 'taskReason', '', '', ''),
('lang',        '套件权限',       'testsuiteAuth', '1', 'testsuiteAuth', '', '', ''),
('system',	'项目集',         'programs', '1', '{\"app\":\"system\",\"module\":\"program\",\"method\":\"getPairs\",\"methodDesc\":\"Get program pairs.\",\"params\":[{\"name\":\"isQueryAll\",\"type\":\"bool\",\"desc\":\"\",\"value\":\"\"},{\"name\":\"orderBy\",\"type\":\"string\",\"desc\":\"\",\"value\":\"id_desc\"}]}',  '',     '',     ''),
('lang',        '需求关闭原因',   'storyClosedReason', '1', 'storyClosedReason', '', '', '');

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('system',      '项目',           'liteprojects',          '1', 'lite', '{\"app\":\"system\",\"module\":\"project\",\"method\":\"getPairsByModel\",\"methodDesc\":\"Get project pairs by model and project.\",\"params\":[{\"name\":\"model\",\"type\":\"string\",\"desc\":\"all|scrum|waterfall\",\"value\":\"all\"},{\"name\":\"programID\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"param\",\"type\":\"\",\"desc\":\"\",\"value\":\"\"}]}',  '',     '',     ''),
('sql',         '任务',           'litetasks',             '1', 'lite', 'select id,name from zt_task where deleted=\"0\" and vision=\"lite\"',      'view_datasource_54',    'id',   'name'),
('system',      '权限分组',       'litegroups',            '1', 'lite', '{\"app\":\"system\",\"module\":\"group\",\"method\":\"getPairs\",\"methodDesc\":\"\",\"params\":[]}',  '',     '',     ''),
('system',      '用户',           'liteusers',             '1', 'lite', '{\"app\":\"system\",\"module\":\"user\",\"method\":\"getPairs\",\"methodDesc\":\"\",\"params\":[{\"name\":\"params\",\"type\":\"\",\"desc\":\"\",\"value\":\"noclosed|noletter\"},{\"name\":\"usersToAppended\",\"type\":\"\",\"desc\":\"\",\"value\":\"\"}]}',        '',     '',     ''),
('sql',         '模块',           'litemodules',           '1', 'lite', 'select id,name from zt_module where deleted=\"0\"',    'view_datasource_11',   'id',   'name'),
('lang',        '项目类型',       'liteprojectType',       '1', 'lite', 'projectType',    '',     '',     ''),
('lang',        '项目状态',       'liteprojectStatus',     '1', 'lite', 'projectStatus',  '',     '',     ''),
('lang',        '项目访问控制',   'liteprojectAcl',        '1', 'lite', 'projectAcl',     '',     '',     ''),
('lang',        '任务类型',       'litetaskType',          '1', 'lite', 'taskType',       '',     '',     ''),
('lang',        '任务优先级',     'litetaskPri',           '1', 'lite', 'taskPri',        '',     '',     ''),
('lang',        '任务状态',       'litetaskStatus',        '1', 'lite', 'taskStatus',     '',     '',     ''),
('lang',        '反馈状态',       'litefeedbackStatus',    '1', 'lite', 'feedbackStatus', '',     '',     ''),
('system',      '反馈分支',       'litefeedbackModules',   '1', 'lite', '{\"app\":\"system\",\"module\":\"tree\",\"method\":\"getOptionMenu\",\"methodDesc\":\"Create an option menu in html.\",\"params\":[{\"name\":\"rootID\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"type\",\"type\":\"string\",\"desc\":\"\",\"value\":\"feedback\"},{\"name\":\"startModule\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"branch\",\"type\":\"\",\"desc\":\"\",\"value\":\"0\"}]}',   '',     '',     ''),
('lang',        '反馈类型',       'litefeedbackType',      '1', 'lite', 'feedbackType',   '',     '',     ''),
('lang',        '反馈处理方案',   'litefeedbackSuolution', '1', 'lite', 'feedbackSuolution',    '',     '',     ''),
('lang',        '反馈关闭原因',   'litefeedbackclosedReason', '1', 'lite', 'feedbackclosedReason', '', '', ''),
('lang',        '任务关闭原因',   'litetaskReason',           '1', 'lite', 'taskReason', '', '', '');

DROP VIEW IF EXISTS `view_datasource_4`;
DROP VIEW IF EXISTS `view_datasource_5`;
DROP VIEW IF EXISTS `view_datasource_6`;
DROP VIEW IF EXISTS `view_datasource_10`;
DROP VIEW IF EXISTS `view_datasource_11`;
DROP VIEW IF EXISTS `view_datasource_12`;
DROP VIEW IF EXISTS `view_datasource_41`;
DROP VIEW IF EXISTS `view_datasource_54`;

CREATE VIEW `view_datasource_4`  AS select `id`,`title` from `zt_story` where `deleted` = '0';
CREATE VIEW `view_datasource_5`  AS select `id`,`name` from `zt_task` where `deleted` = '0' and vision = 'rnd';
CREATE VIEW `view_datasource_6`  AS select `id`,`title` from `zt_bug` where `deleted` = '0';
CREATE VIEW `view_datasource_10` AS select `id`,`name` from `zt_build` where `deleted` = '0';
CREATE VIEW `view_datasource_11` AS select `id`,`name` from `zt_module` where `deleted` = '0';
CREATE VIEW `view_datasource_12` AS select `id`,`title` from `zt_productplan` where `deleted` = '0';
CREATE VIEW `view_datasource_41` AS select `id`,`title` from `zt_case` where `deleted` = '0';
CREATE VIEW `view_datasource_54` AS select `id`,`name` from `zt_task` where `deleted` = '0' and vision = 'lite';
